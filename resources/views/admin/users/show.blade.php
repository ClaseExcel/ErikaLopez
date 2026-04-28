@extends('layouts.admin')
@section('title',"Info: ". $user->nombres . ' ' . $user->apellidos)
@section('content')

<div class="form-group">
    <a class="btn  btn-back  border btn-radius px-4" href="{{ route('admin.users.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>

<div class="row">
    <div class="col-12 col-md-6">        
        <div class="card">
            <div class="card-header">
                Información
            </div>

            {{-- <div class="card-body bg-light">
                <img src="{{ asset('storage/users-avatar/' . $user->avatar) }}" class="img-thumbnail" alt="avatar" style="max-width: 120px; max-height: 120px;">
            </div> --}}
        
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <span class="fs-5">{{ $user->nombres }} {{ $user->apellidos }}</span> <br>
                        <span class="fs-6 fw-bold">{{ $user->cedula }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-help-naranja  text-uppercase"> {{ $user->role->title }} </span>
                        <span class="badge {{ $user->estado == 'ACTIVO' ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->estado }} </span> <br>
                    </div>
                </div>

                <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(215 215 215);">

                <div class="row">
                    <div class="col auto">
                        <b> Correo eléctronico</b>
                    </div>
                    <div class="col auto text-end">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="row">
                    <div class="col auto">
                        <b> Número contacto</b>
                    </div>
                    <div class="col auto text-end">
                        {{ $user->numero_contacto ? preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $user->numero_contacto) : '―' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection