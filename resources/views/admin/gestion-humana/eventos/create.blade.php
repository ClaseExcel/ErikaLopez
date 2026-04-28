@extends('layouts.admin')
@section('title', 'Agregar novedad')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestion-humana.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus"></i> Agregar Novedad
                </div>
        
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gestion-humana-eventos.store') }}" enctype="multipart/form-data">
                        @csrf
        
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
@section('scripts')
<script>
</script>
@endsection
