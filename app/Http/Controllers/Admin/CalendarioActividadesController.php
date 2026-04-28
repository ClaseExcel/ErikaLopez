<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CalendarioActividades;
use App\Mail\NotificacionCorreoActividades;
use App\Models\ActividadCliente;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CalendarioActividadesController extends Controller
{
    use CalendarioActividades;
    

    public function index()
    {
        abort_if(Gate::denies('ACCEDER_CALENDARIO_ACTIVIDADES'), Response::HTTP_UNAUTHORIZED);

        //select de empresas
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }
        //select usuario crea la actividad
        $usercreaact = User::orderBy('nombres')->select('id', 'nombres', 'apellidos')->get();
        $cantEvents =  'vacio';
        $cantRequerimientos = 'vacio';
        $nombreempresa = 'vacio';
        $events = null;
        $event_requerimientos = null;
        $festivos = null;

        return view('home-actividades', compact('cantEvents','nombreempresa','usercreaact', 'events', 'event_requerimientos', 'festivos', 'cantRequerimientos'));
    }

    public function filtroCliente(Request $request)
    {        
        // dd($request->all());
        $empresa_id = $request->input('empresa_id');
        $responsable_id = $request->input('responsable_id');
        $usercreaactId = $request->input('usercreaactId');
        $empresa_id = explode('-', $empresa_id)[0];
        $responsable_id = explode('-', $responsable_id)[0];
        $usercreaactId = explode('-', $usercreaactId)[0];
        //select de empresas
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->get();
        } else {
            $empresas = Empresa::orderBy('razon_social')->select('id', 'razon_social')->where('estado', 1)->whereJsonContains('empleados', (string)Auth::user()->id)->get();
        }
        //select usuario crea la actividad
        $usercreaact = User::orderBy('nombres')->select('id', 'nombres', 'apellidos')->get();
        $resultado = $this->getCalendarioDeActividades($empresa_id, $responsable_id,$usercreaactId);    
        $events = $resultado['events'];
        $event_requerimientos = $resultado['event_requerimientos'];  
        $festivos = $resultado['festivos'];
        
        $cantEvents = count($events);
        $cantRequerimientos = count($event_requerimientos);

        if($empresa_id){
            $nombreempresa=Empresa::select('razon_social')->where('id',$empresa_id)->first();
        }else if($responsable_id || $usercreaactId){
            $nombreempresa = User::selectRaw('CONCAT(nombres, " ", apellidos) as razon_social')
            ->where('id', $responsable_id ?: $usercreaactId)
            ->first();
        }else{
            $nombreempresa= 'vacio';
        };
        //enviar un mensaje con la cantidad de eventos encontrados
        return view('home-actividades', compact('empresas', 'events', 'event_requerimientos', 'festivos', 'cantEvents','nombreempresa','usercreaact', 'cantRequerimientos'));
    }

    function events(Request $request) {
        $resultado = $this->getCalendarioDeActividades($idCliente = null, $responsableId = null, $usercreaactId= null, $request['start_date'], $request['end_date']);
        $events = $resultado['events'];
        $event_requerimientos = $resultado['event_requerimientos'];  
        $festivos = $resultado['festivos'];

        $resultado = array_merge($events, $event_requerimientos, $festivos);
        return $resultado;
    }

     public function notificarCorreo(Request $request)
    {
        $actividad = ActividadCliente::find($request->id_actividad_calendario);

        if (!isset($actividad->reporte_actividades->fecha_fin)) {
            return response()->json(['error' => 'No se puede notificar porque la actividad no se ha finalizado.'], 400);
        }

        $uploadedFiles = [];

        if ($request->notificacion_pdf) {
            foreach ($request->notificacion_pdf as $documentos) {
                $filename =  $documentos->getClientOriginalName();
                $urlPathPhoto = 'storage/reporte_documento/' . $filename;

                $uploadedFiles[] =  $urlPathPhoto;

                Storage::disk('reporte_documento')->put($filename, File::get($documentos->getRealPath()));
            }
        }

        $correos = [];
        if ($request->correos_adicionales) {
            $correos = explode(',', $request->correos_adicionales);
        }

        $correos[] = $actividad->empresa_asociada ? $actividad->empresa_asociada->correo_electronico : $actividad->cliente->correo_electronico;

        Mail::to($correos)->send(new NotificacionCorreoActividades($actividad, $uploadedFiles, $request->observacion_email));

        $actividad->update([
            'notificado' => 1,
            'documentos_notificacion' => json_encode($uploadedFiles)
        ]);

        return true;
    }
}
