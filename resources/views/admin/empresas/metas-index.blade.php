@extends('layouts.admin')
@section('title', 'Lista de presupuesto')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('styles')
    <style>
        #cuentas-container {
            background: #fafafa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 1px 6px rgba(16, 24, 40, 0.04);
            max-height: 270px;
            overflow: auto;
            scroll-behavior: smooth;
            scrollbar-color: #3FBDEE #fff;
            scrollbar-width: thin;
        }

        .cuenta-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: transform .08s ease, box-shadow .08s ease;
        }

        .cuenta-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(30, 41, 59, 0.06);
        }

        .cuenta-title {
            font-size: 0.92rem;
            color: #0f172a;
            font-weight: 600;
            margin: 0;
        }

        .cuenta-meta {
            min-width: 140px;
            max-width: 220px;
        }

        .cuenta-valor {
            width: 100%;
            text-align: right;
        }

        .cuenta-row {
            margin-bottom: 10px;
        }

        @media (max-width: 767px) {
            .cuenta-meta {
                max-width: 140px;
            }
        }

        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .valor-list {
            overflow: auto;
            scroll-behavior: smooth;
            scrollbar-color: #3FBDEE #fff;
            scrollbar-width: thin;
        }
    </style>
@endsection
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12 mb-2">
            <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.empresas.index') }}">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
            @can('CREAR_METAS_EMPRESAS')
                <button id="btn-add-target" class="btn btn-back border btn-radius">
                    <i class="fas fa-plus-circle"></i> Agregar presupuesto
                </button>
                <a href="{{ route('admin.metas_empresas.masiva') }}" class="btn btn-back border btn-radius">
                    <i class="fas fa-arrow-circle-up"></i> Agregar masivo
                </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de presupuesto
        </div>

        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col-12 col-md-10">
                    <div class="row g-2" id="metas-filtros">
                        <div class="col-6 col-md-4">
                            <input class="form-control" id="filter-fecha-inicio" type="month">
                            <small class="d-block text-center mt-1">Periodo inicio</small>
                        </div>
                        <div class="col-6 col-md-4">
                            <input class="form-control" id="filter-fecha-fin" type="month">
                            <small class="d-block text-center mt-1">Periodo fin</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <select id="filter-empresa" class="form-control ">
                                <option value="">Todas</option>
                                @foreach ($empresas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <small class="d-block text-center mt-1">Empresa</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table-striped datatable-MetasEmpresa w-100" id="datatable-MetasEmpresa">
                <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Empresa</th>
                        <th>Valores</th>
                        <th style="width:120px">&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Modal para crear/editar --}}
    <div class="modal fade" id="metaEmpresaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form id="metaEmpresaForm">
                <div class="modal-content" style="border-radius: 15px;">
                    <div class="modal-header">
                        <h5 id="metaEmpresaModalTitle" class="modal-title">Agregar presupuesto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div id="form-errors" class="alert alert-danger d-none"></div>

                        <div class="mb-3">
                            <label class="fw-normal">Mes <b class="text-danger">*</b></label>
                            <input type="month" class="form-control" id="periodo" name="periodo" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-normal">Empresa <b class="text-danger">*</b></label>
                            <select class="form-control" id="empresa_id" name="empresa_id" required>
                                <option value="">Selecciona</option>
                                @foreach ($empresas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Contenedor dinámico donde se generarán las filas de cuenta + input --}}
                        <div class="mb-3">
                            <label class="fw-normal">Valores por cuenta <b class="text-danger">*</b></label>
                            <div id="cuentas-container">
                                <small class="text-muted">Selecciona una empresa para cargar las cuentas.</small>
                            </div>
                            {{-- Hidden input que almacenará el JSON real que enviamos al servidor --}}
                            <input type="hidden" id="valor" name="valor">
                        </div>
                    </div>

                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary btn-radius px-4" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark"></i> Cerrar
                        </button>
                        <button type="submit" id="btn-save-target" class="btn btn-save btn-radius px-4">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para ver valores (desde la tabla index) --}}
    <div class="modal fade" id="valorViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-chart-line me-2"></i>
                        Valores registrados
                    </h5>
                </div>
                <div class="modal-body valor-list">
                    <div id="valor-list" class="p-2"></div>
                </div>
                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-secondary btn-radius px-4" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            // CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // DataTable (ajústalo a tu inicialización si usas otra lib)
            table = new DataTable('#datatable-MetasEmpresa', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}"
                },
                layout: {
                    topStart: 'pageLength',
                    topEnd: {
                        buttons: [{
                            text: '<i class="fa-solid fa-eraser me-2"></i>Limpiar filtros',
                            className: 'btn btn-back border rounded-pill',
                            action: function(e, dt, node, config) {
                                $('#metas-filtros').find('input, select').val('');
                                dt.ajax.reload();
                            }
                        }]
                    },
                    bottomEnd: {
                        paging: {
                            type: 'simple_numbers',
                            numbers: 5
                        }
                    }
                },
                ordering: true,
                order: [[0, 'desc']],
                responsive: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.metas_empresas.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.fecha_inicio = $('#filter-fecha-inicio').val();
                        d.fecha_fin = $('#filter-fecha-fin').val();
                        d.empresa_id = $('#filter-empresa').val();
                    }
                },
                columns: [{
                        data: 'periodo',
                        name: 'periodo'
                    },
                    {
                        data: 'empresa',
                        name: 'empresa'
                    },
                    {
                        data: 'valor',
                        name: 'valor'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'actions-size'
                    }
                ],
                initComplete: function() {
                    const api = this.api();
                    $('#filter-fecha-inicio, #filter-fecha-fin, #filter-empresa')
                        .off().on('change', function() {
                            api.draw();
                        });
                }
            });

            const metaModalEl = document.getElementById('metaEmpresaModal');
            const metaModal = new bootstrap.Modal(metaModalEl);
            const valorViewModal = new bootstrap.Modal(document.getElementById('valorViewModal'));
            let currentId = null;
            let currentLoadedCuentas = []; // cuentas actuales cargadas del controller

            // abrir modal para crear
            $('#btn-add-target').on('click', function() {
                currentId = null;
                $('#metaEmpresaForm')[0].reset();
                $('#cuentas-container').html(
                    '<small class="text-muted">Selecciona una empresa para cargar las cuentas.</small>');
                $('#form-errors').addClass('d-none').html('');
                $('#metaEmpresaModalTitle').text('Agregar presupuesto');
                $('#valor').val('');
                currentLoadedCuentas = [];
                metaModal.show();
            });

            // cuando cambie empresa, cargar cuentas desde servidor
            $('#empresa_id').on('change', function() {
                const empresaId = $(this).val();
                if (!empresaId) {
                    $('#cuentas-container').html(
                        '<small class="text-muted">Selecciona una empresa para cargar las cuentas.</small>'
                    );
                    currentLoadedCuentas = [];
                    return;
                }

                // mostrar loading
                $('#cuentas-container').html(
                    '<div class="text-center py-4"><i class="fa fa-spinner fa-spin"></i> Cargando cuentas...</div>'
                );

                $.get("{{ url('admin/metas_empresas/cuentas') }}/" + empresaId)
                    .done(function(resp) {
                        buildCuentasInputs(resp.data, []); // sin valores por defecto (creación)
                    })
                    .fail(function(xhr) {
                        $('#cuentas-container').html(
                            '<div class="text-danger">No fue posible cargar las cuentas.</div>');
                    });
            });

            // Editar: delegado botón editar
            $(document).on('click', '.btn-edit-target', function() {
                currentId = $(this).data('id');
                $('#form-errors').addClass('d-none').html('');
                $('#metaEmpresaModalTitle').text('Editar presupuesto');

                // Pedimos al servidor el registro completo
                $.get("{{ url('admin/metas_empresas') }}/" + currentId)
                    .done(function(resp) {
                        $('#periodo').val(resp.periodo || '');
                        $('#empresa_id').val(resp.empresa_id || '');

                        // si la empresa carga automáticamente (trigger), necesitamos cargar las cuentas y luego setear valores
                        if (resp.empresa_id) {
                            // llamamos al endpoint de cuentas y pasamos resp.valor como existingValues
                            $.get("{{ url('admin/metas_empresas/cuentas') }}/" + resp.empresa_id)
                                .done(function(r2) {
                                    const existing = Array.isArray(resp.valor) ? resp.valor : [];
                                    buildCuentasInputs(r2.data, existing);
                                    // abrir modal después de renderizar inputs
                                    metaModal.show();
                                })
                                .fail(function() {
                                    $('#cuentas-container').html(
                                        '<div class="text-danger">No fue posible cargar las cuentas.</div>'
                                    );
                                    metaModal.show();
                                });
                        } else {
                            $('#cuentas-container').html(
                                '<small class="text-muted">Selecciona una empresa para cargar las cuentas.</small>'
                            );
                            metaModal.show();
                        }
                    })
                    .fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No fue posible cargar el registro.'
                        });
                    });
            });

            // Copiar: delegado botón copiar
            $(document).on('click', '.btn-copy-target', function() {
                const sourceId = $(this).data('id');
                $('#form-errors').addClass('d-none').html('');
                $('#metaEmpresaModalTitle').text('Copiar presupuesto');

                // Pedimos el registro fuente
                $.get("{{ url('admin/metas_empresas') }}/" + sourceId)
                    .done(function(resp) {
                        // dejamos currentId en null para forzar CREATE al hacer submit
                        currentId = null;
                        // dejamos periodo vacío (el usuario debe seleccionar mes nuevo)
                        $('#periodo').val('');
                        // seteamos empresa para que coincida con la copia visual
                        $('#empresa_id').val(resp.empresa_id || '');

                        if (resp.empresa_id) {
                            // pedimos las cuentas de la empresa y pasamos los valores existentes
                            $.get("{{ url('admin/metas_empresas/cuentas') }}/" + resp.empresa_id)
                                .done(function(r2) {
                                    const existing = Array.isArray(resp.valor) ? resp.valor : [];
                                    buildCuentasInputs(r2.data, existing);
                                    metaModal.show();
                                })
                                .fail(function() {
                                    $('#cuentas-container').html(
                                        '<div class="text-danger">No fue posible cargar las cuentas de la empresa.</div>'
                                    );
                                    metaModal.show();
                                });
                        } else {
                            $('#cuentas-container').html(
                                '<small class="text-muted">Selecciona una empresa para cargar las cuentas.</small>'
                            );
                            metaModal.show();
                        }
                    })
                    .fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No fue posible cargar el registro a copiar.'
                        });
                    });
            });

            // Construye los inputs de cuentas. existingValues = [{cuenta,nombre,valor}, ...]
            function buildCuentasInputs(cuentas, existingValues) {
                currentLoadedCuentas = cuentas;
                let html = '';
                if (!cuentas || cuentas.length === 0) {
                    html = '<div class="text-muted">No se encontraron cuentas para la empresa seleccionada.</div>';
                    $('#cuentas-container').html(html);
                    return;
                }

                // Grid 2 columnas: cada cuenta en su tarjeta
                html += '<div class="row">';
                cuentas.forEach(function(c, idx) {
                    // buscar valor existente
                    const match = (existingValues || []).find(function(ev) {
                        return String(ev.cuenta) === String(c.cuenta);
                    });
                    const valor = match ? match.valor : 0;

                    // columna: col-12 en xs, col-md-6 en pantallas >= md
                    html += `<div class="col-12 col-md-6 cuenta-row">
                                <div class="cuenta-card">
                                    <div class="flex-grow-1">
                                        <p class="cuenta-title">${escapeHtml(c.cuenta)} <small class="text-muted">- ${escapeHtml(c.nombre)}</small></p>
                                    </div>
                                    <div class="cuenta-meta">
                                        <input type="number" step="0.01" required
                                               class="form-control cuenta-valor" 
                                               data-cuenta="${c.cuenta}" 
                                               data-nombre="${escapeHtml(c.nombre)}" 
                                               value="${valor}">
                                    </div>
                                </div>
                             </div>`;
                });
                html += '</div>';
                $('#cuentas-container').html(html);
                // mejora: poner foco en primer input si es creación
                $('#cuentas-container').find('.cuenta-valor').first().focus();
            }

            // escapar texto para prevenir XSS en inyección de nombres
            function escapeHtml(text) {
                return String(text || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            $(document).on('focus', '.cuenta-valor', function() {
                if ($(this).val() === '0' || $(this).val() === '0.00') {
                    $(this).val('');
                }
            });

            // Restaurar 0 si se deja vacío al salir del input
            $(document).on('blur', '.cuenta-valor', function() {
                if ($(this).val().trim() === '') {
                    $(this).val('0');
                }
            });

            // Submit create/update: serializamos los inputs en JSON y lo guardamos en input hidden #valor
            $('#metaEmpresaForm').on('submit', function(e) {
                e.preventDefault();

                // serializar cuentas
                const payload = [];
                $('.cuenta-valor').each(function() {
                    const cuenta = $(this).data('cuenta');
                    const nombre = $(this).data('nombre');
                    const valor = $(this).val() !== '' ? $(this).val() : "0";
                    payload.push({
                        cuenta: cuenta,
                        nombre: nombre,
                        valor: parseFloat(valor) || 0
                    });
                });

                $('#valor').val(JSON.stringify(payload));

                let url = "{{ route('admin.metas_empresas.store') }}";
                let method = 'POST';
                const data = {
                    periodo: $('#periodo').val(),
                    empresa_id: $('#empresa_id').val(),
                    valor: $('#valor').val()
                };

                if (!data.empresa_id) {
                    $('#form-errors').removeClass('d-none').html('<p>Debes seleccionar una empresa.</p>');
                    return;
                }

                if (currentId) {
                    url = "{{ url('admin/metas_empresas') }}/" + currentId;
                    data._method = 'PUT';
                    method = 'POST';
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(res) {
                        metaModal.hide();
                        table.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Listo',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        let errors = '';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(e => {
                                errors += `<p>${e}</p>`;
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errors = `<p>${xhr.responseJSON.message}</p>`;
                        } else {
                            errors = '<p>Error en el servidor.</p>';
                        }
                        $('#form-errors').removeClass('d-none').html(errors);
                    }
                });
            });

            // Ver valores (botón en la tabla)
            $(document).on('click', '.btn-view-valor', function() {
                const id = $(this).data('id');
                $('#valor-list').html(
                    '<div class="text-center py-4"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>'
                );
                $.get("{{ url('admin/metas_empresas') }}/" + id)
                    .done(function(resp) {
                        const valores = Array.isArray(resp.valor) ? resp.valor : [];
                        if (valores.length === 0) {
                            $('#valor-list').html(
                                '<div class="text-muted">No hay valores registrados.</div>');
                            valorViewModal.show();
                            return;
                        }

                        // ordenar descendente por valor (opcional pero recomendable)
                        valores.sort((a, b) => (Number(b.valor) || 0) - (Number(a.valor) || 0));
                        const total = valores.reduce((s, it) => s + (Number(it.valor) || 0), 0);

                        // header con título y total
                        let html = `
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Valores por cuenta</h6>
                                    <small class="text-muted">Detalle por cuenta</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">Total</div>
                                    <div class="text-secondary">${Number(total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                                </div>
                            </div>
                            <div class="container-fluid p-0">
                                <div class="row g-3">
                        `;

                        // construir tarjetas en 2 columnas (col-md-6)
                        valores.forEach(function(item) {
                            const v = Number(item.valor) || 0;
                            const formatted = v.toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            html += `
                                <div class="col-12 col-md-6">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body d-flex align-items-start">
                                            <div>
                                                <div class="fw-semibold">${escapeHtml(item.cuenta)}</div>
                                                <div class="text-muted">${escapeHtml(item.nombre)}</div>
                                            </div>
                                            <div class="text-end ms-auto">
                                                <div class="fw-bold">${formatted}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        html += `
                                </div>  <!-- row -->
                            </div>      <!-- container-fluid -->
                        `;
                        $('#valor-list').html(html);
                        valorViewModal.show();
                    })
                    .fail(function() {
                        $('#valor-list').html(
                            '<div class="text-danger">No fue posible cargar los valores.</div>');
                        valorViewModal.show();
                    });
            });
        });
    </script>
@endsection
