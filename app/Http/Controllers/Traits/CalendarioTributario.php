<?php

namespace App\Http\Controllers\Traits;

use App\Models\calendario_tributario;
use App\Models\DepartamentosCiudades;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\FechasMunicipalesCT;
use App\Models\FechasOtrasEntidadesCT;
use App\Models\FechasPorEmpresaCT;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CalendarioTributario
{
    public function getCalendariotributario($idempresa = null, $codigoobligacion = null,$obligacionmunicipalcodigo = null,$otrasentidadescodigo= null, $responsableid = null)
    {
        setlocale(LC_TIME, 'es_ES');
        
        $events = [];
        $events2 = [];
        $event_requerimientos = [];
        if($idempresa == null && $codigoobligacion == null && $obligacionmunicipalcodigo == null && $otrasentidadescodigo == null && $responsableid == null){
            if(in_array(Auth::user()->role_id, [7])){
                $empresaId = EmpleadoCliente::select('empresa_id', 'empresas_secundarias')->where('user_id', Auth::id())->first();    
                // Verificar si $empresaId está vacío
                if (!$empresaId) {
                    $event_requerimientos=[];
                    $events=[];
                    $events2=[];
                } else {
                    $ids = $empresaId->empresas_secundarias ? json_decode($empresaId->empresas_secundarias, true) : [];

                    // Asegúrate de que $ids sea un array, incluso si está vacío o es nulo
                    $ids = is_array($ids) ? $ids : [];

                    // Verifica si $ids no está vacío antes de realizar operaciones
                    $nits = [];
                    if (!empty($ids)) {
                        $nits = array_merge([$empresaId->empresas->NIT], Empresa::select('NIT')->whereIn('id', $ids)->get()->pluck('NIT')->toArray());
                    } else {
                        // Si $ids está vacío, simplemente agregamos el NIT de la empresa principal
                        $nits = [$empresaId->empresas->NIT];
                    }
                    $calendarios = FechasPorEmpresaCT::select('fechas_por_empresa_calendario_tributario.*', 'empresas.razon_social')
                        ->join('empresas', 'empresas.NIT', '=', 'fechas_por_empresa_calendario_tributario.NIT')
                        ->whereIn('fechas_por_empresa_calendario_tributario.NIT', $nits)
                        ->get();
                    $event_requerimientos=$this->estructuracalendarioporobligacion($calendarios);
                    $events=$this->obligacionesmunicipales();
                    $events2=$this->obligacionesotrasentidades();
                }
                   
            }else{
                $calendarios = FechasPorEmpresaCT::select('fechas_por_empresa_calendario_tributario.*', 'empresas.razon_social')
                ->join('empresas', 'empresas.NIT', '=', 'fechas_por_empresa_calendario_tributario.NIT')
                ->where(function ($query) {
                    $now = Carbon::now();
                    $currentMonth = $now->format('m');
                    $currentYear = $now->format('Y');

                    // Mes actual
                    $query->orWhere(function ($q) use ($currentMonth, $currentYear) {
                        $q->whereMonth('fecha_vencimiento', '=', $currentMonth)
                            ->whereYear('fecha_vencimiento', '=', $currentYear);
                    });

                    // Mes anterior
                    $query->orWhere(function ($q) use ($currentMonth, $currentYear) {
                        $previousMonth = ($currentMonth == 1) ? 12 : $currentMonth - 1;
                        $previousYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;

                        $q->whereMonth('fecha_vencimiento', '=', $previousMonth)
                            ->whereYear('fecha_vencimiento', '=', $previousYear);
                    });

                    // Mes siguiente
                    $query->orWhere(function ($q) use ($currentMonth, $currentYear) {
                        $nextMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
                        $nextYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;

                        $q->whereMonth('fecha_vencimiento', '=', $nextMonth)
                            ->whereYear('fecha_vencimiento', '=', $nextYear);
                    });

                    // Dos meses después
                    $query->orWhere(function ($q) use ($currentMonth, $currentYear) {
                        $twoMonthsLater = ($currentMonth >= 11) ? $currentMonth - 10 : $currentMonth + 2;
                        $twoYearsLater = ($currentMonth >= 11) ? $currentYear + 1 : $currentYear;

                        $q->whereMonth('fecha_vencimiento', '=', $twoMonthsLater)
                            ->whereYear('fecha_vencimiento', '=', $twoYearsLater);
                    });
                })
                ->where(function ($query) {
                    // ID del usuario autenticado
                    $userRole = Auth::user()->role_id;
                
                    // Si el usuario es ID 1, no filtrar por NIT (ver todo)
                    if ($userRole != 1)  {
                        // Obtener los NITs asociados al usuario
                        $nitsAsociados = Empresa::whereJsonContains('empleados', strval(Auth::user()->id))
                            ->pluck('NIT')
                            ->toArray();
                        if (!empty($nitsAsociados)) {
                            $query->whereIn('fechas_por_empresa_calendario_tributario.NIT', $nitsAsociados);
                        }
                    }
                })
                ->get();
                $event_requerimientos=$this->estructuracalendarioporobligacion($calendarios);
                $events=$this->obligacionesmunicipales();
                $events2=$this->obligacionesotrasentidades();
            }
           
        }else if(!empty($codigoobligacion)){
            $calendarios = FechasPorEmpresaCT::select('fechas_por_empresa_calendario_tributario.*',
            'empresas.razon_social',
            )
            ->join('empresas', 'empresas.NIT','=','fechas_por_empresa_calendario_tributario.NIT')
            ->where('codigo_tributario',$codigoobligacion)
            ->where(function ($query) {
                // ID del usuario autenticado
                $userRole = Auth::user()->role_id;
            
                // Si el usuario es ID 1, no filtrar por NIT (ver todo)
                if ($userRole == 1) {
                    // No agregamos ninguna restricción en el WHERE
                } else {
                    // Obtener los NITs asociados al usuario
                    $nitsAsociados = Empresa::whereJsonContains('empleados', strval($userRole))
                        ->pluck('NIT')
                        ->toArray();
            
                    // Aplicar filtro por los NITs del usuario
                    $query->whereIn('fechas_por_empresa_calendario_tributario.NIT', $nitsAsociados);
                }
            })->get();
            $event_requerimientos=$this->estructuracalendarioporobligacion($calendarios);
            $events=[];
            $events2=[];
        }else if(!empty($obligacionmunicipalcodigo)){
            $calendarios = FechasMunicipalesCT::select('fechas_municipales_ct.*',
            'empresas.razon_social',
            )
            ->join('empresas', 'empresas.NIT','=','fechas_municipales_ct.NIT')
            ->where('codigo_tributario',$obligacionmunicipalcodigo)
            ->where(function ($query) {
                // ID del usuario autenticado
                $userRole = Auth::user()->role_id;
            
                // Si el usuario es ID 1, no filtrar por NIT (ver todo)
                if ($userRole == 1) {
                    // No agregamos ninguna restricción en el WHERE
                } else {
                    // Obtener los NITs asociados al usuario
                    $nitsAsociados = Empresa::whereJsonContains('empleados', strval($userRole))
                        ->pluck('NIT')
                        ->toArray();
            
                    // Aplicar filtro por los NITs del usuario
                    $query->whereIn('fechas_municipales_ct.NIT', $nitsAsociados);
                }
            })->get();
            $event_requerimientos=[];
            $events=$this->estructuracalendarioporobligacionmunicipales($calendarios);
            $events2=[];
        }else if(!empty($otrasentidadescodigo)){
            $calendarios = FechasOtrasEntidadesCT::select('fechas_detalles_tributario.*',
            'empresas.razon_social',
            )
            ->join('empresas', 'empresas.NIT','=','fechas_detalles_tributario.NIT')
            ->where('codigo_tributario',$otrasentidadescodigo)
            ->where(function ($query) {
                // ID del usuario autenticado
                $userRole = Auth::user()->role_id;
            
                // Si el usuario es ID 1, no filtrar por NIT (ver todo)
                if ($userRole == 1) {
                    // No agregamos ninguna restricción en el WHERE
                } else {
                    // Obtener los NITs asociados al usuario
                    $nitsAsociados = Empresa::whereJsonContains('empleados', strval($userRole))
                        ->pluck('NIT')
                        ->toArray();
            
                    // Aplicar filtro por los NITs del usuario
                    $query->whereIn('fechas_detalles_tributario.NIT', $nitsAsociados);
                }
            })->get();
            $event_requerimientos=[];
            $events=[];
            $events2=$this->estructuaotrasentidad($calendarios);;
        }else if(!empty($responsableid)){
            $empresas = Empresa::whereJsonContains('empleados', (string) $responsableid)->get();
            // Obtener los NITs de las empresas asociadas
            $nitsempresas = $empresas->pluck('NIT')->toArray();
                
            // Obtener los calendarios tributarios relacionados con los NITs encontrados
            // DIAN
            $calendariosempresa = FechasPorEmpresaCT::select('fechas_por_empresa_calendario_tributario.*', 'empresas.razon_social')
                ->join('empresas', 'empresas.NIT', '=', 'fechas_por_empresa_calendario_tributario.NIT')
                ->whereIn('fechas_por_empresa_calendario_tributario.NIT', $nitsempresas)
                ->get();
            // Municipales
            $calendarios = FechasMunicipalesCT::select('fechas_municipales_ct.*', 'empresas.razon_social')
            ->join('empresas', 'empresas.NIT', '=', 'fechas_municipales_ct.NIT')
            ->whereIn('fechas_municipales_ct.NIT', $nitsempresas)
            ->get();
              // Otras entidades
            $calendarios2 = FechasOtrasEntidadesCT::select('fechas_detalles_tributario.*', 'empresas.razon_social')
            ->join('empresas', 'empresas.NIT', '=', 'fechas_detalles_tributario.NIT')
            ->whereIn('fechas_detalles_tributario.NIT', $nitsempresas)
            ->get();
            $event_requerimientos = [];
            $events = [];
            $events2 = [];
                            
            $eventosEmpresa =  $this->estructuracalendarioporobligacion($calendariosempresa);
                
            $event_requerimientos = array_merge($event_requerimientos, $eventosEmpresa);
    
            
            $eventsMunicipales = $this->estructuracalendarioporobligacionmunicipales($calendarios);
            $events = array_merge($events, $eventsMunicipales);
           
            $eventsOtrasEntidades = $this->estructuaotrasentidad($calendarios2);
            $events2 = array_merge($events2, $eventsOtrasEntidades);
        }else{
            $empresas = Empresa::find($idempresa);
            if(!empty($empresas->obligaciones)){
        
                $nit = $empresas->NIT;
                $event_requerimientos = $this->estructuracalendarioporempresa($nit);
            }else{
                $event_requerimientos = [];
            }
            if(!empty($empresas->codigo_obligacionmunicipal)){
                $obligacionesmunicipales =FechasMunicipalesCT::select('fechas_municipales_ct.*', 'empresas.razon_social','fechas_municipales_ct.detalle_tributario','fechas_municipales_ct.nombre_detalle')
                    ->join('empresas', 'empresas.NIT', '=', 'fechas_municipales_ct.NIT')
                    ->where('fechas_municipales_ct.NIT', $nit)
                    ->distinct()
                    ->get();
            }else{
                $obligacionesmunicipales = [];
                
            }
            if(!empty($empresas->otras_entidades)){
                $obligacionesotrasentidades =FechasOtrasEntidadesCT::select('fechas_detalles_tributario.*','empresas.razon_social')
                ->join('empresas','empresas.NIT','fechas_detalles_tributario.NIT') 
                ->where('fechas_detalles_tributario.NIT', $nit)
                ->get();
            }else{
                $obligacionesotrasentidades=[];
            }
            foreach ($obligacionesmunicipales as $obligacion) {
                if (!empty($obligacion->fecha_revision)) {
                    $color = '#f5ca05'; //el campo revisado no esta vacio
                    $textColor = '#000';
                }else {
                    $color = '#0066ff'; //el campo revisado esta vacio
                    $textColor = '#fff';
                }
                $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $obligacion->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
                // $fechaCreacion = Carbon::parse($calendario->requerimientos->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY h:m a');
                $empresa=Empresa::select('razon_social')->where('NIT',$nit)->value('razon_social');
                $events[] = [
                    'id' => $obligacion->id,
                    'title' => $obligacion->codigo_municipio.'-'.$obligacion->nombre,
                    'description' =>
                    '<b>Obligación Municipal: </b>' . $obligacion->codigo_municipio.'-'.$obligacion->nombre
                        . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                        . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$obligacion->detalle_tributario
                        . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$obligacion->nombre_detalle,
                    'start' => $obligacion->fecha_vencimiento,
                    'end' => $obligacion->fecha_vencimiento,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'textColor' => $textColor,
                    'revision' => $obligacion->fecha_revision,
                    'observacion' => $obligacion->observacion,
                    'tipo' => 2,
                    'correo' => $obligacion->correo,
                    'empresa' =>$empresa,
                    'obligacion' =>$obligacion->nombre,
                    'fecha' =>$fechaVencimiento,
                    'whatsapp' => $obligacion->whatsapp,
                    'revisor' => $obligacion->revisor
                ];
                
            }  
            $events2=$this->estructuaotrasentidad($obligacionesotrasentidades);
        }

        $resultado = [
            'events' => $events,
            'event_requerimientos' => $event_requerimientos,
            'events2' => $events2,
            'festivos' => $this->getFestivos()
        ];

        return $resultado;
    }

    private function estructuracalendarioporempresa($nit){
        $calendarios =FechasPorEmpresaCT::where('NIT',$nit)->get();
        $empresa=Empresa::select('razon_social')->where('NIT',$nit)->value('razon_social');
        $event_requerimientos=[];      
        foreach ($calendarios as $calendario) {
            
            if (!empty($calendario->fecha_revision)) {
                $color = '#f5ca05'; //el campo revisado no esta vacio
                $textColor = '#000';
            }else {
                $color = '#0da13c'; //el campo revisado esta vacio
                $textColor = '#fff';
            }
            $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $calendario->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
            $event_requerimientos[] = [
                'id' => $calendario->id,
                'title' =>  $calendario->codigo_tributario.'-'.$calendario->nombre,
                'description' =>
                '<b>Obligación DIAN: </b>' . $calendario->nombre
                . '</br><div class"py-3"></div>' . '<b>Código Tributario: </b> ' .$calendario->codigo_tributario
                . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$calendario->detalle_tributario
                . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$calendario->nombre_detalle,
                'start' => $calendario->fecha_vencimiento,
                'end' => $calendario->fecha_vencimiento,
                'allDay' => true,
                'backgroundColor' => $color,
                'textColor' => $textColor,
                'revision' => $calendario->fecha_revision,
                'observacion' => $calendario->observacion,
                'tipo' => 1,
                'correo' => $calendario->correo,
                'empresa' => $empresa,
                'obligacion' => $calendario->nombre,
                'fecha' => $fechaVencimiento,
                'whatsapp' => $calendario->whatsapp,
                'revisor' => $calendario->revisor
            ];
            
        }
        return $event_requerimientos;
    }
    private function estructuracalendarioporobligacion($calendarios){
        $event_requerimientos=[];
        foreach ($calendarios as $calendario) {
                
            if (!empty($calendario->fecha_revision)) {
                $color = '#f5ca05'; //el campo revisado no esta vacio
                $textColor = '#000';
            }else {
                $color = '#0da13c'; //el campo revisado esta vacio
                $textColor = '#fff';
            }
            $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $calendario->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
            $event_requerimientos[] = [
                'id' => $calendario->id,
                'title' =>  $calendario->codigo_tributario.'-'.$calendario->razon_social.'-'.$calendario->nombre,
                'description' =>
                '<b>Obligación DIAN: </b>' . $calendario->nombre
                . '</br><div class"py-3"></div>' . '<b>Código Tributario: </b> ' .$calendario->codigo_tributario
                . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$calendario->detalle_tributario
                . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$calendario->nombre_detalle,
                'start' => $calendario->fecha_vencimiento,
                'end' => $calendario->fecha_vencimiento,
                'allDay' => true,
                'backgroundColor' => $color,
                'textColor' => $textColor ,
                'revision' => $calendario->fecha_revision,
                'observacion' => $calendario->observacion,
                'tipo' => 1,
                'correo' =>$calendario->correo,
                'empresa' =>$calendario->razon_social,
                'obligacion' =>$calendario->nombre,
                'fecha' =>$fechaVencimiento,
                'whatsapp' => $calendario->whatsapp,
                'revisor' => $calendario->revisor
            ];
            
        }
        return $event_requerimientos;
    }

    private function estructuracalendarioporobligacionmunicipales($calendarios){
        $events=[];
        foreach ($calendarios as $calendario) {
       
            if (!empty($calendario->fecha_revision)) {
                $color = '#f5ca05'; //el campo revisado no esta vacio
                $textColor = '#000';
                $fecha_revision = $calendario->fecha_revision;
            }else {
                $color = '#0066ff'; //el campo revisado esta vacio
                $textColor = '#fff';
                $fecha_revision = NULL;
            }
            $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $calendario->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
            $events[] = [
                'id' => $calendario->id,
                'title' => $calendario->codigo_municipio.'-'.$calendario->razon_social.'-'.$calendario->nombre,
                'description' =>
                '<b>Obligación Municipal: </b>' . $calendario->codigo_municipio.'-'.$calendario->nombre
                    . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                    . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$calendario->detalle_tributario
                    . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$calendario->nombre_detalle,
                'start' => $calendario->fecha_vencimiento,
                'end' => $calendario->fecha_vencimiento,
                'allDay' => true,
                'backgroundColor' => $color,
                'textColor' => $textColor ,
                'revision' => $fecha_revision,
                'observacion' => $calendario->observacion,
                'tipo' => 2,
                'correo' => $calendario->correo,
                'empresa' => $calendario->razon_social,
                'obligacion' => $calendario->nombre,
                'fecha' => $fechaVencimiento,
                'whatsapp' => $calendario->whatsapp,
                'revisor' => $calendario->revisor
            ];
            
        }
        return $events;
    }

    private function estructuaotrasentidad($obligacionesotrasentidades){
        if(count($obligacionesotrasentidades)>=1){
            foreach ($obligacionesotrasentidades as $obligacion) {
                if (!empty($obligacion->fecha_revision)) {
                    $color = '#f5ca05'; //el campo revisado no esta vacio
                    $textColor = '#000';
                    $fecha_revision = $obligacion->fecha_revision;
                }else {
                    $color = '#7517b8'; //el campo revisado esta vacio
                    $textColor = '#fff';
                    $fecha_revision = NULL;
                }
                $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $obligacion->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
                $events2[] = [
                    'id' => $obligacion->id,
                    'title' => $obligacion->codigo_otraentidad.'-'.$obligacion->razon_social.'-'.$obligacion->nombre,
                    'description' =>
                    '<b>Obligación Otras entidades: </b>' . $obligacion->codigo_otraentidad.'-'.$obligacion->nombre
                        . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                        . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$obligacion->detalle_tributario
                        . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$obligacion->nombre_detalle,
                    'start' => $obligacion->fecha_vencimiento,
                    'end' => $obligacion->fecha_vencimiento,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'textColor' => $textColor ,
                    'revision' => $fecha_revision,
                    'observacion' => $obligacion->observacion,
                    'tipo' => 3,
                    'correo' => $obligacion->correo,
                    'empresa' => $obligacion->razon_social,
                    'obligacion' => $obligacion->nombre,
                    'fecha' => $fechaVencimiento,
                    'whatsapp' => $obligacion->whatsapp,
                    'revisor' => $obligacion->revisor
                ];
                
            }
        }else{
            $events2 = [];
        }
        return $events2;
    }
    private function obligacionesmunicipales(){
        $events = [];
        if(in_array(Auth::user()->role_id, [7])){
            $empresaId = EmpleadoCliente::select('empresa_id','empresas_secundarias')->where('user_id', Auth::id())->first();
            $ids = $empresaId->empresas_secundarias ? json_decode($empresaId->empresas_secundarias, true) : [];
            
            // Asegúrate de que $ids sea un array, incluso si está vacío o es nulo
            $ids = is_array($ids) ? $ids : [];

            // Verifica si $ids no está vacío antes de realizar operaciones
            $nits = [];
            if (!empty($ids)) {
                $nits = array_merge([$empresaId->empresas->NIT], Empresa::select('NIT')->whereIn('id', $ids)->get()->pluck('NIT')->toArray());
            } else {
                // Si $ids está vacío, simplemente agregamos el NIT de la empresa principal
                $nits = [$empresaId->empresas->NIT];
            }
            if($nits){
                $obligacionesmunicipales =FechasMunicipalesCT::select('fechas_municipales_ct.*','empresas.razon_social','fechas_municipales_ct.detalle_tributario','fechas_municipales_ct.nombre_detalle','fechas_municipales_ct.correo')
                    ->join('empresas','empresas.NIT','fechas_municipales_ct.NIT') 
                    ->whereIN('fechas_municipales_ct.NIT', $nits)
                    ->get();
            }else{
                $obligacionesmunicipales=[];
            }
        }else{
            
            $obligacionesmunicipales = FechasMunicipalesCT::select('fechas_municipales_ct.id','fechas_municipales_ct.fecha_vencimiento','fechas_municipales_ct.fecha_revision','fechas_municipales_ct.observacion','fechas_municipales_ct.nombre', 'fechas_municipales_ct.codigo_municipio','empresas.razon_social','fechas_municipales_ct.detalle_tributario','fechas_municipales_ct.nombre_detalle','fechas_municipales_ct.correo','fechas_municipales_ct.whatsapp','fechas_municipales_ct.revisor')   
            ->join('empresas','empresas.NIT','fechas_municipales_ct.NIT') 
            ->where(function ($query) {
                $now = Carbon::now();
                $currentMonth = $now->format('m');
                $currentYear = $now->format('Y');
            
                // Filtrar fechas por rango de meses
                $query->where(function ($q) use ($currentMonth, $currentYear) {
                    // Mes actual
                    $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                        $q1->whereMonth('fechas_municipales_ct.fecha_vencimiento', '=', $currentMonth)
                            ->whereYear('fechas_municipales_ct.fecha_vencimiento', '=', $currentYear);
                    });
            
                    // Mes anterior
                    $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                        $previousMonth = ($currentMonth == 1) ? 12 : $currentMonth - 1;
                        $previousYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;
            
                        $q1->whereMonth('fechas_municipales_ct.fecha_vencimiento', '=', $previousMonth)
                            ->whereYear('fechas_municipales_ct.fecha_vencimiento', '=', $previousYear);
                    });
            
                    // Mes siguiente
                    $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                        $nextMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
                        $nextYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;
            
                        $q1->whereMonth('fechas_municipales_ct.fecha_vencimiento', '=', $nextMonth)
                            ->whereYear('fechas_municipales_ct.fecha_vencimiento', '=', $nextYear);
                    });
            
                    // Dos meses después
                    $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                        $twoMonthsLater = ($currentMonth >= 11) ? $currentMonth - 10 : $currentMonth + 2;
                        $twoYearsLater = ($currentMonth >= 11) ? $currentYear + 1 : $currentYear;
            
                        $q1->whereMonth('fechas_municipales_ct.fecha_vencimiento', '=', $twoMonthsLater)
                            ->whereYear('fechas_municipales_ct.fecha_vencimiento', '=', $twoYearsLater);
                    });
                });
            
                // Filtrar NITs basados en el usuario logueado
                $query->where(function ($q) {
                    $userRole = Auth::user()->role_id;

                    if ($userRole != 1) {
                        // Obtener los NITs asociados al usuario
                        $nitsAsociados = Empresa::whereJsonContains('empleados', strval(Auth::user()->id))
                            ->pluck('NIT')
                            ->toArray();
                        // Aplicar filtro por los NITs del usuario
                        $q->whereIn('fechas_municipales_ct.NIT', $nitsAsociados);
                    }
                });
            })->get();

        }
        if(count($obligacionesmunicipales)>=1){
            foreach ($obligacionesmunicipales as $obligacion) {
                if (!empty($obligacion->fecha_revision)) {
                    $color = '#f5ca05'; //el campo revisado no esta vacio
                    $textColor = '#000';
                    $fecha_revision = $obligacion->fecha_revision;
                }else {
                    $color = '#0066ff'; //el campo revisado esta vacio
                    $textColor = '#fff';
                    $fecha_revision = NULL;
                }
                $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $obligacion->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
                // $fechaCreacion = Carbon::parse($calendario->requerimientos->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY h:m a');
    
                $events[] = [
                    'id' => $obligacion->id,
                    'title' => $obligacion->codigo_municipio.'-'.$obligacion->razon_social.'-'.$obligacion->nombre,
                    'description' =>
                    '<b>Obligación Municipal: </b>' . $obligacion->codigo_municipio.'-'.$obligacion->nombre
                        . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                        . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$obligacion->detalle_tributario
                        . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$obligacion->nombre_detalle,
                    'start' => $obligacion->fecha_vencimiento,
                    'end' => $obligacion->fecha_vencimiento,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'textColor' => $textColor ,
                    'revision' => $fecha_revision,
                    'observacion' => $obligacion->observacion,
                    'tipo' => 2,
                    'correo' => $obligacion->correo,
                    'empresa' => $obligacion->razon_social,
                    'obligacion' => $obligacion->nombre,
                    'fecha' => $fechaVencimiento,
                    'whatsapp' => $obligacion->whatsapp,
                    'revisor' => $obligacion->revisor
                ];
                
            }
        }else{
            $events = [];
        }
            
        
      
        return $events;
    }


    private function obligacionesotrasentidades(){
        $events2 = [];
        $obligacionesotrasentidades=[];
        if(in_array(Auth::user()->role_id, [7])){
            $empresaId = EmpleadoCliente::select('empresa_id','empresas_secundarias')->where('user_id', Auth::id())->first();
            $ids = $empresaId->empresas_secundarias ? json_decode($empresaId->empresas_secundarias, true) : [];
            
            // Asegúrate de que $ids sea un array, incluso si está vacío o es nulo
            $ids = is_array($ids) ? $ids : [];

            // Verifica si $ids no está vacío antes de realizar operaciones
            $nits = [];
            if (!empty($ids)) {
                $nits = array_merge([$empresaId->empresas->NIT], Empresa::select('NIT')->whereIn('id', $ids)->get()->pluck('NIT')->toArray());
            } else {
                // Si $ids está vacío, simplemente agregamos el NIT de la empresa principal
                $nits = [$empresaId->empresas->NIT];
            }
            if($nits){
                $obligacionesotrasentidades =FechasOtrasEntidadesCT::select('fechas_detalles_tributario.*','empresas.razon_social','fechas_detalles_tributario.detalle_tributario','fechas_detalles_tributario.nombre_detalle','fechas_detalles_tributario.correo')
                    ->join('empresas','empresas.NIT','fechas_detalles_tributario.NIT') 
                    ->whereIN('fechas_detalles_tributario.NIT', $nits)
                    ->get();
                }else{
                $obligacionesotrasentidades=[];
            }
        }else{
            $obligacionesotrasentidades = FechasOtrasEntidadesCT::select('fechas_detalles_tributario.id','fechas_detalles_tributario.fecha_vencimiento','fechas_detalles_tributario.fecha_revision','fechas_detalles_tributario.observacion','fechas_detalles_tributario.nombre', 'empresas.razon_social','fechas_detalles_tributario.codigo_otraentidad','fechas_detalles_tributario.detalle_tributario','fechas_detalles_tributario.nombre_detalle','fechas_detalles_tributario.correo','fechas_detalles_tributario.whatsapp','fechas_detalles_tributario.revisor')   
            ->join('empresas','empresas.NIT','fechas_detalles_tributario.NIT') 
            ->where(function ($query) {
                $now = Carbon::now();
                $currentMonth = $now->format('m');
                $currentYear = $now->format('Y');

                    // Filtrar fechas por rango de meses
                    $query->where(function ($q) use ($currentMonth, $currentYear) {
                        // Mes actual
                        $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                            $q1->whereMonth('fechas_detalles_tributario.fecha_vencimiento', '=', $currentMonth)
                                ->whereYear('fechas_detalles_tributario.fecha_vencimiento', '=', $currentYear);
                        });

                        // Mes anterior
                        $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                            $previousMonth = ($currentMonth == 1) ? 12 : $currentMonth - 1;
                            $previousYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;

                            $q1->whereMonth('fechas_detalles_tributario.fecha_vencimiento', '=', $previousMonth)
                                ->whereYear('fechas_detalles_tributario.fecha_vencimiento', '=', $previousYear);
                        });

                        // Mes siguiente
                        $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                            $nextMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
                            $nextYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;

                            $q1->whereMonth('fechas_detalles_tributario.fecha_vencimiento', '=', $nextMonth)
                                ->whereYear('fechas_detalles_tributario.fecha_vencimiento', '=', $nextYear);
                        });

                        // Dos meses después
                        $q->orWhere(function ($q1) use ($currentMonth, $currentYear) {
                            $twoMonthsLater = ($currentMonth >= 11) ? $currentMonth - 10 : $currentMonth + 2;
                            $twoYearsLater = ($currentMonth >= 11) ? $currentYear + 1 : $currentYear;

                            $q1->whereMonth('fechas_detalles_tributario.fecha_vencimiento', '=', $twoMonthsLater)
                                ->whereYear('fechas_detalles_tributario.fecha_vencimiento', '=', $twoYearsLater);
                        });
                    });

                    // Filtrar NITs basados en el usuario logueado
                    $query->where(function ($q) {
                        $userRole = Auth::user()->role_id;

                        if ($userRole != 1) {
                            // Obtener los NITs asociados al usuario
                            $nitsAsociados = Empresa::whereJsonContains('empleados', strval(Auth::user()->id))
                                ->pluck('NIT')
                                ->toArray();

                            // Aplicar filtro por los NITs del usuario
                            $q->whereIn('fechas_detalles_tributario.NIT', $nitsAsociados);
                        }
                    });  
            })->get();

        }
        if(count($obligacionesotrasentidades)>=1){
            foreach ($obligacionesotrasentidades as $obligacion) {
                if (!empty($obligacion->fecha_revision)) {
                    $color = '#f5ca05'; //el campo revisado no esta vacio
                    $textColor = '#000';
                    $fecha_revision = $obligacion->fecha_revision;
                }else {
                    $color = '#7517b8'; //el campo revisado esta vacio
                    $textColor = '#fff';
                    $fecha_revision = NULL;
                }
                $fechaVencimiento = Carbon::createFromFormat('Y-m-d', $obligacion->fecha_vencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
                $events2[] = [
                    'id' => $obligacion->id,
                    'title' => $obligacion->codigo_otraentidad.'-'.$obligacion->razon_social.'-'.$obligacion->nombre,
                    'description' =>
                    '<b>Obligación Otras entidades: </b>' . $obligacion->codigo_otraentidad.'-'.$obligacion->nombre
                    . '</br><div class"py-3"></div>' . '<b>Fecha de vencimiento: </b> ' .$fechaVencimiento
                    . '</br><div class"py-3"></div>' . '<b>Detalle Tributario: </b> ' .$obligacion->detalle_tributario
                    . '</br><div class"py-3"></div>' . '<b>Nombre Detalle: </b> ' .$obligacion->nombre_detalle,
                    'start' => $obligacion->fecha_vencimiento,
                    'end' => $obligacion->fecha_vencimiento,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'textColor' => $textColor ,
                    'revision' => $fecha_revision,
                    'observacion' => $obligacion->observacion,
                    'tipo' => 3,
                    'correo' => $obligacion->correo,
                    'empresa' => $obligacion->razon_social,
                    'obligacion' => $obligacion->nombre,
                    'fecha' => $fechaVencimiento,
                    'whatsapp' => $obligacion->whatsapp,
                    'revisor' => $obligacion->revisor
                ];
                
            }
        }else{
            $events2 = [];
        }
            
        
      
        return $events2;
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
