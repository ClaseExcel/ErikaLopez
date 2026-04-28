@extends('layouts.admin')
@section('title', 'Agregar Empresa')
@section('content')

    <style>
        .accordion-button:not(.collapsed) {
            background-color: #23222228;
            color: #232222;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 .25rem #23222228;
        }


    </style>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.empresas.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <form method="POST" action="{{ route('admin.empresas.store') }}" enctype="multipart/form-data" id="crearempresa">
        @csrf

        <div class="row">
            <div class="col-12 col-xl-7">
                <div class="card">
                    <div class="card-header">
                        Agregar empresa
                    </div>
                    <div class="card-body">
                        <div class="row"
                            style="max-height:650px;margin-right:-20px; overflow-y:auto; scroll-behavior:smooth;scrollbar-width: normal;
                            scrollbar-color: #999999 #ffffff00;overflow-x: hidden;">
                            @include('admin.empresas.fields')
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-xl-5">                
                {{-- Configurar reglas automaticas --}}
                <div class="card">
                    <div class="card-header py-0 bg-transparent border-bottom-0"></div>
                    <div class="card-body">
        
                        @include('admin.empresas.accordion')
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script>
        var obligacionesMunicipalesData = [];
        let routeUVT = "{{ route('admin.empresas.uvt') }}";
        var routeCreate = "{{ route('admin.empresas.create') }}";
        var routeIndex = "{{ route('admin.empresas.index') }}";
    </script>
    <script src="{{ asset('js/empresas/empresas.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            // Oculta los campos al cargar la página
            $(".campos-ocultos").hide();

            // Cierra el ojo al cargar la página
            $("#toggleCampos i").removeClass('fa-eye');
            $("#toggleCampos i").addClass('fa-eye-slash');

            $("#toggleCampos").click(function() {
                // Cambia el ícono según el estado
                var icono = this.querySelector('i');
                if (icono.classList.contains('fa-eye')) {
                    icono.classList.remove('fa-eye');
                    icono.classList.add('fa-eye-slash');
                } else {
                    icono.classList.remove('fa-eye-slash');
                    icono.classList.add('fa-eye');
                }
                $(".campos-ocultos").toggle();
            });
        });
    </script>
@endsection
