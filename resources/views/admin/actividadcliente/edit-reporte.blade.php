@extends('layouts.admin')
@section('title', 'Reporte actividad cliente')
@section('content')

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.actividad_cliente.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="far fa-file-alt"></i> Reporte actividad cliente
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reporte.update', $actividad_cliente->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="grid gap-4 col-span-2 sm:grid-cols-3 sm:gap-6">
                                <div class="form-floating mb-3">
                                    <input type="text" id="nombre" name="nombre"
                                        value="{{ $actividad_cliente->nombre }}" class="form-control" placeholder=""
                                        disabled />
                                    <label for="nombre" class="fw-normal">Nombre</label>
                                </div>

                                @if (
                                    $reporteActividad->estado_actividad_id != 7 &&
                                        $reporteActividad->estado_actividad_id != 8 &&
                                        $reporteActividad->estado_actividad_id != 4)
                                    <div class="form-floating mb-3">
                                        <select
                                            class="form-select {{ $errors->has('estado_actividad_id') ? 'is-invalid' : '' }}"
                                            name="estado_actividad_id" id="estado_actividad_id" required>
                                            <option value="">Seleccione un estado</option>
                                            @foreach ($estado_actividad as $actividad)
                                                <option value="{{ $actividad->id }}">{{ $actividad->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label class="fw-normal" for="estado_actividad_id">Estado actividad</label>
                                        @if ($errors->has('estado_actividad_id'))
                                            <span class="text-danger">{{ $errors->first('estado_actividad_id') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="form-floating mb-3">
                                        <select
                                            class="form-select {{ $errors->has('estado_actividad_id') ? 'is-invalid' : '' }}"
                                            name="estado_actividad_id" id="estado_actividad_id" disabled>
                                            <option value="">Seleccione un estado</option>
                                            @foreach ($estado_actividad as $actividad)
                                                <option value="{{ $actividad->id }}">{{ $actividad->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label class="fw-normal" for="estado_actividad_id">Estado actividad</label>
                                        @if ($errors->has('estado_actividad_id'))
                                            <span class="text-danger">{{ $errors->first('estado_actividad_id') }}</span>
                                        @endif
                                    </div>
                                @endif



                                <div class="form-floating mb-3" id="progreso" style="display:none;">
                                    <select class="form-select" name="progreso">
                                        <option value="">Seleccione un progreso</option>
                                        @for ($i = 0; $i <= 100; $i++)
                                            <option {{ $actividad_cliente->progreso == $i ? 'selected' : '' }}
                                                value="{{ $i }}">{{ $i }} </option>
                                        @endfor
                                    </select>
                                    <label for="" class="fw-normal"> Progreso %</label>
                                    @error('progreso')
                                        <span class="text-danger">{{ $errors->first('progreso') }}</span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3" id="empresa" style="display:none;">
                                    <select name="cliente_id" id="empresa_id" disabled class="form-select">
                                        <option value="">Seleccione un cliente</option>
                                        @if (!is_null($cliente))
                                            @foreach ($cliente as $cliente)
                                                <option
                                                    {{ $actividad_cliente->cliente_id == $cliente->id ? 'selected' : '' }}
                                                    value="{{ $cliente->id }}">{{ $cliente->razon_social }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="cliente_id" class="fw-normal">Empresa</label>
                                    @error('cliente_id')
                                        <span id="cliente_id" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3" id="empleado" style="display:none;">
                                    <select name="usuario_id" id="usuario_id" class="form-select">
                                        <option value="">Seleccione una opción</option>
                                        @if (!is_null($usuario))
                                            @foreach ($usuario as $item)
                                                <option {{ $actividad_cliente->usuario_id == $item->id ? 'selected' : '' }}
                                                    value="{{ $item->id }}">
                                                    {{ $item->nombres . ' ' . $item->apellidos }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="usuario_id" class="fw-normal">Empleado</label>
                                    @error('usuario_id')
                                        <span id="usuario_id" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3" id="just" style="display:none;">
                                    <input type="text" id="justificacion" name="justificacion" class="form-control"
                                        placeholder=" " />
                                    <label for="justificacion" class="fw-normal">Justificación</label>
                                    @error('justificacion')
                                        <span id="justificacion" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3" id="recomendaciones" style="display:none;">
                                    <input type="text" id="recomendacion" name="recomendacion" class="form-control"
                                        placeholder=" " />
                                    <label for="recomendacion" class="fw-normal">Recomendaciones y Tareas</label>
                                    @error('recomendacion')
                                        <span id="recomendacion" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3" id="modalidad" style="display:none;">
                                    <select name="modalidad" id="modalidad" class="form-select">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Presencial">Presencial</option>
                                    </select>
                                    <label for="modalidad" class="fw-normal">Modalidad</label>
                                    @error('modalidad')
                                        <span id="modalidad" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="my-3" id="documento" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="file" name="documento" class="form-control" />
                                        </div>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <div class="col-md-6 mb-3">
                                                <input type="file" name="documento_extra[]" class="form-control" />
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                            </div>

                            <div class="form-group text-end">
                                <button class="btn btn-save btn-radius px-4" type="submit">
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
    @parent
    <script src="{{ asset('js/actividadcliente/show.js') }}" defer></script>
@endsection
