    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <select name="usuario" id="usuario" class="form-select {{ $errors->has('usuario') ? 'is-invalid' : '' }}">
                <option value="">Seleccione un usuario</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">
                        {{ $usuario->nombres . ' ' . $usuario->apellidos }}
                    </option>
                @endforeach
            </select>
            <label for="usuario" class="fw-normal">
                Usuario</label>

            @error('usuario')
                <p id="usuario" class="mt-2 text-xs text-red-600 dark:text-red-400">
                    {{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <select class="form-select {{ $errors->has('empresa') ? 'is-invalid' : '' }}" name="empresa" id="empresa">
                <option value="">Seleccione una empresa</option>
                @foreach ($empresa as $empresa)
                    <option value="{{ $empresa->id }}"> {{ $empresa->razon_social }}</option>
                @endforeach
            </select>
            <label class="fw-normal" for="empresa">Empresa asociada</label>

            @if ($errors->has('empresa'))
                <span class="text-danger">{{ $errors->first('empresa') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} " type="date"
                placeholder="" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d')) }}">
            <label class="fw-normal" for="fecha_inicio">Fecha inicio</label>
            @if ($errors->has('fecha_inicio'))
                <span class="text-danger">{{ $errors->first('fecha_inicio') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }} " type="date"
                placeholder="" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', date('Y-m-d')) }}">
            <label class="fw-normal" for="fecha_fin">Fecha fin</label>
            @if ($errors->has('fecha_fin'))
                <span class="text-danger">{{ $errors->first('fecha_fin') }}</span>
            @endif
        </div>
    </div>
