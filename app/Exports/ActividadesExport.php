<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ActividadesExport implements FromView, ShouldAutoSize, WithDrawings
{
    public  $cantidadActividades, $totalActividades, $datos;

    function __construct($cantidadActividades, $totalActividades, $datos)
    {
        $this->cantidadActividades = $cantidadActividades;
        $this->totalActividades = $totalActividades;
        $this->datos = $datos;
    }

    public function view(): View
    {
        return view('admin.informes.excel.excel-actividades', [
            'cantidadActividadesEstado' => null,
            'cantidadActividades' => $this->cantidadActividades,
            'total' => $this->totalActividades,
            'datos' => $this->datos,
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path('./images/logos/logo_contable.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20); 
        $drawing->setOffsetY(10); 

        return $drawing;
    }
}
