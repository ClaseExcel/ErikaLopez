<?php

namespace App\Http\Controllers\Traits;

use App\Models\ActividadCliente;
use App\Models\ReporteActividad;
use App\Models\SeguimientoRequerimiento;
use App\Models\EmpleadoCliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CalendarioActividades
{
    public function getCalendarioDeActividades($idCliente = null, $responsableId = null, $usercreaactId = null, $startdate = null, $enddate = null)
    {
        setlocale(LC_TIME, 'es_ES');

        $events = [];
        $event_requerimientos = [];

        if (Auth::user()->role_id == 7 ) {
            $usuario_cliente = EmpleadoCliente::where('user_id', Auth::user()->id)->first();

            $requerimientos = SeguimientoRequerimiento::with('requerimientos')
                ->whereHas('requerimientos', function ($query) use ($usuario_cliente) {
                    $query->where('empleado_cliente_id', $usuario_cliente->id);
                })->whereIn('estado_requerimiento_id', [4, 2, 5])
                ->whereBetween('fecha_vencimiento', [$startdate, $enddate])->get();

            //traer todos los requerimientos que esten en estado aceptado, en enviado o finalizado
        } elseif (Auth::user()->role_id == 1) {

            $requerimientos = SeguimientoRequerimiento::with('requerimientos')->whereIn('estado_requerimiento_id', [4, 2, 5])
                ->whereBetween('fecha_vencimiento', [$startdate, $enddate])->get();

            //traer requerimientos por cliente
            if ($idCliente) {
                $requerimientos = SeguimientoRequerimiento::where('empresa_id', $idCliente)->get();
            }
            // Obtener el id del usuario
            $usuario = User::select('id')->where('id', $responsableId)->first();

            //traer requerimientos por responsable 
            if ($idCliente && $responsableId) {
                $requerimientos = $requerimientos->where('empresa_id', $idCliente)->where('user_id', $usuario->id);
            }

            if ($responsableId) {
                // Validar si el usuario existe antes de buscar en requerimientos
                if ($usuario) {
                    $requerimientos = SeguimientoRequerimiento::where('user_id', $usuario->id)->get();
                } else {
                    // El usuario no existe, asignar un valor que garantice que no se obtendrán requerimientos
                    $requerimientos = collect(); // Colección vacía
                }
            } else {
                // Si no hay responsable seleccionado, mostrar todas las requerimientos
                $requerimientos = $requerimientos;
            }
        } else {
            $requerimientos = SeguimientoRequerimiento::with('requerimientos')->whereIn('estado_requerimiento_id', [4, 2, 5])
                ->whereBetween('fecha_vencimiento', [$startdate, $enddate])->where('user_id', Auth::user()->id)->get();

            //traer requerimientos por cliente
            if ($idCliente) {
                $requerimientos = SeguimientoRequerimiento::where('empresa_id', $idCliente)->where('user_id', Auth::user()->id)->get();
            }
            // Obtener el id del usuario
            $usuario = User::select('id')->where('id', $responsableId)->first();

            //traer requerimientos por responsable 
            if ($idCliente && $responsableId) {
                $requerimientos = $requerimientos->where('empresa_id', $idCliente)->where('user_id', $usuario->id);
            }

            if ($responsableId) {
                // Validar si el usuario existe antes de buscar en requerimientos
                if ($usuario) {
                    $requerimientos = SeguimientoRequerimiento::where('user_id', $usuario->id)->get();
                } else {
                    // El usuario no existe, asignar un valor que garantice que no se obtendrán requerimientos
                    $requerimientos = collect(); // Colección vacía
                }
            } else {
                // Si no hay responsable seleccionado, mostrar todas las requerimientos
                $requerimientos = $requerimientos;
            }
        }


        foreach ($requerimientos as $requerimiento) {

            if ($requerimiento->estado_requerimiento_id == 4) {
                $color = '#ffc107'; //en proceso
                $textColor = '#000';
            } elseif ($requerimiento->estado_requerimiento_id == 2) {
                $color = '#007bff'; //aceptado
                $textColor = '#fff';
            } elseif ($requerimiento->estado_requerimiento_id == 5) {
                $color = '#17a2b8'; //finalizado
                $textColor = '#fff';
            }

            $fechaVencimiento = null;
            
            if($requerimiento->fecha_vencimiento) {
                $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $requerimiento->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
            }
            $fechaCreacion = Carbon::parse($requerimiento->requerimientos->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY h:mm a');

            $event_requerimientos[] = [
                'id' => $requerimiento->requerimiento_id,
                'title' => $requerimiento->requerimientos->consecutivo . ' - ' . $requerimiento->requerimientos->tipo_requerimientos->nombre,
                'description' =>
                '<b>Requerimiento: </b>' . $requerimiento->requerimientos->consecutivo . ' - ' . $requerimiento->requerimientos->tipo_requerimientos->nombre
                    . '</br><div class"py-3"></div>' . '<b>Empresa: </b> ' . $requerimiento->requerimientos->empleado_clientes->empresas->razon_social
                    . '</br><div class"py-3"></div>' . '<b>Persona de la empresa quien solicita el requerimiento: </b> ' . $requerimiento->requerimientos->empleado_clientes->nombres . ' ' . $requerimiento->requerimientos->empleado_clientes->apellidos
                    . '</br><div class"py-3"></div>' . '<b>Fecha de creación: </b> ' . $fechaCreacion
                    . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' . $fechaVencimiento
                    . '</br><div class"py-3"></div>' . '<b>Descripción requerimiento: </b> ' . $requerimiento->requerimientos->descripcion
                    . '</br><div class"py-3"></div>' . '<b>Observación: </b>' . $requerimiento->observacion
                    . '</br><div class"py-3"></div>' . '<b>Estado requerimiento: </b>' . $requerimiento->estado_requerimientos->nombre,
                'start' => $requerimiento->fecha_vencimiento,
                'end' => $requerimiento->fecha_vencimiento,
                'allDay' => true,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => $textColor,
                'responsable' => $requerimiento->user_id
            ];
        }

        if (Auth::user()->role_id == 1) {
            //traer todas las actividades
            $actividades = ActividadCliente::whereBetween('fecha_vencimiento', [$startdate, $enddate])->get();

            //traer actividades por cliente
            if ($idCliente) {
                $actividades = ActividadCliente::where('empresa_asociada_id', $idCliente)->get();
            }
            // Obtener el id del usuario
            $usuario = User::select('id')->where('id', $responsableId)->first();

            //traer actividades por responsable 
            if ($idCliente && $responsableId) {
                $actividades = $actividades->where('cliente_id', $idCliente)->where('usuario_id', $usuario->id);
            }

            if ($responsableId) {
                // Validar si el usuario existe antes de buscar en actividades
                if ($usuario) {
                    $actividades = ActividadCliente::where('usuario_id', $usuario->id)->get();
                } else {
                    // El usuario no existe, asignar un valor que garantice que no se obtendrán actividades
                    $actividades = collect(); // Colección vacía
                }
            } else {
                // Si no hay responsable seleccionado, mostrar todas las actividades
                $actividades = $actividades;
            }

            if ($usercreaactId) {
                // Obtener el id del usuario
                $usuario = User::select('id')->where('id', $usercreaactId)->first();

                // Validar si el usuario existe antes de buscar en actividades
                if ($usuario) {
                    $actividades =  ActividadCliente::where('user_crea_act_id', $usuario->id)->get();
                } else {
                    // El usuario no existe, asignar un valor que garantice que no se obtendrán actividades
                    $actividades = collect(); // Colección vacía
                }
            } else {
                // Si no hay responsable seleccionado, mostrar todas las actividades
                $actividades = $actividades;
            }
        } else {
            $actividades = ActividadCliente::where(function ($query) {
                $query->where('usuario_id', Auth::user()->id)
                    ->orWhere('user_crea_act_id', Auth::user()->id);
            })
                ->whereBetween('fecha_vencimiento', [$startdate, $enddate])->get();

            //traer actividades por cliente
            if ($idCliente) {
                $actividades = ActividadCliente::where('empresa_asociada_id', $idCliente)->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })->get();
            }

            // Obtener el id del usuario
            $usuario = User::select('id')->where('id', $responsableId)->first();

            //traer actividades por responsable 
            if ($idCliente && $responsableId) {
                $actividades = $actividades->where('cliente_id', $idCliente)->where('usuario_id', $usuario->id);
            }

            if ($responsableId) {
                // Validar si el usuario existe antes de buscar en actividades
                if ($usuario) {
                    $actividades = ActividadCliente::where('usuario_id', $usuario->id)->get();
                } else {
                    // El usuario no existe, asignar un valor que garantice que no se obtendrán actividades
                    $actividades = collect(); // Colección vacía
                }
            } else {
                // Si no hay responsable seleccionado, mostrar todas las actividades
                $actividades = $actividades;
            }
        }


        foreach ($actividades as $actividad) {

            $fechaVencimiento = Carbon::createFromFormat('Y-m-d',  $actividad->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
            $fechaCreacion = Carbon::parse($actividad->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY h:mm a');
            $empresa = $actividad->empresa_asociada_id ? $actividad->empresa_asociada->razon_social : $actividad->cliente->razon_social;


            $reporteActividad = ReporteActividad::select(['estado_actividad_id'])->where('id', $actividad->reporte_actividad_id)->first();

            if ($reporteActividad->estado_actividad_id == 8) {
                $color = '#f5ca05'; //cumplidas
                $textColor = '#000';
            } elseif ($reporteActividad->estado_actividad_id == 10) {
                $color = '#B695C0'; //reprogramado
                $textColor = '#000';
            } elseif ($reporteActividad->estado_actividad_id == 7) {
                $color = '#0da13c'; //finalizadas
                $textColor = '#fff';
            } elseif ($reporteActividad->estado_actividad_id == 6) {
                $color = '#fa5661'; //vencidas
                $textColor = '#fff';
            } elseif ($reporteActividad->estado_actividad_id == 4) {
                $color = '#000'; //canceladas
                $textColor = '#fff';
            } else {
                $color = '#a7a7a7'; //actividades
                $textColor = '#000';
            }
            $responsable = User::selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo')
                ->where('id', $actividad->usuario_id)
                ->first();
            if (empty($responsable->nombre_completo)) {
                $responsable = 'Usuario Eliminado';
            } else {
                $responsable = $responsable->nombre_completo;
            }
            if (empty($actividad->usuario_crea_act->nombres)) {
                $creador = 'Sin datos';
            } else {
                $creador = $actividad->usuario_crea_act->nombres . ' ' . $actividad->usuario_crea_act->apellidos;
            }
            //barra de progreso
            $progessBar =
                '
            <div class="progress">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: ' . $actividad->progreso . '%" aria-valuenow="' . $actividad->progreso . '" aria-valuemin="0" aria-valuemax="100"></div>
                 <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: ' . (100 - $actividad->progreso) . '%; opacity: 0.2" aria-valuenow="' . (100 - $actividad->progreso) . '" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            ';

            $events[] =
                [
                    'id' => $actividad->id,
                    'title' => $actividad->nombre . ' - ' . $empresa,
                    'description' =>
                    '<div class"py-3"></div>' . $actividad->progreso . '%' . ' de progreso' . $progessBar . ''
                        . '</br><div class"py-3"></div>' . '<b>Empresa: </b>' . $empresa
                        . '</br><b>Nombre: </b>' . $actividad->nombre
                        . '</br><b>Creador: </b>' . $creador
                        . '</br><b>Responsable: </b>' . $responsable
                        . '</br><div class"py-3"></div>' . '<b>Fecha de creación: </b> ' . $fechaCreacion
                        . '<div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' . $fechaVencimiento
                        . '</br><div class"py-3"></div>' . '<b>Periocidad: </b> ' . $actividad->periocidad
                        . '</br><div class"py-3"></div>' . '<b>Nota: </b> ' . $actividad->nota
                        . '</br><div class"py-3"></div>' . '<b>Reporte actividad: </b>' . $reporteActividad->estado_actividades->nombre,
                    'start' => $actividad->fecha_vencimiento,
                    'end' => $actividad->fecha_vencimiento,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => $textColor,
                    'responsable' => $actividad->usuario_id,
                    'user_rol' =>  Auth::user()->role_id,
                    'notificado' => $actividad->notificado
                ];
        }

        $resultado = [
            'events' => $events,
            'event_requerimientos' => $event_requerimientos,
            'festivos' => $this->getFestivos()
        ];

        return $resultado;
    }

    /**
     * Get the value of festivos
     */
    public function getFestivos()
    {
        $añoActual = date('Y');

         return [
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Año Nuevo',
                "start"  => $añoActual . '-01-01',
                "end"  => $añoActual . '-01-01',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Día de los Reyes Magos',
                "start"  => $añoActual . '-01-12',
                "end"  => $añoActual . '-01-12',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Día de San José',
                "start"  => $añoActual . '-03-23',
                "end"  => $añoActual . '-03-23',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Jueves Santo',
                "start"  => $añoActual . '-04-02',
                "end"  => $añoActual . '-04-02',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Viernes Santo',
                "start"  => $añoActual . '-04-03',
                "end"  => $añoActual . '-04-03',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Día del Trabajo',
                "start"  => $añoActual . '-05-01',
                "end"  => $añoActual . '-05-01',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Ascensión del Señor',
                "start"  => $añoActual . '-05-18',
                "end"  => $añoActual . '-05-18',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
                 [
                "id" => 'evento_no_clicable',
                "title"  => 'Corpus Christi',
                "start"  => $añoActual . '-06-08',
                "end"  => $añoActual . '-06-08',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Sagrado Corazón de Jesús',
                "start"  => $añoActual . '-06-15',
                "end"  => $añoActual . '-06-15',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'San Pedro y San Pablo',
                "start"  => $añoActual . '-06-29',
                "end"  => $añoActual . '-06-29',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Día de la Independencia',
                "start"  => $añoActual . '-07-20',
                "end"  => $añoActual . '-07-20',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Batalla de Boyacá',
                "start"  => $añoActual . '-08-07',
                "end"  => $añoActual . '-08-07',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'La Asunción de la Virgen',
                "start"  => $añoActual . '-08-17',
                "end"  => $añoActual . '-08-17',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Día de la Raza',
                "start"  => $añoActual . '-10-12',
                "end"  => $añoActual . '-10-12',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Todos los Santos',
                "start"  => $añoActual . '-11-02',
                "end"  => $añoActual . '-11-02',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Independencia de Cartagena',
                "start"  => $añoActual . '-11-16',
                "end"  => $añoActual . '-11-16',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Inmaculada Concepción',
                "start"  => $añoActual . '-12-08',
                "end"  => $añoActual . '-12-08',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
            [
                "id" => 'evento_no_clicable',
                "title"  => 'Navidad',
                "start"  => $añoActual . '-12-25',
                "end"  => $añoActual . '-12-25',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
             [
                "id" => 'evento_no_clicable',
                "title"  => 'Año nuevo',
                "start"  =>  '2027-12-01',
                "end"  =>  '2027-12-01',
                "backgroundColor"  => '#777',
                "borderColor"  => '#777',
                "textColor"  => '#ffffff',
                "classNames"  => 'event-class-1',
            ],
        ];
    }
}
