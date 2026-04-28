<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionActividades extends Mailable
{
    use Queueable, SerializesModels;

    private $asunto;
    private $mensaje;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $asunto, string $mensaje, $subject)
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject_email = $this->asunto;
        $message_email = $this->mensaje;
        return $this->view('emails.notificacion_actividad', compact('subject_email','message_email'))->subject($this->subject)->subject($this->subject);;
    }
}
