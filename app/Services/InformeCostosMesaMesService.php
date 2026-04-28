<?php

namespace App\Services;

use App\Models\CentroCosto;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeCostosMesaMesService
{

    public function ejecutar($nit, $fechaInicio, $siigo, $datos2, $centro_costo = null)
    {
        // 🔴 PEGA AQUÍ TODO el código actual
       
        $configSiigo = [

                'NUBE' => [
                    'tabla' => 'clientes',
                    'cuenta' => 'codigo_cuenta_contable_ga',
                    'descripcion' => 'nombre_cuenta_contable_ga',
                    'fecha' => 'fechareporte_ga',
                    'debito' => 'movimiento_debito_ga',
                    'credito' => 'movimiento_credito_ga',
                ],

                'PYME' => [
                    'tabla' => 'clientes',
                    'cuenta' => DB::raw("REPLACE(CONCAT(
                                    TRIM(IFNULL(grupo,'')),
                                    TRIM(IFNULL(cuenta,'')),
                                    TRIM(IFNULL(subcuenta,''))
                                ), ' ', '')"),
                    'descripcion' => 'descripcion',
                    'fecha' => 'fechareporte',
                    'debito' => 'debitos',
                    'credito' => 'creditos',
                ],

                'LOGGRO' => [
                    'tabla' => 'loggro',
                    'cuenta' => 'cuenta',
                    'descripcion' => 'descripcion',
                    'fecha' => 'fechareporte',
                    'debito' => 'debitos',
                    'credito' => 'creditos',
                ],

                'CONTAPYME' => [
                    'tabla' => 'contapyme_completo',
                    'cuenta' => 'cuenta',
                    'descripcion' => 'descripcion',
                    'fecha' => 'fechareporte',
                    'debito' => 'debitos',
                    'credito' => 'creditos',
                ],

                'BEGRANDA' => [
                    'tabla' => 'begranda',
                    'cuenta' => 'cuenta',
                    'descripcion' => 'descripcion',
                    'fecha' => 'fechareporte',
                    'debito' => 'debitos',
                    'credito' => 'creditos',
                ],

                'DEFAULT' => [
                    'tabla' => 'informesgenericos',
                    'cuenta' => 'cuenta',
                    'descripcion' => 'descripcion',
                    'fecha' => 'fechareporte',
                    'debito' => 'debitos',
                    'credito' => 'creditos',
                ],
            ];
        $cfg = $configSiigo[$siigo] ?? $configSiigo['DEFAULT'];
        $fechaInicio = Carbon::parse($fechaInicio)->firstOfMonth();
        $mesLimite = $fechaInicio->month; // 1 a 12
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre',
        ];
        $selectMeses = [];

        foreach ($meses as $num => $nombre) {

            if ($num > $mesLimite) {
                break; // 🔥 aquí cortamos hasta el mes que el usuario pidió
            }

            $selectMeses[] = "
                SUM(
                    CASE 
                        WHEN MONTH({$cfg['fecha']}) = $num THEN
                            CASE 
                                WHEN LEFT({$cfg['cuenta']},1) IN ('1','5','6','7')
                                    THEN IFNULL({$cfg['debito']},0) - IFNULL({$cfg['credito']},0)
                                ELSE
                                    IFNULL({$cfg['credito']},0) - IFNULL({$cfg['debito']},0)
                            END
                        ELSE 0 
                    END
                ) AS $nombre
            ";
        }
        $totalAcumuladoSQL = "
        SUM(
            CASE 
                WHEN LEFT({$cfg['cuenta']},1) IN ('1','5','6','7')
                    THEN IFNULL({$cfg['debito']},0) - IFNULL({$cfg['credito']},0)
                ELSE
                    IFNULL({$cfg['credito']},0) - IFNULL({$cfg['debito']},0)
            END
        ) AS total_acumulado
        ";
        $informe = DB::table($cfg['tabla'])
            ->selectRaw("
                {$cfg['cuenta']} AS cuenta,
                {$cfg['cuenta']} AS cuenta_limpia,
                {$cfg['descripcion']} AS descripcion,
                " . implode(',', $selectMeses) . ",
                $totalAcumuladoSQL
            ")
            ->where('Nit', $nit)
            ->whereYear($cfg['fecha'], $fechaInicio->year)
            ->whereMonth($cfg['fecha'], '<=', $mesLimite)
            ->whereRaw("LENGTH({$cfg['cuenta']}) = 6")
            ->whereRaw("LEFT({$cfg['cuenta']},1) IN ('4','5','6','7')")
            ->groupBy(
                DB::raw($cfg['cuenta']),
                $cfg['descripcion']
            )
            ->orderBy('cuenta')
            ->get();
        
        return $informe;

    }

     function limpiarValor($valor)
    {
        if ($valor === null || $valor === '') return 0;
        $valor = str_replace(',', '', $valor); // Quita comas si hubiera
        return (float)$valor; // Fuerza float
    }
}
