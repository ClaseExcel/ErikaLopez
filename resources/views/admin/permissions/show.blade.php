
@extends('layouts.admin')
@section('title',"V")
@section('content')

<div class="form-group">
    <a class="btn  btn-back  border btn-radius px-4" href="{{ route('admin.permissions.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        Permiso
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.id') }}
                        </th>
                        <td>
                            {{ $permission->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        <td>
                            {{ $permission->title }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection