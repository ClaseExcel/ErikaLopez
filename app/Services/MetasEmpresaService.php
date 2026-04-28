<?php

namespace App\Services;

use App\Models\MetasEmpresa;
use Carbon\Carbon;

class MetasEmpresaService
{
    public function cuentasTemplate(): array
    {
        return [
            ['cuenta' => '41',   'nombre' => 'Ingresos'],
            ['cuenta' => '4175', 'nombre' => 'Devoluciones en ventas'],
            ['cuenta' => '6',    'nombre' => 'Costos de venta'],
            ['cuenta' => '52',   'nombre' => 'Gastos operacionales'],
            ['cuenta' => '5105', 'nombre' => 'Gastos de personal'],
            ['cuenta' => '5110', 'nombre' => 'Honorarios'],
            ['cuenta' => '5115', 'nombre' => 'Impuestos'],
            ['cuenta' => '5120', 'nombre' => 'Arrendamientos'],
            ['cuenta' => '5130', 'nombre' => 'Seguros'],
            ['cuenta' => '5135', 'nombre' => 'Servicios'],
            ['cuenta' => '5140', 'nombre' => 'Gastos legales'],
            ['cuenta' => '5145', 'nombre' => 'Mantto. Ed. Y Equipos'],
            ['cuenta' => '5155', 'nombre' => 'Gastos de viaje'],
            ['cuenta' => '5160', 'nombre' => 'Depreciación, Amortización, Deterioro'],
            ['cuenta' => '5195', 'nombre' => 'Diversos'],
            ['cuenta' => '42',   'nombre' => 'Otros Ingresos'],
            ['cuenta' => '53',   'nombre' => 'Otros Egresos'],
            ['cuenta' => '5305', 'nombre' => 'Gastos financieros'],
            ['cuenta' => '54',   'nombre' => 'Impuesto de renta'],
            ['cuenta' => '4210', 'nombre' => 'Ingresos financieros'],
        ];
    }

    public function months(): array
    {
        return [
            'ENERO',
            'FEBRERO',
            'MARZO',
            'ABRIL',
            'MAYO',
            'JUNIO',
            'JULIO',
            'AGOSTO',
            'SEPTIEMBRE',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE'
        ];
    }

    public function normalizeExcelNumber($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);
        $value = str_replace(["\xc2\xa0", ' '], '', $value);

        if (str_contains($value, ',') && str_contains($value, '.')) {
            if (strrpos($value, ',') > strrpos($value, '.')) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                $value = str_replace(',', '', $value);
            }
        } elseif (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }

    public function saveYear(int $empresaId, int $anio, array $filas): void
    {
        for ($month = 1; $month <= 12; $month++) {
            $payload = [];

            foreach ($filas as $fila) {
                $payload[] = [
                    'cuenta' => $fila['cuenta'],
                    'nombre' => $fila['nombre'],
                    'valor'  => (float) ($fila['values'][$month - 1] ?? 0),
                ];
            }

            MetasEmpresa::updateOrCreate(
                [
                    'empresa_id' => $empresaId,
                    'periodo'    => Carbon::create($anio, $month, 1)->startOfMonth(),
                ],
                [
                    'valor' => json_encode($payload),
                ]
            );
        }
    }
}
