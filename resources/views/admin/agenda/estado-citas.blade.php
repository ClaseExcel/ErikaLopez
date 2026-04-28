@extends('layouts.admin')
@section('title', 'Estado cita')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.agendas.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-file-alt"></i> Actualizar Estado de la Cita
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agenda.estadosupdate', $agenda->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-center">
                                    <label for="estado" class="h4 font-weight-bold">Estado de la Cita:</label>
                                    <select name="estado" id="estado" class="form-control form-control-lg text-center"
                                        style="font-size: 1.2em; padding: 10px; max-width: 400px; margin: 0 auto;" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="1" {{ $agenda->estado == '1' ? 'selected' : '' }}>Cancelado por
                                            el cliente</option>
                                        <option value="2" {{ $agenda->estado == '2' ? 'selected' : '' }}>Cancelado por
                                            la empresa</option>
                                        <option value="3" {{ $agenda->estado == '3' ? 'selected' : '' }}>Reprogramado
                                            por el cliente</option>
                                        <option value="4" {{ $agenda->estado == '4' ? 'selected' : '' }}>Reprogramado
                                            por la empresa</option>
                                        <option value="5" {{ $agenda->estado == '5' ? 'selected' : '' }}>Realizada
                                        </option>
                                        <option value="6" {{ $agenda->estado == '6' ? 'selected' : '' }}>Programada
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Espacio entre el campo de estado y los datos informativos -->
                            <div class="col-12">
                                <div class="border-bottom mb-3"></div>
                            </div>

                            <div class="col-4 mb-2 text-center">
                                <!-- Datos informativos menos destacados -->
                                   <span class="fs-5 text-muted">Cliente</span> <br>
                                   <span>{{ $agenda->razon_social }}</span>
                            </div>
                            <div class="col-4 mb-2 text-center">
                                <span class="fs-5 text-muted">Motivo</span> <br>
                                <span>{{ $agenda->motivo }}</span>
                            </div>
                            <div class="col-4 mb-2 text-center">
                                <span class="fs-5 text-muted">Fecha de creación</span> <br>
                                <span>{{ \Carbon\Carbon::parse($agenda->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY h:m a') }}</span>
                            </div>

                            <div class="col-12 mt-3">
                                <!-- Botón para guardar los cambios -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-save btn-radius px-4">Guardar cambios</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    @parent
    <script src="{{ asset('js/actividadcliente/show.js') }}" defer></script>
@endsection
