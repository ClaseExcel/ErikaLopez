@extends('layouts.admin')
@section('title', 'Editar agenda')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-back  border btn-radius" onclick="location.href='{{ route('admin.agendas.index') }}'">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-day"></i> Agendar
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agendas.update', $agenda->id) }}">
                        @csrf
                        @method('PUT')

                        @include('admin.agenda.fields')

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                {{-- <i class="fas fa-save"></i> --}}
                                Guardar
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
        $('#modalidadCreate').change(function() {
            var virtual = document.getElementById('virtual');
            var fisica = document.getElementById('fisica');

            if ($(this).val() == 1) {
                fisica.style.display = 'none';
                virtual.style.display = 'block';

                document.getElementById('linkCreate').value = ""
            } else {
                virtual.style.display = 'none';
                fisica.style.display = 'block';
                document.getElementById('direccionCreate').value = ""
            }
        });
    </script>

@endsection
