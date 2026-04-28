<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ConsultaPorCuentasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $consulta;
    protected $siigo;

    public function __construct($consulta, $siigo)
    {
        $this->consulta = $consulta;
        $this->siigo = $siigo;  // Aquí estaba el error
    }

    public function collection()
    {
        return $this->consulta;
    }

    public function headings(): array
    {
        // Define los encabezados de las columnas aquí
        if($this->siigo == 'PYME' || $this->siigo == 'NUBE'){
            return [
                'NIT',
                'Cuenta descripción',
                'Cuenta',
                'Saldo Mov.',
                'Descripción',
                'Saldo inicial',
                'Comprobante',
                'Fecha',
                // Añade más encabezados según sea necesario
            ];
        } else {
            return [
                'NIT',
                'Cuenta',
                'Descripcion',
                'Saldo anterior',
                'Débito',
                'Crédito',
                'Nuevo saldo',
                'Fecha reporte'
                // Añade más encabezados según sea necesario
            ];
        }
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a cada celda de A1 a N1 individualmente
        $range = 'A1:H1';

        
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '00C0C3'], // Color de fondo naranjado
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT], // Alineación a la izquierda
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN, // Grosor del borde
                    'color' => ['rgb' => '000000'], // Color del borde (negro)
                ],
            ],
        ]);
    }
}
