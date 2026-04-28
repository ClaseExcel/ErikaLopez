@extends('layouts.admin')
@section('title', 'Agenda')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    @can('CREAR_DISPONIBILIDAD')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" onclick="location.href='{{ route('admin.agendas.create') }}'">
                    <i class="fas fa-plus"></i> Agendar
                </a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Agenda citas
                </div>

                <div class="card-body">
                    <div class="">
                        <table class="table-bordered table-striped display nowrap compact" id="datatable-Agenda"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        Fecha
                                    </th>
                                    <th>
                                        Hora inicio
                                    </th>
                                    <th>
                                        Hora fin
                                    </th>
                                    <th>
                                        Empresa
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
        function eliminar(id) {
            var agenda = id;
            var route = "{{ route('admin.agendas.destroy', ':id') }}".replace(':id', agenda);

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¿Deseas eliminar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#69c34e",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        type: 'DELETE',
                        url: route,
                        success: function() {

                            Swal.fire({
                                title: "Eliminado",
                                text: "El registro ha sido eliminado exitosamente",
                                icon: "success"
                            }).then((result) => {
                                location.reload();
                            });
                        }
                    })

                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Agenda', {
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
                ajax: "{{ route('admin.agendas.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'agenda.fecha_disponibilidad',
                        name: 'agenda.fecha_disponibilidad',
                    },
                    {
                        data: 'agenda.hora_inicio',
                        name: 'agenda.hora_inicio',
                        render: function(data) {
                            return moment(data, 'HH:mm a').format('hh:mm a');
                        }
                    },
                    {
                        data: 'agenda.hora_fin',
                        name: 'agenda.hora_fin',
                        render: function(data) {
                            return moment(data, 'HH:mm a').format('hh:mm a');
                        }
                    },
                    {
                        data: 'empresa.razon_social',
                        name: 'empresa.razon_social',
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        render: function(data) {
                            switch (String(data)) {
                                case '1':
                                    return '<span class="fst-italic">Cancelado por el cliente</span>';
                                case '2':
                                    return '<span class="fst-italic">Cancelado por la empresa</span>';
                                case '3':
                                    return '<span class="fst-italic">Reprogramado por el cliente</span>';
                                case '4':
                                    return '<span class="fst-italic">Reprogramado por la empresa</span>';
                                case '5':
                                    return '<span class="fst-italic">Realizada</span>';
                                case '6':
                                    return '<span class="fst-italic">Programada</span>';
                                default:
                                    return '<span class="fst-italic">Reservado</span>';
                            }
                        }
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
