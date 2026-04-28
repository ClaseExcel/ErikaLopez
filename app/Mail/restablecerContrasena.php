<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class restablecerContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $email, $password;

    public $subject = "Notificación de creación de usuario - Erika Lopez";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $password)
    {
        $this->subject = $this->subject;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.restablecer_contrasena')->with($this->email, $this->password);
    }
}
