<?php

namespace App\Http\Controllers\admin;

use App\Exports\ActividadesEstadoExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Actividad;
use App\Models\EstadoActividad;
use App\Models\EmpleadoCliente;
use App\Models\ActividadCliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateInformeUsuarioRequest;
use App\Http\Requests\CreateInformeActividadesRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActividadUsuariosExport;
use App\Exports\ActividadesExport;
use App\Models\HistorialActividades;
use App\Models\ReporteActividad;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;
use SebastianBergmann\Type\NullType;
use Symfony\Component\HttpFoundation\Response;

class InformeUsuarioController extends Controller
{
    public function index()
    {

        abort_if(Gate::denies('ACCEDER_INFORME_USUARIO'), Response::HTTP_UNAUTHORIZED);

        if (Auth::user()->role_id == 1) {
            $empresa = Empresa::orderBy('razon_social')->get();
        } else {
            $empleado = EmpleadoCliente::where('user_id', Auth::user()->id)->first();
            $empresa = Empresa::where('id', $empleado->empresa_id)->get();
        }

        $tipo_actividad = Actividad::select('id', 'nombre')->get();

        return view('admin.informes.informe-usuario-index', compact('empresa', 'tipo_actividad'));
    }

    //si es cliente traer la empresa a la que pertenece y todos los empleados de dicha empresa
    public function showUsuario($empresa)
    {
        if ($empresa == 1) {
            $responsable = User::whereNotIn('role_id', [7, 8])->orderBy('nombres')->get()->toJson();
        } else {
            $responsable = EmpleadoCliente::select('user_id as id', 'nombres', 'apellidos')->where('empresa_id', $empresa)->orderBy('nombres')->get()->toJson();
        }

        return $responsable;
    }

    public function getInformeUsuario(CreateInformeUsuarioRequest $request)
    {

        $clienteId = $request['empresa'];
        $usuarioId = $request['usuario'];
        $actividadId = $request['tipo_actividad_id'];
        $fechaInicio = $request['fecha_inicio'];
        $fechaFin = $request['fecha_fin'];

        // $informe debe contener las columnas
        // cant_actividades, estado actividad, actividad, porcentaje y total actividades


        //traer el estado de la actividad
        $estados = EstadoActividad::select('nombre', 'id')->get();
        $tipo_actividad = Actividad::select('nombre', 'id')->get();

        $nombre_actividad = null;

        if ($actividadId) {
            $searchActividad =  Actividad::select('nombre')->where('id', $actividadId)->first();
            $nombre_actividad = $searchActividad->nombre;
        }

        $cantidadActividadesEstado = [];
        $cantidadActividades = [];

        // total de actividades
        $totalActividades = ActividadCliente::where('cliente_id', $clienteId)
            ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
            ->where('usuario_id', $usuarioId)->count();

        if ($totalActividades == 0) {
            return redirect()->back()->with('message', 'No existen datos relacionados a este tipo de actividad')->with('color', 'danger');
        }

        //traer la cantidad de actividades por tipo de actividad y estado de actividad realizadas por un usuario
        foreach ($estados as $estado) {

            if ($actividadId) {
                $informe = ActividadCliente::where('cliente_id', $clienteId)
                    ->where('actividad_id', $actividadId)
                    ->where('usuario_id', $usuarioId)
                    ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                    ->with(['actividad', 'cliente', 'reporte_actividades', 'usuario'])->get();
            } else {
                $informe = ActividadCliente::where('cliente_id', $clienteId)
                    ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->where('usuario_id', $usuarioId)
                    ->whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                    ->with(['actividad', 'cliente', 'reporte_actividades']);
            }


            if ($estado->id == 1) {
                $programado = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $programado);
            } else if ($estado->id == 2) {
                $proceso = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $proceso);
            } else if ($estado->id == 3) {
                $pausado = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $pausado);
            } else if ($estado->id == 4) {
                $cancelado = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $cancelado);
            } else if ($estado->id == 6) {
                $vencido = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $vencido);
            } else if ($estado->id == 7) {
                $finalizado = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $finalizado);
            } else if ($estado->id == 8) {
                $finalizado = [
                    'cantidad' =>  $informe->count(),
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $finalizado);
            }
        }


        if ($actividadId) {
            $informe = ActividadCliente::where('cliente_id', $clienteId)
                ->where('actividad_id', $actividadId)
                ->where('usuario_id', $usuarioId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('actividad', function ($query) use ($actividadId) {
                    $query->where('id', $actividadId);
                })
                ->with(['actividad', 'cliente', 'reporte_actividades']);

            if ($informe->count() > 0) {
                $actividad =  [
                    'cantidad' =>  $informe->count(),
                    'tipo_actividad' => $nombre_actividad->nombre,
                    'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                ];
            } else {
                return redirect()->back()->with('message', 'No existen datos relacionados a este tipo de actividad')->with('color', 'danger');
            }

            array_push($cantidadActividades, $actividad);
        } else {
            foreach ($tipo_actividad as $tipo_actividad) {

                $informe = ActividadCliente::where('cliente_id', $clienteId)
                    ->where('usuario_id', $usuarioId)
                    ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->where('actividad_id', $tipo_actividad->id)
                    ->with(['actividad', 'cliente', 'reporte_actividades']);

                if ($tipo_actividad->id == 1) {
                    $cierremes = [
                        'cantidad' =>  $informe->count(),
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $cierremes);
                } else if ($tipo_actividad->id == 2) {
                    $solicitudes = [
                        'cantidad' =>  $informe->count(),
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $solicitudes);
                } else if ($tipo_actividad->id == 3) {
                    $prioritario = [
                        'cantidad' =>  $informe->count(),
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $prioritario);
                } else if ($tipo_actividad->id == 4) {
                    $otro = [
                        'cantidad' =>  $informe->count(),
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe->count() / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $otro);
                }
            }
        }

        $empresa = Empresa::select('razon_social')->where('id', $request['empresa'])->first();
        $usuario = User::select('nombres', 'apellidos')->where('id', $request['usuario'])->first();

        $datos = [
            'usuario' => $usuario->nombres . ' ' . $usuario->apellidos,
            'empresa' => $empresa->razon_social,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'tipo_actividad' => $nombre_actividad
        ];

        return Excel::download(new ActividadUsuariosExport($cantidadActividadesEstado, $cantidadActividades, $datos), 'INFORME_ACTIVIDADES_' . $empresa->razon_social . '_' . $usuario->nombres . '_' . $usuario->apellidos . '.xlsx');
    }

    public function indexActividad()
    {
        abort_if(Gate::denies('ACCEDER_INFORME_ACTIVIDADES_POR_USUARIO'), Response::HTTP_UNAUTHORIZED);

        $usuarios = User::orderBy('nombres')->select('id', 'nombres', 'apellidos')->get();
        $empresa = Empresa::orderBy('razon_social')->select('id', 'razon_social')->get();
        $estados = EstadoActividad::select('id', 'nombre')->get();
        $tipo_actividad = Actividad::orderBy('nombre')->select('id', 'nombre')->get();

        return view('admin.informes.informe-actividades-index', compact('empresa', 'tipo_actividad', 'usuarios', 'estados'));
    }

    public function getInformeActividad(CreateInformeActividadesRequest $request)
    {

        //traer las actividades asignadas a un usuario, traer nombre de la atividad y el progreso
        $clienteId = $request->empresa;
        $actividadId = $request->tipo_actividad_id;
        $usuarioId = $request->usuario;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $nombre_actividad = null;

        if ($actividadId) {
            $searchActividad = Actividad::select('nombre')->where('id', $actividadId)->first();
            $nombre_actividad = $searchActividad->nombre;
        }

        //traer las actividades por tipo de actividad y estado de actividad
        $cantidadActividades = [];

        if ($clienteId && $usuarioId && $actividadId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->where('empresa_asociada_id', $clienteId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('actividad', function ($query) use ($actividadId) {
                    $query->where('id', $actividadId);
                })
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else if ($usuarioId && $actividadId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('actividad', function ($query) use ($actividadId) {
                    $query->where('id', $actividadId);
                })
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } elseif ($clienteId) {
            //  trae todas las actividades por empresa asociada
            $informes = ActividadCliente::where('empresa_asociada_id', $clienteId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } elseif ($usuarioId) {
            //trae todas las actividades por usuario
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else if ($actividadId) {
            $informes = ActividadCliente::where('actividad_id', $actividadId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else {
            //trae todas las actividades por todos
            $informes = ActividadCliente::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        }

        if (count($informes) == 0) {
            return redirect()->back()->with('message', 'No existen datos relacionados con las opciones seleccionadas')->with('color', 'danger');
        }

        //guarda todas las actividades por estado y tipo de actividad
        foreach ($informes as $informe) {
            $totalTiempo = null;

            //consulta si la actividad se ha finalizado
            if ($informe->reporte_actividades->fecha_fin != null) {
                //calcula el tiempo de todos los estados
                $totalTiempo = $this->calcularTiempo($informe->reporte_actividades->id);
            }

            $seguimientos = [];
            $justificacion = [];

            if (is_string($informe->reporte_actividades->justificacion)) {
                $seguimientos = json_decode($informe->reporte_actividades->justificacion);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $seguimientos = json_decode($informe->reporte_actividades->justificacion);
                } else {
                    $justificacion[] = [
                        'time' => 'Sin fecha',
                        'user' => $informe->usuario ? $informe->usuario->nombres . ' ' . $informe->usuario->apellidos : 'Usuario inactivo',
                        'descripcion' => $informe->reporte_actividades->justificacion,
                        'estado' => $informe->reporte_actividades->estado_actividades->nombre
                    ];

                    $justificaciones = json_encode($justificacion);
                    $seguimientos = json_decode($justificaciones);
                }
            }

            $actividades = [
                'id' =>  $informe->id,
                'nombre_actividad' =>  $informe->nombre,
                'empresa' => $informe->empresa_asociada_id ? $informe->empresa_asociada->razon_social : $informe->cliente->razon_social,
                'responsable' => $informe->usuario ? $informe->usuario->nombres . ' ' . $informe->usuario->apellidos : 'Usuario Inactivo',
                'tipo_actividad' => $informe->actividad->nombre,
                'porcentaje_avance' => $informe->progreso . '%',
                'fecha_vencimiento' =>  $informe->fecha_vencimiento,
                'fecha_inicio' => $informe->reporte_actividades->fecha_inicio ? $informe->reporte_actividades->fecha_inicio : 'No se ha iniciado la actividad',
                'fecha_final' => $informe->reporte_actividades->estado_actividad_id == 8 ||  $informe->reporte_actividades->estado_actividad_id == 7 ? $informe->reporte_actividades->fecha_fin : 'No se ha finalizado la actividad',
                'total_tiempo_realizado' => $totalTiempo ? $totalTiempo :  'No se ha finalizado la actividad',
                'justificacion' => $seguimientos,
                'observacion' => $informe->nota ? $informe->nota : 'Sin observación'
            ];

            array_push($cantidadActividades, $actividades);
        }

        $empresa = null;
        $usuario = null;

        if ($clienteId) {
            $cliente = Empresa::select('razon_social')->where('id', $clienteId)->first();
            $empresa = $cliente->razon_social;
        }

        if ($usuarioId) {
            $empleado = User::select('nombres', 'apellidos')->where('id', $usuarioId)->first();
            $usuario = $empleado ? $empleado->nombres . ' ' . $empleado->apellidos : 'Usuario Inactivo';
        }

        $datos = [
            'usuario' => $usuario,
            'empresa' => $empresa,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'tipo_actividad' => $nombre_actividad,
            'estado' => null,
            'tipo_informe' => 'tipo_actividad'
        ];

        if ($request->tipo_archivo == 'excel') {
            return Excel::download(new ActividadesExport($cantidadActividades, count($informes), $datos), 'INFORME_ACTIVIDADES_ESPECIFICAS_POR_TIPO_DE_ACTIVIDAD_' . $usuario . '.xlsx');
        }

        if ($request->tipo_archivo == 'pdf') {
            $pdf = PDF::loadView('admin.informes.pdf.pdf-actividades-tipo-actividad', ['actividades' => $cantidadActividades, 'cantidad' => count($informes), 'datos' => $datos])->setPaper('A3', 'landscape');
            return $pdf->download('INFORME_ACTIVIDADES_ESPECIFICAS_POR_TIPO_DE_ACTIVIDAD_' . $usuario . '.pdf');
        }
    }

    public function getInformeEstado(CreateInformeActividadesRequest $request)
    {
        $clienteId = $request->empresa;
        $estadoId = $request->estado_id;
        $usuarioId = $request->usuario;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $nombre_estado = null;

        if ($estadoId) {
            $searchEstado = EstadoActividad::select('nombre')->where('id', $estadoId)->first();
            $nombre_estado = $searchEstado->nombre;
        }

        $cantidadActividadesEstado = [];

        if ($clienteId && $usuarioId && $estadoId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->where('empresa_asociada_id', $clienteId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('reporte_actividades', function ($query) use ($estadoId) {
                    $query->where('estado_actividad_id', $estadoId);
                })
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else if ($usuarioId && $estadoId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('reporte_actividades', function ($query) use ($estadoId) {
                    $query->where('estado_actividad_id', $estadoId);
                })
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } elseif ($clienteId) {
            //  trae todas las actividades por empresa asociada
            $informes = ActividadCliente::where('empresa_asociada_id', $clienteId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } elseif ($usuarioId) {
            //trae todas las actividades por usuario
            $informes = ActividadCliente::where('usuario_id', $usuarioId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else if ($estadoId) {
            $informes = ActividadCliente::whereHas('reporte_actividades', function ($query) use ($estadoId) {
                $query->where('estado_actividad_id', $estadoId);
            })
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        } else {
            //trae todas las actividades por todos
            $informes = ActividadCliente::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'reporte_actividades'])->get();
        }

        if (count($informes) == 0) {
            return redirect()->back()->with('message', 'No existen datos relacionados con las opciones seleccionadas')->with('color', 'danger');
        }

        foreach ($informes as $informe) {
            $totalTiempo = null;

            //consulta si la actividad se ha finalizado
            if ($informe->reporte_actividades->fecha_fin != null) {
                //calcula el tiempo de todos los estados
                $totalTiempo = $this->calcularTiempo($informe->reporte_actividades->id);
            }

            $seguimientos = [];
            $justificacion = [];

            if (is_string($informe->reporte_actividades->justificacion)) {
                $seguimientos = json_decode($informe->reporte_actividades->justificacion);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $seguimientos = json_decode($informe->reporte_actividades->justificacion);
                } else {
                    $justificacion[] = [
                        'time' => 'Sin fecha',
                        'user' => $informe->usuario ? $informe->usuario->nombres . ' ' . $informe->usuario->apellidos : 'Usuario inactivo',
                        'descripcion' => $informe->reporte_actividades->justificacion,
                        'estado' => $informe->reporte_actividades->estado_actividades->nombre
                    ];

                    $justificaciones = json_encode($justificacion);
                    $seguimientos = json_decode($justificaciones);
                }
            }

            $actividades_estados = [
                'id' =>  $informe->id,
                'nombre_actividad' =>  $informe->nombre,
                'empresa' => $informe->empresa_asociada_id ? $informe->empresa_asociada->razon_social : $informe->cliente->razon_social,
                'responsable' => $informe->usuario ? $informe->usuario->nombres . ' ' . $informe->usuario->apellidos : 'Usuario Inactivo',
                'estado' => $informe->reporte_actividades->estado_actividades->nombre,
                'porcentaje_avance' => $informe->progreso . '%',
                'fecha_vencimiento' =>  $informe->fecha_vencimiento,
                'fecha_inicio' => $informe->reporte_actividades->fecha_inicio ? $informe->reporte_actividades->fecha_inicio : 'No se ha iniciado la actividad',
                'fecha_final' =>  $informe->reporte_actividades->estado_actividad_id == 8 ||  $informe->reporte_actividades->estado_actividad_id == 7 ? $informe->reporte_actividades->fecha_fin : 'No se ha finalizado la actividad',
                'total_tiempo_realizado' => $totalTiempo ? $totalTiempo :  'No se ha finalizado la actividad',
                'justificacion' => $seguimientos,
                'observacion' => $informe->nota ? $informe->nota : 'Sin observación'
            ];

            array_push($cantidadActividadesEstado, $actividades_estados);
        }

        $empresa = null;
        $usuario = 'todos';

        if ($clienteId) {
            $cliente = Empresa::select('razon_social')->where('id', $clienteId)->first();
            $empresa = $cliente->razon_social;
        }

        if ($usuarioId) {
            $empleado = User::select('nombres', 'apellidos')->where('id', $usuarioId)->first();
            $usuario = $empleado ? $empleado->nombres . ' ' . $empleado->apellidos : 'Usuario Inactivo';
        }

        $datos = [
            'usuario' => $usuario,
            'empresa' => $empresa,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $nombre_estado,
            'tipo_actividad' => null,
            'tipo_informe' => 'estado'
        ];

        if ($request->tipo_archivo == 'excel') {
            return Excel::download(new ActividadesEstadoExport($cantidadActividadesEstado, count($informes), $datos), 'INFORME_ACTIVIDADES_ESPECIFICAS_POR_ESTADO_' . $usuario . '.xlsx');
        }

        if ($request->tipo_archivo == 'pdf') {
            $pdf = PDF::loadView('admin.informes.pdf.pdf-actividades-estado', ['actividades' => $cantidadActividadesEstado, 'cantidad' => count($informes), 'datos' => $datos])->setPaper('A3', 'landscape');
            return $pdf->download('INFORME_ACTIVIDADES_ESPECIFICAS_POR_ESTADO_' . $usuario . '.pdf');
        }
    }

    public function getPdfActividad(Request $request)
    {
        //Consulta la actividad por el número ingresado
        $actividad = ActividadCliente::where('id', $request->actividad_id)->first();


        //Verifica si la actividad existe
        if ($actividad == null) {
            return redirect()->route('admin.informe-actividades.index')->with('message', 'No existen datos relacionados el número ingresado')->with('color', 'danger');
        }

        //Connsulta el reporte
        $reporte = ReporteActividad::where('id', $actividad->reporte_actividad_id)->first();
        //Consulta si existen registros de historial de acuerdo al reporte
        $historial = HistorialActividades::where('reporte_actividades_id', $actividad->reporte_actividad_id)->get();

        //Verifica si la actividad ha sido finalizada
        if ($reporte->fecha_fin == null) {
            return redirect()->route('admin.informe-actividades.index')->with('message', 'La actividad aún no se ha finalizado')->with('color', 'warning');
        }

        //Guarda los datos de la actividad
        $datos = [
            'id' => $actividad->id,
            'fecha' => Carbon::now()->format('d-m-Y h:i:s A'),
            'cliente' => $actividad->empresa_asociada_id ? $actividad->empresa_asociada->razon_social : $actividad->cliente->razon_social,
            'telefono' => $actividad->empresa_asociada_id ? $actividad->empresa_asociada->numero_contacto : $actividad->cliente->numero_contacto,
            'direccion' => $actividad->empresa_asociada_id ? $actividad->empresa_asociada->direccion_fisica : $actividad->cliente->direccion_fisica,
            'tipo_visita' => null,
            'hora_inicio' => Carbon::parse($reporte->fecha_inicio)->format('d-m-Y h:i:s A'),
            'hora_fin' => Carbon::parse($reporte->fecha_fin)->format('d-m-Y h:i:s A'),
            'actividad_programada' => $actividad->nombre,
            'recomendacion' => $reporte->recomendacion,
            'elaborado_por' => Auth::user()->nombres . ' ' . Auth::user()->apellidos,
            'responsable' => $actividad->usuario ? $actividad->usuario->nombres . ' ' . $actividad->usuario->apellidos : 'Usuario Inactivo',
            'cargo' => Auth::user()->role->title,
            'cargo_responsable' => $actividad->usuario->role->title
        ];

        $historia_reporte = [];
        $seguimientos = json_decode($reporte->justificacion);

        $seguimientos = [];

        if (is_string($reporte->justificacion)) {
            $seguimientos = json_decode($reporte->justificacion);
            if (json_last_error() === JSON_ERROR_NONE) {
                $seguimientos = json_decode($reporte->justificacion);
            } else {
                $justificacion[] = [
                    'time' => 'Sin fecha',
                    'user' => $actividad->usuario ? $actividad->usuario->nombres . ' ' . $actividad->usuario->apellidos : 'Usuario inactivo',
                    'descripcion' => $reporte->justificacion,
                    'estado' => $reporte->estado_actividades->nombre
                ];

                $justificacion = json_encode($justificacion);
                $seguimientos = json_decode($justificacion);
            }
        }

        //Si no hubo seguimiento de historial en la actividad solo guarda la fecha inicial y final de la actividad
        if (count($historial) == 0) {

            foreach ($seguimientos as $seguimiento) {
                if ($seguimiento->estado == 'Finalizado' || $seguimiento->estado == 'Cumplido') {
                    $justificacion = $seguimiento->descripcion;
                }
            }

            $historia_reporte[] = [
                'tipo' => null,
                'fecha' => Carbon::parse($reporte->fecha_inicio)->format('d-m-Y'),
                'inicio' => Carbon::parse($reporte->fecha_inicio)->format('h:i:s A'),
                'fin' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                'justificacion' => $justificacion
            ];
        } else {
            foreach ($historial as $index => $historia) {
                //Guarda la primera fecha en la que se inicio la actividad y la primera que se ingreso en el historial
                if ($index == 0) {
                    $historia_reporte[] = [
                        'tipo' => $historia->modalidad,
                        'fecha' => Carbon::parse($reporte->fecha_inicio)->format('d-m-Y'),
                        'inicio' => Carbon::parse($reporte->fecha_inicio)->format('h:i:s A'),
                        'fin' => Carbon::parse($historia->fecha_creacion)->format('h:i:s A'),
                        'justificacion' => $historia->justificacion
                    ];
                    //Si hay más datos guarda la fecha que sigue y toma la del siguiente registro para ser la final
                } else {
                    // if ($historia->estado == 9) {
                    //     $ultimo = count($historial) - $index;

                    //     //Si es el último registro guarda la última fecha del historial como inicio y la final en la que se termino la actividad
                    //     //Y termina el ciclo
                    //     if ($ultimo == 1) {
                    //         $historia_reporte[] = [
                    //             'tipo' => $historia->modalidad,
                    //             'fecha' => Carbon::parse($reporte->fecha_fin)->format('d-m-Y'),
                    //             'inicio' => Carbon::parse($historial[$index]->fecha_creacion)->format('h:i:s A'),
                    //             'fin' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                    //             'justificacion' => $reporte->justificacion
                    //         ];
                    //         break;
                    //     } else {
                    //         $index = $index + 1;

                    //         if ($index < count($historial)) {
                    //             $historia_reporte[] = [
                    //                 'tipo' => $historia->modalidad,
                    //                 'fecha' => Carbon::parse($historia->fecha_creacion)->format('d-m-Y'),
                    //                 'inicio' => Carbon::parse($historia->fecha_creacion)->format('h:i:s A'),
                    //                 'fin' => Carbon::parse($historial[$index]->fecha_creacion)->format('h:i:s A'),
                    //                 'justificacion' => $historial[$index]->justificacion
                    //             ];
                    //         }
                    //     }
                    if ($historia->estado == 3) {
                        // $index = $index + 1;
                        $ultimo = count($historial) - $index;

                        //Si es el último registro guarda la última fecha del historial como inicio y la final en la que se termino la actividad
                        //Y termina el ciclo
                        if ($ultimo == 1) {
                            if ($index < count($historial)) {
                                $historia_reporte[] = [
                                    'tipo' => $historia->modalidad,
                                    'fecha' => Carbon::parse($reporte->fecha_fin)->format('d-m-Y'),
                                    'inicio' => Carbon::parse($historial[$index]->fecha_creacion)->format('h:i:s A'),
                                    'fin' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                                    'justificacion' => $historia->justificacion
                                ];
                            }
                            break;
                        } else {
                            //Si no sigue guardando la del siguiente registro
                            if ($index < count($historial)) {
                                $historia_reporte[] = [
                                    'tipo' => $historia->modalidad,
                                    'fecha' => Carbon::parse($historial[$index]->fecha_creacion)->format('d-m-Y'),
                                    'inicio' => Carbon::parse($historial[$index - 1]->fecha_creacion)->format('h:i:s A'),
                                    'fin' => Carbon::parse($historial[$index]->fecha_creacion)->format('h:i:s A'),
                                    'justificacion' => $historia->justificacion
                                ];
                            }
                        }
                    }
                }
            }

            if ($historial[count($historial) - 1]->estado == 9) { //Sí la ultima actividasd finalizo en reactivado traer dicho tiempo si no dejar igual el tiempo de finalizacion del reporte
                foreach ($seguimientos as $seguimiento) {
                    if ($seguimiento->estado == 'Finalizado' || $seguimiento->estado == 'Cumplido') {
                        $justificacion = $seguimiento->descripcion;
                    }
                }

                $historia_reporte[] = [
                    'tipo' => $historia->modalidad,
                    'fecha' => Carbon::parse($reporte->fecha_fin)->format('d-m-Y'),
                    'inicio' => Carbon::parse($historial[count($historial) - 1]->fecha_creacion)->format('h:i:s A'),
                    'fin' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                    'justificacion' => $justificacion
                ];
            } else {
                foreach ($seguimientos as $seguimiento) {
                    if ($seguimiento->estado == 'Finalizado' || $seguimiento->estado == 'Cumplido') {
                        $justificacion = $seguimiento->descripcion;
                    }
                }

                $historia_reporte[] = [
                    'tipo' => $historia->modalidad,
                    'fecha' => Carbon::parse($reporte->fecha_fin)->format('d-m-Y'),
                    'inicio' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                    'fin' => Carbon::parse($reporte->fecha_fin)->format('h:i:s A'),
                    'justificacion' => $justificacion
                ];
            }
        }

        $pdf = PDF::loadView('admin.informes.pdf-actividad', ['datos' => $datos, 'historial' => $historia_reporte]);
        return $pdf->download('REPORTE_ACTIVIDAD_NUMERO_' .  $actividad->id . '_' . Carbon::now()->format('d-m-Y') . '.pdf');
    }

    public function calcularTiempo($reporte_id)
    {
        $reporte = ReporteActividad::where('id', $reporte_id)->first();
        $historial = HistorialActividades::where('reporte_actividades_id', $reporte_id)->get();

        $totalSegundos = 0;

        if (count($historial) == 0) {
            $inicio = Carbon::parse($reporte->fecha_inicio);
            $fin = Carbon::parse($reporte->fecha_fin);

            $totalSegundos += $fin->diffInSeconds($inicio);
        } else {
            foreach ($historial as $index => $historia) {
                if ($index == 0) {
                    $inicio = Carbon::parse($reporte->fecha_inicio);
                    $fin = Carbon::parse($historia->fecha_creacion);
                    $totalSegundos += $fin->diffInSeconds($inicio);
                } else {
                    if ($historia->estado == 3) {
                        $ultimo = count($historial) - $index;

                        if ($ultimo == 1) {
                            $inicio = Carbon::parse($historial[$index]->fecha_creacion);
                            $fin = Carbon::parse($reporte->fecha_fin);
                            $totalSegundos += $fin->diffInSeconds($inicio);
                            break;
                        } else {
                            $inicio = Carbon::parse($historial[$index - 1]->fecha_creacion);
                            $fin = Carbon::parse($historial[$index]->fecha_creacion);
                            $totalSegundos += $fin->diffInSeconds($inicio);
                        }
                    }
                }
            }

            if ($historial[count($historial) - 1]->estado == 9) {
                $inicio = Carbon::parse($historial[count($historial) - 1]->fecha_creacion);
                $fin = Carbon::parse($reporte->fecha_fin);
                $totalSegundos += $fin->diffInSeconds($inicio);
            }
        }

        // Convertir segundos acumulados a formato H:i:s
        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }
}
