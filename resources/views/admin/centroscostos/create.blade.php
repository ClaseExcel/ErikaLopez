
@extends('layouts.admin')
@section('title', 'Agregar centros de costos')
@section('content')

<div class="form-group">
    <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.centros_costos.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-circle-plus"></i> Agregar centros de costos
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.centros_costos.store") }}" enctype="multipart/form-data">
            @csrf
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
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }} " 
                    type="text" placeholder="" name="codigo" id="codigo" value="{{ old('codigo', '') }}" required>
                <label class="fw-normal" for="codigo">Código <b class="text-danger">*</b></label>
                @if($errors->has('codigo'))
                    <span class="text-danger">{{ $errors->first('codigo') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }} " 
                    type="text" placeholder="" name="nombre" id="nombre" value="{{ old('nombre', '') }}" required>
                <label class="fw-normal" for="nombre">Nombre <b class="text-danger">*</b></label>
                @if($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                @endif
            </div>

            <div class="form-floating mb-3">
                <select class="form-select {{ $errors->has('compania_id') ? 'is-invalid' : '' }}" name="compania_id" id="compania_id" required>
                    <option value="">Seleccionar</option>
                    @foreach($compania as $id => $nombre)
                        <option
                            value="{{ $id }}" {{ old('compania_id') == $id ? 'selected' : '' }} >{{ $nombre }}
                        </option>
                    @endforeach
                </select>
                <label class="fw-normal" for="compania_id">Compañía cliente <b class="text-danger">*</b></label>
                @if($errors->has('compania_id'))
                    <span class="text-danger">{{ $errors->first('compania_id') }}</span>
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