<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ConsolidadorEstadosFinancierosService
{

    function limpiarNumero($valor)
    {
        if ($valor === null || $valor === '') {
            return 0;
        }

        // Si ya es numérico
        if (is_numeric($valor)) {
            return (float) $valor;
        }

        // Quitar comas y convertir
        $valor = str_replace(',', '', $valor);

        return (float) $valor;
    }

    function combinarInformes($inf1, $inf2, $operador = '+')
    {
        $resultado = $inf1;

        $meses = [
            'ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO',
            'JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'
        ];

        foreach ($meses as $mes) {

            if (!isset($resultado['informePorMes'][$mes])) {
                continue;
            }

            foreach ($resultado['informePorMes'][$mes] as $i => $valor1) {

                $valor2 = $inf2['informePorMes'][$mes][$i] ?? 0;

                $num1 = (float) str_replace(['.', ','], ['', '.'], $valor1);
                $num2 = (float) str_replace(['.', ','], ['', '.'], $valor2);

                $nuevo = $operador === '-'
                    ? $num1 - $num2
                    : $num1 + $num2;

                $resultado['informePorMes'][$mes][$i] =
                    number_format($nuevo, 2, ',', '.');
            }
        }

        return $resultado;
    }

    
    function combinarEstadoResultados($inf1, $inf2, $operador = '+')
    {
        $resultado = $inf1;

        $anio = $inf1['anio'];
        $anioAnterior = $inf1['anioAnterior'];

        foreach ($inf2['informeData'] as $descripcion => $fila2) {

            if (!isset($resultado['informeData'][$descripcion])) {
                continue;
            }

            $fila1 = $resultado['informeData'][$descripcion];

            $valor1Actual = $fila1[$anio] ?? 0;
            $valor2Actual = $fila2[$anio] ?? 0;

            $valor1Anterior = $fila1[$anioAnterior] ?? 0;
            $valor2Anterior = $fila2[$anioAnterior] ?? 0;

            if ($operador == '-') {
                $nuevoActual = $valor1Actual - $valor2Actual;
                $nuevoAnterior = $valor1Anterior - $valor2Anterior;
            } else {
                $nuevoActual = $valor1Actual + $valor2Actual;
                $nuevoAnterior = $valor1Anterior + $valor2Anterior;
            }

            $resultado['informeData'][$descripcion][$anio] = $nuevoActual;
            $resultado['informeData'][$descripcion][$anioAnterior] = $nuevoAnterior;

            $resultado['informeData'][$descripcion]['total_mes'] = $nuevoActual;

            // recalcular variaciones
            $resultado['informeData'][$descripcion]['variacion$'] =
                $nuevoActual - $nuevoAnterior;

            $resultado['informeData'][$descripcion]['var%'] =
                $nuevoAnterior != 0
                    ? (($nuevoActual - $nuevoAnterior) / abs($nuevoAnterior)) * 100
                    : 0;
        }

        return $resultado;
    }

    function combinarSituacionFinanciera($inf1, $inf2, $operador = '+')
    {
        $resultado = [];

        // Convertir inf2 a array indexado por cuenta
        $inf2PorCuenta = [];
        foreach ($inf2 as $fila) {
            $inf2PorCuenta[$fila['cuenta']] = $fila;
        }

        foreach ($inf1 as $fila1) {

            $cuenta = $fila1['cuenta'];

            $fila2 = $inf2PorCuenta[$cuenta] ?? [
                "totalaño1" => 0,
                "totalaño2" => 0
            ];

            // limpiar valores
            $anio1_1 = floatval(str_replace([','], '', $fila1['totalaño1'] ?? 0));
            $anio2_1 = floatval(str_replace([','], '', $fila1['totalaño2'] ?? 0));

            $anio1_2 = floatval(str_replace([','], '', $fila2['totalaño1'] ?? 0));
            $anio2_2 = floatval(str_replace([','], '', $fila2['totalaño2'] ?? 0));

            // operador
            if ($operador == '-') {
                $nuevo1 = $anio1_1 - $anio1_2;
                $nuevo2 = $anio2_1 - $anio2_2;
            } else {
                $nuevo1 = $anio1_1 + $anio1_2;
                $nuevo2 = $anio2_1 + $anio2_2;
            }

            // variaciones
            $variacion = $nuevo1 - $nuevo2;

            $varPorcentaje = $nuevo2 != 0
                ? ($variacion / abs($nuevo2)) * 100
                : 0;

            $resultado[] = [
                "cuenta" => $fila1['cuenta'],
                "descripcion" => $fila1['descripcion'],
                "totalaño1" => $nuevo1,
                "totalaño2" => $nuevo2,
                "VAR" => number_format($varPorcentaje, 2) . '%',
                "VARIACION" => $variacion,
            ];
        }

        // 🔥 agregar cuentas que existan solo en inf2
        $cuentasInf1 = array_column($inf1, 'cuenta');

        foreach ($inf2 as $fila2) {

            if (!in_array($fila2['cuenta'], $cuentasInf1)) {

                $anio1 = floatval(str_replace(',', '', $fila2['totalaño1'] ?? 0));
                $anio2 = floatval(str_replace(',', '', $fila2['totalaño2'] ?? 0));

                if ($operador == '-') {
                    $anio1 = -$anio1;
                    $anio2 = -$anio2;
                }

                $variacion = $anio1 - $anio2;

                $varPorcentaje = $anio2 != 0
                    ? ($variacion / abs($anio2)) * 100
                    : 0;

                $resultado[] = [
                    "cuenta" => $fila2['cuenta'],
                    "descripcion" => $fila2['descripcion'],
                    "totalaño1" => $anio1,
                    "totalaño2" => $anio2,
                    "VAR" => $varPorcentaje,
                    "VARIACION" => $variacion,
                ];
            }
        }

        return $resultado;
    }

    // function combinarFlujoEfectivo($inf1, $inf2, $anio, $anioAnterior, $operador = '+')
    // {
        
    //     $resultado = [];

    //     $keyActual = 'año' . $anio;
    //     $keyAnterior = 'año' . $anioAnterior;

    //     foreach ($inf1 as $grupo => $items1) {

    //         $items2 = $inf2[$grupo] ?? [];

    //         // indexar por descripcion
    //         $items2Index = [];
    //         foreach ($items2 as $item) {
    //             $items2Index[$item['descripcion']] = $item;
    //         }

    //         foreach ($items1 as $item1) {

    //             $descripcion = $item1['descripcion'];

    //             $item2 = $items2Index[$descripcion] ?? [
    //                 $keyActual => 0,
    //                 $keyAnterior => 0
    //             ];
    //             $valor1Actual = $this->limpiarNumero($item1[$keyActual] ?? 0);
    //             $valor2Actual = $this->limpiarNumero($item2[$keyActual] ?? 0);

    //             $valor1Anterior = $this->limpiarNumero($item1[$keyAnterior] ?? 0);
    //             $valor2Anterior = $this->limpiarNumero($item2[$keyAnterior] ?? 0);

    //             if ($operador == '-') {
    //                 $nuevoActual = $valor1Actual - $valor2Actual;
    //                 $nuevoAnterior = $valor1Anterior - $valor2Anterior;
    //             } else {
    //                 $nuevoActual = $valor1Actual + $valor2Actual;
    //                 $nuevoAnterior = $valor1Anterior + $valor2Anterior;
    //             }

    //             $resultado[$grupo][] = [
    //                 'descripcion' => $descripcion,
    //                 'nota' => $item1['nota'] ?? '',
    //                 $keyActual => $nuevoActual,
    //                 $keyAnterior => $nuevoAnterior
    //             ];
    //         }

    //         // agregar items que solo existan en inf2
    //         $descripciones1 = array_column($items1, 'descripcion');

    //         foreach ($items2 as $item2) {

    //             if (!in_array($item2['descripcion'], $descripciones1)) {

    //                 $valorActual = $item2[$keyActual] ?? 0;
    //                 $valorAnterior = $item2[$keyAnterior] ?? 0;

    //                 if ($operador == '-') {
    //                     $valorActual *= -1;
    //                     $valorAnterior *= -1;
    //                 }

    //                 $resultado[$grupo][] = [
    //                     'descripcion' => $item2['descripcion'],
    //                     'nota' => $item2['nota'] ?? '',
    //                     $keyActual => $valorActual,
    //                     $keyAnterior => $valorAnterior
    //                 ];
    //             }
    //         }
    //     }

    //     return $resultado;
    // }
    public function combinarFlujoEfectivo($inf1, $inf2, $anio, $anioAnterior, $operador = '+')
    {
        $resultado = [];
        $keyActual = 'año' . $anio;
        $keyAnterior = 'año' . $anioAnterior;

        // Obtenemos todos los grupos únicos de ambas empresas (Operación, Inversión, Financiación, Variación Efectivo)
        $todosLosGrupos = array_unique(array_merge(array_keys($inf1), array_keys($inf2)));

        foreach ($todosLosGrupos as $grupo) {
            $items1 = $inf1[$grupo] ?? [];
            $items2 = $inf2[$grupo] ?? [];

            // Indexar empresa 2 por descripción para búsqueda rápida
            $items2Index = [];
            foreach ($items2 as $item) {
                $items2Index[trim($item['descripcion'])] = $item;
            }

            $procesadosEnGrupo2 = [];
            $resultado[$grupo] = [];

            // Procesar items de la Empresa 1 (y sumar con Empresa 2 si existen)
            foreach ($items1 as $item1) {
                $desc = trim($item1['descripcion']);
                $item2 = $items2Index[$desc] ?? null;

                $val1Act = $this->limpiarNumero($item1[$keyActual] ?? 0);
                $val1Ant = $this->limpiarNumero($item1[$keyAnterior] ?? 0);
                
                $val2Act = $item2 ? $this->limpiarNumero($item2[$keyActual] ?? 0) : 0;
                $val2Ant = $item2 ? $this->limpiarNumero($item2[$keyAnterior] ?? 0) : 0;

                if ($operador == '-') {
                    $nuevoActual = $val1Act - $val2Act;
                    $nuevoAnterior = $val1Ant - $val2Ant;
                } else {
                    $nuevoActual = $val1Act + $val2Act;
                    $nuevoAnterior = $val1Ant + $val2Ant;
                }

                $resultado[$grupo][] = [
                    'descripcion' => $desc,
                    'nota' => $item1['nota'] ?? ($item2['nota'] ?? ''),
                    $keyActual => $nuevoActual,
                    $keyAnterior => $nuevoAnterior
                ];

                $procesadosEnGrupo2[] = $desc;
            }

            // Agregar items que SOLO existen en la Empresa 2
            foreach ($items2 as $item2) {
                $desc = trim($item2['descripcion']);
                if (!in_array($desc, $procesadosEnGrupo2)) {
                    $val2Act = $this->limpiarNumero($item2[$keyActual] ?? 0);
                    $val2Ant = $this->limpiarNumero($item2[$keyAnterior] ?? 0);

                    if ($operador == '-') {
                        $val2Act *= -1;
                        $val2Ant *= -1;
                    }

                    $resultado[$grupo][] = [
                        'descripcion' => $desc,
                        'nota' => $item2['nota'] ?? '',
                        $keyActual => $val2Act,
                        $keyAnterior => $val2Ant
                    ];
                }
            }
        }

        return $resultado;
    }



    function combinarCambioPatrimonio($inf1, $inf2, $operador = '+')
    {
        $resultado = [];

        foreach ($inf1 as $anio => $coleccion1) {

            $coleccion2 = $inf2[$anio] ?? collect();

            $resultado[$anio] = collect();

            $cuentas = collect($coleccion1)
                ->keys()
                ->merge($coleccion2->keys())
                ->unique();
            foreach ($cuentas as $cuenta) {

                $item1 = $coleccion1[$cuenta] ?? null;
                $item2 = $coleccion2[$cuenta] ?? null;

                $saldoAnterior1 = $this->limpiarNumero($item1->saldo_anterior ?? 0)*-1;
                $aumento1 = $this->limpiarNumero($item1->aumento ?? 0)*-1;
                $disminucion1 = $this->limpiarNumero($item1->disminucion ?? 0)*-1;
                $saldoActual1 = $this->limpiarNumero($item1->saldo_actual ?? 0)*-1;

                $saldoAnterior2 = $this->limpiarNumero($item2->saldo_anterior ?? 0);
                $aumento2 = $this->limpiarNumero($item2->aumento ?? 0);
                $disminucion2 = $this->limpiarNumero($item2->disminucion ?? 0);
                $saldoActual2 = $this->limpiarNumero($item2->saldo_actual ?? 0);

                if ($operador == '-') {

                    $saldoAnterior = $saldoAnterior1 - $saldoAnterior2;
                    $aumento = $aumento1 - $aumento2;
                    $disminucion = $disminucion1 - $disminucion2;
                    $saldoActual = $saldoActual1 - $saldoActual2;

                } else {

                    $saldoAnterior = $saldoAnterior1 + $saldoAnterior2;
                    $aumento = $aumento1 + $aumento2;
                    $disminucion = $disminucion1 + $disminucion2;
                    $saldoActual = $saldoActual1 + $saldoActual2;
                }

                $resultado[$anio][$cuenta] = (object)[
                    'cuenta' => $item1->cuenta ?? $item2->cuenta,
                    'descripcion' => $item1->descripcion ?? $item2->descripcion,
                    'saldo_anterior' => number_format($saldoAnterior, 2, '.', ','),
                    'aumento' => number_format($aumento, 2, '.', ','),
                    'disminucion' => number_format($disminucion, 2, '.', ','),
                    'saldo_actual' => number_format($saldoActual, 2, '.', ','),
                ];
            }
        }

        return $resultado;
    }

      function combinarNotasSituacion($notas1, $notas2, $operador = '+')
    {
        $resultado = [];

        // Indexar notas2 por cuenta
        $notas2PorCuenta = [];
        foreach ($notas2 as $fila) {
            $notas2PorCuenta[$fila->cuenta] = $fila;
        }

        foreach ($notas1 as $fila1) {

            $cuenta = $fila1->cuenta;

            $fila2 = $notas2PorCuenta[$cuenta] ?? (object)[
                'saldo_anio_actual' => 0,
                'saldo_anio_anterior' => 0
            ];

            if ($operador == '-') {
                $nuevoActual = $fila1->saldo_anio_actual - $fila2->saldo_anio_actual;
                $nuevoAnterior = $fila1->saldo_anio_anterior - $fila2->saldo_anio_anterior;
            } else {
                $nuevoActual = $fila1->saldo_anio_actual + $fila2->saldo_anio_actual;
                $nuevoAnterior = $fila1->saldo_anio_anterior + $fila2->saldo_anio_anterior;
            }

            $resultado[] = (object)[
                'cuenta' => $fila1->cuenta,
                'descripcion' => $fila1->descripcion,
                'saldo_anio_actual' => $nuevoActual,
                'saldo_anio_anterior' => $nuevoAnterior
            ];
        }

        // Agregar cuentas que solo existan en notas2
        $cuentasNotas1 = collect($notas1)->pluck('cuenta')->toArray();

        foreach ($notas2 as $fila2) {

            if (!in_array($fila2->cuenta, $cuentasNotas1)) {

                if ($operador == '-') {
                    $actual = -$fila2->saldo_anio_actual;
                    $anterior = -$fila2->saldo_anio_anterior;
                } else {
                    $actual = $fila2->saldo_anio_actual;
                    $anterior = $fila2->saldo_anio_anterior;
                }

                $resultado[] = (object)[
                    'cuenta' => $fila2->cuenta,
                    'descripcion' => $fila2->descripcion,
                    'saldo_anio_actual' => $actual,
                    'saldo_anio_anterior' => $anterior
                ];
            }
        }

        return collect($resultado);
    }

    public function combinarInformesCostosMes($inf1, $inf2, $operador = '+')
    {
        $resultado = [];

        // Convertir inf2 a indexado
        $inf2PorCuenta = collect($inf2)->keyBy('cuenta');

        // 🔥 Detectar meses dinámicamente
        $camposExcluir = ['cuenta','cuenta_limpia','descripcion','total_acumulado'];

        $primerRegistro = collect($inf1)->first();

        $meses = collect($primerRegistro)
            ->keys()
            ->diff($camposExcluir)
            ->values()
            ->toArray();

        foreach ($inf1 as $fila1) {

            $cuenta = $fila1->cuenta;

            $fila2 = $inf2PorCuenta[$cuenta] ?? null;

            $nuevaFila = [
                "cuenta" => $cuenta,
                "cuenta_limpia" => $fila1->cuenta_limpia,
                "descripcion" => $fila1->descripcion
            ];

            $totalAcumulado = 0;

            foreach ($meses as $mes) {

                $valor1 = (float) ($fila1->$mes ?? 0);
                $valor2 = (float) ($fila2->$mes ?? 0);

                if ($operador == '-') {
                    $nuevo = $valor1 - $valor2;
                } else {
                    $nuevo = $valor1 + $valor2;
                }

                $nuevaFila[$mes] = $nuevo;
                $totalAcumulado += $nuevo;
            }

            $nuevaFila['total_acumulado'] = $totalAcumulado;

            $resultado[] = (object) $nuevaFila;
        }

        // 🔥 Cuentas que solo están en inf2

        $cuentasInf1 = collect($inf1)->pluck('cuenta')->toArray();

        foreach ($inf2 as $fila2) {

            if (!in_array($fila2->cuenta, $cuentasInf1)) {

                $nuevaFila = [
                    "cuenta" => $fila2->cuenta,
                    "cuenta_limpia" => $fila2->cuenta_limpia,
                    "descripcion" => $fila2->descripcion
                ];

                $totalAcumulado = 0;

                foreach ($meses as $mes) {

                    $valor = (float) ($fila2->$mes ?? 0);

                    if ($operador == '-') {
                        $valor *= -1;
                    }

                    $nuevaFila[$mes] = $valor;
                    $totalAcumulado += $valor;
                }

                $nuevaFila['total_acumulado'] = $totalAcumulado;

                $resultado[] = (object) $nuevaFila;
            }
        }

        return collect($resultado)->sortBy('cuenta')->values();
    }

}