@extends('layouts.admin')
@section('title', 'Informe CRM')
@section('content')

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header fs-5">
                    Informe CRM
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.excel-cotizacion') }}">
                        @csrf

                        <div class="row">
                            <div class="col-12 col-md-6 mb-1">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="inicial" name="fecha_inicio"
                                        placeholder="Fecha inicial"
                                        @if ($fechaInicial) min="{{ $fechaInicial->fecha_envio }}"
                                        max="{{ $fechaFinal->fecha_envio }}" @endif>
                                    <small>Fecha inicial</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="final" name="fecha_fin"
                                        placeholder="Fecha final"
                                        @if ($fechaFinal) min="{{ $fechaInicial->fecha_envio }}"  max="{{ $fechaFinal->fecha_envio }}" @endif>
                                    <small>Fecha final</small>
                                </div>
                            </div>


                            <div class="form-group text-end">
                                <button class="btn btn-save btn-radius px-4" type="submit">
                                    Generar
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
