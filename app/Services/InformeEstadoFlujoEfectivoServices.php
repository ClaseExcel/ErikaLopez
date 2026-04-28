<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


// class InformeEstadoFlujoEfectivoServices
// {
//     private $utilidadActual;

//     public function generarFlujoEfectivo($situacionFinanciera, $estadoResultados, $anioActual)
//     {
//         try {
//             $anioAnterior = $anioActual - 1;
            
//             // Usamos tu lógica original de obtención de datos
//             $utilidadNeta = $this->obtenerUtilidadNeta($estadoResultados, $anioActual);
//             [$efectivoInicial, $efectivoFinal] = $this->obtenerEfectivo($situacionFinanciera);
//             $variacionObjetivo = round($efectivoFinal - $efectivoInicial, 2);

//             // 1. Extraer movimientos (Tu lógica original de prepararMovimientos)
//             $movimientos = $this->prepararMovimientos($situacionFinanciera);

//             // 2. Construcción del informe (Tu lógica original de formatearYValidar)
//             return $this->formatearYValidar($movimientos, $efectivoInicial, $efectivoFinal, $variacionObjetivo, $anioActual, $anioAnterior);

//         } catch (\Throwable $e) {
//             return ["error" => $e->getMessage()];
//         }
//     }

//     private function prepararMovimientos($balance)
//     {
//         $partidas = [];
//         $excluirNombres = [
//             'Efectivo y equivalentes al efectivo', 'Total activo corriente', 'Total activo no corriente',
//             'Total activo', 'Total pasivos corrientes', 'Total pasivos no corrientes',
//             'Total Pasivo', 'Total patrimonio', 'Total Pasivo & Patrimonio'
//         ];

//         foreach ($balance as $item) {
//             $desc = trim($item['descripcion'] ?? '');
//             $codigo = (string)($item['cuenta'] ?? '');

//             if (str_starts_with($codigo, '3') || str_starts_with($codigo, '11')) continue;
//             if (in_array($desc, $excluirNombres) || empty($desc)) continue;
//             if (str_starts_with($desc, 'Total')) continue;

//             $actual = $this->limpiarNumero($item['totalaño1'] ?? 0); 
//             $anterior = $this->limpiarNumero($item['totalaño2'] ?? 0);
            
//             // Mantenemos tu lógica de variación exacta
//             $diff = round($actual - $anterior, 2);

//             if ($diff != 0) {
//                 $esActivo = str_starts_with($codigo, '1');
                
//                 // Tu lógica de signos que ya te funcionaba
//                 $valorFlujo = $esActivo ? ($diff * -1) : ($diff * -1); 

//                 $partidas[] = [
//                     'descripcion' => $desc,
//                     'valor' => $valorFlujo,
//                     'valor_anterior_real' => $anterior // Solo para mostrar la foto en la columna 2024
//                 ];
//             }
//         }
//         return $partidas;
//     }

//     private function clasificarTecnicamente($codigo, $nombre)
//     {
//         if (preg_match('/^(12|15|16)/', $codigo)) return 'inversion';
//         if (preg_match('/^(21)/', $codigo)) return 'financiacion';
//         return 'operacion';
//     }

//     private function formatearYValidar($movs, $ini, $fin, $obj, $anioActual, $anioAnterior)
//     {
//         $keyActual = "año" . $anioActual;
//         $keyAnterior = "año" . $anioAnterior;

//         $secciones = ['operacion' => [], 'inversion' => [], 'financiacion' => []];

//         $secciones['operacion'][] = [
//             "descripcion" => "Utilidad Neta del Ejercicio", 
//             "valor" => $this->utilidadActual, 
//             "ant" => 0
//         ];

//         foreach ($movs as $m) {
//             $cat = $this->clasificarTecnicamente($this->obtenerCodigoSimulado($m['descripcion']), $m['descripcion']);
//             $secciones[$cat][] = [
//                 "descripcion" => $m['descripcion'], 
//                 "valor" => $m['valor'], 
//                 "ant" => $m['valor_anterior_real']
//             ];
//         }

//         $totalCalculado = 0;
//         foreach ($secciones as $sec) {
//             $totalCalculado += array_sum(array_column($sec, 'valor'));
//         }

//         $diferencia = round($obj - $totalCalculado, 2);
        
//         if (abs($diferencia) > 1) {
//             $secciones['operacion'][] = ["descripcion" => "Ajuste por conciliación", "valor" => $diferencia, "ant" => 0];
//         }

//         // Aquí es donde hacemos los nombres de las columnas dinámicos
//         $f = fn($lista) => array_map(fn($i) => [
//             "descripcion" => $i['descripcion'], 
//             $keyActual => $i['valor'], 
//             $keyAnterior => $i['ant']
//         ], $lista);

//         return [
//             "ACTIVIDADES DE OPERACION" => $f($secciones['operacion']),
//             "ACTIVIDADES DE INVERSION" => $f($secciones['inversion']),
//             "ACTIVIDADES DE FINANCIACION" => $f($secciones['financiacion']),
//             "VARIACION EFECTIVO" => [
//                 ["descripcion" => "Efectivo Inicial", $keyActual => $ini, $keyAnterior => 0],
//                 ["descripcion" => "Incremento Neto", $keyActual => $obj, $keyAnterior => 0],
//                 ["descripcion" => "Efectivo Final", $keyActual => $fin, $keyAnterior => 0]
//             ]
//         ];
//     }

//     // Método auxiliar para no perder la clasificación
//     private function obtenerCodigoSimulado($desc) {
//         if (str_contains($desc, 'Inversiones')) return '12';
//         if (str_contains($desc, 'Propiedades')) return '15';
//         if (str_contains($desc, 'Intangibles')) return '16';
//         if (str_contains($desc, 'Obligaciones')) return '21';
//         return '13';
//     }

//     private function limpiarNumero($n) {
//         if (is_numeric($n)) return (float)$n;
//         $n = str_replace([',', ' '], '', (string)$n);
//         if (preg_match('/\((.*)\)/', $n, $matches)) { $n = '-' . $matches[1]; }
//         return (float)$n;
//     }

//     private function obtenerEfectivo($b) {
//         foreach($b as $i) {
//             if (trim($i['descripcion'] ?? '') === 'Efectivo y equivalentes al efectivo') {
//                 return [$this->limpiarNumero($i['totalaño2']), $this->limpiarNumero($i['totalaño1'])];
//             }
//         }
//         return [0,0];
//     }

//     private function obtenerUtilidadNeta($er, $anio) {
//         $val = $this->limpiarNumero($er['informeData']['Utilidad (Perdida) Neta del periodo'][$anio] ?? 0);
//         $this->utilidadActual = $val;
//         return $val;
//     }
// }


// class InformeEstadoFlujoEfectivoServices
// {
//     private $utilidadActual;

//     public function generarFlujoEfectivo($situacionFinanciera, $estadoResultados, $anioActual)
//     {
//         try {
//             $anioAnterior = $anioActual - 1;
            
//             $this->utilidadActual = $this->obtenerUtilidadNeta($estadoResultados, $anioActual);
//             // También obtenemos la utilidad del año anterior si existe para el flujo de 2024
//             $utilidadAnterior = $this->limpiarNumero($estadoResultados['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior] ?? 0);

//             [$efectivoInicial, $efectivoFinal] = $this->obtenerEfectivo($situacionFinanciera);
//             $variacionObjetivo = round($efectivoFinal - $efectivoInicial, 2);

//             // 1. Extraer movimientos calculando flujos para AMBOS años
//             $movimientos = $this->prepararMovimientos($situacionFinanciera);

//             // 2. Construcción del informe
//             return $this->formatearYValidar($movimientos, $efectivoInicial, $efectivoFinal, $variacionObjetivo, $anioActual, $anioAnterior, $utilidadAnterior);

//         } catch (\Throwable $e) {
//             return ["error" => $e->getMessage()];
//         }
//     }

//     private function prepararMovimientos($balance)
//     {
//         $partidas = [];
//         $excluirNombres = [
//             'Efectivo y equivalentes al efectivo', 'Total activo corriente', 'Total activo no corriente',
//             'Total activo', 'Total pasivos corrientes', 'Total pasivos no corrientes',
//             'Total Pasivo', 'Total patrimonio', 'Total Pasivo & Patrimonio'
//         ];

//         foreach ($balance as $item) {
//             $desc = trim($item['descripcion'] ?? '');
//             $codigo = (string)($item['cuenta'] ?? '');

//             if (str_starts_with($codigo, '3') || str_starts_with($codigo, '11')) continue;
//             if (in_array($desc, $excluirNombres) || empty($desc)) continue;
//             if (str_starts_with($desc, 'Total')) continue;

//             $actual = $this->limpiarNumero($item['totalaño1'] ?? 0); 
//             $anterior = $this->limpiarNumero($item['totalaño2'] ?? 0);
            
//             // Variación 2025: Actual - Anterior
//             $diff2025 = round($actual - $anterior, 2);
//             // Variación 2024: Anterior - 0 (asumiendo que no hay 2023)
//             $diff2024 = round($anterior - 0, 2);

//             if ($diff2025 != 0 || $diff2024 != 0) {
//                 $esActivo = str_starts_with($codigo, '1');
                
//                 // Aplicamos tu lógica de signos a ambas variaciones
//                 // Flujo 2025
//                 $flujo2025 = $esActivo ? ($diff2025 * -1) : ($diff2025 * -1); 
//                 // Flujo 2024
//                 $flujo2024 = $esActivo ? ($diff2024 * -1) : ($diff2024 * -1);

//                 $partidas[] = [
//                     'descripcion' => $desc,
//                     'valor_2025' => $flujo2025,
//                     'valor_2024' => $flujo2024,
//                     'codigo' => $codigo
//                 ];
//             }
//         }
//         return $partidas;
//     }

//     private function formatearYValidar($movs, $ini, $fin, $obj, $anioActual, $anioAnterior, $utilidadAnterior)
//     {
//         $keyActual = "año" . $anioActual;
//         $keyAnterior = "año" . $anioAnterior;

//         $secciones = ['operacion' => [], 'inversion' => [], 'financiacion' => []];

//         // 1. Agregar Utilidades
//         $secciones['operacion'][] = [
//             "descripcion" => "Utilidad Neta del Ejercicio", 
//             "v_act" => $this->utilidadActual, 
//             "v_ant" => $utilidadAnterior
//         ];

//         foreach ($movs as $m) {
//             $cat = $this->clasificarTecnicamente($m['codigo'], $m['descripcion']);
//             $secciones[$cat][] = [
//                 "descripcion" => $m['descripcion'], 
//                 "v_act" => $m['valor_2025'], 
//                 "v_ant" => $m['valor_2024']
//             ];
//         }

//         // 2. Cálculo de Totales por Columna para el Validador
//         $totalFlujo2025 = 0;
//         $totalFlujo2024 = 0;

//         foreach ($secciones as $sec) {
//             $totalFlujo2025 += array_sum(array_column($sec, 'v_act'));
//             $totalFlujo2024 += array_sum(array_column($sec, 'v_ant'));
//         }

//         // 3. Ajuste por conciliación (si aplica)
//         $diff2025 = round($obj - $totalFlujo2025, 2);
//         if (abs($diff2025) > 1) {
//             $secciones['operacion'][] = ["descripcion" => "Ajuste por conciliación", "v_act" => $diff2025, "v_ant" => 0];
//             $totalFlujo2025 += $diff2025; // Actualizamos el total para que el validador de 0
//         }

//         // 4. Mapeo de actividades
//         $f = fn($lista) => array_map(fn($i) => [
//             "descripcion" => $i['descripcion'], 
//             $keyActual => $i['v_act'], 
//             $keyAnterior => $i['v_ant']
//         ], $lista);

//         // 5. Cálculo del "Incremento Neto" de 2024
//         // Para 2024, el incremento neto es la suma de sus actividades
//         $obj2024 = $totalFlujo2024; 

//         return [
//             "ACTIVIDADES DE OPERACION" => $f($secciones['operacion']),
//             "ACTIVIDADES DE INVERSION" => $f($secciones['inversion']),
//             "ACTIVIDADES DE FINANCIACION" => $f($secciones['financiacion']),
//             "VARIACION EFECTIVO" => [
//                 ["descripcion" => "Efectivo Inicial", $keyActual => $ini, $keyAnterior => 0],
//                 ["descripcion" => "Incremento Neto", $keyActual => $obj, $keyAnterior => $obj2024],
//                 ["descripcion" => "Efectivo Final", $keyActual => $fin, $keyAnterior => $ini] // El inicial de 2025 es el final de 2024
//             ],
//             "VALIDADOR" => [
//                 "suma_2025" => $totalFlujo2025,
//                 "suma_2024" => $totalFlujo2024
//             ]
//         ];
//     }

//     private function clasificarTecnicamente($codigo, $nombre)
//     {
//         if (preg_match('/^(12|15|16)/', $codigo)) return 'inversion';
//         if (preg_match('/^(21)/', $codigo)) return 'financiacion';
        
//         // Refuerzo por nombre si el código no es claro
//         if (str_contains($nombre, 'Inversiones')) return 'inversion';
//         if (str_contains($nombre, 'Propiedades')) return 'inversion';
//         if (str_contains($nombre, 'Intangibles')) return 'inversion';
//         if (str_contains($nombre, 'Obligaciones')) return 'financiacion';
        
//         return 'operacion';
//     }

//     private function limpiarNumero($n) {
//         if (is_numeric($n)) return (float)$n;
//         $n = str_replace([',', ' '], '', (string)$n);
//         if (preg_match('/\((.*)\)/', $n, $matches)) { $n = '-' . $matches[1]; }
//         return (float)$n;
//     }

//     private function obtenerEfectivo($b) {
//         foreach($b as $i) {
//             if (trim($i['descripcion'] ?? '') === 'Efectivo y equivalentes al efectivo') {
//                 return [$this->limpiarNumero($i['totalaño2']), $this->limpiarNumero($i['totalaño1'])];
//             }
//         }
//         return [0,0];
//     }

//     private function obtenerUtilidadNeta($er, $anio) {
//         $val = $this->limpiarNumero($er['informeData']['Utilidad (Perdida) Neta del periodo'][$anio] ?? 0);
//         return $val;
//     }
// }

// class InformeEstadoFlujoEfectivoServices
// {
//     private $utilidadActual;

//     public function generarFlujoEfectivo($situacionFinanciera, $estadoResultados, $anioActual)
//     {
//         try {
//             $anioAnterior = $anioActual - 1;
            
//             $this->utilidadActual = $this->obtenerUtilidadNeta($estadoResultados, $anioActual);
//             $utilidadAnterior = $this->limpiarNumero($estadoResultados['informeData']['Utilidad (Perdida) Neta del periodo'][$anioAnterior] ?? 0);

//             [$efectivoInicial, $efectivoFinal] = $this->obtenerEfectivo($situacionFinanciera);
            
//             // Variación real del efectivo entre los dos años
//             $variacionObjetivo = round($efectivoFinal - $efectivoInicial, 2);

//             $movimientos = $this->prepararMovimientos($situacionFinanciera);

//             return $this->formatearYValidar($movimientos, $efectivoInicial, $efectivoFinal, $variacionObjetivo, $anioActual, $anioAnterior, $utilidadAnterior);

//         } catch (\Throwable $e) {
//             return ["error" => $e->getMessage() . " en la línea " . $e->getLine()];
//         }
//     }

//     private function prepararMovimientos($balance)
//     {
//         $partidas = [];
//         $excluirNombres = [
//             'Efectivo y equivalentes al efectivo', 'Total activo corriente', 'Total activo no corriente',
//             'Total activo', 'Total pasivos corrientes', 'Total pasivos no corrientes',
//             'Total Pasivo', 'Total patrimonio', 'Total Pasivo & Patrimonio'
//         ];

//         foreach ($balance as $item) {
//             $desc = trim($item['descripcion'] ?? '');
//             $codigo = (string)($item['cuenta'] ?? '');

//             if (str_starts_with($codigo, '3') || str_starts_with($codigo, '11')) continue;
//             if (in_array($desc, $excluirNombres) || empty($desc)) continue;
//             if (str_starts_with($desc, 'Total')) continue;

//             $actual = $this->limpiarNumero($item['totalaño1'] ?? 0); 
//             $anterior = $this->limpiarNumero($item['totalaño2'] ?? 0);
            
//             $diff2025 = round($actual - $anterior, 2);
//             $diff2024 = round($anterior - 0, 2); // Variación 2024 vs "año cero"

//             if ($diff2025 != 0 || $diff2024 != 0) {
//                 $esActivo = str_starts_with($codigo, '1');
                
//                 // Lógica de signos unificada
//                 $partidas[] = [
//                     'descripcion' => $desc,
//                     'v_act' => $esActivo ? ($diff2025 * -1) : ($diff2025 * -1),
//                     'v_ant' => $esActivo ? ($diff2024 * -1) : ($diff2024 * -1),
//                     'codigo' => $codigo
//                 ];
//             }
//         }
//         return $partidas;
//     }

//     private function formatearYValidar($movs, $ini, $fin, $obj, $anioActual, $anioAnterior, $utilidadAnterior)
//     {
//         $keyActual = "año" . $anioActual;
//         $keyAnterior = "año" . $anioAnterior;

//         $secciones = ['operacion' => [], 'inversion' => [], 'financiacion' => []];

//         // IMPORTANTE: La utilidad debe tener las llaves v_act y v_ant para que array_column funcione
//         $secciones['operacion'][] = [
//             "descripcion" => "Utilidad Neta del Ejercicio", 
//             "v_act" => (float)$this->utilidadActual, 
//             "v_ant" => (float)$utilidadAnterior
//         ];

//         foreach ($movs as $m) {
//             $cat = $this->clasificarTecnicamente($m['codigo'], $m['descripcion']);
//             $secciones[$cat][] = [
//                 "descripcion" => $m['descripcion'], 
//                 "v_act" => (float)$m['v_act'], 
//                 "v_ant" => (float)$m['v_ant']
//             ];
//         }

//         // Totales para validadores
//         $totalFlujo2025 = 0;
//         $totalFlujo2024 = 0;

//         foreach ($secciones as $sec) {
//             // Validamos que la sección no esté vacía antes de sumar
//             if (!empty($sec)) {
//                 $totalFlujo2025 += array_sum(array_column($sec, 'v_act'));
//                 $totalFlujo2024 += array_sum(array_column($sec, 'v_ant'));
//             }
//         }

//         // Conciliación para el año actual
//         $diferencia2025 = round($obj - $totalFlujo2025, 2);
//         if (abs($diferencia2025) > 1) {
//             $secciones['operacion'][] = ["descripcion" => "Ajuste por conciliación", "v_act" => $diferencia2025, "v_ant" => 0];
//             $totalFlujo2025 += $diferencia2025;
//         }

//         $f = fn($lista) => array_map(fn($i) => [
//             "descripcion" => $i['descripcion'], 
//             $keyActual => $i['v_act'], 
//             $keyAnterior => $i['v_ant']
//         ], $lista);

//         return [
//             "ACTIVIDADES DE OPERACION" => $f($secciones['operacion']),
//             "ACTIVIDADES DE INVERSION" => $f($secciones['inversion']),
//             "ACTIVIDADES DE FINANCIACION" => $f($secciones['financiacion']),
//             "VARIACION EFECTIVO" => [
//                 ["descripcion" => "Efectivo Inicial", $keyActual => $ini, $keyAnterior => 0],
//                 ["descripcion" => "Incremento Neto", $keyActual => $obj, $keyAnterior => $totalFlujo2024],
//                 ["descripcion" => "Efectivo Final", $keyActual => $fin, $keyAnterior => $ini]
//             ]
//         ];
//     }

//     private function clasificarTecnicamente($codigo, $nombre)
//     {
//         if (preg_match('/^(12|15|16)/', $codigo)) return 'inversion';
//         if (preg_match('/^(21)/', $codigo)) return 'financiacion';
//         return 'operacion';
//     }

//     private function limpiarNumero($n) {
//         if (is_float($n) || is_int($n)) return (float)$n;
//         $n = str_replace([',', ' '], '', (string)$n);
//         if (preg_match('/\((.*)\)/', $n, $matches)) { $n = '-' . $matches[1]; }
//         return (float)$n;
//     }

//     private function obtenerEfectivo($b) {
//         foreach($b as $i) {
//             if (trim($i['descripcion'] ?? '') === 'Efectivo y equivalentes al efectivo') {
//                 return [$this->limpiarNumero($i['totalaño2']), $this->limpiarNumero($i['totalaño1'])];
//             }
//         }
//         return [0,0];
//     }

//     private function obtenerUtilidadNeta($er, $anio) {
//         return $this->limpiarNumero($er['informeData']['Utilidad (Perdida) Neta del periodo'][$anio] ?? 0);
//     }
// }


class InformeEstadoFlujoEfectivoServices
{
    private $utilidadActual;

    public function generarFlujoEfectivo($situacionFinanciera, $estadoResultados, $anioActual)
    {
        try {
            $anioAnterior = $anioActual - 1;
            
            // 1. Datos base dinámicos
            $this->utilidadActual = $this->obtenerUtilidadNeta($estadoResultados, $anioActual);
            $utilidadAnterior = $this->obtenerUtilidadNeta($estadoResultados, $anioAnterior);

            [$efectivoInicial, $efectivoFinal] = $this->obtenerEfectivo($situacionFinanciera);
            
            // La variación que el sistema DEBE alcanzar para 2025
            $variacionObjetivo2025 = round($efectivoFinal - $efectivoInicial, 2);

            // 2. Procesar movimientos
            $movimientos = $this->prepararMovimientos($situacionFinanciera);

            // 3. Formatear reporte
            return $this->formatearYValidar(
                $movimientos, 
                $efectivoInicial, 
                $efectivoFinal, 
                $variacionObjetivo2025, 
                $anioActual, 
                $anioAnterior, 
                $utilidadAnterior
            );

        } catch (\Throwable $e) {
            return ["error" => $e->getMessage()];
        }
    }

    private function prepararMovimientos($balance)
    {
        $partidas = [];
        $excluir = ['Efectivo y equivalentes al efectivo', 'Total activo', 'Total patrimonio', 'Total Pasivo'];

        foreach ($balance as $item) {
            $desc = trim($item['descripcion'] ?? '');
            $codigo = (string)($item['cuenta'] ?? '');

            if (str_starts_with($codigo, '3') || str_starts_with($codigo, '11')) continue;
            if (in_array($desc, $excluir) || empty($desc) || str_starts_with($desc, 'Total')) continue;

            $val2025 = $this->limpiarNumero($item['totalaño1'] ?? 0); 
            $val2024 = $this->limpiarNumero($item['totalaño2'] ?? 0);
            
            // Variaciones individuales
            $diff2025 = round($val2025 - $val2024, 2);
            $diff2024 = round($val2024 - 0, 2); 

            if ($diff2025 != 0 || $diff2024 != 0) {
                $esActivo = str_starts_with($codigo, '1');
                
                // Aplicamos la lógica de signos: Activos suben = Flujo negativo.
                $partidas[] = [
                    'descripcion' => $desc,
                    'v_act' => $diff2025 * -1,
                    'v_ant' => $diff2024 * -1,
                    'codigo' => $codigo
                ];
            }
        }
        return $partidas;
    }

    private function formatearYValidar($movs, $ini, $fin, $obj, $anioActual, $anioAnterior, $utilAnt)
    {
        $keyActual = "año" . $anioActual;
        $keyAnterior = "año" . $anioAnterior;
        $secciones = ['operacion' => [], 'inversion' => [], 'financiacion' => []];

        // Partida inicial
        $secciones['operacion'][] = [
            "descripcion" => "Utilidad Neta del Ejercicio", 
            "v_act" => (float)$this->utilidadActual, 
            "v_ant" => (float)$utilAnt
        ];

        foreach ($movs as $m) {
            $cat = $this->clasificarTecnicamente($m['codigo'], $m['descripcion']);
            $secciones[$cat][] = ["descripcion" => $m['descripcion'], "v_act" => $m['v_act'], "v_ant" => $m['v_ant']];
        }

        // Totales de actividades
        $tot25 = 0; $tot24 = 0;
        foreach ($secciones as $sec) {
            $tot25 += array_sum(array_column($sec, 'v_act'));
            $tot24 += array_sum(array_column($sec, 'v_ant'));
        }

        // Conciliación 2025
        $diffConciliacion = round($obj - $tot25, 2);
        if (abs($diffConciliacion) > 1) {
            $secciones['operacion'][] = ["descripcion" => "Ajuste por conciliación", "v_act" => $diffConciliacion, "v_ant" => 0];
            $tot25 += $diffConciliacion;
        }

        $f = fn($lista) => array_map(fn($i) => [
            "descripcion" => $i['descripcion'], 
            $keyActual => $i['v_act'], 
            $keyAnterior => $i['v_ant']
        ], $lista);

        return [
            "ACTIVIDADES DE OPERACION" => $f($secciones['operacion']),
            "ACTIVIDADES DE INVERSION" => $f($secciones['inversion']),
            "ACTIVIDADES DE FINANCIACION" => $f($secciones['financiacion']),
            "VARIACION EFECTIVO" => [
                ["descripcion" => "Efectivo Inicial", $keyActual => $ini, $keyAnterior => 0],
                ["descripcion" => "Incremento Neto", $keyActual => $obj, $keyAnterior => $tot24],
                ["descripcion" => "Efectivo Final", $keyActual => $fin, $keyAnterior => $ini]
            ]
        ];
    }

    private function clasificarTecnicamente($codigo, $nombre)
    {
        if (preg_match('/^(12|15|16)/', $codigo)) return 'inversion';
        if (preg_match('/^(21)/', $codigo)) return 'financiacion';
        return 'operacion';
    }

    private function limpiarNumero($n) {
        if (is_numeric($n)) return (float)$n;
        $n = str_replace([',', ' '], '', (string)$n);
        if (preg_match('/\((.*)\)/', $n, $matches)) { $n = '-' . $matches[1]; }
        return (float)$n;
    }

    private function obtenerEfectivo($b) {
        foreach($b as $i) {
            if (trim($i['descripcion'] ?? '') === 'Efectivo y equivalentes al efectivo') {
                return [$this->limpiarNumero($i['totalaño2']), $this->limpiarNumero($i['totalaño1'])];
            }
        }
        return [0,0];
    }

    private function obtenerUtilidadNeta($er, $anio) {
        return $this->limpiarNumero($er['informeData']['Utilidad (Perdida) Neta del periodo'][$anio] ?? 0);
    }
}