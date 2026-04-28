<div class="row">
    <div class="col-xl-12">
        <div class="form-floating mb-3">
            <input class="form-control" type="number" placeholder="" name="cedula" id="cedula"
                value="{{ old('cedula', $user->cedula) }}" required>
            <label class="fw-normal" for="cedula">Cédula <b class="text-danger">*</b></label>
            @if ($errors->has('cedula'))
                <span class="text-danger">{{ $errors->first('cedula') }}</span>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" type="text" placeholder="" name="nombres" id="nombres"
                value="{{ old('nombres', $user->nombres) }}" required>
            <label class="fw-normal" for="nombres">Nombres <b class="text-danger">*</b></label>
            @if ($errors->has('nombres'))
                <span class="text-danger">{{ $errors->first('nombres') }}</span>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" type="text" placeholder="" name="apellidos" id="apellidos"
                value="{{ old('apellidos', $user->apellidos) }}" required>
            <label class="fw-normal" for="apellidos">Apellidos <b class="text-danger">*</b></label>
            @if ($errors->has('apellidos'))
                <span class="text-danger">{{ $errors->first('apellidos') }}</span>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" type="email" placeholder="" name="email" id="email"
                value="{{ old('email', $user->email) }}" required>
            <label class="fw-normal" for="email">Correo <b class="text-danger">*</b></label>
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="tel" id="numero_contacto" name="numero_contacto" class="form-control" placeholder=" "
                value="{{ old('numero_contacto', $user->numero_contacto) }}" required />
            <label for="numero_contacto" class="fw-normal">Número contacto <b class="text-danger">*</b></label>
            @if ($errors->has('numero_contacto'))
                <span id="numero_contacto" class="text-danger">{{ $errors->first('numero_contacto') }}</span>
            @endif
        </div>
    </div>

    @if (request()->routeIs('admin.users.create'))
        <div class="col-xl-6">
            <div class="form-floating mb-3">
                <input class="form-control " type="password" placeholder="" name="password" id="password"
                    autocomplete="password" required>
                <label class="fw-normal" for="password">Contraseña <b class="text-danger">*</b></label>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>
    @else
        <div class="col-xl-6">
            <div class="form-floating mb-3">
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                    name="password" id="password" autocomplete="password">
                <label class="fw-normal" for="password">Contraseña</label>
                <small>Dejar vacío si no se quiere cambiar la contraseña</small>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>
    @endif

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select" name="role_id" id="role_id" required>
                <option value="">Selecciona un rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->title }}</option>
                @endforeach
            </select>
            <label class="fw-normal" for="role_id">Rol <b class="text-danger">*</b></label>
            @if ($errors->has('role_id'))
                <span class="text-danger">{{ $errors->first('role_id') }}</span>
            @endif
        </div>
    </div>
     <div class="col-xl-6">
        {{-- input para guardar numero tarjeta profesional --}}
        <div class="form-floating mb-3">
            <input type="text" id="tarje_profesional" name="tarje_profesional" class="form-control" placeholder=" "
                value="{{ old('tarje_profesional', $user->tarje_profesional) }}" required />
            <label for="tarje_profesional" class="fw-normal">Tarjeta profesional <b class="text-danger">*</b></label>
            @if ($errors->has('tarje_profesional'))
                <span id="tarje_profesional" class="text-danger">{{ $errors->first('tarje_profesional') }}</span>
            @endif
        </div>
    </div>
    @if(isset($user->id) && $user->firma !='default.jpg')
            <div class="card-body bg-light">
                <img src="{{ asset('storage/users_firma/'. $user->firma) }}" class="img-thumbnail" alt="avatar" style="max-width: 120px; max-height: 120px;">
            </div>
        @endif
    <div class="col-12">
        {{-- input para subir imagen de firma --}}
        <div class="form-group mb-3 border rounded bg-light">
            <div class="form-group mb-3">
                <label class="fw-normal" for="firma">Agregar firma digital </label>
                <input class="form-control" type="file" name="firma" id="firma"
                    accept="image/jpeg, image/png, image/jpg, image/gif">
                @if ($errors->has('firma'))
                    <span class="text-danger">{{ $errors->first('firma') }}</span>
                @endif
            </div>

            <div class="text-center">
                <img id="avatar_preview" src="#" alt="Avatar Preview"
                    style="display: none; max-width: 120px; max-height: 120px;" class="rounded mx-auto">
                <small id="text_preview" style="display: none">Vista previa</small>
            </div>
        </div>
    </div>
</div>
{{-- vista previa de la imagen de avatar --}}
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('avatar_preview').src = e.target.result;
                document.getElementById('avatar_preview').style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);

            //hacer visible el texto de vista previa
            document.getElementById('text_preview').style.display = 'block';
        }
    }

    document.getElementById('firma').addEventListener('change', function() {
        readURL(this);
    });
</script>

</div>