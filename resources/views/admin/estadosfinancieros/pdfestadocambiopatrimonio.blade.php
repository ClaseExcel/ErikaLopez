<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$titulo}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .titulo {
            text-align: center;
            font-weight: bold;
            color: #919396;
        }
        .subtitulo {
            text-align: center;
            font-weight: bold;
        }
        .fecha {
            text-align: center;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        .total-patrimonio {
            background-color: #D9D9D9 !important;
            color: #919396 !important;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .text-init{
            text-align: left;
        }
        .cabecera {
            background-color: #919396 !important;
            color: #fffdfd !important;
            font-weight: bold;
        }
        .tabla-integrales {
            text-align: justify;
            margin-bottom: 2px;
            margin: 0cm;
            page-break-inside: avoid;
            border-collapse: collapse;
            margin: 0 !important; /* Eliminar márgenes para evitar recortes */
            /* Opcional: para eliminar espacio entre bordes de celdas */
        }

        .tabla-integrales th,
        .tabla-integrales td {
            border: none;
            /* Elimina los bordes de las celdas */
        }

        .firmas {
            text-align: center;
            margin-top: 50px;
            page-break-inside: avoid;
            
        }
        .firma {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .firma2,
        .firma2.no-image {
            height: 80px;
            width: 100px;
            margin: 0 auto;
            overflow: visible; /* permite que la imagen se salga si lo necesita */
            position: relative;
            display: block; /* quitamos flex para permitir control con márgenes */
        }

        .firma2 img {
            max-height: 100%;
            max-width: 100%;
            display: block;
            margin: 0 auto;
            position: relative;
            bottom: -8px; /* ¡clave! empuja la imagen hacia abajo para tocar el texto */
            opacity: 0.85;
            mix-blend-mode: multiply;
        }
        .firma2.no-image {
            border-bottom: 1px solid #000;
            height: 80px; /* mismo alto que con imagen */
            width: 100px;
            margin: 0 auto;
        }
        .firmatexto {
            margin-top: -4px; /* se superpone ligeramente con la imagen */
            font-size: 11px;
            color: #919396;
            line-height: 1.1;
        }

       
        .firma2.no-image {
            border-bottom: 1px solid #000; /* Solo borde inferior para contenedores sin imagen */
            height: 70px; /* Asegura que ocupe el mismo espacio */
            width: 80%; /* Aumenta el ancho para hacer la línea más larga */
            margin: 0 auto; /* Ajusta la posición para centrarla si es necesario */
        }
        .firma2.no-image + .firmatexto {
            margin-top: 10px !important; /* o el valor que necesites para dar más separación */
        }
        .firma2 img {
            opacity: 0.85; /* transparencia ligera */
            mix-blend-mode: multiply; /* ayuda a “fusionar” fondo blanco */
        }

    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse; border: none; padding: 0 !important;">
        <tr>
            <td style="border: none;width: 25%;"></td>
            <td style="text-align: left; vertical-align: middle; border: none;width: 50%;">
                <h2 class="titulo" style="margin: 0;">{{ $compania }}</h2>
                <h3 class="subtitulo" style="margin: 0;">NIT - {{ $nit }}</h3>
                <h4 class="subtitulo" style="margin: 0;">{{ $titulo }}</h4>
                <h5 class="fecha" style="margin: 0;">
                    A {{ \Carbon\Carbon::parse($fecha_real)->locale('es')->translatedFormat('d \\de F \\de Y') }}
                </h5>
            </td>
            @if($logocliente != '*')
            <td style="text-align: right; vertical-align: middle; border: none;width: 25%;">
                <img id="logo" src="data:image/jpeg;base64,{{ $logocliente }}" 
                     style="max-width: 100px; max-height: 100px;">
            </td>
            @else
                <td style="text-align: right; vertical-align: middle; border: none;width: 25%;">
                    <img id="logo" src="" 
                        style="max-width: 100px; max-height: 100px;">
                </td>
            @endif
        </tr>
    </table>
    @if ($tipoinforme == 1)
        <table class="table table-sm table-striped table-bordered datatable-informe w-100">
            <thead class="card-header">
                <tr>
                    <th rowspan="2" class="text-center">Concepto</th>
                    <th colspan="4" class="text-center">Año {{ array_key_first($informe) }}</th>
                    <th colspan="4" class="text-center">Año {{ array_key_last($informe) }}</th>
                </tr>
                <tr>
                    <th>Saldo Anterior</th>
                    <th>Aumento</th>
                    <th>Disminución</th>
                    <th>Saldo Actual</th>
                    <th>Saldo Anterior</th>
                    <th>Aumento</th>
                    <th>Disminución</th>
                    <th>Saldo Actual</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $nombresCuentas = [
                                '31' => 'CAPITAL SOCIAL',
                                '33' => 'RESERVAS',
                                '32' => 'SUPERÁVIT DE CAPITAL',
                                '34' => 'GANANCIAS ACUMULADAS',
                                '35' => 'DIVIDENDOS O PARTICIPACIÓN',
                                '36' => 'RESULTADOS DEL EJERCICIO',
                                '37' => 'RESULTADOS DE EJERCICIOS ANTERIORES',
                                '362' => 'RESULTADOS DEL ACUMULADOS',
                                'Total Patrimonio' => 'TOTAL PATRIMONIO'
                            ];
                    $anioActual = array_key_first($informe);
                    $anioAnterior = array_key_last($informe);
                @endphp

                @foreach ($informe[$anioActual] as $codigo => $itemActual)
                    @php
                        $itemAnterior = $informe[$anioAnterior][$codigo] ?? null;

                        // Normalizar valores a números
                        $valoresActual = [
                            (float) str_replace(',', '', $itemActual->saldo_anterior ?? 0),
                            (float) str_replace(',', '', $itemActual->aumento ?? 0),
                            (float) str_replace(',', '', $itemActual->disminucion ?? 0),
                            (float) str_replace(',', '', $itemActual->saldo_actual ?? 0),
                        ];

                        $valoresAnterior = $itemAnterior ? [
                            (float) str_replace(',', '', $itemAnterior->saldo_anterior ?? 0),
                            (float) str_replace(',', '', $itemAnterior->aumento ?? 0),
                            (float) str_replace(',', '', $itemAnterior->disminucion ?? 0),
                            (float) str_replace(',', '', $itemAnterior->saldo_actual ?? 0),
                        ] : [0,0,0,0];

                        // Verificar si todos son 0
                        $todosCeros = collect(array_merge($valoresActual, $valoresAnterior))->every(fn($v) => $v == 0);
                    @endphp

                    @if (!$todosCeros)
                        <tr class="{{ $itemActual->cuenta === 'Total Patrimonio' ? 'total-patrimonio' : '' }}">
                            <td>
                                {{ $nombresCuentas[$itemActual->cuenta] ?? $itemActual->cuenta }}
                            </td>

                            {{-- Año actual --}}
                            <td class="text-end">{{ number_format($valoresActual[0], 0) }}</td>
                            <td class="text-end">{{ number_format($valoresActual[1], 0) }}</td>
                            <td class="text-end">{{ number_format($valoresActual[2], 0) }}</td>
                            <td class="text-end">{{ number_format($valoresActual[3], 0) }}</td>

                            {{-- Año anterior --}}
                            @if ($itemAnterior)
                                <td class="text-end">{{ number_format($valoresAnterior[0], 0) }}</td>
                                <td class="text-end">{{ number_format($valoresAnterior[1], 0) }}</td>
                                <td class="text-end">{{ number_format($valoresAnterior[2], 0) }}</td>
                                <td class="text-end">{{ number_format($valoresAnterior[3], 0) }}</td>
                            @else
                                <td colspan="4" class="text-center">-</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <style>
            table {
                font-size: 8px !important;
            }
        </style>
        <table class="table table-sm table-bordered datatable-informe w-100">
            <thead class="card-header">
                <tr>
                    <th>CONCEPTO</th>
                    <th>VALOR ACTUAL {{$mes}} {{$anio}}</th>
                    <th>VALOR ANTERIOR {{$mes}} {{$anio-1}}</th>
                    <th>VARIACIÓN $</th>
                    <th>VARIACIÓN %</th>
                </tr>
            </thead>
            <tbody>
                    {{-- 1. Ciclo principal de secciones --}}
                    @if(is_array($informe))
                    @php 
                        $sumaTotalAct_Actual = 0; 
                        $sumaTotalAct_Anterior = 0; 
                        $objVariacion_Actual = 0;
                        $objVariacion_Anterior = 0;

                        $formatNumber = function($n) {
                            if (round($n, 0) == 0) return '-';
                            $abs = number_format(abs($n), 0, ',', '.');
                            return $n < 0 ? "({$abs})" : $abs;
                        };
                    @endphp

                    @foreach ($informe as $titulo => $filas)
                        <tr>
                            <td colspan="5" style="font-weight: bold; text-align: left; background-color: #f3f3f3;">
                                {{ strtoupper($titulo) }}
                            </td>
                        </tr>

                        @if(is_array($filas))
                            @php
                                // REORDENAMIENTO: Si es Variación Efectivo, forzamos el orden pedido
                                if ($titulo === 'VARIACION EFECTIVO') {
                                    $ordenDeseado = ['Efectivo Inicial', 'Efectivo Final', 'Incremento Neto'];
                                    usort($filas, function($a, $b) use ($ordenDeseado) {
                                        return array_search($a['descripcion'], $ordenDeseado) - array_search($b['descripcion'], $ordenDeseado);
                                    });
                                }
                            @endphp

                            @foreach ($filas as $fila)
                                @php
                                    $descripcion = trim($fila['descripcion'] ?? '');
                                    $keys = array_keys($fila);
                                    $aniosKeys = array_values(array_filter($keys, fn($k) => str_starts_with($k, 'año')));
                                    sort($aniosKeys);

                                    if (count($aniosKeys) < 2) continue;

                                    $actual = floatval($fila[$aniosKeys[1]] ?? 0);
                                    $anterior = floatval($fila[$aniosKeys[0]] ?? 0);
                                    $variacionValor = $actual - $anterior;

                                    // Capturamos el Incremento Neto para validar contra la suma de actividades
                                    if ($descripcion == 'Incremento Neto') {
                                        $objVariacion_Actual = $actual;
                                        $objVariacion_Anterior = $anterior;
                                    }
                                    
                                    // Acumulamos sumas de las secciones de actividades para ambos años
                                    if (in_array($titulo, ['ACTIVIDADES DE OPERACION', 'ACTIVIDADES DE INVERSION', 'ACTIVIDADES DE FINANCIACION'])) {
                                        $sumaTotalAct_Actual += $actual;
                                        $sumaTotalAct_Anterior += $anterior;
                                    }
                                    // Cálculo de la variación porcentual
                                    $variacionPorcentaje = 0;
                                    if ($anterior != 0) {
                                        $variacionPorcentaje = ($variacionValor / abs($anterior)) * 100;
                                    }
                                @endphp

                                <tr>
                                    <td style="{{ !str_contains($descripcion, '(+') ? 'font-weight:bold;' : '' }}">
                                        {{ $descripcion }}
                                    </td>
                                    <td style="text-align:right;">{{ $formatNumber($actual) }}</td>
                                    <td style="text-align:right;">{{ $formatNumber($anterior) }}</td>
                                    <td style="text-align:right;">
                                        @if (round($variacionValor, 0) > 0)
                                            <span style="color: green; font-family: 'DejaVu Sans', sans-serif;">▲</span> {{ number_format($variacionValor, 0, ',', '.') }}
                                        @elseif (round($variacionValor, 0) < 0)
                                            <span style="color: red; font-family: 'DejaVu Sans', sans-serif;">▼</span> ({{ number_format(abs($variacionValor), 0, ',', '.') }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align:right; font-weight:bold;">
                                    @if ($anterior != 0 && round($variacionValor, 0) != 0)
                                        {{ number_format($variacionPorcentaje, 2, ',', '.') }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- 2. BLOQUE FINAL DE VALIDACIÓN --}}
                    @php
                        $diffActual = round($objVariacion_Actual - $sumaTotalAct_Actual, 0);
                        $diffAnterior = round($objVariacion_Anterior - $sumaTotalAct_Anterior, 0);
                    @endphp

                    <tr style="background-color: #e9ecef; font-weight: bold;">
                        <td style="text-align: left;">Validador (Suma Actividades)</td>
                        <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Actual) }}</td>
                        <td style="text-align: right;">{{ $formatNumber($sumaTotalAct_Anterior) }}</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr style="border-top: 2px solid #000; font-weight: bold;">
                        <td style="text-align: left;">Diferencia</td>
                        <td style="text-align: right;">
                            {{ abs($diffActual) <= 1 ? '-' : $formatNumber($diffActual) }}
                        </td>
                        <td style="text-align: right;">
                            {{ abs($diffAnterior) <= 1 ? '-' : $formatNumber($diffAnterior) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                        <tr><td colspan="5" class="text-center">No se pudo cargar la estructura del informe.</td></tr>
                    @endif
            </tbody>
        </table>
    @endif
    <div class="tabla-integrales" style="font-size: 10px; margin: 0; padding: 2px !importante;">
        <div class="firmas">
            <!-- Firma Representante Legal -->
            <div class="firma" style="text-align: center;">
                @if ($representantelegalfirma != '*')
                    <div class="firma2">
                        <img src="data:image/jpeg;base64,{{ $representantelegalfirma }}" alt="Firma Representante Legal">
                    </div>
                @else
                    <div class="firma2 no-image"></div>
                @endif
                <p class="firmatexto">
                    <strong>{{ $representantelegal['representantelegal'] ?? 'Sin datos encontrados' }}</strong><br>
                    Representante legal<br>C.C. {{ $representantelegal['Cedula'] ?? '' }}
                </p>
            </div>
        
            <!-- Firma Contador -->
            <div class="firma" style="text-align: center;">
                @if ($base64Imagefirmacontador != '*')
                    <div class="firma2">
                        <img src="data:image/jpeg;base64,{{ $base64Imagefirmacontador }}" alt="Firma Contador">
                    </div>
                @else
                    <div class="firma2 no-image"></div>
                @endif
                <p class="firmatexto">
                    <strong>{{ ($datoscontador['nombres'] ?? 'Sin datos') . ' ' . ($datoscontador['apellidos'] ?? '') }}</strong><br>
                    Contador Público<br>T.P {{ $datoscontador['tarje_profesional'] ?? '' }}
                </p>
            </div>
        
            <!-- Firma Revisor Fiscal -->
            @if (!empty($representantelegal['revisorfiscal']) && $representantelegal['revisorfiscal'] !== 'Sin datos encontrados')
                <div class="firma" style="text-align: center;">
                    @if ($revisorfiscalfirma != '*')
                        <div class="firma2">
                            <img src="data:image/jpeg;base64,{{ $revisorfiscalfirma }}" alt="Firma Revisor Fiscal">
                        </div>
                    @else
                        <div class="firma2 no-image"></div>
                    @endif
                    <p class="firmatexto">
                        <strong>{{ $representantelegal['revisorfiscal'] }}</strong><br>
                        Revisor Fiscal<br>T.P {{ $representantelegal['cedularevisor'] ?? '' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
