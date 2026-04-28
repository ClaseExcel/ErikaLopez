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
            $keys = array_keys($informe);
            rsort($keys); // Orden descendente: el más reciente primero
            
            $anioActual = $keys[0];
            $anioAnterior = $keys[1] ?? $keys[0];
        @endphp
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
<table>
    <thead>
        <tr>
            <th rowspan="2">Concepto</th>
            <th colspan="4">Año {{ $anioActual }}</th>
            <th colspan="4">Año {{ $anioAnterior }}</th>
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
        
        @foreach ($informe[$anioActual] as $codigo => $itemActual)
            @php
                $itemAnterior = $informe[$anioAnterior][$codigo] ?? null;

                $valoresActual = [
                    (float) str_replace(',', '', $itemActual['saldo_anterior'] ?? 0),
                    (float) str_replace(',', '', $itemActual['aumento'] ?? 0),
                    (float) str_replace(',', '', $itemActual['disminucion'] ?? 0),
                    (float) str_replace(',', '', $itemActual['saldo_actual'] ?? 0),
                ];
                
                $valoresAnterior = $itemAnterior ? [
                    (float) str_replace(',', '', $itemAnterior['saldo_anterior'] ?? 0),
                    (float) str_replace(',', '', $itemAnterior['aumento'] ?? 0),
                    (float) str_replace(',', '', $itemAnterior['disminucion'] ?? 0),
                    (float) str_replace(',', '', $itemAnterior['saldo_actual'] ?? 0),
                ] : [0, 0, 0, 0];
               
                $todosCeros = collect(array_merge($valoresActual, $valoresAnterior))->every(fn($v) => $v == 0);
            @endphp

            @if (!$todosCeros)
                
                <tr class="{{ $itemActual['cuenta'] === 'Total Patrimonio' ? 'total-patrimonio' : '' }}">
                    <td>{{ $nombresCuentas[$itemActual['cuenta']] ?? $itemActual['cuenta'] }}</td>
                    {{-- Año actual --}}
                    <td>{{ $valoresActual[0]}}</td>
                    <td>{{ $valoresActual[2]}}</td>
                    <td>{{ $valoresActual[1]}}</td>
                    <td>{{ $valoresActual[3]}}</td>

                    {{-- Año anterior --}}
                    @if ($itemAnterior)
                        <td>{{ $valoresAnterior[0]}}</td>
                        <td>{{ $valoresAnterior[2]}}</td>
                        <td>{{ $valoresAnterior[1]}}</td>
                        <td>{{ $valoresAnterior[3]}}</td>
                    @else
                        <td colspan="4">-</td>
                    @endif
                </tr>
            @endif
        @endforeach

    </tbody>
</table>