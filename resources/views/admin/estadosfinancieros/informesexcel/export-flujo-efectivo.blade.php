<table>
    <tr>
        <td class="align-middle border-0">
            <h4 class="title mb-0">
                {{ $compania }}
            </h4>
            <h5 class="mb-0">
                Periodo: {{ $mes.'-'.$anio .'-' . $anioAnterior }}
            </h5>
        </td>
    </tr>
</table>
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
                    @if(is_array($informes))
                        @php 
                            $sumaTotalAct_Actual = 0; 
                            $sumaTotalAct_Anterior = 0; 
                            $objVariacion_Actual = 0;
                            $objVariacion_Anterior = 0;
                        @endphp

                        @foreach ($informes as $titulo => $filas)
                            <tr>
                                <td colspan="5" style="font-weight: bold; background-color: #f3f3f3;">
                                    {{ strtoupper($titulo) }}
                                </td>
                            </tr>

                            @if(is_array($filas))
                                @php
                                    if ($titulo === 'VARIACION EFECTIVO') {
                                        $ordenDeseado = ['Efectivo Inicial', 'Efectivo Final', 'Incremento Neto'];
                                        usort($filas, function($a, $b) use ($ordenDeseado) {
                                            $posA = array_search(trim($a['descripcion']), $ordenDeseado);
                                            $posB = array_search(trim($b['descripcion']), $ordenDeseado);
                                            return ($posA === false ? 99 : $posA) - ($posB === false ? 99 : $posB);
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

                                        if ($descripcion == 'Incremento Neto') {
                                            $objVariacion_Actual = $actual;
                                            $objVariacion_Anterior = $anterior;
                                        }
                                        
                                        if (in_array($titulo, ['ACTIVIDADES DE OPERACION', 'ACTIVIDADES DE INVERSION', 'ACTIVIDADES DE FINANCIACION'])) {
                                            $sumaTotalAct_Actual += $actual;
                                            $sumaTotalAct_Anterior += $anterior;
                                        }

                                        $variacionPorcentaje = ($anterior != 0) ? ($variacionValor / abs($anterior)) : 0;
                                    @endphp

                                    <tr>
                                        <td style="{{ !str_contains($descripcion, '(+') ? 'font-weight:bold;' : '' }}">
                                            {{ $descripcion }}
                                        </td>
                                        <td style="text-align:right;">{{ $actual }}</td>
                                        <td style="text-align:right;">{{ $anterior }}</td>
                                        <td style="text-align:right;">{{ $variacionValor }}</td>
                                        <td style="text-align:right;">{{ $variacionPorcentaje }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach

                        @php
                            $diffActual = $objVariacion_Actual - $sumaTotalAct_Actual;
                            $diffAnterior = $objVariacion_Anterior - $sumaTotalAct_Anterior;
                        @endphp

                        <tr style="background-color: #e9ecef; font-weight: bold;">
                            <td>Validador (Suma Actividades)</td>
                            <td style="text-align: right;">{{ $sumaTotalAct_Actual }}</td>
                            <td style="text-align: right;">{{ $sumaTotalAct_Anterior }}</td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr style="font-weight: bold;">
                            <td>Diferencia</td>
                            <td style="text-align: right;">{{ (abs($diffActual) <= 1) ? 0 : $diffActual }}</td>
                            <td style="text-align: right;">{{ (abs($diffAnterior) <= 1) ? 0 : $diffAnterior }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @else
                        <tr><td colspan="5">No hay datos disponibles</td></tr>
                    @endif
                </tbody>
            </table>