<div class="form-floating mb-3">
    <select class="form-select"
        name="tipo_requerimiento_id" id="tipo_requerimiento_id" required>
        <option value="">Selecciona un requerimiento</option>
        @foreach ($tipo_requerimientos as $tipo_requerimiento)
                <option value="{{ $tipo_requerimiento->id }}" {{ old('tipo_requerimiento_id', $requerimiento->tipo_requerimiento_id) == $tipo_requerimiento->id ? 'selected' : '' }}>
                    {{ $tipo_requerimiento->nombre }}
                </option>
        @endforeach
    </select>

<label class="fw-normal" for="tipo_requerimiento_id">Tipo de requerimiento</label>
@if ($errors->has('tipo_requerimiento_id'))
    <span class="text-danger">{{ $errors->first('tipo_requerimiento_id') }}</span>
@endif
</div>


<div class="form-floating mb-3">

    <textarea id="descripcion" rows="5" name="descripcion" class="form-control"
        placeholder="Escribe acerca de tu requerimiento..." style="height: 100px">{{ old('descripcion', $requerimiento->descripcion) }}</textarea>
    <label for="descripcion" class="fw-normal">Descripción</label>
    @if ($errors->has('descripcion'))
        <p id="descripcion" class="">
            {{ $errors->first('descripcion') }}
        </p>
    @endif
</div>

<div class="form-floating mb-3">
    <select class="form-select" name="empresa_id" id="empresa_id"
        required>
        <option value="">selecciona una empresa</option>
        @foreach ($empresas as $empresa)
            <option value="{{ old('empresa_id', $empresa->id) }}">
                {{ $empresa->razon_social }}
            </option>
        @endforeach
    </select>

    <label class="fw-normal" for="empresa_id">Empresa solicitante</label>
    @if ($errors->has('empresa_id'))
        <span class="text-danger">{{ $errors->first('empresa_id') }}</span>
    @endif
</div>

@if(Auth::user()->role_id != 7)
<div class="form-floating mb-3">
    <select class="form-select {{ $errors->has('empleado_id') ? 'is-invalid' : '' }}" name="empleado_id"
        id="empleado_id">
        <option value="">Selecciona un empleado</option>
    </select>

    <label class="fw-normal" for="empleado_id">Empleado solicitante</label>
    @if ($errors->has('empleado_id'))
        <span class="text-danger">{{ $errors->first('empleado_id') }}</span>
    @endif
</div>
@endif

<div class="row">

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>

    <div class="col-12 col-md-6">
        <div class="input-group mb-3">
            <label class="input-group-text bg-transparent" for="files"><i class="fas fa-file-upload"></i></label>
            <input type="file" id="files" name="documentos[]" class="form-control" />
        </div>
        @if ($errors->has('documentos'))
            <span id="descripcion" class="">
                {{ $errors->first('documentos') }}
            </span>
        @endif
    </div>
</div>
