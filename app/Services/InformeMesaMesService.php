<?php

namespace App\Services;

use App\Models\CentroCosto;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeMesaMesService
{

    public function ejecutar($nit, $fechaInicio, $siigo, $datos2, $centro_costo = null)
    {
        // 🔴 PEGA AQUÍ TODO el código actual
       

        // Definir el orden personalizado de los meses
        $ordenMeses = [
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

        // Crear un array para almacenar los resultados por mes
        $informePorMes = [];
        $fechaInicio = Carbon::parse($fechaInicio)->firstOfMonth();
        $anio = $fechaInicio->year;
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->whereNotIn('orden', [4250])->get();
        $orden = $cuentas->pluck('orden')->first();
        $ordenArray = array_unique(json_decode($orden, true));
        $ordenArray = json_decode($orden, true); // Decodifica el JSON a un array
        if ($siigo == 'PYME' || $siigo == 'NUBE') {
            // Limitar los valores a un máximo de 4 dígitos
            $ordenArray = array_map(function ($codigo) {
                return substr($codigo, 0, 4); // Tomar los primeros 4 caracteres
            }, $ordenArray);
        }
        $descripciones = DB::table('ordeninformes')
            ->select('agrupador_cuenta', 'nombre')
            ->whereIn('agrupador_cuenta', $ordenArray)
            ->get();
        $meses = [];
        if ($centro_costo) {
            $centrocostov = CentroCosto::select('codigo')->where('id', $centro_costo)
                ->whereNot('estado', [0])->first();
            $centro_costo = $centrocostov ? substr($centrocostov->codigo, 0, 4) : '';
        }
        $mesfinal = $fechaInicio->month;
        // Itera sobre los últimos 12 meses
        for ($i = 0; $i < $mesfinal; $i++) {
            set_time_limit(600);
            $mes = Carbon::parse($fechaInicio)->locale('es_ES');
            $mes = strtoupper($mes->isoFormat('MMMM'));
            $meses[] = $mes;
            if ($siigo == 'NUBE') {
                $ordenCuentas = [
                    6,
                    7,
                    41,
                    53,
                    54,
                    42,
                    4175,
                    5105,
                    5110,
                    5115,
                    5120,
                    5125,
                    5135,
                    5140,
                    5145,
                    5150,
                    5195,
                    5130,
                    5155,
                    5160,
                    5165,
                    5199,
                    4210
                    // Agrega aquí otras cuentas según sea necesario
                ];
                $informeQuery = DB::table('clientes')
                    ->selectRaw('CASE 
                                    WHEN clientes.codigo_cuenta_contable_ga = "4175" THEN "Devoluciones en ventas"
                                    WHEN clientes.codigo_cuenta_contable_ga= "41" THEN "VENTAS"
                                    WHEN clientes.codigo_cuenta_contable_ga = "4210" THEN "Ingresos financieros"
                                    WHEN clientes.codigo_cuenta_contable_ga = "42" THEN "Otros Ingresos"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5105" THEN "Gastos de Personal"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5110" THEN "Honorarios"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5115" THEN "Impuestos Indirectos"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5120" THEN "Arrendamientos"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5135" THEN "Servicios"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5140")  THEN "Gastos Legales"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5145") THEN "Mantto. Ed. Y Equipos"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5150" THEN "Adecuación e instalación"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5195" THEN "Diversos"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("6", "7") THEN "COSTO MERCANCIA VENDIDA"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5125" THEN "Contribuciones y Afiliaciones"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5130" THEN "Seguros"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5155" THEN "Gastos de viaje"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5305" THEN "Gastos financieros"
                                    WHEN clientes.codigo_cuenta_contable_ga = "53" THEN "Otros Egresos"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5160") THEN "Depreciación"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5165") THEN "Amortización"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5199") THEN "Deterioro"
                                    WHEN clientes.codigo_cuenta_contable_ga = "54" THEN "Impuesto de renta"
                                    WHEN clientes.codigo_cuenta_contable_ga = "52" THEN "Gastos de venta"
                                    ELSE "Sin Clasificar"
                                END AS descripcionct')
                    ->selectRaw('CASE 
                                    WHEN clientes.codigo_cuenta_contable_ga = "4175" THEN "4175"
                                    WHEN clientes.codigo_cuenta_contable_ga = "41"  THEN "41"
                                    WHEN clientes.codigo_cuenta_contable_ga = "4210" THEN "4210"
                                    WHEN clientes.codigo_cuenta_contable_ga = "42" THEN "42"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5105" THEN "5105"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5110" THEN "5110"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5115" THEN "5115"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5120" THEN "5120"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5135" THEN "5135"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5125" THEN "5125"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5140")  THEN "5140"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5145") THEN "5145"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5195" THEN "5195"
                                    WHEN clientes.codigo_cuenta_contable_ga = "6" THEN "6"
                                    WHEN clientes.codigo_cuenta_contable_ga = "7" THEN "7"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5130" THEN "5130"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5150" THEN "5150"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5155" THEN "5155"
                                    WHEN clientes.codigo_cuenta_contable_ga = "5305" THEN "5305"
                                    WHEN clientes.codigo_cuenta_contable_ga = "53" THEN "53"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5160") THEN "5160"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5165") THEN "5165"
                                    WHEN clientes.codigo_cuenta_contable_ga IN ("5199") THEN "5199"
                                    WHEN clientes.codigo_cuenta_contable_ga = "54" THEN "54"
                                    WHEN clientes.codigo_cuenta_contable_ga = "52"  THEN "52"
                                    ELSE "Sin Clasificar"
                                END AS cuenta')
                    ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(
                                    CASE 
                                    WHEN clientes.codigo_cuenta_contable_ga = "4175" THEN 
                                        SUM(IFNULL(clientes.movimiento_credito_ga, 0) - IFNULL(clientes.movimiento_debito_ga, 0))
                                    WHEN clientes.codigo_cuenta_contable_ga = "41" THEN 
                                        SUM(IFNULL(clientes.movimiento_credito_ga, 0) - IFNULL(clientes.movimiento_debito_ga, 0)) 
                                    WHEN SUBSTRING(clientes.codigo_cuenta_contable_ga, 1, 1) = "4" THEN 
                                        SUM(IFNULL(clientes.movimiento_credito_ga, 0) - IFNULL(clientes.movimiento_debito_ga, 0)) 
                                    ELSE 
                                        SUM(IFNULL(clientes.movimiento_debito_ga, 0) - IFNULL(clientes.movimiento_credito_ga, 0)) 
                                END, 2)) AS total_mes')
                    ->where('clientes.Nit', $nit)
                    ->whereYear('clientes.fechareporte_ga', $anio)
                    ->whereMonth('clientes.fechareporte_ga', $fechaInicio->month)
                    ->groupBy('descripcionct', 'cuenta');
                if ($centro_costo) {
                    $informeQuery->whereRaw('SUBSTRING(clientes.transacional_ga,1,4) = ?', $centro_costo);
                }

                $informe = $informeQuery->get();
            } else if ($siigo == 'PYME') {
                $ordenCuentas = [
                    6,
                    7,
                    41,
                    53,
                    54,
                    42,
                    4175,
                    5105,
                    5110,
                    5115,
                    5120,
                    5125,
                    5135,
                    5140,
                    5145,
                    5150,
                    5195,
                    5130,
                    5155,
                    5160,
                    5165,
                    5199,
                    4210
                    // Agrega aquí otras cuentas según sea necesario
                ];
                $cuentaCompuesta = "REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '')";

                $informeQuery = DB::table('clientes')
                    ->selectRaw('CASE 
                                    WHEN '.$cuentaCompuesta.' = "4175" THEN "Devoluciones en ventas"
                                    WHEN '.$cuentaCompuesta.' = "41" THEN "Ingresos"
                                    WHEN '.$cuentaCompuesta.' = "4210" THEN "Ingresos financieros"
                                    WHEN '.$cuentaCompuesta.' = "42" THEN "Otros Ingresos"
                                    WHEN '.$cuentaCompuesta.' = "5105" THEN "Gastos de Personal"
                                    WHEN '.$cuentaCompuesta.' = "5110" THEN "Honorarios"
                                    WHEN '.$cuentaCompuesta.' = "5115" THEN "Impuestos Indirectos"
                                    WHEN '.$cuentaCompuesta.' = "5120" THEN "Arrendamientos"
                                    WHEN '.$cuentaCompuesta.' = "5125" THEN "Contribuciones y Afiliaciones"
                                    WHEN '.$cuentaCompuesta.' = "5135" THEN "Servicios"
                                    WHEN '.$cuentaCompuesta.' IN ("5140")  THEN "Gastos Legales"
                                    WHEN '.$cuentaCompuesta.' IN ("5145") THEN "Mantto. Ed. Y Equipos"
                                    WHEN '.$cuentaCompuesta.' = "5150" THEN "Adecuación e instalación"
                                    WHEN '.$cuentaCompuesta.' = "5195" THEN "Diversos"
                                    WHEN '.$cuentaCompuesta.' IN ("6", "7") THEN "COSTO MERCANCIA VENDIDA"
                                    WHEN '.$cuentaCompuesta.' = "5130" THEN "Seguros"
                                    WHEN '.$cuentaCompuesta.' = "5155" THEN "Gastos de viaje"
                                    WHEN '.$cuentaCompuesta.' = "5305" THEN "Gastos financieros"
                                    WHEN '.$cuentaCompuesta.' = "53" THEN "Otros Egresos"
                                    WHEN '.$cuentaCompuesta.' IN ("5160") THEN "Depreciación"
                                    WHEN '.$cuentaCompuesta.' IN ("5165") THEN "Amortización"
                                    WHEN '.$cuentaCompuesta.' IN ("5199") THEN "Deterioro"
                                    WHEN '.$cuentaCompuesta.' = "54" THEN "Impuesto de renta"
                                    WHEN '.$cuentaCompuesta.' = "52" THEN "Gastos de ventas"
                                    ELSE "Sin Clasificar"
                                END AS descripcionct')
                    ->selectRaw('CASE 
                                    WHEN '.$cuentaCompuesta.' = "4175" THEN "4175"
                                    WHEN '.$cuentaCompuesta.' = "41" THEN "41"
                                    WHEN '.$cuentaCompuesta.' = "4210" THEN "4210"
                                    WHEN '.$cuentaCompuesta.' = "42" THEN "42"
                                    WHEN '.$cuentaCompuesta.' = "5105" THEN "5105"
                                    WHEN '.$cuentaCompuesta.' = "5110" THEN "5110"
                                    WHEN '.$cuentaCompuesta.' = "5115" THEN "5115"
                                    WHEN '.$cuentaCompuesta.' = "5120" THEN "5120"
                                    WHEN '.$cuentaCompuesta.' = "5135" THEN "5135"
                                    WHEN '.$cuentaCompuesta.' = "5125" THEN "5125"
                                    WHEN '.$cuentaCompuesta.' IN ("5140") THEN "5140"
                                    WHEN '.$cuentaCompuesta.' IN ("5145") THEN "5145"
                                    WHEN '.$cuentaCompuesta.' = "5150" THEN "5150"
                                    WHEN '.$cuentaCompuesta.' = "5195" THEN "5195"
                                    WHEN '.$cuentaCompuesta.' = "6" THEN "6"
                                    WHEN '.$cuentaCompuesta.' = "7" THEN "7"
                                    WHEN '.$cuentaCompuesta.' = "5130" THEN "5130"
                                    WHEN '.$cuentaCompuesta.' = "5155" THEN "5155"
                                    WHEN '.$cuentaCompuesta.' = "5305" THEN "5305"
                                    WHEN '.$cuentaCompuesta.' = "53" THEN "53"
                                    WHEN '.$cuentaCompuesta.' IN ("5160") THEN "5160"
                                    WHEN '.$cuentaCompuesta.' IN ("5165") THEN "5165"
                                    WHEN '.$cuentaCompuesta.' IN ("5199") THEN "5199"
                                    WHEN '.$cuentaCompuesta.' = "54" THEN "54"
                                    WHEN '.$cuentaCompuesta.' = "52" THEN "52"
                                    ELSE "Sin Clasificar"
                                END AS cuenta')
                    ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(
                        CASE 
                            WHEN '.$cuentaCompuesta.' = "4175" THEN 
                                SUM(IFNULL(clientes.creditos, 0) - IFNULL(clientes.debitos, 0))
                            WHEN '.$cuentaCompuesta.' = "41" THEN 
                                SUM(IFNULL(clientes.creditos, 0) - IFNULL(clientes.debitos, 0)) 
                            WHEN SUBSTRING('.$cuentaCompuesta.', 1, 1) = "4" THEN 
                                SUM(IFNULL(clientes.creditos, 0) - IFNULL(clientes.debitos, 0)) 
                            ELSE 
                                SUM(IFNULL(clientes.debitos, 0) - IFNULL(clientes.creditos, 0)) 
                        END, 2)) AS total_mes')
                    ->where('clientes.Nit', $nit)
                    ->whereYear('clientes.fechareporte', $anio)
                    ->whereMonth('clientes.fechareporte', $fechaInicio->month)
                    ->groupBy('descripcionct', 'cuenta');


                $informe = $informeQuery->get();
            } else if ($siigo == 'LOGGRO') {
                // Ordenar $ordenArray por longitud de cuenta de mayor a menor
                $ordenArray[] = '41';
                $ordenArray[] = '4175';
                $ordenArray[] = '5125';
                $ordenArray[] = '5150';
                $ordenArray[] = '54';
                // Solo agregar '7' si no está en el array
                if (!in_array('7', $ordenArray)) {
                    $ordenArray[] = '7';
                }
                // Ordenar $ordenArray por longitud de cuenta de mayor a menor
                usort($ordenArray, function ($a, $b) {
                    return strlen($b) - strlen($a);
                });
                $informeQuery = DB::table(DB::raw('(SELECT 
                                        CASE
                                            WHEN loggro.cuenta IN ("5310", "5313", "5315", "5395") THEN "53"
                                            WHEN loggro.cuenta  = "5140" THEN "5140"
                                            WHEN loggro.cuenta = "5125" THEN "5125"
                                            WHEN loggro.cuenta = "5145" THEN "5145"
                                            WHEN loggro.cuenta = "5150" THEN "5150"
                                            WHEN loggro.cuenta = "5160" THEN "5160"
                                            WHEN loggro.cuenta =  "5165" THEN "5165"
                                            WHEN loggro.cuenta = "5199" THEN "5199"
                                            WHEN loggro.cuenta = "4175" THEN "4175"
                                            WHEN loggro.cuenta = "41" THEN "41"
                                            WHEN loggro.cuenta = "54" THEN "54"
                                            ' . implode(' ', array_map(function ($cuenta) {
                    return 'WHEN loggro.cuenta = "' . $cuenta . '" THEN "' . $cuenta . '"';
                }, $ordenArray)) . '
                                            ELSE loggro.cuenta
                                        END AS cuenta,
                                        CASE
                                            WHEN loggro.cuenta = "4175" THEN "Devoluciones en venta"
                                            WHEN loggro.cuenta = "54" THEN "Impuesto de renta"
                                            ' . implode(' ', array_map(function ($cuenta) use ($descripciones) {
                    $descripcion = $descripciones->where('agrupador_cuenta', $cuenta)->first();
                    return 'WHEN loggro.cuenta = "' . $cuenta . '" THEN "' . ($descripcion ? $descripcion->nombre : '') . '"';
                }, $ordenArray)) . '
                                            ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE loggro.cuenta = oi.agrupador_cuenta)
                                        END AS descripcionct,
                                        ROUND(
                                            CASE
                                                WHEN SUBSTRING(loggro.cuenta, 1, 1) = "1" THEN SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0))
                                                WHEN SUBSTRING(loggro.cuenta, 1, 1) = "2" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                                                WHEN SUBSTRING(loggro.cuenta, 1, 1) = "3" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                                                WHEN SUBSTRING(loggro.cuenta, 1, 1) = "4" THEN SUM(IFNULL(loggro.creditos, 0) - IFNULL(loggro.debitos, 0))
                                                WHEN SUBSTRING(loggro.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0))
                                                -- Para todas las demás cuentas
                                                ELSE SUM(IFNULL(loggro.debitos, 0) - IFNULL(loggro.creditos, 0)) 
                                               
                                            END, 2
                                        ) AS total_mes
                                    FROM 
                                        loggro
                                    WHERE 
                                        loggro.Nit = ?
                                        AND (
                                            ' . implode(' OR ', array_map(function ($codigo) {
                    return 'loggro.cuenta = ?';
                }, $ordenArray)) . '
                                        )
                                        AND YEAR(loggro.fechareporte) = ?
                                        AND MONTH(loggro.fechareporte) = ?
                                    GROUP BY 
                                        cuenta, descripcionct
                                    ) AS subquery'))
                    ->selectRaw('cuenta, descripcionct, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                    ->groupBy('cuenta', 'descripcionct')
                    ->orderBy('cuenta', 'asc')
                    ->setBindings(array_merge([$nit], $ordenArray, [$anio, $fechaInicio->month]));
                // Añadir condición para el centro de costo si está presente
                if ($centro_costo) {
                    $informeQuery->whereRaw('SUBSTRING(loggro.cc_scc,1,4) = ?', [$centro_costo]);
                }
                // Ejecutar la consulta
                $informe = $informeQuery->get();
            } else if ($siigo == 'CONTAPYME') {
                $ordenArray[] = '41';
                $ordenArray[] = '4175';
                $ordenArray[] = '5125';
                $ordenArray[] = '5150';
                $ordenArray[] = '54';
                // Solo agregar '7' si no está en el array
                if (!in_array('7', $ordenArray)) {
                    $ordenArray[] = '7';
                }
                // Ordenar $ordenArray por longitud de cuenta de mayor a menor
                usort($ordenArray, function ($a, $b) {
                    return strlen($b) - strlen($a);
                });
                $informeQuery = DB::table(DB::raw('(SELECT 
                                    CASE
                                        -- Agrupamos cuentas específicas bajo la cuenta "53"
                                        WHEN contapyme.cuenta IN ("5310", "5313", "5315", "5395") THEN "53"
                                        WHEN contapyme.cuenta IN ("5140") THEN "5140"
                                        when contapyme.cuenta IN ("5125") THEN "5125"
                                        WHEN contapyme.cuenta IN ("5145") THEN "5145"
                                        WHEN contapyme.cuenta IN ("5150") THEN "5150"
                                        WHEN contapyme.cuenta IN ("5160") THEN "5160"
                                        WHEN contapyme.cuenta IN ("5165") THEN "5165"
                                        WHEN contapyme.cuenta IN ("5199") THEN "5199"
                                        WHEN contapyme.cuenta = "4175" THEN "4175"
                                        WHEN contapyme.cuenta ="41" THEN "41"
                                        WHEN contapyme.cuenta ="54" THEN "54"
                                        ELSE contapyme.cuenta
                                    END AS cuenta,
                                    CASE
                                        -- Descripción de las cuentas
                                        WHEN contapyme.cuenta = "4175" THEN "Devoluciones en venta"
                                        WHEN contapyme.cuenta = "54" THEN "Impuesto de renta"
                                        WHEN contapyme.cuenta = "5125" THEN "Contribuciones y Afiliaciones"
                                        WHEN contapyme.cuenta = "5150" THEN "Adecuación e instalación"
                                        ' . implode(' ', array_map(function ($cuenta) use ($descripciones) {
                    $descripcion = $descripciones->where('agrupador_cuenta', $cuenta)->first();
                    return 'WHEN contapyme.cuenta = "' . $cuenta . '" THEN "' . ($descripcion ? $descripcion->nombre : '') . '"';
                }, $ordenArray)) . '
                                        ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE contapyme.cuenta = oi.agrupador_cuenta)
                                    END AS descripcionct,
                                    ROUND(
                                        CASE 
                                            -- Lógica basada en prefijos
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "1" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "2" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "3" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 1) = "4" THEN SUM(IFNULL(contapyme.creditos, 0) - IFNULL(contapyme.debitos, 0))
                                            WHEN SUBSTRING(contapyme.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0))
                                            -- Para todas las demás cuentas
                                            ELSE SUM(IFNULL(contapyme.debitos, 0) - IFNULL(contapyme.creditos, 0)) 
                                        END, 2) AS total_mes
                                FROM 
                                    contapyme_completo as contapyme 
                                WHERE 
                                    contapyme.Nit = ? 
                                    AND (
                                        ' . implode(' OR ', array_map(function ($codigo) {
                    return 'contapyme.cuenta = ?';
                }, $ordenArray)) . '
                                    )
                                    AND YEAR(contapyme.fechareporte) = ? 
                                    AND MONTH(contapyme.fechareporte) = ?
                                GROUP BY 
                                    cuenta, descripcionct
                                ) AS subquery'))
                    ->selectRaw('cuenta, descripcionct, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                    ->groupBy('cuenta', 'descripcionct')
                    ->orderBy('cuenta', 'asc')
                    ->setBindings(array_merge([$nit], $ordenArray, [$anio, $fechaInicio->month]));

                // Ejecutar la consulta
                $informe = $informeQuery->get();
            } else if ($siigo == 'BEGRANDA') {
                $ordenArray[] = '41';
                $ordenArray[] = '4175';
                $ordenArray[] = '5125';
                $ordenArray[] = '5150';
                $ordenArray[] = '54';
                // Solo agregar '7' si no está en el array
                if (!in_array('7', $ordenArray)) {
                    $ordenArray[] = '7';
                }
                // Ordenar $ordenArray por longitud de cuenta de mayor a menor
                usort($ordenArray, function ($a, $b) {
                    return strlen($b) - strlen($a);
                });
                $informeQuery = DB::table(DB::raw('(SELECT 
                                    CASE
                                        -- Agrupamos cuentas específicas bajo la cuenta "53"
                                        WHEN begranda.cuenta IN ("5310", "5313", "5315", "5395") THEN "53"
                                        WHEN begranda.cuenta = "5140" THEN "5140"
                                        WHEN begranda.cuenta = "5125" THEN "5125"
                                        WHEN begranda.cuenta = "5145" THEN "5145"
                                        WHEN begranda.cuenta = "5150" THEN "5150"
                                        WHEN begranda.cuenta = "5160" THEN "5160"
                                        WHEN begranda.cuenta = "5165" THEN "5165"
                                        WHEN begranda.cuenta = "5199" THEN "5199"
                                        WHEN begranda.cuenta = "4175" THEN "4175"
                                        WHEN begranda.cuenta = "41" THEN "41"
                                        WHEN begranda.cuenta = "54" THEN "54"
                                        ELSE begranda.cuenta
                                    END AS cuenta,
                                    CASE
                                        -- Descripción de las cuentas
                                        WHEN begranda.cuenta = "4175" THEN "Devoluciones en venta"
                                        WHEN contapyme.cuenta = "54" THEN "Impuesto de renta"
                                        WHEN begranda.cuenta = "5125" THEN "Contribuciones y Afiliaciones"
                                        WHEN begranda.cuenta = "5150" THEN "Adecuación e instalación"
                                        ' . implode(' ', array_map(function ($cuenta) use ($descripciones) {
                    $descripcion = $descripciones->where('agrupador_cuenta', $cuenta)->first();
                    return 'WHEN begranda.cuenta = "' . $cuenta . '" THEN "' . ($descripcion ? $descripcion->nombre : '') . '"';
                }, $ordenArray)) . '
                                        ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE begranda.cuenta = oi.agrupador_cuenta)
                                    END AS descripcionct,
                                    ROUND(
                                        CASE 
                                            -- Lógica basada en prefijos
                                            WHEN SUBSTRING(begranda.cuenta, 1, 1) = "1" THEN SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0))
                                            WHEN SUBSTRING(begranda.cuenta, 1, 1) = "2" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                                            WHEN SUBSTRING(begranda.cuenta, 1, 1) = "3" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                                            WHEN SUBSTRING(begranda.cuenta, 1, 1) = "4" THEN SUM(IFNULL(begranda.creditos, 0) - IFNULL(begranda.debitos, 0))
                                            WHEN SUBSTRING(begranda.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0))
                                            -- Para todas las demás cuentas
                                            ELSE SUM(IFNULL(begranda.debitos, 0) - IFNULL(begranda.creditos, 0)) 
                                        END, 2) AS total_mes
                                FROM 
                                    begranda 
                                WHERE 
                                    begranda.Nit = ? 
                                    AND (
                                        ' . implode(' OR ', array_map(function ($codigo) {
                    return 'begranda.cuenta = ?';
                }, $ordenArray)) . '
                                    )
                                    AND YEAR(begranda.fechareporte) = ? 
                                    AND MONTH(begranda.fechareporte) = ?
                                GROUP BY 
                                    cuenta, descripcionct
                                ) AS subquery'))
                    ->selectRaw('cuenta, descripcionct, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                    ->groupBy('cuenta', 'descripcionct')
                    ->orderBy('cuenta', 'asc')
                    ->setBindings(array_merge([$nit], $ordenArray, [$anio, $fechaInicio->month]));

                // Añadir condición para el centro de costo si está presente
                if ($centro_costo) {
                    $informeQuery->whereRaw('SUBSTRING(begranda.cc_scc,1,4) = ?', [$centro_costo]);
                }

                // Ejecutar la consulta
                $informe = $informeQuery->get();
            } else {
                $ordenArray[] = '41';
                $ordenArray[] = '4175';
                $ordenArray[] = '5125';
                $ordenArray[] = '5150';
                $ordenArray[] = '54';
                $ordenArray[] = '5199';
                $ordenArray[] = '5165';
                // Solo agregar '7' si no está en el array
                if (!in_array('7', $ordenArray)) {
                    $ordenArray[] = '7';
                }
                // Ordenar $ordenArray por longitud de cuenta de mayor a menor
                usort($ordenArray, function ($a, $b) {
                    return strlen($b) - strlen($a);
                });
                $informeQuery = DB::table(DB::raw('(SELECT 
                                    CASE
                                        -- Agrupamos cuentas específicas bajo la cuenta "53"
                                        WHEN informesgenericos.cuenta IN ("5310", "5313", "5315", "5395") THEN "53"
                                        WHEN informesgenericos.cuenta = "5140"  THEN "5140"
                                        when informesgenericos.cuenta = "5125"  THEN "5125"
                                        WHEN informesgenericos.cuenta = "5145"  THEN "5145"
                                        WHEN informesgenericos.cuenta = "5150"  THEN "5150"
                                        WHEN informesgenericos.cuenta = "5160"  THEN "5160"
                                        WHEN informesgenericos.cuenta = "5165"  THEN "5165"
                                        WHEN informesgenericos.cuenta = "5199"  THEN "5199"
                                        WHEN informesgenericos.cuenta = "4175"  THEN "4175"
                                        WHEN informesgenericos.cuenta = "41"    THEN "41"
                                        WHEN informesgenericos.cuenta = "54"    THEN "54"
                                        ELSE informesgenericos.cuenta
                                    END AS cuenta,
                                    CASE
                                        -- Descripción de las cuentas
                                        WHEN informesgenericos.cuenta = "4175" THEN "Devoluciones en venta"
                                        WHEN informesgenericos.cuenta = "54" THEN "Impuesto de renta"
                                        WHEN informesgenericos.cuenta = "5125" THEN "Contribuciones y Afiliaciones"
                                        WHEN informesgenericos.cuenta = "5150" THEN "Adecuación e instalación"
                                        WHEN informesgenericos.cuenta = "5160" THEN "Depreciaciones"
                                        WHEN informesgenericos.cuenta = "5165" THEN "Amortizaciones"
                                        WHEN informesgenericos.cuenta = "5199" THEN "Provisiones"
                                        ' . implode(' ', array_map(function ($cuenta) use ($descripciones) {
                    $descripcion = $descripciones->where('agrupador_cuenta', $cuenta)->first();
                    return 'WHEN informesgenericos.cuenta = "' . $cuenta . '" THEN "' . ($descripcion ? $descripcion->nombre : '') . '"';
                }, $ordenArray)) . '
                                        ELSE (SELECT MAX(oi.nombre) FROM ordeninformes oi WHERE informesgenericos.cuenta = oi.agrupador_cuenta)
                                    END AS descripcionct,
                                    ROUND(
                                        CASE 
                                            -- Lógica basada en prefijos
                                            WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "1" THEN SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0))
                                            WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "2" THEN SUM(IFNULL(informesgenericos.creditos, 0) - IFNULL(informesgenericos.debitos, 0))
                                            WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "3" THEN SUM(IFNULL(informesgenericos.creditos, 0) - IFNULL(informesgenericos.debitos, 0))
                                            WHEN SUBSTRING(informesgenericos.cuenta, 1, 1) = "4" THEN SUM(IFNULL(informesgenericos.creditos, 0) - IFNULL(informesgenericos.debitos, 0))
                                            WHEN SUBSTRING(informesgenericos.cuenta, 1, 4) = "4175" THEN SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0))
                                            -- Para todas las demás cuentas
                                            ELSE SUM(IFNULL(informesgenericos.debitos, 0) - IFNULL(informesgenericos.creditos, 0)) 
                                        END, 2) AS total_mes
                                FROM 
                                    informesgenericos 
                                WHERE 
                                    informesgenericos.Nit = ? 
                                    AND (
                                        ' . implode(' OR ', array_map(function ($codigo) {
                    return 'informesgenericos.cuenta = ?';
                }, $ordenArray)) . '
                                    )
                                    AND YEAR(informesgenericos.fechareporte) = ? 
                                    AND MONTH(informesgenericos.fechareporte) = ?
                                GROUP BY 
                                    cuenta, descripcionct
                                ) AS subquery'))
                    ->selectRaw('cuenta, descripcionct, FORMAT(SUM(COALESCE(total_mes, 0)), 2) AS total_mes')
                    ->groupBy('cuenta', 'descripcionct')
                    ->orderBy('cuenta', 'asc')
                    ->setBindings(array_merge([$nit], $ordenArray, [$anio, $fechaInicio->month]));

                // Añadir condición para el centro de costo si está presente
                if ($centro_costo) {
                    $informeQuery->whereRaw('SUBSTRING(informesgenericos.cc_scc,1,4) = ?', [$centro_costo]);
                }

                // Ejecutar la consulta
                $informe = $informeQuery->get();
            }

            set_time_limit(600);
            // Convertimos el informe en un Collection
            $informeCollection = collect($informe);
            $cuentas = ['4210', '5305', '7', '41', '4175', '5125', '5150', '5165', '5199'];
            $totales = [];

            foreach ($cuentas as $cuenta) {
                $totales[$cuenta] = $informeCollection->where('cuenta', $cuenta)->sum(function ($item) {
                    $totalMes = data_get($item, 'total_mes', '0');
                    return $this->limpiarValor($totalMes) ? (float) str_replace(',', '', $totalMes) : 0;
                });
            }

            // Ahora puedes acceder a los valores de esta forma:
            $total4210 = $totales['4210'];
            $total5305 = $totales['5305'];
            $total7 = $totales['7'];
            $total41 = $totales['41'];
            $total4175 = $totales['4175'];
            $total5125 = $totales['5125'];
            $total5150 = $totales['5150'];
            $total5165 = $totales['5165'];
            $total5199 = $totales['5199'];

            // Agrupamos y ajustamos los resultados
            $informeAgrupado = $informeCollection->groupBy('descripcionct')->map(function ($group) use ($total4210, $total5305, $total41, $total4175, $total5150, $total5125, $total5165, $total5199) {
                $totalPorCuenta = $group->sum(function ($item) {
                    $totalMes = data_get($item, 'total_mes', '0');
                    return $this->limpiarValor($totalMes) ? (float) str_replace(',', '', $totalMes) : 0;
                });

                $cuenta = data_get($group->first(), 'cuenta', '');
                $descripcionct = data_get($group->first(), 'descripcionct', '');
                // Aplicamos los descuentos a las cuentas específicas
                if ($cuenta == '42') {
                    $totalPorCuenta -= $total4210;
                }

                if ($cuenta == '53') {
                    $totalPorCuenta -= $total5305;
                }

                if ($cuenta == '41') {
                    $totalPorCuenta = 0;
                    $totalPorCuenta = $total41 + abs($total4175); // Se suma el valor absoluto de la cuenta 4175
                }

                return [
                    'Cuenta' => $cuenta,
                    'descripcionct' => $descripcionct,
                    'total_mes' => number_format($totalPorCuenta, 2, ',', '.'),
                ];
            })->keyBy('descripcionct');
            // Verificamos si la cuenta 6 ya existe en el agrupado
            if ($informeAgrupado->contains('Cuenta', '6')) {

                // Si existe, sumarle el total de la cuenta 7
                $informeAgrupado = $informeAgrupado->map(function ($item) use ($total7) {

                    if ($item['Cuenta'] == '6' && $total7 != '0') {

                        $totalMes = floatval(str_replace(['.', ','], ['', '.'], $item['total_mes'])); // Convertir correctamente a número
                        $nuevoTotal = $totalMes + $total7;
                        $item['total_mes'] = number_format($nuevoTotal, 2, ',', '.');
                    }
                    return $item;
                });
            } else {
                // Si no existe la cuenta 6, agregarla con el total de la cuenta 7
                if ($total7 != 0) {
                    $informeAgrupado->put('Costos de venta', [
                        'Cuenta' => '6',
                        'descripcionct' => 'Costos de venta',
                        'total_mes' => number_format($total7, 2, ',', '.'),
                    ]);
                }
            }


            // === CUENTA 5140 depende de total5125 ===
            if ($informeAgrupado->contains('Cuenta', '5140')) {

                $informeAgrupado = $informeAgrupado->map(function ($item) use ($total5125) {

                    if ($item['Cuenta'] == '5140' && $total5125 != 0) {
                        $totalMes = floatval(str_replace(['.', ','], ['', '.'], $item['total_mes']));
                        $nuevoTotal = $totalMes + $total5125;
                        $item['total_mes'] = number_format($nuevoTotal, 2, ',', '.');
                    }

                    return $item;
                });
            } else {

                if ($total5125 != 0) {
                    $descripcion = optional(
                        $informeCollection->firstWhere('cuenta', '5140')
                    )->descripcionct ?? 'Cuenta 5140';

                    $informeAgrupado->put($descripcion, [
                        'Cuenta' => '5140',
                        'descripcionct' => $descripcion,
                        'total_mes' => number_format($total5125, 2, ',', '.'),
                    ]);
                }
            }

            // Rellena con valores vacíos para cuentas faltantes
            foreach ($ordenMeses as $mesOrden) {
                if (!isset($informePorMes[$mesOrden])) {
                    $informePorMes[$mesOrden] = [];
                }
                set_time_limit(600);

                foreach ($informeAgrupado as $descripcionct => $item) {
                    // Busca la cuenta en el mes actual
                    $cuentaExistente = array_filter($informePorMes[$mesOrden], function ($existing) use ($descripcionct) {
                        return $existing['descripcionct'] === $descripcionct;
                    });

                    // Si no existe la cuenta en el mes actual, agrégala
                    if (empty($cuentaExistente)) {
                        $informePorMes[$mesOrden][] = [
                            'Cuenta' => $item['Cuenta'],
                            'descripcionct' => $descripcionct,
                            'total_mes' => '',
                        ];
                    }
                }
            }

            // Agregar el total de la suma por cuenta al resultado
            $informePorMes[$mes] = $informeAgrupado->toArray();
            set_time_limit(600);
            // Obtener la suma de total_mes directamente desde la consulta
            $totalMes = $informe->sum(function ($item) {
                $totalMesItem = str_replace(',', '', $item->total_mes);
                $totalMesItem = str_replace('.', '.', $totalMesItem);
                $totalMesItem = is_numeric($totalMesItem) ? (float) $totalMesItem : 0;
                return $totalMesItem;
            });
            // Formatear el totalMes antes de agregarlo al array
            $totalMesFormateado = number_format($totalMes, -2, ',', '.');
            // Agregar el total del mes al array
            $informePorMes[$mes][] = [
                'Cuenta' => 0000,
                'descripcionct' => 'Total Mes',
                'total_mes' => $totalMesFormateado,
            ];
            // Restar un mes a la fecha de inicio para la siguiente iteración
            $fechaInicio->subMonth();
        }
        // // Organizar el array resultante según el orden personalizado de los meses
        $informePorMes = array_replace(array_flip($ordenMeses), $informePorMes);

        if ($siigo != 'PYME' || $siigo != 'NUBE') {
            $ordenCuentas1 = json_decode($orden);
            $ordenCuentas = [
                41,
                4175,
                6,
                52,
                5105,
                5110,
                5115,
                5120,
                5130,
                5135,
                5140,
                5145,
                5150,
                5155,
                5160,
                5165,
                5199,
                5195,
                42,
                53,
                5305,
                54,
                4210
                // Agrega aquí otras cuentas según sea necesario
            ];
        } else {
            $ordenCuentas = [
                41,
                4175,
                42,
                5105,
                5110,
                5115,
                5120,
                5135,
                5140,
                5145,
                5150,
                5195,
                6,
                5130,
                5155,
                53,
                5160,
                54,
                52,
                4210,
                5305
                // Agrega aquí otras cuentas según sea necesario
            ];
        }

        if ($siigo != 'PYME' || $siigo != 'NUBE') {
            $nombreCuentas = [
                'Ingresos',
                'Devoluciones en ventas',
                'Costos de venta', 
                'Gastos operacionales', 
                'Gastos de personal',
                'Honorarios',
                'Impuestos',
                'Arrendamientos', 
                'Seguros', 
                'Servicios', 
                'Gastos legales',
                'Mantto. Ed. Y Equipos',
                'Adecuación e instalación',
                'Gastos de viaje', 
                'Depreciación',
                'Amortización',
                'Deterioro',
                'Diversos', 
                'Gastos operacionales',
                'OTROS INGRESOS',
                'OTROS EGRESOS',
                'Gastos financieros',
                'Impuesto de renta',
                'Ingresos financieros',
                // Agrega aquí otras cuentas según sea necesario
            ];
        } else {
            $nombreCuentas = [
                'VENTAS',
                'Devoluciones en ventas',
                'Otros Ingresos',
                'Gastos de Personal',
                'Honorarios',
                'Impuestos',
                'Arrendamientos',
                'Servicios',
                'Gastos Legales',
                'Mantto. Ed. Y Equipos',
                'Adecuación e instalación',
                'Diversos',
                'COSTO MERCANCIA VENDIDA',
                'Seguros',
                'Gastos de viaje',
                'Otros Egresos',
                'Depreciación',
                'Amortización',
                'Deterioro',
                'Impuesto de renta',
                'Gastos de venta',
                'Ingresos financieros',
                'Gastos financieros'
                // Agrega aquí otras cuentas según sea necesario
            ];
        }


        // Reestructurar el array para mostrar los datos en columnas
        $columnData = [];
        $columnData['Cuenta'] = $ordenCuentas; // Inicializar con el orden de cuentas deseado
        $columnData['descripcionct'] = array_values(array_unique($nombreCuentas)); // Eliminar duplicados y reindexar

        $descripcionesGuardadas = []; // Para evitar duplicados

        // Inicializar los totales mensuales en 0
        foreach ($informePorMes as $mes => $informeMes) {
            set_time_limit(600);
            if (!in_array($mes, ['descripcionct', 'Cuenta'])) {
                $columnData[$mes] = array_fill(0, count($ordenCuentas), 0);
            }
        }

        foreach ($informePorMes as $mes => $informeMes) {
            set_time_limit(600);
            if (is_array($informeMes) || is_object($informeMes)) {
                foreach ($ordenCuentas as $indice => $cuenta) {
                    // Inicializar con '-' en caso de que no haya datos para esa cuenta en ese mes
                    $columnData[$mes][$indice] = '-';

                    foreach ($informeMes as $informeItem) {
                        $descripcionct = is_array($informeItem) ? $informeItem['descripcionct'] : $informeItem->descripcionct;
                        $cuentaItem = is_array($informeItem) ? $informeItem['Cuenta'] : $informeItem->cuenta;
                        $totalMes = is_array($informeItem) ? $informeItem['total_mes'] : $informeItem->total_mes;

                        // Agregar la descripción de la cuenta solo si no está en la lista de descripciones guardadas
                        if (!in_array($descripcionct, $descripcionesGuardadas)) {
                            if ($descripcionct !== 'Total Mes') {
                                $columnData['descripcionct'][] = $descripcionct;
                            }
                            $descripcionesGuardadas[] = $descripcionct;
                        }

                        // Verificar si hay datos para esa cuenta en ese mes
                        if ($cuentaItem == $cuenta) {
                            $columnData[$mes][$indice] = $totalMes;
                        }
                    }
                }
            }
        }
        // Eliminar las descripciones duplicadas
        $columnData['descripcionct'] = array_unique($columnData['descripcionct']);
        // Completar con 0 si la cuenta no tiene datos para un mes
        foreach ($columnData['Cuenta'] as $indiceCuenta => $cuenta) {
            set_time_limit(600);
            foreach ($informePorMes as $mes => $informeMes) {
                if (!in_array($mes, ['descripcionct', 'Cuenta'])) {
                    if ($columnData[$mes][$indiceCuenta] === '-') {
                        $columnData[$mes][$indiceCuenta] = 0;
                    }
                }
            }
        }
        if ($siigo != 'PYME' || $siigo != 'NUBE') {
            // Mapeo de nombres de cuentas a posiciones
            $mapeoCuentas = [
                'ACTIVO' => 'ACTIVO',
                'Depreciación acumulada' => 'Depreciación acumulada',
                'Pasivo' => 'Pasivo',
                'Patrimonio' => 'Patrimonio',
                'Ingresos' => 'Ingresos',
                'VENTAS' => 'VENTAS',
                'Devoluciones en ventas' => 'Devoluciones en ventas',
                'Gastos' => 'Gastos',
                'Gastos de personal' => 'Gastos de personal',
                'Costos de venta' => 'Costos de venta',
                'OTROS INGRESOS' => 'OTROS INGRESOS',
                'OTROS EGRESOS' => 'OTROS EGRESOS',
                'Costos operación' => 'Costos operación',
                'Seguros' => 'Seguros',
                'Gastos de viaje' => 'Gastos de viaje',
                'Depreciación' => 'Depreciación',
                'Amortización' => 'Amortización',
                'Deterioro' => 'Deterioro',
                'Impuesto de renta' => 'Impuesto de renta',
                'Diversos' => 'Diversos',
                'Impuestos' => 'Impuestos',
                'Servicios' => 'Servicios',
                'Gastos legales' => 'Gastos legales',
                'Mantto. Ed. Y Equipos' => 'Mantto. Ed. Y Equipos',
                'Adecuación e instalación' => 'Adecuación e instalación',
                'Honorarios' => 'Honorarios',
                'Gastos operacionales' => 'Gastos operacionales',
                'Arrendamientos' => 'Arrendamientos',
                'Gastos financieros' => 'Gastos financieros',
                'Ingresos financieros' => 'Ingresos financieros',
            ];
            $cuentasEspecificas = [
                'Gastos de personal' => 'Gastos de personal',
                'Impuestos' => 'Impuestos',
                'Arrendamientos' => 'Arrendamientos',
                'Servicios' => 'Servicios',
                'Gastos legales' => 'Gastos legales',
                'Mantto. Ed. Y Equipos' => 'Mantto. Ed. Y Equipos',
                'Adecuación e instalación' => 'Adecuación e instalación',
                'Diversos' => 'Diversos',
                'Seguros' => 'Seguros',
                'Gastos de viaje' => 'Gastos de viaje',
                'Depreciación' => 'Depreciación',
                'Amortización' => 'Amortización',
                'Deterioro' => 'Deterioro',
                'Honorarios' => 'Honorarios',
            ];
        } else {
            // Mapeo de nombres de cuentas a posiciones
            $mapeoCuentas = [
                'VENTAS' => 'VENTAS',
                'Devoluciones en ventas' => 'Devoluciones en ventas',
                'COSTO MERCANCIA VENDIDA' => 'COSTO MERCANCIA VENDIDA',
                'Gastos de Personal' => 'Gastos de Personal',
                'Honorarios' => 'Honorarios',
                'Impuestos' => 'Impuestos',
                'Arrendamientos' => 'Arrendamientos',
                'Servicios' => 'Servicios',
                'Gastos Legales' => 'Gastos Legales',
                'Mantto. Ed. Y Equipos' => 'Mantto. Ed. Y Equipos',
                'Adecuación e instalación' => 'Adecuación e instalación',
                'Diversos' => 'Diversos',
                'Seguros' => 'Seguros',
                'Gastos de viaje' => 'Gastos de viaje',
                'Otros Ingresos' => 'Otros Ingresos',
                'Otros Egresos' => 'Otros Egresos',
                'Depreciación' => 'Depreciación',
                'Amortización' => 'Amortización',
                'Deterioro' => 'Deterioro',
                'Impuesto de renta' => 'Impuesto de renta',
                'Gastos de venta' => 'Gastos de venta',
                'Ingresos financieros' => 'Ingresos financieros',
                'Gastos financieros' => 'Gastos financieros'
            ];
            $cuentasEspecificas = [
                'Gastos de Personal' => 'Gastos de Personal',
                'Honorarios' => 'Honorarios',
                'Impuestos' => 'Impuestos',
                'Arrendamientos' => 'Arrendamientos',
                'Servicios' => 'Servicios',
                'Gastos Legales' => 'Gastos Legales',
                'Mantto. Ed. Y Equipos' => 'Mantto. Ed. Y Equipos',
                'Adecuación e instalación' => 'Adecuación e instalación',
                'Diversos' => 'Diversos',
                'Seguros' => 'Seguros',
                'Gastos de viaje' => 'Gastos de viaje',
                'Depreciación' => 'Depreciación',
                'Amortización' => 'Amortización',
                'Deterioro' => 'Deterioro',
            ];
        }

        // Ahora $totales contiene los totales para cada mes
        $totales = [];
        if($siigo!='PYME' || $siigo !='NUBE'){
            foreach ($meses as $mes) {
                set_time_limit(600);
                if (count($columnData[$mes]) <= 1) {
                    $totales[$mes] = [
                        "Total ventas netas" => "",
                        "Utilidad bruta ventas" => "",
                        "Total gastos admón y ventas" => "",
                        "Utilidad operacional" => "",
                        "Utilidad antes de imptos" => "",
                        "Utilidad neta" => "",
                        "Provision impuesto renta" => ""
                    ];
                } else if (count($columnData[$mes]) >= 2) {
                    $cuentaPosicion = [];
                    foreach ($mapeoCuentas as $cuenta => $nombre) {
                        // Ajusta el mapeo de las descripciones "OTROS INGRESOS" y "OTROS EGRESOS"
                        if ($nombre === 'OTROS INGRESOS') {
                            $posicion = array_search('42', $columnData['Cuenta']); // Asigna la cuenta 42 a 'OTROS INGRESOS'
                        } elseif ($nombre === 'OTROS EGRESOS') {
                            $posicion = array_search('53', $columnData['Cuenta']); // Asigna la cuenta 53 a 'OTROS EGRESOS'
                        }elseif($nombre ==='Gastos financieros'){
                            $posicion = array_search('5305', $columnData['Cuenta']); // Asigna la cuenta 5305 a 'gastos financieros'
                        } else {
                            // Para otras cuentas, busca la descripción en el array
                            $posicion = array_search($nombre, $columnData['descripcionct']);
                        }

                        // Si la posición se encuentra y tiene un valor asociado, usa el valor; de lo contrario, usa 0
                        $valor = $posicion !== false && isset($columnData[$mes][$posicion]) ? floatval(str_replace(['.', ','], ['', '.'], $columnData[$mes][$posicion])) : 0;
                        $cuentaPosicion[$nombre] = $valor;
                    }
                    // Calcula el resto de los valores utilizando $cuentaPosicion;
                    $totalVentas = ($cuentaPosicion['Ingresos'] < 0 ? -1 : 1) * (abs($cuentaPosicion['Ingresos']) - abs($cuentaPosicion['Devoluciones en ventas']));
                    // $totalVentas = abs($cuentaPosicion['Ingresos']) - $cuentaPosicion['Devoluciones en ventas'];
                    $utilidadBruta = $totalVentas -  $cuentaPosicion['Costos de venta'];
                    // Asegurarse de sumar solo las cuentas específicas
                    $totalGastos = 0;
                    foreach ($cuentasEspecificas as $cuenta) {
                        if (isset($cuentaPosicion[$cuenta])) {
                            $valor = $cuentaPosicion[$cuenta];
                            // Si el valor es negativo, réstalo, si es positivo, súmalo
                            
                                $totalGastos += $valor;
                        }
                    }
                    $utilidadOperacional = $utilidadBruta - $totalGastos - $cuentaPosicion['Gastos operacionales'];
                    $utilidadImptos = ($utilidadOperacional) + 
                        $cuentaPosicion['OTROS INGRESOS'] - 
                        $cuentaPosicion['OTROS EGRESOS'] + 
                        $cuentaPosicion['Ingresos financieros'] - 
                        $cuentaPosicion['Gastos financieros'];
                    
                    $Utilidadneta = $utilidadImptos - $cuentaPosicion['Impuesto de renta'];
                    $provisionimpuesto = isset($cuentaPosicion['Impuesto de renta']) ? $cuentaPosicion['Impuesto de renta'] : '-';
                                    
                    $totales[$mes] = [
                        "Total ventas netas" => number_format($totalVentas, 0, ',', '.'),
                        "Utilidad bruta ventas" => number_format($utilidadBruta, 0, ',', '.'),
                        "Total gastos admón y ventas" => number_format($totalGastos, 0, ',', '.'),
                        "Utilidad operacional" => number_format($utilidadOperacional, 0, ',', '.'),
                        "Utilidad antes de imptos" => number_format($utilidadImptos, 0, ',', '.'),
                        "Utilidad neta" => number_format($Utilidadneta, 0, ',', '.'),
                        "Provision impuesto renta" => number_format($provisionimpuesto, 0, ',', '.'),
                    ];
                }
            }
        } else {
            foreach ($meses as $mes) {
                set_time_limit(600);
                if (count($columnData[$mes]) <= 1) {
                    $totales[$mes] = [
                        "Total ventas netas" => "",
                        "Utilidad bruta ventas" => "",
                        "Total gastos admón y ventas" => "",
                        "Utilidad operacional" => "",
                        "Utilidad antes de imptos" => "",
                        "Utilidad neta" => ""
                    ];
                } else if (count($columnData[$mes]) >= 2) {
                    $cuentaPosicion = [];
                    foreach ($mapeoCuentas as $cuenta => $nombre) {
                        $posicion = array_search($nombre, $columnData['descripcionct']);
                        $valor = $posicion !== false && isset($columnData[$mes][$posicion]) ? floatval(str_replace(['.', ','], ['', '.'], $columnData[$mes][$posicion])) : 0;
                        $cuentaPosicion[$nombre] = $valor;
                    }
                    // Calcula el resto de los valores utilizando $cuentaPosicion
                    $totalVentas = ($cuentaPosicion['VENTAS'] < 0 ? -1 : 1) * (abs($cuentaPosicion['VENTAS']) - abs($cuentaPosicion['Devoluciones en ventas']));
                    $utilidadBruta = $totalVentas -  $cuentaPosicion['COSTO MERCANCIA VENDIDA'];
                    // Asegurarse de sumar solo las cuentas específicas
                    $totalGastos = 0;

                    foreach ($cuentasEspecificas as $cuenta) {
                        $valor = $cuentaPosicion[$cuenta];
                            $totalGastos += $valor;
                    }
                    $utilidadOperacional = $utilidadBruta - $totalGastos - $cuentaPosicion['Gastos de venta'];
                    $utilidadImptos = ($utilidadOperacional +
                        $cuentaPosicion['Otros Ingresos'] -
                        $cuentaPosicion['Otros Egresos'] +
                        $cuentaPosicion['Ingresos financieros'] -
                        $cuentaPosicion['Gastos financieros']);
                    $Utilidadneta =  $utilidadImptos -  $cuentaPosicion['Impuesto de renta'];
                    $totales[$mes] = [
                        "Total ventas netas" => number_format($totalVentas, 0, ',', '.'),
                        "Utilidad bruta ventas" => number_format($utilidadBruta, 0, ',', '.'),
                        "Total gastos admón y ventas" => number_format($totalGastos, 0, ',', '.'),
                        "Utilidad operacional" => number_format($utilidadOperacional, 0, ',', '.'),
                        "Utilidad antes de imptos" => number_format($utilidadImptos, 0, ',', '.'),
                        "Utilidad neta" => number_format($Utilidadneta, 0, ',', '.')
                    ];
                }
            }
        }
        // Filtrar meses vacíos
        $mesesConDatos = array_filter(array_keys($columnData), function ($mes) use ($columnData) {
            // Mantener siempre los datos de cuenta y descripción
            if (in_array($mes, ['Cuenta', 'descripcionct'])) {
                return true;
            }

            // Verificar si hay al menos un valor no vacío y no cero en el mes
            foreach ($columnData[$mes] as $valor) {
                if (!empty($valor) && (!is_numeric($valor) || (float)$valor != 0)) {
                    return true;
                }
            }
            return false; // Todos los valores son ceros o vacíos
        });

        // Filtrar columnData para solo incluir meses con datos
        $filteredColumnData = array_filter($columnData, function ($valores, $mes) use ($mesesConDatos) {
            return in_array($mes, $mesesConDatos);
        }, ARRAY_FILTER_USE_BOTH);
        // Obtener las descripciones permitidas
        $descripcionesPermitidas = array_keys($mapeoCuentas);

        // Obtener los índices válidos según descripcionct
        $indicesValidos = [];

        foreach ($filteredColumnData['descripcionct'] as $index => $descripcion) {
            if (in_array($descripcion, $descripcionesPermitidas)) {
                $indicesValidos[] = $index;
            }
        }

        // Reconstruir el array completo usando solo esos índices
        $nuevoColumnData = [];

        foreach ($filteredColumnData as $key => $valores) {
            $nuevoColumnData[$key] = [];

            foreach ($indicesValidos as $index) {
                if (isset($valores[$index])) {
                    $nuevoColumnData[$key][] = $valores[$index];
                }
            }
        }

        // Reemplazar
        $filteredColumnData = $nuevoColumnData;

        return [
            'informePorMes' => $filteredColumnData,
            'totales' => $totales, // Si también necesitas filtrar los totales, puedes aplicar un proceso similar aquí
            'totalGeneral' => 0,
        ];
    }

    public function limpiarValor($valor)
    {
        if ($valor === null || $valor === '') return 0;
        $valor = str_replace(',', '', $valor); // Quita comas si hubiera
        return (float)$valor; // Fuerza float
    }
}
