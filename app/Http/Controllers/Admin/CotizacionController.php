<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCotizacionRequest;
use App\Models\Cotizacion;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;


use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\SeguimientoCotizacionRequest;
use App\Http\Requests\UpdateCotizacionRequest;
use App\Mail\SeguimientoCotizacionMail;
use App\Models\Seguimiento;
use App\Models\SeguimientoCotizacion;
use App\Notifications\MessageSent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_COTIZACIONES'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            if (in_array(Auth::user()->role_id, [1, 2, 8])) {
                $cotizaciones =  Cotizacion::with('responsable')->orderBy('fecha_envio', 'DESC')->select('cotizaciones.*');
            } else {
                $cotizaciones =  Cotizacion::with('responsable')->where('responsable_id', Auth::user()->id)->orderBy('fecha_envio', 'DESC')->select('cotizaciones.*');
            }
            return DataTables::of($cotizaciones)
                ->addColumn('fecha_proximo_seguimiento', function ($cotizaciones) {
                    $fecha_proximo_seguimiento = SeguimientoCotizacion::where('cotizacion_id', $cotizaciones->id)->get()->last();

                    if ($fecha_proximo_seguimiento) {
                        $fecha = $fecha_proximo_seguimiento->fecha_proximo_seguimiento;
                    } else {
                        $fecha = $cotizaciones->fecha_primer_seguimiento;
                    }
                    return $fecha;
                })->addColumn('valor_servicio', function ($cotizaciones) {
                    $clientes = Cotizacion::select('cliente')->where('cliente', $cotizaciones->cliente)->get();

                    foreach ($clientes as  $cliente) {
                        $precios = Cotizacion::where('cliente', $cliente->cliente)->select('precio_venta')->get();
                        $total_precio = $precios->sum('precio_venta');  

                        return $total_precio;
                    }
                })
                ->addColumn('actions', function ($cotizaciones) {
                    // Lógica para generar las acciones para cada registro de agenda empresas
                    $reportar = '<a href="' . route("admin.cotizacion-seguimiento.index", $cotizaciones->id) . '" title="Reportar seguimiento" class="btn-eliminar px-2">
                    <i class="fa-solid fa-clipboard"></i>
                    </a>';
                    return $this->getActionButtons('admin.cotizaciones', 'COTIZACIONES', $cotizaciones->id) . $reportar;
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        // $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        // if (in_array(Auth::user()->role_id, [1, 2])) {
        //     $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        // }

        $empresas = Cotizacion::groupBy('cliente')->select('cliente')->get();

        $responsables = User::orderBy('nombres')->select('nombres', 'apellidos', 'id')->where('estado', 'ACTIVO')->get();
        $fechaEnvioInicial = Cotizacion::select('fecha_envio')->orderBy('fecha_envio', 'ASC')->get()->first();
        $fechaEnvioFinal = Cotizacion::select('fecha_envio')->orderBy('fecha_envio', 'DESC')->get()->first();
        $estados = ['Enviado', 'En estudio', 'Aprobado', 'Rechazado'];

        return view('admin.cotizaciones.index', compact('empresas', 'responsables', 'fechaEnvioInicial', 'fechaEnvioFinal', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_COTIZACIONES'), Response::HTTP_UNAUTHORIZED);

        $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (in_array(Auth::user()->role_id, [1, 2])) {
            $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }

        $cotizaciones = Cotizacion::select('numero_cotizacion')->get()->last();
        $responsables = User::orderBy('nombres')->select('nombres', 'apellidos', 'id')->where('estado', 'ACTIVO')->get();

        return view('admin.cotizaciones.create', compact('clientes', 'responsables', 'cotizaciones'), ['cotizacion' => new Cotizacion()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCotizacionRequest $request)
    {

        $request = $request->merge(['estado_cotizacion' => 'Enviado']);
        $documento_cotizacion = null;

        if ($request->file('documento_adjunto')) {
            $foldername = 'cotizacion_' . $request->numero_cotizacion;
            $archivo = $request->file('documento_adjunto');

            $documento = $archivo->getClientOriginalName();
            $documento_cotizacion = 'storage/cotizacion/' . $foldername . '/' . $archivo->getClientOriginalName();

            Storage::disk('cotizacion')->put($foldername . '/' . $documento, File::get($archivo));
        }

        $request = $request->merge(['cotizacion_adjunta' => $documento_cotizacion]);

        $cotizacion = Cotizacion::create($request->all());

        return redirect()->route('admin.cotizaciones.index')->with('message', 'La cotización se ha creado correctamente.')->with('color', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_COTIZACIONES'), Response::HTTP_UNAUTHORIZED);
        $cotizacion = Cotizacion::find($id);
        $seguimientos = SeguimientoCotizacion::where('cotizacion_id', $id)->get();
        $fecha_proximo_seguimiento = SeguimientoCotizacion::where('cotizacion_id', $id)->get()->last();

        $docList = [];

        if ($cotizacion->cotizacion_adjunta) {
            $docList['file_documento_1'] = $cotizacion->cotizacion_adjunta;
        }

        return view('admin.cotizaciones.show', compact('cotizacion', 'docList', 'seguimientos', 'fecha_proximo_seguimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_COTIZACIONES'), Response::HTTP_UNAUTHORIZED);

        $cotizacion = Cotizacion::find($id);
        $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (in_array(Auth::user()->role_id, [1, 2])) {
            $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }

        $responsables = User::orderBy('nombres')->select('nombres', 'apellidos', 'id')->where('estado', 'ACTIVO')->get();

        return view('admin.cotizaciones.edit', compact('clientes', 'responsables', 'cotizacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCotizacionRequest $request, $id)
    {
        $cotizacion = Cotizacion::find($id);

        if ($request->file('documento_adjunto')) {
            $foldername = 'cotizacion_' . $request->numero_cotizacion;
            $archivo = $request->file('documento_adjunto');

            if (File::exists($cotizacion->cotizacion_adjunta)) {
                File::delete($cotizacion->cotizacion_adjunta);
            }

            $documento = $archivo->getClientOriginalName();
            $documento_cotizacion = 'storage/cotizacion/' . $foldername . '/' . $archivo->getClientOriginalName();

            Storage::disk('cotizacion')->put($foldername . '/' . $documento, File::get($archivo));
        } else {
            $documento_cotizacion = $cotizacion->cotizacion_adjunta;
        }

        $request = $request->merge(['cotizacion_adjunta' => $documento_cotizacion]);

        $cotizacion->update($request->all());

        return redirect()->route('admin.cotizaciones.index')->with('message', 'La cotización se ha editado correctamente.')->with('color', 'success');
    }

    /**
     * Trae la información del contacto de la empresa
     *
     * @param  int  $cliente
     */
    public function showInformacionEmpresa($cliente)
    {
        $informacion = [];
        $empresa = Empresa::find($cliente);

        $informacion = [
            'numero_contacto' => $empresa->telefono_contacto,
            'nombre_contacto' => $empresa->nombre_contacto,
        ];

        return json_encode($informacion);
    }

    /**
     * Muestra la vista para crear un seguimiento de la cotización
     *
     * @param  int  $id
     */
    public function showSeguimiento($id)
    {

        $cotizacion = Cotizacion::find($id);
        $estados = ['En estudio', 'Aprobado', 'Rechazado'];

        return view('admin.cotizaciones.seguimiento.create', compact('estados', 'cotizacion'));
    }


    /**
     * Crea el seguimiento de la cotización,
     * el id es la cotización
     * @param  int  $id
     */
    public function storeSeguimiento(SeguimientoCotizacionRequest $request, $id)
    {

        //Guarda el seguimiento de la cotización
        $request = $request->merge(['fecha_seguimiento' => Carbon::now()]);
        $seguimiento = Seguimiento::create($request->all());

        //Toma el seguimiento id y la cotización id para crear un seguimiento con la fecha proxima y observacion del seguimiento si estas se ingresan
        $request = $request->merge(['seguimiento_id' => $seguimiento->id]);
        $request = $request->merge(['cotizacion_id' => $id]);

        SeguimientoCotizacion::create($request->all());

        $cotizacion = Cotizacion::find($id);
        $cotizacion->estado_cotizacion = $request->estado_cotizacion;
        $cotizacion->save();

        return redirect()->route('admin.cotizaciones.index')->with('message', 'El seguimiento se ha creado correctamente.')->with('color', 'success');
    }

    /**
     * Muestra la vista para editar un seguimiento de la cotización
     *
     * @param  int  $id
     */
    public function editSeguimiento($id, $cotizacion_id)
    {
        $seguimiento = Seguimiento::find($id);
        $seguimiento_cotizacion = SeguimientoCotizacion::where('seguimiento_id', $id)->first();
        $cotizacion = Cotizacion::find($cotizacion_id);

        return view('admin.cotizaciones.seguimiento.edit', compact('seguimiento',  'cotizacion', 'seguimiento_cotizacion'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSeguimiento(Request $request, $id)
    {
        $seguimiento = Seguimiento::find($id);
        $seguimiento_cotizacion = SeguimientoCotizacion::where('seguimiento_id', $id)->first();

        $seguimiento->update($request->all());
        $seguimiento_cotizacion->update($request->all());

        return redirect()->route('admin.cotizaciones.show', $request->cotizacion_id)->with('message', 'La cotización se ha editado correctamente.')->with('color', 'success');
    }


    public function emailSeguimiento()
    {
        $cotizaciones = Cotizacion::all();
        $currentDay = Carbon::now()->format('Y-m-d');

        foreach ($cotizaciones as $cotizacion) {
            $seguimiento = SeguimientoCotizacion::where('cotizacion_id', $cotizacion->id)->get();
            $recipient = User::find($cotizacion->responsable->id);

            //Si ya tiene un seguimiento registrado toma el ultimo que tenga la fecha del proximo seguimiento y lo compara con la fecha de hoy
            if (count($seguimiento) > 0) {
                $ultimo_seguimiento = $seguimiento->last();

                if ($ultimo_seguimiento->fecha_proximo_seguimiento == $currentDay) {
                    //aqui se manda el email
                    Mail::to($cotizacion->responsable->email)->send(new SeguimientoCotizacionMail(
                        $cotizacion->cliente,
                        $cotizacion->numero_cotizacion,
                        $ultimo_seguimiento->observacion_proximo_seguimiento,
                        $cotizacion->responsable->nombres . ' ' . $cotizacion->responsable->apellidos,
                        Carbon::parse($ultimo_seguimiento->fecha_proximo_seguimiento)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
                    ));

                    $mensaje = 'Queremos recordarle que el día de hoy ' . $ultimo_seguimiento->fecha_proximo_seguimiento . ' se cumple la fecha para realizar el seguimiento al cliente  <b>' . $cotizacion->cliente . '</b>, con el número de seguimiento <b>#' . $cotizacion->numero_cotizacion . '</b>. Para que des su respectiva revisión y registro.';

                    $data = [
                        'subject' => 'Recordatorio de seguimiento de la cotización #' . $cotizacion->numero_cotizacion,
                        'notifiable_id' => $cotizacion->responsable->id,
                        'actividad_id' =>  $cotizacion->id,
                        'url' => route('admin.cotizaciones.show', $cotizacion->id),
                        'message' => $mensaje,
                    ];

                    $recipient->notify(new MessageSent($data));
                }
            } else {
                if ($cotizacion->fecha_primer_seguimiento == $currentDay) {
                    //aqui se manda el email
                    Mail::to($cotizacion->responsable->email)->send(new SeguimientoCotizacionMail(
                        $cotizacion->cliente,
                        $cotizacion->numero_cotizacion,
                        $cotizacion->observacion_primer_seguimiento,
                        $cotizacion->responsable->nombres . ' ' . $cotizacion->responsable->apellidos,
                        Carbon::parse($cotizacion->fecha_primer_seguimiento)->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
                    ));

                    $mensaje = 'Queremos recordarle que el día de hoy ' . $cotizacion->fecha_primer_seguimiento . ' se cumple la fecha para realizar el seguimiento al cliente  <b>' . $cotizacion->cliente . '</b>, con el número de seguimiento <b>#' . $cotizacion->numero_cotizacion . '</b>. Para que des su respectiva revisión y registro.';

                    $data = [
                        'subject' => 'Recordatorio de seguimiento de la cotización #' . $cotizacion->numero_cotizacion,
                        'notifiable_id' => $cotizacion->responsable->id,
                        'actividad_id' =>  $cotizacion->id,
                        'url' => route('admin.cotizaciones.show', $cotizacion->id),
                        'message' => $mensaje,
                    ];

                    $recipient->notify(new MessageSent($data));
                }
            }
        }
    }
}
