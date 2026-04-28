<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class CalendarioTributarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $empresa;
    public $pdfPath;
    public $currentYear;
    public $currentMonth,$currentMonthName;
    public $filteredEventsArray,$filteredEvents2Array,$filteredEventRequerimientosArray;
    /**
     * Create a new message instance.
     *
     * @return void
     */
      public function __construct($empresa, $pdfPath,$currentYear,$currentMonth,$currentMonthName,$filteredEventsArray,$filteredEvents2Array,$filteredEventRequerimientosArray)
    {
        $this->empresa = $empresa;
        $this->pdfPath = $pdfPath;
        $this->currentYear = $currentYear;
        $this->currentMonth = $currentMonth;
        $this->currentMonthName = $currentMonthName;
        $this->filteredEventsArray = $filteredEventsArray;
        $this->filteredEvents2Array = $filteredEvents2Array;
        $this->filteredEventRequerimientosArray = $filteredEventRequerimientosArray;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Informamos el calendario de vencimientos '.$this->currentMonthName.' '.$this->empresa.'.',
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
            view: 'admin.calendariotributario.correo',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Obligaciones_Tributarias.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
