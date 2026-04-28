@extends('layouts.admin')
@section('title',"Info: ". $empleado_cliente->nombres . ' ' . $empleado_cliente->apellidos)
@section('content')

<div class="form-group">
    <a class="btn  btn-back  border btn-radius px-4" href="{{ route('admin.empleados.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-6">        
        <div class="card">
            <div class="card-header">
                Información del empleado
            </div>

            {{-- <div class="card-body bg-light">
                <img src="{{ asset('storage/users-avatar/' . $empleado_cliente->usuarios->avatar) }}" class="img-thumbnail" alt="avatar" style="max-width: 120px; max-height: 120px;">
            </div> --}}
        
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <span class="fs-5">{{ $empleado_cliente->usuarios->nombres }} {{ $empleado_cliente->usuarios->apellidos }}</span> <br>
                        <span class="fs-6 fw-bold">{{ $empleado_cliente->usuarios->cedula }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-help-naranja text-uppercase"> {{ $empleado_cliente->usuarios->role->title }} </span>
                        <span class="badge {{ $empleado_cliente->usuarios->estado == 'ACTIVO' ? 'bg-success' : 'bg-danger' }}">
                            {{ $empleado_cliente->usuarios->estado }} </span> <br>
                            <span>{{ $empleado_cliente->empresas->razon_social}}</span>
                    </div>
                </div>

                <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(215 215 215);">

                <div class="row">
                    <div class="col auto">
                        <b> Correo eléctronico</b>
                    </div>
                    <div class="col auto text-end">
                        {{ $empleado_cliente->usuarios->email }}
                    </div>
                </div>
                <div class="row">
                    <div class="col auto">
                        <b>Correos secundarios</b>
                    </div>
                    <div class="col auto text-end">
                        {!! $empleado_cliente->correos_secundarios ? $empleado_cliente->correos_secundarios : '<span class="fst-italic text-secondary">Sin correos secundarios</span>' !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col auto">
                        <b> Número contacto</b>
                    </div>
                    <div class="col auto text-end">
                        {{ $empleado_cliente->usuarios->numero_contacto ? preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $empleado_cliente->usuarios->numero_contacto) : '―' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col auto">
                        <b>Empresas secundarias</b>
                    </div>
                    <div class="col auto text-end">
                        @if(count($empresas) > 0)
                            @foreach ($empresas as $index => $empresa)
                            <span>{{ $empresa->razon_social }}@if($index < count(json_decode($empleado_cliente->empresas_secundarias)) - 1),@endif</span>
                            @endforeach
                        @else
                            <span class="fst-italic text-secondary">Sin empresas secundarias</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection