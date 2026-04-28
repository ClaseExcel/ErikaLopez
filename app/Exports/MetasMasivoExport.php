<?php

namespace App\Exports;

use App\Models\MetasEmpresa;
use App\Services\MetasEmpresaService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MetasMasivoExport implements FromArray, ShouldAutoSize, WithEvents
{
    protected int $empresaId;
    protected int $anio;
    protected MetasEmpresaService $service;

    public function __construct(int $empresaId, int $anio, MetasEmpresaService $service)
    {
        $this->empresaId = $empresaId;
        $this->anio = $anio;
        $this->service = $service;
    }

    public function array(): array
    {
        $cuentas = $this->service->cuentasTemplate();
        $months = $this->service->months();

        $registros = MetasEmpresa::where('empresa_id', $this->empresaId)
            ->whereYear('periodo', $this->anio)
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->periodo)->month);

        $rows = [];

        $rows[] = array_merge(['CARGA MASIVA DE PRESUPUESTO', ''], $months);
        $rows[] = array_merge(['Cuenta', 'Descripción'], array_fill(0, 12, 'Presupuesto'));

        foreach ($cuentas as $cuenta) {
            $row = [$cuenta['cuenta'], $cuenta['nombre']];

            for ($m = 1; $m <= 12; $m++) {
                $valor = 0;

                if (isset($registros[$m])) {
                    $items = json_decode($registros[$m]->valor, true) ?: [];
                    $match = collect($items)->firstWhere('cuenta', (string) $cuenta['cuenta']);
                    $valor = $match['valor'] ?? 0;
                }

                $row[] = $valor;
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastRow = count($this->service->cuentasTemplate()) + 2;
                $lastCol = Coordinate::stringFromColumnIndex(14); // N

                $sheet->mergeCells('A1:B1');
                $sheet->freezePane('C3');
                $sheet->setAutoFilter("A2:{$lastCol}{$lastRow}");

                $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3FBDEE'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'D9E2F3'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                $sheet->getStyle("C3:{$lastCol}{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(42);

                for ($i = 3; $i <= 14; $i++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setWidth(16);
                }
            },
        ];
    }
}
