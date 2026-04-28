
@extends('layouts.admin')
@section('title', 'Editar: '. $centroCosto->nombre)
@section('content')

<div class="form-group">
    <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.centros_costos.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-pen-to-square"></i> Editar centros de costos
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.centros_costos.update", $centroCosto->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }} " 
                    type="text" placeholder="" name="codigo" id="codigo" value="{{ $centroCosto->codigo }}" readonly>
                <label class="fw-normal" for="codigo">Código <b class="text-danger">*</b></label>
                @if($errors->has('codigo'))
                    <span class="text-danger">{{ $errors->first('codigo') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }} " 
                    type="text" placeholder="" name="nombre" id="nombre" value="{{ old('nombre', $centroCosto->nombre) }}" required>
                <label class="fw-normal" for="nombre">Nombre <b class="text-danger">*</b></label>
                @if($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <select class="form-select {{ $errors->has('compania_id') ? 'is-invalid' : '' }}" name="compania_id" id="compania_id" disabled>
                    <option value="{{ $centroCosto->compania_id }}">{{ $centroCosto->compania->razon_social }}</option>
                </select>
                <label class="fw-normal" for="compania_id">Compañía cliente <b class="text-danger">*</b></label>
                @if($errors->has('compania_id'))
                    <span class="text-danger">{{ $errors->first('compania_id') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <b class="mx-1">Estado</b>
                <div class="form-check form-switch" style="padding-left: 45px;">
                    <input class="form-check-input" type="checkbox" id="estado" name="estado" {{ $centroCosto->estado == 1 ? 'checked' : '' }}>
                </div>
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