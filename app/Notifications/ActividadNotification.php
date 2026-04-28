<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;
use NotificationChannels\WhatsApp\Component;

class ActividadNotification extends Notification
{
    use Queueable;

    public $nombreActividad, $idActividad, $nombrePlantilla, $numero;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nombreActividad, $idActividad, $nombrePlantilla, $numero)
    {
        $this->nombreActividad = $nombreActividad;
        $this->idActividad = $idActividad;
        $this->nombrePlantilla = $nombrePlantilla;
        $this->numero = $numero;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsapp($notifiable)
    {
        if($this->nombrePlantilla == 'actividad_vencida') {
            return WhatsAppTemplate::create()
            ->name($this->nombrePlantilla) // Name of your configured template            
            ->language('es_ES')
            ->body(Component::text($this->nombreActividad))
            ->buttons(Component::urlButton([$this->idActividad])) // List of url suffixes
            ->to('57'.$this->numero);
        }else{
            return WhatsAppTemplate::create()
            ->name($this->nombrePlantilla) // Name of your configured template            
            ->language('es_ES')
            ->body(Component::text($this->nombreActividad))
            ->body(Component::text($this->idActividad))
            ->buttons(Component::urlButton([$this->idActividad])) // List of url suffixes
            ->to('57'.$this->numero);
        }
     
    }
}
