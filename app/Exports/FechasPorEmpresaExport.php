<?php
namespace App\Exports;

use App\Models\Empresa;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\FechasPorEmpresaCT;
use Carbon\Carbon;

class FechasPorEmpresaExport implements FromView, WithStyles, WithEvents
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
            $fechasEmpresa = FechasPorEmpresaCT::whereBetween('fecha_vencimiento', [$inicioAño, $finAño])
                ->whereHas('empresa', function ($query) use ($empresa) {
                    $query->where('NIT', $empresa->NIT);
                })
                ->get();
        } else {
            // Filtrar por el rango de fechas proporcionado
            $fechasEmpresa = FechasPorEmpresaCT::whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->get();
        }
        return view('admin.calendariotributario.fechas_por_empresa', compact('fechasEmpresa'));
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar color a la cabecera
        $sheet->getStyle('A1:L2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '232222'] // Aguamarina
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
                    'E' => 20,  // Código Tributario
                    'F' => 20,  // Fecha Revisión
                    'G' => 30,  // Observación
                    'H' => 25,  // Detalle Tributario
                    'I' => 60,   // Nombre Detalle
                    'J' => 20,  // NOTIFICADO
                    'K' => 20,  // REVISOR
                    'L' => 20   //WHASTAPP
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            }
        ];
    }
}
