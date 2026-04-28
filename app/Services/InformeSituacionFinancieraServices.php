<?php

namespace App\Services;
use App\Models\CentroCosto;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeSituacionFinancieraServices
{
    public function ejecutar($fecha,$nit,$siigo,$centro_costo,$tipoinforme,$tipoinformeresultados,$utilidad1 = null,$utilidad2 = null)
    {   
        $fechaInicio = Carbon::parse($fecha)->firstOfMonth();
        $anio = $fechaInicio->year;
        $anioAnterior = $anio - 1;
        if($tipoinformeresultados=="1"){
            $mesAño2=$fechaInicio->month;
        }else{
            $mesAño2 =12;
        }
        $mesn='0';
        $anioActual = $anio;
        $ordenArray = [
             "210517","1205","1290","1295","1245","1355","1330","1730", "2305","2310", "2315", "2320", "2330",
              "2335", "2340", "2345", "2350", "2355","2360","2365", "2367", "2368","2369", "2370", "2380", "2635",
              "2805", "2810", "2815", "2820", "2825", "2830", "2835", "2840", "2850", "2855", "2560", 
              "2865", "2870", "2895", "2640", "11", "12", "13", "14", "18", "17", "19", "15", "16", "21",
               "22",'23', "24", "25", "26", "27",'28','29', "31", "32", "33", "34", "35", "36", "37", "38", "2615", "2610"
        ];
        usort($ordenArray, function($a, $b) {
            // Compara las cadenas por su longitud, en orden descendente
            return strlen($b) - strlen($a);
        });
        if($siigo=='CONTAPYME'){
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";

            // Definir la condición del mes según $tipoinformeresultados
            $condicionMes = $tipoinforme == "1" ? "= ?" : "= ?";
            $informeQuery = DB::table(DB::raw("(SELECT 
                                    $caseCuenta,
                                    saldo_anterior,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(nuevo_saldo, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño1,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(nuevo_saldo, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño2
                                FROM contapyme_completo
                                WHERE Nit = ?
                                GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                                ) AS subquery"))
                ->selectRaw('cuenta, 
                        FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                        FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
                ->whereNotNull('cuenta')
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc');

            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinformeresultados == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $mesAño2, $nit];
            }

            $informeQuery->setBindings($bindings);

            // Ejecutar la consulta existente
            $informe = $informeQuery->get()->keyBy('cuenta');
        }else if($siigo=='LOGGRO'){
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
            
            // Definir la condición del mes según $tipoinformeresultados
            $condicionMes = $tipoinforme == "1" ? "= ?" : "= ?";
            
            $informeQuery = DB::table(DB::raw("(SELECT 
                                    $caseCuenta,
                                    saldo_final as saldo_anterior,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(saldo_final, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño1,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(saldo_final, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño2
                                FROM loggro
                                WHERE Nit = ?
                                GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                                ) AS subquery"))
                ->selectRaw('cuenta, 
                        FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                        FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
                ->whereNotNull('cuenta')
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc');
            
            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinformeresultados == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $mesAño2, $nit];
            }
            
            $informeQuery->setBindings($bindings);
            
            // Ejecutar la consulta existente
            $informe = $informeQuery->get()->keyBy('cuenta');
        }else if($siigo == 'NUBE'){
          // Construir las condiciones del CASE dinámicamente basado en $ordenArray.
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                // Usar una igualdad exacta para todas las cuentas en $ordenArray
                $caseCuenta .= "WHEN codigo_cuenta_contable_ga = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";

            // Definir la condición del mes según $tipoinformeresultados
            $condicionMes = $tipoinforme == "1" ? "= ?" : "= ?";

            $informeQuery = DB::table(DB::raw("(SELECT 
                                $caseCuenta,
                                saldo_inicial_ga,
                                ROUND(
                                    CASE 
                                        WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) $condicionMes THEN 
                                                    SUM(IFNULL(saldo_final_ga, 0)) -- Tomamos el saldo_final directamente
                                        ELSE 0
                                    END, 2
                                ) AS totalaño1,
                                ROUND(
                                    CASE 
                                        WHEN YEAR(fechareporte_ga) = ? AND MONTH(fechareporte_ga) $condicionMes THEN 
                                                    SUM(IFNULL(saldo_final_ga, 0)) -- Tomamos el saldo_final directamente
                                        ELSE 0
                                    END, 2
                                ) AS totalaño2
                            FROM clientes
                            WHERE Nit = ?
                            GROUP BY codigo_cuenta_contable_ga, YEAR(fechareporte_ga), MONTH(fechareporte_ga)
                            ) AS subquery"))
                ->selectRaw('cuenta, 
                        FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                        FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
                ->whereNotNull('cuenta') // Excluir cuentas que no están en la lista CASE
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc');

            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinformeresultados == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, 12, $nit];
            }

            $informeQuery->setBindings($bindings);

            // Ejecutar la consulta existente
            $informe = $informeQuery->get()->keyBy('cuenta');
        }else if($siigo == "PYME"){
           $cuentaSQL = "REPLACE(CONCAT(
                TRIM(IFNULL(clientes.grupo, '')),
                TRIM(IFNULL(clientes.cuenta, '')),
                TRIM(IFNULL(clientes.subcuenta, ''))
            ), ' ', '')";

            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN {$cuentaSQL} = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";

            // Reemplaza $condicionMes si solo filtras por diciembre (ejemplo mes 12)
            $condicionMes1 = '= ?'; // para el año 1
            $condicionMes2 = '= ?'; // para el año 2

            $informeQuery = DB::table(DB::raw("(SELECT 
                    $caseCuenta,
                    nuevo_saldo,
                    YEAR(fechareporte) AS anio,
                    MONTH(fechareporte) AS mes
                FROM clientes
                WHERE Nit = ?
            ) AS subquery"))
            ->selectRaw("
                cuenta,
                FORMAT(SUM(CASE WHEN anio = ? AND mes $condicionMes1 THEN nuevo_saldo ELSE 0 END), 2) AS totalaño1,
                FORMAT(SUM(CASE WHEN anio = ? AND mes $condicionMes2 THEN nuevo_saldo ELSE 0 END), 2) AS totalaño2
            ")
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->orderBy('cuenta', 'asc');


            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinformeresultados == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $mesAño2, $nit];
            }

            $informeQuery->setBindings($bindings);

            // Ejecutar la consulta existente
            $informe = $informeQuery->get()->keyBy('cuenta');
        }else if($siigo == "BEGRANDA"){
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
            // Definir la condición del mes según $tipoinformeresultados
            $condicionMes = $tipoinforme == "1" ? "= ?" : "= ?";
            $informeQuery = DB::table(DB::raw("(SELECT 
                                                $caseCuenta,
                                                saldo_final as saldo_anterior,
                                                ROUND(
                                                    CASE 
                                                        WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                            SUM(IFNULL(saldo_final, 0)) 
                                                        ELSE 0
                                                    END, 2
                                                ) AS totalaño1,
                                                ROUND(
                                                    CASE 
                                                        WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                            SUM(IFNULL(saldo_final, 0)) 
                                                        ELSE 0
                                                    END, 2
                                                ) AS totalaño2
                                            FROM begranda
                                            WHERE Nit = ?
                                            GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                                            ) AS subquery"))
                ->selectRaw('cuenta, 
                            FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                            FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
                ->whereNotNull('cuenta')
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc');

            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinforme == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $mesAño2, $nit];
            }

            $informeQuery->setBindings($bindings);

            // Ejecutar la consulta
            $informe = $informeQuery->get()->keyBy('cuenta');
        }else {
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";

            // Definir la condición del mes según $tipoinformeresultado
            
            $condicionMes = $tipoinforme == "1" ? "= ?" : "= ?";
            $informeQuery = DB::table(DB::raw("(SELECT 
                                    $caseCuenta,
                                    saldo_anterior,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(saldo_final, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño1,
                                    ROUND(
                                        CASE 
                                            WHEN YEAR(fechareporte) = ? AND MONTH(fechareporte) $condicionMes THEN 
                                                SUM(IFNULL(saldo_final, 0)) 
                                            ELSE 0
                                        END, 2
                                    ) AS totalaño2
                                FROM informesgenericos
                                WHERE Nit = ?
                                GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                                ) AS subquery"))
                ->selectRaw('cuenta, 
                        FORMAT(SUM(COALESCE(totalaño1, 0)), 2) AS totalaño1, 
                        FORMAT(SUM(COALESCE(totalaño2, 0)), 2) AS totalaño2')
                ->whereNotNull('cuenta')
                ->groupBy('cuenta')
                ->orderBy('cuenta', 'asc');

            // Ajustar los valores de bindings según la condición del mes
            if ($tipoinforme == 1) {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $fechaInicio->month, $nit];
            } else {
                $bindings = [$anio, $fechaInicio->month, $anioAnterior, $mesAño2, $nit];
            }

            $informeQuery->setBindings($bindings);

            // Ejecutar la consulta existente
            $informe = $informeQuery->get()->keyBy('cuenta');
        }
      
        // Inicializar array para almacenar el informe final
        $informeFinal = collect();
        // Recorrer el $ordenArray para asegurarse de que todas las cuentas estén presentes
        foreach ($ordenArray as $cuenta) {
            if (isset($informe[$cuenta])) {
                // Si la cuenta existe en los resultados de la consulta
                $registro = $informe[$cuenta];
        
                // Lógica específica para la cuenta "36"
                if ($cuenta === "36") {
                        // Guardamos valores originales ANTES de modificar
                        $original1 = (float)str_replace(',', '', $registro->totalaño1 ?? '0');
                        $original2 = (float)str_replace(',', '', $registro->totalaño2 ?? '0');

                        // Nuevos valores de utilidad
                        $valorUtilidad1 = (float)str_replace(',', '', $utilidad1 ?? '0');
                        $valorUtilidad2 = (float)str_replace(',', '', $utilidad2 ?? '0');

                        // Sobrescribimos el registro principal
                        $registro->totalaño1 = $valorUtilidad1;
                        $registro->totalaño2 = $valorUtilidad2;

                        $informeFinal->push($registro);

                        // Verificamos si los originales eran distintos de 0 y distintos a la utilidad
                        $hayDiferencia1 = $original1 != 0 && $original1 != $valorUtilidad1;
                        $hayDiferencia2 = $original2 != 0 && $original2 != $valorUtilidad2;

                        if ($hayDiferencia1 || $hayDiferencia2) {
                            $registroDiferencia = (object)[
                                'cuenta' => '362',
                                'totalaño1' => $hayDiferencia1 ? $original1 : 0,
                                'totalaño2' => $hayDiferencia2 ? $original2 : 0,
                            ];

                            $informeFinal->push($registroDiferencia);
                        }
                        continue;

                }
                $informeFinal->push($registro);
            } else {
                // Si la cuenta no existe, agregarla con valores en 0.00
                $nuevoRegistro = (object) [
                    'cuenta' => $cuenta,
                    'totalaño1' => '0',
                    'totalaño2' => '0',
                ];
        
                // Lógica específica para la cuenta "36"
                if ($cuenta === "36") {
                    $nuevoRegistro->totalaño1 = (float)str_replace(',', '', $utilidad1 ?? '0');
                    $nuevoRegistro->totalaño2 = (float)str_replace(',', '', $utilidad2 ?? '0');
                }
        
                $informeFinal->push($nuevoRegistro);
            }
        }
        // Convertir la colección en un array o lo que necesites para procesar el resultado final
        $informeFinal = $informeFinal->sortBy('cuenta')->values();
        $cuentasoperacionesMapeadas = [
            'Efectivo y equivalentes al efectivo' => '11',
            'Inversiones' => '12',
            'Cuentas comerciales y otras cuentas por cobrar' => '13-1330-1355',
            'Activos por impuestos corrientes' => '1355',
            'Inventarios' => '14',
            'Anticipos y avances' => '1330',
            'Otros activos' => '18+19',
            'Inversiones no corriente' => '1290',
            'Propiedades planta y equipos' => '15',
            'Activos Intangibles' => '16',
            'Impuesto diferido' => '17',
            'Obligaciones financieras' => '21-210517',
            'Cuentas comerciales y otras cuentas por pagar' => '22+2305+2310+2315+2320+2330+2335+2340+2345+2350',
            'Cuentas por pagar' => '23-2305-2310-2315-2320-2330-2335-2340-2345-2350-2365-2367-2368-2369-2370-2380-2355-2357-2360',
            'Pasivos por Impuestos Corrientes' => '2365+2367+2368+2369+24+2615',
            'Beneficios a empleados' => '2370+2380+25+2610',
            'Anticipos y avances recibidos' => '2805+2810+2815',
            'Otros Pasivos' => '2820+2825+2830+2835+2840+2850+2855+2560+2865+2870+2895',
            'Obligaciones Financieras' => '210517',
            'Cuentas por pagar comerciales y otras cuentas por pagar' =>'2355+2357+2360',
            'Pasivos Contingentes' => '26-2610-2615',
            'Pasivo por impuesto diferido' => '27',
            'Otros pasivos no corrientes' => '28-2820-2825-2830-2835-2840-2850-2855-2865-2870-2895-2805-2810-2815',
            'Bonos y papeles comerciales' => '29',
            'Capital social' => '31',
            'Superavit de capital' => '32',
            'Reservas' => '33',
            'Ganancias acumuladas - Adopcion por primera vez' => '34',
            'Dividendos o participacion' => '35',
            'Utilidad y/o perdidas del ejercicio' => '36',
            'Resultado del ejercicio' => '362',
            'Utilidad y/o perdidas acumuladas' => '37',
            'Superavit de Capital Valorizacion' => '38',
        ];
        // Inicializar el array final
        $resultadosAgrupados = [];
        foreach ($cuentasoperacionesMapeadas as $descripcion => $operacion) {
            $cuentas = preg_split('/(\+|\-)/', $operacion, -1, PREG_SPLIT_DELIM_CAPTURE);
            
            $totalAño1 = 0.00;
            $totalAño2 = 0.00;
            $operador = '+'; // Default to addition
            foreach ($cuentas as $cuenta) {
                if ($cuenta == '+' || $cuenta == '-') {
                    $operador = $cuenta;
                } else {
                    // Buscar valores para la cuenta
                    $cuentaData = $informeFinal->firstWhere('cuenta', $cuenta);
                    
                    // Si no se encuentra la cuenta, se asignan valores cero
                    if ($cuentaData) {
                        $valorAño1 = floatval(str_replace(',', '', $cuentaData->totalaño1));
                        $valorAño2 = floatval(str_replace(',', '', $cuentaData->totalaño2));
                    } else {
                        $valorAño1 = 0.00;
                        $valorAño2 = 0.00;
                    }
                    // Aplicar el operador actual
                    if ($operador == '+') {
                        $totalAño1 += $valorAño1;
                        $totalAño2 += $valorAño2;
                    } elseif ($operador == '-') {
                        $totalAño1 -= $valorAño1;
                        $totalAño2 -= $valorAño2;
                    }
                }
            }
            // Cálculo de la variación porcentual y en dólares
            $varPorcentaje = $totalAño2 != 0 ? (($totalAño1 / $totalAño2) - 1) * 100 : 0;
            $variacionDolares = $totalAño1 - $totalAño2;
            
            // Formatear y agregar el resultado al array final
            $resultadosAgrupados[] = [
                'cuenta' => $operacion,
                'descripcion' => $descripcion,
                'totalaño1' => number_format($totalAño1, 0, '.', ','),
                'totalaño2' => number_format($totalAño2, 0, '.', ','),
                'VAR' => number_format($varPorcentaje, 2, '.', ',') . '%',
                'VARIACION' => number_format($variacionDolares, 0, '.', ','),
            ];
        }
        ///calcular los totales 
        // Cuentas que se suman para obtener el Total activo corriente
        $cuentasActivoCorriente = [
            'Efectivo y equivalentes al efectivo',
            'Inversiones',
            'Cuentas comerciales y otras cuentas por cobrar',
            'Activos por impuestos corrientes',
            'Inventarios',
            'Anticipos y avances',
            'Otros activos'
        ];
        $cuentasActivoNoCorriente = [
            'Inversiones no corriente',
            'Propiedades planta y equipos',
            'Activos Intangibles',
            'Impuesto diferido'
        ];

        $cuentasPasivoCorriente = [
            'Obligaciones financieras',
            'Cuentas comerciales y otras cuentas por pagar',
            'Pasivos por Impuestos Corrientes',
            'Beneficios a empleados', 
            'Anticipos y avances recibidos', 
            'Otros Pasivos'

        ];
        $cuentasPasivoNoCorriente = [
            'Obligaciones Financieras',
            'Cuentas por pagar comerciales y otras cuentas por pagar',
            'Pasivos Contingentes',
            'Pasivo por impuesto diferido',
            'Otros pasivos no corrientes',
            'Bonos y papeles comerciales'

        ];
        $cuentasPatrimonio = [
            'Capital social',
            'Superavit de capital',
            'Reservas',
            'Utilidad y/o perdidas del ejercicio',
            'Resultado del ejercicio',
            'Utilidad y/o perdidas acumuladas',
            'Ganancias acumuladas - Adopcion por primera vez',
            'Dividendos o participacion',
            'Superavit de Capital Valorizacion'
        ];
        

        // Agregar los totales al array final
        // Obtener los resultados de la función
        $totalesActivoCorriente = $this->calcularTotalesYVariaciones($resultadosAgrupados, $cuentasActivoCorriente,'Total activo corriente');
        $totalesActivoNoCorriente = $this->calcularTotalesYVariaciones($resultadosAgrupados, $cuentasActivoNoCorriente,'Total activo no corriente');
        $resultadoTotalActivo = $this->calcularTotalActivo($totalesActivoCorriente, $totalesActivoNoCorriente, 'Total activo');
        $totalesPasivoCorriente = $this->calcularTotalesYVariacionespatrimonio($resultadosAgrupados, $cuentasPasivoCorriente,'Total pasivos corrientes',$siigo);
        $totalesPasivoNoCorriente = $this->calcularTotalesYVariacionespatrimonio($resultadosAgrupados, $cuentasPasivoNoCorriente,'Total pasivos no corrientes',$siigo);
        $resultadoTotalPasivo = $this->calcularTotalActivo($totalesPasivoCorriente, $totalesPasivoNoCorriente, 'Total Pasivo');
        $totalpatrimonio  =  $this->calcularTotalesYVariacionespatrimonio($resultadosAgrupados, $cuentasPatrimonio,'Total patrimonio',$siigo);
        $resultadoTotalPasivoypatrimonio = $this->calcularTotalActivo($resultadoTotalPasivo, $totalpatrimonio, 'Total Pasivo & Patrimonio');
        // Convertir totales a enteros para comparar
        $activoAño1 = (int)str_replace(',', '', $resultadoTotalActivo['totalaño1']);
        $activoAño2 = (int)str_replace(',', '', $resultadoTotalActivo['totalaño2']);

        $pasivoPatrimonioAño1 = (int)str_replace(',', '', $resultadoTotalPasivoypatrimonio['totalaño1']);
        $pasivoPatrimonioAño2 = (int)str_replace(',', '', $resultadoTotalPasivoypatrimonio['totalaño2']);

        // Si la diferencia es <= 5, ajustar
        if (abs($activoAño1 - $pasivoPatrimonioAño1) <= 5) {
            $resultadoTotalPasivoypatrimonio['totalaño1'] = number_format($activoAño1, 0, '.', ',');
        }
        if (abs($activoAño2 - $pasivoPatrimonioAño2) <= 5) {
            $resultadoTotalPasivoypatrimonio['totalaño2'] = number_format($activoAño2, 0, '.', ',');
        }
        $resultadosAgrupados[] = $totalesActivoCorriente;
        $resultadosAgrupados[] = $totalesActivoNoCorriente;
        $resultadosAgrupados[] = $resultadoTotalActivo;
        $resultadosAgrupados[] = $totalesPasivoCorriente;
        $resultadosAgrupados[] = $totalesPasivoNoCorriente;
        $resultadosAgrupados[] = $resultadoTotalPasivo;
        $resultadosAgrupados[] = $totalpatrimonio;
        $resultadosAgrupados[] = $resultadoTotalPasivoypatrimonio;
        // Retornar el array final con los resultados agrupados
        return $resultadosAgrupados;

    }

     function calcularTotalesYVariaciones($resultados, $cuentasParaSumar,$titulo) {
        // Inicializar variables para acumular totales
        $totalAño1 = 0;
        $totalAño2 = 0;
        $valorCuenta37Año1 = 0;
        $valorCuenta37Año2 = 0;
        // // Procesar las cuentas y acumular totales la cuenta 37 del patrimonio se suma si es negativo y se resta si es positivo
        foreach ($resultados as $resultado) {
            // Verificar si la cuenta es 37
            if ($resultado['cuenta'] === "37") {
                // Guardar el valor de la cuenta 37
                $valorCuenta37Año1 = (int)str_replace(',', '', $resultado['totalaño1']);
                $valorCuenta37Año2 = (int)str_replace(',', '', $resultado['totalaño2']);
            } else if($resultado['cuenta'] === "1355" && $titulo=='Total activo corriente') {
                // Para la cuenta 11, sumar los valores directamente
                $totalAño1 += (int)str_replace(',', '', $resultado['totalaño1']); // Usar el valor reales para las sumas
                $totalAño2 += (int)str_replace(',', '', $resultado['totalaño2']); // Usar el valor reales para las sumas

            }else if ($resultado['cuenta'] === "13-1330-1355" && $titulo=='Total activo corriente') {
               

                $valor1 = (float) str_replace(',', '', $resultado['totalaño1']);
                $valor2 = (float) str_replace(',', '', $resultado['totalaño2']);

                if ($valor1 < 0) {
                    $totalAño1 -= abs($valor1);  // restar en lugar de sumar
                } else {
                    $totalAño1 += $valor1;
                }

                if ($valor2 < 0) {
                    $totalAño2 -= abs($valor2);
                } else {
                    $totalAño2 += $valor2;
                }
            }else if (in_array($resultado['descripcion'], $cuentasParaSumar)) {
                // Para todas las demás cuentas, sumar los valores (si están en las cuentas para sumar)
                $totalAño1 += (int)str_replace(',', '', $resultado['totalaño1']); // Usar el valor absoluto para las sumas
                $totalAño2 += (int)str_replace(',', '', $resultado['totalaño2']); // Usar el valor absoluto para las sumas
            }
        }
        
        // Operaciones para la cuenta 37
        if (isset($valorCuenta37Año1) &&  $titulo=='Total patrimonio') {
            // Verificar si el valor de la cuenta 37 es positivo o negativo en Año1
            if ($valorCuenta37Año1 >= 0) {
                $totalAño1 -= abs($valorCuenta37Año1); // Restar si es positivo
            } else {
                $totalAño1 += abs($valorCuenta37Año1); // Sumar si es negativo
            }
        }
        
        if (isset($valorCuenta37Año2) && $titulo=='Total patrimonio') {
            // Verificar si el valor de la cuenta 37 es positivo o negativo en Año2
            if ($valorCuenta37Año2 >= 0) {
                $totalAño2 -= abs($valorCuenta37Año2); // Restar si es positivo
            } else {
                $totalAño2 += abs($valorCuenta37Año2); // Sumar si es negativo
            }
        }

        
        // Restar el valor de la cuenta 37 de los totales

        // Calcular VAR% y VARIACION$
        if ((int)str_replace(',', '', $resultado['totalaño2']) != 0) {
            $varPorcentaje = (((int)str_replace(',', '', $resultado['totalaño1']) / (int)str_replace(',', '', $resultado['totalaño2'])) - 1) * 100;
        } else {
            $varPorcentaje = 0; // Evitar división por cero
        }
    
        $variacionDolares = $totalAño1 - $totalAño2;
    
        // Formatear los resultados
        $resultadosCalculados = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción de la categoría
            'totalaño1' => number_format($totalAño1, 0, '.', ','),
            'totalaño2' => number_format($totalAño2, 0, '.', ','),
            'VAR' => number_format($varPorcentaje, 2, '.', ',') . '%',
            'VARIACION' => number_format($variacionDolares, 0, '.', ','),
        ];
    
        return $resultadosCalculados;
    }

    function calcularTotalesYVariacionespatrimonio($resultados, $cuentasParaSumar, $titulo,$siigo) {
        // Inicializar variables para acumular totales
        $totalAño1 = 0;
        $totalAño2 = 0;
    
        // Definir las cuentas y su mapeo para cada título
        $cuentasPorTitulo = [
            'Total pasivos corrientes' => [
                'Obligaciones financieras' => '21-210517',
                'Cuentas comerciales y otras cuentas por pagar' => '22+2305+2310+2315+2320+2330+2335+2340+2345+2350',
                'Pasivos por Impuestos Corrientes' => '2365+2367+2368+2369+24+2615',
                'Beneficios a empleados' => '2370+2380+25+2610',
                'Anticipos y avances recibidos' => '2805+2810+2815',
                'Otros Pasivos' => '23-2305-2310-2315-2320-2330-2335-2340-2345-2350-2365-2367-2368-2369-2370-2380-2355-2357-2360',
            ],
            'Total pasivos no corrientes' => [
                'Obligaciones Financieras' => '210517',
                'Cuentas por pagar comerciales y otras cuentas por pagar' => '2355+2357+2360',
                'Pasivos Contingentes' => '26-2610-2615',
                'Pasivo por impuesto diferido' => '27',
                'Otros pasivos no corrientes' => '28-2820-2825-2830-2835-2840-2850-2855-2865-2870-2895-2805-2810-2815',
                'Bonos y papeles comerciales' => '29',
            ],
            'Total patrimonio' => [
                'Capital social' => '31',
                'Superavit de capital' => '32',
                'Reservas' => '33',
                'Ganancias acumuladas - Adopcion por primera vez' => '34',
                'Dividendos o participacion' => '35',
                'Utilidad y/o perdidas del ejercicio' => '36',
                'Resultado del ejercicio' => '362',
                'Utilidad y/o perdidas acumuladas' => '37',
                'Superavit de Capital Valorizacion' => '38',
            ],
        ];
          $reglasSigno = [
            'Reservas' => 'invertido',
            'Utilidad y/o perdidas del ejercicio' => 'directo',
            'Capital social' => 'invertido',
            'Resultado del ejercicio' => 'negativo',
            'Utilidad y/o perdidas acumuladas' => 'negativo',
            'Superavit de capital' => 'negativo',
        ];

        $aplicarSigno = function ($valor, $tipo) {
            return match ($tipo) {
                'directo'   => $valor < 0 ? -abs($valor) :  abs($valor),
                'invertido' => $valor * -1,
                'negativo'  => $valor < 0 ?  abs($valor) : -abs($valor),
                default     => $valor < 0 ?  abs($valor) :  abs($valor),
            };
        };
       
          // Procesar las cuentas y acumular totales para las cuentas de cada título
        foreach ($resultados as $resultado) {
            // Verificar si la cuenta pertenece al título actual
            if (isset($cuentasPorTitulo[$titulo])) {
                // Manejar las cuentas que están en cuentasPorTitulo
                foreach ($cuentasPorTitulo[$titulo] as $descripcion => $cuenta) {
                    if ($resultado['descripcion'] === $descripcion) {
                        // Sumar o restar según la lógica descrita
                        $valorAño1 = (int)str_replace(',', '', $resultado['totalaño1']);
                        $valorAño2 = (int)str_replace(',', '', $resultado['totalaño2']);
                         // 🔸 Sección especial para títulos relacionados con pasivos
                            if (in_array($titulo, ['Total pasivos corrientes','Total pasivos no corrientes'])) {
                                // Acumular con el signo original (como viene en contabilidad)
                                    $totalAño1 += $valorAño1*-1;
                                    $totalAño2 += $valorAño2*-1;
                                continue; // saltar el resto del if
                            }
                          
                            if($descripcion=='Utilidad y/o perdidas del ejercicio'){
                                // Lógica de suma/resta basada en los valores positivos o negativos
                                $totalAño1 += $valorAño1 ;
                                $totalAño2 += $valorAño2 ;
                            }else if($descripcion=='Reservas'){
                                $tipo = $reglasSigno[$descripcion] ?? 'default';
                                $totalAño1 += $aplicarSigno($valorAño1, $tipo);
                                $totalAño2 += $aplicarSigno($valorAño2, $tipo);
                            }else{
                                $tipo = $reglasSigno[$descripcion] ?? 'invertido';
                                $totalAño1 += $aplicarSigno($valorAño1, $tipo);
                                $totalAño2 += $aplicarSigno($valorAño2, $tipo);
                            }
                       
                    }
                }
            } else {
                // Si el título no está definido en cuentasPorTitulo, sumar directamente
                $valorAño1 = (int)str_replace(',', '', $resultado['totalaño1']);
                $valorAño2 = (int)str_replace(',', '', $resultado['totalaño2']);
                
                // Acumular totales directamente
                $totalAño1 += $valorAño1 < 0 ? abs($valorAño1) : -abs($valorAño1);
                $totalAño2 += $valorAño2 < 0 ? abs($valorAño2) : -abs($valorAño2);
            }
        }

    
        // Calcular VAR% y VARIACION$
        if ($totalAño2 != 0) {
            $varPorcentaje = (($totalAño1 / $totalAño2) - 1) * 100;
        } else {
            $varPorcentaje = 0; // Evitar división por cero
        }
    
        $variacionDolares = $totalAño1 - $totalAño2;
    
        // Formatear los resultados
        $resultadosCalculados = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción de la categoría
            'totalaño1' => number_format($totalAño1, 0, '.', ','),
            'totalaño2' => number_format($totalAño2, 0, '.', ','),
            'VAR' => number_format($varPorcentaje, 2, '.', ',') . '%',
            'VARIACION' => number_format($variacionDolares, 0, '.', ','),
        ];
        return $resultadosCalculados;
    }

    function calcularTotalActivo($totalesActivoCorriente, $totalesActivoNoCorriente, $titulo) {
        // Obtener totales para cada categoría y año
        $totalActivoCorrienteAño1 = (int)str_replace(',', '', $totalesActivoCorriente['totalaño1']);
        $totalActivoCorrienteAño2 = (int)str_replace(',', '', $totalesActivoCorriente['totalaño2']);
        $totalActivoNoCorrienteAño1 = (int)str_replace(',', '', $totalesActivoNoCorriente['totalaño1']);
        $totalActivoNoCorrienteAño2 = (int)str_replace(',', '', $totalesActivoNoCorriente['totalaño2']);
    
        // Calcular totales combinados
        $totalActivoAño1 = $totalActivoCorrienteAño1 + $totalActivoNoCorrienteAño1;
        $totalActivoAño2 = $totalActivoCorrienteAño2 + $totalActivoNoCorrienteAño2;
    
        // Calcular VAR% y VARIACION$
        if ($totalActivoAño2 != 0) {
            $varPorcentaje = (($totalActivoAño1 / $totalActivoAño2) - 1) * 100;
        } else {
            $varPorcentaje = 0; // Evitar división por cero
        }
    
        $variacionDolares = $totalActivoAño1 - $totalActivoAño2;
    
        // Formatear los resultados
        $resultadoTotalActivo = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción para el total activo
            'totalaño1' => number_format($totalActivoAño1, 0, '.', ','),
            'totalaño2' => number_format($totalActivoAño2, 0, '.', ','),
            'VAR' => number_format($varPorcentaje, 2, '.', ',') . '%',
            'VARIACION' => number_format($variacionDolares, 0, '.', ','),
        ];
    
        return $resultadoTotalActivo;
    }

}