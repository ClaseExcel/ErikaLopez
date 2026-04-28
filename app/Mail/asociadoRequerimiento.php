<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class asociadoRequerimiento extends Mailable
{
    use Queueable, SerializesModels;

    public $consecutivo, $usuario_cliente, $tipo_requerimiento;

    public $subject = "Notificación solicitud de requerimiento - Erika Lopez";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($consecutivo, $usuario_cliente, $tipo_requerimiento)
    {
        $this->consecutivo = $consecutivo;
        $this->usuario_cliente = $usuario_cliente;
        $this->tipo_requerimiento = $tipo_requerimiento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.asociado_requerimiento')
        ->with($this->consecutivo, $this->usuario_cliente, $this->tipo_requerimiento );
    }
}
