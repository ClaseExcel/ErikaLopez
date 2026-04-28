<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionCalendario extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
        $data = $this->data;
        if($this->data['observacion_correo']=='enviado por revisor fiscal'){
            $email = $this->view('emails.notificacion_calendariotributario_revisor',compact('data'))
            ->subject('Presentación Exitosa de la Obligación: ' . $this->data['obligacion']);
             if (!empty($this->data['notificacion_pdf']) && is_array($this->data['notificacion_pdf'])) {
                foreach ($this->data['notificacion_pdf'] as $archivo) {
                    $email->attach(
                        storage_path('app/public/' . $archivo['path']),
                        [
                            'as' => $archivo['original'], // nombre visible en el correo
                        ]
                    );
                }
            }
        }else{
            $email = $this->view('emails.notificacion_calendariotributario',compact('data'))
            ->subject('Presentación Exitosa de la Obligación: ' . $this->data['obligacion']);
            if (!empty($this->data['notificacion_pdf']) && is_array($this->data['notificacion_pdf'])) {
                foreach ($this->data['notificacion_pdf'] as $archivo) {
                    $email->attach(
                        storage_path('app/public/' . $archivo['path']),
                        [
                            'as' => $archivo['original'], // nombre visible en el correo
                        ]
                    );
                }
            }
        }
      
        return $email;
    }
}
