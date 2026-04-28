@extends('layouts.admin')
@section('title', 'Lista de cotizaciones ')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_COTIZACIONES')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.cotizaciones.create') }}">
                    <i class="fas fa-plus"></i> Crear cotización
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de cotizaciones
        </div>

        <div class="card-body pb-0 mb-0">
            <div class="row">
                <div class="col-12 col-md-2 mb-2">
                    <div class="form-group" id="filter_col0" data-column="0">
                        {{-- <label class="fw-normal" for="col0_filter">#</label> --}}
                        <input type="text" class="column_filter form-control" id="col0_filter"
                            placeholder="# Cotización">
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-2">
                    <div class="form-group">
                        <select class="form-select" id="empresa">
                            <option class="text-secondary" value="">Ninguna empresa</option>
                            @foreach ($empresas as $empresa)
                                <option>{{ $empresa->cliente }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-2">
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

                <div class="col-12 col-md-2 mb-2">
                    <div class="form-group">
                        <select class="form-select" name="" id="linea_negocio">
                            <option class="text-secondary" value="">Ninguna línea de negocio</option>
                            <option value="PC">PC</option>
                            <option value="PT">PT</option>
                            <option value="TT">TT</option>
                            <option value="RF">RF</option>
                            <option value="PF">PF</option>
                            <option value="PE">PE</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-2 mb-2">
                    <div class="form-group">
                        <select class="form-select" id="estado">
                            <option class="text-secondary" value="">Ningún estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado }}">{{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-1">
                    <div class="form-group">
                        <input type="date" class="form-control" id="inicial">
                        <small>Fecha inicial de próximo seguimiento</small>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <div class="form-group">
                        <input type="date" class="form-control" id="final">
                        <small>Fecha final de próximo seguimiento</small>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="">
                <table class="table-bordered display nowrap compact" id="datatable-Cotizacion" style="width:100%">
                    <thead>
                        <tr>
                            <th>Numero de cotización:</th>
                            <th>Cliente: </th>
                            <th>Estado:</th>
                            <th>Línea de negocio:</th>
                            <th>Servicio:</th>
                            <th>Fecha del próximo seguimiento:</th>
                            <th>Valor de la propuesta:</th>
                            {{-- <th> Valor del servicio</th> --}}
                            <th style="width: 120px">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            {{-- <th></th> --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        let table;
        let selectedRows = [];
        let selectedEmpresa = [];

        let inicial = document.getElementById('inicial');
        let final = document.getElementById('final');

        // la fecha final no puede ser menor a la fecha inicial
        inicial.addEventListener('change', function() {
            final.min = inicial.value;
        });

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Cotizacion', {
                language: {
                    url: '{{ asset('/js/datatable/Spanish.json') }}',
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
                                document.getElementById('empresa').value = '';
                                document.getElementById('responsable').value = '';
                                document.getElementById('inicial').value = '';
                                document.getElementById('final').value = '';
                                document.getElementById('linea_negocio').value = '';


                                table.columns().search('')
                                    .draw(); //limpiar los filtros de las columnas
                                table.order([0, 'asc']).draw(); //limpiar el ordenamiento
                            }
                        }, ]
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
                    [5, 'desc']
                ],
                responsive: true,
                // columnDefs: [

                // ],
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.cotizaciones.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'numero_cotizacion',
                        className: 'text-center cursor-pointer',
                    },
                    {
                        data: 'cliente',
                    },
                    {
                        data: 'estado_cotizacion',
                    },
                    {
                        data: 'linea_negocio',
                        render: function(data, type, row) {
                            return row.linea_negocio ? data :
                                '<i class="text-secondary fst-italic"> Sin línea de negocio</i>';
                        },
                    },
                    {
                        data: 'servicio_cotizado',
                    },
                    {
                        data: 'fecha_proximo_seguimiento',
                        render: function(data, type, row) {
                            return data ? data : '―';
                        },
                    },
                    {
                        className: 'text-end',
                        data: 'precio_venta',
                        render: $.fn.dataTable.render.number('.', ' ', 0, '$'),
                    },
                    {
                        data: 'responsable_id',
                        visible: false,
                        render: function(data, type, row) {
                            return row.responsable_id ? row.responsable.nombres + ' ' + row
                                .responsable.apellidos : 'Sin responsable';
                        },
                    },
                    // {
                    //     data: 'valor_servicio',
                    //     render: $.fn.dataTable.render.number('.', ' ', 0, '$'),
                    // },
                    {
                        data: 'actions',
                        orderable: false,
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[€,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    var total = api.column(6, { search: 'applied' }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    $(api.column(6).footer()).html('Total: ' + $.fn.dataTable.render.number('.', ',', 0, '$').display(total));
                },
                // select: {
                //     style: 'multi',
                //     className: 'selected-row',
                // },
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
                    //Empresa
                    this.api().columns(1).every(
                        function() {
                            let column = this;
                            let select = document.getElementById('empresa');
                            select.addEventListener('change', function() {
                                column.search('^' + select.value + '$', true, false).draw();
                            });
                        });

                    //Línea de negocio
                    this.api().columns(3).every(function() {
                        let column = this;
                        let select = document.getElementById('linea_negocio');
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
                            column.search(select.value, {
                                exact: true
                            }).draw();
                        });
                    });

                    // estado
                    this.api().columns(2).every(function() {
                        let column = this;
                        let select = document.getElementById('estado');

                        select.addEventListener('change', function() {
                            column.search(select.value, {
                                exact: true
                            }).draw();
                        });
                    });

                    
                    this.api().columns(6).every(function() {
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
                } //intiComplete


            });

        });
    </script>
@endsection
