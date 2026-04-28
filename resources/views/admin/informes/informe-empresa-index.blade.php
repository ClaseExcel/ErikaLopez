@extends('layouts.admin')
@section('title', 'Informe empresa')
@section('content')

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header fs-5">
                Informe de actividades por empresa
            </div>
    
            <div class="card-body">
                <form method="GET" action="{{ route('admin.excel-empresa') }}">
                    @csrf
    
                    {{-- //empresa --}} 
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('empresa') ? 'is-invalid' : '' }}" name="empresa" id="empresa">
                                    <option value="">Seleccione una empresa</option>
                                    @foreach ($empresa as $empresa)
                                        <option value="{{ $empresa->id }}"> {{ $empresa->razon_social }}</option>
                                    @endforeach
                                </select>
                                <label class="fw-normal" for="empresa">Empresa</label>
            
                                @if ($errors->has('empresa'))
                                    <span class="text-danger">{{ $errors->first('empresa') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('tipo_actividad_id') ? 'is-invalid' : '' }}" name="tipo_actividad_id" id="tipo_actividad_id">
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
                    </div>
    
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} " type="date" placeholder="" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}" >
                                <label class="fw-normal" for="fecha_inicio">Fecha inicio</label>
                                @if($errors->has('fecha_inicio'))
                                    <span class="text-danger">{{ $errors->first('fecha_inicio') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} " type="date" placeholder="" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', date('Y-m-d')) }}" >
                                <label class="fw-normal" for="fecha_fin">Fecha fin</label>
                                @if($errors->has('fecha_fin'))
                                    <span class="text-danger">{{ $errors->first('fecha_fin') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
    
    
                    <div class="form-group text-end">
                        <button class="btn btn-save btn-radius px-4" type="submit">
                            Generar
                        </button>
                    </div>

                    {{-- <div class="form-group text-end">
                        <button class="btn btn-save btn-radius px-4" type="submit">
                            Previsualizar
                        </button>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 
<div class="row">

    @php
        $empresas = ['Empresa0', 'Empresa 1', 'Empresa 2', 'Empresa 3', 'Empresa 4', 'Empresa 5'];
    @endphp

    @foreach($empresas as $empresa )
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header fs-5">
                {{ $empresa }}
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Tipo de actividad</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $tiposActividad = ['Cierre del mes', 'Solicitudes', 'Prioritario', 'Otro'];
                            $datos = [0, 3];
                            $totalCantidad = 50;
                            $totalPorcentaje = 50;
                        @endphp

                        @foreach ($tiposActividad as $tipoActividad)
                            <tr>
                                    <td>{{ $tipoActividad }}</td>
                                @foreach ($datos as $dato)
                                    <td>{{ $dato }}</td>
                                @endforeach                                        
                            </tr>                                
                        @endforeach
                        <tr>
                            <th>Total Actividades</th>
                            <th>{{ $totalCantidad }}  </th>
                            <th>{{ $totalPorcentaje }}</th>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach


</div> --}}


@endsection
