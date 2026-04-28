@extends('layouts.admin')
@section('title', 'Actualizar requerimiento')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.seguimientos.cliente.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header fs-5">
                    Requerimiento {{ $requerimiento->requerimientos->consecutivo }}
                </div>

                <div class="card-body">
                    <form method="POST"
                        action="{{ route('admin.seguimientos.cliente.update', $requerimiento->id) }}"
                        enctype="multipart/form-data"
                        id="edit-requerimiento">
                        @csrf
                        @method('PUT')

                        @include('admin.requerimiento-empleado.seguimiento.fields')

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

@section('scripts')
<script src="{{ asset('js/requerimiento/requerimiento.js') }}" defer></script>
@endsection
