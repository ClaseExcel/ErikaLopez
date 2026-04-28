@extends('layouts.admin')
@section('title', 'Crear agenda')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-back  border btn-radius" onclick="location.href='{{ route('admin.agendas.index') }}'">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-day"></i> Agendar
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agendas.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('admin.agenda.fields')

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                {{-- <i class="fas fa-save"></i> --}}
                                Guardar
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
        // Evitar fechas anteriores a la actual
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


        $('#modalidadCreate').change(function() {
            var virtual = document.getElementById('virtual');
            var fisica = document.getElementById('fisica');

            if ($(this).val() == 1) {
                fisica.style.display = 'none';
                virtual.style.display = 'block';
            } else {
                virtual.style.display = 'none';
                fisica.style.display = 'block';
            }
        });

        var virtual = document.getElementById('virtual');
        var fisica = document.getElementById('fisica');

        if (virtual == 1) {
            fisica.style.display = 'none';
            virtual.style.display = 'block';
        } else if (virtual == 2) {
            virtual.style.display = 'none';
            fisica.style.display = 'block';
        }
    </script>
@endsection
