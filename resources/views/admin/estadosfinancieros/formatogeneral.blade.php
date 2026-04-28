@extends('layouts.admin')
@section('title',"Informe General")
@section('content')
@section('cdn')
{{--esto funcionaba para un informe distinto que hicieron eliminar pero lo podemos dejar por si lo vuelven a pedir--}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
@endsection
<style>
    /* Estilo para el borde del select */
    .custom-select-border {
        border: 1px solid #363636 ; /* Color y grosor del borde */
        border-radius: 4px; /* Borde redondeado */
        padding: 5px; /* Espaciado interno para el contenido del select */
    }

</style>
<div class="form-group">
    <a class="btn btn-light border btn-radius px-4" href="{{route('admin.estadosfinancieros.store') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>
<div class="card">
    <div class="card-header">
        <i class="fas fa-list"></i> Informe General
    </div>
    <div class="col-12 card-body">
        <form action="{{route('admin.estadosfinancieros.fgeneral') }}" class="informepyc" method='POST' enctype="multipart/form-data">
            @csrf
            <input type="number" hidden name="nit" id="nit" value="{{ $nit}}" >
            <input type="date" hidden name="fechareporte" id="fechareporte" value="{{ $fecha}}" >
            <input type="text" hidden name="tipoinforme" id="tipoinforme" value="{{ $tipoinforme}}" >
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Seleccionar una Opción o Varias: <b class="text-danger">*</b></strong>
                        <div class="btn-group">
                            <button id="selectAllButton" type="button" class="btn btn-outline-info btn-xs  btn-radius px-4" style="border-radius: 5px">Seleccionar Todo</button>
                            <button id="deselectAllButton" type="button" class="btn btn-outline-info btn-xs btn-radius px-4" style="border-radius: 5px">Deseleccionar Todo</button>
                        </div>
                    </div>
                    <select id="multiple-checkboxes" multiple="multiple" class="form-select fw-normal {{ $errors->has('compania') ? 'is-invalid' : '' }} custom-select-border" name="compania[]" required>
                        @foreach($datos as $centrocostos)
                            <option value="{{ $centrocostos->cuenta }}" {{ in_array($centrocostos->cuenta, old('compania', [])) ? 'selected' : '' }}>
                                {{ $centrocostos->cuenta.' - '.$centrocostos->descripcion }}
                            </option>
                        @endforeach 
                    </select>
                    @if($errors->has('compania'))
                        <span class="text-danger">{{ $errors->first('compania') }}</span>
                    @endif
                </div>
            </div>
            <br>
            <div class="card-body p-0 text-center">
                <button class="btn btn-save btn-radius px-4" type="submit">
                    <i class="fa-solid fa-file-signature"></i> Generar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script>   
$(document).ready(function() {
     // Inicializar el campo select múltiple con Select2
     $("#multiple-checkboxes").select2({
            dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
            containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
            closeOnSelect: false // Evitar que se cierre al seleccionar
        });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButton").on("click", function() {
            $("#multiple-checkboxes").find("option").prop("selected", true);
            $("#multiple-checkboxes").trigger("change"); // Actualizar el select2 después de la selección
        });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButton").on("click", function() {
            $("#multiple-checkboxes").find("option").prop("selected", false);
            $("#multiple-checkboxes").trigger("change"); // Actualizar el select2 después de la desselección
        });
      // Mantener el orden de selección en el select múltiple
      $("#multiple-checkboxes").on("select2:select", function (e) {
            var selectedOption = e.params.data;
            var $select = $(this);
            var $option = $select.find('option[value="' + selectedOption.id + '"]');

            // Mover la opción seleccionada al final del select
            var $lastOption = $select.find('option:last-child');
            $option.insertAfter($lastOption);
        });
});
</script>
@endsection