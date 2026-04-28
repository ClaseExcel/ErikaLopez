<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ActividadesEspecificasEmpresasEstadoExport;
use App\Exports\ActividadesEspecificasEmpresasExport;
use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\ActividadCliente;
use App\Models\Empresa;
use App\Models\EstadoActividad;
use App\Models\HistorialActividades;
use App\Models\ReporteActividad;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class InformeEspecificoEmpresaController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ACCEDER_INFORME_ACTIVIDADES_POR_EMPRESA'), Response::HTTP_UNAUTHORIZED);

        $empresa = Empresa::orderBy('razon_social')->select('id', 'razon_social')->get();
        $estados = EstadoActividad::select('id', 'nombre')->get();
        $tipo_actividad = Actividad::orderBy('nombre')->select('id', 'nombre')->get();

        return view('admin.informes.informe-actividades-empresa', compact('empresa', 'tipo_actividad', 'estados'));
    }

    public function getInformeActividad(Request $request)
    {

        //traer las actividades asignadas a un usuario, traer nombre de la atividad y el progreso
        $clienteId = $request->empresa;
        $actividadId = $request->tipo_actividad_id;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $nombre_actividad = null;

        if ($actividadId) {
            $searchActividad = Actividad::select('nombre')->where('id', $actividadId)->first();
            $nombre_actividad = $searchActividad->nombre;
        }

        //traer las actividades por tipo de actividad y estado de actividad
        $cantidadActividades = [];

        if ($clienteId && $actividadId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where(function ($query) use ($clienteId) {
                    $query->where('empresa_asociada_id', $clienteId)
                        ->orWhere('cliente_id', $clienteId);
                })
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('actividad', function ($query) use ($actividadId) {
                    $query->where('id', $actividadId);
                })
                ->with(['actividad', 'empresa_asociada', 'cliente', 'reporte_actividades'])->get();
        } elseif ($clienteId) {
            //  trae todas las actividades por empresa asociada
            $informes = ActividadCliente::where(function ($query) use ($clienteId) {
                    $query->where('empresa_asociada_id', $clienteId)
                        ->orWhere('cliente_id', $clienteId);
                })
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'cliente', 'reporte_actividades'])->get();
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
                'responsable' => $informe->usuario->nombres . ' ' . $informe->usuario->apellidos,
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

        if ($clienteId) {
            $cliente = Empresa::select('razon_social')->where('id', $clienteId)->first();
            $empresa = $cliente->razon_social;
        }

        $datos = [
            'empresa' => $empresa,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'tipo_actividad' => $nombre_actividad,
            'estado' => null,
            'tipo_informe' => 'tipo_actividad'
        ];

        if ($request->tipo_archivo == 'excel') {
            return Excel::download(new ActividadesEspecificasEmpresasExport($cantidadActividades, count($informes), $datos), 'INFORME_ACTIVIDADES_ESPECIFICAS_EMPRESA_POR_TIPO_DE_ACTIVIDAD_' . $empresa . '.xlsx');
        }

        if ($request->tipo_archivo == 'pdf') {
            $pdf = PDF::loadView('admin.informes.pdf.pdf-empresas-tipo-actividad', ['actividades' => $cantidadActividades, 'cantidad' => count($informes), 'datos' => $datos])->setPaper('A3', 'landscape');
            return $pdf->download('INFORME_ACTIVIDADES_ESPECIFICAS_EMPRESA_POR_TIPO_DE_ACTIVIDAD_' . $empresa . '.pdf');
        }
    }

    public function getInformeEstado(Request $request)
    {
        $clienteId = $request->empresa;
        $estadoId = $request->estado_id;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $nombre_estado = null;

        if ($estadoId) {
            $searchEstado = EstadoActividad::select('nombre')->where('id', $estadoId)->first();
            $nombre_estado = $searchEstado->nombre;
        }

        $cantidadActividadesEstado = [];

        if ($clienteId && $estadoId) {
            //trae todas las actividades por usuario, empresa asociada y tipo de actividad
            $informes = ActividadCliente::where(function ($query) use ($clienteId) {
                $query->where('empresa_asociada_id', $clienteId)
                    ->orWhere('cliente_id', $clienteId);
            })
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('reporte_actividades', function ($query) use ($estadoId) {
                    $query->where('estado_actividad_id', $estadoId);
                })
                ->with(['actividad', 'empresa_asociada', 'cliente','reporte_actividades'])->get();
        } elseif ($clienteId) {
            //  trae todas las actividades por empresa asociada
            $informes = ActividadCliente::where(function ($query) use ($clienteId) {
                $query->where('empresa_asociada_id', $clienteId)
                    ->orWhere('cliente_id', $clienteId);
            })
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->with(['actividad', 'empresa_asociada', 'cliente','reporte_actividades'])->get();
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
                'responsable' => $informe->usuario->nombres . ' ' . $informe->usuario->apellidos,
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

        if ($clienteId) {
            $cliente = Empresa::select('razon_social')->where('id', $clienteId)->first();
            $empresa = $cliente->razon_social;
        }

        $datos = [
            'empresa' => $empresa,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $nombre_estado,
            'tipo_actividad' => null,
            'tipo_informe' => 'estado'
        ];

        if ($request->tipo_archivo == 'excel') {
            return Excel::download(new ActividadesEspecificasEmpresasEstadoExport($cantidadActividadesEstado, count($informes), $datos), 'INFORME_ACTIVIDADES_ESPECIFICAS_EMPRESA_POR_ESTADO_' . $empresa . '.xlsx');
        }

        if ($request->tipo_archivo == 'pdf') {
            $pdf = PDF::loadView('admin.informes.pdf.pdf-empresas-estado', ['actividades' => $cantidadActividadesEstado, 'cantidad' => count($informes), 'datos' => $datos])->setPaper('A3', 'landscape');
            return $pdf->download('INFORME_ACTIVIDADES_ESPECIFICAS_EMPRESA_POR_ESTADO_' . $empresa . '.pdf');
        }
    }

    public function calcularTiempo($reporte_id)
    {
        // $sumasEstados = HistorialActividades::select(DB::raw('DATE_FORMAT(fecha_creacion, "%H:%i:%s") as created_time'))
        //     ->whereNotNull('fecha_creacion')
        //     ->where('reporte_actividades_id', $reporte_id)
        //     ->get();

        $reporte = ReporteActividad::where('id', $reporte_id)->first();
        $historial = HistorialActividades::where('reporte_actividades_id', $reporte_id)->get();

        $acumulativo_diferencias = [];

        if (count($historial) == 0) {
            $inicio = $reporte->fecha_inicio;
            $fin = $reporte->fecha_fin;

            $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));

            $acumulativo_diferencias[] = ['tiempo' => $diferencia];
        } else {
            foreach ($historial as $index => $historia) {
                //Guarda la primera fecha en la que se inicio la actividad y la primera que se ingreso en el historial
                if ($index == 0) {
                    $inicio = $reporte->fecha_inicio;
                    $fin = $historia->fecha_creacion;

                    $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));

                    $acumulativo_diferencias[] = ['tiempo' => $diferencia];
                    //Si hay más datos guarda la fecha que sigue y toma la del siguiente registro para ser la final
                } else {

                    if ($historia->estado == 3) {
                        // $index = $index + 1;
                        $ultimo = count($historial) - $index;

                        //Si es el último registro guarda la última fecha del historial como inicio y la final en la que se termino la actividad
                        //Y termina el ciclo
                        if ($ultimo == 1) {
                            if ($index < count($historial)) {
                                $inicio = $historial[$index]->fecha_creacion;
                                $fin = $reporte->fecha_fin;

                                $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));

                                $acumulativo_diferencias[] = ['tiempo' => $diferencia];
                            }
                            break;
                        } else {
                            //Si no sigue guardando la del siguiente registro
                            if ($index < count($historial)) {
                                $inicio = $historial[$index - 1]->fecha_creacion;
                                $fin = $historial[$index]->fecha_creacion;

                                $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));

                                $acumulativo_diferencias[] = ['tiempo' => $diferencia];
                            }
                        }
                    }
                }
            }

            if ($historial[count($historial) - 1]->estado == 9) { //Sí la ultima actividasd finalizo en reactivado traer dicho tiempo si no dejar igual el tiempo de finalizacion del reporte
                $inicio = $historial[count($historial) - 1]->fecha_creacion;
                $fin = $reporte->fecha_fin;

                $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));
                $acumulativo_diferencias[] = ['tiempo' => $diferencia];
            } else {

                $inicio = $reporte->fecha_fin;
                $fin = $reporte->fecha_fin;

                $diferencia = Carbon::parse($inicio)->diff(Carbon::parse($fin));
                $acumulativo_diferencias[] = ['tiempo' => $diferencia];
            }
        }

        // dd($acumulativo_diferencias);

        $sumarTiempo = new DateTime();
        $sumarTiempo->setTime(0, 0, 0);

        foreach ($acumulativo_diferencias as $acumulativo) {

            $tiempo = $acumulativo['tiempo']; // Crea un nuevo DateInterval a partir del tiempo
            $sumarTiempo->add($tiempo); // Suma el intervalo actual al total
        }

        $totalTiempo = $sumarTiempo->format('H:i:s');

        return $totalTiempo;
    }

}
