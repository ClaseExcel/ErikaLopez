@extends('layouts.admin')
@section('title', 'Info: ' . $gestion_humana->nombres)
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')

    <style>
        .max-text-resume {
            max-height: 520px;
            overflow-y: auto;
            overflow-x: auto;
            scroll-behavior: smooth;
            /* cambiar color scroll bar a azul */
            scrollbar-width: thin;
        }
    </style>

    <div class="form-group">
        <a class="btn  btn-back  border btn-radius px-4" href="{{ route('admin.gestion-humana.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>

        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestion-humana.edit', $gestion_humana->id) }}">
            <i class="fas fa-pencil-alt"></i> Editar información
        </a>

         <button class="btn btn-back  border btn-radius px-4"data-bs-toggle="modal" data-bs-target="#modalEventos" title="Ver novedades">
            <i class="fa-solid fa-book"></i> Ver novedades</button>
    </div>

    @include('admin.gestion-humana.eventos.modal-eventos')

    <div class="row">
        <div class="col-xl-5">
            <div class="card" style="height:420px;">
                <div class="card-body max-text-resume">
                    <table witdh="100%">
                        <tr>
                            <td>
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=Hire&style=minimalistic&token=ca71a3a4-a898-457d-b398-fae965c6ceb9"
                                    trigger="click"
                                    attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#000000","group-2":"#00205C","background":"#FFFFFF"}}'
                                    height="120" width="120">
                                </animated-icons>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $gestion_humana->nombres }}</span><br>
                                <span class="fw-semibold text-help">{{ $gestion_humana->cedula }}</span><br>
                                <span>{{ $gestion_humana->estado_civil }}</span><br>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <div class="row">
                            <div class="col auto" style="display: flex; align-items: center; gap: 2px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=mail&style=minimalistic&token=e55f9897-402e-4453-b539-03e933b6d7fa"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,"backgroundIsGroup":false,"strokeWidth":1.5,"defaultColours":{"group-1":"#000000","background":"#FFFFFFFF"}}'
                                    height="32" width="32">
                                </animated-icons>
                                <span class="fw-semibold"> Correo electrónico</span>
                            </div>
                            <div class="col auto text-end">
                                {{ $gestion_humana->correo_electronico ? $gestion_humana->correo_electronico : '―' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col auto" style="display: flex; align-items: center; gap:2px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=contact&style=minimalistic&token=a95329fc-932a-463b-996b-23c9a6ec0092"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,"backgroundIsGroup":false,"strokeWidth":1.5,"defaultColours":{"group-1":"#000000","background":"#FFFFFFFF"}}'
                                    height="24" width="24" style="margin:4px;">
                                </animated-icons>
                                <span class="fw-semibold"> Teléfono</span>
                            </div>
                            <div class="col auto text-end">
                                {{ $gestion_humana->telefono ? preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $gestion_humana->telefono) : '―' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col auto" style="display: flex; align-items: center; gap:4px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=calendar%20V2&style=minimalistic&token=f68d7578-e739-4d5f-a372-2a5b56f25a79"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,"backgroundIsGroup":false,"strokeWidth":1.5,"defaultColours":{"group-1":"#000000","background":"#FFFFFFFF"}}'
                                    height="20" width="20" style="margin:4px;">
                                </animated-icons>
                                <span class="fw-semibold"> Fecha de nacimiento</span>
                            </div>
                            <div class="col auto text-end">
                                {{ \Carbon\Carbon::parse($gestion_humana->fecha_nacimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col auto" style="display: flex; align-items: center; gap:1px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=home&style=minimalistic&token=1de785be-f87f-4fdd-9cda-efb65431763b"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,"backgroundIsGroup":false,"strokeWidth":1.2,"defaultColours":{"group-1":"#000000","background":"#FFFFFFFF"}}'
                                    height="23" width="23" style="margin:5px;">
                                </animated-icons>
                                <span class="fw-semibold"> Dirección</span>
                            </div>
                            <div class="col auto text-end">
                                {{ $gestion_humana->direccion }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col auto" style="display: flex; align-items: center; gap:1px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=location&style=minimalistic&token=1de785be-f87f-4fdd-9cda-efb65431763b"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,"backgroundIsGroup":false,"strokeWidth":1.5,"defaultColours":{"group-1":"#000000","background":"#FFFFFFFF"}}'
                                    height="23" width="23" style="margin:2.5px;">
                                </animated-icons>
                                <span class="fw-semibold"> Municipio de residencia</span>
                            </div>
                            <div class="col auto text-end">
                                {{ $gestion_humana->municipio_residencia }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body pl-2 pb-0 pt-2">
                        <div class="row">
                            <div class="col-xl-12 justify-content-center"
                                style="display: flex; align-items: center; gap:1px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=Staff&style=minimalistic&token=1de785be-f87f-4fdd-9cda-efb65431763b"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#000000","group-2":"#00205C","background":"#FFFFFFFF"}}'
                                    height="50" width="50">
                                </animated-icons>
                                <span class="fw-semibold text-help-azul">Número de beneficiarios</span> <br>
                            </div>
                            <div class="col-12 fs-5 text-center">
                                {{ $gestion_humana->numero_beneficiarios }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card" style="height:420px;">
                <div class="max-text-resume">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-xl-6 mb-3">
                                <span class="fs-4 text-help">{{ $gestion_humana->empresa->razon_social }}</span> <br>
                                <span class="fs-5">{{ $gestion_humana->cargo }}</span> <br>
                                <small class="fw-bold">Tipo de contrato: {{ $gestion_humana->tipo_contrato }}</small>
                            </div>
                            <div class="col-xl-6"
                                style="display: flex; align-items: center; justify-content: center; gap:1px;">
                                <animated-icons
                                    src="https://animatedicons.co/get-icon?name=Schedule&style=minimalistic&token=1de785be-f87f-4fdd-9cda-efb65431763b"
                                    trigger="hover"
                                    attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#000000","group-2":"#00205C","background":"#FFFFFFFF"}}'
                                    height="50" width="50">
                                </animated-icons>
                                <span>{{ $gestion_humana->fecha_ingreso }} ― {!! $gestion_humana->fecha_finalizacion
                                    ? $gestion_humana->fecha_finalizacion
                                    : '<span class="fst-italic text-secondary">Sin fecha de finalización</span>' !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4">
                                <span class="fw-semibold text-help-azul"> Salario </span> <br>
                                ${{ number_format($gestion_humana->salario, 0, ',', '.') }}
                            </div>
                            <div class="col-xl-4">
                                <span class="fw-semibold text-help-azul">Bonificación</span> <br>
                                {{ $gestion_humana->bonificacion ? '$' . number_format($gestion_humana->bonificacion, 0, ',', '.') : '―' }}
                            </div>
                            <div class="col-xl-4">
                                <span class="fw-semibold text-help-azul">Auxilio de transporte</span> <br>
                                {{ $gestion_humana->auxilio_transporte ? '$' . number_format($gestion_humana->auxilio_transporte, 0, ',', '.') : '―' }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column" style="min-width:480px;">
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-1">
                                    <strong class="fw-semibold text-help-azul me-2">EPS </strong>
                                    <span class="flex-grow-1 text-truncate" title="{{ $gestion_humana->eps }}"
                                        style="max-width:820px;">
                                        {{ $gestion_humana->eps }}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center mb-1">
                                    <strong class="fw-semibold text-help-azul me-2">AFP</strong>
                                    <span class="flex-grow-1 text-truncate" title="{{ $gestion_humana->afp }}"
                                        style="max-width:820px;">
                                        {{ $gestion_humana->afp }}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center mb-1">
                                    <strong class="fw-semibold text-help-azul me-2">CESANTÍAS</strong>
                                    <span class="flex-grow-1 text-truncate" title="{{ $gestion_humana->cesantias }}"
                                        style="max-width:820px;">
                                        {{ $gestion_humana->cesantias }}
                                    </span>
                                </li>
                                <li class="d-flex align-items-center mb-1">
                                    <strong class="fw-semibold text-help-azul me-2">ARL</strong>
                                    <span class="flex-grow-1 text-truncate" title="{{ $gestion_humana->arl }}"
                                        style="max-width:820px;">
                                        {{ $gestion_humana->arl }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            @if ($docList)
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3" style="display: flex; align-items: center; justify-content: center; gap:1px;">
                            <animated-icons
                                src="https://animatedicons.co/get-icon?name=open%20folder&style=minimalistic&token=1de785be-f87f-4fdd-9cda-efb65431763b"
                                trigger="hover"
                                attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1.5,"defaultColours":{"group-1":"#000000","group-2":"#00205C","background":"#FFFFFFFF"}}'
                                height="25" width="25">
                            </animated-icons>
                            <span class="fw-semibold"> Documentos adjuntos</span>
                        </div>

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
                                                <a type="button" class="btn-ver px-3" href="{{ asset($docName) }}"
                                                    download>
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
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0 d-flex justify-content-center">

                                                    @if (pathinfo($docName, PATHINFO_EXTENSION) == 'pdf')
                                                        <iframe src="{{ asset($docName) }}"
                                                            style="width:100%;height:637px;" frameborder="0">
                                                        </iframe>
                                                    @else
                                                        <img src="{{ asset($docName) }}" alt="" width="100%"
                                                            style="max-width: 750px;"">
                                                    @endif

                                                </div>
                                                <div class="modal-footer border-0">
                                                    <a class="btn btn-save text-white shadow-none mx-auto"
                                                        href="{{ asset($docName) }}" download>
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
                </div>
            @endif
        </div>


    </div>
@endsection
