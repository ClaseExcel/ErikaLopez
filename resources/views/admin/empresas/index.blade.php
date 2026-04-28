@extends('layouts.admin')
@section('title', 'Lista de Empresas ')
@section('library')

    @include('cdn.datatables-head')

    <style>
        .cursor-pointer {
            cursor: help;
        }
    </style>

@endsection
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12 mb-2">
            @can('CREAR_EMPRESAS')
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.empresas.create') }}">
                    <i class="fas fa-plus"></i> Agregar empresa
                </a>
            @endcan
            @can('CREAR_METAS_EMPRESAS')
                <a class="btn btn-back border btn-radius" href="{{ route('admin.metas_empresas.index') }}">
                    <i class="fas fa-plus"></i> Agregar presupuesto de ventas
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de empresas
        </div>

        <div class="card-body pb-0">
            <div class="row">
                <div class="col-12 col-md-2">
                    <div class="form-group" id="filter_col0" data-column="0">
                        <input type="text" class="column_filter form-control" id="col0_filter" placeholder="NIT">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="form-group" id="filter_col1" data-column="1">
                        <input type="text" class="column_filter form-control" id="col1_filter"
                            placeholder="Razón Social">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <select class="form-select" id="estado">
                            <option class="text-secondary" value="">Ningún estado</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <select class="form-select" id="tipocliente">
                            <option class="text-secondary" value="">Tipo de cliente</option>
                            <option value="empresa">Empresa</option>
                            <option value="persona">Persona</option>
                        </select>
                    </div>
                </div>

                @if (Auth::user()->role_id == 1)
                    <div class="col-md-3 mb-2">
                        <div class="form-floating">
                            <select name="empleados[]" id="empleados" class="form-select" multiple>
                                @foreach ($empleados as $empleado)
                                    <option value={{ $empleado->id }}>
                                        {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                            <small>Seleccionar un empleado o varios:</small>
                        </div>
                    </div>
                @else
                    <input type="hidden" id="responsable">
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Empresa" style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                NIT
                            </th>
                            <th>
                                Razón social
                            </th>
                            <th>
                                Correo
                            </th>
                            <th>
                                Frecuencia
                            </th>
                            <th>
                                Estado
                            </th>
                            <th>
                                Tipo de cliente
                            </th>
                            <th>
                                Número de contacto
                            </th>
                            <th>Empleados</th>
                            <th style="width: 120px">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        let table;
        let routeExport = "{{ route('admin.empresas.export') }}";

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Empresa', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: 'pageLength',
                    topEnd: {
                        buttons: [{
                                // extend: 'clearFilter',
                                text: '<span title="Quitar todas las opciones del filtro"><i class="fas fa-eraser"></i> Quitar filtros </span>',
                                className: 'btn btn-back border rounded-pill',
                                action: function(e, dt, node, config) {
                                    document.querySelectorAll('.column_filter').forEach((el) => {
                                        el.value = '';
                                    });
                                    //limpiar los filtros de los selects
                                    document.getElementById('estado').value = '';
                                    document.getElementById('tipocliente').value = '';
                                    $('#empleados').val(null).trigger('change');

                                    table.columns().search('')
                                        .draw(); //limpiar los filtros de las columnas
                                    table.order([0, 'desc']).draw(); //limpiar el ordenamiento
                                }
                            },
                            {
                                text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                                className: 'btn btn-back border rounded-pill',
                                action: function(e, dt, node, config) {
                                    window.location.href = routeExport;
                                },
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
                responsive: true,
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.empresas.index') }}",
                    type: "GET",
                    dataType: 'json',
                    data: function(d) {
                        d.filter = $('#empleados').val(); // Enviar el valor del filtro
                    }
                },
                columns: [{
                        data: 'NIT',
                        name: 'NIT',
                    },
                    {
                        data: 'razon_social',
                        name: 'razon_social',
                    },
                    {
                        data: 'correo_electronico',
                        name: 'correo_electronico',
                    },
                    {
                        data: 'frecuencia.nombre',
                        name: 'frecuencia.nombre',
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        render: function(data, type, row) {
                            if (data === '0') {
                                return `
                                <div class="text-center">
                                    <span class="badge rounded-pill w-100 bg-danger fw-normal"> Inactivo </span>
                                </div>
                                `;
                            } else {
                                return `
                                <div class="text-center">
                                    <span class="badge rounded-pill w-100 bg-success fw-normal"> Activo </span>
                                </div>
                                `;
                            }

                            return data;
                        },
                    },
                    {
                        data: 'tipocliente',
                        name: 'tipocliente',
                        render: function(data, type, row) {
                            if (data) {
                                return data.charAt(0).toUpperCase() + data.slice(1);
                            } else {
                                return `<span class="text-secondary fst-italic"> Sin clasificación </span>`;
                            }
                        }

                    },
                    {
                        data: 'numero_contacto',
                        name: 'numero_contacto',
                        className: 'text-center',
                        render: function(data, type, row) {
                            //validar si el numero de contacto es nulo
                            if (data == null) {
                                return '―';
                            }
                            return data.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                        }
                    },
                    {
                        data: 'empleados',
                        render: {
                            _: '[, ].name',
                        },

                        orderable: false,
                        visible: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searcheable: false,
                        orderable: false,
                        className: 'actions-size'
                    },
                ],
                initComplete: function() {

                    $('#empleados').change(function() {
                        table.ajax.reload();
                    });

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


                    this.api().columns(4).every(function() {
                        let column = this;
                        let select = document.getElementById('estado');

                        select.addEventListener('change', function() {
                            console.log(select.value);
                            column.search(select.value, {
                                exact: true
                            }).draw();
                        });
                    });

                    this.api().columns(5).every(function() {
                        let column = this;
                        let select = document.getElementById('tipocliente');

                        select.addEventListener('change', function() {
                            console.log(select.value);
                            column.search(select.value, {
                                exact: true
                            }).draw();
                        });
                    });

                } //intiComplete


            });

        });

        $(document).ready(function() {
            $("#empleados").select2({
                dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
                containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
                closeOnSelect: false // Evitar que se cierre al seleccionar
            });
        });
    </script>
@endsection
