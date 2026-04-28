<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormatoBalanceExport implements FromArray, WithStyles
{
    public function array(): array
    {
        return [
            ['Digita el nombre'], // Editable por el usuario
            ['NIT '],    // Editable por el usuario
            ['BALANCE DE COMPROBACION'],
            ['DEL 01 DE FEBRERO DE 2024 AL 29 DE FEBRERO DE 2024'],
            ['', '','', '','', '','', '','', '','', '','', '','', ''],
            ['Cta Contable', 'Nombre Cuenta', 'Saldo Anterior', 'Debitos', 'Creditos', 'Saldo Final']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Negrita en las filas de encabezado
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A6:F6')->getFont()->setBold(true);

        // Ancho automático
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}

