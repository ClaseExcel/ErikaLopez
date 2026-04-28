@extends('layouts.admin')
@section('title', 'Editar')
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


    <form method="POST" action="{{ route('admin.empresas.update', [$empresa->id]) }}" enctype="multipart/form-data"  id="editar-empresa">
        @method('PUT')
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Agregar empresa
                    </div>
                    <div class="card-body">
                        <div class="row" style="max-height:650px;margin-right:-20px; overflow-y:auto; scroll-behavior:smooth;scrollbar-width: normal;
                            scrollbar-color: #999999 #ffffff00;overflow-x: hidden;"">
                            @if ($empresa->id != 1)
                                @livewire('estado-component', ['estado' => $empresa->estado])
                            @endif
                            @include('admin.empresas.fields')
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body"
                        style="max-height:830px; overflow-y:auto; scroll-behavior:smooth;scrollbar-width:thin;
                       scrollbar-color: #999999 #ffffff00;overflow-x: hidden;">
                        @include('admin.empresas.accordion')
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@section('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {

                let empresaId = document.getElementById("companiaedit")?.value;
                let inputCompania = document.getElementById("compania_id");
                let opciones = document.getElementById("datalistOptionsCompanias").options;

                if (empresaId) {

                    for (let option of opciones) {

                        if (option.getAttribute("data-id") == empresaId) {

                            inputCompania.value = option.value;

                            updateCompania(inputCompania);

                            break;
                        }

                    }

                }

            });

        var obligacionesMunicipalesData = {!! json_encode($obligacionesMunicipales) !!};
        var routeCreate = null;
        var routeIndex = "{{ route('admin.empresas.index') }}";
        let routeUVT = "{{ route('admin.empresas.uvt') }}";
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
