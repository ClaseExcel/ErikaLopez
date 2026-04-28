<div class="row">
    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $actividad_cliente->nombre) }}"
                class="form-control" placeholder="" required />
            <label for="nombre" class="fw-normal">Nombre de la actividad</label>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select name="actividad_id" id="tipo_actividad_id" class="form-select">
                <option value="">Seleccione una actividad</option>
                @foreach ($tipo_actividades as $actividad)
                    <option
                        {{ old('actividad_id', $actividad_cliente->actividad_id) == $actividad->id ? 'selected' : '' }}
                        value="{{ $actividad->id }}">
                        {{ $actividad->nombre }}
                    </option>
                @endforeach
            </select>
            <label for="actividad_id" class="fw-normal">Tipo actividad <b class="text-danger">*</b></label>
            @error('actividad_id')
                <p id="actividad_id" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select name="progreso" id="progreso" class="form-select">
                <option value="">Seleccione un progreso</option>
                @for ($i = 0; $i <= 100; $i++)
                    <option {{ old('progreso', $actividad_cliente->progreso) == $i ? 'selected' : '' }}
                        value="{{ $i }}">
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <label for="progreso" class="fw-normal"> Progreso % </label>
            @error('progreso')
                <p id="progreso" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select name="prioridad" id="prioridad" class="form-select">
                <option value="">Selecciona una prioridad</option>
                <option value="SÍ" {{ old('prioridad', $actividad_cliente->prioridad) == 'SÍ' ? 'selected' : '' }}>
                    SI</option>
                <option value="NO" {{ old('prioridad', $actividad_cliente->prioridad) == 'NO' ? 'selected' : '' }}>
                    NO</option>
            </select>
            <label for="prioridad" class="fw-normal">Prioridad alta</label>
        </div>
    </div>

    <div class="col-12 col-xl-12">
        <div class="form-floating mb-3">
            <input type="date" id="fecha_vencimiento" name="fecha_vencimiento"
                value="{{ old('fecha_vencimiento', \Carbon\Carbon::parse($actividad_cliente->vencimiento)->format('Y-m-d')) }}"
                class="form-control" placeholder="" />
            <label for="fecha_vencimiento" class="fw-normal">Fecha vencimiento <b class="text-danger">*</b></label>
            @error('fecha_vencimiento')
                <p id="fecha_vencimiento" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-xl-3">
        <div class="form-floating mb-3">
            <select name="recurrencia" id="recurrencia" class="form-select">
                <option value="">Selecciona una recurrencia</option>
                <option value="Diario"
                    {{ old('recurrencia', $actividad_cliente->recurrencia) == 'Diario' ? 'selected' : '' }}>
                    Diario</option>
                <option value="Semanalmente"
                    {{ old('recurrencia', $actividad_cliente->recurrencia) == 'Semanalmente' ? 'selected' : '' }}>
                    Semanalmente</option>
                <option value="Mensualmente"
                    {{ old('recurrencia', $actividad_cliente->recurrencia) == 'Mensualmente' ? 'selected' : '' }}>
                    Mensualmente</option>
            </select>
            <label for="recurrencia" class="fw-normal">Recurrencia</label>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="form-floating mb-3" id="div_periodicidad">
            <input type="number" id="periodicidad" name="periodicidad"
                value="{{ old('periodicidad', $actividad_cliente->periodicidad) }}" class="form-control"
                placeholder="" />
            <label for="periodicidad" class="fw-normal">Periodicidad</label>
            @error('periodicidad')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-xl-3">
        <div class="form-floating mb-3">
            <input type="number" id="recordatorio" name="recordatorio"
                value="{{ old('recordatorio', $actividad_cliente->recordatorio) }}" class="form-control"
                placeholder="" />
            <label for="recordatorio" class="fw-normal">Cantidad recordatorios <b class="text-danger">*</b></label>
            @error('recordatorio')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-xl-3">
        <div class="form-floating mb-3">
            <input type="number" id="recordatorio_distancia" name="recordatorio_distancia"
                value="{{ old('recordatorio_distancia', $actividad_cliente->recordatorio_distancia) }}"
                class="form-control" placeholder="" />
            <label for="recordatorio_distancia" class="fw-normal">Cantidad días entre
                recordatorios <b class="text-danger">*</b></label>
            @error('recordatorio_distancia')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-xl-12">
        <div class="form-floating mb-3" id="div_periodicidad_corte">
            <input type="date" id="fecha_corte_periocidad" name="fecha_corte_periocidad"
                value="{{ old('fecha_corte_periocidad', \Carbon\Carbon::parse($actividad_cliente->fecha_corte_periocidad)->format('Y-m-d')) }}"
                class="form-control" placeholder="" />
            <label for="fecha_corte_periocidad" class="fw-normal">Fecha finalización periodicidad</label>
            @error('fecha_corte_periocidad')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-12 col-xl-12">
        <div class="form-floating mb-3">
            <textarea id="nota" name="nota" rows="1" class="form-control" style="height: 100px">{{ old('nota', $actividad_cliente->nota) }}</textarea>
            <label for="nota" class="fw-normal">Observación</label>
        </div>
    </div>

    @if (request()->routeIs('admin.actividad_cliente.edit'))
        <div class="col-12 col-xl-4">
            <div class="form-floating mb-3">
                <select name="responsable_id" id="responsable_id" class="form-select" disabled>
                    <option value="">Seleccione una opción</option>
                    @foreach ($responsable as $responsable)
                        <option
                            {{ old('responsable_id', $actividad_cliente->responsable_id) == $responsable->id ? 'selected' : '' }}
                            value="{{ $responsable->id }}">
                            {{ $responsable->nombre }}
                        </option>
                    @endforeach
                </select>
                <label for="responsable_id" class="fw-normal">Responsable actividad <b
                        class="text-danger">*</b></label>
                @error('responsable_id')
                    <p id="responsable_id" class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptionsEmpresa" placeholder="Escribe para buscar..."
                    name="cliente_id" id="cliente_id" autocomplete="off" readonly>
                <datalist id="datalistOptionsEmpresa">
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id . ' - ' . $cliente->razon_social }}"
                            data-id="{{ $cliente->id }}">
                        </option>
                    @endforeach
                </datalist>
                <label class="fw-normal">Empresa <b class="text-danger">*</b></label>
                <input type="hidden" id="empresaexistente" value="{{ $actividad_cliente->cliente_id }}">
                @error('proceso_id')
                    <p class="text-danger text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <script>
            var valorExistente = document.getElementById('empresaexistente').value;

            if (valorExistente) {
                var datalistOptions = document.getElementById('datalistOptionsEmpresa');
                var options = datalistOptions.getElementsByTagName('option');

                for (var i = 0; i < options.length; i++) {
                    var valorDataId = options[i].getAttribute('data-id');
                    if (valorDataId == valorExistente) {
                        document.getElementById('cliente_id').value = options[i].value;
                        break;
                    }
                }
            }
        </script>

        <div class="col-12 col-xl-4">
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptionsResponsable" placeholder="Escribe para buscar..."
                    name="usuario_id" id="usuario_id" autocomplete="off" readonly>
                <datalist id="datalistOptionsResponsable">
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id . ' - ' . $usuario->nombres . ' ' . $usuario->apellidos }}"
                            data-id="{{ $usuario->id }}">
                        </option>
                    @endforeach
                </datalist>
                <label class="fw-normal">Responsable <b class="text-danger">*</b></label>
                <input type="hidden" id="usuarioexistente" value="{{ $actividad_cliente->usuario_id }}">
                @error('usuario_id')
                    <p class="text-danger text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <script>
            var valorExistente = document.getElementById('usuarioexistente').value;

            if (valorExistente) {
                var datalistOptions = document.getElementById('datalistOptionsResponsable');
                var options = datalistOptions.getElementsByTagName('option');

                for (var i = 0; i < options.length; i++) {
                    var valorDataId = options[i].getAttribute('data-id');
                    if (valorDataId == valorExistente) {
                        document.getElementById('usuario_id').value = options[i].value;
                        break;
                    }
                }
            }
        </script>

        @if ($actividad_cliente->empresa_asociada_id)
            <div class="col-12 col-xl-12">
                <div class="form-floating mb-3">
                    <input class="form-control" list="datalistOptionsEmpresaAsociada"
                        placeholder="Escribe para buscar..." name="empresa_asociada_id" id="empresa_asociada_id"
                        autocomplete="off" readonly>
                    <datalist id="datalistOptionsEmpresaAsociada">
                        <option value="">Todos los clientes</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id . ' - ' . $cliente->razon_social }}"
                                data-id="{{ $cliente->id }}">
                            </option>
                        @endforeach
                    </datalist>
                    <input type="hidden" id="clienteexistente"
                        value="{{ $actividad_cliente->empresa_asociada_id }}">
                    <label class="fw-normal">Clientes</label>
                    @if ($errors->has('empresa_id'))
                        <span class="text-danger">{{ $errors->first('empresa_id') }}</span>
                    @endif
                </div>
            </div>

            <script>
                var valorExistente = document.getElementById('clienteexistente').value;

                if (valorExistente) {
                    var datalistOptions = document.getElementById('datalistOptionsEmpresaAsociada');
                    var options = datalistOptions.getElementsByTagName('option');

                    for (var i = 0; i < options.length; i++) {
                        var valorDataId = options[i].getAttribute('data-id');
                        if (valorDataId == valorExistente) {
                            document.getElementById('empresa_asociada_id').value = options[i].value;
                            break;
                        }
                    }
                }
            </script>
        @endif
    @else
        <div class="col-12 col-xl-6">
            <div class="form-floating mb-3">
                <select name="responsable_id" id="responsable_id" class="form-select">
                    <option value="">Seleccione una opción</option>
                    @foreach ($responsable as $responsable)
                        <option
                            {{ old('responsable_id', $actividad_cliente->responsable_id) == $responsable->id ? 'selected' : '' }}
                            value="{{ $responsable->id }}">
                            {{ $responsable->nombre }}
                        </option>
                    @endforeach
                </select>
                <label for="responsable_id" class="fw-normal">Responsable actividad <b
                        class="text-danger">*</b></label>
                @error('responsable_id')
                    <p id="responsable_id" class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptionsEmpresa" placeholder="Escribe para buscar..."
                    name="cliente_id" id="cliente_id" autocomplete="off" disabled>
                <datalist id="datalistOptionsEmpresa">
                </datalist>
                <label class="fw-normal">Empresa <b class="text-danger">*</b></label>
                @error('proceso_id')
                    <p class="text-danger text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-12 col-xl-12">
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptionsResponsable" placeholder="Escribe para buscar..."
                    name="usuario_id" id="usuario_id" autocomplete="off" disabled>
                <datalist id="datalistOptionsResponsable">
                </datalist>
                <label class="fw-normal">Responsable <b class="text-danger">*</b></label>
                @error('usuario_id')
                    <p class="text-danger text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-12 col-xl-12" id='empresa_asociada' style="display:none">
            <div class="form-floating mb-3">
                <input class="form-control" list="datalistOptionsEmpresaAsociada"
                    placeholder="Escribe para buscar..." name="empresa_asociada_id" id="empresa_asociada_id"
                    autocomplete="off">
                <datalist id="datalistOptionsEmpresaAsociada">
                    <option value="">Todos los clientes</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id . ' - ' . $cliente->razon_social }}"
                            data-id="{{ $cliente->id }}">
                        </option>
                    @endforeach
                </datalist>
                <input type="hidden" id="clienteexistente" value="{{ $actividad_cliente->empresa_asociada_id }}">
                <label class="fw-normal">Clientes</label>
                @if ($errors->has('empresa_id'))
                    <span class="text-danger">{{ $errors->first('empresa_id') }}</span>
                @endif
            </div>
        </div>
    @endif



    <div class="row mb-3">
        <div class="col border-bottom">
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_1"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_1" name="documento_1" class="form-control" />
            @if ($errors->has('documento_1'))
                <span id="documento_1" class="text-danger">
                    {{ $errors->first('documento_1') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_2"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_2" name="documento_2" class="form-control" />
            @if ($errors->has('documento_2'))
                <span id="documento_2" class="text-danger">
                    {{ $errors->first('documento_2') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_3"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_3" name="documento_3" class="form-control" />
            @if ($errors->has('documento_3'))
                <span id="documento_3" class="text-danger">
                    {{ $errors->first('documento_3') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_4"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_4" name="documento_4" class="form-control" />
            @if ($errors->has('documento_4'))
                <span id="documento_4" class="text-danger">
                    {{ $errors->first('documento_4') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_5"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_5" name="documento_5" class="form-control" />
            @if ($errors->has('documento_5'))
                <span id="documento_5" class="text-danger">
                    {{ $errors->first('documento_5') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_6"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_6" name="documento_6" class="form-control" />
            @if ($errors->has('documento_6'))
                <span id="documento_6" class="text-danger">
                    {{ $errors->first('documento_6') }}
                </span>
            @endif
        </div>
    </div>
</div>
