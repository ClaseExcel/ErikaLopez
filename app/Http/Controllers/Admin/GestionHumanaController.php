<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GestionHumanaExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\storeGestionHumanaRequest;
use App\Models\Empresa;
use App\Models\GestionHumana;
use App\Models\GestionHumanaEvento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class GestionHumanaController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $gestion_humana =  GestionHumana::withTrashed()->with('empresa')->select('gestion_humana.*', 'gestion_humana.estado AS estado_usuario');
            return DataTables::of($gestion_humana)
                ->addColumn('actions', function ($gestion_humana) {
                    // Lógica para generar las acciones para cada registro de empleados

                    $evento = '';
                    if (Gate::allows('CREAR_EVENTO_GESTIÓN_HUMANA')) {
                        $evento = '<a href="' . route("admin.gestion-humana-eventos.create", $gestion_humana->id) . '" title="Crear novedad" class="btn-eliminar px-1 py-0">
                    <i class="fa-solid fa-bullhorn"></i>
                    </a>';
                    }

                    return $this->getActionButtons('admin.gestion-humana', 'GESTIÓN_HUMANA', $gestion_humana->id, $gestion_humana->estado) . $evento;
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }


        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::select('razon_social', 'id')->orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::select('razon_social', 'id')->whereJsonContains('empleados', (string)Auth::user()->id)->orderBy('razon_social')->get();
        }

        return view('admin.gestion-humana.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::select('razon_social', 'id')->orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::select('razon_social', 'id')->whereJsonContains('empleados', (string)Auth::user()->id)->orderBy('razon_social')->get();
        }

        $tipos_identificacion = [
            'Cédula de extranjería (CE)',
            'Cédula de ciudadanía (CC)',
            'Documento de identificación extranjero (DIE)',
            'Identificación tributaria de otro país (NE)',
            'Número de Identificación Tributaria CO (NIT)',
            'Pasaporte (PSPT)',
            'Permiso especial permanencia (PEP)',
            'Permiso por protección temporal (PPT)',
            'Registro civil (RC)',
            'Registro Único de Información Fiscal (RIF)',
            'Tarjeta de identidad (TI)',
            'Tarjeta de extranjería (TE)',
        ];

        return view('admin.gestion-humana.create', compact('empresas', 'tipos_identificacion'), ['gestion_humana' => new GestionHumana()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeGestionHumanaRequest $request)
    {

        $contrato = $request->file('document_contrato');
        $cedula = $request->file('documento_ced');
        $certificado = $request->file('certifica_eps');
        $certificadoCcf = $request->file('certifica_ccf');
        $certificadoArl = $request->file('certifica_arl');
        $certificadoAfp = $request->file('certifica_afp');

        if ($contrato) { // Verifica si se ha subido un archivo
            $filename = 'contrato-' . $request->input('cedula') . "." . $contrato->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/contratos-empleados/' . $filename))) {
                unlink(storage_path('app/public/contratos-empleados/' . $filename));
            }

            // Mueve el archivo subido a la ubicación deseada
            $contrato->move(storage_path('app/public/contratos-empleados/'), $filename);
        } else {
            $filename = null;
        }

        if ($cedula) {
            $filename2 = 'cedula-' . $request->input('cedula') . "." . $cedula->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/cedula-empleados/' . $filename2))) {
                unlink(storage_path('app/public/cedula-empleados/' . $filename2));
            }

            // Mueve el archivo subido a la ubicación deseada
            $cedula->move(storage_path('app/public/cedula-empleados/'), $filename2);
        } else {
            $filename2 = null;
        }

        if ($certificado) {
            $filename3 = 'certificado-eps-' . $request->input('cedula') . "." . $certificado->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-eps-empleados/' . $filename3))) {
                unlink(storage_path('app/public/certificado-eps-empleados/' . $filename3));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificado->move(storage_path('app/public/certificado-eps-empleados/'), $filename3);
        } else {
            $filename3 = null;
        }

        if ($certificadoCcf) {
            $filename4 = 'certificado-ccf-' . $request->input('cedula') . "." . $certificadoCcf->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-ccf-empleados/' . $filename4))) {
                unlink(storage_path('app/public/certificado-ccf-empleados/' . $filename4));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoCcf->move(storage_path('app/public/certificado-ccf-empleados/'), $filename4);
        } else {
            $filename4 = null;
        }

        if ($certificadoArl) {
            $filename5 = 'certificado-arl-' . $request->input('cedula') . "." . $certificadoArl->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-arl-empleados/' . $filename5))) {
                unlink(storage_path('app/public/certificado-arl-empleados/' . $filename5));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoArl->move(storage_path('app/public/certificado-arl-empleados/'), $filename5);
        } else {
            $filename5 = null;
        }

        if ($certificadoAfp) {
            $filename6 = 'certificado-afp-' . $request->input('cedula') . "." . $certificadoAfp->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-afp-empleados/' . $filename6))) {
                unlink(storage_path('app/public/certificado-afp-empleados/' . $filename6));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoAfp->move(storage_path('app/public/certificado-afp-empleados/'), $filename6);
        } else {
            $filename6 = null;
        }

        $parts_empresa = explode('-', $request->empresa_id);
        $empresa = trim($parts_empresa[0]);

        $request = $request->merge([
            'documento_cedula' => $filename2,
            'documento_contrato' =>  $filename,
            'certificado_eps' => $filename3,
            'certificado_ccf' => $filename4,
            'certificado_arl' => $filename5,
            'certificado_afp' => $filename6,
            'empresa_id' => $empresa
        ]);

        $gestion_humana = GestionHumana::create($request->all());
        return redirect()->route('admin.gestion-humana.index')->with('message', 'Registro creado exitosamente')->with('color', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);
        $gestion_humana = GestionHumana::withTrashed()->find($id);

        $docList = [];

        if ($gestion_humana->documento_cedula) {
            $docList['file_documento_1'] = 'storage/cedula-empleados/' . $gestion_humana->documento_cedula;
        }
        if ($gestion_humana->certificado_eps) {
            $docList['file_documento_2'] = 'storage/certificado-eps-empleados/' . $gestion_humana->certificado_eps;
        }
        if ($gestion_humana->documento_contrato) {
            $docList['file_documento_3'] = 'storage/contratos-empleados/' .  $gestion_humana->documento_contrato;
        }
        if ($gestion_humana->certificado_ccf) {
            $docList['file_documento_4'] = 'storage/certificado-ccf-empleados/' .  $gestion_humana->certificado_ccf;
        }
        if ($gestion_humana->certificado_arl) {
            $docList['file_documento_5'] = 'storage/certificado-arl-empleados/' .  $gestion_humana->certificado_arl;
        }
        if ($gestion_humana->certificado_afp) {
            $docList['file_documento_6'] = 'storage/certificado-afp-empleados/' .  $gestion_humana->certificado_afp;
        }
        if ($gestion_humana->certificado_retiro_caja) {
            $docList['file_documento_7'] = 'storage/certificado-ccf-empleados/' .  $gestion_humana->certificado_retiro_caja;
        }
        if ($gestion_humana->certificado_retiro_arl) {
            $docList['file_documento_8'] = 'storage/certificado-arl-empleados/' .  $gestion_humana->certificado_retiro_arl;
        }
        if ($gestion_humana->liquidacion) {
            $docList['file_documento_9'] = 'storage/liquidaciones-empleados/' .  $gestion_humana->liquidacion;
        }

        return view('admin.gestion-humana.show', compact('gestion_humana', 'docList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_GESTIÓN_HUMANA'), Response::HTTP_UNAUTHORIZED);
        $gestion_humana = GestionHumana::withTrashed()->find($id);
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::select('razon_social', 'id')->orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::select('razon_social', 'id')->whereJsonContains('empleados', (string)Auth::user()->id)->orderBy('razon_social')->get();
        }

        $tipos_identificacion = [
            'Cédula de extranjería (CE)',
            'Cédula de ciudadanía (CC)',
            'Documento de identificación extranjero (DIE)',
            'Identificación tributaria de otro país (NE)',
            'Número de Identificación Tributaria CO (NIT)',
            'Pasaporte (PSPT)',
            'Permiso especial permanencia (PEP)',
            'Permiso por protección temporal (PPT)',
            'Registro civil (RC)',
            'Registro Único de Información Fiscal (RIF)',
            'Tarjeta de identidad (TI)',
            'Tarjeta de extranjería (TE)',
        ];

        return view('admin.gestion-humana.edit', compact('gestion_humana', 'empresas', 'tipos_identificacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gestion_humana = GestionHumana::withTrashed()->find($id);

        $contrato = $request->file('document_contrato');
        $cedula = $request->file('documento_ced');
        $certificado = $request->file('certifica_eps');
        $certificadoCcf = $request->file('certifica_ccf');
        $certificadoArl = $request->file('certifica_arl');
        $certificadoAfp = $request->file('certifica_afp');

        if ($contrato) { // Verifica si se ha subido un archivo
            $filename = 'contrato-' . $request->input('cedula') . "." . $contrato->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/contratos-empleados/' . $filename))) {
                unlink(storage_path('app/public/contratos-empleados/' . $filename));
            }

            // Mueve el archivo subido a la ubicación deseada
            $contrato->move(storage_path('app/public/contratos-empleados/'), $filename);
        } else {
            $filename = $gestion_humana->documento_contrato;
        }

        if ($cedula) {
            $filename2 = 'cedula-' . $request->input('cedula') . "." . $cedula->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/cedula-empleados/' . $filename2))) {
                unlink(storage_path('app/public/cedula-empleados/' . $filename2));
            }

            // Mueve el archivo subido a la ubicación deseada
            $cedula->move(storage_path('app/public/cedula-empleados/'), $filename2);
        } else {
            $filename2 = $gestion_humana->documento_cedula;
        }

        if ($certificado) {
            $filename3 = 'certificado-eps-' . $request->input('cedula') . "." . $certificado->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-eps-empleados/' . $filename3))) {
                unlink(storage_path('app/public/certificado-eps-empleados/' . $filename3));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificado->move(storage_path('app/public/certificado-eps-empleados/'), $filename3);
        } else {
            $filename3 = $gestion_humana->certificado_eps;
        }

        if ($certificadoCcf) {
            $filename4 = 'certificado-ccf-' . $request->input('cedula') . "." . $certificadoCcf->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-ccf-empleados/' . $filename4))) {
                unlink(storage_path('app/public/certificado-ccf-empleados/' . $filename4));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoCcf->move(storage_path('app/public/certificado-ccf-empleados/'), $filename4);
        } else {
            $filename4 = $gestion_humana->certificado_ccf;
        }

        if ($certificadoArl) {
            $filename5 = 'certificado-arl-' . $request->input('cedula') . "." . $certificadoArl->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-arl-empleados/' . $filename5))) {
                unlink(storage_path('app/public/certificado-arl-empleados/' . $filename5));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoArl->move(storage_path('app/public/certificado-arl-empleados/'), $filename5);
        } else {
            $filename5 = $gestion_humana->certificado_arl;
        }

        if ($certificadoAfp) {
            $filename6 = 'certificado-afp-' . $request->input('cedula') . "." . $certificadoAfp->getClientOriginalExtension();

            // Verifica si el archivo ya existe y lo elimina
            if (file_exists(storage_path('app/public/certificado-afp-empleados/' . $filename6))) {
                unlink(storage_path('app/public/certificado-afp-empleados/' . $filename6));
            }

            // Mueve el archivo subido a la ubicación deseada
            $certificadoAfp->move(storage_path('app/public/certificado-afp-empleados/'), $filename6);
        } else {
            $filename6 = $gestion_humana->certificado_afp;
        }

        $parts_empresa = explode('-', $request->empresa_id);
        $empresa = trim($parts_empresa[0]);

        $request = $request->merge([
            'documento_cedula' => $filename2,
            'documento_contrato' =>  $filename,
            'certificado_eps' => $filename3,
            'certificado_ccf' => $filename4,
            'certificado_arl' => $filename5,
            'certificado_afp' => $filename6,
            'empresa_id' => $empresa
        ]);

        $gestion_humana->update($request->all());

        return redirect()->route('admin.gestion-humana.index')->with('message', 'Registro actualizado exitosamente')->with('color', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = GestionHumana::withTrashed()->find($id);

        //si esta activo inactivar usuario
        if ($user->estado == 'ACTIVO') {
            $user->estado = 'INACTIVO';
            $user->delete();
            $user->save();

            return true;
        }

        $user->estado = 'ACTIVO';
        $user->restore();
        $user->save();

        return true;
    }

    public function exportarGestion(Request $request)
    {
        $cedula = $request->cedula;
        $nombres = $request->nombres;
        $empresa = null;
        if ($request->empresa) {
            $empresa = Empresa::where('razon_social', $request->empresa)->first();
        }

        if ($cedula && $empresa) {
            $gestiones = GestionHumana::withTrashed()->orderBy('nombres')->where('cedula', $cedula)
                ->where('empresa_id', $empresa->id);
        } else if ($cedula) {
            $gestiones = GestionHumana::withTrashed()->orderBy('nombres')->where('cedula', $cedula);
        } else if ($empresa) {
            $gestiones = GestionHumana::withTrashed()->orderBy('nombres')->where('empresa_id', $empresa->id);
        } else if ($nombres) {
            $gestiones = GestionHumana::withTrashed()->orderBy('nombres')->where('nombres', 'like', '%' . $nombres . '%');
        } else {
            $gestiones = GestionHumana::withTrashed()->orderBy('nombres');
        }

        if ($gestiones->get()->count() == 0) {
            if ($request->ajax()) {
                return response()->json(['message' => 'No se encontraron resultados para la búsqueda', 'icon' => 'warning'], 404);
            }
        }

        if ($request->ajax()) {
            $gestionesIds = $gestiones->pluck('id')->toArray();
            $gestionesIdsString = implode(',', $gestionesIds);
            return response()->json(['url' => route('admin.gestion-humana.generar', ['gestiones' => $gestionesIdsString])]);
        }
    }

    public function generarDocumento($gestionesIds)
    {
        return Excel::download(new GestionHumanaExport($gestionesIds), 'exportacion_gestión_humana_' . Carbon::now()->format('d-m-Y_h:s') . '.xlsx');
    }
}
