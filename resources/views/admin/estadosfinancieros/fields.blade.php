<div class="my-2">
    <div class="row">
        <div class="form-floating mb-3">
            <input class="form-control" list="datalistOptionsCompanias"
                placeholder="Escribe para buscar..." name="compania_id" id="compania_id"
                autocomplete="off" oninput="updateCompania(this)">
            <datalist id="datalistOptionsCompanias">
                @foreach ($companias as $id => $compania)
                    <option value="{{ $compania }}" data-id="{{ $id }}"></option>
                @endforeach
            </datalist>
            <label class="fw-normal">&nbsp;Compañía <b class="text-danger">*</b></label>
            <input type="hidden" name="compania" id="compania">
        </div>
        
        <script>
            function updateCompania(input) {
                let datalist = document.getElementById("datalistOptionsCompanias").options;
                let hiddenInput = document.getElementById("compania");
        
                hiddenInput.value = ""; // Limpiar ID si no se encuentra
                for (let option of datalist) {
                    if (option.value === input.value) {
                        hiddenInput.value = option.getAttribute("data-id");
                        break;
                    }
                }
            }
        </script>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('estado') ? 'is-invalid' : '' }}" name="estado"
                    id="estado">
                    <option value="">Seleccionar una Opción</option>
                    <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Balance Prueba</option>
                    <option value="3" {{ old('estado') == 3 ? 'selected' : '' }}>Estado de Resultados acumulado</option>
                    <option value="4" {{ old('estado') == 4 ? 'selected' : '' }}>Estado de Resultados Comparativo</option>
                    <option value="8" {{ old('estado') == 8 ? 'selected' : '' }}>Estado de Ingresos y Gastos</option>
                    <option value="5" {{ old('estado') == 5 ? 'selected' : '' }}>Estado de Situacion Financiera acumulado</option>
                    <option value="6" {{ old('estado') == 6 ? 'selected' : '' }}>Estado de cambio de patrimonio</option>
                    <option value="7" {{ old('estado') == 7 ? 'selected' : '' }}>Estado de flujos de efectivo</option>
                </select>
                <label class="fw-normal" for="estado">Estado Financiero <b class="text-danger">*</b></label>
                @if ($errors->has('estado'))
                    <span class="text-danger">{{ $errors->first('estado') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
  
    <div class="row" id="periodoSwitch" style="display: none;">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('tipoinforme') ? 'is-invalid' : '' }}" name="tipoinforme" id="tipoinforme">
                    <option value="" selected>Seleccionar una Opción</option>
                    <option value="1" {{ old('tipoinforme')==1 ? 'selected' : '' }}>Mensual</option>
                    <option value="3" {{ old('tipoinforme')==3 ? 'selected' : '' }}>Anual</option>
                    <option value="2" {{ old('tipoinforme')==2 ? 'selected' : '' }}>Acumulado</option>
                </select>
                <label class="fw-normal" for="tipoinforme">Tipo Informe <b class="text-danger">*</b></label>
                @if ($errors->has('tipoinforme'))
                    <span class="text-danger">{{ $errors->first('tipoinforme') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
    <div class="row" id="periodoSwitch2" style="display: none;">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('tipoinforme2') ? 'is-invalid' : '' }}" name="tipoinforme2" id="tipoinforme2">
                    <option value="" selected>Seleccionar una Opción</option>
                    <option value="1" {{ old('tipoinforme2')==1 ? 'selected' : '' }}>Mensual</option>
                    <option value="2" {{ old('tipoinforme2')==2 ? 'selected' : '' }}>Acumulado</option>
                </select>
                <label class="fw-normal" for="tipoinforme2">Tipo Informe <b class="text-danger">*</b></label>
                @if ($errors->has('tipoinforme2'))
                    <span class="text-danger">{{ $errors->first('tipoinforme2') }}</span>
                @endif
                <br>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-floating">
                <select class="form-select {{ $errors->has('centro_costo') ? 'is-invalid' : '' }}" name="centro_costo"
                    id="centro_costo" disabled>
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
                <input class="form-control {{ $errors->has('fechareporte') ? 'is-invalid' : '' }}" 
                    type="date" name="fechareporte" id="fechareporte"
                    min="2000-01-01" max="2099-12-31"
                    value="{{ old('fechareporte', '') }}">
                <label class="fw-normal" for="fechareporte">Fecha Reporte <b class="text-danger">*</b></label>
                @if ($errors->has('fechareporte'))
                    <span class="text-danger">{{ $errors->first('fechareporte') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
