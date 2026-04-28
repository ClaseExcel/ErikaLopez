@extends('layouts.admin')
@section('title', 'Actualizar requerimiento')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.requerimientos.empleado.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header fs-5">
                    Requerimiento {{ $requerimiento->requerimientos->consecutivo }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.requerimientos.empleado.update', $requerimiento->id) }}"
                        id="edit-requerimiento">
                        @csrf
                        @method('PUT')

                        @include('admin.requerimiento-empleado.fields')

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script>
        let estado_requerimiento = $('#estado_requerimiento').val();
        let fecha = document.getElementById('fecha');
        let responsable = document.getElementById('responsable');

        $('#estado_requerimiento').change(function() {
            if ($(this).val() == 3) {
                fecha.style.display = 'none';
                responsable.style.display = 'none';
            } else {
                fecha.style.display = 'block';
                responsable.style.display = 'block';
            }
        });

        window.onload = function() {
            var inputsFecha = document.querySelectorAll('input[type="date"]'); // Obtener todos los inputs de tipo date

            var fechaActual = new Date(); // Fecha actual
            var mes = fechaActual.getMonth() + 1; // Obtener el mes actual
            var dia = fechaActual.getDate(); // Obtener el día actual
            var anio = fechaActual.getFullYear(); // Obtener el año actual

            if (dia < 10) dia = '0' + dia; // Agregar un cero si el día es menor a 10
            if (mes < 10) mes = '0' + mes; // Agregar un cero si el mes es menor a 10

            var fechaMinima = anio + '-' + mes + '-' + dia; // Crear la fecha mínima en formato yyyy-mm-dd

            // Establecer la fecha mínima en todos los inputs de fecha
            for (var i = 0; i < inputsFecha.length; i++) {
                inputsFecha[i].setAttribute('min', fechaMinima);
            }
        }

        if (estado_requerimiento == 3) {
            fecha.style.display = 'none';
            responsable.style.display = 'none';
        } else if (estado_requerimiento == 2) {
            fecha.style.display = 'block';
            responsable.style.display = 'block';
        }
    </script>
@endsection
