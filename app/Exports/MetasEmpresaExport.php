<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MetasEmpresaExport implements FromArray, WithEvents, ShouldAutoSize
{
    protected array $metas;
    protected array $rows;
    protected array $months;
    protected array $metaInfo;

    public function __construct(array $metasComparative, $metaInfo = [])
    {
        $this->metas = $metasComparative;
        $this->months = array_keys($this->metas);
        $this->metaInfo = $metaInfo;

        $headerRow1 = ['SEGUIMIENTO PRESUPUESTAL ' . $this->metaInfo['compania'] ?? '', ''];
        foreach ($this->months as $m) {
            $headerRow1[] = $m;
            $headerRow1[] = '';
            $headerRow1[] = '';
        }

        $headerRow2 = ['Cuenta', 'Descripción'];
        foreach ($this->months as $m) {
            $headerRow2[] = 'Ejecución';
            $headerRow2[] = 'Presupuesto';
            $headerRow2[] = '%';
        }

        $this->rows = [];
        $this->rows[] = $headerRow1;
        $this->rows[] = $headerRow2;

        $primer = reset($this->metas);
        $cuentasBase = $primer['cuentas'] ?? [];

        foreach ($cuentasBase as $index => $cuentaBase) {
            $fila = [];
            $fila[] = $cuentaBase['cuenta'] ?? '';
            $fila[] = $cuentaBase['descripcion'] ?? '';

            foreach ($this->months as $m) {
                $r = $this->metas[$m]['cuentas'][$index] ?? null;

                $valorInforme = $this->toFloatOrNull($r['valor_informe'] ?? null);
                $meta = $this->toFloatOrNull($r['meta'] ?? null);
                $pctVal = $this->toFloatOrNull($r['porcentaje'] ?? null);

                if ($pctVal !== null) {
                    $pctVal = $pctVal / 100.0;
                }

                $fila[] = $valorInforme !== null ? (float) $valorInforme : null;
                $fila[] = $meta !== null ? (float) $meta : null;
                $fila[] = $pctVal !== null ? (float) $pctVal : null;
            }

            $this->rows[] = $fila;
        }

        $this->rows[] = $this->buildTotalsRow('TOTAL INGRESOS', 'totales_ingresos');
        $this->rows[] = $this->buildTotalsRow('TOTAL EGRESOS', 'totales_egresos');
    }

    protected function buildTotalsRow(string $label, string $totalsKey): array
    {
        $row = [$label, ''];

        foreach ($this->months as $m) {
            $tot = $this->metas[$m][$totalsKey] ?? [
                'total_informe' => null,
                'total_meta' => null,
                'porcentaje_total' => null,
            ];

            $tInf = $this->toFloatOrNull($tot['total_informe'] ?? null);
            $tMeta = $this->toFloatOrNull($tot['total_meta'] ?? null);
            $tPct = $this->toFloatOrNull($tot['porcentaje_total'] ?? null);

            if ($tPct !== null) { $tPct = $tPct / 100.0; }

            $row[] = $tInf !== null ? (float) $tInf : null;
            $row[] = $tMeta !== null ? (float) $tMeta : null;
            $row[] = $tPct !== null ? (float) $tPct : null;
        }

        return $row;
    }

    protected function toFloatOrNull($v)
    {
        if ($v === null || $v === '') { return null; }
        if (is_numeric($v)) { return (float) $v; }

        $n = str_replace('.', '', (string) $v);
        $n = str_replace(',', '.', $n);
        $n = preg_replace('/[^\d\-\.\+]/', '', $n);

        if ($n === '' || $n === null) { return null; }
        return (float) $n;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 7);

                $logoPath = $this->metaInfo['logo']
                ? storage_path('app/public/logo_cliente/' . $this->metaInfo['logo'])
                : public_path('images/logos/logo_contable.png');

                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo');
                    $drawing->setDescription('Logo Empresa');
                    $drawing->setPath($logoPath);
                    $drawing->setHeight(125);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(7);
                    $drawing->setOffsetY(7);
                    $drawing->setWorksheet($sheet);
                }

                $rowsCount = count($this->rows);
                if ($rowsCount < 2) { return; }

                $numMonths = count($this->months);
                $totalCols = 2 + ($numMonths * 3);
            
                // Merge del título (compañía)
                $sheet->mergeCells("A8:B8");
                $sheet->getStyle("A8:B8")->getAlignment()->setWrapText(true);

                // Header month merge
                for ($i = 0; $i < $numMonths; $i++) {
                    $startColIndex = 3 + ($i * 3);
                    $endColIndex = $startColIndex + 2;

                    $start = Coordinate::stringFromColumnIndex($startColIndex) . '8';
                    $end = Coordinate::stringFromColumnIndex($endColIndex) . '8';

                    $sheet->mergeCells("$start:$end");
                }

                // Header styles (filas 8 y 9)
                $headerRange = 'A8:' . Coordinate::stringFromColumnIndex($totalCols) . '9';
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($headerRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('3FBDEE');
                $sheet->getStyle($headerRange)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                // Freeze pane (datos comienzan fila 10)
                $sheet->freezePane('C10');

                $lastRow = $rowsCount + 7;
                $dataStartRow = 10;

                for ($i = 0; $i < $numMonths; $i++) {
                    $colInforme = Coordinate::stringFromColumnIndex(3 + ($i * 3));
                    $colMeta = Coordinate::stringFromColumnIndex(4 + ($i * 3));
                    $colPct = Coordinate::stringFromColumnIndex(5 + ($i * 3));

                    $rangeInforme = "{$colInforme}{$dataStartRow}:{$colInforme}{$lastRow}";
                    $rangeMeta = "{$colMeta}{$dataStartRow}:{$colMeta}{$lastRow}";
                    $rangePct = "{$colPct}{$dataStartRow}:{$colPct}{$lastRow}";

                    $sheet->getStyle($rangeInforme)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle($rangeMeta)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle($rangePct)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                }

                // Bordes
                $tableRange = 'A8:' . Coordinate::stringFromColumnIndex($totalCols) . $lastRow;
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFDCDCDC');

                // Resaltar las dos filas finales de totales
                $totalsStartRow = $lastRow - 1;
                $sheet->getStyle("A{$totalsStartRow}:" . Coordinate::stringFromColumnIndex($totalCols) . "{$lastRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$totalsStartRow}:" . Coordinate::stringFromColumnIndex($totalCols) . "{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F2F2');

                $sheet->getRowDimension(8)->setRowHeight(24);
                $sheet->getRowDimension(9)->setRowHeight(18);

                foreach (range('A', 'Z') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
