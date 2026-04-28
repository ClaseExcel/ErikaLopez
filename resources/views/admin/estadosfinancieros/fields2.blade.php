<div class="my-2">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select fw-normal {{ $errors->has('compania') ? 'is-invalid' : '' }}" name="compania"
                    id="compania2">
                    <option value="">Seleccionar una Opción</option>
                    @foreach ($companias as $id => $compania)
                        <option value="{{ $id }}" {{ old('compania') == $id ? 'selected' : '' }}>
                            {{ $compania }}</option>
                    @endforeach
                </select>
                <label class="fw-normal" for="compania">Compañia <b class="text-danger">*</b></label>
                @if ($errors->has('compania'))
                    <span class="text-danger">{{ $errors->first('compania') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado"
                    id="estado">
                    <option value="">Seleccionar una Opción</option>
                    {{-- <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Balance General</option> --}}
                    <option value="3" {{ old('estado') == 3 ? 'selected' : '' }}>Estado de Resultados acumulado</option>
                </select>
                <label class="fw-normal" for="estado">Estado Financiero <b class="text-danger">*</b></label>
                @if ($errors->has('estado'))
                    <span class="text-danger">{{ $errors->first('estado') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
    {{-- <div class="row" id="tipoinformeDiv">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('tipoinforme') ? 'is-invalid' : '' }}" name="tipoinforme" id="tipoinforme">
                    <option value="">Seleccionar una Opción</option>
                    <option value="1" {{ old('tipoinforme')==1 ? 'selected' : '' }}>Balance</option>
                    <option value="2" {{ old('tipoinforme')==2 ? 'selected' : '' }}>Movimientos</option>
                </select>
                <label class="fw-normal" for="tipoinforme">Tipo Informe <b class="text-danger">*</b></label>
                @if ($errors->has('tipoinforme'))
                    <span class="text-danger">{{ $errors->first('tipoinforme') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}" name="centro_costo"
                    id="centro_costo2" disabled>
                    <option value="">Selecciona un centro de costo</option>
                </select>
                <label class="fw-normal" for="centro_costo">Centro de costos </label>
                @if ($errors->has('centro_costo'))
                    <span class="text-danger">{{ $errors->first('centro_costo') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <input class="form-control {{ $errors->has('fechareporte') ? 'is-invalid' : '' }} " type="date"
                    placeholder="" name="fechareporte" id="fechareporte" value="{{ old('fechareporte', '') }}">
                <label class="fw-normal" for="fechareporte">Fecha Reporte <b class="text-danger">*</b></label>
                @if ($errors->has('fechareporte'))
                    <span class="text-danger">{{ $errors->first('fechareporte') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
