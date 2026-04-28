<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Presupuesto - {{ $nit ?? $empresaId }} - {{ $fecha }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            margin: 1%;
            padding: 1%;
            background-color: #ffffff;
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 6px 6px;
            vertical-align: middle;
        }

        th {
            font-size: 10px;
            text-align: center;
            background: #3FBDEE;
            color: #fff;
        }

        td {
            font-size: 9px;
        }

        td.text-end {
            text-align: right;
        }

        .small {
            font-size: 9px;
            color: #555;
        }

        .totals {
            background: #f2f2f2;
            font-weight: bold;
        }

        .table-block {
            margin-bottom: 12px;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .logo {
            width: 140px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .header-row {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .card {
            border: 2px solid #dddddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid #dddddd;
            padding: 0px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border: none !important;
            text-transform: uppercase;
        }

        .card-body {
            padding: 5px 15px 25px 15px;
        }
    </style>
</head>

<body>
    <div style="width: 100; text-align:center; margin-bottom: 1rem !important;">
        @if (!empty($base64ImageLogo))
            <img src="data:image/png;base64,{{ $base64ImageLogo }}" alt="logo" class="logo">
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="color: #3FBDEE">SEGUIMIENTO PRESUPUESTAL {{ $empresa->razon_social ?? '' }}</h2>
            <h5 class="small">{{ $nit ?? $empresaId }} — periodo: {{ $fecha }}</h5>
            <h5 class="small">Generado: {{ \Carbon\Carbon::now()->format('Y-m-d h:i a') }}</h5>
        </div>
        <div class="card-body">
            @php
                // cuentas base (fila por fila)
                $primer = reset($metasComparative);
                $cuentas = $primer['cuentas'] ?? [];
            @endphp

            @foreach ($monthGroups as $group)
                @php
                    $groupMonths = $group['months']; // associative key => label
                    $pageBreak = !empty($group['pageBreakBefore']);
                @endphp

                <div class="table-block" style="{{ $pageBreak ? 'page-break-before: always;' : '' }}">
                    <table>
                        <thead>
                            <tr style="text-transform: uppercase;">
                                <th rowspan="2" class="text-center">Cuenta</th>
                                <th rowspan="2" class="text-center">Descripción</th>
                                <th rowspan="2" class="text-center">Acumulado</th>
                                @foreach ($groupMonths as $mkey => $mlabel)
                                    <th class="text-end">{{ ucfirst($mlabel) }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($groupMonths as $mkey => $mlabel)
                                    <th class="text-center">Presupuesto / Ejecución / %</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cuentas as $idx => $c)
                                <tr>
                                    <td>{{ $c['cuenta'] }}</td>
                                    <td style="text-transform: uppercase;">{{ $c['descripcion'] }}</td>

                                    {{-- Informe acumulado (suma de los meses visibles + si existe Total mostrarlo) --}}
                                    @php
                                        // Si esta fila tiene 'Total' en metasComparative, usar ese valor
                                        $valorAcumulado = null;
                                        if (isset($metasComparative['Total']['cuentas'][$idx]['valor_informe'])) {
                                            $valorAcumulado =
                                                $metasComparative['Total']['cuentas'][$idx]['valor_informe'];
                                        } else {
                                            // fallback: sumar los meses del grupo
                                            $s = 0;
                                            foreach (array_keys($groupMonths) as $k) {
                                                if (isset($metasComparative[$k]['cuentas'][$idx]['valor_informe'])) {
                                                    $s += floatval(
                                                        $metasComparative[$k]['cuentas'][$idx]['valor_informe'],
                                                    );
                                                }
                                            }
                                            $valorAcumulado = $s;
                                        }
                                    @endphp

                                    <td class="text-end">
                                        {{ $valorAcumulado ? number_format($valorAcumulado, 0, ',', '.') : '-' }}</td>

                                    @foreach ($groupMonths as $mkey => $mlabel)
                                        @php
                                            $r = $metasComparative[$mkey]['cuentas'][$idx] ?? null;
                                        @endphp

                                        @if ($r)
                                            <td class="text-end">
                                                @if ($r['meta'] !== null)
                                                    <div class="small">Presup.:
                                                        ${{ number_format($r['meta'], 0, ',', '.') }}
                                                    </div>
                                                @endif
                                                <div>Ejec.: ${{ number_format($r['valor_informe'] ?? 0, 0, ',', '.') }}
                                                </div>
                                                <div>
                                                    @if (isset($r['porcentaje']) && is_numeric($r['porcentaje']))
                                                        {{ number_format($r['porcentaje'], 2, ',', '.') }}%
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </td>
                                        @else
                                            <td class="text-center">-</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            @foreach ([
                                'ingresos' => ['label' => 'TOTAL INGRESOS', 'key' => 'totales_ingresos'],
                                'egresos'  => ['label' => 'TOTAL EGRESOS', 'key' => 'totales_egresos'],
                            ] as $grupo => $cfg)
                                <tr class="totals">
                                    <td colspan="2" style="text-align: left;">{{ $cfg['label'] }}</td>

                                    @php
                                        $grandTotal = 0;
                                        if (isset($metasComparative['Total'][$cfg['key']]['total_informe'])) {
                                            $grandTotal = $metasComparative['Total'][$cfg['key']]['total_informe'];
                                        } else {
                                            foreach (array_keys($groupMonths) as $k) {
                                                $grandTotal += $metasComparative[$k][$cfg['key']]['total_informe'] ?? 0;
                                            }
                                        }
                                    @endphp

                                    <td class="text-end">{{ number_format($grandTotal, 0, ',', '.') }}</td>

                                    @foreach ($groupMonths as $mkey => $mlabel)
                                        @php
                                            $tot = $metasComparative[$mkey][$cfg['key']] ?? [
                                                'total_informe' => 0,
                                                'total_meta' => 0,
                                                'porcentaje_total' => null,
                                            ];
                                        @endphp

                                        <td class="text-end" style="text-transform: uppercase;">
                                            <div class="small">Presup.:
                                                ${{ number_format($tot['total_meta'] ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div>Ejec.: ${{ number_format($tot['total_informe'] ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div>
                                                @if ($tot['porcentaje_total'] !== null)
                                                    {{ number_format($tot['porcentaje_total'], 2, ',', '.') }}%
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tfoot>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
