<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EstadoRequerimiento;

class seguimientoRequerimientos extends Mailable
{
    use Queueable, SerializesModels;
    public $consecutivo, $observacion, $estado_requerimiento, $usuario_cliente, $estado;

    public $subject = "Notificación seguimiento de tu requerimiento - Erika Lopez";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($consecutivo, $observacion, $estado_requerimiento, $usuario_cliente, $estado)
    {
        $this->consecutivo = $consecutivo;
        $this->observacion = $observacion;
        $this->estado_requerimiento = $estado_requerimiento;
        $this->usuario_cliente = $usuario_cliente;
        $this->estado = $estado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.requerimientos')->with(
         $this->consecutivo, 
         $this->observacion, 
         $this->estado_requerimiento, 
         $this->estado,
         $this->usuario_cliente);
    }
}
