<?php

namespace App\Http\Controllers\admin;

use App\Exports\ActividadClienteExport;
use App\Exports\InformeActividadesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateActividadClienteRequest;
use App\Jobs\ExcelActividadClienteBatchImport;
use App\Mail\NotificacionActividades;
use App\Models\Actividad;
use App\Models\ActividadCliente;
use App\Models\ReporteActividad;
use App\Models\EstadoActividad;
use App\Models\Empresa;
use App\Models\EmpleadoCliente;
use App\Models\Festivo;
use App\Models\Responsable;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\UpdateReporteActividad;
use App\Mail\notificacionAsignacionActividad;
use App\Models\DocumentoActividad;
use App\Models\HistorialActividades;
use App\Notifications\ActividadNotification;
use App\Notifications\MessageSent;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ActividadClienteController extends Controller
{

    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_ACTIVIDADES'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {

            //Si el rol del usuario es supoer administrador, administrador y contador senior mostrar todas las actividades
            if (Auth::user()->role_id == 1) {
                $actividades =  ActividadCliente::with(
                    'reporte_actividades',
                    'actividad',
                    'cliente',
                    'usuario',
                    'empresa_asociada',
                    'reporte_actividades.estado_actividades'
                )
                    ->whereHas('usuario', function ($query) {
                        $query->where('estado', 'ACTIVO');
                    })
                    ->select(
                        'actividad_cliente.*',
                    );
                //Si no, se muestran las actividades asignadas
            } else {
                $actividades =  ActividadCliente::with(
                    'reporte_actividades',
                    'actividad',
                    'cliente',
                    'usuario',
                    'empresa_asociada',
                    'reporte_actividades.estado_actividades'
                )
                    ->select('actividad_cliente.*')
                    ->whereHas('usuario', function ($query) {
                        $query->where('estado', 'ACTIVO');
                    })
                    ->where(function ($query) {
                        $query->where('usuario_id', Auth::user()->id)
                            ->orWhere('user_crea_act_id', Auth::user()->id);
                    });
            }

            return DataTables::of($actividades)
                ->addColumn('actions', function ($actividades) {
                    // Lógica para generar las acciones para cada registro de empleados
                    if (Auth::user()->role_id == 1) {
                        $reasignar = '<a href="' . route('admin.reporte.reasignar', $actividades->id) . '" title="Reasignar registro" class="btn-editar px-2 py-0">
                        <i class="fa-solid fa-user-pen"></i>
                        </a>';
                    } else {
                        $reasignar = '';
                    }

                    if ($actividades->usuario_id != Auth::user()->id) {
                        $reasignar = '<a href="' . route('admin.reporte.reasignar', $actividades->id) . '" title="Reasignar registro" class="btn-editar px-2 py-0">
                        <i class="fa-solid fa-user-pen"></i>
                        </a>';
                    }

                    $reportar = '<a href="' . route("admin.reporte.index", $actividades->id) . '" title="Reportar registro" class="btn-eliminar px-2 py-0">
                    <i class="fas fa-file-alt"></i>
                    </a>';

                    if ($actividades->reporte_actividades->estado_actividad_id == 4 || $actividades->reporte_actividades->estado_actividad_id == 7 || $actividades->reporte_actividades->estado_actividad_id == 8) {
                        $reasignar = '';
                        $reportar = '';
                    }

                    return $this->getActionButtons('admin.actividad_cliente', 'ACTIVIDADES', $actividades->id) . $reportar . $reasignar;
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        $estados = EstadoActividad::where('id', '!=', '5')->get();

        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }

        //seleccionar los responsables de las actividades
        $listaResponsables =  ActividadCliente::whereHas('usuario', function ($query) {
            $query->where('estado', 'ACTIVO');
        })->pluck('usuario_id')->unique()->toArray();

        $responsables = User::orderBy('nombres')->select('id', 'nombres', 'apellidos')->whereIn('id', $listaResponsables)->get();

        $fechaVencimientoInicial = ActividadCliente::select('fecha_vencimiento')->orderBy('fecha_vencimiento', 'ASC')->get()->first();
        $fechaVencimientoFinal = ActividadCliente::select('fecha_vencimiento')->orderBy('fecha_vencimiento', 'DESC')->get()->first();


        return view('admin.actividadcliente.index', compact('estados', 'empresas', 'responsables', 'fechaVencimientoInicial', 'fechaVencimientoFinal'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_ACTIVIDADES'), Response::HTTP_UNAUTHORIZED);

        $tipo_actividades = Actividad::select('nombre', 'id')->orderBy('nombre')->get();
        $responsable = Responsable::select('nombre', 'id')->orderBy('nombre')->get();
        $usuarios = User::select('id', 'nombres', 'apellidos')->where('estado', 'ACTIVO')->orderBy('nombres')->get();
        $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (Auth::user()->role_id == 1) {
            $clientes = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }

        return view(
            'admin.actividadcliente.create',
            compact('tipo_actividades', 'responsable', 'clientes', 'usuarios'),
            ['actividad_cliente' => new ActividadCliente()]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateActividadClienteRequest $request)
    {

        $parts_cliente = explode('-', $request->cliente_id);
        $cliente = trim($parts_cliente[0]);

        $parts_usuario = explode('-', $request->usuario_id);
        $usuario = trim($parts_usuario[0]);

        $empresa = null;

        if ($request->empresa_asociada_id) {
            $parts_empresa = explode('-', $request->empresa_asociada_id);
            $empresa_asociada = trim($parts_empresa[0]);
            $empresa = $empresa_asociada;
        } else if ($cliente == 1 && $request->empresa_asociada_id == null) {
            $empresa = 1;
        }

        $request = $request->merge(['cliente_id' => $cliente]);
        $request = $request->merge(['usuario_id' => $usuario]);
        $request = $request->merge(['empresa_asociada_id' => $empresa]);

        //si esta ingresada la periodicidad y fecha de corte repetir las actividades
        if ($request['recurrencia'] && $request['periodicidad'] && $request['fecha_corte_periocidad']) {

            if ($request['fecha_corte_periocidad'] <= $request['fecha_vencimiento']) {
                return redirect()->back()->withInput()->with('message', 'La fecha final de periodicidad no puede ser menor o igual a la fecha de vencimiento')->with('color', 'danger');
            }

            // * Ruta base para los archivos
            $fileBasePath = storage_path('app/public/data/actividadcliente');
            // * Define inputs y columnas
            $documents = [
                'documento_1' => 'file_documento_1',
                'documento_2' => 'file_documento_2',
                'documento_3' => 'file_documento_3',
                'documento_4' => 'file_documento_4',
                'documento_5' => 'file_documento_5',
            ];

            // * Valida documentos
            foreach ($documents as $documentInput => $documentPath) {
                $this->load_file_create($request, $documentInput, $documentPath, $fileBasePath);
            }

            $startDate = new Carbon($request['fecha_vencimiento']);
            $endDate = new Carbon($request['fecha_corte_periocidad']);
            $recurrenceType = $request['recurrencia'];
            $recurrenceInterval = $request['periodicidad'] ?? 1;

            $fechasRecurrencia = [];
            $currentDate = $startDate->copy();
            $diaDeseado = $currentDate->day;

            // Solo agregar la fecha si está dentro del rango permitido

            while ($currentDate->lte($endDate)) {
                $fechasRecurrencia[] = [$currentDate->copy()->format('Y-m-d')];

                switch ($recurrenceType) {
                    case 'Mensualmente':
                        $currentDate->addMonthsNoOverflow($recurrenceInterval);
                        $diasEnMes = $currentDate->daysInMonth;

                        // Forzar al día original si existe, si no, al último día del mes
                        $currentDate->day(min($diaDeseado, $diasEnMes));
                        break;

                    case 'Diario':
                        $currentDate->addDay($recurrenceInterval);
                        break;

                    case 'Semanalmente':
                        $currentDate->addWeek($recurrenceInterval);
                        break;
                }

                if ($currentDate->gt($endDate)) {
                    break;
                }
            }

            // Extraer solo la primera fecha de cada subarray y crear un array plano de fechas
            $listaFechas = array_map(function ($fechas) {
                return $fechas[0];
            }, $fechasRecurrencia);

            sort($listaFechas);

            //Recorrer el periodo de fechas y guardar las actividades
            $errors = [];
            foreach ($listaFechas as $fecha) {
                try {
                    $reporteActividad = ReporteActividad::create([
                        'estado_actividad_id' => 1,
                    ]);

                    $request = $request->merge(['fecha_vencimiento' => Carbon::parse($fecha)->format('Y-m-d')]);
                    $request = $request->merge(['progreso' => 0]);
                    $request = $request->merge(['empresa_asociada_id' => $empresa]);
                    $request = $request->merge(['reporte_actividad_id' => $reporteActividad->id]);
                    $request = $request->merge(['user_crea_act_id' =>  Auth::user()->id]);

                    // * Crea la actividad
                    $actividadCliente = ActividadCliente::create($request->all());

                    $recipient = User::withTrashed()->find($actividadCliente->usuario_id);
                    $numero = $recipient->numero_contacto ? $recipient->numero_contacto : '0000000000';
                    $nombrePlantilla = 'actividad_asignada';

                    if ($recipient->estado == "ACTIVO") {
                        $data = [
                            'subject' => 'Se te ha asignado una nueva actividad',
                            'notifiable_id' => $recipient->id,
                            'actividad_id' =>  $actividadCliente->id,
                            'url' => route('admin.actividad_cliente.show', $actividadCliente->id),
                            'message' => 'Te notificamos que se te ha asignado una nueva actividad con el nombre registrado <strong>' . $request->nombre . '</strong> y número de ID <strong>' . $actividadCliente->id . '</strong> para que le des su respectiva revisión.',
                        ];

                        $docList = [];

                        if ($request->file_documento_1) {
                            $docList[] = 'storage/data/actividadcliente/file_documento_1/' . $request->file_documento_1;
                        }
                        if ($request->file_documento_2) {
                            $docList[] = 'storage/data/actividadcliente/file_documento_2/' . $request->file_documento_2;
                        }
                        if ($request->file_documento_3) {
                            $docList[] = 'storage/data/actividadcliente/file_documento_3/' .  $request->file_documento_3;
                        }
                        if ($request->file_documento_4) {
                            $docList[] = 'storage/data/actividadcliente/file_documento_4/' .  $request->file_documento_4;
                        }
                        if ($request->file_documento_5) {
                            $docList[] = 'storage/data/actividadcliente/file_documento_5/' . $request->file_documento_5;
                        }

                        Mail::to($actividadCliente->usuario->email)->send(new notificacionAsignacionActividad($actividadCliente->nombre, $actividadCliente->cliente->razon_social, $actividadCliente->fecha_vencimiento, $docList));

                        $recipient->notify(new MessageSent($data));
                        $recipient->notify(new ActividadNotification($request->nombre, $actividadCliente->id, $nombrePlantilla, $numero));

                        // * Actualiza columnas de documentos
                        $columnsToUpdate = ['file_documento_1', 'file_documento_2', 'file_documento_3', 'file_documento_4', 'file_documento_5'];
                        ActividadCliente::where('id', $actividadCliente->id)->update($request->only($columnsToUpdate));
                    }
                } catch (\Exception $e) {
                    Log::error($e);
                    $errors[] = $e->getMessage();
                }
            }

            if (!empty($errors)) {
                session(['message' => 'Las actividades han sido creadas exitosamente, pero hubo problemas al enviar algunas notificaciones.', 'color' => 'warning']);
                return redirect()->route('admin.actividad_cliente.index');
            }

            session(['message' => 'La actividad se ha creado correctamente.', 'color' => 'success']);
            return redirect()->route('admin.actividad_cliente.index');
        } else {
            $reporteActividad = ReporteActividad::create([
                'estado_actividad_id' => 1,
            ]);

            $request = $request->merge(['progreso' => 0]);
            $request = $request->merge(['empresa_asociada_id' => $empresa]);
            $request = $request->merge(['reporte_actividad_id' => $reporteActividad->id]);
            $request = $request->merge(['user_crea_act_id' =>  Auth::user()->id]);

            // * Crea la actividad
            $actividadCliente = ActividadCliente::create($request->all());

            // * Ruta base para los archivos
            $fileBasePath = storage_path('app/public/data/actividadcliente');
            // * Define inputs y columnas
            $documents = [
                'documento_1' => 'file_documento_1',
                'documento_2' => 'file_documento_2',
                'documento_3' => 'file_documento_3',
                'documento_4' => 'file_documento_4',
                'documento_5' => 'file_documento_5',
            ];

            // * Valida documentos
            foreach ($documents as $documentInput => $documentPath) {
                $this->load_file_create($request, $documentInput, $documentPath, $fileBasePath);
            }
            // * Actualiza columnas de documentos
            $columnsToUpdate = ['file_documento_1', 'file_documento_2', 'file_documento_3', 'file_documento_4', 'file_documento_5'];
            ActividadCliente::where('id', $actividadCliente->id)->update($request->only($columnsToUpdate));

            $recipient = User::withTrashed()->find($actividadCliente->usuario_id);
            $numero = $recipient->numero_contacto ? $recipient->numero_contacto : '0000000000';
            $nombrePlantilla = 'actividad_asignada';

            try {
                if ($recipient->estado == "ACTIVO") {
                    $data = [
                        'subject' => 'Se te ha asignado una nueva actividad',
                        'notifiable_id' => $recipient->id,
                        'actividad_id' =>  $actividadCliente->id,
                        'url' => route('admin.actividad_cliente.show', $actividadCliente->id),
                        'message' => 'Te notificamos que se te ha asignado una nueva actividad con el nombre registrado <strong>' . $request->nombre . '</strong> y número de ID <strong>' . $actividadCliente->id . '</strong> para que le des su respectiva revisión.',
                    ];

                    $docList = [];

                    if ($request->file_documento_1) {
                        $docList[] = 'storage/data/actividadcliente/file_documento_1/' . $request->file_documento_1;
                    }
                    if ($request->file_documento_2) {
                        $docList[] = 'storage/data/actividadcliente/file_documento_2/' . $request->file_documento_2;
                    }
                    if ($request->file_documento_3) {
                        $docList[] = 'storage/data/actividadcliente/file_documento_3/' .  $request->file_documento_3;
                    }
                    if ($request->file_documento_4) {
                        $docList[] = 'storage/data/actividadcliente/file_documento_4/' .  $request->file_documento_4;
                    }
                    if ($request->file_documento_5) {
                        $docList[] = 'storage/data/actividadcliente/file_documento_5/' . $request->file_documento_5;
                    }

                    Mail::to($actividadCliente->usuario->email)->send(new notificacionAsignacionActividad($actividadCliente->nombre, $actividadCliente->cliente->razon_social, $actividadCliente->fecha_vencimiento, $docList));

                    $recipient->notify(new MessageSent($data));
                    $recipient->notify(new ActividadNotification($request->nombre, $actividadCliente->id, $nombrePlantilla, $numero));

                    // * Actualiza columnas de documentos
                    $columnsToUpdate = ['file_documento_1', 'file_documento_2', 'file_documento_3', 'file_documento_4', 'file_documento_5'];
                    ActividadCliente::where('id', $actividadCliente->id)->update($request->only($columnsToUpdate));
                }

                session(['message' => 'La actividad se ha creado correctamente.', 'color' => 'success']);
                return redirect()->route('admin.actividad_cliente.index');
            } catch (\Exception $e) {
                Log::error($e);
                session(['message' => 'La actividad ha sido creada exitosamente, pero hubo un problema al enviar la notificación.', 'color' => 'warning']);
                return redirect()->route('admin.actividad_cliente.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ActividadCliente $actividadCliente)
    {
        $reporteActividad = ReporteActividad::where('id', $actividadCliente->reporte_actividad_id)->first();

        // $historialTiempos = HistorialActividades::where('reporte_actividades_id', $actividadCliente->reporte_actividad_id)->get();

        $seguimientos = [];

        if (is_string($reporteActividad->justificacion)) {
            $seguimientos = json_decode($reporteActividad->justificacion);
            if (json_last_error() === JSON_ERROR_NONE) {
                $seguimientos = json_decode($reporteActividad->justificacion);
            } else {
                $justificacion[] = [
                    'time' => 'Sin fecha',
                    'user' => $actividadCliente->usuario ? $actividadCliente->usuario->nombres . ' ' . $actividadCliente->usuario->apellidos : 'Usuario inactivo',
                    'descripcion' => $reporteActividad->justificacion,
                    'estado' => $reporteActividad->estado_actividades->nombre
                ];

                $justificacion = json_encode($justificacion);
                $seguimientos = json_decode($justificacion);
            }
        }

        $docList = [];

        if ($actividadCliente->file_documento_1) {
            $docList['file_documento_1'] = 'storage/data/actividadcliente/file_documento_1/' . $actividadCliente->file_documento_1;
        }
        if ($actividadCliente->file_documento_2) {
            $docList['file_documento_2'] = 'storage/data/actividadcliente/file_documento_2/' . $actividadCliente->file_documento_2;
        }
        if ($actividadCliente->file_documento_3) {
            $docList['file_documento_3'] = 'storage/data/actividadcliente/file_documento_3/' .  $actividadCliente->file_documento_3;
        }
        if ($actividadCliente->file_documento_4) {
            $docList['file_documento_4'] = 'storage/data/actividadcliente/file_documento_4/' .  $actividadCliente->file_documento_4;
        }
        if ($actividadCliente->file_documento_5) {
            $docList['file_documento_5'] = 'storage/data/actividadcliente/file_documento_5/' . $actividadCliente->file_documento_5;
        }
        if ($actividadCliente->file_documento_6) {
            $docList['file_documento_6'] = 'storage/data/actividadcliente/file_documento_6/' .  $actividadCliente->file_documento_6;
        }
        if ($reporteActividad->documento) {
            $docList['file_documento_7'] = $reporteActividad->documento;
        }

        $documentos = DocumentoActividad::where('actividad_id', $actividadCliente->id)->get();

        $index = 8;
        foreach ($documentos as $documento) {
            $docList['file_documento_' . $index++] = $documento->nombre;
        }

        return view('admin.actividadcliente.show', compact('actividadCliente', 'reporteActividad', 'docList', 'seguimientos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_ACTIVIDADES'), Response::HTTP_UNAUTHORIZED);

        $actividad_cliente = ActividadCliente::find($id);

        $tipo_actividades = Actividad::select('nombre', 'id')->orderBy('nombre')->get();
        $responsable = Responsable::select('nombre', 'id')->orderBy('nombre')->get();
        $usuarios = User::select('id', 'nombres', 'apellidos')->where('estado', 'ACTIVO')->orderBy('nombres')->get();
        $clientes = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();

        if (Auth::user()->role_id == 1) {
            $clientes = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->get();
        }

        return view('admin.actividadcliente.edit', compact('tipo_actividades', 'responsable', 'usuarios', 'clientes', 'actividad_cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActividadCliente $actividad_cliente)
    {
        try {
            // *** Ruta base para los archivos
            $fileBasePath = storage_path('app/public/data/actividadcliente');
            // *** Define inputs y columnas
            $documents = [
                'documento_1' => 'file_documento_1',
                'documento_2' => 'file_documento_2',
                'documento_3' => 'file_documento_3',
                'documento_4' => 'file_documento_4',
                'documento_5' => 'file_documento_5',
            ];

            // *** Valida documentos
            foreach ($documents as $documentInput => $documentPath) {
                $name = $actividad_cliente->$documentPath;
                $name = $this->load_file_update($request, $documentInput, $documentPath, $fileBasePath, $name);
                $request->merge([$documentPath => $name]);
            }

            if ($request->fecha_vencimiento != $actividad_cliente->fecha_vencimiento) {
                $reporteActividad = ReporteActividad::where('id', $actividad_cliente->reporte_actividad_id)->first();

                $reporteActividad->estado_actividad_id = 10;
                $reporteActividad->save();
            }

            $parts_cliente = explode('-', $request->cliente_id);
            $cliente = trim($parts_cliente[0]);

            $parts_usuario = explode('-', $request->usuario_id);
            $usuario = trim($parts_usuario[0]);

            $parts_empresa = explode('-', $request->empresa_asociada_id);
            $empresa_asociada = trim($parts_empresa[0]);

            if ($request->cliente_id) {
                $request = $request->merge(['cliente_id' => $cliente]);
            } else {
                $request = $request->merge(['cliente_id' => $actividad_cliente->cliente_id]);
            }

            if ($request->usuario_id) {
                $request = $request->merge(['usuario_id' => $usuario]);
            } else {
                $request = $request->merge(['usuario_id' => $actividad_cliente->usuario_id]);
            }

            if ($request->empresa_asociada_id) {
                $request = $request->merge(['empresa_asociada_id' => $empresa_asociada]);
            } else {
                $request = $request->merge(['empresa_asociada_id' => $actividad_cliente->empresa_asociada_id]);
            }

            $request = $request->merge(['user_update_act_id' =>  Auth::user()->id]);
            $actividad_cliente->update($request->all());

            session(['message' => 'La actividad se ha actualizado correctamente.', 'color' => 'success']);
            return redirect()->route('admin.actividad_cliente.index');
        } catch (\Throwable $th) {
            session(['message' => 'Hubo un problema al actualizar la actividad', 'color' => 'warning']);
            return redirect()->route('admin.actividad_cliente.edit', $actividad_cliente->id);
        }
    }

    public function reporteIndex($id)
    {
        $usuario = User::select('id', 'nombres', 'apellidos')->whereNotIn('role_id', [7])->get();
        $actividad_cliente = ActividadCliente::find($id);
        $cliente = Empresa::all();

        $reporteActividad = ReporteActividad::where('id', $actividad_cliente->reporte_actividad_id)->first();

          //Si la actividad esta programada trae solo el estado en proceso
        if ($reporteActividad->estado_actividad_id == 1) {
            $estado_actividad = EstadoActividad::whereIn('id', [2])->get();
        } else if ($reporteActividad->fecha_inicio == null) {
            $estado_actividad = EstadoActividad::whereIn('id', [2])->get();
        } else {
            //se muestran los estados cumplido si la fecha de vencimiento es menor a la actual
            if ($actividad_cliente->fecha_vencimiento < Carbon::now()->format('Y-m-d')) {
                if ($reporteActividad->estado_actividad_id == 3) { //Pausado
                    $estado_actividad = EstadoActividad::whereIn('id', [9])->get();
                } else if ($reporteActividad->estado_actividad_ == 9) { //Reactivado
                    $estado_actividad = EstadoActividad::whereIn('id', [3, 8])->get();
                } else {
                    $estado_actividad = EstadoActividad::whereIn('id', [3, 8])->get();
                }
            } else {
                if ($reporteActividad->estado_actividad_id == 3) { //Pausado
                    $estado_actividad = EstadoActividad::whereIn('id', [9])->get();
                } else if ($reporteActividad->estado_actividad_ == 9) { //Reactivado
                    $estado_actividad = EstadoActividad::whereIn('id', [3, 7])->get();
                } else {
                    $estado_actividad = EstadoActividad::whereIn('id', [3, 7])->get();
                }
            }
        }

        return view('admin.actividadcliente.edit-reporte', compact('usuario', 'cliente', 'actividad_cliente', 'estado_actividad', 'reporteActividad'));
    }

    public function reasignarActividad($id)
    {
        $usuario = User::orderBy('nombres')->select('id', 'nombres', 'apellidos')->whereNotIn('role_id', [7])->get();
        $actividad_cliente = ActividadCliente::find($id);
        $cliente = Empresa::orderBy('razon_social')->where('estado', 1)->get();

        $reporteActividad = ReporteActividad::where('id', $actividad_cliente->reporte_actividad_id)->first();

        $estado_actividad = EstadoActividad::whereIn('id', [4, 5])->get();

        return view('admin.actividadcliente.edit-reporte', compact('usuario', 'cliente', 'actividad_cliente', 'estado_actividad', 'reporteActividad'));
    }

    public function reporteEdit(UpdateReporteActividad $request, $id, HistorialActividades $historialActividades)
    {
        $actividad_cliente = ActividadCliente::find($id);
        $reporteActividad = ReporteActividad::where('id', $actividad_cliente->reporte_actividad_id)->first();
        $estado = EstadoActividad::find($request->estado_actividad_id);

        $urlPathDocumentUno = null;
        $urlPathDocumentDos = null;

        if ($reporteActividad->documento != null && $request->file('documento')) {
            $foldername = basename(dirname($reporteActividad->documento));

            $archivo = $request->file('documento');

            if (File::exists($reporteActividad->documento)) {
                File::delete($reporteActividad->documento);
            }

            $documento = $archivo->getClientOriginalName();
            $urlPathDocumentUno = 'storage/reporte_documento/' . $foldername . '/' . $archivo->getClientOriginalName();

            Storage::disk('reporte_documento')->put($foldername . '/' . $documento, File::get($archivo));
        } else {
            if ($request->file('documento')) {
                $archivo = $request->file('documento');
                $foldername = uniqid();

                $documento = $archivo->getClientOriginalName();
                $urlPathDocumentUno = 'storage/reporte_documento/' . $foldername . '/' . $archivo->getClientOriginalName();

                Storage::disk('reporte_documento')->put($foldername . '/' . $documento, File::get($archivo));
            }
        }

        if ($request->hasFile('documento_extra')) {
            foreach ($request->file('documento_extra') as $file) {
                if ($file) {
                    $foldername = uniqid();

                    $documento = $file->getClientOriginalName();
                    $urlPathDocumentDos = 'storage/reporte_documento/' . $foldername . '/' . $file->getClientOriginalName();

                    Storage::disk('reporte_documento')->put($foldername . '/' . $documento, File::get($file));

                    DocumentoActividad::create([
                        'actividad_id' => $actividad_cliente->id,
                        'nombre' => $urlPathDocumentDos,
                    ]);
                }
            }
        }

        if ($request->estado_actividad_id == 2) {
            $actividad_cliente->update([
                'progreso' => '0'
            ]);

            $reporteActividad->update([
                'fecha_inicio' => Carbon::now()->format('Y-m-d H:i:s'),
                'estado_actividad_id' => $request->estado_actividad_id,
                'documento' => $urlPathDocumentUno
            ]);
        } else if ($request->estado_actividad_id == 4) {

            if ($reporteActividad->justificacion) {
                $seguimiento = json_decode($reporteActividad->justificacion);
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => 'Cancelado'
                ];
            } else {
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => 'Cancelado'
                ];
            }

            $actividad_cliente->update([
                'progreso' => $request['progreso']
            ]);

            $reporteActividad->update([
                'estado_actividad_id' => $request->estado_actividad_id,
                'justificacion' => $seguimiento,
                'documento' => $urlPathDocumentUno
            ]);
        } else if ($request->estado_actividad_id == 5) {

            $actividad_cliente->update([
                'usuario_id' => $request['usuario_id'],
            ]);

            $reporteActividad->update([
                'estado_actividad_id' => 1,
                'fecha_inicio' => null
            ]);
        } else if ($request->estado_actividad_id == 7 || $request->estado_actividad_id == 8) {

            if ($reporteActividad->justificacion) {
                $seguimiento = json_decode($reporteActividad->justificacion);
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => $estado->nombre
                ];
            } else {
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => $estado->nombre
                ];
            }

            $actividad_cliente->update([
                'progreso' => '100'
            ]);

            $reporteActividad->update([
                'estado_actividad_id' => $request->estado_actividad_id,
                'fecha_fin' => Carbon::now()->format('Y-m-d H:i:s'),
                'justificacion' => $seguimiento,
                'recomendacion' => $request['recomendacion'],
                'documento' => $urlPathDocumentUno
            ]);

            try {
                $recipient = User::withTrashed()->find($actividad_cliente->user_crea_act_id);
                $numero = $recipient->numero_contacto ? $recipient->numero_contacto : '0000000000';
                $nombrePlantilla = 'actividad_finalizada';

                $data = [
                    'subject' => 'Se ha actualizado una actividad',
                    'notifiable_id' => $recipient->id,
                    'actividad_id' =>  $actividad_cliente->id,
                    'url' => route('admin.actividad_cliente.show', $actividad_cliente->id),
                    'message' => 'Te notificamos que se ha ' . $estado->nombre . ' una actividad con el nombre registrado <strong>' . $actividad_cliente->nombre . '</strong> y número de ID <strong>' . $actividad_cliente->id . '</strong> para que le des su respectiva revisión.',
                ];

                if ($recipient->estado == "ACTIVO") {
                    $recipient->notify(new MessageSent($data));
                    $recipient->notify(new ActividadNotification($actividad_cliente->nombre, $actividad_cliente->id, $nombrePlantilla, $numero));
                }
            } catch (\Exception $e) {
                Log::error($e);
                session(['message' => 'La actividad ha sido reportada exitosamente, pero hubo un problema al enviar la notificación.', 'color' => 'warning']);
                return redirect()->route('admin.actividad_cliente.index');
            }
        } else if ($request->estado_actividad_id == 3 || $request->estado_actividad_id == 9) {

            if ($reporteActividad->justificacion) {
                $seguimiento = json_decode($reporteActividad->justificacion);
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => $estado->nombre
                ];
            } else {
                $seguimiento[] = [
                    'time' => Carbon::now()->format('Y-m-d h:i:s'),
                    'user' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
                    'descripcion' => str_replace('&', '&amp;', $request->justificacion),
                    'estado' => $estado->nombre
                ];
            }

            $fecha_creacion = Carbon::now();

            $historialActividades->reporte_actividades_id = $actividad_cliente->reporte_actividad_id;
            $historialActividades->estado = $request->estado_actividad_id;
            $historialActividades->justificacion = str_replace('&', '&amp;', $request->justificacion);
            $historialActividades->modalidad = $request->modalidad;
            $historialActividades->fecha_creacion = $fecha_creacion;

            $historialActividades->save();

            $actividad_cliente->update([
                'progreso' => $request['progreso']
            ]);

            $reporteActividad->update([
                'estado_actividad_id' => $request->estado_actividad_id,
                'justificacion' => $seguimiento,
                'documento' => $urlPathDocumentUno
            ]);
        }

        session(['message' => 'La actividad se ha reportado correctamente', 'color' => 'success']);
        return redirect()->route('admin.actividad_cliente.index');
    }

    public function load_file_create($request, $input, $path, $basePath)
    {
        if ($request->file($input)) {
            $file = $request->file($input);
            $filename = uniqid() . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            $fullPath = $basePath . '/' . $path . '/' . $filename;

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $file->move($basePath . '/' . $path, $filename);
            $request->merge([$path => $filename]);
        }
    }

    public function load_file_update($request, $input, $path, $basePath, $oldName)
    {
        if ($request->file($input)) {
            $file = $request->file($input);
            $filename = uniqid() . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            $fullPath = $basePath . '/' . $path . '/' . $filename;

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // if ($oldName && file_exists($basePath . '/' . $path . '/' . $oldName)) {
            //     unlink($basePath . '/' . $path . '/' . $oldName);
            // }

            $file->move($basePath . '/' . $path, $filename);
            return $filename;
        } else {
            return $oldName;
        }
    }

    public function activity_notification()
    {
        // *** Actividades con fecha de vencimiento del dia actual y recordatorios de vencimiento de dias futuros
        $this->getFutureActivities();

        // *** Actividades con fecha de vencimiento pasadas sin estado de actividad "finalizado"
        $this->getExpiredActivities();
    }

    private function getFutureActivities()
    {
        $current_date = Carbon::now();
        $startOfMonth = $current_date->startOfMonth()->format('Y-m-d');
        $endOfMonth = $current_date->endOfMonth()->format('Y-m-d');

        $actividades = ActividadCliente::whereBetween('fecha_vencimiento', [$startOfMonth, $endOfMonth])->get();

        foreach ($actividades as $actividad) {
            // *** Declara variables
            $activity_status = $actividad->reporte_actividades->estado_actividad_id;

            if ($activity_status != 4 && $activity_status != 7 && $activity_status != 6 && $activity_status != 8) {
                $user = User::withTrashed()->find($actividad->usuario_id);

                if ($user->estado == "ACTIVO") {
                    $destination = $actividad->usuario->email;
                    $subject = 'Estimado/a ' . $actividad->usuario->nombres . ' ' . $actividad->usuario->apellidos . ',';
                    $titulo = "Recordatorio de: " . $actividad->nombre;

                    // *** Formato de texto para la fecha
                    Carbon::setLocale('es');
                    $date = Carbon::createFromFormat('Y-m-d', $actividad->fecha_vencimiento);

                    // *** Fecha recordatorio
                    $reminder_message = $this->reminder($date->isoFormat('D [de] MMMM [de] YYYY'), $actividad->nombre, $actividad->id);

                    if (!$current_date->isSaturday() && !$current_date->isSunday()) {
                        // *** Repite la cantidad de recordatorios
                        for ($i = 0; $i < $actividad->recordatorio; $i++) {
                            // *** Resta la cantidad de dias entre recordatorios
                            $date->subDays($actividad->recordatorio_distancia);

                            if (!$this->mail_condition($date, $destination, $subject, $reminder_message, $titulo)) {
                                if ($date->isSameDay(date('Y-m-d'))) {
                                    Mail::to($destination)->send(new NotificacionActividades($subject, $reminder_message, $titulo));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function getExpiredActivities()
    {
        $current_date = Carbon::now();
        $actividades = ActividadCliente::with(['reporte_actividades' => function ($query) {
            $query->where('estado_actividad_id', 6);
        }, 'usuario'])->where('fecha_vencimiento', '<', date('Y-m-d'))->get();

        foreach ($actividades as $actividad) {
            if (isset($actividad->reporte_actividades)) {
                // *** Declara variables

                $recipient = User::withTrashed()->find($actividad->usuario_id);
                $destination = $recipient->email;
                $subject = 'Estimado/a ' . $recipient->nombres . ' ' . $recipient->apellidos . ',';
                $titulo = "Urgente - actividad vencida: " . $actividad->nombre;

                // *** Formato de texto para la fecha
                Carbon::setLocale('es');
                $date = Carbon::createFromFormat('Y-m-d', $actividad->fecha_vencimiento);

                // *** Fecha vencida
                $expired_message = $this->expired($date->isoFormat('D [de] MMMM [de] YYYY'), $actividad->nombre, $actividad->id);

                if (!$current_date->isSaturday() && !$current_date->isSunday()) {
                    if (!$this->mail_condition($date, $destination, $subject, $expired_message, $titulo)) {
                        if ($recipient->estado == "ACTIVO") {
                            Mail::to($destination)->send(new NotificacionActividades($subject, $expired_message, $titulo));
                        }
                    }
                }
            }
        }
    }

    private function mail_condition($database_date, $destination, $subject, $message, $titulo)
    {
        $current_date = Carbon::now();

        if ($database_date->isWeekend() && $current_date->isFriday()) {
            Mail::to($destination)->send(new NotificacionActividades($subject, $message, $titulo));
            return true;
        } elseif ($this->holidays($database_date)) {
            $days = $this->days_anticipation($database_date);
            $send_date = $database_date->subDays($days);

            if ($send_date->isToday()) {
                Mail::to($destination)->send(new NotificacionActividades($subject, $message, $titulo));
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function days_anticipation($database_date)
    {
        if ($database_date->isMonday()) {
            return 3;
        } elseif ($database_date->isTuesday() || $database_date->isWednesday() || $database_date->isThursday() || $database_date->isFriday()) {
            return 1;
        }
    }

    private function holidays($database_date)
    {
        $festivo = Festivo::select('day')->whereYear('day', date('Y'))->get();
        $aux = false;

        foreach ($festivo as $date) {
            if ($date->day == $database_date->toDateString()) {
                $aux = true;
                break;
            }
        }

        if ($aux) {
            return true;
        } else {
            return false;
        }
    }

    private function expired($date, $nombre, $id)
    {
        $message = 'Esperamos que se encuentre bien. Queremos recordarle que la fecha de vencimiento de la actividad que tiene asignada con consecutivo <b>' . $id . '</b> y nombre registrado ' . $nombre . ', ha pasado. ';
        $message .= 'Le recomendamos encarecidamente que revise el estado actual de la actividad para asegurarse de que todo esté en orden. ';
        $message .= 'La fecha de vencimiento era el ' . $date . '.<br><br>';
        $message .= 'Si ya ha completado la actividad o ha tomado medidas al respecto, le agradecemos su diligencia. ';
        $message .= 'Si necesita alguna extensión de plazo o asistencia adicional, no dude en ponerse en contacto con nosotros. <br><br>';
        $message .= 'Atentamente, Erika Lopez.';
        return $message;
    }

    private function reminder($date, $nombre, $id)
    {
        $message = 'Esperamos que esté teniendo un buen día. Queremos informarle que la fecha de vencimiento de la actividad que tiene asignada con consecutivo <b>' . $id . '</b> y nombre registrado ' . $nombre . ', se aproxima. ';
        $message .= 'Para su conveniencia, le sugerimos revisar el estado actual de la actividad y asegurarse de que esté en camino de cumplirse antes del ' . $date . '.<br><br>';
        $message .= 'Si necesita más tiempo, recursos adicionales o tiene alguna pregunta sobre la actividad, estamos aquí para ayudar. ';
        $message .= 'No dude en ponerse en contacto con nosotros para cualquier tipo de apoyo que pueda requerir. <br><br>Gracias por su atención y colaboración.<br><br>';
        $message .= 'Atentamente, Erika Lopez.';
        return $message;
    }

    public function showEmpresa($responsableActividad)
    {
        if (Auth::user()->role_id == 1) {
            if ($responsableActividad == 1) {
                $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->get()->toJson();
            } else {
                $empresas = Empresa::select('id', 'razon_social')->where('id', 1)->get()->toJson();
            }
        } else {
            if ($responsableActividad == 1) {
                $empresas = Empresa::orderBy('razon_social')->where('estado', 1)->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get()->toJson();
            } else {
                $empresas = Empresa::select('id', 'razon_social')->where('id', 1)->get()->toJson();
            }
        }

        return $empresas;
    }

    public function showResponsable($empresa)
    {
        $responsable = [];
        $parts = explode('-', $empresa);
        $value = trim($parts[0]);

        if ($value == 1) {
            //Trae todos los empleados de HelpContable
            $responsable = User::orderBy('nombres')->where('estado', 'ACTIVO')->whereNotIn('role_id', [7, 8])->select('id', 'nombres', 'apellidos')->get()->toJson();
        } else if ($value == "null") {
            $responsable = User::orderBy('nombres')->where('estado', 'ACTIVO')->select('id', 'nombres', 'apellidos')->get()->toJson();
        } else if ($value != 1) {
            //Trae los clientes de la empresa seleccionada
            $responsable = EmpleadoCliente::orderBy('nombres')->select('user_id as id', 'nombres', 'apellidos')
                ->whereHas('usuarios', function ($query) {
                    $query->where('estado', 'ACTIVO');
                })
                ->where(function ($query) use ($value) {
                    $query->where('empresa_id', $value)
                        ->orWhereJsonContains('empresas_secundarias', $value);
                })
                ->get()->toJson();
        }

        return $responsable;
    }

    public function update_activities()
    {
        $this->expired_activities();
    }

    private function expired_activities()
    {

        $actividades = ActividadCliente::select('fecha_vencimiento', 'reporte_actividad_id', 'usuario_id', 'id')->get();

        $today = Carbon::now()->format('Y-m-d');

        foreach ($actividades as $actividad) {

            $reporteActividad = ReporteActividad::where('id', $actividad->reporte_actividad_id)->first();

            //No este finalizado, cumplido, cancelado o pausado
            if ($actividad->fecha_vencimiento < $today && !in_array($reporteActividad->estado_actividad_id, [7, 8, 4, 3])) {
                $reporteActividad->update([
                    'estado_actividad_id' => 6
                ]);
            }
        }
    }

    public function masivoactividades()
    {
        //actualiza el archivo Excel y actualiza las listas desplegables 
        $export = new ActividadClienteExport();
        $export->array();
        $ruta = public_path('data/ActividadCliente/MasivoActividades.xlsx');
        // Después de la actualización, descarga el archivo
        return response()->download($ruta, 'MasivoActividades.xlsx');
    }

    public function importExcel(Request $request)
    {
        //obtener el archivo  
        $file = $request->file('file');
        //validar si se cargo el archivo
        if (!isset($file)) {
            return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
        }
        $job = new ExcelActividadClienteBatchImport($file->getRealPath());
        dispatch($job);
        // Session::flash('file_upload_completed', true) 'pendiente para las vistas';
        // Redireccionar a la vista de importación con un mensaje de éxito
        return back();
    }

    public function descargarExcel(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipo = $request->input('tipo');
        $id_user = explode(' - ', $request->input('user'))[0];
        return Excel::download(new InformeActividadesExport($fechaInicio, $fechaFin, $tipo, $id_user), 'informe_tributario.xlsx');
    }
}
