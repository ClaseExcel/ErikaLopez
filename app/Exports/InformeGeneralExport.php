<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class InformeGeneralExport implements FromView, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.estadosfinancieros.informesexcel.export-estado-acumulado', $this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | ESTILOS GENERALES
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {
        return [
             2 => [ // Fila de encabezados
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '919396'
                    ]
                ],
            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTOS PARA FORMATO NUMÉRICO Y TOTALES
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                /*
                |--------------------------------------------------------------------------
                | FORMATO NUMÉRICO DESDE B2 HASTA FINAL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('B2:' . $highestColumn . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            },
        ];
    }
}
