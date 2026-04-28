@extends('layouts.admin')
@section('title', 'Seguimiento de cotización: ' . $cotizacion->numero_cotizacion)
@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.cotizaciones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <form method="POST" action="{{ route('admin.cotizacion-seguimiento.store', $cotizacion->id) }}"
                    enctype="multipart/form-data" id="cotizacion">
                    @csrf
                    <div class="card-header">
                        <span class="fw-normal"><i class="fa-solid fa-file-invoice"></i>&nbsp; Seguimiento de la
                            cotización: <span class="fw-bold">#{{ $cotizacion->numero_cotizacion }}</span> </span>
                    </div>
                    <div class="card-body">

                        @include('admin.cotizaciones.seguimiento.fields')


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
        var routeCliente = null;
        var routeCreate = null;
        var routeEdit = "{{ route('admin.cotizaciones.edit', $cotizacion->id) }}";
    </script>
    <script src="{{ asset('js/cotizaciones/cotizaciones.js') }}" defer></script>
@endsection
