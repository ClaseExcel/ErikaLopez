@extends('layouts.admin')
@section('title', 'Lista de usuarios')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_USUARIOS')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.users.create') }}">
                    <i class="fas fa-plus"></i> Agregar usuario
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de usuarios
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-User" style="width:100%">
                    <thead class="text-start">
                        <tr>
                            <th>
                                Nombres
                            </th>
                            <th>
                                Apellidos
                            </th>
                            <th>
                                Correo
                            </th>
                            <th>
                                Número contacto
                            </th>
                            <th>
                                Estado
                            </th>
                            <th>
                                Rol
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
        function eliminar(id, estado) {
            var user = id;
            var route = "{{ route('admin.users.destroy', ':id') }}".replace(':id', user);
            var mensaje = estado === 'ACTIVO' ? "¿Deseas inactivar este usuario?" : "¿Deseas activar este usuario?";
            var titulo = estado === 'ACTIVO' ? "Inactivado" : "Activado";
            var texto = estado === 'ACTIVO' ? "El usuario ha sido inactivado exitosamente" : "El usuario ha sido activado exitosamente";

            Swal.fire({
            title: "¿Estás seguro?",
            text: mensaje,
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
                    title: titulo,
                    text: texto,
                    icon: "success"
                    }).then((result) => {
                    location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                    title: "Error",
                    text: xhr.responseJSON['message'],
                    icon: "error"
                    });
                }
                })

            }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-User', {
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
                ajax: "{{ route('admin.users.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'nombres',
                        name: 'nombres',
                    },
                    {
                        data: 'apellidos',
                        name: 'apellidos',
                    },
                    {
                        data: 'email',
                        name: 'email',
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
                        data: 'estado',
                        name: 'estado',
                    },
                    {
                        data: 'role.title',
                        name: 'role.title',
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
