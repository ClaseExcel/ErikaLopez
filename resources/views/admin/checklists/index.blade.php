@extends('layouts.admin')
@section('title', 'Checklist Contable Empresas')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    @can('CREAR_CHECKLIST_CONTABLE')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius"
                    onclick="location.href='{{ route('admin.checklist_empresas.create') }}'">
                    <i class="fas fa-plus"></i> Crear checklist
                </a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-12 col-md-12">

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Listar checklist contable empresas
                </div>


                <div class="card-body pb-0 mb-0">
                    <div class="row mb-3">
                        @if (Auth::user()->role_id == 1)
                            <div class="col-6 col-md-4 mb-0">
                                <div class="form-group">
                                    <select class="form-select" id="responsable">
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
                    </div>
                </div>


                <div class="card-body">
                    <div class="">
                        <table class="table-bordered table-striped display nowrap compact" id="datatable-Checklist"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Año</th>
                                    <th>Mes que se esta realizando</th>
                                    <th>Fecha creación</th>
                                    <th>Usuario quién actualiza</th>
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

    @include('admin.checklists.modal-actividades')
@endsection


@section('scripts')
    @parent
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Checklist', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['pageLength'],
                    topEnd: {
                        buttons: [{
                            // extend: 'clearFilter',
                            text: '<span title="Quitar todas las opciones del filtro"><i class="fas fa-eraser"></i> Quitar filtros </span>',
                            className: 'btn btn-back border rounded-pill',
                            action: function(e, dt, node, config) {
                                // document.querySelectorAll('.column_filter').forEach((el) => {
                                //     el.value = '';
                                // });
                                document.getElementById('responsable').value = '';
                                document.getElementById('empresa').value = '';

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
                    [0, 'desc']
                ],
                responsive: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.checklist_empresas.index') }}",
                    type: "GET",
                    dataType: 'json',
                    data: function(d) {
                        d.filter = $('#responsable').val(); // Enviar el valor del filtro
                    },
                },
                columns: [{
                        data: 'empresa.razon_social',
                        name: 'empresa.razon_social',
                    },
                    {
                        data: 'año',
                        name: 'año',
                    },
                    {
                        data: 'mes',
                        name: 'mes',
                        render: function(data, type, row) {
                            return data ? '<span class="text-uppercase">' + moment(data).locale(
                                'es').format('MMMM') + '</span>' : '―';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return data ? moment(data).format('DD/MM/YYYY h:mm a') : '';
                        }
                    },
                    {
                        data: 'user_act.nombres',
                        name: 'user_act.nombres',
                        render: function(data, type, row) {
                            return row.user_act ? row.user_act.nombres + ' ' + row.user_act
                                .apellidos : '';
                        }
                    },
                    {
                        data: 'user_act.apellidos',
                        name: 'user_act.apellidos',
                        visible: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searcheable: false,
                        orderable: false,
                        className: 'actions-size',
                        render: function(data, type, row) {

                            var button = `<a class="btn-editar px-2 py-0 ver_actividades" 
                                data-bs-toggle="modal" 
                                data-actividadid="${row.id}" 
                                data-bs-target="#modalInformacion" 
                                title="Ver seguimiento actividades"><i class="fa-solid fa-list-check"></i></a>`;

                            return data + button;
                        }
                    },
                ],
                // select: {
                //     style: 'multi',
                //     className: 'selected-row',
                // },
                initComplete: function() {

                    //Recarga la tabla con el valor que he seleccionado en responsables
                    $('#responsable').change(function() {
                        table.ajax.reload();
                    });

                    //Empresa
                    this.api().columns(0).every(
                        function() {
                            let column = this;
                            let select = document.getElementById('empresa');
                            select.addEventListener('change', function() {
                                column.search(select.value, {
                                    exact: true
                                }).draw();
                            });
                        });
                } //intiComplete


            });
        });
    </script>
@endsection
