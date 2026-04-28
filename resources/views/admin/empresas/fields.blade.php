<div class="col-xl-12">
    <div class="form-floating mb-3">
        <input type="number" name="NIT" value="{{ old('NIT', $empresa->NIT) }}" class="form-control" placeholder=" " />
        <label for="NIT" class="fw-normal">NIT <b class="text-danger">*</b></label>
        <span id="NIT" class="text-danger text-sm"></span>
    </div>
</div>
<div class="col-xl-12">
    <div class="form-floating mb-3">
        <input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}" class="form-control" placeholder=" " />
        <label for="razon_social" class="fw-normal">Razón social <b class="text-danger">*</b></label>
        <span id="razon_social" class="text-danger text-sm"></span>
    </div>
</div>

<div class="col-12">
    @include('admin.empresas.campos-opcionales')
</div>





<div class="row">
    <div class="col-xl-12 mt-3">
        <div class="form-floating mb-3">
            <input type="email" name="correo_electronico" value="{{ old('correo_electronico', $empresa->correo_electronico) }}" class="form-control"
                placeholder=" " />
            <label for="correo_electronico" class="fw-normal">Correo electrónico <b class="text-danger">*</b></label>
            <span id="correo_electronico" class="text-danger text-sm"></span>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="correos_secundarios" name="correos_secundarios"
                value="{{ old('correos_secundarios', $empresa->correos_secundarios) }}" class="form-control" placeholder=" "
                title="Ingrese los correos electrónicos separados por comas" />
            <label for="correos_secundarios" class="fw-normal">Correos electrónicos secundarios</label>
    
            <span id="correos_secundarios" class="text-danger text-sm"></span>
        </div>
    </div>    
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="numero_contacto" value="{{ old('numero_contacto', $empresa->numero_contacto) }}" class="form-control"
                placeholder=" " />
            <label for="numero_contacto" class="fw-normal">Número de contacto de la empresa <b class="text-danger">*</b></label>
            <span id="numero_contacto" class="text-danger text-sm"></span>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="telefonos_secundarios" name="telefonos_secundarios"
                value="{{ old('telefonos_secundarios', $empresa->telefonos_secundarios) }}" class="form-control" placeholder=" "
                title="Ingrese los teléfonos separados por comas" />
            <label for="telefonos_secundarios" class="fw-normal">Teléfonos secundarios</label>
    
            @if ($errors->has('telefonos_secundarios'))
                <span id="telefonos_secundarios" class="text-danger">{{ $errors->first('telefonos_secundarios') }}</span>
            @endif
        </div>
    </div>    
    
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select name="notifica_calendario" class="form-select">
                <option value="0" {{ old('notifica_calendario', $empresa->notifica_calendario) == '0' ? 'selected' : '' }}>Selecciona
                    una opción
                </option>
                <option value="1"
                    {{ old('notifica_calendario', $empresa->notifica_calendario) == '1' ? 'selected' : '' }}>SÍ
                </option>
                <option value="2"
                    {{ old('notifica_calendario', $empresa->notifica_calendario) == '2' ? 'selected' : '' }}>NO
                </option>
            </select>
            <label for="notifica_calendario" class="fw-normal">Notificar calendario vencimientos</label>
            <span id="notifica_calendario" class="text-danger text-sm"></span>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" name="nombre_contacto" value="{{ old('nombre_contacto', $empresa->nombre_contacto) }}" class="form-control"
                placeholder=" " />
            <label for="nombre_contacto" class="fw-normal">Nombre del contacto <b class="text-danger">*</b></label>
            <span id="nombre_contacto" class="text-danger text-sm"></span>
        </div>
    </div>
    
    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" name="telefono_contacto" value="{{ old('telefono_contacto', $empresa->telefono_contacto) }}" class="form-control"
                placeholder=" " />
            <label for="telefono_contacto" class="fw-normal">Número del contacto <b class="text-danger">*</b></label>
            <span id="telefono_contacto" class="text-danger text-sm"></span>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-12">
        <div class="form-floating mb-3">
            <input type="text" name="direccion_fisica" value="{{ old('direccion_fisica', $empresa->direccion_fisica) }}" class="form-control"
                placeholder=" " />
            <label for="direccion_fisica" class="fw-normal">Dirección física <b class="text-danger">*</b></label>
            <span id="direccion_fisica" class="text-danger text-sm"></span>
        </div>
    </div>
    
    
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select id="frecuencias" name="frecuencia_id" class="form-select">
                <option value="" {{ old('tipocliente') == '' ? 'selected' : '' }}>Selecciona una opción
                    @foreach ($frecuencias as $id => $nombre)
                <option value="{{ $id }}" {{ old('frecuencia_id', $empresa->frecuencia_id) == $id ? 'selected' : '' }}>
                    {{ $nombre }}</option>
                @endforeach
            </select>
            <label for="frecuencias" class="fw-normal">Frecuencia <b class="text-danger">*</b></label>
            <span id="frecuencia_id" class="text-danger text-sm"></span>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select name="tipocliente" class="form-select">
                <option value="" {{ old('tipocliente') == '' ? 'selected' : '' }}>Selecciona un tipo cliente
                </option>
                <option value="persona" {{ old('tipocliente', $empresa->tipocliente) == 'persona' ? 'selected' : '' }}>
                    Persona</option>
                <option value="empresa" {{ old('tipocliente', $empresa->tipocliente) == 'empresa' ? 'selected' : '' }}>
                    Empresa</option>
    
            </select>
            <label for="frecuencias" class="fw-normal">Tipo cliente <b class="text-danger">*</b></label>
            <span id="tipocliente" class="text-danger text-sm"></span>
        </div>
    </div>

</div>

<div class="row">
    
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select id="gruponiif" name="gruponiif" class="form-select">
                <option value="" {{ old('gruponiif', $empresa->gruponiif) == '' ? 'selected' : '' }}>Selecciona un Grupo NIIF</option>
                <option value="1" {{ old('gruponiif', $empresa->gruponiif) == '1' ? 'selected' : '' }}>NIIF 1</option>
                <option value="2" {{ old('gruponiif', $empresa->gruponiif) == '2' ? 'selected' : '' }}>NIIF 2</option>
                <option value="3" {{ old('gruponiif', $empresa->gruponiif) == '3' ? 'selected' : '' }}>NIIF 3</option>
            </select>
            <label for="frecuencias" class="fw-normal">Grupo NIIF <b class="text-danger">*</b></label>
            @if ($errors->has('gruponiif'))
                <span id="gruponiif" class="text-danger">{{ $errors->first('gruponiif') }}</span>
            @endif
    
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select {{ $errors->has('tipo') ? 'is-invalid' : '' }}" name="tipo" id="tipo" required>
                <option value="">Seleccionar</option>
                <option value="PYME" {{ old('tipo', $empresa->tipo) == 'PYME' ? 'selected' : '' }}>SIIGO PYME</option>
                <option value="NUBE" {{ old('tipo', $empresa->tipo) == 'NUBE' ? 'selected' : '' }}>SIIGO NUBE</option>
                <option value="CONTAPYME" {{ old('tipo', $empresa->tipo) == 'CONTAPYME' ? 'selected' : '' }}>CONTAPYME</option>
                <option value="LOGGRO" {{ old('tipo', $empresa->tipo) == 'LOGGRO' ? 'selected' : '' }}>LOGGRO</option>
                <option value="BEGRANDA" {{ old('tipo', $empresa->tipo) == 'BEGRANDA' ? 'selected' : '' }}>BEGRANDA</option>
                <option value="WIMAX" {{ old('tipo', $empresa->tipo) == 'WIMAX' ? 'selected' : '' }}>WIMAX</option>
                <option value="GENERICO" {{ old('tipo', $empresa->tipo) == 'GENERICO' ? 'selected' : '' }}>GENERICO</option>
                <option value="ILIMITADA" {{ old('tipo', $empresa->tipo) == 'ILIMITADA' ? 'selected' : '' }}>ILIMITADA</option>
                <option value="WORLD" {{ old('tipo', $empresa->tipo) == 'WORLD' ? 'selected' : '' }}>WORLD OFFICE</option>
                <option value="SAG" {{ old('tipo', $empresa->tipo) == 'SAG' ? 'selected' : '' }}>SAG</option>
            </select>
            <label class="fw-normal" for="tipo">Programa contable <b class="text-danger">*</b></label>
            @if ($errors->has('tipo'))
                <span class="text-danger">{{ $errors->first('tipo') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12">
        <div class="form-floating mb-3">
            <select class="form-select {{ $errors->has('contador') ? 'is-invalid' : '' }}" name="contador" id="contador" required>
                <option value="">Seleccionar</option>
                @foreach ($empleados as $id => $nombre)
                    <option value={{ $id }} {{ $empresa->contador != null && $empresa->contador == $id ? 'selected' : '' }}>
                        {{ $id . ' - ' . $nombre }}
                    </option>
                @endforeach
            </select>
            <label class="fw-normal" for="contador">Contador <b class="text-danger">*</b></label>
            @if ($errors->has('contador'))
                <span class="text-danger">{{ $errors->first('contador') }}</span>
            @endif
        </div>
    </div>
    <div class="col-xl-12" id="contenedor_compania">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsCompanias"
                placeholder="Escribe para buscar..." name="compania_id" id="compania_id"
                autocomplete="off" oninput="updateCompania(this)">

            <datalist id="datalistOptionsCompanias">
                <option value="Sin compañía" data-id=""></option>
                @foreach ($companias as $id => $compania)
                    <option value="{{ $compania }}" data-id="{{ $id }}"></option>
                @endforeach
            </datalist>

            <label class="fw-normal">&nbsp;Compañía cruce información <b class="text-danger">*</b></label>

            <input type="hidden" name="empresaasociada" id="compania">
            <input type="hidden" name="empresaasociadaedit" id="companiaedit" value="{{$empresa->empresaasociada}}">
        </div>
    </div>
    <div class="col-xl-6 d-none" id="contenedor_operador">
        <div class="form-floating mb-3">

            <select class="form-select" name="operador" id="operador_cruce">
                <option value="">Seleccionar operador</option>
                <option value="+" {{ $empresa->operador != null && ($empresa->operador == '+') ? 'selected' : '' }}>+</option>
                <option value="-" {{ $empresa->operador != null && ($empresa->operador == '-') ? 'selected' : '' }}>-</option>
            </select>

            <label class="fw-normal">Operador para cruce</label>

        </div>
    </div>
    <script>

        function updateCompania(input) {

            let datalist = document.getElementById("datalistOptionsCompanias").options;
            let hiddenInput = document.getElementById("compania");
            let operadorContainer = document.getElementById("contenedor_operador");
            let companiaContainer = document.getElementById("contenedor_compania");
            let operadorSelect = document.getElementById("operador_cruce");

            hiddenInput.value = "";

            operadorContainer.classList.add("d-none");

            // volver a 12 columnas
            companiaContainer.classList.remove("col-xl-6");
            companiaContainer.classList.add("col-xl-12");

            for (let option of datalist) {

                if (option.value === input.value) {

                    let id = option.getAttribute("data-id");

                    if (!id) {
                        hiddenInput.value = "";
                        operadorSelect.value = "";
                        return;
                    }

                    hiddenInput.value = id;

                    operadorContainer.classList.remove("d-none");

                    companiaContainer.classList.remove("col-xl-12");
                    companiaContainer.classList.add("col-xl-6");

                    break;
                }
            }
        }

        // 🔥 Para modo editar
        document.addEventListener("DOMContentLoaded", function() {
            let input = document.getElementById("compania_id");
            if (input && input.value) {
                updateCompania(input);
            }
        });

    </script>

    <div class="col-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-10">
                    <label class="fw-normal">
                        RUT: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <label class="input-group-text bg-transparent" for="rut"><i class="fas fa-file-upload"></i>
                            &nbsp;
                        </label>
                        <input type="file" id="idrut" name="rut" class="form-control" accept="application/pdf" />
                        <span id="rut" class="text-danger text-sm"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-12">
                    <label class="fw-normal">
                        FCC - NIT:
                    </label>
                </div>
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text bg-transparent" for="contrato"><i class="fas fa-file-upload"></i>
                            &nbsp; </label>
                        <input type="file" id="idcontrato" name="contrato" class="form-control" accept="application/pdf" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-12">
        <div class="row">
            <div class="col-12">
                <label class="fw-normal">
                    Seleccionar un Empleado o varios: <b class="text-danger">*</b>
                </label>
            </div>
            <div class="col">
                <div class="btn-group mb-2">
                    <button id="selectAllButtonempleados" type="button" class="btn btn-outline-info btn-xs  btn-radius px-4 me-2"
                        style="border-radius: 5px">Seleccionar
                        Todo</button>
                    <button id="deselectAllButtonempleados" type="button" class="btn btn-outline-info btn-xs btn-radius px-4 "
                        style="border-radius: 5px">Deseleccionar
                        Todo</button>
                </div>
            </div>
        </div>
        <select name="empleados[]" id="empleados" class="form-select" multiple>
            @foreach ($empleados as $id => $nombre)
                <option value={{ $id }}
                    {{ $empresa->empleados != null && in_array($id, json_decode($empresa->empleados)) ? 'selected' : '' }}>
                    {{ $id . ' - ' . $nombre }}
                </option>
            @endforeach
        </select>
        <span id="empleado" class="text-danger text-sm"></span>
    </div>
    
    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-12">
                    <label class="fw-normal">
                        Seleccionar una obligación tributaria DIAN o varias: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col">
                    <div class="btn-group mb-2">
                        <button id="selectAllButton" type="button" class="btn btn-outline-info btn-xs  btn-radius px-4 me-2"
                            style="border-radius: 5px">Seleccionar Todo</button>
                        <button id="deselectAllButton" type="button" class="btn btn-outline-info btn-xs btn-radius px-4 "
                            style="border-radius: 5px">Deseleccionar
                            Todo</button>
                    </div>
                </div>
    
            </div>
            <select name="obligaciones[]" id="obligaciones" class="form-select" multiple>
                @foreach ($obligaciones as $obligacion)
                    <option value="{{ $obligacion->codigo }}"
                        {{ $empresa->obligaciones != null && in_array($obligacion->codigo, json_decode($empresa->obligaciones)) ? 'selected' : '' }}>
                        {{ $obligacion->codigo . ' - ' . $obligacion->nombre }}
                    </option>
                @endforeach
            </select>
            <span id="obligaciones_error" class="text-danger text-sm"></span>
        </div>
    </div>
    
    <div class="col-xl-12">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsCamara" placeholder="Escribe Para Buscar..." name="camaracomercio_id"
                id="camaracomercio" autocomplete="off">
            <datalist id="datalistOptionsCamara">
                @foreach ($camarascomercio as $camaracomercio)
                    <option value="{{ $camaracomercio->id . ' - ' . $camaracomercio->nombre }}" data-id="{{ $camaracomercio->id }}"></option>
                @endforeach
            </datalist>
            <input type="hidden" id="camaraexistente" value="{{ $empresa->camaracomercio_id }}">
            <label class="fw-normal">Cámara de comercio principal <b class="text-danger">*</b></label>
            <span id="camaracomercio_id" class="text-danger text-sm"></span>
        </div>
    </div>
    
    <script>
        var valorExistente = document.getElementById('camaraexistente').value;
    
        if (valorExistente) {
            var datalistOptions = document.getElementById('datalistOptionsCamara');
            var options = datalistOptions.getElementsByTagName('option');
    
            for (var i = 0; i < options.length; i++) {
                var valorDataId = options[i].getAttribute('data-id');
                if (valorDataId == valorExistente) {
                    document.getElementById('camaracomercio').value = options[i].value;
                    break;
                }
            }
        }
    </script>
    
    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-12">
                    <label class="fw-normal">
                        Seleccionar una obligación Municipal o varias: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col">
                    <div class="btn-group mb-2">
                        <button id="selectAllButton3" type="button" class="btn btn-outline-info btn-xs  btn-radius px-4 me-2"
                            style="border-radius: 5px">Seleccionar Todo</button>
                        <button id="deselectAllButton3" type="button" class="btn btn-outline-info btn-xs btn-radius px-4 "
                            style="border-radius: 5px">Deseleccionar Todo</button>
                    </div>
                </div>
    
            </div>
            <select id="obligacionMunicipal" multiple="multiple"
                class="form-select {{ $errors->has('codigo_obligacionmunicipal') ? 'is-invalid' : '' }} custom-select-border w-100 py-4"
                name="codigo_obligacionmunicipal[]" required data-dropup="true" data-container="body">
                @foreach ($obligacionesmunicipalescodigo as $obligacion)
                    <option value="{{ $obligacion->codigo }}"
                        {{ $empresa->codigo_obligacionmunicipal != null && $empresa->codigo_obligacionmunicipal != 'null' && in_array($obligacion->codigo, json_decode($empresa->codigo_obligacionmunicipal)) ? 'selected' : '' }}>
                        {{ $obligacion->codigo . ' - ' . $obligacion->nombre }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('codigo_obligacionmunicipal'))
                <span class="text-danger text-sm ">{{ $errors->first('codigo_obligacionmunicipal') }}</span>
            @endif
            <span id="codigo_obligacionmunicipal" class="text-danger text-sm"></span>
        </div>
    </div>
    
    <div class="col-12 col-md-12">
        <div class="form-floating mb-3">
            <div class="row d-flex justify-content-between ">
                <div class="col-12">
                    <label class="fw-normal">
                        Seleccionar un detalle de otras entidades o varias: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col">
                    <div class="btn-group mb-2">
                        <button id="selectAllButton4" type="button" class="btn btn-outline-info btn-xs  btn-radius px-4 me-2"
                            style="border-radius: 5px">Seleccionar Todo</button>
                        <button id="deselectAllButton4" type="button" class="btn btn-outline-info btn-xs btn-radius px-4 "
                            style="border-radius: 5px">Deseleccionar Todo</button>
                    </div>
                </div>
    
            </div>
            <select id="OtrasEntidades" multiple="multiple"
                class="form-select {{ $errors->has('otras_entidades') ? 'is-invalid' : '' }} custom-select-border w-100 py-4" name="otras_entidades[]"
                required data-dropup="true" data-container="body">
                @foreach ($otrasentidades as $obligacion)
                    <option value="{{ $obligacion->codigo }}"
                        {{ $empresa->otras_entidades != null && in_array($obligacion->codigo, json_decode($empresa->otras_entidades)) ? 'selected' : '' }}>
                        {{ $obligacion->codigo . ' - ' . $obligacion->nombre }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('otras_entidades'))
                <span class="text-danger text-sm ">{{ $errors->first('otras_entidades') }}</span>
            @endif
            <span id="otras_entidades" class="text-danger text-sm"></span>
        </div>
    </div>
</div>
