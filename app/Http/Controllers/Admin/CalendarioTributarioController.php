<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExcelPlantillaCalendarioExport as ExportsExcelPlantillaCalendarioExport;
use App\Exports\InformeTributarioMultipleExport;
use App\Http\Controllers\Traits\CalendarioTributario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ExcelCalendarioTributarioBatchImport;
use App\Jobs\ExcelPlantillaCalendarioExport;
use App\Jobs\GenerarInformeTributario;
use App\Mail\CalendarioTributarioMail;
use App\Mail\NotificacionCalendario;
use App\Models\calendario_tributario;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\FechasMunicipalesCT;
use App\Models\FechasOtrasEntidadesCT;
use App\Models\FechasPorEmpresaCT;
use App\Models\Notificacion;
use App\Models\ObligacionDian;
use App\Models\ObligacionesMunicipalesDian;
use App\Models\OtrasEntidadesCT;
use App\Models\User;
use App\Notifications\CalendarioTributarioNotification;
use App\Notifications\MessageSent;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CalendarioTributarioController extends Controller
{
    public function index()  {

        abort_if(Gate::denies('ACCEDER_CALENDARIO_TRIBUTARIO'), Response::HTTP_UNAUTHORIZED);
        //select de empresas
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }else if(Auth::user()->role_id == 7){
            // Obtener el EmpleadoCliente
            $empleadoCliente = EmpleadoCliente::where('user_id', Auth::id())->first();
            // Obtener empresa_id principal
            $empresaPrincipalId = $empleadoCliente->empresa_id;
            // Decodificar empresas_secundarias (asegúrate de que sea un JSON válido)
            $empresasSecundarias = json_decode($empleadoCliente->empresas_secundarias, true) ?? [];
            // Combinar ambos arrays y eliminar duplicados
            $empresaIds = collect($empresasSecundarias)->push($empresaPrincipalId)->unique()->values();
            // Obtener las empresas por ID
            $empresas = Empresa::whereIn('id', $empresaIds)
                        ->orderBy('razon_social')
                        ->select('id', 'razon_social') 
                        ->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }
        
        $obligaciones = ObligacionDian::all()->sortBy('codigo');
        $obligacionesmunicipales = ObligacionesMunicipalesDian::all()->sortBy('codigo');
        $otrasentidades = OtrasEntidadesCT::all()->sortBy('codigo');
        $responsables = User::orderBy('nombres')->selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->whereNotIn('role_id', [7])->pluck('nombre_completo', 'id')->toArray();
        $resultado = $this->getCalendariotributario();    
        $events = $resultado['events'];
        $events2 = $resultado['events2'];
        $event_requerimientos = $resultado['event_requerimientos'];  
        $festivos = $resultado['festivos'];
        $cantEvents = 'vacio';
        $nombreempresa='vacio';
        $nombreresponsable = 'vacio';
        return view('admin.calendariotributario.index',compact('empresas','events', 'event_requerimientos', 'festivos', 'cantEvents','nombreempresa','obligaciones','events2','obligacionesmunicipales','otrasentidades','responsables','nombreresponsable'));
    }

    public function table(Request $request){
        abort_if(Gate::denies('ACCEDER_CALENDARIO_TRIBUTARIO'), Response::HTTP_UNAUTHORIZED);

        if($request->ajax())
        {
            $calendario = calendario_tributario::orderBy('id', 'desc');//hace la consulta a la base de datos
            return DataTables::of($calendario)
                ->make(true);
        }
        return view('admin.calendariotributario.table');
    }

    public function create()  {

        abort_if(Gate::denies('CREAR_CALENDARIO_TRIBUTARIO'), Response::HTTP_UNAUTHORIZED);

        return view('admin.calendariotributario.masivocalendario');
    }
    
    public function masivo(Request $request){
        //obtener el archivo  
        $file = $request->file('masivo');
        //validar si se cargo el archivo
        if (!isset($file)) {
                return back()->with('message2', 'Por favor, cargue el archivo correspondiente para continuar.')->with('color', 'warning');
        }
        $job = new ExcelCalendarioTributarioBatchImport($file->getRealPath());
        dispatch($job);
        return back();
    }
    use CalendarioTributario;
    public function calendarioobligaciones(Request $request){
        //select de empresas
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        }else if(Auth::user()->role_id == 7){
            // Obtener el EmpleadoCliente
            $empleadoCliente = EmpleadoCliente::where('user_id', Auth::id())->first();
            // Obtener empresa_id principal
            $empresaPrincipalId = $empleadoCliente->empresa_id;
            // Decodificar empresas_secundarias (asegúrate de que sea un JSON válido)
            $empresasSecundarias = json_decode($empleadoCliente->empresas_secundarias, true) ?? [];
            // Combinar ambos arrays y eliminar duplicados
            $empresaIds = collect($empresasSecundarias)->push($empresaPrincipalId)->unique()->values();
            // Obtener las empresas por ID
            $empresas = Empresa::whereIn('id', $empresaIds)
                        ->orderBy('razon_social')
                        ->select('id', 'razon_social') 
                        ->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }
        $obligaciones = ObligacionDian::all()->sortBy('codigo');
        $obligacionesmunicipales = ObligacionesMunicipalesDian::all()->sortBy('codigo');
        $otrasentidades = OtrasEntidadesCT::all()->sortBy('codigo');
        $responsables = User::orderBy('nombres')->selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->whereNotIn('role_id', [7])->pluck('nombre_completo', 'id')->toArray();
        $empresaid = $this->separarnombre($request->empresa_id);
        $obligaciondiancodigo = $this->separarnombre($request->codigoobligacion);
        $obligacionmunicipalcodigo =$this->separarnombre(($request->codigoobligacionm));
        $otrasentidadescodigo =$this->separarnombre(($request->codigootraentidades));
        $responsablesid =$this->separarnombre(($request->responsable));
        $nombreobligacion = $request->codigoobligacion ?? $request->codigoobligacionm ?? $request->codigootraentidades ?? "Sin datos";
        $resultado = $this->getCalendariotributario($empresaid,$obligaciondiancodigo,$obligacionmunicipalcodigo,$otrasentidadescodigo,$responsablesid);    
        $events = $resultado['events'];
        $events2 = $resultado['events2'];
        $event_requerimientos = $resultado['event_requerimientos'];  
        $festivos = $resultado['festivos'];
        $cantEvents =  count($event_requerimientos) + count($events) + count($events2);
        $nombreempresa=Empresa::select('razon_social')->where('id',$request->empresa_id)->first();
        $nombreresponsable = User::selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo')
        ->where('id', $responsablesid)
        ->first();
        return view('admin.calendariotributario.index',compact('empresas','events', 'event_requerimientos', 'festivos', 'cantEvents','nombreempresa','obligaciones','events2','nombreobligacion','obligacionesmunicipales','otrasentidades','responsables','nombreresponsable'));
    }

    private function separarnombre($id){
        $empresaid = $id;
        $parts2 = explode('-', $empresaid);
        $numeroid = trim($parts2[0]);
        return $numeroid;
    }

    public function exportExcelActualizarcalendario()
    {
        $calendario =calendario_tributario::select('codigo_tributario','detalle_tributario','ultimos_digitos',
        'ultimo_digito','rango_inicial','rango_final','fecha_vencimiento','codigo_municipio'
        )->get();
        $export = new ExportsExcelPlantillaCalendarioExport([$calendario]);
        return Excel::download($export, 'MasivoCalendario.xlsx');
    }

    
    
     public function marcarRevisado(Request $request)
    {
        $evento = FechasPorEmpresaCT::find($request->id);
        $observacion = $request->observacion !== 'null' ? urldecode($request->observacion) : null;

        if ($evento) {
            $evento->fecha_revision = $request->check === 'true' ? now() : null;
            $evento->observacion = $observacion;
            $evento->save();
        }

        return response()->json([
            'success' => true,
            'fecha_revision' => $evento->fecha_revision
        ]);
    }


    public function marcarRevisado2(Request $request)
    {
        $evento = FechasMunicipalesCT::find($request->id);
        $observacion = $request->observacion !== 'null' ? urldecode($request->observacion) : null;
        if ($evento) {
            $evento->fecha_revision = $request->check === 'true' ? now() : null;
            $evento->observacion = $observacion;
            $evento->save();
        }
        // Envía la variable fecha_revision junto con la respuesta JSON
        return response()->json(['success' => true, 'fecha_revision' => $evento->fecha_revision]);
    }

    public function marcarRevisado3(Request $request)
    {
        $evento = FechasOtrasEntidadesCT::find($request->id);
        $observacion = $request->observacion !== 'null' ? urldecode($request->observacion) : null;

        if ($evento) {
            $evento->fecha_revision = $request->check === 'true' ? now() : null;
            $evento->observacion = $observacion;
            $evento->save();
        }
        // Envía la variable fecha_revision junto con la respuesta JSON
        return response()->json(['success' => true, 'fecha_revision' => $evento->fecha_revision]);
    }

    public function Correonotificaciontb(Request $request){
            $request->validate([
                'nombre_empresa' => 'required|string|max:255',
                'fecha_vencimiento' => 'required',
                'obligacion' => 'required',
                'observacion_correo' => 'nullable|string|max:255',
                'notificacion_pdf' => 'nullable',
                'notificacion_pdf.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:2048',
                'id' => 'required',
                'correos_adicionales' => 'nullable|string'
            ]);
            $id=$request->id;
            $obligacion=$request->obligacion;
            $data = $request->only('nombre_empresa', 'fecha_vencimiento', 'observacion_correo','obligacion');
             $archivosCargados = [];
            if ($request->hasFile('notificacion_pdf')) {
                foreach ($request->file('notificacion_pdf') as $archivo) {
                    
                    $nombreOriginal = $archivo->getClientOriginalName();
                    $extension = $archivo->getClientOriginalExtension();

                    // 👉 nombre único en disco
                    $nombreUnico = Str::uuid() . '.' . $extension;

                    // Guardar archivo
                    $path = $archivo->storeAs('adjuntos', $nombreUnico, 'public');

                    // Guardamos info completa (NO solo string)
                    $archivosCargados[] = [
                        'path' => $path,
                        'original' => $nombreOriginal
                    ];
                }
            }
            $data['notificacion_pdf'] = $archivosCargados;
            // Obtener el usuario autenticado
            $user = Auth::user();
            $data['usuario'] = $user->id; // O el campo que desees, como $user->name, $user->email, etc.
            $correo = Empresa::where('razon_social', $data['nombre_empresa'])->value('correo_electronico');
            // Verificar si hay correos adicionales
             $correosAdicionales = $request->input('correos_adicionales');
             $correosArray = []; // Inicializar como un array vacío por defecto
             if (!empty($correosAdicionales)) {
                 $correosArray = array_map('trim', explode(',', $correosAdicionales));
                 if (!array_reduce($correosArray, fn($carry, $correo) => $carry && filter_var($correo, FILTER_VALIDATE_EMAIL), true)) {
                     return redirect()->back()->with('messagec', 'Uno o más correos adicionales no son válidos.')->with('color', 'danger');
                 }
             }
             $correos = array_merge([$correo], $correosArray);
             // Guardar los correos en el campo 'correos' del arreglo $data
             $data['correos'] = implode(',', $correos);
             try {
                 Mail::to($correo)->cc($correosArray)->send(new NotificacionCalendario($data));
                 $ziparchivos = $this->ziparchivos($archivosCargados, $obligacion); // Pasar el nombre del ZIP
                 $data['notificacion_pdf'] = $ziparchivos;
                 Notificacion::create($data);
                 $valor = $this->revisarcorreoenviado($id, $obligacion);
                 return redirect()->to('admin/calendariotributario/index ')->with('messagec', 'Correo enviado con éxito')->with('color', 'success');
             } catch (\Exception $e) {
                Log::error($e);
                 return redirect()->to('admin/calendariotributario/index ')->with('messagec', 'Error al enviar el correo')->with('color', 'danger');
             }
            
            
    }

    private function ziparchivos(array $archivosCargados, $obligacion)
    {
        $zipFileName = "{$obligacion}_" . now()->format('Ymd_His') . ".zip";
        $zipPath = storage_path("app/public/adjuntos/{$zipFileName}");

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {

            foreach ($archivosCargados as $archivo) {

                $filePath = storage_path("app/public/{$archivo['path']}");

                if (file_exists($filePath)) {
                    // 👉 nombre ORIGINAL dentro del ZIP
                    $zip->addFile($filePath, $archivo['original']);
                }
            }

            $zip->close();
        }

        return "adjuntos/{$zipFileName}";
    }


    private function revisarcorreoenviado($id,$obligacion){
        $newValue = '1'; // El nuevo valor para el campo a actualizar
        // Extraer la parte después del "-"
        $obligacionPartes = explode('-', $obligacion);
        $obligacionKey = count($obligacionPartes) > 1 ? trim($obligacionPartes[1]) : trim($obligacion);

        // Buscar en la primera tabla
        $notificacion1 = FechasPorEmpresaCT::where('id', $id)
            ->where('nombre', 'LIKE', '%' . $obligacionKey . '%')
            ->first();
        if ($notificacion1) {
            // Actualizar el campo deseado
            $notificacion1->correo = $newValue;
            $notificacion1->save();
        } else {
            // Buscar en la segunda tabla
            $notificacion2 = FechasMunicipalesCT::where('id', $id)
                ->where('nombre', 'LIKE', '%' . $obligacionKey . '%')
                ->first();
            if ($notificacion2) {
                // Actualizar el campo deseado
                $notificacion2->correo = $newValue;
                $notificacion2->save();
            } else {
                // Buscar en la tercera tabla
                $notificacion3 = FechasOtrasEntidadesCT::where('id', $id)
                    ->where('nombre', 'LIKE', '%' . $obligacionKey . '%')
                    ->first();
                if ($notificacion3) {
                    // Actualizar el campo deseado
                    $notificacion3->correo = $newValue;
                    $notificacion3->save();
                }
            }
        }
    }

    private function revisarwhatsappenviado($id,$obligacion){
        $newValue = '1'; // El nuevo valor para el campo a actualizar
        // Extraer la parte después del "-"
        $obligacionPartes = explode('-', $obligacion);
        $obligacionKey = count($obligacionPartes) > 1 ? trim($obligacionPartes[1]) : trim($obligacion);
        // Buscar en la primera tabla
        $notificacion1 = FechasPorEmpresaCT::where('id', $id)
            ->where('nombre', 'LIKE', '%' . $obligacion . '%')
            ->first();
        if ($notificacion1) {
            // Actualizar el campo deseado
            $notificacion1->whatsapp = $newValue;
            $notificacion1->save();
        } else {
            // Buscar en la segunda tabla
            $notificacion2 = FechasMunicipalesCT::where('id', $id)
                ->where('nombre', 'LIKE', '%' . $obligacion . '%')
                ->first();
            if ($notificacion2) {
                // Actualizar el campo deseado
                $notificacion2->whatsapp = $newValue;
                $notificacion2->save();
            } else {
                // Buscar en la tercera tabla
                $notificacion3 = FechasOtrasEntidadesCT::where('id', $id)
                    ->where('nombre', 'LIKE', '%' . $obligacion . '%')
                    ->first();
                if ($notificacion3) {
                    // Actualizar el campo deseado
                    $notificacion3->whatsapp = $newValue;
                    $notificacion3->save();
                }
            }
        }
    }

    private function revisarrevisorenviado($id,$obligacion){
        $newValue = '1'; // El nuevo valor para el campo a actualizar
        // Extraer la parte después del "-"
        $obligacionPartes = explode('-', $obligacion);
        $obligacionKey = count($obligacionPartes) > 1 ? trim($obligacionPartes[1]) : trim($obligacion);
        // Buscar en la primera tabla
        $notificacion1 = FechasPorEmpresaCT::where('id', $id)
            ->where('nombre', 'LIKE', '%' . $obligacion . '%')
            ->first();
        if ($notificacion1) {
            // Actualizar el campo deseado
            $notificacion1->revisor = $newValue;
            $notificacion1->save();
        } else {
            // Buscar en la segunda tabla
            $notificacion2 = FechasMunicipalesCT::where('id', $id)
                ->where('nombre', 'LIKE', '%' . $obligacion . '%')
                ->first();
            if ($notificacion2) {
                // Actualizar el campo deseado
                $notificacion2->revisor = $newValue;
                $notificacion2->save();
            } else {
                // Buscar en la tercera tabla
                $notificacion3 = FechasOtrasEntidadesCT::where('id', $id)
                    ->where('nombre', 'LIKE', '%' . $obligacion . '%')
                    ->first();
                if ($notificacion3) {
                    // Actualizar el campo deseado
                    $notificacion3->revisor = $newValue;
                    $notificacion3->save();
                }
            }
        }
    }

    public function Notificaciontable(Request $request){
        abort_if(Gate::denies('ACCEDER_CALENDARIO_TRIBUTARIO'), Response::HTTP_UNAUTHORIZED);
        $user = Auth::user();
        if ($request->ajax()) {
            if($user->role_id == 7){
                $empleadoCliente=EmpleadoCliente::where('user_id',$user->id)->first();
                $empresa = $empleadoCliente->empresas->razon_social;
                $calendario = Notificacion::with('user') // Cargar la relación 'user'
                    ->where('nombre_empresa', $empresa)
                    ->orderBy('id', 'desc')
                    ->get()
                    ->map(function($item) {
                        $item->archivo_url = $item->notificacion_pdf ? url('storage/' . $item->notificacion_pdf) : null;
                        // Agregar el nombre y apellido del usuario
                        $item->usuario_nombre = $item->user ? $item->user->nombres . ' ' . $item->user->apellidos : null;
                        return $item;
                    });
        
                return DataTables::of($calendario)
                    ->addColumn('usuario_nombre', function ($row) {
                        return $row->usuario_nombre;
                    })
                    ->make(true);
            }else{
                $calendario = Notificacion::with('user') // Cargar la relación 'user'
                    ->orderBy('id', 'desc')
                    ->get()
                    ->map(function($item) {
                        $item->archivo_url = $item->notificacion_pdf ? url('storage/' . $item->notificacion_pdf) : null;
                        // Agregar el nombre y apellido del usuario
                        $item->usuario_nombre = $item->user ? $item->user->nombres . ' ' . $item->user->apellidos : null;
                        return $item;
                    });
        
                return DataTables::of($calendario)
                    ->addColumn('usuario_nombre', function ($row) {
                        return $row->usuario_nombre;
                    })
                    ->make(true);
            }
           
        }
        return view('admin.calendariotributario.notificaciones');
    }
    
     public function Notificacionwhatsapp(Request $request)
    {
        $nombre = $request->nombre;
        $empresa = $request->empresa;
        $id = $request->id;

       $recipient = Empresa::where('razon_social', $empresa)->first();
        $numero = $recipient->numero_contacto;

        if ($recipient->telefonos_secundarios) {
            $numeros_secundarios = explode(',', $recipient->telefonos_secundarios);
            $numeros_secundarios[] = $recipient->numero_contacto; // Agregar el número principal al array
        }
        
        $filePath = null;

        if ($request->file('notificacion_pdf')) {
            $nombrePlantilla = 'notificacion_tributaria_documento';

            $file = $request->file('notificacion_pdf');
            $filename = 'adjunto-' . $recipient->NIT . "_" . uniqid() . "." . $file->getClientOriginalExtension();
            $filePath = 'data/documentos-obligaciones/' . $filename;

            // Elimina el archivo anterior si existe
            if (file_exists(public_path('data/documentos-obligaciones/' . $filename))) {
                unlink(public_path('data/documentos-obligaciones/' . $filename));
            }
            // Mueve y guarda el nuevo archivo PDF
            $file->move(public_path('data/documentos-obligaciones/'), $filename);
        } else {
            $nombrePlantilla = 'notificacion_tributaria';
        }

        //guardar datos del envio 
        $user = Auth::user();
        $data = [
            'nombre_empresa' => $empresa,
            'observacion_correo' => 'enviado por Whatsapp',
            'obligacion' => $nombre,
            'usuario'   => $user->id
        ];
        try {
               if(!empty($numeros_secundarios)) {
                // Enviar notificación a todos los números secundarios
                foreach ($numeros_secundarios as $numero_secundario) {
                    $recipient->notify(new CalendarioTributarioNotification($nombre, $empresa, $nombrePlantilla, $numero_secundario, $filePath));
                }
            } else {
                // Enviar notificación al número principal
                $recipient->notify(new CalendarioTributarioNotification($nombre, $empresa, $nombrePlantilla, $numero,  $filePath));
            }
            // Guardar datos en la base de datos
            Notificacion::create($data);
            $valor = $this->revisarwhatsappenviado($id, $nombre);
            // Envía la variable fecha_revision junto con la respuesta JSON
            return response()->json(['success' => 'Notificado.'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            // Envía la variable fecha_revision junto con la respuesta JSON
            return response()->json(['error' => 'Error el notificar.', 400]);
        }
    }

    public function Notificacionrevisoria($nombre,$id,$empresa,$fecha){
        $recipient = Empresa::where('razon_social', $empresa)->first();
        $correo = $recipient->correo_electronico;
        //guardar datos del envio 
        $user = Auth::user();
        $data = [
            'nombre_empresa' => $empresa,
            'observacion_correo' => 'enviado por revisor fiscal',
            'obligacion' => $nombre,
            'usuario'   => $user->id,
            'fecha_vencimiento' => $fecha
        ];
        try {
            Mail::to($correo)->send(new NotificacionCalendario($data));
            // Guardar datos en la base de datos
            Notificacion::create($data);
            $valor=$this->revisarrevisorenviado($id,$nombre);
            // Envía la variable fecha_revision junto con la respuesta JSON
            return response()->json(['success' => true, 'valido' => 1]);
        } catch (\Exception $e) {
                Log::error($e);
            // Envía la variable fecha_revision junto con la respuesta JSON
            return response()->json(['success' => true, 'valido' => 2]);
        }
    }

    public function descargarPDF(Request $request){
        $opcioncreacion = $request->input('opcioncreacion');
        if($opcioncreacion==1){
            $empresa = $request->input('empresapdf');
            $eventsData  = json_decode($request->input('events'), true);
            $fechaInput = $request->input('currentMonthDate');
        }else{
            $empresa = $request->input('empresapdfCorreo');
            $eventsData  = json_decode($request->input('eventsCorreo'), true);
            $fechaInput = $request->input('currentMonthDateCorreo');
        }
      
        
        // Convertir la fecha a un objeto Carbon
        $currentDate = Carbon::parse($fechaInput);
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        // Inicializar arrays de eventos
        $events = collect(isset($eventsData['events']) ? $eventsData['events'] : []);
        $events2 = collect(isset($eventsData['events2']) ? $eventsData['events2'] : []);
        $eventRequerimientos = collect(isset($eventsData['event_requerimientos']) ? $eventsData['event_requerimientos'] : []);
        // Función para filtrar por mes y año actual
        $filterByCurrentMonthAndYear = function ($event) use ($currentYear, $currentMonth) {
            $eventDate = Carbon::parse($event['start']);
            return $eventDate->year == $currentYear && $eventDate->month == $currentMonth;
        };

        // Filtrar arrays por mes y año actual usando las colecciones de Laravel
        $filteredEvents = $events->filter($filterByCurrentMonthAndYear);
        $filteredEvents2 = $events2->filter($filterByCurrentMonthAndYear);
        $filteredEventRequerimientos = $eventRequerimientos->filter($filterByCurrentMonthAndYear);

        // Convertir de nuevo a arrays si es necesario
        $filteredEventsArray = $filteredEvents->values()->all();
        $filteredEvents2Array = $filteredEvents2->values()->all();
        $filteredEventRequerimientosArray = $filteredEventRequerimientos->values()->all();
        // Lógica para generar el PDF con Dompdf
        $options = new Options();
        // $options->set('defaultFont', 'Verdana');
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $imagePathLogo = public_path("images/logos/logo_contable.png");
        $imageDataLogo = file_get_contents($imagePathLogo);
        $base64ImageLogo = base64_encode($imageDataLogo);
        $html = view('admin.calendariotributario.pdf', compact('filteredEventsArray','filteredEvents2Array','filteredEventRequerimientosArray','empresa','base64ImageLogo'))->render(); // Reemplaza 'admin.cotizador.pdf' por la ruta real de tu vista
        // Logo encabezado
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        if($opcioncreacion==1){
            $dompdf->render();
            $dompdf->stream('Obligaciones_Tributarias.pdf', [
                'Attachment' => false // Cambiado a false para abrir en otra pestaña
            ]);
        }else{
             // Guardar el PDF en un archivo temporal
            $dompdf->render();
            $output = $dompdf->output();

            // Definir la ruta del archivo temporal
            $fileName = 'Obligaciones_Tributarias.pdf';
            $tempDir = storage_path('app/temp');
            $tempPath = $tempDir . '/' . $fileName;

            // Crear el directorio si no existe
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Guardar el PDF en el directorio temporal
            file_put_contents($tempPath, $output);

            return $tempPath;
        }
       
    }

    public function correofechas(Request $request){
        
        $empresa = $request->input('empresapdfCorreo');
        $eventsData  = json_decode($request->input('eventsCorreo'), true);
        $fechaInput = $request->input('currentMonthDateCorreo');
        $opcioncreacion = $request->input('opcioncreacion');
        Carbon::setLocale('es');
        $currentDate = Carbon::parse($fechaInput);
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        $currentMonthName = $currentDate->translatedFormat('F');
        $pdfPath = $this->descargarPDF($request);  // Adaptar el método para devolver la ruta del archivo
        $user = Auth::user();
        $data =[
            'nombre_empresa' => $empresa,
            'fecha_vencimiento' => $fechaInput,
            'observacion_correo' => 'Notificacion calendario de vencimientos',
            'obligacion' => 'calendario de vencimientos '.$currentMonthName,
            'usuario' => $user->id,
        ];
        $events = collect(isset($eventsData['events']) ? $eventsData['events'] : []);
        $events2 = collect(isset($eventsData['events2']) ? $eventsData['events2'] : []);
        $eventRequerimientos = collect(isset($eventsData['event_requerimientos']) ? $eventsData['event_requerimientos'] : []);
        // Función para filtrar por mes y año actual
        $filterByCurrentMonthAndYear = function ($event) use ($currentYear, $currentMonth) {
            $eventDate = Carbon::parse($event['start']);
            return $eventDate->year == $currentYear && $eventDate->month == $currentMonth;
        };

        // Filtrar arrays por mes y año actual usando las colecciones de Laravel
        $filteredEvents = $events->filter($filterByCurrentMonthAndYear);
        $filteredEvents2 = $events2->filter($filterByCurrentMonthAndYear);
        $filteredEventRequerimientos = $eventRequerimientos->filter($filterByCurrentMonthAndYear);

        // Convertir de nuevo a arrays si es necesario
        $filteredEventsArray = $filteredEvents->values()->all();
        $filteredEvents2Array = $filteredEvents2->values()->all();
        $filteredEventRequerimientosArray = $filteredEventRequerimientos->values()->all();
        
        // Verificar si los tres arrays están vacíos
        if (empty($filteredEventsArray) && empty($filteredEvents2Array) && empty($filteredEventRequerimientosArray)) {
            return ['color' => 'warning', 'mensaje' => 'No hay eventos para enviar.'];
        }
        // Obtener los correos electrónicos principales y secundarios
        $correoPrincipal = Empresa::where('razon_social', $empresa)->value('correo_electronico');
        $correosSecundarios = Empresa::where('razon_social', $empresa)->value('correos_secundarios');

        // Verificar si hay correos secundarios y convertirlos en un array si existen
        $correosSecundariosArray = $correosSecundarios ? explode(',', $correosSecundarios) : [];

        // Crear un array de destinatarios combinando el correo principal y los secundarios
        $destinatarios = array_filter(array_merge([$correoPrincipal], $correosSecundariosArray), function ($email) {
            return !empty(trim($email)); // Filtrar correos vacíos o con espacios
        });
        // Enviar el correo si hay destinatarios válidos
        if (!empty($destinatarios)) {
            try {
                Mail::to($correoPrincipal)
                    ->cc($correosSecundariosArray)
                    ->send(new CalendarioTributarioMail(
                        $empresa,
                        $pdfPath,
                        $currentYear,
                        $currentMonth,
                        $currentMonthName,
                        $filteredEventsArray,
                        $filteredEvents2Array,
                        $filteredEventRequerimientosArray
                    ));

                // Guardar datos en la base de datos
                Notificacion::create($data);
                return redirect()->to('admin/calendariotributario/index ')->with('messagec', 'Correo enviado con éxito')->with('color', 'success');
            } catch (\Exception $e) {
                Log::error($e);
                return redirect()->to('admin/calendariotributario/index ')->with('messagec', 'Error al enviar el correo')->with('color', 'danger');
            }
        } else {
            return redirect()->to('admin/calendariotributario/index ')->with('messagec', 'No hay destinatarios para enviar el correo.')->with('color', 'danger');
        }
       
    }
    
    public static function correofechasmasivo($id): array
    {
        // Obtener los datos de la empresa
        $empresa = Empresa::select('id', 'razon_social', 'notifica_calendario', 'correo_electronico', 'correos_secundarios')
                        ->where('id', $id)
                        ->first();

        // Verificar si notifica_calendario es igual a 2
        if ($empresa->notifica_calendario == 2) {
            return ['color' => 'info', 'mensaje' => 'La empresa ha desactivado la notificación de calendario.'];
        }

        // Obtener eventos
        $resultado = (new self())->getCalendariotributario($id, null, null, null);
        $combinedEvents = [
            'events' => $resultado['events'],
            'events2' => $resultado['events2'],
            'event_requerimientos' => $resultado['event_requerimientos']
        ];
        $eventsData = json_encode($combinedEvents, true);
        $fechaInput = now()->startOfMonth()->format('Y-m-d');
        $opcioncreacion = "2";
        Carbon::setLocale('es');
        $currentDate = Carbon::parse($fechaInput);
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        $currentMonthName = $currentDate->translatedFormat('F');

        $data = [
            'opcioncreacion' => $opcioncreacion,
            'empresapdfCorreo' => $empresa->razon_social,
            'eventsCorreo' => $eventsData,
            'currentMonthDateCorreo' => $fechaInput,
        ];

        // Crear un nuevo Request falso con esos datos
        $fakeRequest = new Request($data);
        $pdfPath = (new self())->descargarPDF($fakeRequest);

        // Obtener los correos electrónicos principales y secundarios
        $correoPrincipal = $empresa->correo_electronico;
        $correosSecundariosArray = $empresa->correos_secundarios ? explode(',', $empresa->correos_secundarios) : [];
        $destinatarios = array_filter(array_merge([$correoPrincipal], $correosSecundariosArray), function ($email) {
            return !empty(trim($email));
        });

        $dataNotificacion = [
            'nombre_empresa' => $empresa->razon_social,
            'fecha_vencimiento' => $fechaInput,
            'observacion_correo' => 'Notificación calendario de vencimientos automática',
            'obligacion' => 'Calendario de vencimientos ' . $currentMonthName,
            'usuario' => 1,
        ];

        $eventsData = json_decode($eventsData, true);
        $events = collect($eventsData['events'] ?? []);
        $events2 = collect($eventsData['events2'] ?? []);
        $eventRequerimientos = collect($eventsData['event_requerimientos'] ?? []);

        // Filtrar arrays por mes y año actual
        $filterByCurrentMonthAndYear = function ($event) use ($currentYear, $currentMonth) {
            $eventDate = Carbon::parse($event['start']);
            return $eventDate->year == $currentYear && $eventDate->month == $currentMonth;
        };
        $filteredEventsArray = $events->filter($filterByCurrentMonthAndYear)->values()->all();
        $filteredEvents2Array = $events2->filter($filterByCurrentMonthAndYear)->values()->all();
        $filteredEventRequerimientosArray = $eventRequerimientos->filter($filterByCurrentMonthAndYear)->values()->all();

        if (empty($filteredEventsArray) && empty($filteredEvents2Array) && empty($filteredEventRequerimientosArray)) {
            return ['color' => 'warning', 'mensaje' => 'No hay eventos para enviar.'];
        }

        if (!empty($destinatarios)) {
            try {
                Mail::to($correoPrincipal)
                    ->cc($correosSecundariosArray)
                    ->send(new CalendarioTributarioMail(
                        $empresa->razon_social,
                        $pdfPath,
                        $currentYear,
                        $currentMonth,
                        $currentMonthName,
                        $filteredEventsArray,
                        $filteredEvents2Array,
                        $filteredEventRequerimientosArray
                    ));

                Notificacion::create($dataNotificacion);
                return ['color' => 'success', 'mensaje' => 'Correo enviado.'];
            } catch (\Exception $e) {
                Log::error($e);
                return ['color' => 'danger', 'mensaje' => 'Correo no enviado. Error: ' . $e->getMessage()];
            }
        } else {
            return ['color' => 'warning', 'mensaje' => 'No hay destinatarios para enviar el correo.'];
        }
    }

    
    public function descargarPDFCompleto(Request $request)
    {

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipo = $request->input('tipo');
        $id_empresa = explode(' - ', $request->input('empresa'))[0];
    
        dispatch(new GenerarInformeTributario($fechaInicio, $fechaFin, $tipo,$id_empresa));
    }

    public function descargarExcel(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipo = $request->input('tipo');
        $id_empresa = explode(' - ', $request->input('empresa'))[0];
        return Excel::download(new InformeTributarioMultipleExport($fechaInicio, $fechaFin,$tipo,$id_empresa), 'informe_tributario.xlsx');
    }
}
