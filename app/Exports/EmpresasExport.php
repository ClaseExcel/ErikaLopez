<?php

namespace App\Exports;

use App\Models\CamarasComercio;
use App\Models\CodigoCiiu;
use App\Models\ObligacionDian;
use App\Models\ObligacionesMunicipalesDian;
use App\Models\OtrasEntidadesCT;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;

class EmpresasExport implements FromView, ShouldAutoSize
{
    public  $empresas;
   

    function __construct($empresas)
    {
        $this->empresas = $empresas;
    }

    public function view(): View
    {

        $obligaciones = ObligacionDian::all()->sortBy('codigo');
        $empleados = User::all()->sortBy('nombres');
        $obligacionesmunicipales = ObligacionesMunicipalesDian::all()->sortBy('codigo');
        $otrasentidades = OtrasEntidadesCT::all()->sortBy('codigo');
        $camarascomercio = CamarasComercio::all();
        $ciiu = CodigoCiiu::all();

        return view('admin.empresas.empresas-export', [
            'empresas' => $this->empresas,
            'obligaciones' => $obligaciones,
            'empleados' => $empleados,
            'obligacionesmunicipales' => $obligacionesmunicipales,
            'otrasentidades' => $otrasentidades,
            'camarascomercio' => $camarascomercio,
            'ciius' => $ciiu
        ]);
    }
}
