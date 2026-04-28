@extends('layouts.admin')
@section('title', 'Info: '. $centroCosto->nombre)
@section('content')

<div class="form-group">
    <a class="btn  btn-back border btn-radius px-4" href="{{ route('admin.centros_costos.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-circle-info"></i> Información
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Código</th>
                        <td>{{ $centroCosto->codigo }}</td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td>{{ $centroCosto->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Compañía cliente</th>
                        <td>{{ $centroCosto->compania->razon_social }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>{{ $centroCosto->estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection