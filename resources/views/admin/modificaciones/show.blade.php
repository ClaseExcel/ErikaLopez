@extends('layouts.admin')
@section('title', 'Info: '. $modificacion->compania->razon_social)
@section('content')

<div class="form-group">
    <a class="btn  btn-back border btn-radius px-4" href="{{ route('admin.modificaciones.index') }}">
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
                        <th>Compañía cliente</th>
                        <td>{{ $modificacion->compania->razon_social }}</td>
                    </tr>
                    <tr>
                        <th>Periodo contable</th>
                        <td>{{ $modificacion->periodo }}</td>
                    </tr>
                    <tr>
                        <th>Cuenta contable</th>
                        <td>{{ $modificacion->movimiento }}</td>
                    </tr>
                    <tr>
                        <th>Valor ajustado</th>
                        <td>$ {{ number_format($modificacion->valor_ajustado) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection