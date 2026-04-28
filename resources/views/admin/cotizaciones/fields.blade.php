<div class="row">
    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" type="date" placeholder="" name="fecha_envio" id="fecha_envio"
                value="{{ old('fecha_envio', $cotizacion->fecha_envio) }}">
            <label class="fw-normal" for="fecha_envio">
                <i class="far fa-calendar"></i> Fecha de envío de la propuesta <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_envio'))
                <span class="text-danger">{{ $errors->first('fecha_envio') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select" name="prospecto_cliente" id="prospecto_cliente">
                <option value="">Selecciona una opción</option>
                <option value="PROSPECTO"
                    {{ old('prospecto_cliente', $cotizacion->prospecto_cliente) == 'PROSPECTO' ? 'selected' : ' ' }}>
                    PROSPECTO</option>
                <option value="CLIENTE"
                    {{ old('prospecto_cliente', $cotizacion->prospecto_cliente) == 'CLIENTE' ? 'selected' : ' ' }}>
                    CLIENTE</option>
            </select>

            <label class="fw-normal" for="prospecto_cliente">¿Prospecto o cliente? </label>
            @if ($errors->has('prospecto_cliente'))
                <span class="text-danger">{{ $errors->first('prospecto_cliente') }}</span>
            @endif
        </div>
    </div>

    {{-- <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input class="form-control" type="date"
                placeholder="" name="fecha_vigencia" id="fecha_vigencia"
                value="{{ old('fecha_vigencia', $cotizacion->fecha_vigencia) }}">
            <label class="fw-normal" for="fecha_vigencia">
                <i class="far fa-calendar"></i> Fecha de vigencia de la propuesta <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_vigencia'))
                <span class="text-danger">{{ $errors->first('fecha_vigencia') }}</span>
            @endif
        </div>
    </div> --}}

    {{-- <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select" name="cliente_id"
                id="cliente_id">
                <option value="">Selecciona un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $cotizacion->cliente_id) == $cliente->id ? 'selected' : ' ' }}>
                        {{ $cliente->razon_social }}
                    </option>
                @endforeach
            </select>

            <label class="fw-normal" for="cliente_id">Empresa solicitante <b class="text-danger">*</b></label>
            @if ($errors->has('cliente_id'))
                <span class="text-danger">{{ $errors->first('cliente_id') }}</span>
            @endif
        </div>
    </div> --}}

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="cliente" name="cliente" value="{{ old('cliente', $cotizacion->cliente) }}"
                class="form-control" placeholder="" />
            <label for="cliente" class="fw-normal">Empresa solicitante <b class="text-danger">*</b></label>
            @if ($errors->has('cliente'))
                <span id="cliente" class="text-danger">{{ $errors->first('cliente') }}</span>
            @endif
        </div>
    </div>


    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select" name="responsable_id" id="responsable_id">
                <option value="">Selecciona un responsable</option>
                @foreach ($responsables as $responsable)
                    <option value="{{ $responsable->id }}"
                        {{ old('responsable_id', $cotizacion->responsable_id) == $responsable->id ? 'selected' : ' ' }}>
                        {{ $responsable->nombres . ' ' . $responsable->apellidos }}
                    </option>
                @endforeach
            </select>

            <label class="fw-normal" for="responsable_id">Responsable <b class="text-danger">*</b></label>
            @if ($errors->has('responsable_id'))
                <span class="text-danger">{{ $errors->first('responsable_id') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="nombre_contacto" name="nombre_contacto"
                value="{{ old('nombre_contacto', $cotizacion->nombre_contacto) }}" class="form-control"
                placeholder="" />
            <label for="nombre_contacto" class="fw-normal">Nombre de contacto en la empresa <b
                    class="text-danger">*</b></label>
            @if ($errors->has('nombre_contacto'))
                <span id="nombre_contacto" class="text-danger">{{ $errors->first('nombre_contacto') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="telefono_contacto" name="telefono_contacto"
                value="{{ old('telefono_contacto', $cotizacion->telefono_contacto) }}" class="form-control"
                placeholder=" " maxlength="10"/>
            <label for="telefono_contacto" class="fw-normal">Número de contacto en la empresa <b
                    class="text-danger">*</b></label>
            @if ($errors->has('telefono_contacto'))
                <span id="telefono_contacto" class="text-danger">{{ $errors->first('telefono_contacto') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="email" id="correo_contacto" name="correo_contacto"
                value="{{ old('correo_contacto', $cotizacion->correo_contacto) }}" class="form-control"
                placeholder=" " />
            <label for="correo_contacto" class="fw-normal">E-mail de contacto en la empresa</label>
            @if ($errors->has('correo_contacto'))
                <span id="correo_contacto" class="text-danger">{{ $errors->first('correo_contacto') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <select class="form-select" name="linea_negocio" id="linea_negocio">
                <option value="">Selecciona una opción</option>
                <option value="PC"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'PC' ? 'selected' : ' ' }}>PC</option>
                <option value="PT"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'PT' ? 'selected' : ' ' }}>PT</option>
                <option value="TT"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'TT' ? 'selected' : ' ' }}>TT</option>
                <option value="RF"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'RF' ? 'selected' : ' ' }}>RF</option>
                <option value="PF"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'PF' ? 'selected' : ' ' }}>PF</option>
                <option value="PE"
                    {{ old('linea_negocio', $cotizacion->linea_negocio) == 'PE' ? 'selected' : ' ' }}>PE</option>
            </select>
            <label class="fw-normal" for="linea_negocio">Línea de negocio </label>
            @if ($errors->has('linea_negocio'))
                <span class="text-danger">{{ $errors->first('linea_negocio') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="text" id="servicio_cotizado" name="servicio_cotizado"
                value="{{ old('servicio_cotizado', $cotizacion->servicio_cotizado) }}" class="form-control"
                placeholder=" " />
            <label for="servicio_cotizado" class="fw-normal">Servicio cotizado</label>
            @if ($errors->has('servicio_cotizado'))
                <span id="servicio_cotizado" class="text-danger">{{ $errors->first('servicio_cotizado') }}</span>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="form-floating mb-3">
            <input type="number" id="precio_venta" name="precio_venta"
                value="{{ old('precio_venta', $cotizacion->precio_venta) }}" class="form-control" placeholder=" " />
            <label for="precio_venta" class="fw-normal">Precio de venta</label>
            @if ($errors->has('precio_venta'))
                <span id="precio_venta" class="text-danger">{{ $errors->first('precio_venta') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12 mb-3">
        <label for="observaciones" class="fw-normal">Observaciones: </label>
        <textarea id="observaciones" name="observaciones"> {!! old('observaciones',$cotizacion->observaciones) !!} </textarea>
    </div>

    <div class="col-xl-12">
        <div class="form-floating mb-2">
            <div class="row d-flex justify-content-between ">
                <div class="col-10">
                    <label>
                        Cotización adjunta
                    </label>
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <label class="input-group-text bg-transparent" for="documento_adjunto"><i
                                class="fas fa-file-upload"></i> &nbsp; </label>
                        <input type="file" id="documento_adjunto" name="documento_adjunto" class="form-control"
                            accept=".pdf, .docx, .doc, .docm, .dotm, .dotx" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-2">
        <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(215 215 215);">
    </div>

    <div class="col-12 col-xl-12">
        <div class="form-floating mb-3">
            <input class="form-control" type="date" placeholder="" name="fecha_primer_seguimiento"
                id="fecha_primer_seguimiento"
                value="{{ old('fecha_primer_seguimiento', $cotizacion->fecha_primer_seguimiento ? $cotizacion->fecha_primer_seguimiento : \Carbon\Carbon::now()->addDays(2)->format('Y-m-d')) }}">
            <label class="fw-normal" for="fecha_primer_seguimiento">
                <i class="far fa-calendar"></i> Fecha primer seguimiento <b class="text-danger">*</b></label>
            @if ($errors->has('fecha_primer_seguimiento'))
                <span class="text-danger">{{ $errors->first('fecha_primer_seguimiento') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12 mb-3">
        <label for="observacion_primer_seguimiento" class="fw-normal">Observación primer seguimiento: <b
                class="text-danger">*</b></label>
        <textarea id="observacion_primer_seguimiento" name="observacion_primer_seguimiento"> {!! old('observacion_primer_seguimiento',$cotizacion->observacion_primer_seguimiento) !!} </textarea>

        @if ($errors->has('observacion_primer_seguimiento'))
            <span class="text-danger">{{ $errors->first('observacion_primer_seguimiento') }}</span>
        @endif
    </div>
</div>


