<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CotizacionesExport implements  FromView,ShouldAutoSize
{
    public $datos;

    function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function view(): View
    {
        return view('admin.informes.excel.excel-cotizaciones', [
            'datos' => $this->datos, 
    ]);
    }
}
