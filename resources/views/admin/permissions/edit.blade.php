@extends('layouts.admin')
@section('title',"Editar permiso")
@section('content')

<div class="form-group">
    <a class="btn  btn-back  border btn-radius px-4" href="{{ route('admin.permissions.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        Editar permiso
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.permissions.update", [$permission->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" placeholder="" value="{{ old('title', $permission->title) }}" required>
                <label class="fw-normal" for="title">Permiso</label>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
            <div class="form-group text-end">
                <button class="btn btn-save" type="submit">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>



@endsection