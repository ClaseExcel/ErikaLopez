<?php

namespace App\Exports;

use App\Models\ActividadCliente;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InformeActividadesExport implements FromCollection, WithHeadings,WithStyles, WithEvents
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $tipo;
    protected $id_user;

    public function __construct($fechaInicio, $fechaFin, $tipo, $id_user)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->tipo = $tipo;
        $this->id_user = $id_user;
    }

    public function sanitizeText($text)
    {
        if (!$text) return ''; // Si es null, devolver cadena vacía
        
        // Eliminar caracteres de control invisibles
        $text = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', ' ', $text);
        
        // Convertir tabulaciones y saltos de línea en espacios
        $text = str_replace(["\t", "\r", "\n"], ' ', $text);

        // Quitar espacios en exceso
        $text = trim($text);

        // Si comienza con "=", eliminarlo para evitar que Excel lo interprete como fórmula
        if (str_starts_with($text, '=')) {
            $text = substr($text, 1);
        }

        return $text;
    }


    public function collection()
    {
        // Base de la consulta
        $query = ActividadCliente::with([
            'reporte_actividades.estado_actividades',
            'actividad',
            'cliente',
            'empresa_asociada',
            'usuario'
        ])
        ->where(function ($query) {
            $query->whereHas('empresa_asociada', function ($query) {
                $query->where('estado', 1);
            })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                $query->where('estado', 1);
            });
        });

        // Aplicar filtros según el tipo
        if ($this->tipo === 'fecha') {
            $query->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
        } elseif ($this->tipo === 'user_crea_Act') {
            $query->where('user_id', $this->id_user);
        } elseif ($this->tipo === 'responsable') {
            $query->where('usuario_id', $this->id_user);
        }

        $actividades = $query->get();


        // Retornar datos formateados para la exportación
        return $actividades->map(function ($actividad) {
            return [
                'ID' => $actividad->id ?? '',
                'Nombre Actividad' => $this->sanitizeText($actividad->nombre ?? ''), // LIMPIEZA AQUÍ
                'Progreso' => $actividad->progreso !== null ? $actividad->progreso . '%' : '',
                'Prioridad' => ($actividad->prioridad === 1 ? 'SI' : 'NO'),
                'Fecha Vencimiento' => $actividad->fecha_vencimiento ? Carbon::parse($actividad->fecha_vencimiento)->format('d-m-Y') : '',
                'Periodicidad' => $this->sanitizeText($actividad->periodicidad ?? ''),
                'Nota' => $this->sanitizeText($actividad->nota ?? ''),
                'Responsable' => $this->sanitizeText(optional($actividad->usuario)->nombres . ' ' . optional($actividad->usuario)->apellidos),
                'Creador' => $this->sanitizeText(optional($actividad->usuario_crea_act)->nombres . ' ' . optional($actividad->usuario_crea_act)->apellidos ?? ''),
                'Empresa Asociada' => $this->sanitizeText(optional($actividad->empresa_asociada)->razon_social ?? ''),
                'Estado Actividad' => $this->sanitizeText(optional($actividad->reporte_actividades)->estado_actividades->nombre ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Actividad',
            'Progreso',
            'Prioridad',
            'Fecha Vencimiento',
            'Periodicidad',
            'Nota',
            'Responsable',
            'Creador',
            'Empresa Asociada',
            'Estado Actividad'
        ];
    }
    public function beforeExport($event)
    {
        $event->writer->getDelegate()->setPreCalculateFormulas(false);
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:k1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '394D73']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $columnWidths = [
                    'A' => 15,  // ID
                    'B' => 30,  // Nombre de la actividad
                    'C' => 15,  // Progreso
                    'D' => 20,  // Prioridad
                    'E' => 20,  // Fecha de Vencimiento
                    'F' => 15,  // Periodicidad
                    'G' => 35,  // Nota
                    'H' => 35,  // Responsable
                    'I' => 35,  // Usuario crea actividad
                    'J' => 65,  // Empresa Asociada
                    'K' => 30,  // Estado Actividad
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            }
        ];
    }
}
    
