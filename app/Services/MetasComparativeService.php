<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\MetasEmpresa;

class MetasComparativeService
{
    private const CUENTAS_INGRESOS = ['41', '42', '4210'];

    private function clasificarCuenta(string $cuenta): string
    {
        return in_array($cuenta, self::CUENTAS_INGRESOS, true) ? 'ingresos' : 'egresos';
    }

    private function emptyTotals(): array
    {
        return [
            'total_informe' => 0.0,
            'total_meta' => 0.0,
            'porcentaje_total' => null,
        ];
    }

    public function execute(int $empresaId, $informePorMesRaw, ?string $fechaContext = null): array
    {
        // Normalización optimizada en una línea (si viene JSON -> decode, si ya es array -> usarlo)
        $informePorMes = is_array($informePorMesRaw) ? $informePorMesRaw : (json_decode($informePorMesRaw, true) ?: []);

        // Helper parseNumber para convertir strings numéricos con formato a float (ej: "1.234,56" -> 1234.56)
        $parseNumber = function ($number) {
            if ($number === null || $number === '' || $number === '-') {
                return 0.0;
            }
            if (is_numeric($number)) {
                return (float) $number;
            }
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
            return (float) $number;
        };

        // Mapeo de meses (en minúscula para comparación insensible)
        $monthMap = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
            'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'setiembre' => 9,
            'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
        ];

        // Intento de obtener el año del contexto (si la fecha es válida), sino uso el año actual
        try {
            $year = $fechaContext ? Carbon::parse($fechaContext)->year : Carbon::now()->year;
        } catch (\Throwable $e) {
            $year = Carbon::now()->year;
        }

        // Extraigo las claves de meses (ignorando 'descripcionct', 'Total Mes' y 'Cuenta')
        $monthKeys = array_filter(array_keys($informePorMes), function ($k) {
            return !in_array($k, ['descripcionct', 'Total Mes', 'Cuenta'], true);
        });

        // Consulta optimizada: traigo todas las metas del año para la empresa y las agrupo por mes (clave numérica)
        $metasQuery = MetasEmpresa::where('empresa_id', $empresaId)
            ->whereYear('periodo', $year)
            ->get()
            ->groupBy(fn($m) => Carbon::parse($m->periodo)->month);

        $result = [];
        $cuentasArray = $informePorMes['Cuenta'] ?? [];
        $descripcionct = $informePorMes['descripcionct'] ?? [];

        // Recorro cada mes del informe (en el orden que vienen) y construyo la comparación con las metas
        foreach ($monthKeys as $monthKey) {
            $keyLower = mb_strtolower($monthKey);
            $monthNumber = $monthMap[$keyLower] ?? (is_numeric($monthKey) ? intval($monthKey) : null);
            $periodo = $monthNumber ? Carbon::create($year, $monthNumber, 1)->format('Y-m-d') : null;
            $metasForMonth = $monthNumber && isset($metasQuery[$monthNumber]) ? $metasQuery[$monthNumber][0] : null;
            $metaMap = [];

            // Si hay metas para el mes, decodifico su valor (JSON) y construyo un mapa de cuenta => metaValor
            if ($metasForMonth) {
                $decoded = json_decode($metasForMonth->valor ?? null, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $m) {
                        $metaMap[(string)($m['cuenta'] ?? '')] =
                            isset($m['valor']) ? floatval($m['valor']) : 0.0;
                    }
                }
            }

            $cuentasResult = [];
            $totalesMes = [
                'ingresos' => $this->emptyTotals(),
                'egresos' => $this->emptyTotals(),
            ];

            foreach ($cuentasArray as $idx => $cuentaNum) {
                $cuentaNum = (string) $cuentaNum;
                $grupo = $this->clasificarCuenta($cuentaNum);

                $descripcion = $descripcionct[$idx] ?? null;
                $rawValorInforme = $informePorMes[$monthKey][$idx] ?? 0;
                $valorInforme = is_string($rawValorInforme) ? $parseNumber($rawValorInforme) : floatval($rawValorInforme);
                $metaValor = $metaMap[$cuentaNum] ?? null;
                $porcentaje = ($metaValor && $metaValor != 0.0) ? ($valorInforme / $metaValor) * 100.0 : null;

                $totalesMes[$grupo]['total_informe'] += $valorInforme;
                if ($metaValor !== null) {
                    $totalesMes[$grupo]['total_meta'] += $metaValor;
                }

                $cuentasResult[] = [
                    'cuenta' => $cuentaNum,
                    'descripcion' => $descripcion,
                    'grupo' => $grupo,
                    'valor_informe' => $valorInforme,
                    'meta' => $metaValor,
                    'porcentaje' => $porcentaje,
                ];
            }

            foreach (['ingresos', 'egresos'] as $grupo) {
                $ti = $totalesMes[$grupo]['total_informe'];
                $tm = $totalesMes[$grupo]['total_meta'];
                $totalesMes[$grupo]['porcentaje_total'] = $tm != 0.0 ? ($ti / $tm) * 100.0 : null;
            }

            $result[$monthKey] = [
                'periodo' => $periodo,
                'cuentas' => $cuentasResult,
                'totales_ingresos' => $totalesMes['ingresos'],
                'totales_egresos' => $totalesMes['egresos'],
            ];
        }

        // Construir columna 'Total' sumando por cuenta a través de los meses
        $cuentasTotal = [];
        $totalesAll = [
            'ingresos' => $this->emptyTotals(),
            'egresos' => $this->emptyTotals(),
        ];

        foreach ($cuentasArray as $idx => $cuentaNum) {
            $cuentaNum = (string) $cuentaNum;
            $grupo = $this->clasificarCuenta($cuentaNum);

            $descripcion = $descripcionct[$idx] ?? null;
            $sumaInforme = 0.0;
            $sumaMeta = 0.0;

            // Para cada mes, busco el valor de la cuenta y su meta (si existen) y los sumo al total
            foreach ($monthKeys as $mKey) {
                $r = $result[$mKey]['cuentas'][$idx] ?? null;
                if ($r) {
                    $sumaInforme += floatval($r['valor_informe'] ?? 0);
                    $sumaMeta += floatval($r['meta'] ?? 0);
                }
            }

            $cuentasTotal[] = [
                'cuenta' => $cuentaNum,
                'descripcion' => $descripcion,
                'grupo' => $grupo,
                'valor_informe' => $sumaInforme,
                'meta' => $sumaMeta ?: null,
                'porcentaje' => $sumaMeta != 0.0 ? ($sumaInforme / $sumaMeta) * 100.0 : null,
            ];

            $totalesAll[$grupo]['total_informe'] += $sumaInforme;
            $totalesAll[$grupo]['total_meta'] += $sumaMeta;
        }

        foreach (['ingresos', 'egresos'] as $grupo) {
            $ti = $totalesAll[$grupo]['total_informe'];
            $tm = $totalesAll[$grupo]['total_meta'];
            $totalesAll[$grupo]['porcentaje_total'] = $tm != 0.0 ? ($ti / $tm) * 100.0 : null;
        }

        $result['Total'] = [
            'periodo' => null,
            'cuentas' => $cuentasTotal,
            'totales_ingresos' => $totalesAll['ingresos'],
            'totales_egresos' => $totalesAll['egresos'],
        ];

        return $result;
    }
}
