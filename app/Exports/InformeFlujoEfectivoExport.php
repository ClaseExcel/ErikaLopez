<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InformeFlujoEfectivoExport implements FromView, WithColumnWidths, WithStyles, WithEvents
{
    protected $informes;
    protected $anio;
    protected $anioAnterior;
    protected $mes;
    protected $compania;

    public function __construct($informes, $anio,$anioanterior, $mes, $compania)
    {
        $this->informes = $informes;
        $this->anio = $anio;
        $this->anioAnterior = $anioanterior;
        $this->compania = $compania;
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE',
        ];

        $this->mes = $meses[(int) $mes] ?? '';
    }

    public function view(): View
    {
        return view('admin.estadosfinancieros.informesexcel.export-flujo-efectivo', [
            'informes' => $this->informes,
            'anio' => $this->anio,
            'anioAnterior' => $this->anioAnterior,
            'mes' => $this->mes,
            'compania' => $this->compania,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 60,
            'B' => 25,
            'C' => 25,
            'D' => 20,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Encabezados
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:E3')->getAlignment()->setVertical('center');

        // Alineación de números
        $sheet->getStyle('B:E')->getAlignment()->setHorizontal('right');

        // Bordes
        $sheet->getStyle('A3:E' . $sheet->getHighestRow())
              ->getBorders()->getAllBorders()
              ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Fondo gris para títulos de sección (mayúsculas completas)
        $highestRow = $sheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $sheet->getCell("A{$row}")->getValue();
            if (is_string($value) && ctype_upper(str_replace(' ', '', $value))) {
                $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:E{$row}")
                      ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFF3F3F3');
            }
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Obtener la última fila usada
                $highestRow = $sheet->getHighestRow();

                // Convertir columnas B–D a valores numéricos reales
                foreach (range(2, $highestRow) as $row) {
                    foreach (['B', 'C', 'D'] as $col) {
                        $cell = $sheet->getCell("{$col}{$row}");
                        $value = $cell->getValue();

                        // Si es numérico (aunque venga como texto), conviértelo
                        if (is_string($value) && preg_match('/^-?\d+(\.\d+)?$/', str_replace('.', '', $value))) {
                            $numericValue = floatval(str_replace('.', '', $value));
                            $sheet->setCellValueExplicit("{$col}{$row}", $numericValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        }
                    }
                }

                // Aplicar formato numérico a las columnas B-D
                $sheet->getStyle("B2:D{$highestRow}")
                      ->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }
}
