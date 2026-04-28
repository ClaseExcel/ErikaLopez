<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\Component;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class GestionesNotification extends Notification
{
    use Queueable;

   public $empresa, $documento, $numero, $nombrePlantilla;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($empresa, $documento, $numero, $nombrePlantilla)
    {
        $this->empresa = $empresa;
        $this->documento = $documento;
        $this->numero = $numero;
        $this->nombrePlantilla = $nombrePlantilla;
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
            ->header(Component::document(asset($this->documento)))
            ->body(Component::text($this->empresa)) // List of url suffixes
            ->to('57'.$this->numero);
    }
}
