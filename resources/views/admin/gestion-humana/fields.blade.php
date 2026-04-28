<div class="row">
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistEmpresas" placeholder="Escribe Para Buscar..." name="empresa_id"
                id="empresa_id" autocomplete="off">
            <datalist id="datalistEmpresas">
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }} - {{ $empresa->razon_social }}" data-id="{{ $empresa->id }}">
                    </option>
                @endforeach
            </datalist>
            <label class="fw-normal">Empresa <b class="text-danger">*</b></label>
        </div>
        <input type="hidden" id="empresaexistente" value="{{ $gestion_humana->empresa_id }}">
        @if ($errors->has('empresa_id'))
            <span id="empresa_id" class="text-danger">{{ $errors->first('empresa_id') }}
            </span>
        @endif
    </div>

    <script>
        var valorExistente = document.getElementById('empresaexistente').value;

        if (valorExistente) {
            var datalistOptions = document.getElementById('datalistEmpresas');
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

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select id="tipo_identificacion" name="tipo_identificacion" class="form-select">
                <option value="" {{ old('tipo_identificacion') == '' ? 'selected' : '' }}>Selecciona una opción
                </option>
                @foreach ($tipos_identificacion as $tipo)
                    <option value="{{ $tipo }}"
                        {{ old('tipo_identificacion', $gestion_humana->tipo_identificacion ? $gestion_humana->tipo_identificacion : '') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }} </option>
                @endforeach
            </select>
            <label for="frecuencias" class="fw-normal">Tipo de identificación <b class="text-danger">*</b></label>
            @if ($errors->has('tipo_identificacion'))
                <span id="tipo_identificacion"
                    class="text-danger text-sm">{{ $errors->first('tipo_identificacion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="cedula" value="{{ old('cedula', $gestion_humana->cedula) }}"
                class="form-control" placeholder=" " />
            <label for="cedula" class="fw-normal">Cédula <b class="text-danger">*</b></label>
            @if ($errors->has('cedula'))
                <span id="cedula" class="text-danger">{{ $errors->first('cedula') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="nombres" value="{{ old('nombres', $gestion_humana->nombres) }}"
                class="form-control" placeholder=" " />
            <label for="nombres" class="fw-normal">Nombres <b class="text-danger">*</b></label>
            @if ($errors->has('nombres'))
                <span id="nombres" class="text-danger">{{ $errors->first('nombres') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', $gestion_humana->fecha_nacimiento) }}" class="form-control"
                placeholder=" " />
            <label for="fecha_nacimiento" class="fw-normal">Fecha de nacimiento <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_nacimiento'))
                <span id="fecha_nacimiento" class="text-danger">{{ $errors->first('fecha_nacimiento') }}</span>
            @endif
        </div>
    </div>

    <script>
        const inputFecha = document.getElementById("fecha_nacimiento");
        const hoy = new Date();
        const cincoAniosAtras = new Date(hoy.getFullYear() - 5, hoy.getMonth(), hoy.getDate());

        // Establece la fecha máxima permitida
        inputFecha.max = cincoAniosAtras.toISOString().split("T")[0];
    </script>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="estado_civil" value="{{ old('estado_civil', $gestion_humana->estado_civil) }}"
                class="form-control" placeholder=" " />
            <label for="estado_civil" class="fw-normal">Estado civil </label>
            @if ($errors->has('estado_civil'))
                <span id="estado_civil" class="text-danger">{{ $errors->first('estado_civil') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="telefono" value="{{ old('telefono', $gestion_humana->telefono) }}"
                class="form-control" placeholder=" " />
            <label for="telefono" class="fw-normal">Teléfono </label>
            @if ($errors->has('telefono'))
                <span id="telefono" class="text-danger">{{ $errors->first('telefono') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="correo_electronico"
                value="{{ old('correo_electronico', $gestion_humana->correo_electronico) }}" class="form-control"
                placeholder=" " />
            <label for="correo_electronico" class="fw-normal">Correo electrónico </label>
            @if ($errors->has('correo_electronico'))
                <span id="correo_electronico" class="text-danger">{{ $errors->first('correo_electronico') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="direccion" value="{{ old('direccion', $gestion_humana->direccion) }}"
                class="form-control" placeholder=" " />
            <label for="direccion" class="fw-normal">Dirección <b class="text-danger">*</b></label>
            @if ($errors->has('direccion'))
                <span id="direccion" class="text-danger">{{ $errors->first('direccion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="municipio_residencia"
                value="{{ old('municipio_residencia', $gestion_humana->municipio_residencia) }}" class="form-control"
                placeholder=" " />
            <label for="municipio_residencia" class="fw-normal">Municipio de residencia <b
                    class="text-danger">*</b></label>
            @if ($errors->has('municipio_residencia'))
                <span id="municipio_residencia"
                    class="text-danger">{{ $errors->first('municipio_residencia') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="salario" value="{{ old('salario', $gestion_humana->salario) }}"
                class="form-control" placeholder=" " />
            <label for="salario" class="fw-normal">Salario <b class="text-danger">*</b></label>
            @if ($errors->has('salario'))
                <span id="salario" class="text-danger">{{ $errors->first('salario') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="cargo" value="{{ old('cargo', $gestion_humana->cargo) }}"
                class="form-control" placeholder=" " />
            <label for="cargo" class="fw-normal">Cargo <b class="text-danger">*</b></label>
            @if ($errors->has('cargo'))
                <span id="cargo" class="text-danger">{{ $errors->first('cargo') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="bonificacion"
                value="{{ old('bonificacion', $gestion_humana->bonificacion) }}" class="form-control"
                placeholder=" " />
            <label for="bonificacion" class="fw-normal">Bonificación </label>
            @if ($errors->has('bonificacion'))
                <span id="bonificacion" class="text-danger">{{ $errors->first('bonificacion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="auxilio_transporte"
                value="{{ old('auxilio_transporte', $gestion_humana->auxilio_transporte) }}" class="form-control"
                placeholder=" " />
            <label for="auxilio_transporte" class="fw-normal">Auxilio de transporte </label>
            @if ($errors->has('auxilio_transporte'))
                <span id="auxilio_transporte" class="text-danger">{{ $errors->first('auxilio_transporte') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12">
        <div class="form-floating mb-3">
            <input type="text" name="tipo_contrato"
                value="{{ old('tipo_contrato', $gestion_humana->tipo_contrato) }}" class="form-control"
                placeholder=" " />
            <label for="tipo_contrato" class="fw-normal">Tipo de contrato </label>
            @if ($errors->has('tipo_contrato'))
                <span id="tipo_contrato" class="text-danger">{{ $errors->first('tipo_contrato') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="date" name="fecha_ingreso"
                value="{{ old('fecha_ingreso', $gestion_humana->fecha_ingreso) }}" class="form-control"
                placeholder=" " />
            <label for="fecha_ingreso" class="fw-normal">Fecha de ingreso <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_ingreso'))
                <span id="fecha_ingreso" class="text-danger">{{ $errors->first('fecha_ingreso') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="date" name="fecha_finalizacion"
                value="{{ old('fecha_finalizacion', $gestion_humana->fecha_finalizacion) }}" class="form-control"
                placeholder=" " />
            <label for="fecha_finalizacion" class="fw-normal">Fecha de finalización </label>
            @if ($errors->has('fecha_finalizacion'))
                <span id="fecha_finalizacion" class="text-danger">{{ $errors->first('fecha_finalizacion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="eps" value="{{ old('eps', $gestion_humana->eps) }}"
                class="form-control" placeholder=" " />
            <label for="eps" class="fw-normal">EPS </label>
            @if ($errors->has('eps'))
                <span id="eps" class="text-danger">{{ $errors->first('eps') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="afp" value="{{ old('afp', $gestion_humana->afp) }}"
                class="form-control" placeholder=" " />
            <label for="afp" class="fw-normal">AFP </label>
            @if ($errors->has('afp'))
                <span id="afp" class="text-danger">{{ $errors->first('afp') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="cesantias" value="{{ old('cesantias', $gestion_humana->cesantias) }}"
                class="form-control" placeholder=" " />
            <label for="cesantias" class="fw-normal">Cesantias </label>
            @if ($errors->has('cesantias'))
                <span id="cesantias" class="text-danger">{{ $errors->first('cesantias') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="arl" value="{{ old('arl', $gestion_humana->arl) }}"
                class="form-control" placeholder=" " />
            <label for="arl" class="fw-normal">ARL - Riesgo </label>
            @if ($errors->has('arl'))
                <span id="arl" class="text-danger">{{ $errors->first('arl') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="ccf" value="{{ old('ccf', $gestion_humana->ccf) }}"
                class="form-control" placeholder=" " />
            <label for="ccf" class="fw-normal">CCF </label>
            @if ($errors->has('ccf'))
                <span id="ccf" class="text-danger">{{ $errors->first('ccf') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="numero_beneficiarios"
                value="{{ old('numero_beneficiarios', $gestion_humana->numero_beneficiarios) }}" class="form-control"
                placeholder=" " />
            <label for="numero_beneficiarios" class="fw-normal">Número de beneficiarios </label>
            @if ($errors->has('numero_beneficiarios'))
                <span id="numero_beneficiarios"
                    class="text-danger">{{ $errors->first('numero_beneficiarios') }}</span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Documento cédula:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="documento_ced"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="documento_ced" name="documento_ced" class="form-control"
                                accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Certificado EPS:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="certifica_eps"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="certifica_eps" name="certifica_eps" class="form-control"
                                accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Certificado AFP:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="certifica_afp"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="certifica_afp" name="certifica_afp" class="form-control"
                                accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Certificado CCF:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="certifica_ccf"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="certifica_ccf" name="certifica_ccf" class="form-control"
                                accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Certificado ARL:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="certifica_arl"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="certifica_arl" name="certifica_arl" class="form-control"
                                accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="form-floating">
                <div class="row d-flex justify-content-between ">
                    <div class="col-12">
                        <label class="fw-normal">
                            Documento contrato:
                        </label>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <label class="input-group-text bg-transparent" for="document_contrato"><i
                                    class="fas fa-file-upload"></i>
                                &nbsp; </label>
                            <input type="file" id="document_contrato" name="document_contrato"
                                class="form-control" accept="application/pdf" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
