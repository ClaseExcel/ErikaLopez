
@extends('layouts.admin')
@section('title', 'Editar: '. $modificacion->compania->razon_social)
@section('content')

<div class="form-group">
    <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.modificaciones.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-pen-to-square"></i> Editar compañías
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.modificaciones.update", $modificacion->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('periodo') ? 'is-invalid' : '' }} " 
                    type="date" placeholder="" name="periodo" id="periodo" value="{{ old('periodo', $modificacion->periodo) }}" readonly>
                <label class="fw-normal" for="periodo">Periodo contable <b class="text-danger">*</b></label>
                @if($errors->has('periodo'))
                    <span class="text-danger">{{ $errors->first('periodo') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <select class="form-select {{ $errors->has('compania_id') ? 'is-invalid' : '' }}" name="compania_id" id="compania_id" disabled>
                    <option value="{{ $modificacion->compania_id }}">{{ $modificacion->compania->razon_social }}</option>
                </select>
                <label class="fw-normal" for="compania_id">Compañía cliente <b class="text-danger">*</b></label>
                @if($errors->has('compania_id'))
                    <span class="text-danger">{{ $errors->first('compania_id') }}</span>
                @endif
            </div>

            {{-- <div class="form-group mb-3">
                <label class="required fw-normal" for="movimiento">Cuenta contable </label>
                <select class="form-control select2 {{ $errors->has('movimiento') ? 'is-invalid' : '' }}" name="movimiento" id="movimiento" disabled required>
                    <option value="{{ $modificacion->movimiento }}">{{ $modificacion->movimiento }}</option>
                </select>
                @if($errors->has('movimiento'))
                    <span class="text-danger">{{ $errors->first('movimiento') }}</span>
                @endif
            </div> --}}
            <div class="form-floating mb-3">
                <select class="form-select {{ $errors->has('movimiento') ? 'is-invalid' : '' }}" name="movimiento" id="movimiento" disabled>
                    <option value="{{ $modificacion->movimiento }}">{{ $modificacion->movimiento }}</option>
                </select>
                <label class="fw-normal" for="movimiento">Cuenta contable <b class="text-danger">*</b></label>
                @if($errors->has('movimiento'))
                    <span class="text-danger">{{ $errors->first('movimiento') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('valor_ajustado') ? 'is-invalid' : '' }} " 
                    type="number" placeholder="" name="valor_ajustado" id="valor_ajustado" value="{{ old('valor_ajustado', $modificacion->valor_ajustado) }}" required>
                <label class="fw-normal" for="valor_ajustado">Valor ajustado <b class="text-danger">*</b></label>
                @if($errors->has('valor_ajustado'))
                    <span class="text-danger">{{ $errors->first('valor_ajustado') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-save btn-radius px-4" type="submit">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="{{ asset('assets/js/modificaciones/movimiento.js') }}" defer></script>
@endsection