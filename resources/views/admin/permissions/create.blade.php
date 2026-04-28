@extends('layouts.admin')
@section('title',"Crear premiso")
@section('content')

<div class="form-group">
    <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.permissions.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus"></i> Crear permiso
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.permissions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" placeholder="" value="{{ old('title', '') }}" required>
                <label class="fw-normal" for="title">Permiso</label>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
            <div class="form-group text-end">
                <button class="btn btn-save btn-radius px-4" type="submit">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>



@endsection