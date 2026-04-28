@extends('layouts.admin')
@section('title', 'Editar: ' . $user->name)
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.users.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>


    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-edit"></i> Editar usuario
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', [$user->id]) }}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        @include('admin.users.fields')
                        </script>
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
