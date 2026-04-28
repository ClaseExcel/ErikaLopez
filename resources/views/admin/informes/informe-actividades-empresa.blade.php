@extends('layouts.admin')
@section('title', 'Informe actividades especificas por empresa')
@section('content')

    <style>
        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: #232222;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .nav-link {
            display: block;
            padding: .5rem 1rem;
            color: #000000;
            text-decoration: none;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
        }

        .nav-link:hover {
            display: block;
            padding: .5rem 1rem;
            color:#232222;
            
            text-decoration: none;
        }
    </style>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header fs-5">
                    Informe de actividades especifico por empresa
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ta-tab" data-bs-toggle="tab" data-bs-target="#ta"
                                type="button" role="tab" aria-controls="ta" aria-selected="true">Por tipo de
                                actividad</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="estado-tab" data-bs-toggle="tab" data-bs-target="#estado"
                                type="button" role="tab" aria-controls="estado" aria-selected="false">Por
                                estado</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="ta" role="tabpanel" aria-labelledby="ta-tab">
                            <form method="GET" action="{{ route('admin.excel-actividades-empresas') }}" id="actividadesTipo">
                                @csrf
                                <div class="row">
                                    <input type="hidden" id="tipo_archivo" name="tipo_archivo" value="excel">

                                    <div class="col-12 col-md-6">
                                        <div class="form-floating mb-3">
                                            <select
                                                class="form-select"
                                                name="tipo_actividad_id" id="tipo_actividad_id">
                                                <option value="">Seleccione un tipo de actividad</option>
                                                @foreach ($tipo_actividad as $actividad)
                                                    <option value="{{ $actividad->id }}">
                                                        {{ $actividad->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label class="fw-normal" for="tipo_actividad_id">Tipo de actividad</label>

                                            @if ($errors->has('tipo_actividad_id'))
                                                <span class="text-danger">{{ $errors->first('tipo_actividad_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @include('admin.informes.includes.fields-empresas')

                                    <div class="form-group text-end">
                                        <button class="btn btn-save btn-radius px-4" type="submit" id="generar-excel" form="actividadesTipo">
                                            <i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;Generar Excel
                                        </button>

                                        <button class="btn btn-save btn-radius px-4" id="generar-pdf" type="submit" form="actividadesTipo">
                                            <i class="fa-solid fa-file-pdf"></i>&nbsp;&nbsp;Generar PDF
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade" id="estado" role="tabpanel" aria-labelledby="estado-tab">
                            <form method="GET" action="{{ route('admin.excel-actividades-empresas-estado') }}"
                                id="actividadesEstado">
                                @csrf

                                <input type="hidden" id="tipo_archivo_estado" name="tipo_archivo" value="excel">

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating mb-3">
                                            <select
                                                class="form-select"
                                                name="estado_id" id="estado_id">
                                                <option value="">Seleccione un estado</option>
                                                @foreach ($estados as $estado)
                                                    <option value="{{ $estado->id }}">
                                                        {{ $estado->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label class="fw-normal" for="estado_id">Estado</label>

                                            @if ($errors->has('tipo_actividad_id'))
                                                <span class="text-danger">{{ $errors->first('tipo_actividad_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @include('admin.informes.includes.fields-empresas')

                                    <div class="form-group text-end">
                                        <button class="btn btn-save btn-radius px-4" type="submit" id="generar-excel-estado" form="actividadesEstado">
                                            <i class="fa-solid fa-file-excel"></i>&nbsp;&nbsp;Generar Excel
                                        </button>

                                        <button class="btn btn-save btn-radius px-4" id="generar-pdf-estado" type="submit" form="actividadesEstado">
                                            <i class="fa-solid fa-file-pdf"></i>&nbsp;&nbsp;Generar PDF
                                        </button>
                                    </div>
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
    <script>
        // Seleccionamos el input y el botón
        const input = document.getElementById('tipo_archivo');
        const input2  = document.getElementById('tipo_archivo_estado');
        const botonPDF = document.getElementById('generar-pdf');
        const botonEXCEL = document.getElementById('generar-excel');

        // Añadimos un evento de clic al botón
        botonPDF.addEventListener('click', function() {
            // Cambiamos el valor del input
            input.value = 'pdf';
        });

        botonEXCEL.addEventListener('click', function() {
            // Cambiamos el valor del input
            input.value = 'excel';
        });

        const botonEstadoPDF = document.getElementById('generar-pdf-estado');
        const botonEstadoEXCEL = document.getElementById('generar-excel-estado');

         // Añadimos un evento de clic al botón
         botonEstadoPDF.addEventListener('click', function() {
            // Cambiamos el valor del input
            input2.value = 'pdf';
        });

        botonEstadoEXCEL.addEventListener('click', function() {
            // Cambiamos el valor del input
            input2.value = 'excel';
        });
    </script>
@endsection
