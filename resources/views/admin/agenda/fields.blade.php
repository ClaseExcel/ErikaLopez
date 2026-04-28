<div class="row">
    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <input class="form-control {{ $errors->has('fecha_disponibilidad') ? 'is-invalid' : '' }} " type="date"
                placeholder="" name="fecha_disponibilidad" id="fecha_disponibilidad"
                value="{{ old('fecha_disponibilidad', Carbon\Carbon::parse($agenda->agenda_empresa ? $agenda->agenda_empresa->agenda->fecha_disponibilidad : '')->format('Y-m-d')) }}">
            <label class="fw-normal" for="fecha_disponibilidad">
                <i class="far fa-calendar"></i> Fecha de disponibilidad <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_disponibilidad'))
                <span class="text-danger">{{ $errors->first('fecha_disponibilidad') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input class="form-control {{ $errors->has('hora_inicio') ? 'is-invalid' : '' }} " type="time"
                placeholder="" name="hora_inicio" id="hora_inicio"
                value="{{ old('hora_inicio', Carbon\Carbon::parse($agenda->agenda_empresa ? $agenda->agenda_empresa->agenda->hora_inicio : '')->format('H:i')) }}">
            <label class="fw-normal" for="hora_inicio">
                <i class="far fa-clock"></i> Hora de inicio <b class="text-danger">*</b></label>
            @if ($errors->has('hora_inicio'))
                <span class="text-danger">{{ $errors->first('hora_inicio') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input class="form-control {{ $errors->has('hora_fin') ? 'is-invalid' : '' }} " type="time"
                placeholder="" name="hora_fin" id="hora_fin"
                value="{{ old('hora_fin', Carbon\Carbon::parse($agenda->agenda_empresa ? $agenda->agenda_empresa->agenda->hora_fin : '')->format('H:i')) }}">
            <label class="fw-normal" for="hora_fin">
                <i class="far fa-clock"></i> Hora de finalización <b class="text-danger">*</b></label>
            @if ($errors->has('hora_fin'))
                <span class="text-danger">{{ $errors->first('hora_fin') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsEmpresa" placeholder="Escribe para buscar..."
                name="empresa" id="empresa_id" autocomplete="off">
            <datalist id="datalistOptionsEmpresa">
                <option value="">Todos los clientes</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->razon_social }}" data-id="{{ $cliente->id }}">
                    </option>
                @endforeach
            </datalist>
            @if ($agenda->agenda_id)
                <input type="hidden" id="clienteexistente" value="{{ $agenda->agenda_empresa->empresa_id }}">
            @endif
            <label class="fw-normal">Clientes</label>
            @if ($errors->has('empresa_id'))
                <span class="text-danger">{{ $errors->first('empresa_id') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12">
        <div class="border-bottom mb-3"></div>
    </div>

    <input type="hidden" name="estado" id="estado" value="2">

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="motivoCreate" name="motivo"
                class="form-control {{ $errors->has('motivo') ? 'is-invalid' : '' }}" placeholder=" "
                value="{{ old('motivo', $agenda->motivo) }}" />
            <label for="motivo" class="fw-normal">
                Motivo <b class="text-danger">*</b></label>
            @if ($errors->has('motivo'))
                <span class="text-danger">{{ $errors->first('motivo') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating  mb-3">
            <select id="modalidadCreate" name="modalidad_id"
                class="form-select {{ $errors->has('modalidad_id') ? 'is-invalid' : '' }}">
                <option value="">Selecciona una modalidad</option>
                @foreach ($modalidades as $modalidad)
                    <option value="{{ $modalidad->id }}"
                        {{ $agenda->modalidad_id && $agenda->modalidad_id == $modalidad->id ? 'selected' : '' }}>
                        {{ $modalidad->nombre }}</option>
                @endforeach
            </select>
            <label class="fw-normal" for="modalidad_id">Modalidades <b class="text-danger">*</b></label>
            @if ($errors->has('modalidad_id'))
                <span class="text-danger">{{ $errors->first('modalidad_id') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-12">
        <div class="form-floating mb-3" id="fisica" style="display: none;">
            <input type="text" id="direccionCreate" name="direccion"
                class="form-control {{ $errors->has('direccion') ? 'is-invalid' : '' }} " type="text"
                value="{{ old('direccion', $agenda->direccion) }}" placeholder="" />
            <label for="direccion" id="label-fisico" class="fw-normal">
                Dirección</label>
            @if ($errors->has('direccion'))
                <span class="text-danger">{{ $errors->first('direccion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-12">
        <div class="form-floating mb-3" id="virtual" style="display: none;">
            <input type="text" id="linkCreate" name="link"
                class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}"
                value="{{ old('link', $agenda->link) }}" placeholder=" " />
            <label for="link" id="label-virtual" class="fw-normal">
                Link</label>
            @if ($errors->has('link'))
                <span class="text-danger">{{ $errors->first('link') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <input type="text" id="observacionCreate" name="observacion"
                value="{{ old('observacion', $agenda->observacion) }}"
                class="form-control {{ $errors->has('observacion') ? 'is-invalid' : '' }}" placeholder=" " />
            <label for="observacion" class="fw-normal">
                Observación</label>
        </div>
    </div>
</div>

<script>
    var valorExistente = document.getElementById('clienteexistente').value;

    if (valorExistente) {
        var datalistOptions = document.getElementById('datalistOptionsEmpresa');
        var options = datalistOptions.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            var valorDataId = options[i].getAttribute('data-id');
            if (valorDataId == valorExistente) {
                document.getElementById('empresa_id').value = options[i].value;
                break;
            }
        }
    }
</script>
