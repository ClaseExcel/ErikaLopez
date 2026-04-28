<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InformeEstadoPatrimonioExport implements FromView, WithColumnWidths, WithStyles
{
    protected $informeData;
    protected $anio;
    protected $anioAnterior;
    protected $mes;
    protected $tipo;
    protected $compania;

    public function __construct($informeData, $anio, $anioAnterior, $mes, $tipo, $compania)
    {
        $this->informeData = $informeData;
        $this->anio = $anio;
        $this->anioAnterior = $anioAnterior;
        $this->mes = $mes;
        $this->compania = $compania;
        $this->tipo = $tipo;
    }

    /**
     * Renderiza la vista Blade para generar el Excel.
     */
    public function view(): View
    {
        return view('admin.estadosfinancieros.informesexcel.export-estado-patrimonio', [
            'informe' => $this->informeData,
            'anio' => $this->anio,
            'anioAnterior' => $this->anioAnterior,
            'mes' => $this->mes,
            'tipo' => $this->tipo,
            'compania' => $this->compania,
        ]);
    }

    /**
     * Define el ancho de las columnas en Excel.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 40, // Concepto
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }

    /**
     * Aplica estilos generales al Excel.
     */
        /**
     * Aplica estilos generales al Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // Encabezados (fondo gris y texto blanco)
        $sheet->getStyle('A2:I4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // texto blanco
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '919396'], // gris de fondo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Bordes para todas las celdas usadas
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = "A1:{$highestColumn}{$highestRow}";

        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Alinear valores numéricos a la derecha y aplicar formato
        $sheet->getStyle('B:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B:I')->getNumberFormat()->setFormatCode('#,##0');

        return [];
    }
}