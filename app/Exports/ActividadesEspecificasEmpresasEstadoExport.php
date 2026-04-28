<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ActividadesEspecificasEmpresasEstadoExport implements FromView,ShouldAutoSize, WithDrawings
{
    public  $cantidadActividadesEstado, $totalActividades, $datos;

    function __construct( $cantidadActividadesEstado, $totalActividades, $datos)
    {
        $this->cantidadActividadesEstado = $cantidadActividadesEstado;
        $this->totalActividades = $totalActividades;
        $this->datos = $datos;
    }

    public function view(): View
    {
        return view('admin.informes.excel.excel-actividades-empresas', [
            'cantidadActividadesEstado' => $this->cantidadActividadesEstado, 
            'cantidadActividades' => null, 
            'total' => $this->totalActividades, 
            'datos' => $this->datos, 
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('allwars');
        $drawing->setPath(public_path('./images/logos/allwars.png'));
        $drawing->setHeight(85);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20); 
        $drawing->setOffsetY(10); 

        return $drawing;
    }
}
