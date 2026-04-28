<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SeguimientoCotizacionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $cliente, $numero, $observacion, $responsable, $fecha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente, $numero, $observacion, $responsable, $fecha)
    {
        $this->cliente = $cliente;
        $this->numero = $numero;
        $this->observacion = $observacion;
        $this->responsable = $responsable;
        $this->fecha = $fecha;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Notificación de seguimiento de cotización: #' . $this->numero,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        $cliente = $this->cliente;
        $numero = $this->numero;
        $observacion = $this->observacion;
        $responsable = $this->responsable;
        $fecha = $this->fecha;
        
        return $this->view('emails.seguimiento_cotizacion', compact('cliente', 'numero', 'observacion', 'responsable', 'fecha'));
    }

}
