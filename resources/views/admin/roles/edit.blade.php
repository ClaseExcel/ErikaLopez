
@extends('layouts.admin')
@section('title',"Editar rol")
@section('content')

<div class="form-group">
    <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.roles.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-pen"></i> Editar rol
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.roles.update", [$role->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" placeholder="" value="{{ old('title', $role->title) }}" required>
                <label class="fw-normal" for="title">Rol</label>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="fw-normal" for="permissions">Permisos</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-outline-info btn-xs select-all" style="border-radius: 5px">Agregar todos</span>
                    <span class="btn btn-outline-info btn-xs deselect-all" style="border-radius: 5px">Quitar todos</span>
                </div>
                <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple required>
                    @foreach($permissions as $id => $permission)
                        <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || $role->permissions->contains($id)) ? 'selected' : '' }}>{{ $permission }}</option>
                    @endforeach
                </select>
                @if($errors->has('permissions'))
                    <span class="text-danger">{{ $errors->first('permissions') }}</span>
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