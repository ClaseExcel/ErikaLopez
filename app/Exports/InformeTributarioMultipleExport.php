<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InformeTributarioMultipleExport implements WithMultipleSheets
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $tipo;
    protected $id_empresa;

    public function __construct($fechaInicio, $fechaFin, $tipo,$id_empresa)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->tipo = $tipo;
        $this->id_empresa = $id_empresa;
    }

    public function sheets(): array
    {

        return [
            'Fechas Empresa' => new FechasPorEmpresaExport($this->fechaInicio, $this->fechaFin,$this->tipo,$this->id_empresa),
            'Otras Entidades' => new FechasOtrasEntidadesExport($this->fechaInicio, $this->fechaFin,$this->tipo,$this->id_empresa),
            'Fechas Municipales' => new FechasMunicipalesExport($this->fechaInicio, $this->fechaFin,$this->tipo,$this->id_empresa),
        ];
    }
}
