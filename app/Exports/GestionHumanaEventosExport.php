<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GestionHumanaEventosExport implements FromView, ShouldAutoSize
{
     public  $eventos;

    function __construct($eventos)
    {
        $this->eventos = $eventos;
    }

    public function view(): View
    {
        return view('admin.gestion-humana.eventos-export', [
            'eventos' => $this->eventos,
        ]);
    }
}
