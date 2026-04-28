@extends('layouts.admin')
@section('title', 'Agregar Rol')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_ROL')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.roles.create') }}">
                    <i class="fas fa-user-tag"></i> Agregar Rol
                </a>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de roles
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Role" style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                Título
                            </th>
                            <th>
                                Permisos
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
    @parent
    <script>
        function eliminar(id) {
            var role_id = id;

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
                        url: 'roles/' + role_id,
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
            table = new DataTable('#datatable-Role', {
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
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.roles.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'permissions.title',
                        name: 'permissions.title',
                        searcheable: false,
                        orderable: false,
                        className: 'text-wrap',
                        render: function(data, type, row) {
                            var permissions = '';
                            row.permissions.forEach(function(item) {
                                permissions +=
                                    '<span class="badge bg-light rounded-pill border px-2 mr-2 fw-normal"><i class="fas fa-check-circle text-success"></i> ' +
                                    item.title + '</span> ';
                            });
                            return permissions;
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
                    //Lógica
                }

            });

        });
    </script>
@endsection
