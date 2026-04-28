@extends('layouts.admin')
@section('title', 'Actualizar empleado')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.empleados.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i> Actualizar empleado
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.empleados.update', $empleado->user_id) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.empleados.fields')

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script src="{{ asset('js/empleados/empleados.js') }}" defer></script>
@endsection
