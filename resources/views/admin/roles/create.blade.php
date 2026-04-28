
@extends('layouts.admin')
@section('title',"Ver permiso")
@section('content')

<div class="form-group">
    <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.roles.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>


<div class="row">
    {{-- Deshabilidato --}}
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-body">
                <ul>
                    <li>
                        Para ageregar un rol, se debe ingresar el nombre del rol y seleccionar los permisos que tendrá el rol. 
                    </li>
                    <li>
                        Los permisos se dividen en 6 categorías:<br>
                        Gestionar, acceder, crear, ver , editar y eliminar.
                    </li>
                    <li>
                        Agregue permisos basandose en la siguiente tabla en orden de prioridades.
                    </li>
                </ul>
                <div class="d-flex justify-content-center">
                    <img src="{{asset('images/descripcion_roles.jpg')}}" alt="" class="w-100">                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-tag"></i> Agregar rol
            </div>
        
            <div class="card-body">
                <form method="POST" action="{{ route("admin.roles.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" placeholder="" value="{{ old('title', '') }}" required>
                        <label class="fw-normal" for="title">Rol</label>
                        @if($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="permissions">Permisos</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-outline-info btn-xs select-all" style="border-radius: 5px">Agregar todos</span>
                            <span class="btn btn-outline-info btn-xs deselect-all" style="border-radius: 5px">Quitar todos</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" name="permissions[]" id="permissions" multiple>
                            @foreach($permissions as $id => $permission)
                                <option value="{{ $id }}" {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>{{ $permission }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('permissions'))
                            <span class="text-danger">{{ $errors->first('permissions') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.role.fields.permissions_helper') }}</span>
                    </div>
                    <div class="form-group text-end">
                        <button class="btn btn-save btn-radius px-4" type="submit">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</div>




@endsection