<?php

namespace App\Http\Controllers;

use App\Models\ActividadCliente;
use App\Models\Cargo;
use App\Models\EmpleadoCliente;
use App\Models\User;
use App\Notifications\ActividadNotification;
use App\Notifications\ActividadVencidaNotification;
use App\Notifications\MessageSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function sendExpiredActivities()
    {
        $current_date = Carbon::now();
        $actividades = ActividadCliente::with(['reporte_actividades' => function ($query) {
            $query->where('estado_actividad_id', 6);
        }, 'usuario'])->where('fecha_vencimiento', '<', date('Y-m-d'))->get();

        foreach ($actividades as $actividad) {
            if (isset($actividad->reporte_actividades)) {
                // *** Declara variables
                $recipient = User::withTrashed()->find($actividad->usuario_id);
                $destination = $recipient->id;
                $titulo = "Urgente actividad vencida";
                // *** Formato de texto para la fecha
                Carbon::setLocale('es');
                $date = Carbon::createFromFormat('Y-m-d', $actividad->fecha_vencimiento);

                // *** Fecha vencida
                $expired_message = 'La actividad que tienes asignada con nombre <strong>' . $actividad->nombre . ' </strong> ha vencido, le recomendamos revisar el estado actual de la actividad para asegurarse de que todo esté en orden. 
                La fecha de vencimiento fue el  <b>' .  $date->isoFormat('D [de] MMMM [de] YYYY') . '</b>.';

                if ($recipient->estado == "ACTIVO") {
                    $data = [
                        'subject' => $titulo,
                        'notifiable_id' => $destination,
                        'actividad_id' => $actividad->id,
                        'url' => route('admin.actividad_cliente.show', $actividad->id),
                        'message' => $expired_message
                    ];

                    if (!$current_date->isSaturday() && !$current_date->isSunday()) {
                        $recipient->notify(new MessageSent($data));
                    }
                }
            }
        }

        return true;
    }


    public function sendExpiredActivitiesWhatsapp()
    {
        $current_date = Carbon::now();
        $actividades = ActividadCliente::with(['reporte_actividades' => function ($query) {
            $query->where('estado_actividad_id', 6);
        }, 'usuario'])->where('fecha_vencimiento', '<', date('Y-m-d'))->get();

        foreach ($actividades as $actividad) {
            if (isset($actividad->reporte_actividades)) {

                $recipient = User::withTrashed()->find($actividad->usuario_id);
                $numero = $recipient->numero_contacto ? $recipient->numero_contacto : '0000000000';
                $nombrePlantilla = 'actividad_vencida';

                if ($recipient->estado == "ACTIVO") {
                    if (!$current_date->isSaturday() && !$current_date->isSunday()) {
                            $recipient->notify(new ActividadNotification($actividad->nombre, $actividad->id, $nombrePlantilla, $numero));
                    }
                }
            }
        }

        return true;
    }
}
