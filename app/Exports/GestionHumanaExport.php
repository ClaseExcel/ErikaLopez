<?php

namespace App\Exports;

use App\Models\GestionHumana;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GestionHumanaExport implements FromView, ShouldAutoSize, WithEvents
{
    public  $gestionesIds;

    function __construct($gestionesIds)
    {
        $this->gestionesIds = $gestionesIds;
    }

    public function view(): View
    {
        $this->gestionesIds = explode(',', $this->gestionesIds);
        $gestiones = GestionHumana::withTrashed()->whereIn('id', $this->gestionesIds)->get();
       
        return view('admin.gestion-humana.export', [
            'gestiones' => $gestiones,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getAutoFilter()->setRange('A1:U1'); // Ajusta el rango según tus encabezados
            },
        ];
    }
}
