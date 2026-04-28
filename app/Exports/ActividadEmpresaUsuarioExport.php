<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ActividadEmpresaUsuarioExport implements FromView,ShouldAutoSize

{
    public $cantidadActividadesEstado, $cantidadActividades, $datos;

    function __construct($cantidadActividadesEstado, $cantidadActividades, $datos)
    {
        $this->cantidadActividadesEstado = $cantidadActividadesEstado;
        $this->cantidadActividades = $cantidadActividades;
         $this->datos = $datos;
    }

    public function view(): View
    {
            return view('admin.informes.excel.excel-actividad-empresa-usuario', [
                'cantidadActividadesEstado' => $this->cantidadActividadesEstado, 
                'cantidadActividades' => $this->cantidadActividades, 
                'datos' => $this->datos, 
        ]);
    }
}
