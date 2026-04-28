<div class="row">
    <div class="col-12 col-xl-12">
        <div class="form-floating mb-3">
            <select class="form-select" name="estado_cotizacion" id="estado_cotizacion">
                <option value="">Selecciona un estado</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" {{ old('estado_cotizacion', '') == $estado ? 'selected' : '' }}>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>

            <label class="fw-normal" for="estado_cotizacion">Estado de la cotizacion <b class="text-danger">*</b></label>
            @if ($errors->has('estado_cotizacion'))
                <span class="text-danger">{{ $errors->first('estado_cotizacion') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12 mb-3">
        <label for="observaciones" class="fw-normal">Observaciones: <b class="text-danger">*</b></label>
        <textarea id="observaciones" name="observaciones"> {!! old('observaciones', '') !!} </textarea>
        @if ($errors->has('observaciones'))
            <span class="text-danger">{{ $errors->first('observaciones') }}</span>
        @endif
    </div>


    <div class="col-xl-12">
        <div class="form-floating mb-2">
            <div class="row d-flex justify-content-between ">
                <div class="col-10">
                    <label>
                       Documento
                    </label>
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <label class="input-group-text bg-transparent" for="documento"><i
                                class="fas fa-file-upload"></i> &nbsp; </label>
                        <input type="file" id="documento" name="documento" class="form-control"/>
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
            <input class="form-control" type="date" placeholder="" name="fecha_proximo_seguimiento"
                id="fecha_proximo_seguimiento" value="{{ old('fecha_proximo_seguimiento') }}">
            <label class="fw-normal" for="fecha_proximo_seguimiento">
                <i class="far fa-calendar"></i> Fecha del próximo seguimiento </label>
            @if ($errors->has('fecha_proximo_seguimiento'))
                <span class="text-danger">{{ $errors->first('fecha_proximo_seguimiento') }}</span>
            @endif
        </div>
    </div>

    <div class="col-xl-12 mb-3">
        <label for="observacion_proximo_seguimiento" class="fw-normal">Observación del próximo seguimiento: </label>
        <textarea id="observacion_proximo_seguimiento" name="observacion_proximo_seguimiento"> {!! old('observacion_proximo_seguimiento', '') !!} </textarea>
        @if ($errors->has('observacion_proximo_seguimiento'))
            <span class="text-danger">{{ $errors->first('observacion_proximo_seguimiento') }}</span>
        @endif
    </div>
</div>
