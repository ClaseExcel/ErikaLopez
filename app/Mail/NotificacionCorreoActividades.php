<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionCorreoActividades extends Mailable
{
    use Queueable, SerializesModels;
    public $actividad, $documentos, $observaciones;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($actividad, $documentos, $observaciones)
    {
        $this->actividad = $actividad;
        $this->documentos = $documentos;
         $this->observaciones = $observaciones;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Actividad finalizada exitosamente: ' . $this->actividad->nombre,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.notificacion_actividad_cliente',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return $this->documentos;
    }
}
