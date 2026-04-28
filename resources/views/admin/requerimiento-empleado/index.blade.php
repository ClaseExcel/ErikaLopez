@extends('layouts.admin')
@section('title', 'Requerimientos empleados')
@section('library')

    @include('cdn.datatables-head')

    <style>
        .cursor-pointer {
            cursor: help;
        }
    </style>

@endsection
@section('content')

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Requerimientos empleados
                </div>

                <div class="card-body">
                    <div class="">
                        <table class="table-bordered table-striped display nowrap compact" id="datatable-Requerimientos"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        Consecutivo
                                    </th>
                                    <th>
                                        Descripcion
                                    </th>
                                    <th>
                                        Tipo de requerimiento
                                    </th>
                                    <th>
                                        Empresa que solicitó
                                    </th>
                                    <th>
                                        Estado
                                    </th>
                                    <th>
                                        Fecha de creación
                                    </th>
                                    <th>
                                        Fecha de vencimiento
                                    </th>
                                    <th>
                                        Fecha de finalización
                                    </th>
                                    <th>
                                        Responsable
                                    </th>
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
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        let table;
        let routeExport = "{{ route('admin.requerimientos.export') }}";

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Requerimientos', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['search'],
                    topEnd: [{
                        buttons: [{
                            text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                            className: 'btn btn-back border rounded-pill mb-2',
                            action: function(e, dt, node, config) {
                                window.location.href = routeExport;
                            },
                        }]
                    }, 'pageLength'],
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
                    },
                    {
                        responsivePriority: 1,
                        targets: 7
                    },
                    {
                        responsivePriority: 1,
                        targets: 5
                    },
                    {
                        responsivePriority: 1,
                        targets: 6
                    },
                    {
                        responsivePriority: 2,
                        targets: 4
                    },
                    {
                        responsivePriority: 3,
                        targets: 2
                    },
                    {
                        responsivePriority: 4,
                        targets: 3
                    },
                    {
                        responsivePriority: 5,
                        targets: 4
                    },
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
                    },
                ],
                responsive: true,
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.requerimientos.empleado.index') }}",
                    type: "GET",
                    dataType: 'json',
                },
                columns: [{
                        data: 'requerimientos.consecutivo',
                        name: 'requerimientos.consecutivo',
                    },
                    {
                        data: 'requerimientos.descripcion',
                        name: 'requerimientos.descripcion',
                    },
                    {
                        data: 'requerimientos.tipo_requerimientos.nombre',
                        name: 'requerimientos.tipo_requerimientos.nombre',
                    },
                    {
                        data: 'empresa.razon_social',
                        name: 'empresa.razon_social',
                    },
                    {
                        data: 'estado_requerimientos.nombre',
                        name: 'estado_requerimientos.nombre',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (data == 'Enviado') {
                                    return `<span class="badge rounded-pill w-100 bg-success"> ${data} </span>`;
                                } else if (data == 'Aceptado') {
                                    return `<span class="badge rounded-pill w-100 bg-primary"> ${data} </span>`;
                                } else if (data == 'Rechazado') {
                                    return ` <span class="badge rounded-pill w-100 bg-danger"> ${data} </span>`;
                                } else if (data == 'En proceso') {
                                    return `<span class="badge rounded-pill w-100 bg-warning"> ${data} </span>`;
                                } else if (data == 'Finalizado') {
                                    return `<span class="badge rounded-pill w-100 bg-info"> ${data} </span>`;
                                } else if (data == 'Desistió') {
                                    return `<span class="badge rounded-pill w-100 bg-secondary"> ${data} </span>`;
                                }
                            }

                            return data;
                        },
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return data ? moment(data).format('DD/MM/YYYY h:mm a') : '';
                        }
                    },
                    {
                        data: 'fecha_vencimiento',
                        name: 'fecha_vencimiento',
                        render: function(data, type, row) {
                            return row.fecha_vencimiento ? row.fecha_vencimiento :
                                '<span class="fst-italic text-secondary"> Sin fecha de vencimiento </span>';
                        },
                    },
                    {
                        data: 'fecha_fin',
                        name: 'fecha_fin',
                        render: function(data, type, row) {
                            return row.fecha_fin ? row.fecha_fin :
                                '<span class="fst-italic text-secondary"> Sin fecha de finalización </span>';
                        },
                    },
                    {
                        data: 'user_id',
                        render: function(data, type, row) {
                            return row.user_id ? row.usuario_responsable.nombres + ' ' + row
                                .usuario_responsable
                                .apellidos :
                                '<span class="fst-italic text-secondary"> Sin responsable </span>';
                        },
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searcheable: false,
                        orderable: false,
                        className: 'actions-size'
                    },
                ],
            });

        });
    </script>
@endsection
