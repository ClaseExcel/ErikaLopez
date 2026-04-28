@extends('layouts.admin')
@section('title', 'Estados Financieros')
@section('content')
    <style>
        /* Oculta el icono de la flecha en el select múltiple */
        .caret {
            display: none !important;
        }
    </style>
    @can('CREAR_INFORMES')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-12">
            <a class="btn btn-back border btn-radius mb-2" href="{{ route('admin.estadosfinancieros.create') }}">
                <i class="fas fa-circle-plus"></i> Orden Informes
            </a>
            {{-- <a class="btn btn-back border btn-radius mb-2" href="{{ route('admin.estadosfinancieros.impuesto') }}">
                <i class="fas fa-circle-plus"></i> Provision impuesto renta
            </a> --}}
        </div>
    </div>
    @endcan

    <div class="row">
        <div class="col-12 col-md-8">

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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="informe-tab" data-bs-toggle="tab" data-bs-target="#informe"
                                type="button" role="tab" aria-controls="informe" aria-selected="true">Informe</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#grafico" type="button"
                                role="tab" aria-controls="grafico" aria-selected="false">Gráfico</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        {{-- formulario informe --}}
                        <div class="tab-pane fade show active" id="informe" role="tabpanel" aria-labelledby="informe-tab">
                            <form action="{{ route('admin.estadosfinancieros.store') }}" id="estadosfinancieros" method='POST'
                                enctype="multipart/form-data">
                                @csrf
        
                                @include('admin.estadosfinancieros.fields')
        
                                <div class="card-body p-0 text-center">
                                    <button class="btn btn-save btn-radius px-4" type="submit" onclick="submitForm()" form="estadosfinancieros">
                                        <i class="fa-solid fa-file-signature"></i> Generar
                                    </button>
                                </div>
        
                            </form>
                        </div>
                        {{-- formulario grafico --}}
                        <div class="tab-pane fade" id="grafico" role="tabpanel" aria-labelledby="grafico-tab">
                            <form action="{{ route('admin.estadosfinancieros.grafico') }}" id="estadosgraficos" method='POST'
                                enctype="multipart/form-data">
                                @csrf
        
                                @include('admin.estadosfinancieros.fields2')
        
                                <div class="card-body p-0 text-center">
                                    <button class="btn btn-save btn-radius px-4" type="submit" onclick="submitGraphForm()" form="estadosgraficos">
                                        <i class="fa-solid fa-chart-line"></i> Generar gráfico
                                    </button>
                                </div>
        
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
   
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const estadoSelect = document.getElementById('estado');
        const periodoSwitch = document.getElementById('periodoSwitch');
        const periodoSwitch2 = document.getElementById('periodoSwitch2');
        estadoSelect.addEventListener('change', function () {
            if (estadoSelect.value == '4') {
                periodoSwitch.style.display = 'block';
                periodoSwitch2.style.display = 'none';
            }else if(estadoSelect.value=='5'){
                periodoSwitch2.style.display = 'block';
                periodoSwitch.style.display = 'none';
            }else {
                periodoSwitch.style.display = 'none';
                periodoSwitch2.style.display = 'none';
            }
        });
    });
    $('#compania').change(function() {
        let empresa = $(this).val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'GET',
            url: 'estadosfinancieros/centro-costo/' + empresa,
            success: function(centro_costo) {
                if (JSON.parse(centro_costo).length == 0) {
                    $('#centro_costo').prop("disabled", true);
                    $('#centro_costo2').prop("disabled", true);
                } else {
                    $('#centro_costo').prop("disabled", false);
                    $('#centro_costo2').prop("disabled", false);

                    let centro = JSON.parse(centro_costo);

                    $('#centro_costo').empty();

                    $("#centro_costo").append('<option value="">Selecciona un centro de costo</option>');

                    $.each(centro, function(index, value) {
                        $("#centro_costo").append('<option value=' + value.id + '>' +
                            value.codigo + '-' + value.nombre + '</option>');
                    });
                }
            }
        })
    });

    $('#compania2').change(function() {
        let empresa = $(this).val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'GET',
            url: 'estadosfinancieros/centro-costo/' + empresa,
            success: function(centro_costo) {
                if (JSON.parse(centro_costo).length == 0) {
                    $('#centro_costo2').prop("disabled", true);
                } else {
                    $('#centro_costo2').prop("disabled", false);

                    let centro = JSON.parse(centro_costo);

                    $('#centro_costo2').empty();

                    $("#centro_costo2").append('<option value="">Selecciona un centro de costo</option>');

                    $.each(centro, function(index, value) {
                        $("#centro_costo2").append('<option value=' + value.id + '>' +
                            value.codigo + '-' + value.nombre + '</option>');
                    });
                }
            }
        })
    });
     // Función para mostrar SweetAlert
     function showSweetAlert(message) {
        Swal.fire({
            title: 'Procesando...',
            text: message,
            icon: 'info',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Función para ocultar SweetAlert
    function hideSweetAlert() {
        Swal.close();
    }

    // Función para enviar el formulario de generación de informe
    function submitForm() {
        // Mostrar SweetAlert
        showSweetAlert('Generando informe...');

        // Enviar el formulario
        document.getElementById('estadosfinancieros').submit();
    }

    // Función para enviar el formulario de generación de gráfico
    function submitGraphForm() {
        // Mostrar SweetAlert
        showSweetAlert('Generando gráfico...');

        // Enviar el formulario
        document.getElementById('estadosgraficos').submit();
    }
</script>
 


@endsection
