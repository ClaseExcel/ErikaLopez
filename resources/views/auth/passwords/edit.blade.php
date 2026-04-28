
@extends('layouts.admin')
@section('title',"perfil")
@section('content')
@php
    \Debugbar::disable();
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Mi perfil
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("profile.password.updateProfile") }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('nombres') ? 'is-invalid' : '' }}" type="text" name="nombres" id="nombres" value="{{ old('nombres', auth()->user()->nombres) }}" placeholder="" required>
                        <label class="required fw-normal" for="nombres">Nombres</label>
                        @if($errors->has('nombres'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nombres') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('apellidos') ? 'is-invalid' : '' }}" type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', auth()->user()->apellidos) }}" placeholder="" required>
                        <label class="required fw-normal" for="apellidos">Apellidos</label>
                        @if($errors->has('apellidos'))
                            <div class="invalid-feedback">
                                {{ $errors->first('apellidos') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" placeholder="" required>
                        <label class="required fw-normal" for="title">Correo electrónico</label>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group text-end">
                        <button class="btn btn-save btn-radius" type="submit">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Cambiar contraseña
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route("profile.password.update") }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" placeholder="" required>
                        <label class="required fw-normal" for="password">Nueva contraseña</label>
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" type="password" name="password_confirmation" id="password_confirmation" placeholder="" required>
                        <label class="required fw-normal" for="password_confirmation">Repetir contraseña</label>
                    </div>
                    <div class="form-group text-end">
                        <button class="btn btn-save btn-radius" type="submit">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Eliminar cuenta
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("profile.password.destroyProfile") }}" onsubmit="return prompt('{{ __('global.delete_account_warning') }}') == '{{ auth()->user()->email }}'">
                    @csrf
                    <div class="form-group text-end">
                        <button class="btn btn-save btn-radius" type="submit">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection