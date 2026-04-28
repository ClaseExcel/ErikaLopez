@extends('layouts.admin')
@section('title', 'Solicitar requerimiento')
@section('content')


    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-file-alt"></i> Solicitar requerimiento
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.requerimientos.cliente.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        @include('admin.requerimiento-cliente.fields')

                        @can('CREAR_SOLICITAR_REQUERIMIENTOS')
                            <div class="form-group text-end">
                                <button class="btn btn-save btn-radius px-4" type="submit">
                                    Guardar
                                </button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        const MAXIMO_TAMANIO_BYTES = 25 * 1000000;

        // Obtener referencia al elemento
        const miInput = document.querySelector("#files");
        miInput.addEventListener("change", function() {
            const archivos = this.files;
            let totalTamanio = 0;

            // Si no hay archivos, regresamos
            if (archivos.length <= 0) return;

            for (let i = 0; i < archivos.length; i++) {
                totalTamanio += archivos[i].size;
            }

            if (totalTamanio > MAXIMO_TAMANIO_BYTES) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se permiten más de 25 MB en total',
                })

                miInput.value = "";
            }
        });

        $('#empresa_id').change(function() {
            let empresa = $(this).val();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: 'GET',
                url: 'empleados/' + empresa,
                success: function(empleados) {

                    let empresas = JSON.parse(empleados);

                    $('#empleado_id').empty();

                    $("#empleado_id").append('<option value="">Selecciona un empleado</option>');

                    $.each(empresas, function(index, value) {
                        $("#empleado_id").append('<option value=' + value.id + '>' + value
                            .nombres + ' ' + value.apellidos + '</option>');
                    });
                }
            })
        });
    </script>
@endsection
