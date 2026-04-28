@php
    function normalizarNumero($valor)
    {
        return (float) str_replace(',', '', $valor ?? 0);
    }
    $cuentasValidar = ['31', '32', '33', '36', '37'];
    $anioComparar = array_key_first($informe); // Ej: 2025
    $comparaciones = [];
    $equivalencias = [
        '36' => '362'
    ];
    foreach ($cuentasValidar as $cuenta) {
        // $itemTabla = $informe[$anioComparar][$cuenta] ?? null;
        // $itemDetallado = collect($informedetallado)->firstWhere('cuenta', $cuenta);
       $cuentaTabla = $equivalencias[$cuenta] ?? $cuenta;

        $itemTabla = $informe[$anioComparar][$cuentaTabla] ?? null;
        $itemDetallado = collect($informedetallado)
            ->firstWhere('cuenta', $cuenta);


        if ($itemTabla && $itemDetallado) {
            $valorTabla = normalizarNumero($itemTabla->saldo_actual);
            $valorDetallado = normalizarNumero($itemDetallado['totalaño1']);

            // Normalizar signo → ambos negativos o ambos positivos
            if ($valorTabla > 0 && $valorDetallado < 0) {
                $valorDetallado *= -1;
            } elseif ($valorTabla < 0 && $valorDetallado > 0) {
                $valorDetallado *= -1;
            }

            $diferencia = $valorTabla - $valorDetallado;
            $estado = abs($diferencia) <= 5 ? 'CUADRADO' : 'DESCADRADO';

            $comparaciones[] = [
                'cuenta' => $cuenta,
                'nombre' => $itemDetallado['descripcion'] ?? $cuenta,
                'tabla' => $valorTabla,
                'detallado' => $valorDetallado,
                'diferencia' => $diferencia,
                'estado' => $estado,
            ];
        }
    }
@endphp
@if (collect($comparaciones)->contains(fn($c) => $c['estado'] == 'DESCADRADO'))
    <button type="button" class="btn btn-danger border btn-radius px-4" data-bs-toggle="modal"
        data-bs-target="#validacionModal">
        Descuadrado
    </button>
@else
    <button type="button" class="btn btn-success border btn-radius px-4" data-bs-toggle="modal"
        data-bs-target="#validacionModal">
        Cuadrado
    </button>
@endif

<div class="modal fade" id="validacionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validación Patrimonio - Año {{ $anioComparar }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered datatable-informe w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Cuenta</th>
                            <th>Descripción</th>
                            <th class="text-end">Estado patrimonio</th>
                            <th class="text-end">Situacion financiera</th>
                            <th class="text-end">Diferencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comparaciones as $c)
                            <tr class="{{ $c['estado'] == 'DESCADRADO' ? 'table-danger' : 'table-success' }}">
                                <td>{{ $c['cuenta'] }}</td>
                                <td>{{ $c['nombre'] }}</td>
                                <td class="text-end">{{ number_format($c['tabla'], 0) }}</td>
                                <td class="text-end">{{ number_format($c['detallado'], 0) }}</td>
                                <td class="text-end">{{ number_format($c['diferencia'], 0) }}</td>
                                <td><strong>{{ $c['estado'] }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
