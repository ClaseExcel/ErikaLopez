<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Requerimiento;
use App\Models\User;
use App\Models\SeguimientoRequerimiento;
use App\Models\EmpleadoCliente;
use App\Models\Documento;
use App\Models\TipoRequerimiento;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateRequerimientoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Mail;
use App\Mail\envioRequerimiento;
use App\Models\Empresa;
use App\Notifications\MessageSent;
use App\Notifications\SolicitudRequerimientoNotification;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use Illuminate\Support\Facades\Log;

class RequerimientoClienteController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        abort_if(Gate::denies('ACCEDER_MIS_REQUERIMIENTOS'), Response::HTTP_UNAUTHORIZED);

        $usuario_cliente = EmpleadoCliente::where('user_id', Auth::user()->id)->first();

        if ($request->ajax()) {

            //Si el rol del usuario es supoer administrador, administrador y contador senior mostrar todas las actividades
            if (Auth::user()->role_id != 7) {
                $requerimientos = SeguimientoRequerimiento::with('requerimientos', 'requerimientos.tipo_requerimientos', 'estado_requerimientos');
            } else {
                $requerimientos = SeguimientoRequerimiento::with('requerimientos', 'requerimientos.tipo_requerimientos', 'estado_requerimientos')
                    ->whereHas('requerimientos', function ($query) use ($usuario_cliente) {
                        $query->where('empleado_cliente_id', $usuario_cliente->id);
                    });
            }

            return DataTables::of($requerimientos)
                ->addColumn('actions', function ($requerimientos) {
                    // Lógica para generar las acciones para cada registro de empleados;

                    $desistir = '';

                    if (Gate::allows('DESISTIR_MIS_REQUERIMIENTOS') && ($requerimientos->estado_requerimiento_id != 5 && $requerimientos->estado_requerimiento_id != 3)) {
                        $desistir = '<a onclick="desistir(' . $requerimientos->requerimiento_id . ')"  title="Desistir requerimiento" class="btn-eliminar">
                                    <i class="fas fa-minus-circle"></i></a>';
                    }

                    return $this->getActionButtons('admin.requerimientos.cliente', 'MIS_REQUERIMIENTOS', $requerimientos->requerimiento_id) . $desistir;
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        return view('admin.requerimiento-cliente.index');
    }


    public function create()
    {
        abort_if(Gate::denies('ACCEDER_SOLICITAR_REQUERIMIENTOS'), Response::HTTP_UNAUTHORIZED);

        $tipo_requerimientos = TipoRequerimiento::orderBy('nombre')->select('nombre', 'id')->get();

        $usuario = Auth::user()->id;
        $admin = Auth::user()->role_id;

        $cliente = EmpleadoCliente::where('user_id', $usuario)->first();

        if ($cliente) {
            $empresas_cliente = json_decode($cliente->empresas_secundarias);

            if ($empresas_cliente) {
                $empresas_cliente = array_merge($empresas_cliente, [$cliente->empresa_id]);
            } else {
                $empresas_cliente =  [$cliente->empresa_id];
            }

            $empresas = Empresa::whereIn('id', $empresas_cliente)->get();
        } else {
            if (Auth::user()->role_id == 1) {
                $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereNotIn('id', [1])->get();
            } else {
                $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereNotIn('id', [1])->whereJsonContains('empleados', (string)Auth::user()->id)->get();
            }
        }

        return view('admin.requerimiento-cliente.create', compact('tipo_requerimientos', 'empresas'), ['requerimiento' => new Requerimiento]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequerimientoRequest $request)
    {
        if (Auth::user()->role_id != 7) {
            $usuario_cliente = $request->empleado_id;
        } else {
            $empleado = EmpleadoCliente::where('user_id', Auth::user()->id)->first();
            $usuario_cliente = $empleado->id;
        }

        if (!$request->empleado_id) {
            session(['message' => 'No se puede crear un requerimientos sin un usuario solicitante.', 'color' => 'danger']);
            return redirect()->route('admin.requerimientos.cliente.create');
        }

        $consecutivo = Requerimiento::generarCodigo();

        $request = $request->merge(['empleado_cliente_id' => $usuario_cliente]);
        $request = $request->merge(['consecutivo' => $consecutivo]);
        $request = $request->merge(['estado_requerimiento_id' => 1]);

        $requerimiento = Requerimiento::create($request->all());

        $request = $request->merge(['requerimiento_id' => $requerimiento->id]);

        SeguimientoRequerimiento::create($request->all());


        if ($request->file('documentos')) {
            $url = storage_path('requerimientos');
            $foldername = uniqid();

            if (!File::exists($url)) {
                File::makeDirectory($url);
            }

            foreach ($request->file('documentos') as $archivo) {
                $archivo->move($url . '/' . $foldername, $archivo->getClientOriginalName());
                $urlPathDocument = 'requerimientos/' . $foldername . '/' . $archivo->getClientOriginalName();

                Documento::create([
                    'documentos' => $urlPathDocument,
                    'requerimiento_id' => $requerimiento->id,
                ]);
            }
        }

        $empresa = Empresa::select('razon_social')->where('id', $request->empresa_id)->first();
        $empleado = EmpleadoCliente::select('nombres', 'apellidos')->where('id', $usuario_cliente)->first();

        $contador_senior = User::where('role_id', 1)->where('estado', 'ACTIVO')->get();

        $errors = [];
        foreach ($contador_senior as  $contador) {
            try {
                $recipient = User::find($contador->id);
                $nombrePlantilla = 'solicitud_requerimiento';

                $data = [
                    'subject' => 'Se ha solicitado un nuevo requerimiento',
                    'notifiable_id' => $contador->id,
                    'actividad_id' =>  $requerimiento->id,
                    'url' => route('admin.requerimientos.cliente.show', $requerimiento->id),
                    'message' => 'Te notificamos que se ha solicitado un requerimiento con el consecutivo registrado <strong>' . $requerimiento->consecutivo . '</strong> para su asignación.',
                ];

                $recipient->notify(new MessageSent($data));

                if ($recipient->numero_contacto) {
                    $numero = $recipient->numero_contacto;
                    $recipient->notify(new SolicitudRequerimientoNotification($requerimiento->consecutivo, $requerimiento->id, $nombrePlantilla, $numero));
                }

                Mail::to($contador->email)->send(new envioRequerimiento(
                    $requerimiento->consecutivo,
                    $requerimiento->tipo_requerimientos->nombre,
                    $empresa->razon_social,
                    $empleado->nombres . ' ' . $empleado->apellidos
                ));
            } catch (\Exception $e) {
                Log::error($e);
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            session(['message' => 'El requerimiento ha sido solicitado exitosamente, pero hubo problemas al enviar algunas notificaciones.', 'color' => 'warning']);
            if (Auth::user()->role_id != 7) {
                return redirect()->route('admin.requerimientos.cliente.create');
            } else {
                return redirect()->route('admin.requerimientos.cliente.index');
            }
        }

        session(['message' => 'Requerimiento solicitado exitosamente.', 'color' => 'success']);
        if (Auth::user()->role_id != 7) {
            return redirect()->route('admin.requerimientos.cliente.create');
        } else {
            return redirect()->route('admin.requerimientos.cliente.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('ACCEDER_SOLICITAR_REQUERIMIENTOS'), Response::HTTP_UNAUTHORIZED);

        $requerimiento = Requerimiento::find($id);
        $seguimiento_requerimiento = SeguimientoRequerimiento::where('requerimiento_id', $id)->first();

        $documento = Documento::where('requerimiento_id', $id)
            ->where('documentos', 'not like', 'storage/seguimiento_requerimiento%')
            ->first();

        $documentos_seguimientos = Documento::where('requerimiento_id', $id)
            ->where('documentos', 'like', 'storage/seguimiento_requerimiento%')
            ->get();

        $docList = [];

        $index = 2;

        if ($seguimiento_requerimiento->documento) {
            $docList['file_documento_1'] = $seguimiento_requerimiento->documento;
        }

        if ($documentos_seguimientos) {
            foreach ($documentos_seguimientos as $documento_seguimiento) {
                $docList['file_documento_' . $index++] = $documento_seguimiento->documentos;
            }
        }

        return view('admin.requerimiento-cliente.show', compact('requerimiento', 'seguimiento_requerimiento', 'docList', 'documento'));
    }

    public function download($id)
    {
        $documento = Documento::where('requerimiento_id', $id)
            ->where('documentos', 'not like', 'requerimientos/documentos-seguimiento%')
            ->first();

        $carpeta = storage_path(File::dirname($documento->documentos)); // Ruta a la carpeta que quieres comprimir
        $archivoZip = storage_path('requerimientos/' . uniqid() . '.zip'); // Ruta donde se almacenará el archivo ZIP

        $nombreCarpeta = dirname($documento->documentos);

        if ($nombreCarpeta != 'requerimientos/documentos-seguimiento') {
            $zip = new ZipArchive();
            if ($zip->open($archivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $archivos = File::files($carpeta);

                foreach ($archivos as $archivo) {
                    $nombreArchivo = pathinfo($archivo, PATHINFO_BASENAME);
                    $zip->addFile($archivo, $nombreArchivo);
                }

                $zip->close();

                return response()->download($archivoZip)->deleteFileAfterSend(true);
            }

            return redirect()->back()->with('message', 'Error al crear el archivo ZIP.')->with('color', 'danger');
        }
    }

    public function desistir($id)
    {

        $requerimiento = SeguimientoRequerimiento::where('requerimiento_id', $id)->first();

        $requerimiento->update([
            'estado_requerimiento_id' => 6
        ]);

        return true;
    }


    /**
     * Encuentra los clientes según la empresa principal o secundaria 
     */
    public function findEmpleados($id)
    {

        $empleados_cliente = EmpleadoCliente::orderBy('nombres')->select('id', 'nombres', 'apellidos')
            ->where(function ($query) use ($id) {
                $query->where('empresa_id', $id)
                    ->orWhereJsonContains('empresas_secundarias', $id);
            })
            ->whereHas('usuarios', function ($query) {
                $query->where('estado', 'ACTIVO');
            })
            ->get()->toJson();

        return $empleados_cliente;
    }
}
