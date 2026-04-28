<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class envioRequerimiento extends Mailable
{
    use Queueable, SerializesModels;

    public $consecutivo, $tipo_requerimiento, $empresa, $empleado;

    public $subject = "Solicitud de revisión de requerimiento - Erika Lopez";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($consecutivo, $tipo_requerimiento, $empresa, $empleado)
    {
        $this->consecutivo = $consecutivo;
        $this->tipo_requerimiento = $tipo_requerimiento;
        $this->empresa = $empresa;
        $this->empleado = $empleado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.envio_requerimiento')->with($this->consecutivo, $this->tipo_requerimiento, $this->empresa, $this->empleado);
    }
}
