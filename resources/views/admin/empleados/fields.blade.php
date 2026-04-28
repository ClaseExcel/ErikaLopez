<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="number" id="cedula" name="cedula"
                value="{{ old('cedula', $empleado && $empleado->usuarios ? $empleado->usuarios->cedula : '') }}"
                class="form-control" placeholder=" " required />
            <label for="cedula" class="fw-normal">Cédula <b class="text-danger">*</b></label>
            @if ($errors->has('cedula'))
                <span id="cedula" class="text-danger">{{ $errors->first('cedula') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="number" id="numero_contacto" name="numero_contacto"
                value="{{ old('numero_contacto', $empleado ? $empleado->numero_contacto : '') }}" class="form-control"
                placeholder=" " required />
            <label for="numero_contacto" class="fw-normal">Número contacto <b class="text-danger">*</b></label>
            @if ($errors->has('numero_contacto'))
                <span id="numero_contacto" class="text-danger">{{ $errors->first('numero_contacto') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="nombres" name="nombres"
                value="{{ old('nombres', $empleado ? $empleado->nombres : '') }}" class="form-control" placeholder=" "
                required />
            <label for="nombres" class="fw-normal">Nombres <b class="text-danger">*</b></label>
            @if ($errors->has('nombres'))
                <span id="nombres" class="text-danger">{{ $errors->first('nombres') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="apellidos" name="apellidos"
                value="{{ old('apellidos', $empleado ? $empleado->apellidos : '') }}" class="form-control"
                placeholder=" " required />
            <label for="apellidos" class="fw-normal"> Apellidos <b class="text-danger">*</b></label>
            @if ($errors->has('apellidos'))
                <span id="apellidos" class="text-danger">{{ $errors->first('apellidos') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="email" id="email" name="email"
                value="{{ old('email', $empleado ? $empleado->correo_electronico : '') }}" class="form-control"
                placeholder=" " required />
            <label for="email" class="fw-normal">Correo electrónico <b class="text-danger">*</b></label>
            @if ($errors->has('email'))
                <span id="email" class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-floating mb-3">
            <input type="text" id="correos_secundarios" name="correos_secundarios"
                value="{{ old('correos_secundarios', $empleado ? $empleado->correos_secundarios : '') }}"
                class="form-control" placeholder=" " />
            <label for="correos_secundarios" class="fw-normal">Correos secundarios</label>
            <small>
                <i class="text-primary"><i class="fas fa-info-circle"></i> Ingresar más correos separalos por comas.</i>
            </small>
            @if ($errors->has('correos_secundarios'))
                <span id="correos_secundarios" class="text-danger">
                    {{ $errors->first('correos_secundarios') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-floating mb-3">
            <select id="cargos" name="cargo_id" class="form-select" required>
                <option value="">Selecciona un cargo</option>
                @foreach ($cargo as $id => $nombre)
                    <option value="{{ old('cargo_id', $id) }}"
                        {{ $empleado && $empleado->usuarios && $empleado->usuarios->cargo_id == $id ? 'selected' : '' }}>
                        {{ $nombre }}</option>
                @endforeach
            </select>
            <label for="cargos" class="fw-normal">Cargo <b class="text-danger">*</b></label>

            @if ($errors->has('cargo_id'))
                <p id="cargo_id" class="text-danger">{{ $errors->first('cargo_id') }}</p>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-floating mb-3">
            <select id="roles" name="role_id" class="form-select" required>
                <option value="">Selecciona un rol</option>
                @foreach ($roles as $id => $rol)
                    <option value="{{ old('role_id', $id) }}"
                        {{ $empleado && $empleado->usuarios && $empleado->usuarios->role_id == $id ? 'selected' : '' }}>
                        {{ $rol }}</option>
                @endforeach
            </select>

            <label for="roles" class="fw-normal">Roles <b class="text-danger">*</b></label>

            @if ($errors->has('role_id'))
                <span id="role_id" class="text-danger">{{ $errors->first('role_id') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-floating">
            <select id="empresas" name="empresa_id" class="form-select" required>
                <option value="">Selecciona una empresa</option>
                @foreach ($empresas as $id => $razon_social)
                    <option value="{{ $id }}"
                        {{ $empleado && $empleado->empresa_id == $id ? 'selected' : '' }}>
                        {{ $razon_social }}
                    </option>
                @endforeach
            </select>

            <label for="empresas" class="fw-normal">Empresa <b class="text-danger">*</b></label>

            @if ($errors->has('empresa_id'))
                <span id="empresa_id" class="text-danger">{{ $errors->first('empresa_id') }}
                </span>
            @endif
        </div>
    </div>

    <div class="col-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-12">
                    <label>
                        Empresas secundarias
                    </label> <br>
                    <label class="fw-normal">
                        Seleccionar una Empresa o varias:
                    </label>
                </div>
                <div class="col">
                    <div class="btn-group mb-2">
                        <button id="selectAllButtonEmpresasSecundarias" type="button"
                            class="btn btn-outline-info btn-xs  btn-radius px-4 me-2"
                            style="border-radius: 5px">Seleccionar Todo</button>
                        <button id="deselectAllButtonEmpresasSecundarias" type="button"
                            class="btn btn-outline-info btn-xs btn-radius px-4 "
                            style="border-radius: 5px">Deseleccionar Todo</button>
                    </div>
                </div>
            </div>
            <select name="empresas_secundarias[]" id="empresas_secundarias" class="form-select" multiple>
                @foreach ($empresas as $id => $razon_social)
                    <option value={{ $id }}
                        {{ collect(json_decode($empleado->empresas_secundarias))->contains($id) ? 'selected' : '' }}>
                        {{ $id . ' - ' . $razon_social }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('empresas_secundarias'))
                <span id="empresas_secundarias"
                    class="text-danger">{{ $errors->first('empresas_secundarias') }}</span>
            @endif
        </div>
    </div>

    @if (request()->routeIs('admin.empleados.edit'))
        <div class="col-12">
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                    name="password" id="password" autocomplete="password">
                <label class="fw-normal" for="password">Contraseña</label>
                <small>Dejar vacío si no se quiere cambiar la contraseña</small>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="form-floating mb-3">
                <input type="password" id="contraseña" name="password" class="form-control" placeholder=" " />
                <label for="contraseña" class="fw-normal">Contraseña <b class="text-danger">*</b></label>
            </div>
            @if ($errors->has('password'))
                <span id="password" class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>
    @endif
</div>
