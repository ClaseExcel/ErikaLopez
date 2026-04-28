@extends('layouts.admin')
@section('title', 'Informe usuario')
@section('content')

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header fs-5">
                    Informe actividades por empresa usuario
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.excel-empresa-usuario') }}">
                        @csrf

                        {{-- //empresa --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select name="usuario" id="usuario"
                                        class="form-select {{ $errors->has('usuario') ? 'is-invalid' : '' }}">
                                        <option value="">Seleccione un usuario</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">
                                                {{ $usuario->nombres . ' ' . $usuario->apellidos }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="usuario" class="fw-normal">
                                        Usuario</label>

                                    @error('usuario')
                                        <p id="usuario" class="mt-2 text-xs text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select {{ $errors->has('empresa') ? 'is-invalid' : '' }}"
                                        name="empresa" id="empresa">
                                        <option value="">Seleccione una empresa</option>
                                        @foreach ($empresa as $empresa)
                                            <option value="{{ $empresa->id }}"> {{ $empresa->razon_social }}</option>
                                        @endforeach
                                    </select>
                                    <label class="fw-normal" for="empresa">Empresa asociada</label>

                                    @if ($errors->has('empresa'))
                                        <span class="text-danger">{{ $errors->first('empresa') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select {{ $errors->has('tipo_actividad_id') ? 'is-invalid' : '' }}"
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
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} "
                                        type="date" placeholder="" name="fecha_inicio" id="fecha_inicio"
                                        value="{{ old('fecha_inicio', date('Y-m-d')) }}">
                                    <label class="fw-normal" for="fecha_inicio">Fecha inicio</label>
                                    @if ($errors->has('fecha_inicio'))
                                        <span class="text-danger">{{ $errors->first('fecha_inicio') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} "
                                        type="date" placeholder="" name="fecha_fin" id="fecha_fin"
                                        value="{{ old('fecha_fin', date('Y-m-d')) }}">
                                    <label class="fw-normal" for="fecha_fin">Fecha fin</label>
                                    @if ($errors->has('fecha_fin'))
                                        <span class="text-danger">{{ $errors->first('fecha_fin') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                {{-- <i class="fas fa-save"></i> --}}
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

@endsection
