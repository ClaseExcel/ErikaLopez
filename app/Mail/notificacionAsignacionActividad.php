<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class notificacionAsignacionActividad extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre_actividad, $empresa, $fecha, $documentos;

    public $subject = "Asignación de actividad - Help!Digital";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre_actividad, $empresa, $fecha, $documentos)
    {
        $this->nombre_actividad = $nombre_actividad;
        $this->empresa = $empresa;
        $this->fecha = $fecha;
        $this->documentos = $documentos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.notificacion_asignacion')->with($this->nombre_actividad, $this->empresa, $this->fecha);

        if($this->documentos == null){
            return $email;
        }

        foreach ($this->documentos as $documento) {
            $email->attach($documento);
        }
        
        return $email;
    }
}
