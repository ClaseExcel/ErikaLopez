<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InformeEstadoResultadosExport  implements FromView, WithColumnWidths, WithStyles
{
    protected $informeData;
    protected $anio;
    protected $anioAnterior;
    protected $mes;
    protected $tipo;
    protected $compania;
    

    public function __construct($informeData, $anio, $anioAnterior, $mes, $tipo, $compania)
    {
            // 🔧 Normalizar solo si tipo == 2
        if ($tipo == 2) {
            foreach ($informeData as &$fila) {
                foreach (['totalaño1', 'totalaño2',  'VARIACION'] as $campo) {
                    if (isset($fila[$campo])) {
                        // Limpiar valores tipo string "1.234.567" o "1,234,567"
                        $valor = str_replace([',', '.'], '', $fila[$campo]);
                        $fila[$campo] = is_numeric($valor) ? (float)$valor : 0.0;
                    }
                }
            }
            unset($fila);
        }
        $this->informeData = $informeData;
        $this->anio = $anio;
        $this->anioAnterior = $anioAnterior;
        $this->mes = $mes;
        $this->tipo = $tipo;
        $this->compania = $compania;
    }

    public function view(): View
    {
        return view('admin.estadosfinancieros.informesexcel.export-estado-resultados', [
            'informeData' => $this->informeData,
            'anio' => $this->anio,
            'anioAnterior' => $this->anioAnterior,
            'mes' => $this->mes,
            'tipo' => $this->tipo,
            'compania' => $this->compania,
        ]);
    }

    public function columnWidths(): array
    {
           // ✅ Definir ancho dinámico según tipo
        if ($this->tipo == 4) {
            // Columnas hasta la M (13 columnas: A–M)
            $columnWidths = ['A' => 50];
            foreach (range('B', 'M') as $col) {
                $columnWidths[$col] = 15;
            }
            return $columnWidths;
        }
        return [
            'A' => 50,
            'B' => 20,
            'C' => 20,
            'D' => 15,
            'E' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
         // === 🔧 Estilo para tipo == 4 ===
        if ($this->tipo == 4) {
            // Cabecera en negrita y centrada
            $sheet->getStyle('A1:M1')->getFont()->setBold(true);
            $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal('center');

            // Alinear todos los valores a la derecha (números con .)
            $sheet->getStyle('B:M')->getAlignment()->setHorizontal('right');

            // ❌ No aplicar formato numérico, dejar texto tal cual viene del Blade
            // porque ya los formateas con puntos en la vista.
            // (Si lo pones, Excel reemplaza los puntos por comas automáticamente)

            return [];
        }

        // Encabezado en negrita y centrado
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        // Alinear valores numéricos a la derecha
        $sheet->getStyle('B:E')->getAlignment()->setHorizontal('right');

        // Formato general de miles
        $sheet->getStyle('B:E')
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        return [];
    }
}
