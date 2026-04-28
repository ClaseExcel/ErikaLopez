<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Exports\ActividadEmpresasExport;
use App\Models\Empresa;
use App\Models\ActividadCliente;
use App\Models\EstadoActividad;
use App\Models\Actividad;
use App\Http\Requests\CreateInformeEmpresaRequest;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class InformeEmpresaController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ACCEDER_INFORME_POR_EMPRESA'), Response::HTTP_UNAUTHORIZED);

        $empresa = Empresa::select('id', 'razon_social')->where('estado', 1)->orderBy('razon_social')->get();
        $tipo_actividad = Actividad::orderBy('nombre')->select('id', 'nombre')->get();

        return view('admin.informes.informe-empresa-index', compact('empresa', 'tipo_actividad'));
    }

    // Genera un excel por empresa en general y tambien genera por tipo de actividad dependiendo de las fechas
    public function getInformeEmpresa(CreateInformeEmpresaRequest $request)
    {

        $clienteId = $request['empresa'];
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
        if ($clienteId == null) {
            $totalActividades = ActividadCliente::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])->count();
        } else {
            $totalActividades = ActividadCliente::where(function ($query) use ($clienteId) {
                $query->where('empresa_asociada_id', $clienteId)
                    ->orWhere('cliente_id', $clienteId);
            })->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])->count();
        }


        if ($totalActividades == 0) {
            return redirect()->back()->with('message', 'No existen datos relacionados al usuario con las opciones seleccionadas')->with('color', 'danger');
        }

        //traer la cantidad de actividades por tipo de actividad y estado de actividad
        foreach ($estados as $estado) {

            if ($actividadId) {
                $informe = ActividadCliente::where(function ($query) use ($clienteId) {
                    $query->where('empresa_asociada_id', $clienteId)
                        ->orWhere('cliente_id', $clienteId);
                })
                    ->where('actividad_id', $actividadId)
                    ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                    ->with(['actividad', 'cliente', 'reporte_actividades'])->count();
            } else if ($clienteId != null) {
                $informe = ActividadCliente::where(function ($query) use ($clienteId) {
                    $query->where('empresa_asociada_id', $clienteId)
                        ->orWhere('cliente_id', $clienteId);
                })
                    ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                    ->with(['actividad', 'cliente', 'reporte_actividades'])->count();
            } else {
                $informe = ActividadCliente::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                    ->whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                    ->with(['actividad', 'cliente', 'reporte_actividades'])->count();
            }


            if ($estado->id == 1) {
                $programado = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $programado);
            } else if ($estado->id == 2) {
                $proceso = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $proceso);
            } else if ($estado->id == 3) {
                $pausado = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $pausado);
            } else if ($estado->id == 4) {
                $cancelado = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $cancelado);
            } else if ($estado->id == 6) {
                $vencido = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $vencido);
            } else if ($estado->id == 7) {
                $finalizado = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $finalizado);
            } else if ($estado->id == 8) {
                $finalizado = [
                    'cantidad' =>  $informe,
                    'estado' => $estado->nombre,
                    'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                ];
                array_push($cantidadActividadesEstado, $finalizado);
            }
        }


        if ($actividadId) {
            $informe = ActividadCliente::where(function ($query) use ($clienteId) {
                $query->where('empresa_asociada_id', $clienteId)
                    ->orWhere('cliente_id', $clienteId);
            })
                ->where('actividad_id', $actividadId)
                ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                ->whereHas('actividad', function ($query) use ($actividadId) {
                    $query->where('id', $actividadId);
                })
                ->with(['actividad', 'cliente', 'reporte_actividades'])->count();

            if ($informe > 0) {
                $actividad =  [
                    'cantidad' =>  $informe,
                    'tipo_actividad' => $nombre_actividad,
                    'porcentaje' => ($informe / $totalActividades) * 100 . '%'
                ];
            } else {
                return redirect()->back()->with('message', 'No existen datos relacionados al usuario con las opciones seleccionadas')->with('color', 'danger');
            }

            array_push($cantidadActividades, $actividad);
        } else {
            foreach ($tipo_actividad as $tipo_actividad) {

                if ($clienteId == null) {
                    $informe = ActividadCliente::whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                        ->where('actividad_id', $tipo_actividad->id)
                        ->with(['actividad', 'cliente', 'reporte_actividades'])->count();
                } else if ($clienteId != null) {
                    $informe = ActividadCliente::where(function ($query) use ($clienteId) {
                        $query->where('empresa_asociada_id', $clienteId)
                            ->orWhere('cliente_id', $clienteId);
                    })
                        ->whereBetween('fecha_vencimiento', [$fechaInicio, $fechaFin])
                        ->where('actividad_id', $tipo_actividad->id)
                        ->with(['actividad', 'cliente', 'reporte_actividades'])->count();
                }

                if ($tipo_actividad->id == 1) {
                    $cierremes = [
                        'cantidad' =>  $informe,
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $cierremes);
                } else if ($tipo_actividad->id == 2) {
                    $solicitudes = [
                        'cantidad' =>  $informe,
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $solicitudes);
                } else if ($tipo_actividad->id == 3) {
                    $prioritario = [
                        'cantidad' =>  $informe,
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $prioritario);
                } else if ($tipo_actividad->id == 4) {
                    $otro = [
                        'cantidad' =>  $informe,
                        'tipo_actividad' => $tipo_actividad->nombre,
                        'porcentaje' => round(($informe / $totalActividades) * 100) . '%'
                    ];
                    array_push($cantidadActividades, $otro);
                }
            }
        }

        $empresa = 'todos';

        if ($clienteId) {
            $cliente = Empresa::select('razon_social')->where('id', $clienteId)->first();
            $empresa = $cliente->razon_social;
        }

        $datos = [
            'empresa' => $empresa,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'tipo_actividad' => $nombre_actividad
        ];

        return Excel::download(new ActividadEmpresasExport($cantidadActividadesEstado, $cantidadActividades, $datos), 'INFORME_ACTIVIDADES_EMPRESA_' . $empresa . '.xlsx');
    }
}
