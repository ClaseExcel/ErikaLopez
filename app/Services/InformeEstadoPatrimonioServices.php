<?php

namespace App\Services;

use App\Models\CentroCosto;
use App\Models\Empresa;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\ConsolidadorEstadosFinancierosService;

class InformeEstadoPatrimonioServices
{

    private $consolidador;

    public function __construct(ConsolidadorEstadosFinancierosService $consolidador)
    {
        $this->consolidador = $consolidador;
    }

    public function ejecutar($fecha, $nit, $siigo, $centro_costo, $tipoinforme, $anio, $anioAnterior)
    {
            $ordenArray = ['31', '32','33','34','35', '36', '37'];
            $sectorCooperativo = 2;
            $cambiosCooperativa = [
                    'Cuentas comerciales y otras cuentas por cobrar' => 'Inventarios',
                    'Ganancias acumuladas - Adopcion por primera vez' => 'Superavit de Capital Valorizacion',
                    'Propiedades planta y equipos' => 'Activos biológicos',
                    'Inventarios' => 'Cartera de creditos',
                    'Otros activos' => 'Activos materiales',
                    'Activos Intangibles' => 'Cuentas comerciales y otras cuentas por cobrar',
                    'Pasivos por Impuestos Corrientes' => 'Cuentas por pagar',
                    'Pasivo por impuesto diferido' => 'Otros Pasivos',
                    'Otros Pasivos' => 'Pasivos estimados',
                    'Cuentas por pagar' => 'Obligaciones financieras',
                    'Obligaciones financieras' => 'No existe',
                    'Cuentas comerciales y otras cuentas por pagar' => 'Creditos ordinarios a corto plazo',
                    'Beneficios a empleados' => 'Fondo social solidaridad',
                    'Dividendos o participacion'            => 'Excedentes y/o pérdidas',
                    'Reservas'                              => 'Fondos de destinación específica',
                    'Superavit de capital'                  => 'Reservas',
                    'resultados de ejercicios anteriores'   => 'Excedentes',
                    'Resultado del ejercicio'               => 'Excedentes acumulados por convergencia',
                    'Pasivos Contingentes'                  => 'Fondos sociales y mutuales',
                ];
           
          
            // ⚡ función interna que calcula patrimonio para un año específico
            $calcular = function($anio) use ($fecha, $nit, $siigo, $centro_costo, $ordenArray,$cambiosCooperativa,$sectorCooperativo) {
                $mesAño2 = $fecha->month;
                $cuentaSQL = "REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '')";
                $baseGenerico = [
                    'tabla' => 'informesgenericos',
                    'campoCuenta' => 'cuenta',
                    'campoSaldo' => 'saldo_final',
                    'campoFecha' => 'fechareporte',
                    'campoSaldoAnterior' => 'saldo_anterior',
                    'campoDebitos' => 'debitos',
                    'campoCreditos' => 'creditos'
                ];
                $config = [
                    'CONTAPYME' => [
                        'tabla' => 'contapyme_completo',
                        'campoCuenta' => 'cuenta',
                        'campoSaldo' => 'nuevo_saldo',
                        'campoFecha' => 'fechareporte',
                        'campoSaldoAnterior' => 'saldo_anterior',
                        'campoDebitos' => 'debitos',
                        'campoCreditos' => 'creditos'
                    ],

                    'LOGGRO' => [
                        'tabla' => 'loggro',
                        'campoCuenta' => 'cuenta',
                        'campoSaldo' => 'saldo_final',
                        'campoFecha' => 'fechareporte',
                        'campoSaldoAnterior' => 'saldo_anterior',
                        'campoDebitos' => 'debitos',
                        'campoCreditos' => 'creditos'
                    ],

                    'BEGRANDA' => [
                        'tabla' => 'begranda',
                        'campoCuenta' => 'cuenta',
                        'campoSaldo' => 'saldo_final',
                        'campoFecha' => 'fechareporte',
                        'campoSaldoAnterior' => 'saldo_anterior',
                        'campoDebitos' => 'debitos',
                        'campoCreditos' => 'creditos'
                    ],

                    'PYME' => [
                        'tabla' => 'clientes',
                        'campoCuenta' => $cuentaSQL . ' as cuenta',
                        'campoSaldo' => 'nuevo_saldo',
                        'campoFecha' => 'fechareporte',
                        'campoSaldoAnterior' => 'saldo_anterior',
                        'campoDebitos' => 'debitos',
                        'campoCreditos' => 'creditos'
                    ],

                    'NUBE' => [
                        'tabla' => 'clientes',
                        'campoCuenta' => 'codigo_cuenta_contable_ga',
                        'campoSaldo' => 'saldo_final_ga',
                        'campoFecha' => 'fechareporte_ga',
                        'campoSaldoAnterior' => 'saldo_inicial_ga',
                        'campoDebitos' => 'movimiento_debito_ga',
                        'campoCreditos' => 'movimiento_credito_ga'
                    ],
                ];
                
                // ✅ CORRECCIÓN: Función auxiliar para convertir a float, definida al inicio del scope.
                $toFloat = fn($val) => (float) str_replace(',', '', $val);

                // 1. Obtener Utilidad Neta (el signo que traiga del estado de resultados)
                 $empresaasociada = Empresa::where('NIT', $nit)->value('empresaasociada');
                $operador = Empresa::where('NIT', $nit)->value('operador') ?? '+';
                if($empresaasociada){
                    $informe1= app(\App\Services\InformeEstadoResultadosServices::class)->ejecutar($fecha, $nit, $siigo, $centro_costo, 2, $tipoinformepdf = 2,$valorparautilidad=1);
                    $informe2= app(\App\Services\InformeEstadoResultadosServices::class)->ejecutar($fecha, $empresaasociada, $siigo, $centro_costo, 2, $tipoinformepdf = 2,$valorparautilidad=1);
                    $informeResultados =$this->consolidador->combinarEstadoResultados(
                        $informe1,
                        $informe2,
                        $operador
                    );
                }else{
                    $informeResultados = app(\App\Services\InformeEstadoResultadosServices::class)->ejecutar($fecha, $nit, $siigo, $centro_costo, 2, $tipoinformepdf = 2,$valorparautilidad=1);
                }
                
                $utilidad_perdida_neta =
                    $informeResultados['informeData']['Utilidad (Perdida) Neta del periodo'][$anio]
                    ?? $informeResultados['informeData']['Utilidad y/o perdidas del ejercicio'][$anio]
                    ?? 0;

                
                // 💡 Aquí se obtiene el valor que quieres invertir: La utilidad Neta del Periodo.
                $utilidadanio1 = number_format($utilidad_perdida_neta, 0, '.', ','); 
                $utilidad_num = (float) str_replace(['.', ','], '', $utilidadanio1);
                $utilidad_ajustada = $utilidad_num * -1; // 👈 ÚNICA LÍNEA A INVERTIR
                $utilidad_ajustada_str = number_format($utilidad_ajustada, 0, '.', ',');

                // 3. Obtener datos de Patrimonio de la base de datos (sin invertir el signo de la DB)
                if ($siigo == 'PYME' || $siigo == 'NUBE') {
                    $informeData = $this->PatrimonioSiigoInforme($siigo, $nit, $anio,$fecha->month, $mesAño2, $ordenArray);
                    
                } else {
                    $cfg = $config[$siigo] ?? $baseGenerico;
                    $informeQuery = DB::table(DB::raw("(SELECT 
                        CASE 
                            WHEN {$cfg['campoCuenta']} = '31' THEN '31'
                            WHEN {$cfg['campoCuenta']} = '32' THEN '32'
                            WHEN {$cfg['campoCuenta']} = '33' THEN '33'
                            WHEN {$cfg['campoCuenta']} = '36' THEN '36'
                            WHEN {$cfg['campoCuenta']} = '37' THEN '37'
                            ELSE {$cfg['campoCuenta']}
                        END AS cuenta,
                        SUM(CASE 
                            WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) = ? 
                            THEN IFNULL({$cfg['campoSaldoAnterior']}, 0) 
                            ELSE 0 
                        END) AS saldo_anterior,
                        SUM(CASE 
                            WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) = ? 
                            THEN IFNULL({$cfg['campoSaldo']}, 0) 
                            ELSE 0 
                        END) AS saldo_actual,
                        SUM(CASE WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) <= ? THEN IFNULL({$cfg['campoCreditos']}, 0) ELSE 0 END) AS aumento,
                        SUM(CASE WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) <= ? THEN IFNULL({$cfg['campoDebitos']}, 0) ELSE 0 END) AS disminucion
                        FROM {$cfg['tabla']}
                        WHERE Nit = ?
                        GROUP BY cuenta, YEAR({$cfg['campoFecha']})
                        ) AS subquery"))
                        // 🛑 Sin * -1, saldos con signo natural de la DB
                        ->selectRaw('cuenta, 
                                    FORMAT(SUM(COALESCE(saldo_anterior, 0)), 2) AS saldo_anterior, 
                                    FORMAT(SUM(COALESCE(aumento, 0)), 2) AS aumento, 
                                    FORMAT(SUM(COALESCE(disminucion, 0)), 2) AS disminucion, 
                                    FORMAT(SUM(COALESCE(saldo_actual, 0)), 2) AS saldo_actual')
                        ->whereNotNull('cuenta')
                        ->groupBy('cuenta')
                        ->orderBy('cuenta', 'asc');

                    $bindings = [$anio, $fecha->month, $anio, $fecha->month, $anio, $fecha->month, $anio, $fecha->month, $nit];
                    $informeQuery->setBindings($bindings);
                    $informeData = $informeQuery->get()->keyBy('cuenta');
                    
                }
               
                // Filtrar y asegurar que existan las cuentas permitidas
                $cuentasPermitidas = ['31', '32','33','34','35', '36', '37','38'];
                $informeData = $informeData->filter(fn ($item) => in_array($item->cuenta, $cuentasPermitidas))->keyBy('cuenta')->toArray();

                foreach ($cuentasPermitidas as $cuenta) {
                    if (!isset($informeData[$cuenta])) {
                        $informeData[$cuenta] = (object)['cuenta' => $cuenta, 'saldo_anterior' => '0.00', 'aumento' => '0.00', 'disminucion' => '0.00', 'saldo_actual' => '0.00'];
                    }
                }
                // --- convertir a número + poner positivos SOLO cuenta 31 ---
                if (isset($informeData['31'])) {
                    foreach (['saldo_anterior', 'aumento', 'disminucion', 'saldo_actual'] as $campo) {
                        if (isset($informeData['31']->$campo)) {
                            $informeData['31']->$campo = abs(str_replace(',', '', $informeData['31']->$campo));
                        }
                    }
                }
                // --- Normalizar patrimonio positivo ---
                foreach (['32', '33','34','35'] as $cta) {
                    if (isset($informeData[$cta])) {
                        foreach (['saldo_anterior', 'aumento', 'disminucion', 'saldo_actual'] as $campo) {
                            $informeData[$cta]->$campo = abs(
                                (float) str_replace(',', '', $informeData[$cta]->$campo)
                            );
                        }
                    }
                }
                
                // --- Ajuste de Cuenta 36 (RESULTADOS DEL EJERCICIO) ---
                
                $cuenta36 = (object)[
                    'cuenta' => '362',
                    'descripcion' => $this->nombreSector('resultados de ejercicios anteriores', $sectorCooperativo, $cambiosCooperativa),
                    'saldo_anterior' => '0.00', 
                    'saldo_actual' => $utilidad_ajustada_str, 
                    'aumento' => $utilidad_ajustada > 0 ? number_format($utilidad_ajustada , 2)  : '0.00',
                    'disminucion' => $utilidad_ajustada < 0 ? number_format($utilidad_ajustada , 2): '0.00',
                ];
                
                // --- Asignación de descripciones ---
                $informeData['31'] = (object) array_merge((array)$informeData['31'], ['descripcion' => 'CAPITAL SOCIAL']);
                $informeData['32'] = (object) array_merge((array)$informeData['32'], ['descripcion' => 'SUPERÁVIT DE CAPITAL']);
                $informeData['33'] = (object) array_merge((array)$informeData['33'], ['descripcion' => 'OTRAS RESERVAS']);
                $informeData['34'] = (object) array_merge((array)$informeData['34'], ['descripcion' => 'GANANCIAS ACUMULADAS']);
                $informeData['35'] = (object) array_merge((array)$informeData['35'], ['descripcion' => $this->nombreSector('Reservas', $sectorCooperativo, $cambiosCooperativa)]);
                $informeData['36'] = (object) array_merge((array)$informeData['36'], ['descripcion' => $this->nombreSector('Resultado ejercicios', $sectorCooperativo, $cambiosCooperativa)]);
                $informeData['37'] = (object) array_merge((array)$informeData['37'], ['descripcion' => $this->nombreSector('resultados de ejercicios anteriores', $sectorCooperativo, $cambiosCooperativa)]);
                $informeData['38'] = (object) array_merge((array)$informeData['38'], ['descripcion' => $this->nombreSector('Superavit de Capital Valorizacion', $sectorCooperativo, $cambiosCooperativa)]);
                
                
                // 5. Ajuste cuenta 36 (solo si hay utilidad calculada)
                if ($utilidad_ajustada != 0) {
                    $informeData['362'] = $cuenta36;
                } else {
                    // Si no hay utilidad calculada y no existe en BD, la dejamos en cero
                    if (!isset($informeData['362'])) {
                        $informeData['362'] = (object)[
                            'cuenta' => '362',
                            'descripcion' => 'RESULTADOS DEL EJERCICIO',
                            'saldo_anterior' => '0.00',
                            'aumento' => '0.00',
                            'disminucion' => '0.00',
                            'saldo_actual' => '0.00',
                        ];
                    }
                }

                // Convertir el informe en una colección ordenada por cuenta
                $informeData = collect($informeData)->sortKeys();
                // ✅ Invertir signo a todas las cuentas excepto la 37
                $informeData = $informeData->map(function ($item) use ($toFloat,$sectorCooperativo,$cambiosCooperativa) {

                    $saldoAnterior = $toFloat($item->saldo_anterior);
                    $aumento = $toFloat($item->aumento);
                    $disminucion = $toFloat($item->disminucion);
                    $saldoActual = $toFloat($item->saldo_actual);

                    // cuentas que no invierten signo
                    if (in_array($item->cuenta, ['31','32','33','34','35'])) {

                        return (object)[
                            'cuenta' => $item->cuenta,
                            'descripcion' => $this->nombreSector($item->descripcion, $sectorCooperativo, $cambiosCooperativa),
                            'saldo_anterior' => number_format($saldoAnterior,2,'.',','),
                            'aumento' => number_format($aumento,2,'.',','),
                            'disminucion' => number_format($disminucion,2,'.',','),
                            'saldo_actual' => number_format($saldoActual,2,'.',','),
                        ];
                    }
                    // cuenta 3 solo invierte saldo anterior
                    if ($item->cuenta === '36') {
                        $saldoAnterior = $saldoAnterior < 0 ? abs($saldoAnterior) : abs($saldoAnterior);
                        $saldoActual   = $saldoActual < 0 ? abs($saldoActual) : abs($saldoActual);

                        return (object)[
                            'cuenta' => $item->cuenta,
                            'descripcion' => $item->descripcion,
                            'saldo_anterior' => number_format($saldoAnterior ,2,'.',','),
                            'aumento' => number_format($aumento,2,'.',','),
                            'disminucion' => number_format($disminucion,2,'.',','),
                            'saldo_actual' => number_format($saldoActual,2,'.',','),
                        ];
                    }
                    // cuenta 37 solo invierte saldo anterior
                    if ($item->cuenta === '37') {

                        return (object)[
                            'cuenta' => $item->cuenta,
                            'descripcion' => $item->descripcion,
                            'saldo_anterior' => number_format($saldoAnterior * -1,2,'.',','),
                            'aumento' => number_format($aumento,2,'.',','),
                            'disminucion' => number_format($disminucion,2,'.',','),
                            'saldo_actual' => number_format($saldoActual*-1,2,'.',','),
                        ];
                    }


                    // resto de cuentas
                    return (object)[
                        'cuenta' => $item->cuenta,
                        'descripcion' => $item->descripcion ?? 'NN',
                        'saldo_anterior' => number_format($saldoAnterior * -1,2,'.',','),
                        'aumento' => number_format($aumento * -1,2,'.',','),
                        'disminucion' => number_format($disminucion * -1,2,'.',','),
                        'saldo_actual' => number_format($saldoActual * -1,2,'.',','),
                    ];

                });

                $totalSaldoAnterior = 0;
                $totalAumento = 0;
                $totalDisminucion = 0;
                $totalSaldoActual = 0;

                foreach ($informeData as $item) {

                    $saldoAnterior = $toFloat($item->saldo_anterior);
                    $aumento = $toFloat($item->aumento);
                    $disminucion = $toFloat($item->disminucion);
                    $saldoActual = $toFloat($item->saldo_actual);

                    $totalSaldoAnterior += $saldoAnterior;
                    $totalAumento += $aumento;
                    $totalDisminucion += $disminucion;
                    $totalSaldoActual += $saldoActual;
                }


                // Agregar fila de total al final del array
                $informeData->put('TOTAL', (object) [
                    'cuenta' => 'Total Patrimonio',
                    'descripcion' => 'TOTAL PATRIMONIO',
                    // Se usa 0 decimales, como en tu código original
                    'saldo_anterior' => number_format($totalSaldoAnterior, 0), 
                    'aumento' => number_format($totalAumento, 0),
                    'disminucion' => number_format($totalDisminucion, 0),
                    'saldo_actual' => number_format($totalSaldoActual, 0),
                ]);

                
                // Convertir todas las cuentas a formato final (con 2 decimales, excepto el total)
                return $informeData->map(function($item) use ($toFloat,$sectorCooperativo,$cambiosCooperativa) { // 🛑 Se usa 'use ($toFloat)' aquí.
                    if ($item->cuenta === 'Total Patrimonio') {
                        return $item; // Los totales ya están en formato 0 decimales
                    }
                    return (object)[
                        'cuenta' => $item->cuenta,
                        'descripcion' => $item->descripcion,
                        'saldo_anterior' => number_format($toFloat($item->saldo_anterior), 2, '.', ','),
                        'aumento' => number_format($toFloat($item->aumento), 2, '.', ','),
                        'disminucion' => number_format($toFloat($item->disminucion), 2, '.', ','),
                        'saldo_actual' => number_format($toFloat($item->saldo_actual), 2, '.', ','),
                    ];
                });
            };
            
            // 📌 Calculamos ambos años
            $resultadoActual = $calcular($anio);
            $resultadoAnterior = $calcular($anioAnterior);
            if (
                isset($resultadoActual['36'], $resultadoAnterior['36'], $resultadoActual['TOTAL'])
            ) {
                $saldoAnteriorActual36 = (float) str_replace(',', '', $resultadoActual['36']->saldo_anterior);
                $saldoActualAnterior36 = (float) str_replace(',', '', $resultadoAnterior['36']->saldo_actual);

                // 👉 Condición exacta
                if ($saldoAnteriorActual36 == 0 && $saldoActualAnterior36 != 0) {

                    // 1️⃣ Actualizar cuenta 36
                    $resultadoActual['36']->saldo_anterior = number_format(
                        $saldoActualAnterior36,
                        2,
                        '.',
                        ','
                    );

                    // 2️⃣ Actualizar TOTAL PATRIMONIO (saldo anterior)
                    $saldoAnteriorTotal = (float) str_replace(',', '', $resultadoActual['TOTAL']->saldo_anterior);

                    $resultadoActual['TOTAL']->saldo_anterior = number_format(
                        $saldoAnteriorTotal + $saldoActualAnterior36,
                        0, // 👈 respetando tu formato de total
                        '.',
                        ','
                    );
                }
            }
            foreach ($resultadoActual as $cuenta => $itemActual) {

                if ($cuenta === 'TOTAL') {
                    continue; // el total lo manejamos después
                }

                if (isset($resultadoAnterior[$cuenta])) {

                    $saldoActualAnterior = (float) str_replace(',', '', $resultadoAnterior[$cuenta]->saldo_actual);

                    // 👉 Pasar saldo actual del año anterior a saldo anterior del año actual
                    $resultadoActual[$cuenta]->saldo_anterior = number_format($saldoActualAnterior, 2, '.', ',');
                }
            }
            $totalSaldoAnterior = collect($resultadoActual)
                ->reject(fn($item, $key) => $key === 'TOTAL')
                ->sum(fn($item) => (float) str_replace(',', '', $item->saldo_anterior));

            $resultadoActual['TOTAL']->saldo_anterior = number_format($totalSaldoAnterior, 0, '.', ',');


            // 📊 Devolvemos en un array con dos bloques
            return [
                $anio => $resultadoActual,
                $anioAnterior => $resultadoAnterior,
            ];
    }

    public function PatrimonioSiigoInforme($siigo, $nit, $anio, $mes,$mesAño2,$ordenArray)
    {
        $cuentaSQL = "REPLACE(CONCAT(
            TRIM(IFNULL(clientes.grupo, '')),
            TRIM(IFNULL(clientes.cuenta, '')),
            TRIM(IFNULL(clientes.subcuenta, ''))
        ), ' ', '')";
        $config = [
                'PYME' => [
                    'tabla' => 'clientes',
                    'campoCuenta' => $cuentaSQL,
                    'campoSaldo' => 'nuevo_saldo',
                    'campoFecha' => 'fechareporte',
                    'campoSaldoAnterior' => 'saldo_anterior',
                    'campoDebitos' => 'debitos',
                    'campoCreditos' => 'creditos'
                ],
                'NUBE' => [
                    'tabla' => 'clientes',
                    'campoCuenta' => 'codigo_cuenta_contable_ga',
                    'campoSaldo' => 'saldo_final_ga',
                    'campoFecha' => 'fechareporte_ga',
                    'campoSaldoAnterior' => 'saldo_inicial_ga',
                    'campoDebitos' => 'movimiento_debito_ga',
                    'campoCreditos' => 'movimiento_credito_ga'
                ]
        ];
        
        if($siigo == 'NUBE'){
                $cfg = $config[$siigo];
           $informeQuery = DB::table(DB::raw("(SELECT 
                {$cfg['campoCuenta']} AS cuenta,

                SUM(CASE 
                    WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) = ? 
                    THEN IFNULL({$cfg['campoSaldoAnterior']}, 0) 
                    ELSE 0 
                END) AS saldo_anterior,

                SUM(CASE 
                    WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) = ? 
                    THEN IFNULL(ABS({$cfg['campoSaldo']}), 0) 
                    ELSE 0 
                END) AS saldo_actual,

                SUM(CASE 
                    WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) <= ? 
                    THEN IFNULL({$cfg['campoCreditos']}, 0) 
                    ELSE 0 
                END) AS aumento,

                SUM(CASE 
                    WHEN YEAR({$cfg['campoFecha']}) = ? AND MONTH({$cfg['campoFecha']}) <= ? 
                    THEN IFNULL({$cfg['campoDebitos']}, 0) 
                    ELSE 0 
                END) AS disminucion

                FROM {$cfg['tabla']}
                WHERE Nit = ?
                AND {$cfg['campoCuenta']} IN ('31','32','33','36','37')
                GROUP BY {$cfg['campoCuenta']}, YEAR({$cfg['campoFecha']})
            ) AS subquery"))
            ->selectRaw("cuenta, 
                FORMAT(SUM(COALESCE(saldo_anterior, 0)), 2) AS saldo_anterior, 
                FORMAT(SUM(COALESCE(aumento, 0)), 2) AS aumento, 
                FORMAT(SUM(COALESCE(disminucion, 0)), 2) AS disminucion, 
                FORMAT(SUM(COALESCE(saldo_actual, 0)), 2) AS saldo_actual")
            ->whereNotNull('cuenta')
            ->groupBy('cuenta')
            ->orderBy('cuenta', 'asc');


            $bindings = [$anio, $mes, $anio, $mes, $anio, $mes, $anio, $mes, $nit];
            $informeQuery->setBindings($bindings);

            $informe = $informeQuery->get()->keyBy('cuenta');
        }else if($siigo == 'PYME'){
      

            $informe = DB::table('clientes')
                ->selectRaw("$cuentaSQL AS cuenta")
                ->selectRaw("ROUND(SUM(clientes.saldo_anterior), 0) AS saldo_anterior")
                ->selectRaw("ROUND(SUM(clientes.debitos), 0) AS aumento")
                ->selectRaw("ROUND(SUM(clientes.creditos), 0) AS disminucion")
                ->selectRaw("ROUND(SUM(clientes.nuevo_saldo), 0) AS saldo_actual")
                ->whereRaw("$cuentaSQL IN (" . implode(',', array_map(fn($c) => "'$c'", $ordenArray)) . ")")
                ->whereYear('fechareporte', $anio)
                ->whereMonth('fechareporte', '=', $mes)
                ->where('Nit', $nit)
                ->groupByRaw($cuentaSQL)
                ->get()
                ->keyBy('cuenta');
        }


        return $informe;
    }

    function nombreSector($nombre, $sectorCooperativo, $mapa)
    {
        if ($sectorCooperativo == 1 && isset($mapa[$nombre])) {
            return $mapa[$nombre];
        }

        return $nombre;
    }
}