<button type="button" class="btn btn-light border btn-radius px-4" data-bs-toggle="modal"
    data-bs-target="#metasVentasModal">
    <i class="fa-solid fa-award me-1"></i> Ver presupuesto
</button>

<!-- Metas Modal -->
<div class="modal fade" id="metasVentasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xxl-custom">
        <div class="modal-content">
            <div class="modal-header bg-gradient-light">
                <h5
                    class="modal-title bg-transparent border-0 pb-0 mt-1 py-2 d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Presupuesto {{ $compania }} — periodo: {{ $fecha }}
                </h5>
                <div class="ml-auto">
                    <div class="row">
                        <div class="col d-flex">
                            {{-- Exportar a Excel --}}
                            <form id="export-metas-form" action="{{ route('admin.estadosfinancieros.exportMetas') }}"
                                method="POST" class="d-inline ms-2">
                                @csrf
                                <input type="hidden" name="empresa_id" value="{{ $empresaId }}">
                                <input type="hidden" name="nit" value="{{ $nit }}">
                                <input type="hidden" name="informePorMes" value="{{ json_encode($informePorMes) }}">
                                <input type="hidden" name="fecha" value="{{ $dateNumber }}">
                                <button type="submit" class="btn btn-sm btn-save btn-radius px-3"
                                    title="Exportar a Excel">
                                    <i class="fa-solid fa-file-excel"></i>
                                </button>
                            </form>
                            {{-- Exportar a PDF --}}
                            <form id="export-metas-pdf-form" action="{{ route('admin.estadosfinancieros.exportMetasPdf') }}"
                                method="POST" class="d-inline ms-2" target="_blank">
                                @csrf
                                <input type="hidden" name="empresa_id" value="{{ $empresaId }}">
                                <input type="hidden" name="nit" value="{{ $nit }}">
                                <input type="hidden" name="informePorMes" value="{{ json_encode($informePorMes) }}">
                                <input type="hidden" name="fecha" value="{{ $dateNumber }}">
                                <button type="submit" class="btn btn-sm btn-save btn-radius px-3"
                                    title="Exportar a PDF">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                {{-- CONTROLES: búsqueda + filtro --}}
                <div class="d-flex gap-2 align-items-center mb-3">
                    <div class="input-group w-50">
                        <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                        <input id="metas-search" class="form-control form-control-sm"
                            placeholder="Buscar cuenta o descripción..." />
                    </div>

                    <div class="form-check form-check-inline ms-auto">
                        <input class="form-check-input" type="checkbox" id="metas-filter-unmet" />
                        <label class="form-check-label" for="metas-filter-unmet">
                            <i class="fas fa-eye me-1"></i> Mostrar sólo incumplidas
                        </label>
                    </div>

                    <div class="btn-group btn-group-sm ms-2" role="group" aria-label="view">
                        <button id="metas-reset" class="btn btn-light border btn-radius px-2">
                            <i class="fas fa-eraser me-1"></i>Restablecer
                        </button>
                    </div>
                </div>

                @if (!empty($metasComparative))
                    @php
                        $meses = array_keys($metasComparative);
                        $primerMes = reset($metasComparative);
                        $cuentas = $primerMes['cuentas'] ?? [];
                    @endphp

                    <div class="table-responsive" style="max-height:60vh; overflow:auto;">
                        <table class="table table-sm align-middle text-nowrap metas-table" id="metas-table">
                            {{-- THEAD MULTINIVEL --}}
                            <thead class="table-light text-center sticky-top" style="top:0; z-index:3;">
                                <tr>
                                    <th rowspan="2" class="align-middle sticky-col first-col bg-white">Cuenta</th>
                                    <th rowspan="2" class="align-middle sticky-col second-col bg-white">Descripción
                                    </th>

                                    @foreach ($meses as $mes)
                                        <th colspan="3" class="px-3 text-uppercase">
                                            <span class="fw-semibold">{{ $mes }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($meses as $mes)
                                        <th class="text-center small">Ejecución</th>
                                        <th class="text-center small">Presupuesto</th>
                                        <th class="text-center small">%</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cuentas as $index => $cuentaBase)
                                    @php
                                        // calcular si la fila tiene alguna meta incumplida (<100)
                                        $hasUnmet = false;
                                        foreach ($meses as $mcheck) {
                                            $rcheck = $metasComparative[$mcheck]['cuentas'][$index] ?? null;
                                            if (
                                                $rcheck &&
                                                isset($rcheck['porcentaje']) &&
                                                is_numeric($rcheck['porcentaje']) &&
                                                $rcheck['porcentaje'] < 100
                                            ) {
                                                $hasUnmet = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    <tr class="{{ $hasUnmet ? 'table-warning' : '' }}"
                                        data-cuenta="{{ $cuentaBase['cuenta'] }}"
                                        data-descripcion="{{ strtolower($cuentaBase['descripcion'] ?? '') }}"
                                        data-grupo="{{ $cuentaBase['grupo'] ?? 'egresos' }}">
                                        {{-- STICKY COLUMNS --}}
                                        <td class="sticky-col first-col bg-white">
                                            <span class="fw-medium">{{ $cuentaBase['cuenta'] }}</span>
                                        </td>

                                        <td class="sticky-col second-col bg-white">
                                            <small class="text-muted">{{ $cuentaBase['descripcion'] }}</small>
                                        </td>

                                        {{-- CELDAS POR MES --}}
                                        @foreach ($meses as $mes)
                                            @php $row = $metasComparative[$mes]['cuentas'][$index] ?? null; @endphp

                                            @if ($row)
                                                <td class="text-end pe-3 ejecucion"
                                                    data-value="{{ $row['valor_informe'] ?? 0 }}">
                                                    <small class="d-block text-secondary">$
                                                        {{ number_format($row['valor_informe'] ?? 0) }}
                                                    </small>
                                                </td>

                                                <td class="text-end pe-3 presupuesto"
                                                    data-value="{{ $row['meta'] ?? 0 }}">
                                                    <small class="d-block">
                                                        @if ($row['meta'] !== null)
                                                            ${{ number_format($row['meta']) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </small>
                                                </td>

                                                @php
                                                    $pct =
                                                        isset($row['porcentaje']) && is_numeric($row['porcentaje'])
                                                            ? $row['porcentaje']
                                                            : null;
                                                    // decidir clase
                                                    $pctCls = 'text-muted';
                                                    if ($pct !== null) {
                                                        if ($pct >= 100) {
                                                            $pctCls = 'text-success';
                                                        } elseif ($pct >= 80) {
                                                            $pctCls = 'text-warning';
                                                        } else {
                                                            $pctCls = 'text-danger';
                                                        }
                                                    }
                                                @endphp

                                                <td class="text-end pe-3 align-middle">
                                                    @if ($pct !== null)
                                                        <div
                                                            class="d-flex align-items-center justify-content-end gap-2">
                                                            <small
                                                                class="{{ $pctCls }} fw-bold">{{ number_format($pct, 2, ',', '.') }}%</small>
                                                            <div class="progress" style="height:6px; width:70px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: {{ min(max($pct, 0), 150) }}%;"
                                                                    aria-valuenow="{{ $pct }}"
                                                                    aria-valuemin="0" aria-valuemax="150"></div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td colspan="3" class="text-center text-muted">-</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>

                            {{-- FOOTER: Totales por mes --}}
                            <tfoot class="table-light">
                                @foreach (['ingresos' => 'Total ingresos', 'egresos' => 'Total egresos'] as $grupo => $label)
                                    @php
                                        $totKey = $grupo === 'ingresos' ? 'totales_ingresos' : 'totales_egresos';
                                    @endphp
                                    <tr data-group="{{ $grupo }}">
                                        <th class="sticky-col first-col bg-white text-left" colspan="2">{{ $label }}</th>

                                        @foreach ($meses as $m)
                                            @php
                                                $tot = $metasComparative[$m][$totKey] ?? [
                                                    'total_informe' => 0,
                                                    'total_meta' => 0,
                                                    'porcentaje_total' => null,
                                                ];
                                                $tInf = $tot['total_informe'] ?? 0;
                                                $tMeta = $tot['total_meta'] ?? 0;
                                                $tPct = $tot['porcentaje_total'] ?? null;
                                            @endphp

                                            <th class="text-end pe-3 total-informe" data-mes="{{ $m }}">
                                                ${{ number_format($tInf) }}
                                            </th>
                                            <th class="text-end pe-3 total-meta" data-mes="{{ $m }}">
                                                ${{ number_format($tMeta) }}
                                            </th>
                                            <th class="text-end pe-3 total-pct" data-mes="{{ $m }}">
                                                @if ($tPct !== null)
                                                    <span
                                                        class="{{ $tPct >= 100 ? 'text-success' : ($tPct >= 80 ? 'text-warning' : 'text-danger') }} fw-bold">
                                                        {{ number_format($tPct, 2, ',', '.') }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> No hay metas configuradas para este
                        periodo.
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-radius px-4" data-bs-dismiss="modal">
                    <i class="fa-regular fa-circle-xmark me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CSS ligero --}}
<style>
    /* Evitar espacios entre celdas que generan huecos */
    .metas-table {
        border-collapse: collapse;
    }

    /* sticky columns */
    .sticky-col {
        position: sticky;
        z-index: 3;
        background-clip: padding-box;
        /* evita overlays raros con bordes */
        background-color: #fff !important;
    }

    /* Primera columna (cuenta) */
    .first-col {
        left: 0;
        width: 76px;
        /* ancho fijo */
        min-width: 76px;
        max-width: 76px;
        padding: .6rem .5rem;
        /* control de padding */
        box-shadow: 2px 0 0 #eef2f6;
        /* separación sutil vertical */
    }

    /* Segunda columna (descripción) pegada a la primera */
    .second-col {
        left: 76px;
        /* = width de first-col */
        min-width: 200px;
        /* ancho mínimo para descripción */
        padding: .6rem .75rem;
        box-shadow: 2px 0 0 #eef2f6;
    }

    /* Asegurar que el thead sticky esté por encima */
    thead.sticky-top th {
        z-index: 5;
    }

    /* Evitar duplicación de espacio en th/td sticky */
    .metas-table th.sticky-col,
    .metas-table td.sticky-col {
        white-space: nowrap;
    }

    /* Ajustes de apariencia */
    .metas-table th,
    .metas-table td {
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
        border-right: 1px solid transparent;
    }

    /* Progres bar y colores */
    .metas-table .progress {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    .metas-table .progress-bar {
        background: linear-gradient(90deg, #4ade80, #16a34a);
    }

    /* Row zebra sutil */
    .metas-table tbody tr:nth-child(even) {
        background: rgba(0, 0, 0, 0.02);
    }

    /* Fila con incumplimiento */
    .table-warning {
        background: rgba(255, 243, 205, 0.9) !important;
    }

    .modal-xxl-custom {
        max-width: 85vw !important;
        overflow: auto;
        scroll-behavior: smooth;
        scrollbar-color: #3FBDEE #fff;
        scrollbar-width: thin;
    }
</style>

{{-- JS: búsqueda simple y filtro incumplidas --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const search = document.getElementById('metas-search');
        const filterUnmet = document.getElementById('metas-filter-unmet');
        const resetBtn = document.getElementById('metas-reset');
        const table = document.getElementById('metas-table');
        const tbody = table ? table.querySelector('tbody') : null;

        function applyFilters() {
            const q = (search.value || '').trim().toLowerCase();
            const onlyUnmet = filterUnmet.checked;
            if (!tbody) return;
            Array.from(tbody.querySelectorAll('tr')).forEach(function(row) {
                const cuenta = (row.dataset.cuenta || '').toString();
                const descripcion = (row.dataset.descripcion || '').toLowerCase();
                let show = true;
                if (q) {
                    if (!(cuenta.includes(q) || descripcion.includes(q))) {
                        show = false;
                    }
                }
                if (onlyUnmet) {
                    // row has class table-warning when any month <100 (server-side)
                    if (!row.classList.contains('table-warning')) show = false;
                }
                row.style.display = show ? '' : 'none';
            });
            recalculateTotals();
        }

        function recalculateTotals() {
            const visibleRows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
            const moneyFmt = new Intl.NumberFormat('es-CO');
            const footerRows = table.querySelectorAll('tfoot tr[data-group]');

            footerRows.forEach(footerRow => {
                const group = footerRow.dataset.group;
                const groupRows = visibleRows.filter(row => row.dataset.grupo === group);
                const ejecuciones = footerRow.querySelectorAll('.total-informe');
                const metas = footerRow.querySelectorAll('.total-meta');
                const porcentajes = footerRow.querySelectorAll('.total-pct');

                ejecuciones.forEach((cell, index) => {
                    let totalInforme = 0;
                    let totalMeta = 0;

                    groupRows.forEach(row => {
                        const ejecCells = row.querySelectorAll('.ejecucion');
                        const metaCells = row.querySelectorAll('.presupuesto');
                        const ejec = ejecCells[index];
                        const meta = metaCells[index];

                        if (ejec) totalInforme += parseFloat(ejec.dataset.value || 0);
                        if (meta) totalMeta += parseFloat(meta.dataset.value || 0);
                    });

                    const pct = totalMeta > 0 ? (totalInforme / totalMeta) * 100 : null;
                    ejecuciones[index].innerHTML = '$' + moneyFmt.format(totalInforme);
                    metas[index].innerHTML = '$' + moneyFmt.format(totalMeta);

                    if (pct !== null) {
                        const cls = pct >= 100 ?
                            'text-success' :
                            pct >= 80 ?
                            'text-warning' :
                            'text-danger';
                        porcentajes[index].innerHTML = `<span class="${cls} fw-bold">${pct.toFixed(2)}%</span>`;
                    } else {
                        porcentajes[index].innerHTML = `<span class="text-muted">-</span>`;
                    }
                });
            });
        }

        if (search) search.addEventListener('input', applyFilters);
        if (filterUnmet) filterUnmet.addEventListener('change', applyFilters);
        if (resetBtn) resetBtn.addEventListener('click', function() {
            search.value = '';
            filterUnmet.checked = false;
            applyFilters();
        });

        // inicial
        applyFilters();

        // init bootstrap tooltips if present
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(el => new bootstrap.Tooltip(el));
        }
    });
</script>
