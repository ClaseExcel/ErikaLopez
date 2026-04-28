<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelPlantillaCalendarioExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{
    private $calendario; // Declara la propiedad

    public function __construct(array $calendario)
    {
        $this->calendario = $calendario; //Asignamos el valor a la propiedad
    }
    public function array(): array
    {
        return $this->calendario;
    }


    public function headings(): array
    {
        return [
            [
                "CODIGO RESPONSABILIDAD TRIBUTARIA", "DETALLE RESPONSABILIDAD TRIBUTARIA", "ULTIMOS 2 DIGITOS",  "ULTIMO DIGITO",
                "RANGO INICIAL", "RANGO FINAL","FECHA DE VENCIMIENTO","CODIGO MUNICIPIO"
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:H1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0070C0');

                $event->sheet->getDelegate()->getStyle('A1:H1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('0070C0');


                $event->sheet->getStyle('A1:H1')->getFont()
                    ->setSize(14)
                    ->setBold(true)
                    ->getColor()->setRGB('FFFFFF');



                $event->sheet->getStyle('A1:H1')->getFont()
                    ->setSize(12)
                    ->setBold(true)
                    ->getColor()->setRGB('FFFFFF');

                $event->sheet->setAutoFilter('A1:H1');


                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $event->sheet->getDelegate()->getStyle('A1:H1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
