<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="date" id="fecha_visita" name="fecha_visita"
                value="{{ old('fecha_visita', $gestion->fecha_visita) }}" class="form-control" placeholder="" />
            <label for="fecha_visita" class="fw-normal">Fecha gestión <b class="text-danger">*</b></label>
            @error('fecha_visita')
                <p id="fecha_visita" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <select name="tipo_visita" id="tipo_visita" class="form-select">
                <option value="">Selecciona una opción</option>
                <option
                    value="Gestión de seguimiento"{{ old('tipo_visita', $gestion->tipo_visita) == 'Gestión de seguimiento' ? 'selected' : '' }}>
                    Gestión de seguimiento</option>
                <option value="Gestión prioritaria"
                    {{ old('tipo_visita', $gestion->tipo_visita) == 'Gestión prioritaria' ? 'selected' : '' }}> Gestión
                    prioritaria</option>
                <option value="Gestión de Auditoria"
                    {{ old('tipo_visita', $gestion->tipo_visita) == 'Gestión de Auditoria' ? 'selected' : '' }}>Gestión
                    de Auditoria</option>
            </select>
            <label for="tipo_visita" class="fw-normal">Tipo de gestión <b class="text-danger">*</b></label>
            @error('tipo_visita')
                <p id="tipo_visita" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <select name="cliente_id" id="cliente_id" class="form-select">
                <option value="">Selecciona un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $gestion->cliente_id ? 'selected' : '' }}>
                        {{ $cliente->razon_social }}</option>
                @endforeach
            </select>
            <label for="cliente_id" class="fw-normal">Cliente <b class="text-danger">*</b></label>
            @error('cliente_id')
                <p id="cliente_id" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="mb-3">
            <label for="detalle_visita" class="fw-normal">Detalle de la gestión<b class="text-danger">*</b></label>
            <textarea id="detalle_visita" name="detalle_visita" rows="1" class="form-control" style="height: 150px">{{ old('detalle_visita', $gestion ? $gestion->detalle_visita : '') }}</textarea>
            @error('detalle_visita')
                <p id="detalle_visita" class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="asistentes_help" name="asistentes_help"
                value="{{ old('asistentes_help', $gestion ? $gestion->asistentes_help : '') }}" class="form-control"
                placeholder=" " required />
            <label for="asistentes_help" class="fw-normal">Asistentes Erika Lopez </label>
            @if ($errors->has('asistentes_help'))
                <span id="asistentes_help" class="text-danger">{{ $errors->first('asistentes_help') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="asistentes_cliente" name="asistentes_cliente"
                value="{{ old('asistentes_cliente', $gestion ? $gestion->asistentes_cliente : '') }}"
                class="form-control" placeholder=" " required />
            <label for="asistentes_cliente" class="fw-normal">Asistentes por parte del cliente </label>
            @if ($errors->has('asistentes_cliente'))
                <span id="asistentes_cliente" class="text-danger">{{ $errors->first('asistentes_cliente') }}</span>
            @endif
        </div>
    </div>

    <div class="col border-bottom mb-3"></div>

    <div class="col-12">
        <div class="mb-3">
            <label for="compromisos" class="fw-normal">Compromisos Erika Lopez </label>
            <textarea id="compromisos" name="compromisos" rows="1" class="form-control" style="height: 150px">{!! old('compromisos', $gestion ? $gestion->compromisos : '') !!}</textarea>
        </div>
    </div>

    <div class="col-12">
        <div class=" mb-3">
            <label for="compromisos_cliente" class="fw-normal">Compromisos por parte del cliente </label>
            <textarea id="compromisos_cliente" name="compromisos_cliente" rows="1" class="form-control" style="height: 150px">{!! old('compromisos_cliente', $gestion ? $gestion->compromisos_cliente : '') !!}</textarea>
        </div>
    </div>

    <div class="col-12">
        <div class="mb-3">
            <label for="hallazgos" class="fw-normal">Observaciones </label>
            <textarea id="hallazgos" name="hallazgos" rows="1" class="form-control" style="height: 150px">{!! old('hallazgos', $gestion ? $gestion->hallazgos : '') !!}</textarea>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_uno"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_uno" name="documento_uno" class="form-control" />
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_dos"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_dos" name="documento_dos" class="form-control" />
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_tres"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_tres" name="documento_tres" class="form-control" />
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_cuatro"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_cuatro" name="documento_cuatro" class="form-control" />
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_cinco"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_cinco" name="documento_cinco" class="form-control" />
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="documento_seis"><i
                    class="fas fa-file-upload"></i></label>
            <input type="file" id="documento_seis" name="documento_seis" class="form-control" />
        </div>
    </div>
</div>
