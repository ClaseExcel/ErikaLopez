@extends('layouts.admin')
@section('title', 'Mis requerimientos')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    <div class="row">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Mis requerimientos
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
                                        Estado
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
        function desistir(id) {
            Swal.fire({
                title: '¿Deseas desistir?',
                text: "No podrás revertir este requerimiento",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        type: 'POST',
                        url: 'requerimientos-cliente/desistir/' + id,
                        success: function() {
                            Swal.fire(
                                'Completado',
                                'Haz desistido en tu requerimento con éxito.',
                                'success'
                            ).then((result) => {
                                location.reload();
                            });
                        }
                    });
                }
            })
        }
        
    </script>
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
                ajax: "{{ route('admin.requerimientos.cliente.index') }}",
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
