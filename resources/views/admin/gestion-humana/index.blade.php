@extends('layouts.admin')
@section('title', 'Lista gestión humana')
@section('library')

    @include('cdn.datatables-head')

    <style>
        .cursor-pointer {
            cursor: help;
        }
    </style>

@endsection
@section('content')
    @can('CREAR_GESTIÓN_HUMANA')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.gestion-humana.create') }}">
                    <i class="fas fa-plus"></i> Agregar empleado
                </a>
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header row m-0">
            <div class="col-6 d-flex align-items-center"> <i class="fas fa-list"></i>&nbsp; Lista de empleados</div>
            <div class="col-6 text-end">
                <button class="btn btn-back btn-sm  border btn-radius px-4 " data-bs-toggle="modal"
                    data-bs-target="#modalFiltroEventos" title="Exportar novedades">
                    <i class="fa-solid fa-file-export"></i></button>
            </div>
        </div>

        @include('admin.gestion-humana.modal-exportar-eventos')

        <div class="card-body pb-0">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-2">
                    <div class="form-group" id="filter_col1" data-column="1">
                        <input type="text" class="column_filter form-control" id="col1_filter" placeholder="Cédula">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="form-group" id="filter_col2" data-column="2">
                        <input type="text" class="column_filter form-control" id="col2_filter" placeholder="Nombres">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <select class="form-select" id="estado">
                            <option class="text-secondary" value="">Ningún estado</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3 mb-2">
                    <input class="form-control" list="datalistOptionsEmpresa" placeholder="empresa" name="empresa"
                        id="empresa" autocomplete="off">
                    <datalist id="datalistOptionsEmpresa">
                        @foreach ($empresas as $empresa)
                            <option value="{{ $empresa->razon_social }}" data-id="{{ $empresa->id }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-Gestion" class="table table-striped table-bordered nowrap compact" style="width:100%">
                    <thead>
                        <tr>
                            <th>Tipo: </th>
                            <th>
                                Cédula:
                            </th>
                            <th>
                                Nombres:
                            </th>
                            <th>
                                Estado:
                            </th>
                            <th>
                                Correo:
                            </th>
                            <th>
                                Teléfono:
                            </th>
                            <th>
                                Municipio de residencia:
                            </th>
                            <th>
                                Empresa:
                            </th>
                            <th>
                                Tipo de contrato:
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
            var gestion = id;
            var route = "{{ route('admin.gestion-humana.destroy', ':id') }}".replace(':id', gestion);
            var mensaje = estado === 'ACTIVO' ? "¿Deseas inactivar este usuario?" : "¿Deseas activar este usuario?";
            var titulo = estado === 'ACTIVO' ? "Inactivado" : "Activado";
            var texto = estado === 'ACTIVO' ? "El usuario ha sido inactivado exitosamente" :
                "El usuario ha sido activado exitosamente";

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

        let table;
        let routeExport = "{{ route('admin.gestion-humana.export') }}";

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Gestion', {
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
                                    document.getElementById('empresa').value = '';

                                    table.columns().search('')
                                        .draw(); //limpiar los filtros de las columnas
                                    table.order([0, 'desc']).draw(); //limpiar el ordenamiento
                                }
                            },
                            {
                                text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                                className: 'btn btn-back border rounded-pill',
                                action: function() {
                                    $.ajax({
                                        url: routeExport,
                                        type: 'POST',
                                        headers: {
                                            "X-CSRF-TOKEN": $(
                                                'meta[name="csrf-token"]').attr(
                                                "content")
                                        },
                                        data: [{
                                                name: 'cedula',
                                                value: document.getElementById(
                                                    'filter_col1').value
                                            },
                                            {
                                                name: 'nombres',
                                                value: document.getElementById(
                                                    'filter_col2').value
                                            },
                                            {
                                                name: 'empresa',
                                                value: document.getElementById(
                                                    'empresa').value
                                            },
                                        ],
                                        success: function(response) {
                                            Swal.fire({
                                                title: "Exportado",
                                                text: "El archivo ha sido exportado exitosamente",
                                                icon: "success"
                                            }).then(() => window.location.href =
                                                response.url);
                                        },
                                        error: function(xhr) {
                                            Swal.fire({
                                                title: xhr.responseJSON.icon ?
                                                    "Sin información" : "Error",
                                                text: xhr.responseJSON
                                                    .message ||
                                                    "No se pudo exportar el archivo.",
                                                icon: xhr.responseJSON.icon ||
                                                    "error"
                                            });
                                        }
                                    });
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
                columnDefs: [{
                        targets: 0,
                        responsivePriority: 1
                    }, // Cedula (siempre visible)
                    {
                        targets: 9,
                        responsivePriority: 2
                    }, // Acciones (siempre visible)
                    {
                        targets: [4, 5, 6],
                        className: 'none'
                    }, // Ocultar en móvil
                ],
                responsive: true,
                // searching: true,
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.gestion-humana.index') }}",
                    type: "GET",
                    dataType: 'json',
                },
                columns: [{
                        data: 'tipo_identificacion',
                        name: 'tipo_identificacion',
                        width: '10px',
                        render: function(data, type, row) {
                            // Verifica si hay datos y extrae el contenido entre paréntesis
                            const match = data ? data.match(/\(([^)]+)\)/) : null;
                            // Si hay coincidencia, devuelve el contenido entre paréntesis; si no, devuelve cadena vacía
                            return match ? `(${match[1]})` : '―';
                        }
                    },
                    {
                        data: 'cedula',
                        name: 'cedula',
                    },
                    {
                        data: 'nombres',
                        name: 'nombres',
                    },
                    {
                        data: 'estado_usuario',
                        name: 'gestion_humana.estado',
                        render: function(data, type, row) {
                            return data == "ACTIVO" ?
                                '<div class="alert-success rounded-pill text-center text-uppercase px-3"><p class="m-0 p-0" style="font-size:12px">Activo</p></div>' :
                                '<div class="alert-danger rounded-pill text-center text-uppercase px-3"><p class="m-0 p-0" style="font-size:12px">Inactivo</p></div>';
                        }
                    },
                    {
                        data: 'correo_electronico',
                        name: 'correo_electronico',
                    },
                    {
                        data: 'telefono',
                        name: 'telefono',
                        render: function(data, type, row) {
                            //validar si el numero de contacto es nulo
                            if (data == null) {
                                return '―';
                            }
                            return data.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                        }
                    },
                    {
                        data: 'municipio_residencia',
                        name: 'municipio_residencia',
                    },
                    {
                        data: 'empresa.razon_social',
                        name: 'empresa.razon_social',
                        render: function(data, type, row) {
                            //validar si el campo empresa es nulo
                            if (data == null) {
                                return '―';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'tipo_contrato',
                        name: 'tipo_contrato',
                        render: function(data, type, row) {
                            //validar si el campo tipo_contrato es nulo
                            if (data == null) {
                                return '―';
                            }
                            return data;
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
                initComplete: function() {

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

                    this.api().columns(3).every(function() {
                        let column = this;
                        let select = document.getElementById('estado');

                        select.addEventListener('change', function() {
                            column.search('^' + select.value + '$', true, false).draw();
                        });
                    });

                    this.api().columns(7).every(function() {
                        let column = this;
                        let select = document.getElementById('empresa');

                        select.addEventListener('change', function() {
                            column.search('^' + select.value + '$', true, false).draw();
                        });
                    });


                } //intiComplete


            });

        });
    </script>
@endsection
