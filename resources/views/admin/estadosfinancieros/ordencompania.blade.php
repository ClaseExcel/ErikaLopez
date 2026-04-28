@extends('layouts.admin')
@section('title',"Orden Estados Financieros")
@section('content')
@section('cdn')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.15/dist/css/bootstrap-multiselect.css">
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}
@endsection
<div class="form-group">
    <a class="btn btn-light border btn-radius px-4" href="{{route('admin.estadosfinancieros.store') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>
<div class="card">
    <div class="card-header">
        <i class="fa-solid fa-table"></i> Generador estados financieros
    </div>

    <div class="card-body">
        @if (session('message2'))
                <div class="row px-2">
                    <div class="alert alert-{{ session('color') }} border-0 alert-dismissible fade show d-flex align-items-center"
                        role="alert">
                        <div class="d-flex flex-grow-1">
                            <div>
                                <i class="fa-solid fa-circle-info"></i> <b>{{ session('message2') }}</b>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
        <div class="">
            <form action="{{route('admin.estadosfinancieros.guardar_orden')}}" class="cargarexcel" method='POST' enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-floating">
                                <select class="form-select fw-normal {{ $errors->has('compania') ? 'is-invalid' : '' }}" name="compania" id="compania">
                                    <option value="">Seleccionar una Opción</option>
                                    @php
                                        // Ordenar la colección $companias alfabéticamente
                                        $companiasOrdenadas = $companias->sortBy(function($compania, $id) {
                                            return $compania;
                                        });
                                    @endphp
                                    @foreach($companiasOrdenadas as $id => $compania)
                                        <option value="{{ $id }}" {{ old('compania') == $id ? 'selected' : '' }}>{{ $compania }}</option>
                                    @endforeach
                                </select>
                                <label class="fw-normal" for="compania">Compañia <b class="text-danger">*</b></label>
                                @if($errors->has('compania'))
                                    <span class="text-danger">{{ $errors->first('compania') }}</span>
                                @endif
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fw-normal" for="orden">Orden</label>
                        <div style="padding-bottom: 4px">
                            <span id="agregarTodosBtn" class="btn btn-outline-info btn-xs select-all" style="border-radius: 5px">Agregar todos</span>
                            <span id="quitarTodosBtn" class="btn btn-outline-info btn-xs deselect-all" style="border-radius: 5px">Quitar todos</span>
                        </div>
                        {{-- <select class="form-control select2 {{ $errors->has('orden') ? 'is-invalid' : '' }}" name="orden[]" id="ordenPersonalizadoDiv" multiple required>
                            @foreach($opciones as $id => $opcion)
                                <option value="{{ $id }}" {{ in_array($id, $ordenPersonalizado->orden ?? []) ? 'selected' : '' }}>{{ $id.'-'.$opcion }}</option>
                            @endforeach
                        </select>                         --}}
                        <select class="form-control select2 {{ $errors->has('orden') ? 'is-invalid' : '' }}" name="orden[]" id="ordenPersonalizadoDiv" multiple required>
                            @foreach($opciones as $id => $opcion)
                                <option value="{{ $id }}" {{ in_array($id, $ordenPersonalizado->orden ?? []) ? 'selected' : '' }}>
                                    {{ $id.'-'.$opcion }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('orden'))
                            <span class="text-danger">{{ $errors->first('orden') }}</span>
                        @endif
                    </div>
                    <button class="btn btn-outline-info btn-xs" type="button" id="mostrarCampo">Agregar Nuevo Elemento</button>
                    
                    <div id="campoNuevoElemento" style="display: none;">
                        <br>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-floating">
                                    <input class="form-control {{ $errors->has('nuevo_elemento') ? 'is-invalid' : '' }} " type="text" name="nuevo_elemento" placeholder="Nuevo elemento" value="{{ old('nuevo_elemento', '') }}">
                                    <label class="fw-normal" for="nuevo_elemento">Nombre <b class="text-danger">*</b></label>
                                    @if($errors->has('nuevo_elemento'))
                                        <span class="text-danger">{{ $errors->first('nuevo_elemento') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-floating">
                                    <input class="form-control {{ $errors->has('cuenta') ? 'is-invalid' : '' }} " type="text" name="cuenta" placeholder="Nuevo elemento" value="{{ old('cuenta', '') }}">
                                    <label class="fw-normal" for="cuenta">Agrupador Cuenta <b class="text-danger">*</b></label>
                                    @if($errors->has('cuenta'))
                                        <span class="text-danger">{{ $errors->first('cuenta') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <br>
                    <div class="card-body p-0 text-center">
                        <button class="btn btn-save btn-radius px-4" type="submit">
                            <i class="fa-solid fa-file-signature"></i> Guardar
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.15/dist/js/bootstrap-multiselect.js"></script>
<script>
     // Inicializar el campo select múltiple con Select2
    $("#ordenPersonalizadoDiv").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Mantener el orden de selección en el select múltiple
    $("#ordenPersonalizadoDiv").on("select2:select", function (e) {
            var selectedOption = e.params.data;
            var $select = $(this);
            var $option = $select.find('option[value="' + selectedOption.id + '"]');

            // Mover la opción seleccionada al final del select
            var $lastOption = $select.find('option:last-child');
            $option.insertAfter($lastOption);
    });
    
    $('#mostrarCampo').click(function() {
        // Alternar la visibilidad del campo
        $('#campoNuevoElemento').toggle();
    });
    $('#compania').change(function() {
        var selectedCompania = $(this).val();
        // Limpia el contenido del select
        $('#ordenPersonalizadoDiv').empty();

        // Realiza una solicitud AJAX para obtener los datos de orden personalizado
        $.ajax({
            url: 'obtener-orden/' + selectedCompania,
            type: 'GET',
            success: function(data) {
                // Parsea la respuesta JSON
                var ordenPersonalizado = data.orden;
                // Agrega las opciones al select
                $.each(ordenPersonalizado, function(agrupadorCuenta, nombre) {
                    // Agrega la opción al select
                    $('#ordenPersonalizadoDiv').append(
                        $('<option>', {
                            value: agrupadorCuenta,
                            text: agrupadorCuenta + '-' + nombre,
                            selected: true // Marca la opción como seleccionada
                        })
                    );
                });

                // Actualiza el multiselect
                $('#ordenPersonalizadoDiv').trigger('change');
            },
            error: function() {
                // Limpia y oculta el multiselect en caso de error
                $('#ordenPersonalizadoDiv').empty().hide();
            }
        });
    });

    // Agrega un controlador de eventos al botón "Quitar todos"
    $('#quitarTodosBtn').click(function() {
       // Deselecciona todas las opciones en el multiselect
        $('#ordenPersonalizadoDiv').multiselect('deselectAll', false);

        // Elimina todas las selecciones de Select2
        $('#ordenPersonalizadoDiv').val(null).trigger('change');

        // Actualiza el multiselect
        $('#ordenPersonalizadoDiv').multiselect('updateButtonText'); // Actualiza el botón de selección
    });
   // Agrega un controlador de eventos al botón "Agregar todos"
    $('#agregarTodosBtn').click(function() {
        // Selecciona todas las opciones en el multiselect
        $('#ordenPersonalizadoDiv option').prop('selected', true);
        
        // Actualiza el multiselect
        $('#ordenPersonalizadoDiv').multiselect('rebuild');

        // Actualiza Select2 para que refleje las selecciones
        $('#ordenPersonalizadoDiv').trigger('change.select2');
    });
</script>
@endsection