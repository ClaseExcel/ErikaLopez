@extends('layouts.admin')
@section('title', 'Agregar Cotización')
@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.cotizaciones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <form method="POST" action="{{ route('admin.cotizaciones.store') }}" enctype="multipart/form-data"
                    id="cotizacion">
                    @csrf
                    <div class="card-header">
                        <div class="row d-flex justify-content-between">
                            <div class="col-4 d-flex align-items-center">

                                <span class="fw-normal"><i class="fa-solid fa-receipt fa-lg"></i>&nbsp; Datos de la
                                    cotización </span>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <div class="input-group">
                                    <span class="input-group-text border-dark text-white"
                                        style="width: 40px;background-color:#000000;color:#ffffff;">
                                        #
                                    </span>
                                    <input type="hidden" id="ultima_cotizacion"
                                        value="{{ $cotizaciones ? $cotizaciones->numero_cotizacion : 749 }}">
                                    <input type="text" class="form-control shadow-none border-none bg-white"
                                        id="numero_cotizacion" name="numero_cotizacion" aria-label="digito_verificacion"
                                        aria-describedby="basic-addon2" value="" readonly>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="card-body">

                        @include('admin.cotizaciones.fields')


                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        var routeCliente = "{{ route('admin.informacion-empresa') }}";
        var routeCreate = "{{ route('admin.cotizaciones.create') }}";
        var routeEdit = null;
    </script>
    <script src="{{ asset('js/cotizaciones/cotizaciones.js') }}" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('cliente').addEventListener('keyup', function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
@endsection
