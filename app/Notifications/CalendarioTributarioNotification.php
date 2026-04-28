<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;
use NotificationChannels\WhatsApp\Component;

class CalendarioTributarioNotification extends Notification
{
    use Queueable;
    public $nombre, $empresa, $nombrePlantilla, $numero, $documento;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nombre,  $empresa, $nombrePlantilla, $numero, $documento)
    {
        $this->nombre = $nombre;
        $this->empresa = $empresa;
        $this->nombrePlantilla = $nombrePlantilla;
        $this->numero = $numero;
        $this->documento = $documento;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toWhatsapp($notifiable)
    {

        if ($this->documento) {
            return WhatsAppTemplate::create()
                ->name($this->nombrePlantilla) // Name of your configured template            
                ->language('es_ES')
                ->header(Component::document(asset($this->documento)))
                ->body(Component::text($this->empresa))
                ->body(Component::text($this->nombre))
                // ->buttons(Component::urlButton([$this->fecha])) // List of url suffixes
                ->to('57' . $this->numero);
        } else {
            return WhatsAppTemplate::create()
                ->name($this->nombrePlantilla) // Name of your configured template            
                ->language('es_ES')
                ->body(Component::text($this->empresa))
                ->body(Component::text($this->nombre))
                // ->buttons(Component::urlButton([$this->fecha])) // List of url suffixes
                ->to('57' . $this->numero);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
