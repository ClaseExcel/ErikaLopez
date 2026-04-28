@extends('layouts.admin')
@section('title', 'Actividades')
@section('library')

    @include('cdn.datatables-head')
    
    <style>
        .cursor-pointer {
            cursor: help;
        }

        .selected-row {
            cursor: pointer;
            background-color: #bde3ff !important;
            color: #002d8f !important;
        }

        .select-info .select-item {
            font-style: oblique;
            color: #868686;
        }
    </style>

@endsection
@section('content')



    @can('CREAR_ACTIVIDADES')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.actividad_cliente.create') }}">
                    <i class="fas fa-circle-plus"></i>
                    Nueva actividad
                </a>
            </div>
        </div>
    @endcan


    <div class="row">

        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header fs-5 d-flex justify-content-between align-items-center">
                    <i class="fas fa-list"></i>&nbsp;  Actividades
                    <div class="ml-auto d-flex align-items-center">
                        @can('ACCEDER_REPORTE_ACTIVIDADES')
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-sm btn-save btn-radius" title="Informe actividades" data-bs-toggle="modal" data-bs-target="#modalDescarga">
                                <i class="fa-solid fa-file-arrow-down fa-lg"></i>
                            </button>
                            @include('modal.actividades-informe-modal')
                        @endcan
                    </div>
                </div>
                <div class="card-body pb-0 mb-0">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-2 mb-2">
                            <div class="form-group" id="filter_col1" data-column="0">
                                {{-- <label class="fw-normal" for="col0_filter">#</label> --}}
                                <input type="text" class="column_filter form-control" id="col0_filter"
                                    placeholder="Número de la actividad">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <div class="form-group" id="filter_col2" data-column="1">
                                {{-- <label class="fw-normal" for="col1_filter">Nombre de la actividad</label>                             --}}
                                <input type="text" class="column_filter form-control" id="col1_filter"
                                    placeholder="Nombre de la actividad">
                            </div>
                        </div>
                        <div class="col-12 col-md-2 mb-2">
                            <div class="form-group">
                                <select class="form-select" id="estado">
                                    <option class="text-secondary" value="">Ningún estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (Auth::user()->role->title != 'Cliente')
                            <div class="col-6 col-md-4 mb-2">
                                <div class="form-group">
                                    <select class="form-select" id="empresa">
                                        <option class="text-secondary" value="">Ninguna empresa</option>
                                        @foreach ($empresas as $empresa)
                                            <option>{{ $empresa->razon_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" id="empresa">
                        @endif

                        @if (Auth::user()->role_id == 1)
                            <div class="col-6 col-md-4 mb-0">
                                <div class="form-group">
                                    <select class="form-select" name="" id="responsable">
                                        <option class="text-secondary" value="">Ningún responsable</option>
                                        @foreach ($responsables as $responsable)
                                            <option value="{{ $responsable->id }}">
                                                {{ $responsable->nombres . ' ' . $responsable->apellidos }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" id="responsable">
                        @endif

                        <div class="col-6 col-md-4 mb-0">
                            <div class="form-group">
                                <input type="date" class="form-control" id="inicial"
                                    placeholder="Fecha inicial de vencimiento"
                                    @if ($fechaVencimientoInicial) min="{{ $fechaVencimientoInicial->fecha_vencimiento }}"
                                    max="{{ $fechaVencimientoFinal->fecha_vencimiento }}" @endif>
                                <small>Fecha incial de vencimiento</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-0">
                            <div class="form-group">
                                <input type="date" class="form-control" id="final"
                                    placeholder="Fecha final de vencimiento"
                                    @if ($fechaVencimientoFinal) max="{{ $fechaVencimientoFinal->fecha_vencimiento }}" @endif>
                                <small>Fecha final de vencimiento</small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-body pt-0">
                    <div class="">
                        <div id="chart-output" style="margin-bottom: 1em;" class="chart-display"></div>
                        <table class="table-bordered display nowrap compact" id="datatable-Actividades" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre actividad:</th>
                                    <th>Progreso:</th>
                                    <th>Inicio:</th>
                                    <th>Vencimiento:</th>
                                    <th>Estado:</th>
                                    <th style="width: 10%">Empresa:</th>
                                    <th>Responsable:</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
    @livewireStyles
    @livewire('reasignar-actividad')
    @livewire('cambiar-estado-actividad')

@endsection

@section('scripts')
    <script>
        let table;
        let selectedRows = [];
        let selectedEmpresa = [];

        let inicial = document.getElementById('inicial');
        let final = document.getElementById('final');

        //la fecha final no puede ser menor a la fecha inicial
        inicial.addEventListener('change', function() {
            final.min = inicial.value;
        });

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Actividades', {
                language: {
                    url: '{{ asset('/js/datatable/Spanish.json') }}',
                },
                layout: {
                    topStart: 'pageLength',
                    topEnd: {
                        buttons: [{
                                text: '<i class="fas fa-exchange-alt"></i> Reasignar',
                                className: 'btn btn-back border rounded-pill',
                                attr: {
                                    'style': 'display:' + isAuthReasignar + ';',
                                    'title': 'Tip: Para reasignar las actividades debes seleccionar registros de una misma empresa',
                                },
                                action: function() {
                                    //abrir modal empresa y usuarios de la empresa
                                    if (selectedEmpresa.length > 1) {
                                        // Livewire.dispatch('getIdsReasignarActividad', { ids: selectedEmpresa });    
                                        selectedEmpresa = [];
                                        selectedRows = [];
                                        table.rows().deselect();

                                        //alerta de seleccionar una sola empresa con sweetalert
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Atención',
                                            text: 'Para reasignar las actividades debes seleccionar registros de una misma empresa',
                                            showConfirmButton: false,
                                            timer: 15000
                                        });

                                    }

                                    if (selectedEmpresa.length == 1) {
                                        //enviar los id de las actividades seleccionadas al componente livewire
                                        Livewire.dispatch('getIdsReasignarActividad', {
                                            ids: selectedRows,
                                            empresa: selectedEmpresa
                                        });
                                        selectedRows = [];
                                        selectedEmpresa = [];
                                    }

                                },
                            },
                            {
                                text: '<i class="fas fa-retweet"></i> Cambiar estado',
                                className: 'btn btn-back border rounded-pill',
                                attr: {
                                    'style': 'display:' + isAuthEstado + ';',
                                    'title': 'Cambiar  estado de las actividades seleccionadas',
                                },
                                action: function() {
                                    //abrir modal lista de estados
                                    if (selectedRows.length > 0) {
                                        //enviar los id de las actividades seleccionadas al componente livewire                             
                                        Livewire.dispatch('getIdsCambiarEstado', {
                                            ids: selectedRows
                                        });
                                        selectedRows = [];
                                    }
                                },
                            },
                            {
                                text: '<i class="far fa-check-square"></i> Seleccionar todos',
                                className: 'btn btn-back border rounded-pill',
                                attr: {
                                    'style': 'display:' + isAuthReasignar + ';',
                                },
                                action: function() {
                                    table.rows().select();
                                }
                            },
                            {
                                text: '<i class="far fa-window-close"></i> Desmarcar todos',
                                className: 'btn btn-back border rounded-pill',
                                attr: {
                                    'style': 'display:' + isAuthReasignar + ';',
                                },
                                action: function() {
                                    table.rows().deselect();
                                    selectedRows = [];
                                }
                            },
                            {
                                // extend: 'clearFilter',
                                text: '<span title="Quitar todas las opciones del filtro"><i class="fas fa-eraser"></i> Quitar filtros </span>',
                                className: 'btn btn-back border rounded-pill',
                                action: function(e, dt, node, config) {
                                    document.querySelectorAll('.column_filter').forEach((el) => {
                                        el.value = '';
                                    });
                                    //limpiar los filtros de los selects
                                    document.getElementById('estado').value = '';
                                    document.getElementById('empresa').value = '';
                                    document.getElementById('responsable').value = '';
                                    document.getElementById('inicial').value = '';
                                    document.getElementById('final').value = '';

                                    table.columns().search('')
                                        .draw(); //limpiar los filtros de las columnas
                                    table.order([0, 'asc']).draw(); //limpiar el ordenamiento
                                }
                            },
                        ]
                    },
                    bottomEnd: {
                        paging: {
                            type: 'simple_numbers',
                            numbers: 5,
                        }
                    }
                },
                ordering: true,
                //ordenar por la columna 0 de forma ascendente
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // ID tiene la máxima prioridad
                    {
                        responsivePriority: 2,
                        targets: 1
                    }, // Nombre tiene la segunda prioridad más alta
                    {
                        responsivePriority: 3,
                        targets: 5
                    }, // Nombre tiene la segunda prioridad más alta
                    {
                        responsivePriority: 10000,
                        targets: -1
                    },
                    {
                        responsivePriority: 10001,
                        targets: 3
                    }, // Usuario tiene baja prioridad
                    {
                        responsivePriority: 10002,
                        targets: 2
                    }, // Usuario tiene baja prioridad
                    {
                        className: 'none',
                        targets: [3]
                    }
                ],
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.actividad_cliente.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'id',
                        className: 'text-center cursor-pointer',
                    },
                    {
                        data: 'nombre',
                    },
                    {
                        data: 'progreso',
                        render: function(data, type, row) {
                            return data + '%';
                        }
                    },
                    {
                        data: 'reporte_actividades.fecha_inicio',
                        defaultContent: '<i class="text-danger"> Sin iniciar</i>',
                    },
                    {
                        data: 'fecha_vencimiento',
                        render: function(data, type, row) {
                            return data.split('-').reverse().join('/');
                        }
                    },
                    {
                        data: 'reporte_actividades.estado_actividades.nombre',
                    },
                    {
                        data: 'empresa_asociada.razon_social',
                        className: 'text-start',
                        render: function(data, type, row) {
                            return row.empresa_asociada_id ? row.empresa_asociada.razon_social : row
                                .cliente.razon_social;
                        },
                    },
                    {
                        data: 'usuario_id',
                        className: 'text-start',
                        render: function(data, type, row) {
                            return row.usuario_id ? row.usuario.nombres + ' ' + row.usuario
                                .apellidos : 'Sin responsable';
                        },
                    },
                    {
                        data: 'actions',
                        orderable: false,
                    },
                ],
                select: {
                    style: 'multi',
                    className: 'selected-row',
                },
                initComplete: function() {

                    //INPUTS
                    // console.log(this.api().ajax.json().data);
                    //Filtrar por columnas escribiendo en un input
                    function filterColumn(table, i) {
                        let filter = document.querySelector('#col' + i + '_filter');
                        table.column(i).search(filter.value).draw();
                    }

                    document.querySelectorAll('input.column_filter').forEach((el) => {
                        let tr = el.closest('div');
                        let columnIndex = tr.getAttribute('data-column');

                        el.addEventListener(el.type === 'text' ? 'keyup' : 'change', () =>
                            filterColumn(table, columnIndex)
                        );
                    });

                    //SELECTS
                    // estado
                    this.api().columns(5).every(function() {
                        let column = this;
                        let select = document.getElementById('estado');

                        select.addEventListener('change', function() {
                            column.search(select.value, {
                                exact: true
                            }).draw();
                        });
                    });

                    //Empresa
                    this.api().columns(6).every(
                        function() {
                            let column = this;
                            let select = document.getElementById('empresa');
                            select.addEventListener('change', function() {
                                column.search(select.value, {
                                    exact: true
                                }).draw();
                            });
                        });

                    //Responsable
                    this.api().columns(7).every(function() {
                        let column = this;
                        let select = document.getElementById('responsable');
                        select.addEventListener('change', function() {
                            column.search('^' + select.value + '$', true, false).draw();
                        });
                    });

                    // vencimiento
                    this.api().columns(4).every(function() {
                        let column = this;
                        let inicial = document.getElementById('inicial');
                        let final = document.getElementById('final');

                        inicial.addEventListener('change', function() {
                            let startDate = new Date(inicial.value);
                            let endDate = new Date(final.value);
                            let dates = [];
                            while (startDate <= endDate) {
                                dates.push(startDate.toISOString().split('T')[0]);
                                startDate.setDate(startDate.getDate() + 1);
                            }
                            column.search(dates.join('|'), true, false).draw();
                        });
                        final.addEventListener('change', function() {
                            let startDate = new Date(inicial.value);
                            let endDate = new Date(final.value);
                            let dates = [];
                            while (startDate <= endDate) {
                                dates.push(startDate.toISOString().split('T')[0]);
                                startDate.setDate(startDate.getDate() + 1);
                            }
                            column.search(dates.join('|'), true, false).draw();
                        });
                    });


                    //obtener el id de las filas seleccionadas y guardarlas en un array y mostrarlas en consola                    
                    table.on('select', function(e, dt, type, indexes) {
                        let rowData = table.rows(indexes).data().toArray();
                        rowData.forEach((row) => {
                            selectedRows.push(row.id);
                        });
                    });

                    table.on('deselect', function(e, dt, type, indexes) {
                        let rowData = table.rows(indexes).data().toArray();
                        rowData.forEach((row) => {
                            selectedRows = selectedRows.filter((id) => id !== row.id);
                        });
                        selectedRows = [];
                        selectedEmpresa = [];
                    });

                    //al pasar de pagina borrar las filas seleccionadas
                    table.on('page.dt', function() {
                        table.rows().deselect();
                        selectedRows = [];
                        selectedEmpresa = [];
                    });

                    //al ordenar las filas borrar las filas seleccionadas
                    table.on('order.dt', function() {
                        table.rows().deselect();
                        selectedRows = [];
                        selectedEmpresa = [];
                    });


                    //obtener empresa_asociada.razon_social de las filas seleccionadas y guardarlas en un array y mostrarlas en consola
                    table.on('select', function(e, dt, type, indexes) {
                        let rowData = table.rows(indexes).data().toArray();
                        rowData.forEach((row) => {

                            var empresa_asociada = row.empresa_asociada ? row.empresa_asociada.razon_social : ' ';
                            var empresa = row.cliente ? row.cliente.razon_social : ' ';
                          
                            if (empresa != ' ') {
                                if (!selectedEmpresa.includes(empresa)) {
                                    selectedEmpresa.push(empresa);
                                }
                            } else  if (empresa_asociada != ' ') {
                                if (!selectedEmpresa.includes(empresa_asociada)) {
                                    selectedEmpresa.push(empresa_asociada);
                                }
                            }


                        });
                    });


                } //intiComplete


            });

        });

        //actualizar la tabla despues de reasignar una actividad
        Livewire.on('actualizarTablaActividades', () => {
            table.ajax.reload();
            selectedRows = [];
            selectedEmpresa = [];
        });
    </script>

@endsection
