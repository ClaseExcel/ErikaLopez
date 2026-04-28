<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\EventStatus;
use Spatie\IcalendarGenerator\Enums\ParticipationStatus;
use Spatie\IcalendarGenerator\Properties\TextProperty;

class eventCreatedNotification extends Notification
{
    use Queueable;

    public $cita, $user, $empresa;

    /**
     * Create a new notification instance.
     */
    public function __construct($cita,$user,$empresa)
    {
        $this->cita = $cita;
        $this->user = $user;
        $this->empresa = $empresa;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $address = $this->cita->link ? $this->cita->link : $this->cita->direccion;
        $descripcion = $this->cita->observacion ? $this->cita->observacion : 'Sin observación';
        $email_organizer = $this->user->email;
        $name_organizer = $this->user->nombres . ' ' . $this->user->apellidos;

        if($notifiable->razon_social) {
            $calendar = Calendar::create($this->cita->motivo)
            ->event(
                Event::create($this->cita->motivo)
                    ->description($descripcion)
                    ->organizer($email_organizer, $name_organizer)
                    ->attendee($notifiable->correo_electronico, $notifiable->razon_social, ParticipationStatus::needs_action(), requiresResponse: true)
                    ->startsAt(Carbon::parse($this->cita->fecha_inicio))
                    ->endsAt(Carbon::parse($this->cita->fecha_fin))
                    ->address($address)
            );
        } else {
            $calendar = Calendar::create($this->cita->motivo)
            ->event(
                Event::create($this->cita->motivo)
                    ->description($descripcion)
                    ->organizer($email_organizer, $name_organizer)
                    ->attendee($this->empresa->correo_electronico, $this->empresa->razon_social)
                    ->startsAt(Carbon::parse($this->cita->fecha_inicio))
                    ->endsAt(Carbon::parse($this->cita->fecha_fin))
                    ->address($address)
            );
        }

        

        $calendar->appendProperty(TextProperty::create('METHOD', 'REQUEST'));

        $mailMessage = (new MailMessage)
            ->subject("📨 Asignación de cita: " . $this->cita->motivo)
            ->markdown('emails.agenda')
            ->attachData($calendar->get(), 'invite.ics', [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
            ]);

        return $mailMessage;
    }



    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @return array<string, mixed>
    //  */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
