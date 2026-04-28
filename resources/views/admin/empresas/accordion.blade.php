<p>Selecciona un año para configurar las reglas automáticas: </p>
<div class="form-floating mb-4">
    <select id="year" name="anio" class="form-select">
        <option value="">Selecciona un año</option>
    </select>
    <label for="year" class="fw-normal">Año <strong class="text-danger"> *</strong></label>
    <input type="hidden" id="anioexistente" value="{{ old('anio', $clasificacion->anio) }}">
</div>

<script>
    const endYear = 2020; // Año de fin
    const startYear = new Date().getFullYear(); // Año actual
    const inputYear = document.getElementById('year');

    for (let year = startYear; year >= endYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        inputYear.appendChild(option);
    }

    inputYear.addEventListener('change', function() {
        //Mostrar el accordionEmpresa
        document.getElementById('accordionEmpresa').style.display = 'block';
        //ocultar si el valor es vacío
        if (inputYear.value == '') {
            document.getElementById('accordionEmpresa').style.display = 'none';
        }
    });

    var valorExistente = document.getElementById('anioexistente').value;

    if (valorExistente) {
        var datalistOptions = document.getElementById('year');
        var options = datalistOptions.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            if (options[i].value == valorExistente) {
                document.getElementById('year').value = options[i].value;
                break;
            }
        }
    }
</script>

<style>
            .scroll-reglas{
            max-height:450px;
            overflow-y:auto; 
            scroll-behavior:smooth;
            scrollbar-width:thin; 
            scrollbar-color: #999999 #ffffff00;
            overflow-x: hidden;
        }
</style>

<div class="accordion scroll-reglas py-4" id="accordionEmpresa" style="display: none;">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <i class="fas fa-dot-circle" style="color:#0267b2b5;"></i>&nbsp; CLASIFICACIÓN DEL IVA
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
            data-bs-parent="#accordionEmpresa">
            <div class="accordion-body row">

                <input type="hidden" name="uvt" id="uvt" value="">
                <input type="hidden" name="salario_minimo" id="salario_minimo" value="">

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <select id="regimen_simple_tributacion" name="regimen_simple_tributacion" class="form-select">
                            <option value="">Selecciona una opción</option>
                            <option value="SI"
                                {{ old('regimen_simple_tributacion', $clasificacion->regimen_simple_tributacion) == 'SI' ? 'selected' : '' }}>
                                SI</option>
                            <option value="NO"
                                {{ old('regimen_simple_tributacion', $clasificacion->regimen_simple_tributacion) == 'NO' ? 'selected' : '' }}>
                                NO</option>

                        </select>
                        <label for="frecuencias" class="fw-normal">¿Pertenece al régimen simple de tributación? </label>
                        @if ($errors->has('regimen_simple_tributacion'))
                            <span id="regimen_simple_tributacion"
                                class="text-danger">{{ $errors->first('regimen_simple_tributacion') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="fs-5 fw-bold">Ingresos</span>
                </div>

                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="ingresos_gravados" name="ingresos_gravados" min="0"
                            value="{{ old('ingresos_gravados', $clasificacion->ingresos_gravados) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingresos_gravados" class="fw-normal">Ingresos gravados</label>
                        @if ($errors->has('ingresos_gravados'))
                            <span id="ingresos_gravados"
                                class="fw-normal text-danger">{{ $errors->first('ingresos_gravados') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="ingresos_exentos" name="ingresos_exentos" min="0"
                            value="{{ old('ingresos_exentos', $clasificacion->ingresos_exentos) }}" class="form-control"
                            placeholder=" " />
                        <label for="ingresos_exentos" class="fw-normal">Ingresos exentos</label>
                        @if ($errors->has('ingresos_exentos'))
                            <span id="ingresos_exentos"
                                class="fw-normal text-danger">{{ $errors->first('ingresos_exentos') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="ingresos_excluidos" name="ingresos_excluidos" min="0"
                            value="{{ old('ingresos_excluidos', $clasificacion->ingresos_excluidos) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingresos_excluidos" class="fw-normal">Ingresos excluidos</label>
                        @if ($errors->has('ingresos_excluidos'))
                            <span id="ingresos_excluidos"
                                class="fw-normal text-danger">{{ $errors->first('ingresos_excluidos') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="ingresos_no_gravados" name="ingresos_no_gravados" min="0"
                            value="{{ old('ingresos_no_gravados', $clasificacion->ingresos_no_gravados) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingresos_no_gravados" class="fw-normal">Ingresos no gravados</label>
                        @if ($errors->has('ingresos_no_gravados'))
                            <span id="ingresos_no_gravados"
                                class="fw-normal text-danger">{{ $errors->first('ingresos_no_gravados') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="devoluciones" name="devoluciones" min="0"
                            value="{{ old('devoluciones', $clasificacion->devoluciones) }}" class="form-control"
                            placeholder=" " />
                        <label for="devoluciones" class="fw-normal">Devoluciones</label>
                        @if ($errors->has('devoluciones'))
                            <span id="devoluciones"
                                class="fw-normal text-danger">{{ $errors->first('devoluciones') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="number" id="total_ingresos" name="total_ingresos"
                            value="{{ old('total_ingresos', $clasificacion->total_ingresos) }}" class="form-control"
                            placeholder=" " />
                        <label for="total_ingresos" class="fw-normal">Total ingresos</label>
                        @if ($errors->has('total_ingresos'))
                            <span id="total_ingresos"
                                class="fw-normal text-danger">{{ $errors->first('total_ingresos') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <span class="fs-5 fw-bold">Actividades económicas</span>
                </div>

                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="actividad_1" name="actividad_1"
                            value="{{ old('actividad_1', $clasificacion->actividad_1) }}" class="form-control"
                            placeholder=" " />
                        <label for="actividad_1" class="fw-normal">Actividad 1</label>
                        @if ($errors->has('actividad_1'))
                            <span id="actividad_1"
                                class="fw-normal text-danger">{{ $errors->first('actividad_1') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="actividad_2" name="actividad_2"
                            value="{{ old('actividad_2', $clasificacion->actividad_2) }}" class="form-control"
                            placeholder=" " />
                        <label for="actividad_2" class="fw-normal">Actividad 2</label>
                        @if ($errors->has('actividad_2'))
                            <span id="actividad_2"
                                class="fw-normal text-danger">{{ $errors->first('actividad_2') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="actividad_3" name="actividad_3"
                            value="{{ old('actividad_3', $clasificacion->actividad_3) }}" class="form-control"
                            placeholder=" " />
                        <label for="actividad_3" class="fw-normal">Actividad 3</label>
                        @if ($errors->has('actividad_3'))
                            <span id="actividad_3"
                                class="fw-normal text-danger">{{ $errors->first('actividad_1') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-floating mb-3">
                        <input type="text" id="actividad_4" name="actividad_4"
                            value="{{ old('actividad_4', $clasificacion->actividad_4) }}" class="form-control"
                            placeholder=" " />
                        <label for="actividad_4" class="fw-normal">Actividad 4</label>
                        @if ($errors->has('actividad_4'))
                            <span id="actividad_4"
                                class="fw-normal text-danger">{{ $errors->first('actividad_4') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <select id="operaciones_excentas" name="operaciones_excentas" class="form-select">
                            <option value="">Selecciona una opción</option>
                            <option value="SI"
                                {{ old('operaciones_excentas', $clasificacion->operaciones_excentas) == 'SI' ? 'selected' : '' }}>
                                SI</option>
                            <option value="NO"
                                {{ old('operaciones_excentas', $clasificacion->operaciones_excentas) == 'NO' ? 'selected' : '' }}>
                                NO</option>

                        </select>
                        <label for="frecuencias" class="fw-normal">¿Realiza operaciones exentas?</label>
                        @if ($errors->has('operaciones_excentas'))
                            <span id="operaciones_excentas"
                                class="text-danger">{{ $errors->first('operaciones_excentas') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <select id="actividades_exp_imp" name="actividades_exp_imp" class="form-select">
                            <option value="">Selecciona una opción</option>
                            <option value="SI"
                                {{ old('actividades_exp_imp', $clasificacion->actividades_exp_imp) == 'SI' ? 'selected' : '' }}>
                                SI</option>
                            <option value="NO"
                                {{ old('actividades_exp_imp', $clasificacion->actividades_exp_imp) == 'NO' ? 'selected' : '' }}>
                                NO</option>

                        </select>
                        <label for="frecuencias" class="fw-normal">¿Realiza actividades de exportación o
                            importación?</label>
                        @if ($errors->has('actividades_exp_imp'))
                            <span id="actividades_exp_imp"
                                class="text-danger">{{ $errors->first('actividades_exp_imp') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <select id="gran_contribuyente" name="gran_contribuyente" class="form-select">
                            <option value="">Selecciona una opción</option>
                            <option value="SI"
                                {{ old('gran_contribuyente', $clasificacion->gran_contribuyente) == 'SI' ? 'selected' : '' }}>
                                SI</option>
                            <option value="NO"
                                {{ old('gran_contribuyente', $clasificacion->gran_contribuyente) == 'NO' ? 'selected' : '' }}>
                                NO</option>

                        </select>
                        <label for="frecuencias" class="fw-normal">¿Es gran contribuyente?</label>
                        @if ($errors->has('gran_contribuyente'))
                            <span id="gran_contribuyente"
                                class="text-danger">{{ $errors->first('gran_contribuyente') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fas fa-dot-circle" style="color:#0267b2b5;"></i>&nbsp; OBLIGACIÓN DE PRESENTAR LA
                CONCILIACIÓN FISCAL A LA DIAN
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#accordionEmpresa">
            <div class="accordion-body row">
                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <input type="number" id="ingresos_brutos_fiscales_anio_anterior" min="0"
                            name="ingresos_brutos_fiscales_anio_anterior"
                            value="{{ old('ingresos_brutos_fiscales_anio_anterior', $clasificacion->ingresos_brutos_fiscales_anio_anterior) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingresos_brutos_fiscales_anio_anterior" class="fw-normal">Ingresos brutos fiscales
                            del
                            año anterior</label>
                        @if ($errors->has('ingresos_brutos_fiscales_anio_anterior'))
                            <span id="ingresos_brutos_fiscales_anio_anterior"
                                class="fw-normal text-danger">{{ $errors->first('ingresos_brutos_fiscales_anio_anterior') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="formato_conciliacion_fiscal" name="formato_conciliacion_fiscal"
                            readonly
                            value="{{ old('formato_conciliacion_fiscal', $clasificacion->formato_conciliacion_fiscal) }}"
                            class="form-control" placeholder=" " />
                        <label for="frecuencias" class="fw-normal">¿Debe presentar el formato de conciliación
                            fiscal?</label>

                        @if ($errors->has('formato_conciliacion_fiscal'))
                            <span id="formato_conciliacion_fiscal"
                                class="text-danger">{{ $errors->first('formato_conciliacion_fiscal') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <i class="fas fa-dot-circle" style="color:#0267b2b5;"></i>&nbsp; OBLIGACIÓN DE TENER REVISOR FISCAL
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
            data-bs-parent="#accordionEmpresa">
            <div class="accordion-body row">
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="number" id="activos_brutos_diciembre_anio_anterior" min="0"
                            name="activos_brutos_diciembre_anio_anterior"
                            value="{{ old('activos_brutos_diciembre_anio_anterior', $clasificacion->activos_brutos_diciembre_anio_anterior) }}"
                            class="form-control input-md" placeholder=" " />
                        <label for="activos_brutos_diciembre_anio_anterior" class="fw-normal">Activos brutos a diciembre
                            31 del año anterior</label>
                    </div>
                    @if ($errors->has('activos_brutos_diciembre_anio_anterior'))
                        <span id="activos_brutos_diciembre_anio_anterior"
                            class="fw-normal text-danger">{{ $errors->first('activos_brutos_diciembre_anio_anterior') }}</span>
                    @endif
                </div>

                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="number" id="ingreso_brutos_diciembre_anio_anterior" min="0"
                            name="ingreso_brutos_diciembre_anio_anterior"
                            value="{{ old('ingreso_brutos_diciembre_anio_anterior', $clasificacion->ingreso_brutos_diciembre_anio_anterior) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingreso_brutos_diciembre_anio_anterior" class="fw-normal">Ingresos brutos a
                            diciembre
                            31 del año anterior</label>
                    </div>


                    @if ($errors->has('ingreso_brutos_diciembre_anio_anterior'))
                        <span id="ingreso_brutos_diciembre_anio_anterior"
                            class="fw-normal text-danger">{{ $errors->first('ingreso_brutos_diciembre_anio_anterior') }}</span>
                    @endif
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="revisor_fiscal" name="revisor_fiscal" readonly
                            value="{{ old('revisor_fiscal', $clasificacion->revisor_fiscal) }}" class="form-control"
                            placeholder=" " />
                        <label for="frecuencias" class="fw-normal">¿Está obligado a tener revisor fiscal?</label>
                        @if ($errors->has('revisor_fiscal'))
                            <span id="revisor_fiscal"
                                class="text-danger">{{ $errors->first('revisor_fiscal') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <i class="fas fa-dot-circle" style="color:#0267b2b5;"></i>&nbsp; OBLIGACIÓN DE SER FIRMADAS LAS
                DECLARACIONES TRIBUTARIAS POR CONTADOR
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
            data-bs-parent="#accordionEmpresa">
            <div class="accordion-body row">
                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="number" id="patrimonio_brutos_diciembre_anio_anterior" min="0"
                            name="patrimonio_brutos_diciembre_anio_anterior"
                            value="{{ old('patrimonio_brutos_diciembre_anio_anterior', $clasificacion->patrimonio_brutos_diciembre_anio_anterior) }}"
                            class="form-control" placeholder=" " />
                        <label for="patrimonio_brutos_diciembre_anio_anterior" class="fw-normal">Patrimonio bruto a
                            diciembre 31 del año anterior</label>
                    </div>
                    @if ($errors->has('patrimonio_brutos_diciembre_anio_anterior'))
                        <span id="patrimonio_brutos_diciembre_anio_anterior"
                            class="fw-normal text-danger">{{ $errors->first('patrimonio_brutos_diciembre_anio_anterior') }}</span>
                    @endif
                </div>

                <div class="col-12 mb-3">
                    <div class="form-floating">
                        <input type="number" id="ingreso_brutos_tributario_diciembre_anio_anterior" min="0"
                            name="ingreso_brutos_tributario_diciembre_anio_anterior"
                            value="{{ old('ingreso_brutos_tributario_diciembre_anio_anterior', $clasificacion->ingreso_brutos_tributario_diciembre_anio_anterior) }}"
                            class="form-control" placeholder=" " />
                        <label for="ingreso_brutos_tributario_diciembre_anio_anterior" class="fw-normal">Ingresos
                            brutos a
                            diciembre del 31 año anterior</label>
                    </div>
                    @if ($errors->has('ingreso_brutos_tributario_diciembre_anio_anterior'))
                        <span id="ingreso_brutos_tributario_diciembre_anio_anterior"
                            class="fw-normal text-danger">{{ $errors->first('ingreso_brutos_tributario_diciembre_anio_anterior') }}</span>
                    @endif
                </div>

                <div class="col-xl-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="declaracion_tributaria_firma_contador"
                            name="declaracion_tributaria_firma_contador" readonly
                            value="{{ old('declaracion_tributaria_firma_contador', $clasificacion->declaracion_tributaria_firma_contador) }}"
                            class="form-control" placeholder=" " />
                        <label for="frecuencias" class="fw-normal">¿Las declaraciones tributarias deben ser firmadas
                            por
                            el contador?</label>
                        @if ($errors->has('declaracion_tributaria_firma_contador'))
                            <span id="declaracion_tributaria_firma_contador"
                                class="text-danger">{{ $errors->first('declaracion_tributaria_firma_contador') }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
