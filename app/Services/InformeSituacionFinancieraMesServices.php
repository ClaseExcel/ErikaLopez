<?php

namespace App\Services;
use App\Models\CentroCosto;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeSituacionFinancieraMesServices
{
    public function ejecutar($fecha,$nit,$siigo,$centro_costo,$tipoinforme,$tipoinformeresultados)
    {
        $fechaInicio = Carbon::parse($fecha)->firstOfMonth();
        $anio = $fechaInicio->year;
        $mes = $fechaInicio->month;
        $ordenArray = [
            "210517","1290","1355","1330","1730", "2305","2310", "2315", "2320", "2330",
             "2335", "2340", "2345", "2350", "2355","2360","2365", "2367", "2368", "2370", "2380", "2635",
             "2805", "2810", "2815", "2820", "2825", "2830", "2835", "2840", "2850", "2855", "2560", 
             "2865", "2870", "2895", "2640", "11", "12", "13", "14", "18", "17", "19", "15", "16", "21",
              "22", "24", "25", "26", "27", "31", "32", "33", "34", "35", "36", "37", "38", "2615", "2610"
       ];
    
        usort($ordenArray, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        if ($siigo == 'CONTAPYME') {
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
        
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                $caseCuenta,
                                YEAR(fechareporte) AS anio,
                                MONTH(fechareporte) AS mes,
                                SUM(IFNULL(nuevo_saldo, 0)) AS saldo
                            FROM contapyme_completo
                            WHERE Nit = ?
                            GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                            ) AS subquery"))
                            ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                            ->whereNotNull('cuenta')
                            ->where(function($query) use ($anio, $mes) {
                                $query->where('anio', '=', $anio)
                                      ->where('mes', '<=', $mes);
                            })
                            ->groupBy('cuenta', 'anio', 'mes')
                            ->orderBy('cuenta', 'asc')
                            ->orderBy('anio', 'asc')
                            ->orderBy('mes', 'asc')
                            ->setBindings([$nit, $anio, $mes]); // Usamos parámetros posicionales
        
            $informe = $informeQuery->get()->groupBy('cuenta');
        }else if ($siigo == 'LOGGRO') {
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
        
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                $caseCuenta,
                                YEAR(fechareporte) AS anio,
                                MONTH(fechareporte) AS mes,
                                SUM(IFNULL(saldo_final, 0)) AS saldo
                            FROM loggro
                            WHERE Nit = ?
                            GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                            ) AS subquery"))
                            ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                            ->whereNotNull('cuenta')
                            ->where(function($query) use ($anio, $mes) {
                                $query->where('anio', '=', $anio)
                                      ->where('mes', '<=', $mes);
                            })
                            ->groupBy('cuenta', 'anio', 'mes')
                            ->orderBy('cuenta', 'asc')
                            ->orderBy('anio', 'asc')
                            ->orderBy('mes', 'asc')
                            ->setBindings([$nit, $anio, $mes]); // Usamos parámetros posicionales
        
            $informe = $informeQuery->get()->groupBy('cuenta');
        } else if ($siigo == 'NUBE') {
            // Construir las condiciones del CASE dinámicamente basado en $ordenArray.
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                // Usar una igualdad exacta para todas las cuentas en $ordenArray
                $caseCuenta .= "WHEN codigo_cuenta_contable_ga = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
            
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                    $caseCuenta,
                                    YEAR(fechareporte_ga) AS anio,
                                    MONTH(fechareporte_ga) AS mes,
                                    SUM(IFNULL(saldo_final_ga, 0)) AS saldo
                                FROM clientes
                                WHERE Nit = ?
                                GROUP BY codigo_cuenta_contable_ga, YEAR(fechareporte_ga), MONTH(fechareporte_ga)
                                ) AS subquery"))
                                ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                                ->whereNotNull('cuenta')
                                ->where(function ($query) use ($anio, $mes) {
                                    $query->where('anio', '=', $anio)
                                          ->where('mes', '<=', $mes);
                                })
                                ->groupBy('cuenta', 'anio', 'mes')
                                ->orderBy('cuenta', 'asc')
                                ->orderBy('anio', 'asc')
                                ->orderBy('mes', 'asc')
                                ->setBindings([$nit, $anio, $mes]);
            
            $informe = $informeQuery->get()->groupBy('cuenta');
        } else if($siigo == 'PYME') {
            // Construir las condiciones del CASE dinámicamente basado en $ordenArray.
            $cuentaSQL = "REPLACE(CONCAT(
                TRIM(IFNULL(clientes.grupo, '')),
                TRIM(IFNULL(clientes.cuenta, '')),
                TRIM(IFNULL(clientes.subcuenta, ''))
            ), ' ', '')";
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                // Usar una igualdad exacta para todas las cuentas en $ordenArray
                $caseCuenta .= "WHEN  {$cuentaSQL} = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
            
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                    $caseCuenta,
                                    YEAR(fechareporte) AS anio,
                                    MONTH(fechareporte) AS mes,
                                    SUM(IFNULL(nuevo_saldo, 0)) AS saldo
                                FROM clientes
                                WHERE Nit = ?
                                GROUP BY grupo, YEAR(fechareporte), MONTH(fechareporte)
                                ) AS subquery"))
                                ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                                ->whereNotNull('cuenta')
                                ->where(function ($query) use ($anio, $mes) {
                                    $query->where('anio', '=', $anio)
                                          ->where('mes', '<=', $mes);
                                })
                                ->groupBy('cuenta', 'anio', 'mes')
                                ->orderBy('cuenta', 'asc')
                                ->orderBy('anio', 'asc')
                                ->orderBy('mes', 'asc')
                                ->setBindings([$nit, $anio, $mes]);
        
            $informe = $informeQuery->get()->groupBy('cuenta');
        }else if($siigo == 'BEGRANDA'){
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
        
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                $caseCuenta,
                                YEAR(fechareporte) AS anio,
                                MONTH(fechareporte) AS mes,
                                SUM(IFNULL(saldo_final, 0)) AS saldo
                            FROM begranda
                            WHERE Nit = ?
                            GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                            ) AS subquery"))
                            ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                            ->whereNotNull('cuenta')
                            ->where(function($query) use ($anio, $mes) {
                                $query->where('anio', '=', $anio)
                                      ->where('mes', '<=', $mes);
                            })
                            ->groupBy('cuenta', 'anio', 'mes')
                            ->orderBy('cuenta', 'asc')
                            ->orderBy('anio', 'asc')
                            ->orderBy('mes', 'asc')
                            ->setBindings([$nit, $anio, $mes]); // Usamos parámetros posicionales
        
            $informe = $informeQuery->get()->groupBy('cuenta');
        }else{
            $caseCuenta = "CASE ";
            foreach ($ordenArray as $codigo) {
                $caseCuenta .= "WHEN cuenta = '{$codigo}' THEN '{$codigo}' ";
            }
            $caseCuenta .= "ELSE NULL END AS cuenta";
        
            // Consultar los totales acumulados por cuenta y por mes
            $informeQuery = DB::table(DB::raw("(SELECT 
                                $caseCuenta,
                                YEAR(fechareporte) AS anio,
                                MONTH(fechareporte) AS mes,
                                SUM(IFNULL(saldo_final, 0)) AS saldo
                            FROM informesgenericos
                            WHERE Nit = ?
                            GROUP BY cuenta, YEAR(fechareporte), MONTH(fechareporte)
                            ) AS subquery"))
                            ->selectRaw('cuenta, anio, mes, SUM(COALESCE(saldo, 0)) AS saldo')
                            ->whereNotNull('cuenta')
                            ->where(function($query) use ($anio, $mes) {
                                $query->where('anio', '=', $anio)
                                      ->where('mes', '<=', $mes);
                            })
                            ->groupBy('cuenta', 'anio', 'mes')
                            ->orderBy('cuenta', 'asc')
                            ->orderBy('anio', 'asc')
                            ->orderBy('mes', 'asc')
                            ->setBindings([$nit, $anio, $mes]); // Usamos parámetros posicionales
        
            $informe = $informeQuery->get()->groupBy('cuenta');
        }
        
        $informeFinal = collect();
        foreach ($ordenArray as $cuenta) {
            // Inicializar datos de la cuenta
            $cuentaData = [
                'cuenta' => $cuenta
            ];

            // Verificar si la cuenta existe en el informe
            if (isset($informe[$cuenta])) {
                // Si existe, recorrer cada mes hasta el seleccionado
                for ($i = 1; $i <= $mes; $i++) {
                    $mesKey = "mes{$i}";
                    // Buscar los datos del mes específico
                    $mesData = $informe[$cuenta]->firstWhere('mes', $i);
                    
                    // Asignar el valor del mes o 0.00 si no se encuentra
                    $cuentaData[$mesKey] = $mesData ? number_format($mesData->saldo, 2) : '0.00';
                }
            } else {
                // Si la cuenta no existe, asignar 0.00 a todos los meses
                for ($i = 1; $i <= $mes; $i++) {
                    $mesKey = "mes{$i}";
                    $cuentaData[$mesKey] = '0.00';
                }
            }

            // Agregar los datos al informe final
            $informeFinal->push((object) $cuentaData);
        }
        // Ordenar el informe final por cuenta
        $informeFinal = $informeFinal->sortBy('cuenta')->values();
        $cuentasoperacionesMapeadas = [
            'Efectivo y equivalentes al efectivo' => '11',
            'Inversiones' => '12',
            'Cuentas comerciales y otras cuentas por cobrar' => '13-1330-1355',
            'Activos por impuestos corrientes' => '1355',
            'Inventarios' => '14',
            'Anticipos y avances' => '1330',
            'Otros activos' => '18+17-1730+19',
            'Inversiones no corriente' => '1290',
            'Propiedades planta y equipos' => '15',
            'Activos Intangibles' => '16',
            'Impuesto diferido' => '1730',
            'Obligaciones financieras' => '21-210517',
            'Cuentas comerciales y otras cuentas por pagar' => '22+2305+2310+2315+2320+2330+2335+2340+2345+2350',
            'Pasivos por Impuestos Corrientes' => '2365+2367+2368+24+2615',
            'Beneficios a empleados' => '2370+2380+25+2610',
            'Anticipos y avances recibidos' => '2805+2810+2815',
            'Otros Pasivos' => '2820+2825+2830+2835+2840+2850+2855+2560+2865+2870+2895',
            'Obligaciones Financieras' => '210517',
            'Cuentas por pagar comerciales y otras cuentas por pagar' =>'2355+2357+2360',
            'Pasivos Contingentes' => '2640+2635',
            'Pasivo por impuesto diferido' => '27',
            'Capital social' => '31',
            'Superavit de capital' => '32',
            'Reservas' => '33',
            'Ganancias acumuladas - Adopcion por primera vez' => '34',
            'Dividendos o participacion' => '35',
            'Utilidad y/o perdidas del ejercicio' => '36',
            'Utilidad y/o perdidas acumuladas' => '37',
            'Superavit de Capital Valorizacion' => '38',
        ];
        $resultadosAgrupados = [];

        // Iterar sobre cada operación que se debe agrupar
        foreach ($cuentasoperacionesMapeadas as $descripcion => $operacion) {
            // Inicializar el resultado para cada descripción
            $resultado = [
                'descripcion' => $descripcion,
            ];
        
            // Dividir la operación en cuentas y operadores
            $elementos = preg_split('/(\+|\-)/', $operacion, -1, PREG_SPLIT_DELIM_CAPTURE);
        
            // Inicializar los totales de cada mes
            $mesesTotales = [];
            $operador = '+'; // Operador inicial (por defecto suma)
        
            foreach ($elementos as $elemento) {
                $elemento = trim($elemento); // Eliminar espacios en blanco
                if ($elemento === '+' || $elemento === '-') {
                    // Actualizar el operador actual
                    $operador = $elemento;
                } else {
                    // Buscar la cuenta en el informe final
                    $cuentaData = collect($informeFinal)->firstWhere('cuenta', $elemento);
        
                    if ($cuentaData) {
                        // Sumar o restar los valores de cada mes según el operador actual
                        foreach ($cuentaData as $key => $value) {
                            if (strpos($key, 'mes') === 0) { // Comprobar si la clave empieza con "mes"
                                if (!isset($mesesTotales[$key])) {
                                    $mesesTotales[$key] = 0.00; // Inicializar si no existe
                                }
                                // Aplicar el operador: sumar o restar
                                if ($operador === '+') {
                                    $mesesTotales[$key] += floatval(str_replace(',', '', $value));
                                } elseif ($operador === '-') {
                                    $mesesTotales[$key] -= floatval(str_replace(',', '', $value));
                                }
                            }
                        }
                    }
                }
            }
        
            // Añadir los totales de cada mes al resultado
            $resultado = array_merge($resultado, $mesesTotales);
            // Agregar la primera cuenta como referencia
            $resultado['cuenta'] = trim(preg_split('/(\+|\-)/', $operacion, -1, PREG_SPLIT_NO_EMPTY)[0]);
        
            // Agregar el resultado final al array de resultados
            $resultadosAgrupados[] = $resultado;
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
            'Pasivo por impuesto diferido'

        ];
        $cuentasPatrimonio = [
            'Capital social',
            'Superavit de capital',
            'Reservas',
            'Utilidad y/o perdidas del ejercicio',
            'Utilidad y/o perdidas acumuladas',
            'Ganancias acumuladas - Adopcion por primera vez',
            'Dividendos o participacion',
            'Superavit de Capital Valorizacion'
        ];

        // Agregar los totales al array final
        // Obtener los resultados de la función
        ini_set('memory_limit', '1G');
        $totalesActivoCorriente = $this->calcularTotalesYVariacionesMensual($resultadosAgrupados, $cuentasActivoCorriente,'Total activo corriente',$mes);
        $totalesActivoNoCorriente = $this->calcularTotalesYVariacionesMensual($resultadosAgrupados, $cuentasActivoNoCorriente,'Total activo no corriente',$mes);
        $resultadoTotalActivo = $this->calcularTotalActivoMensual($totalesActivoCorriente, $totalesActivoNoCorriente, 'Total activo',$mes);
        $totalesPasivoCorriente = $this->calcularTotalesYVariacionesPatrimonioMensual($resultadosAgrupados, $cuentasPasivoCorriente,'Total pasivos corrientes',$mes);
        $totalesPasivoNoCorriente = $this->calcularTotalesYVariacionesPatrimonioMensual($resultadosAgrupados, $cuentasPasivoNoCorriente,'Total pasivos no corrientes',$mes);
        $resultadoTotalPasivo = $this->calcularTotalActivoMensual($totalesPasivoCorriente, $totalesPasivoNoCorriente, 'Total Pasivo',$mes);
        $totalpatrimonio  =  $this->calcularTotalesYVariacionesPatrimonioMensual($resultadosAgrupados, $cuentasPatrimonio,'Total patrimonio',$mes);
        $resultadoTotalPasivoypatrimonio = $this->calcularTotalActivoMensual($resultadoTotalPasivo, $totalpatrimonio, 'Total Pasivo & Patrimonio',$mes);
        
        $resultadosAgrupados[] = $totalesActivoCorriente;
        $resultadosAgrupados[] = $totalesActivoNoCorriente;
        $resultadosAgrupados[] = $resultadoTotalActivo;
        $resultadosAgrupados[] = $totalesPasivoCorriente;
        $resultadosAgrupados[] = $totalesPasivoNoCorriente;
        $resultadosAgrupados[] = $resultadoTotalPasivo;
        $resultadosAgrupados[] = $totalpatrimonio;
        $resultadosAgrupados[] = $resultadoTotalPasivoypatrimonio;
        return $resultadosAgrupados;
    }
    
 
    
    function calcularTotalesYVariacionesMensual($resultados, $cuentasParaSumar, $titulo, $numMeses) {
        // Inicializar un array para acumular totales de cada mes
        $totales = [];
        $valorCuenta37 = [0, 0]; // Para almacenar los valores de la cuenta 37 en dos años
    
        // Procesar las cuentas y acumular totales
        foreach ($resultados as $resultado) {
            // Verificar si la cuenta es 37
            if ($resultado['cuenta'] === "37") {
                // Guardar el valor de la cuenta 37 solo para los meses requeridos
                for ($i = 1; $i <= $numMeses; $i++) {
                    $valorCuenta37[$i - 1] = (int)str_replace(',', '', $resultado["mes$i"] ?? 0); // Usar 0 si no está definido
                }
            } else if (in_array($resultado['descripcion'], $cuentasParaSumar)) {
                // Para todas las demás cuentas, sumar los valores solo para los meses requeridos
                for ($i = 1; $i <= $numMeses; $i++) {
                    if (!isset($totales["mes$i"])) {
                        $totales["mes$i"] = 0;
                    }
                    $totales["mes$i"] += abs((int)str_replace(',', '', $resultado["mes$i"] ?? 0)); // Usar 0 si no está definido
                }
            }
        }
    
        // Operaciones para la cuenta 37
        for ($i = 0; $i < count($valorCuenta37); $i++) {
            if (isset($valorCuenta37[$i]) && $titulo === 'Total patrimonio') {
                if ($valorCuenta37[$i] >= 0) {
                    $totales["mes" . ($i + 1)] -= abs($valorCuenta37[$i]); // Restar si es positivo
                } else {
                    $totales["mes" . ($i + 1)] += abs($valorCuenta37[$i]); // Sumar si es negativo
                }
            }
        }
    
        // Calcular VAR% y VARIACION$ para cada mes
        $resultadosCalculados = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción de la categoría
        ];
    
        // Calcular variaciones mensuales solo hasta numMeses
        for ($i = 1; $i <= $numMeses; $i++) {
            $mesKey = "mes$i";
            $totalMesActual = $totales[$mesKey] ?? 0; // Asegúrate de usar 0 si no está definido
            $totalMesAnterior = $i > 1 ? $totales["mes" . ($i - 1)] ?? 0 : 0; // Usar 0 para el primer mes
    
            if ($totalMesAnterior != 0) {
                $varPorcentajes[$mesKey] = (($totalMesActual / $totalMesAnterior) - 1) * 100;
            } else {
                $varPorcentajes[$mesKey] = 0; // Evitar división por cero
            }
    
            $variacionesDolares[$mesKey] = $totalMesActual - $totalMesAnterior;
    
            // Formatear los resultados
            $resultadosCalculados[$mesKey] = number_format($totalMesActual, 0, '.', ',');
        }
    
        return $resultadosCalculados;
    }

    function calcularTotalActivoMensual($totalesActivoCorriente, $totalesActivoNoCorriente, $titulo, $numMeses) {
        // Inicializar los totales para cada mes
        $totalActivo = [];
        
        // Obtener totales para cada categoría y mes
        for ($i = 1; $i <= $numMeses; $i++) {
            $totalActivoCorriente = (int)str_replace(',', '', $totalesActivoCorriente["mes$i"] ?? 0); // Usar 0 si no está definido
            $totalActivoNoCorriente = (int)str_replace(',', '', $totalesActivoNoCorriente["mes$i"] ?? 0); // Usar 0 si no está definido
            
            // Calcular totales combinados
            $totalActivo[$i] = $totalActivoCorriente + $totalActivoNoCorriente;
        }
    
        // Calcular VAR% y VARIACION$ para cada mes
        $resultadosCalculados = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción para el total activo
        ];
    
        // Calcular variaciones mensuales
        for ($i = 1; $i <= $numMeses; $i++) {
            $mesKey = "mes$i";
            $totalMesActual = $totalActivo[$i] ?? 0; // Usar 0 si no está definido
            $totalMesAnterior = $i > 1 ? $totalActivo[$i - 1] ?? 0 : 0; // Usar 0 para el primer mes
    
            // Calcular VAR%
            if ($totalMesAnterior != 0) {
                $varPorcentaje = (($totalMesActual / $totalMesAnterior) - 1) * 100;
            } else {
                $varPorcentaje = 0; // Evitar división por cero
            }
    
            $variacionDolares = $totalMesActual - $totalMesAnterior;
    
            // Formatear los resultados
            $resultadosCalculados[$mesKey] = number_format($totalMesActual, 0, '.', ',');
        }
    
        return $resultadosCalculados;
    }

    function calcularTotalesYVariacionesPatrimonioMensual($resultados, $cuentasParaSumar, $titulo,$numMeses) {
        // Inicializar un array para acumular totales por mes
        $totalesPorMes = array_fill(1, 12, 0); // Suponiendo un máximo de 12 meses
    
        // Definir las cuentas y su mapeo para cada título
        $cuentasPorTitulo = [
            'Total pasivos corrientes' => [
                'Obligaciones financieras' => '21-210517',
                'Cuentas comerciales y otras cuentas por pagar' => '22+2305+2310+2315+2320+2330+2335+2340+2345+2350',
                'Pasivos por Impuestos Corrientes' => '2365+2367+2368+24+2615',
                'Beneficios a empleados' => '2370+2380+25+2610',
                'Anticipos y avances recibidos' => '2805+2810+2815',
                'Otros Pasivos' => '2820+2825+2830+2835+2840+2850+2855+2560+2865+2870+2895',
            ],
            'Total pasivos no corrientes' => [
                'Obligaciones Financieras' => '210517',
                'Cuentas por pagar comerciales y otras cuentas por pagar' => '2355+2357+2360',
                'Pasivos Contingentes' => '2640+2635',
                'Pasivo por impuesto diferido' => '27',
            ],
            'Total patrimonio' => [
                'Capital social' => '31',
                'Superavit de capital' => '32',
                'Reservas' => '33',
                'Ganancias acumuladas - Adopcion por primera vez' => '34',
                'Dividendos o participacion' => '35',
                'Utilidad y/o perdidas del ejercicio' => '36',
                'Utilidad y/o perdidas acumuladas' => '37',
                'Superavit de Capital Valorizacion' => '38',
            ],
        ];
    
        // Procesar las cuentas y acumular totales para las cuentas de cada título
        foreach ($resultados as $resultado) {
            // Verificar si la cuenta pertenece al título actual
            if (isset($cuentasPorTitulo[$titulo])) {
                // Manejar las cuentas que están en cuentasPorTitulo
                foreach ($cuentasPorTitulo[$titulo] as $descripcion => $cuenta) {
                    if ($resultado['descripcion'] === $descripcion) {
                        // Acumular los totales por mes
                        for ($i = 1; $i <= $numMeses; $i++) {
                            $valorMes = (int)str_replace(',', '', $resultado["mes$i"]);
                            // Sumar o restar según la lógica descrita
                            $totalesPorMes[$i] += $valorMes < 0 ? abs($valorMes) : -abs($valorMes);
                        }
                    }
                }
            } else {
                // Si el título no está definido en cuentasPorTitulo, sumar directamente
                for ($i = 1; $i <= $numMeses; $i++) {
                    $valorMes = (int)str_replace(',', '', $resultado["mes$i"]);
                    // Acumular totales directamente
                    $totalesPorMes[$i] += $valorMes < 0 ? abs($valorMes) : -abs($valorMes);
                }
            }
        }
    
        // Formatear los resultados
        $resultadosCalculados = [
            'cuenta' => '0',
            'descripcion' => $titulo, // Descripción de la categoría
        ];
    
        // Agregar los totales por mes al resultado final
        for ($i = 1; $i <= $numMeses; $i++) {
            $resultadosCalculados["mes$i"] = number_format($totalesPorMes[$i], 0, '.', ',');
        }
    
        return $resultadosCalculados;
    }
    
    

}
