@extends('layouts.admin')
@section('title', 'Ver empresa')
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.empresas.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>

        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.empresas.edit', $empresa->id) }}">
            <i class="fas fa-pencil-alt"></i> Editar empresa
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="max-height:260px;"">
                    <div class="row">
                        <div class="col-12 p-0 d-flex align-items-center text-help">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                                width="30px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <span class="fs-4">Información de la empresa</span>
                        </div>
                        <div class="col-12 py-0">
                            <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(100 100 100);">
                        </div>

                        <div class="col-md-12 mb-3">
                            <span class="fs-5">{{ $empresa->razon_social }}</span> <br>
                            <span class="fw-bold text-help">{{ $empresa->NIT }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="fw-normal">Correo eléctronico <b>{{ $empresa->correo_electronico }}</b></span><br>
                            <span class="fw-normal">Número de contacto <b>{{ $empresa->numero_contacto }}</b></span><br>
                            <span>Dirección fisica <b>{{ $empresa->direccion_fisica }}</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 ">
            <div class="card">
                <div class="card-body px-0 py-0 ">
                    <div class="accordion" id="accordionObligaciones">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneObligaciones">
                                <button class="accordion-button collapsed shadow-none bg-help" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOneObligaciones" aria-expanded="false" style="font-size: 17px"
                                    aria-controls="collapseOneObligaciones">
                                    Obligaciones tributarias DIAN
                                </button>
                            </h2>

                            <div id="collapseOneObligaciones" class="accordion-collapse collapse show" aria-labelledby="headingOneObligaciones"
                                data-bs-parent="#accordionObligaciones" style="max-height:260px; overflow-y:auto; scroll-behavior:smooth;">
                                @if ($obligaciones)
                                    <table class="table-sm table-bordered table-striped mb-3 w-100" id="obligacionesDian">
                                        <thead>
                                            <tr>
                                                <th width="25%"><i class="fa-regular fa-rectangle-list"></i>&nbsp;Código</th>
                                                <th> Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obligaciones as $item)
                                                <tr>
                                                    <td class="text-end">{{ $item['codigo'] }}</td>
                                                    <td>{{ $item['nombre'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene ningúna obligación.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent"></div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body px-0 py-0 ">
                    <div class="accordion" id="accordionDeclaracion">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneDeclaracion">
                                <button class="accordion-button collapsed shadow-none bg-help" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOneDeclaracion" aria-expanded="false" style="font-size: 17px"
                                    aria-controls="collapseOneDeclaracion">
                                    Declaración de industria y comercio
                                </button>
                            </h2>

                            <div id="collapseOneDeclaracion" class="accordion-collapse collapse show" aria-labelledby="headingOneDeclaracion"
                                data-bs-parent="#accordionDeclaracion" style="max-height:260px; overflow-y:auto; scroll-behavior:smooth;">
                                @if (!empty($empresa->codigo_obligacionmunicipal))
                                    <table class="table-sm table-bordered table-striped mb-3 w-100" id="departamentoMunicipioTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="25%"><i class="fa-regular fa-rectangle-list"></i>&nbsp;Código</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($empresa->codigo_obligacionmunicipal))
                                                @foreach ($obligacionesMunicipales as $item)
                                                    <tr>
                                                        <td class="text-end">{{ $item['codigo'] }}</td>
                                                        <td>{{ $item['nombre'] }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene ningúna declaración
                                        de industria y comercio.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent"></div>
                </div>
            </div>
        </div>

        {{-- CAMARA DE COMERCIO PRINCIPAL --}}
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body px-0 py-0 ">
                    <div class="accordion" id="accordionCamaraComercio">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header border-bottom" id="headingOneCamaraComercio">
                                <button class="accordion-button collapsed shadow-none bg-help" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOneCamaraComercio" aria-expanded="false" style="font-size: 17px"
                                    aria-controls="collapseOneCamaraComercio">
                                    Cámara de comercio principal
                                </button>
                            </h2>

                            <div id="collapseOneCamaraComercio" class="accordion-collapse collapse show" aria-labelledby="headingOneCamaraComercio"
                                data-bs-parent="#accordionCamaraComercio" style="max-height:260px; overflow-y:auto; scroll-behavior:smooth;">
                                @if (!empty($empresa->camaracomercio_id))
                                    <table class="table-sm table-bordered table-striped mb-3 w-100" id="departamentoMunicipioTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="25%"><i class="fa-regular fa-rectangle-list"></i>&nbsp;Código
                                                </th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($empresa->camaracomercio_id))
                                                <tr>
                                                    <td class="text-end">{{ $camaraComercioPrincipal->id }}</td>
                                                    <td>{{ $camaraComercioPrincipal->nombre }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene ningún código de
                                        camara de comercio.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                </div>
            </div>
        </div>

        {{-- CAMARA DE COMERCIO ESTABLECIMIENTOS --}}
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="accordion" id="accordionCamaraComercioEstablecimientos">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="headingOneCamaraComercioEstablecimientos">
                            <button class="accordion-button shadow-none bg-help" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOneCamaraComercioEstablecimientos" aria-expanded="true"
                                aria-controls="collapseOneCamaraComercioEstablecimientos" style="font-size: 17px; width:100%;">
                                Cámaras de comercio establecimientos de comercio
                            </button>
                        </h2>

                        <div id="collapseOneCamaraComercioEstablecimientos" class="accordion-collapse collapse show"
                            aria-labelledby="headingOneCamaraComercioEstablecimientos" data-bs-parent="#accordionCamaraComercioEstablecimientos"
                            style="max-height:260px; overflow-y:auto; scroll-behavior:smooth;">
                            <div class="accordion-body p-0">
                                @if (!empty($camaraComercio) && count($camaraComercio) > 0)
                                    <table class="table-bordered table-striped display nowrap compact" style="width:100%"
                                        id="departamentoMunicipioTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="25%"><i class="fa-regular fa-rectangle-list"></i>&nbsp;Código</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($camaraComercio as $item)
                                                <tr>
                                                    <td class="text-end">{{ $item['id'] }}</td>
                                                    <td>{{ $item['nombre'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene ningún código de cámara de comercio.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- EMPLEADOS --}}
        <div class="col-12 col-md-6">
            @if (!empty($users) || count($users) != 0)
                <div class="card">
                    <div class="card-header">
                        Empleados
                    </div>
                    <div class="card-body p-0" style="max-height:300px; overflow-y:auto; scroll-behavior:smooth;">
                        <table id="departamentoMunicipioTable" class="table-sm table-bordered table-striped mb-3 border-rounded w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td>{{ $item['id'] }}</td>
                                        <td>{{ $item['nombre_completo'] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-transparent"></div>
                </div>
            @endif
        </div>

        {{-- DOCUMENTOS ADJUNTOS --}}
        <div class="col-12 col-md-6">
            @if ($docList)
                <div class="col mb-5">
                    <div class="card">
                        <div class="card-header bg-help fs-6">
                            <i class="fas fa-paperclip"></i> Documentos adjuntos
                        </div>
                        <div class="card-body p-0" style="max-height:250px; overflow-y:auto; scroll-behavior:smooth;">
                            <table class="table table-hover table-sm">
                                <tbody>
                                    @foreach ($docList as $key => $docName)
                                        <tr>
                                            <td class="pl-4 border border-top-0 border-left-0 border-right-0">
                                                {{ basename($docName) }}
                                            </td>
                                            <td class=" border border-top-0 border-left-0 border-right-0 text-center">

                                                {{-- Descarga directa si la extensión es zip o rar --}}
                                                @if (pathinfo($docName, PATHINFO_EXTENSION) == 'zip' ||
                                                        pathinfo($docName, PATHINFO_EXTENSION) == 'rar' ||
                                                        pathinfo($docName, PATHINFO_EXTENSION) == 'xlsx' ||
                                                        pathinfo($docName, PATHINFO_EXTENSION) == 'xls')
                                                    <a type="button" class="btn-ver px-3" href="{{ asset($docName) }}" download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <!-- Button trigger modal -->
                                                    <a type="button" class="btn-ver px-3" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal{{ $key }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif


                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Documento
                                                            {{ basename($docName) }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-0 d-flex justify-content-center">

                                                        @if (pathinfo($docName, PATHINFO_EXTENSION) == 'pdf')
                                                            <iframe src="{{ asset($docName) }}" style="width:100%;height:637px;" frameborder="0">
                                                            </iframe>
                                                        @else
                                                            <img src="{{ asset($docName) }}" alt="" width="100%"
                                                                style="max-width: 750px;"">
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <a class="btn btn-save text-white shadow-none mx-auto" href="{{ asset($docName) }}" download>
                                                            <i class="fas fa-arrow-alt-circle-down"></i>
                                                            Descargar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Campos adicionales
                </div>
                <div class="card-body responsive">
                    <table class="table-bordered table-striped display nowrap compact" id="datatable-Adicional" style="width:100%">
                        <thead>
                            <tr>
                                <th width="20%">
                                    Descripción
                                </th>
                                <th width="20%">
                                    Información
                                </th>
                                <th width="20%"></th>
                                <th width="20%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Cédula</td>
                                <td>{{ $empresa->Cedula }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Correos secundarios</td>
                                <td>
                                    @if (!empty($empresa->correos_secundarios))
                                        <span title="{{ $empresa->correos_secundarios }}">
                                            {{ Str::limit($empresa->correos_secundarios, 50, '...') }}
                                        </span>
                                    @else
                                        <span>Sin datos</span> <!-- Opcional: Puedes dejar esto vacío o poner un texto alternativo -->
                                    @endif
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>CIIU</td>
                                <td>{{ implode(', ', $empresa->ciiu != 'null' && $empresa->ciiu != null ? json_decode($empresa->ciiu) : []) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Código CIIU para municipios</td>
                                <td>{{ $empresa->ciiu_municipios }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>SIGLA</td>
                                <td>{{ $empresa->sigla }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>DV</td>
                                <td>{{ $empresa->dv }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Contraseña DIAN</td>
                                <td>{{ $empresa->contrasenadian }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pregunta DIAN</td>
                                <td>{{ $empresa->preguntadian }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Firma electrónica DIAN</td>
                                <td>{{ $empresa->firmadian }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cámara comercio clave portal</td>
                                <td>{{ $empresa->camaracomercioclaveportal }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cámara comercio firma</td>
                                <td>{{ $empresa->firmacamaracomercio }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php $usuarios = json_decode($empresa->usuario_ica);
                            $claves = json_decode($empresa->icaclaveportal); ?>

                            @if ($usuarios)
                                @foreach ($usuarios as $index => $usuario)
                                    <tr>
                                        <td>Usuario ICA</td>
                                        <td>{{ $usuario }}</td>
                                        <td>ICA contraseña portal</td>
                                        <td>{{ $claves[$index] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td>ARL</td>
                                <td>{{ $empresa->arl }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Clave ARL</td>
                                <td>{{ $empresa->clavearl }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>APORTES - enlace operativo</td>
                                <td>{{ $empresa->aportes }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>CCF</td>
                                <td>{{ $empresa->ccf }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Usuario/clave EPS</td>
                                <td>{{ $empresa->usuario_clave_eps }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Usuario/clave UGPP</td>
                                <td>{{ $empresa->usuario_clave_ugpp }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Usuario FACT/nómina electrónica</td>
                                <td>{{ $empresa->usuario_fac_nomina }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Clave Fact/nómina electrónica</td>
                                <td>{{ $empresa->clave_fact_nomina }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Usuario sistema contable</td>
                                <td>{{ $empresa->usuario_sistema_contable }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Clave sistema contable</td>
                                <td>{{ $empresa->clave_sistema_contable }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Requerimientos obligación
                </div>
                <div class="card-body">
                    <table class="table-bordered table-striped display nowrap compact w-100" id="datatable-Adicional1" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50%">
                                    Descripción
                                </th>
                                <th>
                                    Información
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clasificaciones as $clasificacion)
                                <tr>
                                    <td><b>Año</b></td>
                                    <td>{{ $clasificacion->anio }}</td>
                                </tr>
                                <tr>
                                    <td>¿Pertenece al régimen simple de tributación? </td>
                                    <td>{{ $clasificacion->regimen_simple_tributacion }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos gravados </td>
                                    <td>${{ number_format($clasificacion->ingresos_gravados, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos exentos </td>
                                    <td>${{ number_format($clasificacion->ingresos_exentos, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos excluidos </td>
                                    <td>${{ number_format($clasificacion->ingresos_excluidos, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos no gravados </td>
                                    <td>${{ number_format($clasificacion->ingresos_no_gravados, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Devoluciones </td>
                                    <td>${{ number_format($clasificacion->devoluciones, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Total ingresos </td>
                                    <td>${{ number_format($clasificacion->total_ingresos, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Actividad 1 </td>
                                    <td>{{ $clasificacion->actividad_1 }}</td>
                                </tr>
                                <tr>
                                    <td>Actividad 2 </td>
                                    <td>{{ $clasificacion->actividad_2 }}</td>
                                </tr>
                                <tr>
                                    <td>Actividad 3 </td>
                                    <td>{{ $clasificacion->actividad_3 }}</td>
                                </tr>
                                <tr>
                                    <td>Actividad 4 </td>
                                    <td>{{ $clasificacion->actividad_4 }}</td>
                                </tr>
                                <tr>
                                    <td>¿Realiza operaciones exentas? </td>
                                    <td>{{ $clasificacion->operaciones_excentas }}</td>
                                </tr>
                                <tr>
                                    <td>¿Realiza actividades de exportación o
                                        importación? </td>
                                    <td>{{ $clasificacion->actividades_exp_imp }}</td>
                                </tr>
                                <tr>
                                    <td>¿Es gran contribuyente? </td>
                                    <td>{{ $clasificacion->gran_contribuyente }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos brutos fiscales del
                                        año anterior </td>
                                    <td>${{ number_format($clasificacion->ingresos_brutos_fiscales_anio_anterior, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>¿Debe presentar el formato de conciliación
                                        fiscal? </td>
                                    <td>{{ $clasificacion->formato_conciliacion_fiscal }}</td>
                                </tr>
                                <tr>
                                    <td>Activos brutos a diciembre
                                        31 del año anterior</td>
                                    <td>${{ number_format($clasificacion->activos_brutos_diciembre_anio_anterior, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos brutos a diciembre
                                        31 del año anterior </td>
                                    <td>${{ number_format($clasificacion->ingreso_brutos_diciembre_anio_anterior, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>¿Está obligado a tener revisor fiscal? </td>
                                    <td>{{ $clasificacion->revisor_fiscal }}</td>
                                </tr>
                                <tr>
                                    <td>Patrimonio bruto a
                                        diciembre 31 del año anterior</td>
                                    <td>${{ number_format($clasificacion->patrimonio_brutos_diciembre_anio_anterior, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Ingresos brutos a
                                        diciembre del 31 año anterior </td>
                                    <td>${{ number_format($clasificacion->ingreso_brutos_tributario_diciembre_anio_anterior, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>¿Las declaraciones tributarias deben ser firmadas por
                                        el contador? </td>
                                    <td>{{ $clasificacion->declaracion_tributaria_firma_contador }}</td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>

        @if (
            !empty($empresa->iva_generado_codigo_1) ||
                !empty($empresa->iva_generado_codigo_2) ||
                !empty($empresa->iva_generado_codigo_3) ||
                !empty($empresa->iva_descontable_codigo_1) ||
                !empty($empresa->iva_descontable_codigo_2) ||
                !empty($empresa->iva_descontable_codigo_3) ||
                !empty($empresa->iva_descontable_codigo_4))
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-regular fa-rectangle-list"></i> Códigos IVA
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-6 pr-md-0">
                                @if (empty($empresa->iva_generado_codigo_1) && 
                                     empty($empresa->iva_generado_codigo_2) && 
                                     empty($empresa->iva_generado_codigo_3))

                                    {{-- Si no se encuentra ningún dato para esta empresa, se muestra el siguiente mensaje: --}}
                                    <table class="table table-sm table-bordered table-striped mb-xl-3 w-100 mb-0" style="min-height:198px;">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center">Generados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td>
                                                <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene códigos generados.</p>
                                            </td>
                                        </tbody>
                                    </table>
                                @else
                                    {{-- Si se encuentran datos para esta empresa, se muestran a continuación: --}}
                                    <table class="table table-sm table-bordered table-striped mb-3 w-100 mb-0" style="min-height:198px;">
                                        <thead class="bg-light">
                                            <tr>
                                                <th colspan="2" class="text-center">Generados</th>
                                            </tr>
                                            <tr>
                                                <th width="10%" class="text-center">#</th>
                                                <th>Código</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center text-secondary">1</td>
                                                <td>{{ $empresa->iva_generado_codigo_1 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary">2</td>
                                                <td>{{ $empresa->iva_generado_codigo_2 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary">3</td>
                                                <td>{{ $empresa->iva_generado_codigo_3 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary"></td>
                                                <td> &nbsp; </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <div class="col-md-6 pl-md-0">
                                @if (empty($empresa->iva_descontable_codigo_1) &&
                                        empty($empresa->iva_descontable_codigo_2) &&
                                        empty($empresa->iva_descontable_codigo_3) &&
                                        empty($empresa->iva_descontable_codigo_4))
                                        <table class="table table-sm table-bordered table-striped mb-xl-3 w-100 mb-0" style="min-height:198px;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center">Descontables</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td>
                                                    <p class="text-center py-4"> <i class="fas fa-circle-info"></i> No tiene códigos descontables.</p>
                                                </td>
                                            </tbody>
                                        </table>
                                @else
                                    <table class="table table-sm table-bordered table-striped mb-3 w-100 mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th colspan="2" class="text-center">Descontables</th>
                                            </tr>
                                            <tr>
                                                <th width="10%" class="text-center">#</th>
                                                <th>Código</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center text-secondary">1</td>
                                                <td>{{ $empresa->iva_descontable_codigo_1 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary">2</td>
                                                <td>{{ $empresa->iva_descontable_codigo_2 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary">3</td>
                                                <td>{{ $empresa->iva_descontable_codigo_3 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center text-secondary">4</td>
                                                <td>{{ $empresa->iva_descontable_codigo_4 ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent"> </div>
                </div>
            </div>
        @endif
    </div>




@endsection
@section('scripts')
    <script>
        function toggleDiv(divId) {
            var div = document.getElementById(divId);
            if (div.style.display === "none") {
                div.style.display = "block";
            } else {
                div.style.display = "none";
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Adicional', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['search'],
                    topEnd: ['pageLength'],
                    bottomEnd: {
                        paging: {
                            type: 'simple_numbers',
                            numbers: 5,
                        }
                    }
                },
                ordering: true,
                //ordenar por la columna 0 de forma ascendente
                order: [
                    [5, 'desc']
                ],
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            table = new DataTable('#datatable-Adicional1', {
                language: {
                    url: "{{ asset('/js/datatable/Spanish.json') }}",
                },
                layout: {
                    topStart: ['search'],
                    topEnd: ['pageLength'],
                    bottomEnd: {
                        paging: {
                            type: 'simple_numbers',
                            numbers: 5,
                        }
                    }
                },
                ordering: true,
                //ordenar por la columna 0 de forma ascendente
                order: [
                    [5, 'desc']
                ],
            });
        });
    </script>
@endsection
