<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class RequerimientosExport implements FromView, ShouldAutoSize
{
    public  $seguimientos;
   

    function __construct($seguimientos)
    {
        $this->seguimientos = $seguimientos;
    }

    public function view(): View
    {

        return view('admin.requerimiento-empleado.seguimientos-export', [
            'seguimientos' => $this->seguimientos,
        ]);
    }
}
