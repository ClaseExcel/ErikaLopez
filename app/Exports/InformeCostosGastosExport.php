<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InformeCostosGastosExport implements FromArray, WithStyles, ShouldAutoSize
{
    protected $tableData;
    protected $fecha,$compania,$nit;
    protected $logo;


    public function __construct($tableData, $fecha,$compania,$nit,$logo = null)
    {
        $this->tableData = collect($tableData);
        $this->fecha = $fecha;
        $this->nit = $nit;
        $this->compania = $compania;
        $this->logo = $logo;
    }

    public function array(): array
    {
        $fechaInicio = Carbon::parse($this->fecha)->firstOfMonth();
        $mesLimite = $fechaInicio->month;

        $meses = [
            1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',
            5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',
            9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
        ];

        $mesesMostrar = array_slice($meses, 0, $mesLimite, true);
        $filtroConMovimiento = function ($fila) use ($mesesMostrar) {

            foreach ($mesesMostrar as $mes) {
                if (($fila->$mes ?? 0) != 0) {
                    return true;
                }
            }

            if (($fila->total_acumulado ?? 0) != 0) {
                return true;
            }

            return false;
        };
       $grupo4 = $this->tableData
            ->filter(fn($f)=>str_starts_with($f->cuenta,'4'))
            ->filter($filtroConMovimiento);

        $grupo5 = $this->tableData
            ->filter(fn($f)=>str_starts_with($f->cuenta,'5'))
            ->filter($filtroConMovimiento);

        $grupo6 = $this->tableData
            ->filter(fn($f)=>str_starts_with($f->cuenta,'6'))
            ->filter($filtroConMovimiento);

        $grupo7 = $this->tableData
            ->filter(fn($f)=>str_starts_with($f->cuenta,'7'))
            ->filter($filtroConMovimiento);

        $rows = [];

        // Encabezado
        $header = ['Cuenta','Descripción'];
        foreach($mesesMostrar as $mes){
            $header[] = ucfirst($mes);
        }
        $header[] = 'Acumulado';
        $header[] = '%';
        $rows[] = $header;

        //Ingresos
        
        $rows[] = ['Ingresos'];
        $totalIngresosAcumulado = $grupo4->sum('total_acumulado');

        foreach($grupo4 as $fila){
            $row = [$fila->cuenta,$fila->descripcion];
            foreach($mesesMostrar as $mes){
                $row[] = $fila->$mes ?? 0;
            }
            $acumulado = $fila->total_acumulado ?? 0;

            $row[] = $acumulado;
            $row[] = $totalIngresosAcumulado != 0 
                ? ($acumulado / $totalIngresosAcumulado) 
                : 0;

            $rows[] = $row;
        }

        $subtotal4 = ['Subtotal Ingresos',''];
        foreach($mesesMostrar as $mes){
            $subtotal4[] = $grupo4->sum($mes);
        }
        $subtotal4[] = $grupo4->sum('total_acumulado');
        $rows[] = $subtotal4;

        $rows[] = [];
        // gastos 
        $rows[] = ['Gastos'];

        foreach($grupo5 as $fila){
            $row = [$fila->cuenta,$fila->descripcion];
            foreach($mesesMostrar as $mes){
                $row[] = $fila->$mes ?? 0;
            }
            $acumulado = $fila->total_acumulado ?? 0;

            $row[] = $acumulado;
            $row[] = $totalIngresosAcumulado != 0 
                ?($acumulado / $totalIngresosAcumulado) 
                : 0;
            $rows[] = $row;
        }

        $subtotal5 = ['Subtotal Gastos',''];
        foreach($mesesMostrar as $mes){
            $subtotal5[] = $grupo5->sum($mes);
        }
        $subtotalAcumulado = $grupo5->sum('total_acumulado');

        $subtotal5[] = $subtotalAcumulado;

        $subtotal5[] = $totalIngresosAcumulado != 0 
            ? ($subtotalAcumulado / $totalIngresosAcumulado) 
            : 0;
        $rows[] = $subtotal5;

        $rows[] = [];

        // COSTOS
        $rows[] = ['Costos'];

        foreach($grupo6 as $fila){
            $row = [$fila->cuenta,$fila->descripcion];
            foreach($mesesMostrar as $mes){
                $row[] = $fila->$mes ?? 0;
            }
            $acumulado = $fila->total_acumulado ?? 0;

            $row[] = $acumulado;
            $row[] = $totalIngresosAcumulado != 0 
                ?($acumulado / $totalIngresosAcumulado) 
                : 0;
                $rows[] = $row;
        }

        $subtotal6 = ['Subtotal Costos',''];
        foreach($mesesMostrar as $mes){
            $subtotal6[] = $grupo6->sum($mes);
        }
         $subtotalAcumulado = $grupo6->sum('total_acumulado');

        $subtotal6[] = $subtotalAcumulado;

        $subtotal6[] = $totalIngresosAcumulado != 0 
            ? ($subtotalAcumulado / $totalIngresosAcumulado) 
            : 0;
        $rows[] = $subtotal6;

        $rows[] = [];

        // GASTOS
        $rows[] = ['Costos de producción'];

        foreach($grupo7 as $fila){
            $row = [$fila->cuenta,$fila->descripcion];
            foreach($mesesMostrar as $mes){
                $row[] = $fila->$mes ?? 0;
            }
            $acumulado = $fila->total_acumulado ?? 0;

            $row[] = $acumulado;
            $row[] = $totalIngresosAcumulado != 0 
                ?($acumulado / $totalIngresosAcumulado) 
                : 0;
                $rows[] = $row;
        }

        $subtotal7 = ['Subtotal Costos de producción',''];
        foreach($mesesMostrar as $mes){
            $subtotal7[] = $grupo7->sum($mes);
        }
         $subtotalAcumulado = $grupo7->sum('total_acumulado');

        $subtotal7[] = $subtotalAcumulado;

        $subtotal7[] = $totalIngresosAcumulado != 0 
            ? ($subtotalAcumulado / $totalIngresosAcumulado) 
            : 0;
        $rows[] = $subtotal7;

       $totalGeneral = ['UTILIDAD DEL EJERCICIO',''];

        foreach($mesesMostrar as $mes){
            $totalGeneral[] =
                $grupo4->sum($mes)
                - $grupo5->sum($mes)
                - $grupo6->sum($mes)
                - $grupo7->sum($mes);
        }
        $utilidadAcumulada =
            $grupo4->sum('total_acumulado')
            - $grupo5->sum('total_acumulado')
            - $grupo6->sum('total_acumulado')
            - $grupo7->sum('total_acumulado');

        $totalGeneral[] = $utilidadAcumulada;

        $totalGeneral[] = $totalIngresosAcumulado != 0 
            ? ($utilidadAcumulada / $totalIngresosAcumulado) 
            : 0;

        $rows[] = $totalGeneral;

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // 🔥 INSERTAR ESPACIO ARRIBA
        $sheet->insertNewRowBefore(1, 5);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        // 🔹 Insertar filas arriba
        $colIndex = Coordinate::columnIndexFromString($highestColumn);
        $columnaAntesPorcentaje = Coordinate::stringFromColumnIndex($colIndex - 1);

        // 🔹 Combinar celdas para centrar el texto
        $sheet->mergeCells("A1:{$highestColumn}1");
        $sheet->mergeCells("A2:{$highestColumn}2");
        $sheet->mergeCells("A3:{$highestColumn}3");
        $sheet->mergeCells("A4:{$highestColumn}4");
        $sheet->mergeCells("A5:{$highestColumn}5");
        // 🔹 Bordes generales
        $sheet->getStyle("A6:{$highestColumn}{$highestRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // 🔹 Encabezado
        $sheet->getStyle("A6:{$highestColumn}6")->applyFromArray([
            'font'=>[
                'bold'=>true,
                'color'=>['rgb'=>'FFFFFF']
            ],
            'fill'=>[
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>['rgb'=>'919396']
            ]
        ]);
          // 🔹 Combinar celdas
        $sheet->mergeCells("A1:{$highestColumn}1");
        $sheet->mergeCells("A2:{$highestColumn}2");
        $sheet->mergeCells("A3:{$highestColumn}3");
        $sheet->mergeCells("A4:{$highestColumn}4");
        $sheet->mergeCells("A5:{$highestColumn}5");
        $sheet->setCellValue("A5", "(Cifras expresadas en pesos colombianos)");
        // 🔹 Formato miles (desde columna C hasta antes del %)
        $colIndex = Coordinate::columnIndexFromString($highestColumn);
        $columnaAntesPorcentaje = Coordinate::stringFromColumnIndex($colIndex - 1);

        $sheet->getStyle("C7:{$columnaAntesPorcentaje}{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // 🔹 Formato porcentaje (última columna)
        $sheet->getStyle("{$highestColumn}7:{$highestColumn}{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('0.00%');
        // 🔹 Texto
        $sheet->setCellValue("A1", $this->compania ?? '');
        $sheet->setCellValue("A2", "NIT - " . ($this->nit ?? ''));
        $sheet->setCellValue("A3", "ESTADO INGRESOS Y GASTOS ");
       $sheet->setCellValue(
                "A4",
                "A " . strtoupper(
                    Carbon::parse($this->fecha)
                        ->locale('es')
                        ->translatedFormat('F d \\d\\e Y')
                )
            );
        

        // 🔹 Estilo centrado
        $sheet->getStyle("A1:A5")->applyFromArray([
            'alignment' => [
                'horizontal' => 'center',
            ],
            'font' => [
                'bold' => true,
                'size' => 12
            ]
        ]);
        if(!empty($this->logo) && $this->logo != '*'){

            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo Empresa');

            // convertir base64 a archivo temporal
            $imageData = base64_decode($this->logo);
            $tempFile = tempnam(sys_get_temp_dir(), 'logo');
            file_put_contents($tempFile, $imageData);

            $drawing->setPath($tempFile);
            $drawing->setHeight(60); // tamaño del logo

            // 📍 POSICIÓN (ajústalo aquí)
            $drawing->setCoordinates($highestColumn . '1'); 
            // Ej: E1, F1, G1 dependiendo columnas

            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);

            $drawing->setWorksheet($sheet);
        }

       

        // 🔹 Ajustar ancho descripción (columna B)
        $sheet->getColumnDimension('B')->setWidth(35);

        // 🔹 Pintar subtítulos dinámicamente
        for($row=1;$row<=$highestRow;$row++){

            $value = $sheet->getCell("A{$row}")->getValue();

            if(in_array($value,['Ingresos','Subtotal Ingresos','Gastos','Subtotal Gastos','Costos','Subtotal Costos','Costos de producción','Subtotal Costos de producción'])){
                $sheet->getStyle("A{$row}:{$highestColumn}{$row}")
                    ->applyFromArray([
                        'font'=>[
                            'bold'=>true,
                            'color'=>['rgb'=>'000000']
                        ],
                        'fill'=>[
                            'fillType'=>Fill::FILL_SOLID,
                            'startColor'=>['rgb'=>'E8F4FB']
                        ]
                    ]);
            }

            if($value === 'UTILIDAD DEL EJERCICIO'){
                $sheet->getStyle("A{$row}:{$highestColumn}{$row}")
                    ->applyFromArray([
                        'font'=>[
                            'bold'=>true,
                            'color'=>['rgb'=>'FFFFFF']
                        ],
                        'fill'=>[
                            'fillType'=>Fill::FILL_SOLID,
                            'startColor'=>['rgb'=>'3FBDEE']
                        ]
                    ]);
            }
        }

        return [];
    }
}