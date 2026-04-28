@extends('layouts.admin')
@section('title', 'Actualizar novedad')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestion-humana.show', $gestion_humana->id) }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-pen-to-square"></i> Actualizar Novedad
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.gestion-humana-eventos.update', $evento->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.gestion-humana.eventos.fields')

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
