<?php

namespace App\Exports;

use App\Models\Empresa;
use App\Models\FechasOtrasEntidadesCT;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FechasOtrasEntidadesExport implements FromView, WithStyles, WithEvents
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

    public function view(): View
    {
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;
        if ($this->tipo == 'empresas') {
            // Obtener la empresa específica
            $empresa = Empresa::find($this->id_empresa);
    
            if (!$empresa) {
                abort(404, 'Empresa no encontrada');
            }
    
            // Definir el rango del año en curso
            $inicioAño = Carbon::now()->startOfYear();
            $finAño = Carbon::now()->endOfYear();
            // Filtrar solo por la empresa en el año en curso
            $detallesTributarios = FechasOtrasEntidadesCT::whereBetween('fecha_vencimiento', [$inicioAño, $finAño])
            ->whereHas('empresa', function ($query) use ($empresa) {
                $query->where('NIT', $empresa->NIT);
            })
            ->get();
        }else{
            $detallesTributarios = FechasOtrasEntidadesCT::whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->get();
        }
        return view('admin.calendariotributario.fechas_otras_entidades', compact('detallesTributarios', 'fechaInicio', 'fechaFin'));
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar color a la cabecera
        $sheet->getStyle('A1:M2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '232222'] 
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);

       
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Definir anchos de columna específicos
                $columnWidths = [
                    'A' => 20,  // Fecha Vencimiento
                    'B' => 43,  // Empresa
                    'C' => 20,  // NIT
                    'D' => 40,  // Nombre
                    'E' => 20,  // Código OBLIGACION
                    'F' => 20,  // Código Tributario
                    'G' => 20,  // Fecha Revisión
                    'H' => 30,  // Observación
                    'I' => 20,   // Detalle Tributario
                    'J' => 60,  // Nombre Detalle
                    'K' => 20,  // NOTIFICADO
                    'L' => 20,  // REVISOR
                    'M' => 20   //WHASTAPP
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            }
        ];
    }
}

