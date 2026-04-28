<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;
use NotificationChannels\WhatsApp\Component;

class SolicitudRequerimientoNotification extends Notification
{
    use Queueable;

    public $consecutivo, $idRequerimiento, $nombrePlantilla, $numero;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($consecutivo, $idRequerimiento, $nombrePlantilla, $numero)
    {
        $this->consecutivo = $consecutivo;
        $this->nombrePlantilla = $nombrePlantilla;
        $this->idRequerimiento = $idRequerimiento;
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
        return WhatsAppTemplate::create()
            ->name($this->nombrePlantilla) // Name of your configured template            
            ->language('es_ES')
            ->body(Component::text($this->consecutivo))
            ->buttons(Component::urlButton([$this->idRequerimiento])) // List of url suffixes
            ->to('57'.$this->numero);
    }
}
