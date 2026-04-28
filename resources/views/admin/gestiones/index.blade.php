@extends('layouts.admin')
@section('title', 'Lista de gestiones ')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
    @can('CREAR_GESTION')
        <div class="row mb-3">
            <div class="col-md-6">
                <a class="btn btn-back border btn-radius" href="{{ route('admin.gestiones.create') }}">
                    <i class="fas fa-plus"></i> Agregar gestión
                </a>
            </div>
            <div class="col-md-6 text-end">
                <a class="btn btn-save btn-radius" href="{{ route('admin.gestiones.notificaciones') }}">
                    <i class="fas fa-envelope-open-text"></i> Ver Notificaciones
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Lista de gestión
        </div>

        <div class="card-body">
            <div class="">
                <table class="table-bordered table-striped display nowrap compact" id="datatable-Gestion"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                Fecha de gestión
                            </th>
                            <th>
                                Tipo de gestión
                            </th>
                            <th>
                                Cliente
                            </th>
                            <th>
                                Quién creo la gestión
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

    @include('admin.gestiones.modal-correo')
    @include('admin.gestiones.modal-whatsapp')

@endsection
@section('scripts')
    @parent
    <script>
        // Función para abrir el modal de enviar correo
        // Abrir modal al hacer clic en el botón
        $(document).on('click', '.btn-enviar-correo', function() {
            const gestionId = $(this).data('id');
            $('#correoGestionId').val(gestionId);
            $('#observacionCorreo').val('');
            $('#modalEnviarCorreo').modal('show');
        });

        // Enviar el formulario por AJAX
        $('#formEnviarCorreo').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            Swal.fire({
                title: 'Enviando correo...',
                icon: 'info',
                showConfirmButton: false,
                position: 'top',
                toast: true,
                didOpen: () => {
                    // Elevar el z-index para que esté por encima del modal Bootstrap
                    const swalContainer = Swal.getPopup().parentNode;
                    swalContainer.style.zIndex = '20000';
                }
            });

            $.ajax({
                url: "{{ route('admin.gestiones.enviarCorreo') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                    $('#modalEnviarCorreo').modal('hide');
                      Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Correo enviado',
                        text: response.message,
                        timer: 3000
                    }).then(() => {
                        // Limpiar los campos de observación y correos adicionales
                        document.getElementById('observacionCorreo').value = '';
                        document.getElementById('correos_adicionales').value = '';
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo enviar el correo',
                    });
                }
            });
        });

        $('#datatable-Gestion').on('click', '.btn-enviar-whatsapp', function() {
            const gestionId = $(this).data('id');
            $('#WhatsappGestionId').val(gestionId);
            $('#modalEnviarWhatsapp').modal('show');
        });

        // Enviar el formulario por AJAX
        $('#formEnviarWhatsapp').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            Swal.fire({
                title: 'Enviando notificación...',
                icon: 'info',
                showConfirmButton: false,
                position: 'top',
                toast: true,
                didOpen: () => {
                    // Elevar el z-index para que esté por encima del modal Bootstrap
                    const swalContainer = Swal.getPopup().parentNode;
                    swalContainer.style.zIndex = '20000';
                }
            });

            $.ajax({
                url: "{{ route('admin.gestiones.enviarWhatsapp') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                      Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'WhatsApp enviado',
                        text: response.message,
                        timer: 3000
                    }).then(() => {
                        // Limpiar los campos de observación y correos adicionales
                        document.getElementById('observacionCorreo').value = '';
                        document.getElementById('correos_adicionales').value = '';
                        location.reload();
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const error = jqXHR.responseJSON.error;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jqXHR.responseJSON.error ? error :
                            'No se pudo enviar el WhatsApp',
                    });
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Gestion', {
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
                ajax: "{{ route('admin.gestiones.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [{
                        data: 'fecha_visita',
                        name: 'fecha_visita',
                    },
                    {
                        data: 'tipo_visita',
                        name: 'tipo_visita',
                    },
                    {
                        data: 'cliente.razon_social',
                        name: 'cliente.razon_social',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return data.usuario_create.nombres + ' ' + data.usuario_create
                                .apellidos;
                        },
                        name: 'usuario_create.nombres'
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
