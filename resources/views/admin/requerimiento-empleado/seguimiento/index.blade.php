@extends('layouts.admin')
@section('title', 'Seguimiento requerimientos cliente')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Seguimiento requerimientos clientes
        </div>


        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Requerimiento" style="width:100%">
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
                                Fecha de vencimiento
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
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Requerimiento', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['search'],
                    topEnd: ['pageLength'],
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
                ajax: "{{ route('admin.seguimientos.cliente.index') }}",
                dataType: 'json',
                type: "POST",
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
                        data: 'fecha_vencimiento',
                        name: 'fecha_vencimiento',
                    },
                    {
                        data: 'user_id',
                        render: function(data, type, row) {
                            return row.user_id ? row.usuario_responsable.nombres + ' ' + row
                                .usuario_responsable
                                .apellidos : 'Sin responsable';
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
                // select: {
                //     style: 'multi',
                //     className: 'selected-row',
                // },
                initComplete: function() {


                } //intiComplete


            });

        });
    </script>

@endsection
