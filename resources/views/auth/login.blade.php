@extends('layouts.app')
@section('content')
@php
    \Debugbar::disable();
@endphp
    <div class="login-box bg-white shadow-lg py-4" style="border-radius: 5px">
        <div class="login-logo d-flex justify-content-center">
            <a href="{{ route('admin.home') }}">
                <img src="{{ asset('../images/logos/logo_contable.png') }}" width="200" alt="Home">
            </a>
        </div>
        <div class="card bg-transparent border-0 shadow-none ">
            <div class="card-body login-card-body pb-0 mb-0">
                @if (session()->has('message'))
                    <p class="alert alert-info">
                        {{ session()->get('message') }}
                    </p>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-floating mb-3">
                        <input id="email" type="email"
                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}  " required autofocus
                            placeholder="Correo electrónico" name="email" value="{{ old('email', null) }}">
                        <label class="fw-normal" for="email"><i class="fas fa-envelope"></i> Correo electrónico</label>

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-floating mb-3">
                        <input id="password" type="password"
                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}  " name="password"
                            required placeholder="Contraseña">
                        <label class="fw-normal" for="password"><i class="fas fa-key"></i> Contraseña</label>

                        @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>


                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-block btn-save border btn-radius py-3" style="border-radius: 10px">
                                Ingresar
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


                {{-- @if (Route::has('password.request'))
                <p class="mb-1">
                    <a href="{{ route('password.request') }}">
                        {{ trans('global.forgot_password') }}
                    </a>
                </p>
            @endif --}}
                <p class="mb-1">

                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>



    <footer class="footer text-center">
        <small> Desarrollado por <strong><a href="https://www.helpdigital.com.co" target="BLANK">Help!Digital</a>
            &copy;</strong> Todos los derechos reservados</small>
            
        <small><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Términos y condiciones</a></small>
    </footer>


    @include('modal.terminosycondiciones')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
