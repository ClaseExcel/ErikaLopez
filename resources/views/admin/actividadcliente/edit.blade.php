@extends('layouts.admin')
@section('title', 'Editar actividad')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.actividad_cliente.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    Editar actividad
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.actividad_cliente.update', $actividad_cliente->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if (in_array(Auth::user()->role_id, [1, 2]))
                            @include('admin.actividadcliente.fields')
                        @else
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_1"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_1" name="documento_1" class="form-control" />
                                    @if ($errors->has('documento_1'))
                                        <span id="documento_1" class="text-danger">
                                            {{ $errors->first('documento_1') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_2"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_2" name="documento_2" class="form-control" />
                                    @if ($errors->has('documento_2'))
                                        <span id="documento_2" class="text-danger">
                                            {{ $errors->first('documento_2') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_3"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_3" name="documento_3" class="form-control" />
                                    @if ($errors->has('documento_3'))
                                        <span id="documento_3" class="text-danger">
                                            {{ $errors->first('documento_3') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_4"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_4" name="documento_4" class="form-control" />
                                    @if ($errors->has('documento_4'))
                                        <span id="documento_4" class="text-danger">
                                            {{ $errors->first('documento_4') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_5"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_5" name="documento_5" class="form-control" />
                                    @if ($errors->has('documento_5'))
                                        <span id="documento_5" class="text-danger">
                                            {{ $errors->first('documento_5') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text bg-transparent" for="documento_6"><i
                                            class="fas fa-file-upload"></i></label>
                                    <input type="file" id="documento_6" name="documento_6" class="form-control" />
                                    @if ($errors->has('documento_6'))
                                        <span id="documento_6" class="text-danger">
                                            {{ $errors->first('documento_6') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                </div>

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
    @parent
    <script>
        let routeCreate = null;
    </script>
    <script src="{{ asset('js/actividadcliente/show.js') }}" defer></script>
    <script>
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
    </script>
@endsection
