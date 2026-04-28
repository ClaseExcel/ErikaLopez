<?php

namespace App\Services;

use App\Models\CentroCosto;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeEstadoResultadosServices
{
    public function ejecutar($fechaInicio,$nit,$siigo,$centro_costo,$tipoinforme,$tipoinformepdf,$valorparautilidad)
    {
        $fechaInicio = Carbon::parse($fechaInicio)->firstOfMonth();
        $anio = $fechaInicio->year;
        $anioAnterior = $anio - 1;
        $mesn='0';
        $anioActual = $anio;
        if($siigo =='CONTAPYME'){
            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1) {
                $operacionTotalMes = 'ROUND(
                    CASE 
                        WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "1" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                        WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "2" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                        WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "3" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                        WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "4" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                        WHEN SUBSTRING(contapyme.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                        ELSE SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0)) 
                    END, 2)';
            } else {
                
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(contapyme.fechareporte) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI nuevo_saldo = 0 → asumir cierre contable
                                        WHEN IFNULL(contapyme.nuevo_saldo,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(contapyme.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(contapyme.debitos,0) - IFNULL(contapyme.creditos,0)
                                                ELSE
                                                    IFNULL(contapyme.creditos,0) - IFNULL(contapyme.debitos,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(contapyme.nuevo_saldo,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(contapyme.fechareporte) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI nuevo_saldo = 0 → asumir cierre contable
                                        WHEN IFNULL(contapyme.nuevo_saldo,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(contapyme.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(contapyme.debitos,0) - IFNULL(contapyme.creditos,0)
                                                ELSE
                                                    IFNULL(contapyme.creditos,0) - IFNULL(contapyme.debitos,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(contapyme.nuevo_saldo,0)
                                    END

                            END
                        )
                    ,2)';
            }

            $baseQuery = DB::table(DB::raw("(SELECT 
                            CASE
                                WHEN contapyme.cuenta = '41' THEN '41'
                                WHEN contapyme.cuenta = '6' THEN '6'
                                WHEN contapyme.cuenta = '7' THEN '7'
                                WHEN contapyme.cuenta = '4210' THEN '4210'
                                WHEN contapyme.cuenta = '42' THEN '42'
                                WHEN contapyme.cuenta = '51' THEN '51'
                                WHEN contapyme.cuenta = '52' THEN '52'
                                WHEN contapyme.cuenta = '5305' THEN '5305'
                                WHEN contapyme.cuenta = '53' THEN '53'
                                WHEN contapyme.cuenta = '5405' THEN '54'
                                ELSE contapyme.cuenta
                            END AS cuenta,
                            CASE
                                WHEN contapyme.cuenta = '41' THEN 'Ingresos de actividades ordinarias'
                                WHEN contapyme.cuenta = '6' THEN 'Costos de venta'
                                WHEN contapyme.cuenta = '7' THEN 'Costos de venta'
                                WHEN contapyme.cuenta = '4210' THEN 'Ingresos financieros'
                                WHEN contapyme.cuenta = '42' THEN 'Otros ingresos'
                                WHEN contapyme.cuenta = '51' THEN 'Gastos de administración'
                                WHEN contapyme.cuenta = '52' THEN 'Gastos de ventas'
                                WHEN contapyme.cuenta = '5305' THEN 'Gastos financieros'
                                WHEN contapyme.cuenta = '53' THEN 'Otros gastos'
                                WHEN contapyme.cuenta = '54' THEN 'Gastos impuesto de renta y cree'
                                ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE contapyme.cuenta = oi.agrupador_cuenta)
                            END AS descripcionct,
                            $operacionTotalMes AS total_mes,
                            YEAR(contapyme.fechareporte) AS year
                            FROM 
                                contapyme_completo as contapyme 
                            WHERE 
                                contapyme.Nit = ?
                                AND (
                                    SUBSTRING(contapyme.cuenta, 1, 2) IN ('41', '42', '51', '52', '53', '54')
                                    OR SUBSTRING(contapyme.cuenta, 1, 1) IN ('6', '7')
                                    OR SUBSTRING(contapyme.cuenta, 1, 4) IN ('4210', '5305')
                                )
                                AND (
                                    (YEAR(contapyme.fechareporte) = ?)
                                    OR (YEAR(contapyme.fechareporte) = ?)
                                )
                                %s
                            GROUP BY 
                                cuenta, descripcionct, year
                            ) AS subquery"));

            $bindings = [$nit, $anio, $anioAnterior];

            if ($tipoinforme == 2) {
                // Para informes tipo "corte"
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(contapyme.fechareporte) = ? AND MONTH(contapyme.fechareporte) = ?) 
                    OR 
                    (YEAR(contapyme.fechareporte) = ? AND MONTH(contapyme.fechareporte) = ?)
                )';
              
                //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = 12;
                    $mesn = 'Corte'.$fechaInicio->month;
                }else{
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = $mesn;
                    $mesn = 'Corte'.$fechaInicio->month;
                }
            } else{
                // Para informes mensuales con comparación de diciembre
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(contapyme.fechareporte) = ? AND MONTH(contapyme.fechareporte) = ?) 
                    OR 
                    (YEAR(contapyme.fechareporte) = ? AND MONTH(contapyme.fechareporte) = ?)
                )';
                $bindings[] = $anio;
                $bindings[] = $mesn;
                $bindings[] = $anioAnterior;
                $bindings[] = $mesn;
            } 

            $queryWithConditions = sprintf($baseQuery->toSql(), $additionalConditions);

            $informeQuery = DB::table(DB::raw('('.$queryWithConditions.') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(COALESCE(total_mes, 0)) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);

            // Añadir condición para el centro de costo si está presente
            if ($centro_costo) {
                $informeQuery->whereRaw('SUBSTRING(contapyme.cc_scc, 1, 4) = ?', [$centro_costo]);
            }

            // Ejecutar la consulta
            $informe = $informeQuery->get();
            
        }else if($siigo =='LOGGRO'){
            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1) {
                $operacionTotalMes = 'ROUND(
                    CASE 
                        WHEN SUBSTRING(loggro.cuenta, 1, 1) = "1" THEN SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0))
                        WHEN SUBSTRING(loggro.cuenta, 1, 1) = "2" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                        WHEN SUBSTRING(loggro.cuenta, 1, 1) = "3" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                        WHEN SUBSTRING(loggro.cuenta, 1, 1) = "4" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                        WHEN SUBSTRING(loggro.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0))
                        ELSE SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0)) 
                    END, 2)';
            } else {
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(loggro.fechareporte) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(loggro.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(loggro.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(loggro.debitos,0) - IFNULL(loggro.creditos,0)
                                                ELSE
                                                    IFNULL(loggro.creditos,0) - IFNULL(loggro.debitos,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(loggro.saldo_final,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(loggro.fechareporte) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(loggro.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(loggro.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(loggro.debitos,0) - IFNULL(loggro.creditos,0)
                                                ELSE
                                                    IFNULL(loggro.creditos,0) - IFNULL(loggro.debitos,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(loggro.saldo_final,0)
                                    END

                            END
                        )
                    ,2)';
            }

            $baseQuery = DB::table(DB::raw("(SELECT 
                            CASE
                                WHEN loggro.cuenta = '41' THEN '41'
                                WHEN loggro.cuenta = '6' THEN '6'
                                WHEN loggro.cuenta = '7' THEN '7'
                                WHEN loggro.cuenta = '4210' THEN '4210'
                                WHEN loggro.cuenta = '42' THEN '42'
                                WHEN loggro.cuenta = '51' THEN '51'
                                WHEN loggro.cuenta = '52' THEN '52'
                                WHEN loggro.cuenta = '5305' THEN '5305'
                                WHEN loggro.cuenta = '53' THEN '53'
                                WHEN loggro.cuenta = '5405' THEN '54'
                                ELSE loggro.cuenta
                            END AS cuenta,
                            CASE
                                WHEN loggro.cuenta = '41' THEN 'Ingresos de actividades ordinarias'
                                WHEN loggro.cuenta = '6' THEN 'Costos de venta'
                                WHEN loggro.cuenta = '7' THEN 'Costos de venta'
                                WHEN loggro.cuenta = '4210' THEN 'Ingresos financieros'
                                WHEN loggro.cuenta = '42' THEN 'Otros ingresos'
                                WHEN loggro.cuenta = '51' THEN 'Gastos de administración'
                                WHEN loggro.cuenta = '52' THEN 'Gastos de ventas'
                                WHEN loggro.cuenta = '5305' THEN 'Gastos financieros'
                                WHEN loggro.cuenta = '53' THEN 'Otros gastos'
                                WHEN loggro.cuenta = '54' THEN 'Gastos impuesto de renta y cree'
                                ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE loggro.cuenta = oi.agrupador_cuenta)
                            END AS descripcionct,
                            $operacionTotalMes AS total_mes,
                            YEAR(loggro.fechareporte) AS year
                            FROM 
                                loggro
                            WHERE 
                                loggro.Nit = ?
                                AND (
                                    SUBSTRING(loggro.cuenta, 1, 2) IN ('41', '42', '51', '52', '53', '54')
                                    OR SUBSTRING(loggro.cuenta, 1, 1) IN ('6', '7')
                                    OR SUBSTRING(loggro.cuenta, 1, 4) IN ('4210', '5305')
                                )
                                AND (
                                    (YEAR(loggro.fechareporte) = ?)
                                    OR (YEAR(loggro.fechareporte) = ?)
                                )
                                %s
                            GROUP BY 
                                cuenta, descripcionct, year
                            ) AS subquery"));

            $bindings = [$nit, $anio, $anioAnterior];

            if ($tipoinforme == 2) {
                // Para informes tipo "corte"
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(loggro.fechareporte) = ? AND MONTH(loggro.fechareporte) = ?) 
                    OR 
                    (YEAR(loggro.fechareporte) = ? AND MONTH(loggro.fechareporte) = ?)
                )';
                 //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = 12;
                    $mesn = 'Corte'.$fechaInicio->month;
                }else{
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = $mesn;
                    $mesn = 'Corte'.$fechaInicio->month;
                }
            } else{
                // Para informes mensuales con comparación de diciembre
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(loggro.fechareporte) = ? AND MONTH(loggro.fechareporte) = ?) 
                    OR 
                    (YEAR(loggro.fechareporte) = ? AND MONTH(loggro.fechareporte) = 12)
                )';
                $bindings[] = $anio;
                $bindings[] = $mesn;
                $bindings[] = $anioAnterior;
            } 

            $queryWithConditions = sprintf($baseQuery->toSql(), $additionalConditions);

            $informeQuery = DB::table(DB::raw('('.$queryWithConditions.') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(COALESCE(total_mes, 0)) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);

            // Añadir condición para el centro de costo si está presente
            if ($centro_costo) {
                $informeQuery->whereRaw('SUBSTRING(loggro.cc_scc, 1, 4) = ?', [$centro_costo]);
            }

            // Ejecutar la consulta
            $informe = $informeQuery->get();

           
        }else if($siigo =='PYME'){
            $cuentaCompuesta = "REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '')";
             // Base de la consulta SQL
            $baseQuery = '
                      SELECT 
                          SUBSTRING('.$cuentaCompuesta.', 1, 3) AS substring_cuenta,
                          CASE
                              WHEN '.$cuentaCompuesta.' = "41" THEN "41"
                              WHEN '.$cuentaCompuesta.' = "6" THEN "6"
                              WHEN '.$cuentaCompuesta.' = "7" THEN "7"
                              WHEN '.$cuentaCompuesta.' = "4210" THEN "4210"
                              WHEN '.$cuentaCompuesta.' = "42" THEN "42"
                              WHEN '.$cuentaCompuesta.' = "51" THEN "51"
                              WHEN '.$cuentaCompuesta.' = "52" THEN "52"
                              WHEN '.$cuentaCompuesta.' = "5305" THEN "5305"
                              WHEN '.$cuentaCompuesta.' = "53" THEN "53"
                              WHEN '.$cuentaCompuesta.' = "5405" THEN "54"
                              ELSE "Otros"
                          END AS cuenta,
                          CASE
                              WHEN '.$cuentaCompuesta.' = "41" THEN "Ingresos de actividades ordinarias"
                              WHEN '.$cuentaCompuesta.' = "6" THEN "Costos de venta"
                              WHEN '.$cuentaCompuesta.' = "7" THEN "Costos de venta"
                              WHEN '.$cuentaCompuesta.' = "4210" THEN "Ingresos financieros"
                              WHEN '.$cuentaCompuesta.' = "42" THEN "Otros ingresos"
                              WHEN '.$cuentaCompuesta.' = "51" THEN "Gastos de administración"
                              WHEN '.$cuentaCompuesta.' = "52" THEN "Gastos de ventas"
                              WHEN '.$cuentaCompuesta.' = "5305" THEN "Gastos financieros"
                              WHEN '.$cuentaCompuesta.' = "53" THEN "Otros gastos"
                              WHEN '.$cuentaCompuesta.' = "54" THEN "Gastos impuesto de renta y cree"
                              ELSE "Otros"
                          END AS descripcionct,
                          %s AS total_mes,
                          YEAR(fechareporte) AS year
                      FROM 
                          clientes 
                      WHERE 
                          Nit = ?
                          AND (
                              SUBSTRING('.$cuentaCompuesta.', 1, 1) IN ("6","7") OR
                              SUBSTRING('.$cuentaCompuesta.', 1, 2) IN ("41","42", "51", "52", "53", "54") OR
                              SUBSTRING('.$cuentaCompuesta.', 1, 4) IN ("4210", "5315", "6135","5305")
                          )
                          AND (
                              (YEAR(fechareporte) = ?)
                              OR (YEAR(fechareporte) = ? )
                          )
                      %s
                      GROUP BY 
                          SUBSTRING('.$cuentaCompuesta.', 1, 3), 
                          YEAR(fechareporte),
                          cuenta,
                          descripcionct
                  ';

            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1) {
                $operacionTotalMes = 'ROUND(
                          CASE 
                              WHEN SUBSTRING('.$cuentaCompuesta.', 1, 1) = "1" THEN SUM(COALESCE(debitos, 0) - COALESCE(creditos, 0))
                              WHEN SUBSTRING('.$cuentaCompuesta.', 1, 1) = "2" THEN SUM(COALESCE(creditos, 0) - COALESCE(debitos, 0))
                              WHEN SUBSTRING('.$cuentaCompuesta.', 1, 1) = "3" THEN SUM(COALESCE(creditos, 0) - COALESCE(debitos, 0))
                              WHEN SUBSTRING('.$cuentaCompuesta.', 1, 1) = "4" THEN SUM(COALESCE(creditos, 0) - COALESCE(debitos, 0))
                              WHEN SUBSTRING('.$cuentaCompuesta.', 1, 4) = "4175" THEN SUM(COALESCE(debitos, 0) - COALESCE(creditos, 0))
                              ELSE SUM(COALESCE(debitos, 0) - COALESCE(creditos, 0)) 
                          END, 2)';
            } else {
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(fechareporte) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(nuevo_saldo,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING('.$cuentaCompuesta.',1,1) IN ("5","6","7")
                                                    THEN IFNULL(debitos,0) - IFNULL(creditos,0)
                                                ELSE
                                                    IFNULL(creditos,0) - IFNULL(debitos,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(nuevo_saldo,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(fechareporte) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI nuevo_saldo = 0 → asumir cierre contable
                                        WHEN IFNULL(nuevo_saldo,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING('.$cuentaCompuesta.',1,1) IN ("5","6","7")
                                                    THEN IFNULL(debitos,0) - IFNULL(creditos,0)
                                                ELSE
                                                    IFNULL(creditos,0) - IFNULL(debitos,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(nuevo_saldo,0)
                                    END

                            END
                        )
                    ,2)';
            }

            if ($tipoinforme == 2) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(fechareporte) = ? AND MONTH(fechareporte) = ?) 
                    OR 
                    (YEAR(fechareporte) = ? AND MONTH(fechareporte) = ?)
                )';
                //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings = [$nit, $anioActual, $anioAnterior, $anioActual, $mesn, $anioAnterior, 12];
                }else{
                    $bindings = [$nit, $anioActual, $anioAnterior, $anioActual, $mesn, $anioAnterior, $mesn];
                }
                
            } elseif ($tipoinforme == 1) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (MONTH(fechareporte) = ? OR MONTH(fechareporte) = ?)';
                $bindings = [$nit, $anioActual, $anioAnterior, $mesn, 12];
            } else {
                $additionalConditions = '';
                $bindings = [$nit, $anioActual, $anioAnterior];
            }

            $queryWithConditions = sprintf($baseQuery, $operacionTotalMes, $additionalConditions);

            $informeQuery = DB::table(DB::raw('(' . $queryWithConditions . ') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(total_mes) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);
            $informe = $informeQuery->get();
        } else if ($siigo == 'NUBE') {
            // Base de la consulta SQL
            $baseQuery = '
                      SELECT 
                          SUBSTRING(codigo_cuenta_contable_ga, 1, 3) AS substring_cuenta,
                          CASE
                              WHEN codigo_cuenta_contable_ga = "41" THEN "41"
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) IN ("6","7") THEN 6
                              WHEN codigo_cuenta_contable_ga = "4210" THEN "4210"
                              WHEN codigo_cuenta_contable_ga = "42" THEN "42"
                              WHEN codigo_cuenta_contable_ga = "51" THEN "51"
                              WHEN codigo_cuenta_contable_ga = "52" THEN "52"
                              WHEN codigo_cuenta_contable_ga = "5305" THEN "5305"
                              WHEN codigo_cuenta_contable_ga = "53" THEN "53"
                              WHEN codigo_cuenta_contable_ga = "5405" THEN "54"
                              ELSE "Otros"
                          END AS cuenta,
                          CASE
                              WHEN codigo_cuenta_contable_ga = "41" THEN "Ingresos de actividades ordinarias"
                              WHEN codigo_cuenta_contable_ga = "6" THEN "Costos de venta"
                              WHEN codigo_cuenta_contable_ga = "7" THEN "Costos de venta"
                              WHEN codigo_cuenta_contable_ga = "4210" THEN "Ingresos financieros"
                              WHEN codigo_cuenta_contable_ga = "42" THEN "Otros ingresos"
                              WHEN codigo_cuenta_contable_ga = "51" THEN "Gastos de administración"
                              WHEN codigo_cuenta_contable_ga = "52" THEN "Gastos de ventas"
                              WHEN codigo_cuenta_contable_ga = "5305" THEN "Gastos financieros"
                              WHEN codigo_cuenta_contable_ga = "53" THEN "Otros gastos"
                              WHEN codigo_cuenta_contable_ga = "54" THEN "Gastos impuesto de renta y cree"
                              ELSE "Otros"
                          END AS descripcionct,
                          %s AS total_mes,
                          YEAR(fechareporte_ga) AS year
                      FROM 
                          clientes as clientesmovimientos
                      WHERE 
                          Nit = ?
                          AND (
                              SUBSTRING(codigo_cuenta_contable_ga, 1, 1) IN ("6","7") OR
                              SUBSTRING(codigo_cuenta_contable_ga, 1, 2) IN ("41","42", "51", "52", "53", "54") OR
                              SUBSTRING(codigo_cuenta_contable_ga, 1, 4) IN ("4210", "5315", "6135","5305")
                          )
                          AND (
                              (YEAR(fechareporte_ga) = ?)
                              OR (YEAR(fechareporte_ga) = ? )
                          )
                      %s
                      GROUP BY 
                          SUBSTRING(codigo_cuenta_contable_ga, 1, 3), 
                          YEAR(fechareporte_ga),
                          cuenta,
                          descripcionct
                  ';

            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1) {
                $operacionTotalMes = 'ROUND(
                          CASE 
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) = "1" THEN SUM(COALESCE(movimiento_debito_ga, 0) - COALESCE(movimiento_credito_ga, 0))
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) = "2" THEN SUM(COALESCE(movimiento_credito_ga, 0) - COALESCE(movimiento_debito_ga, 0))
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) = "3" THEN SUM(COALESCE(movimiento_credito_ga, 0) - COALESCE(movimiento_debito_ga, 0))
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 1) = "4" THEN SUM(COALESCE(movimiento_credito_ga, 0) - COALESCE(movimiento_debito_ga, 0))
                              WHEN SUBSTRING(codigo_cuenta_contable_ga, 1, 4) = "4175" THEN SUM(COALESCE(movimiento_debito_ga, 0) - COALESCE(movimiento_credito_ga, 0))
                              ELSE SUM(COALESCE(movimiento_debito_ga, 0) - COALESCE(movimiento_credito_ga, 0)) 
                          END, 2)';
            } else {
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(fechareporte_ga) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(saldo_final_ga,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(movimiento_debito_ga,0) - IFNULL(movimiento_credito_ga,0)
                                                ELSE
                                                    IFNULL(movimiento_credito_ga,0) - IFNULL(movimiento_debito_ga,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(saldo_final_ga,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(fechareporte_ga) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(saldo_final_ga,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(movimiento_debito_ga,0) - IFNULL(movimiento_credito_ga,0)
                                                ELSE
                                                    IFNULL(movimiento_credito_ga,0) - IFNULL(movimiento_debito_ga,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(saldo_final_ga,0)
                                    END

                            END
                        )
                    ,2)';
            }

            if ($tipoinforme == 2) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ?) 
                    OR 
                    (YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) = ?)
                )';
                //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings = [$nit, $anioActual, $anioAnterior, $anioActual, $mesn, $anioAnterior, 12];
                }else{
                    $bindings = [$nit, $anioActual, $anioAnterior, $anioActual, $mesn, $anioAnterior, $mesn];
                }
                
            } else {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (MONTH(fechareporte_ga) = ? OR MONTH(fechareporte_ga) = ?)';
                $bindings = [$nit, $anioActual, $anioAnterior, $mesn, 12];
            }

            $queryWithConditions = sprintf($baseQuery, $operacionTotalMes, $additionalConditions);

            $informeQuery = DB::table(DB::raw('(' . $queryWithConditions . ') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(total_mes) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);
            $informe = $informeQuery->get();
        }else if($siigo == 'BEGRANDA'){
            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1 ) {
                $operacionTotalMes = 'ROUND(
                    CASE 
                        WHEN SUBSTRING(begranda.cuenta, 1, 1) = "1" THEN SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0))
                        WHEN SUBSTRING(begranda.cuenta, 1, 1) = "2" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                        WHEN SUBSTRING(begranda.cuenta, 1, 1) = "3" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                        WHEN SUBSTRING(begranda.cuenta, 1, 1) = "4" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                        WHEN SUBSTRING(begranda.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0))
                        ELSE SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0)) 
                    END, 2)';
            } else {
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(begranda.fechareporte) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(begranda.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(begranda.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(begranda.debitos,0) - IFNULL(begranda.creditos,0)
                                                ELSE
                                                    IFNULL(begranda.creditos,0) - IFNULL(begranda.debitos,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(begranda.saldo_final,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(begranda.fechareporte) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(begranda.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(begranda.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(begranda.debitos,0) - IFNULL(begranda.creditos,0)
                                                ELSE
                                                    IFNULL(begranda.creditos,0) - IFNULL(begranda.debitos,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(begranda.saldo_final,0)
                                    END

                            END
                        )
                    ,2)';
            }

            $baseQuery = DB::table(DB::raw("(SELECT 
                            CASE
                                WHEN begranda.cuenta = '41' THEN '41'
                                WHEN begranda.cuenta = '6' THEN '6'
                                WHEN begranda.cuenta = '7' THEN '7'
                                WHEN begranda.cuenta = '4210' THEN '4210'
                                WHEN begranda.cuenta = '42' THEN '42'
                                WHEN begranda.cuenta = '51' THEN '51'
                                WHEN begranda.cuenta = '52' THEN '52'
                                WHEN begranda.cuenta = '5305' THEN '5305'
                                WHEN begranda.cuenta = '53' THEN '53'
                                WHEN begranda.cuenta = '5405' THEN '54'
                                ELSE begranda.cuenta
                            END AS cuenta,
                            CASE
                                WHEN begranda.cuenta = '41' THEN 'Ingresos de actividades ordinarias'
                                WHEN begranda.cuenta = '6' THEN 'Costos de venta'
                                WHEN begranda.cuenta = '7' THEN 'Costos de venta'
                                WHEN begranda.cuenta = '4210' THEN 'Ingresos financieros'
                                WHEN begranda.cuenta = '42' THEN 'Otros ingresos'
                                WHEN begranda.cuenta = '51' THEN 'Gastos de administración'
                                WHEN begranda.cuenta = '52' THEN 'Gastos de ventas'
                                WHEN begranda.cuenta = '5305' THEN 'Gastos financieros'
                                WHEN begranda.cuenta = '53' THEN 'Otros gastos'
                                WHEN begranda.cuenta = '54' THEN 'Gastos impuesto de renta y cree'
                                ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE begranda.cuenta = oi.agrupador_cuenta)
                            END AS descripcionct,
                            $operacionTotalMes AS total_mes,
                            YEAR(begranda.fechareporte) AS year
                            FROM 
                                begranda 
                            WHERE 
                                begranda.Nit = ?
                                AND (
                                    SUBSTRING(begranda.cuenta, 1, 2) IN ('41', '42', '51', '52', '53', '54')
                                    OR SUBSTRING(begranda.cuenta, 1, 1) IN ('4','6', '7')
                                    OR SUBSTRING(begranda.cuenta, 1, 4) IN ('4210', '5315','5305')
                                )
                                AND (
                                    (YEAR(begranda.fechareporte) = ?)
                                    OR (YEAR(begranda.fechareporte) = ?)
                                )
                                %s
                            GROUP BY 
                                cuenta, descripcionct, year
                            ) AS subquery"));

            $bindings = [$nit, $anio, $anioAnterior]; 

            if ($tipoinforme == 2) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(begranda.fechareporte) = ? AND MONTH(begranda.fechareporte) = ?) 
                    OR 
                    (YEAR(begranda.fechareporte) = ? AND MONTH(begranda.fechareporte) = ?)
                )';
                 //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = 12;
                    $mesn = 'Corte'.$fechaInicio->month;
                }else{
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = $mesn;
                    $mesn = 'Corte'.$fechaInicio->month;
                }
            } else {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(begranda.fechareporte) = ? AND MONTH(begranda.fechareporte) = ?) 
                    OR 
                    (YEAR(begranda.fechareporte) = ? AND MONTH(begranda.fechareporte) = 12)
                )';
                $bindings[] = $anio;
                $bindings[] = $mesn;
                $bindings[] = $anioAnterior;


            } 

            $queryWithConditions = sprintf($baseQuery->toSql(), $additionalConditions);

            $informeQuery = DB::table(DB::raw('('.$queryWithConditions.') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(COALESCE(total_mes, 0)) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);

            // Ejecutar la consulta
            $informe = $informeQuery->get();

        }else {
            // Definir la operación de total_mes según el tipo de informe
            if ($tipoinforme == 1) {
                $operacionTotalMes = 'ROUND(
                    CASE 
                        WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "1" THEN SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0))
                        WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "5" THEN SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0))
                        WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) IN ("6", "7") THEN SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0))
                        ELSE SUM(IFNULL(informesgenericos.creditos, 0) - IFNULL(informesgenericos.debitos, 0))
                    END, 2)';
            } else {
                $operacionTotalMes = 'ROUND(
                        SUM(
                            CASE

                                -- 🔴 AÑO ANTERIOR (SIEMPRE CIERRE)
                                WHEN YEAR(informesgenericos.fechareporte) = '.$anioAnterior.' THEN
                                    CASE
                                     -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(informesgenericos.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(informesgenericos.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(informesgenericos.debitos,0) - IFNULL(informesgenericos.creditos,0)
                                                ELSE
                                                    IFNULL(informesgenericos.creditos,0) - IFNULL(informesgenericos.debitos,0)
                                            END
                                         -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(informesgenericos.saldo_final,0)
                                    END

                                -- 🟢 AÑO ACTUAL
                                WHEN YEAR(informesgenericos.fechareporte) = '.$anio.' THEN
                                    CASE
                                        -- 👉 SI saldo_final = 0 → asumir cierre contable
                                        WHEN IFNULL(informesgenericos.saldo_final,0) = 0 THEN
                                            CASE
                                                WHEN SUBSTRING(informesgenericos.cuenta,1,1) IN ("5","6","7")
                                                    THEN IFNULL(informesgenericos.debitos,0) - IFNULL(informesgenericos.creditos,0)
                                                ELSE
                                                    IFNULL(informesgenericos.creditos,0) - IFNULL(informesgenericos.debitos,0)
                                            END
                                        -- 👉 SI NO → usar saldo normal
                                        ELSE
                                            IFNULL(informesgenericos.saldo_final,0)
                                    END

                            END
                        )
                    ,2)';
            }

            // Aquí construimos la baseQuery
            $baseQuery = DB::table(DB::raw("(SELECT 
                CASE
                    WHEN informesgenericos.cuenta IN ('41') THEN '41'
                    WHEN informesgenericos.cuenta IN ('6') THEN '6'
                    WHEN informesgenericos.cuenta IN ('7') THEN '7'
                    WHEN informesgenericos.cuenta IN ('45', '4210') THEN '4210'
                    WHEN informesgenericos.cuenta IN ('42') THEN '42'
                    WHEN informesgenericos.cuenta IN ('51') THEN '51'
                    WHEN informesgenericos.cuenta IN ('52') THEN '52'
                    WHEN informesgenericos.cuenta IN ('55', '5305') THEN '5305'
                    WHEN informesgenericos.cuenta IN ('53') THEN '53'
                    WHEN informesgenericos.cuenta IN ('5405') THEN '54'
                    ELSE informesgenericos.cuenta
                END AS cuenta,
                CASE
                    WHEN informesgenericos.cuenta = '41' THEN 'Ingresos de actividades ordinarias'
                    WHEN informesgenericos.cuenta IN ('6', '7') THEN 'Costos de venta'
                    WHEN informesgenericos.cuenta IN ('45', '4210') THEN 'Ingresos financieros'
                    WHEN informesgenericos.cuenta = '42' THEN 'Otros ingresos'
                    WHEN informesgenericos.cuenta = '51' THEN 'Gastos de administración'
                    WHEN informesgenericos.cuenta = '52' THEN 'Gastos de ventas'
                    WHEN informesgenericos.cuenta IN ('55', '5305') THEN 'Gastos financieros'
                    WHEN informesgenericos.cuenta = '53' THEN 'Otros gastos'
                    WHEN informesgenericos.cuenta = '54' THEN 'Gastos impuesto de renta y cree'
                    ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE informesgenericos.cuenta = oi.agrupador_cuenta)
                END AS descripcionct,
                $operacionTotalMes AS total_mes,
                YEAR(informesgenericos.fechareporte) AS year
                FROM informesgenericos
                WHERE informesgenericos.Nit = ?
                AND (
                    SUBSTRING(informesgenericos.cuenta, 1, 2) IN ('41', '42', '51', '52', '53', '54', '45', '55')
                    OR SUBSTRING(informesgenericos.cuenta, 1, 1) IN ('6', '7')
                    OR SUBSTRING(informesgenericos.cuenta, 1, 4) IN ('5305', '4210')
                )
                AND (
                    (YEAR(informesgenericos.fechareporte) = ?)
                    OR (YEAR(informesgenericos.fechareporte) = ?)
                )
                %s
                GROUP BY cuenta, descripcionct, year
            ) as subquery"));

            // Ahora agregamos los bindings
            $bindings = [$nit, $anio, $anioAnterior];

            // Condiciones adicionales según tipo de informe
            if ($tipoinforme == 2) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (
                    (YEAR(informesgenericos.fechareporte) = ? AND MONTH(informesgenericos.fechareporte) = ?) 
                    OR (YEAR(informesgenericos.fechareporte) = ? AND MONTH(informesgenericos.fechareporte) = ?)
                )';
                 //esto es para que cuando el informe se llame desde el estado de situacion financiera, se pueda tomar el mes de diciembre del año anterior
                if($valorparautilidad == 1){
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = 12;
                    $mesn = 'Corte'.$fechaInicio->month;
                }else{
                    $bindings[] = $anio;
                    $bindings[] = $mesn;
                    $bindings[] = $anioAnterior;
                    $bindings[] = $mesn;
                    $mesn = 'Corte'.$fechaInicio->month;
                }

            } elseif ($tipoinforme == 1 || $tipoinforme == 3) {
                $mesn = $fechaInicio->month;
                $additionalConditions = 'AND (MONTH(informesgenericos.fechareporte) = ? OR MONTH(informesgenericos.fechareporte) = ?)';
                $bindings[] = $mesn;
                if ($tipoinformepdf != null) {
                    $bindings[] = 12;
                } else {
                    $bindings[] = $mesn;
                }
            } else {
                $additionalConditions = '';
            }

            // Unimos la consulta con las condiciones
            $queryWithConditions = sprintf($baseQuery->toSql(), $additionalConditions);

            // Finalmente ejecutamos la consulta
            $informeQuery = DB::table(DB::raw('(' . $queryWithConditions . ') as subquery'))
                ->selectRaw('cuenta, descripcionct, year, SUM(COALESCE(total_mes, 0)) AS total_mes')
                ->groupBy('cuenta', 'descripcionct', 'year')
                ->orderBy('cuenta', 'asc')
                ->setBindings($bindings);

            // Ejecutar y obtener el informe
            $informe = $informeQuery->get();
            
        }
        // Define las cuentas esperadas con sus descripciones
        $cuentasEsperadas = [
            '41' => 'Ingresos de actividades ordinarias',
            '6_7' => 'Costos de venta',
            '4210' => 'Ingresos financieros',
            '42' => 'Otros ingresos',
            '51' => 'Gastos de administración',
            '52' => 'Gastos de ventas',
            '5305' => 'Gastos financieros',
            '53' => 'Otros gastos',
            '54' => 'Gastos impuesto de renta y cree',
            // Agrega más cuentas si es necesario
        ];
        $informeData = [];
        foreach ($cuentasEsperadas as $cuenta => $descripcion) {
            $informeData[$descripcion] = [
                'descripcionct' => $descripcion,
                $anio => 0.00,
                $anioAnterior => 0.00,
                'total_mes' => 0.00,
                'var%' => 0.00,
                'variacion$' => 0.00
            ];
        }
       
        // Rellena el array con los datos obtenidos de la consulta
        foreach ($informe as $row) {
            $descripcionct = $row->descripcionct;
            $year = $row->year;
            $total_mes = $row->total_mes;
            // Aquí mapeamos correctamente las cuentas con las descripciones definidas en $cuentasEsperadas
            if ($descripcionct == 'Gastos financieros' && isset($cuentasEsperadas['5305'])) {
                $descripcionct = $cuentasEsperadas['5305']; // Asignamos la descripción correcta: 'Costos financieros'
            }
            if($descripcionct == 'Costos de venta' && isset($cuentasEsperadas['6_7'])){
                $descripcionct = $cuentasEsperadas['6_7'];
            }
            // Verificamos si la descripción de la cuenta existe en el array de cuentas esperadas
            if (isset($informeData[$descripcionct])) {
                $informeData[$descripcionct][$year] += $total_mes;
                $informeData[$descripcionct]['total_mes'] += $total_mes;
            }
        }  
        // Ahora realizamos las restas necesarias
        $otrosIngresos = 'Otros ingresos';
        $ingresosFinancieros = 'Ingresos financieros';
        $otrosGastos = 'Otros gastos';
        $gastosFinancieros = 'Gastos financieros';

        foreach ($informeData as $descripcion => &$datos) {
            foreach ([$anio, $anioAnterior] as $year) {
                if (isset($datos[$year])) {
                    if($siigo != 'WIMAX'){
                         // Restar "Ingresos financieros" de "Otros ingresos"
                        if ($descripcion == $otrosIngresos && isset($informeData[$ingresosFinancieros][$year])) {
                            $datos[$year] -= $informeData[$ingresosFinancieros][$year];
                        }
                        // Restar "Gastos financieros" de "Otros gastos"
                        if ($descripcion == $otrosGastos && isset($informeData[$gastosFinancieros][$year])) {
                            $datos[$year] -= $informeData[$gastosFinancieros][$year];
                        }
                    }
                }
            }

            // Ajustar el total_mes después de la resta
            if ($descripcion == $otrosIngresos) {
                $datos['total_mes'] = $datos[$anio] + $datos[$anioAnterior];
            }
            if ($descripcion == $otrosGastos) {
                $datos['total_mes'] = $datos[$anio] + $datos[$anioAnterior];
            }
        }

        unset($datos); // Para evitar referencias accidentales

        // Cálculos de var% y variacion$ para todas las cuentas
        foreach ($informeData as $descripcionct => $data) {
            if (isset($informeData[$descripcionct][$anio]) && isset($informeData[$descripcionct][$anioAnterior])) {
                $anio1 = $informeData[$descripcionct][$anio];
                $anio2 = $informeData[$descripcionct][$anioAnterior];

                if ($anio2 != 0) {
                    $informeData[$descripcionct]['var%'] = ($anio1 / $anio2) - 1;
                } else {
                    $informeData[$descripcionct]['var%'] = 0; // Evita división por cero
                }

                $informeData[$descripcionct]['variacion$'] = $anio1 - $anio2;
            }
        }

        $informeData['Utilidad Bruta'] = [
            'descripcionct' => 'Utilidad Bruta',
            $anio => abs($informeData['Ingresos de actividades ordinarias'][$anio]) - abs($informeData['Costos de venta'][$anio]),
            $anioAnterior => abs($informeData['Ingresos de actividades ordinarias'][$anioAnterior]) - abs($informeData['Costos de venta'][$anioAnterior]),
            'total_mes' => 0.00,
            'var%' => 0.00,
            'variacion$' => 0.00
        ];

         // Calcular var% y variacion$ para utilidad bruta
         $utilidadBrutaAnio1 = $informeData['Utilidad Bruta'][$anio];
         $utilidadBrutaAnio2 = $informeData['Utilidad Bruta'][$anioAnterior];
         if ($utilidadBrutaAnio2 != 0) {
             $informeData['Utilidad Bruta']['var%'] = ($utilidadBrutaAnio1 / $utilidadBrutaAnio2) - 1;
         } else {
             $informeData['Utilidad Bruta']['var%'] = 0; // Evita división por cero
         }
         $gastosadministacion = $nit == '9013214757' ? abs($informeData['Gastos de administración'][$anio]) : $informeData['Gastos de administración'][$anio];
         $informeData['Utilidad Bruta']['variacion$'] = $utilidadBrutaAnio1 - $utilidadBrutaAnio2;
             // Crear Utilidad (Pérdida) operativa
            $informeData['Utilidad (Pérdida) operativa'] = [
                'descripcionct' => 'Utilidad (Pérdida) operativa',
                $anio => $informeData['Utilidad Bruta'][$anio]
                    - $gastosadministacion
                    - $informeData['Gastos de ventas'][$anio],
                $anioAnterior => $informeData['Utilidad Bruta'][$anioAnterior]
                    - $informeData['Gastos de administración'][$anioAnterior]
                    - $informeData['Gastos de ventas'][$anioAnterior],
                'total_mes' => 0.00,
                'var%' => 0.00,
                'variacion$' => 0.00
            ];
 
             // Calcular var% y variacion$ para Utilidad (Pérdida) operativa
             $utilidadOperativaAnio1 = $informeData['Utilidad (Pérdida) operativa'][$anio];
             $utilidadOperativaAnio2 = $informeData['Utilidad (Pérdida) operativa'][$anioAnterior];
             if ($utilidadOperativaAnio2 != 0) {
                 $informeData['Utilidad (Pérdida) operativa']['var%'] = ($utilidadOperativaAnio1 / $utilidadOperativaAnio2) - 1;
             } else {
                 $informeData['Utilidad (Pérdida) operativa']['var%'] = 0;
             }
 
            $informeData['Utilidad (Pérdida) operativa']['variacion$'] = $utilidadOperativaAnio1 - $utilidadOperativaAnio2;

            $otrosingresos = $nit == '901246963' ? $informeData['Otros ingresos'][$anio]*-1 : abs($informeData['Otros ingresos'][$anio]); 
            //  dd($informeData['Utilidad (Pérdida) operativa'][$anio] ,$informeData['Otros ingresos'][$anio] ,$informeData['Ingresos financieros'][$anio] ,$informeData['Otros gastos'][$anio] ,$informeData['Gastos financieros'][$anio]);
             // Crear (Pérdida) Utilidad antes de impuestos de renta
             $informeData['Utilidad (Pérdida) antes de impuestos de renta'] = [
                'descripcionct' => 'Utilidad (Pérdida) antes de impuestos de renta',
                $anio => $informeData['Utilidad (Pérdida) operativa'][$anio] + $otrosingresos + abs($informeData['Ingresos financieros'][$anio]) - $informeData['Otros gastos'][$anio] - $informeData['Gastos financieros'][$anio],
                $anioAnterior => $informeData['Utilidad (Pérdida) operativa'][$anioAnterior] + abs($informeData['Otros ingresos'][$anioAnterior]) + abs($informeData['Ingresos financieros'][$anioAnterior]) - $informeData['Otros gastos'][$anioAnterior] - $informeData['Gastos financieros'][$anioAnterior],
                'total_mes' => 0.00,
                'var%' => 0.00,
                'variacion$' => 0.00
            ];
 
             // Calcular var% y variacion$ para (Pérdida) Utilidad antes de impuestos de renta
             $utilidadAntesImpuestosAnio1 = $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio];
             $utilidadAntesImpuestosAnio2 = $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior];
             if ($utilidadAntesImpuestosAnio2 != 0) {
                 $informeData['Utilidad (Pérdida) antes de impuestos de renta']['var%'] = ($utilidadAntesImpuestosAnio1 / $utilidadAntesImpuestosAnio2) - 1;
             } else {
                 $informeData['Utilidad (Pérdida) antes de impuestos de renta']['var%'] = 0;
             }
             $informeData['Utilidad (Pérdida) antes de impuestos de renta']['variacion$'] = $utilidadAntesImpuestosAnio1 - $utilidadAntesImpuestosAnio2;
 
            // Crear (Perdida) Utilidad Neta del periodo
            $informeData['Utilidad (Perdida) Neta del periodo'] = [
                'descripcionct' => 'Utilidad (Perdida) Neta del periodo',
                $anio => isset($informeData['Gastos impuesto de renta y cree'][$anio]) && $informeData['Gastos impuesto de renta y cree'][$anio] != 0
                    ? $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio] - $informeData['Gastos impuesto de renta y cree'][$anio]
                    : $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anio],
                $anioAnterior => isset($informeData['Gastos impuesto de renta y cree'][$anioAnterior]) && $informeData['Gastos impuesto de renta y cree'][$anioAnterior] != 0
                    ? $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior] - $informeData['Gastos impuesto de renta y cree'][$anioAnterior]
                    : $informeData['Utilidad (Pérdida) antes de impuestos de renta'][$anioAnterior],
                'total_mes' => 0.00,
                'var%' => 0.00,
                'variacion$' => 0.00
            ];
 
             // Calcular var% y variacion$ para (Perdida) Utilidad Neta del periodo
             $utlidadnetaperidoAnio1 = $informeData['Utilidad (Perdida) Neta del periodo'][$anio];
             $utlidadnetaperidoAnio2 = $informeData['Utilidad (Perdida) Neta del periodo'][$anioAnterior];
             if ($utlidadnetaperidoAnio2 != 0) {
                 $informeData['Utilidad (Perdida) Neta del periodo']['var%'] = ($utlidadnetaperidoAnio1 / $utlidadnetaperidoAnio2) - 1;
             } else {
                 $informeData['Utilidad (Perdida) Neta del periodo']['var%'] = 0;
             }
             $informeData['Utilidad (Perdida) Neta del periodo']['variacion$'] = $utlidadnetaperidoAnio1 - $utlidadnetaperidoAnio2;
             return  ['informeData' => $informeData, 'anio' => $anio, 'anioAnterior' => $anioAnterior,'mes' =>$mesn];
    }
}