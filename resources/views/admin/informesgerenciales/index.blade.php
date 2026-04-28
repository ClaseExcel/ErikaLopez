@extends('layouts.admin')
@section('title', 'Informes Gerenciales')
@section('library')
    @include('partials.librerias-charts')
    @include('admin.informesgerenciales.style')
@endsection
@section('content')

    {{-- ::::::::::: FORMULARIO :::::::::::::: --}}
    @include('admin.informesgerenciales.form')

    {{-- ::::::::::: INFORME :::::::::::::: --}}
    <div class="row" id="informe">
        <div class="col-12">
            <div id="titulo"></div>
        </div>
        <div class="col-12 mb-4 container-informe-tabs" style="display:none ;">
            @include('admin.informesgerenciales.tabs'){{-- Pestañas del informe --}}
            @include('admin.informesgerenciales.tabs-content'){{-- contenido de las pestañas --}}
        </div>

        {{-- graficos --}}
        <div class="col-12" id="graficos-container">
            @include('admin.informesgerenciales.graficos')
        </div>

    </div>


    {{-- tabla --}}
    <div class="row">
        <div class="col-12 pb-3" style="display:none ;#ver tabla">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-informe" class="table table-sm table-hover " style="width:100% ;">
                        <thead>
                            <tr>
                                <th>valor</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- pdf --}}
    <div id="pdf-div"></div>

    {{-- modal de carga --}}
    <div id="overlay">
        <div class="modal fade show" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content bg-white border-0">
                    <div class="modal-body text-center pb-4 pt-5 px-0">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-center mt-2">
                            <p>&nbsp;&nbsp;Cargando datos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    {{-- validaciones formulario --}}
    <script src="{{ asset('/js/informes-gerenciales/val-script.js') }}"></script>
    {{-- importar scripts para la generacion del pdf y graficos --}}
    <script src="{{ asset('/js/informes-gerenciales/script.js') }}"></script>
    <script src="{{ asset('/js/informes-gerenciales/pdf-script.js') }}"></script>
    <script src="{{ asset('/js/informes-gerenciales/chart-setup.js') }}" defer></script>
    <script src="{{ asset('/js/informes-gerenciales/graficos.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let url = "{{ env('APP_URL') }}";
            let formulario = document.getElementById('formulario-informe');
            let containerInformeTabs = document.querySelector('.container-informe-tabs');
            let containerGrafico = document.querySelectorAll('.container-grafico');

            let table = new DataTable('#datatable-informe', {
                ajax: "{{ route('admin.informesgerenciales.index') }}",
                dataType: 'json',
                //enviar
                type: "POST",
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: -1,
                lengthMenu: [
                    [-1],
                    ["Todos"]
                ],
                columns: [{
                    data: 'total_mes',
                    orderable: false,
                }, ],
                drawCallback: function() {

                    //al presionar btn-generar-informe
                    $("#btn-generar-informe").on('click', function() {
                        //habilitar el overlay
                        $('#overlay').show();
                        //obtener los valores del formulario
                        let empresa_id = document.getElementById('empresa_id').value;
                        let fechaInicial = document.getElementById('fecharInicial').value;
                        let fechaFinal = document.getElementById('fecharFinal').value;

                        //obtener los datos seleccionados en el checklist
                        let datosSeleccionados = [];
                        document.querySelectorAll('input[name="datos[]"]:checked').forEach(function(checkbox) {
                            datosSeleccionados.push(checkbox.value);
                        });

                        //validar los datos seleccionados
                            if (datosSeleccionados.length === 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: '¡Atención!',
                                    text: 'Por favor, seleccione al menos un gráfico para generar el informe.',
                                });
                                $('#overlay').hide();
                                return;
                            }


                        table.ajax.url("{{ route('admin.informesgerenciales.generar-informe') }}" +
                            '?compania=' + empresa_id +
                            '&fecha_inicio=' + fechaInicial +
                            '&fecha_fin=' + fechaFinal +
                            '&datos=' + datosSeleccionados.join(',')
                        ).load();
                        return;
                    });
                    //deshabilitar el overlay
                    $('#overlay').hide();
                    // DIVS DEL INFORME
                    let titulo = document.getElementById('titulo');
                    let desempenoFinancieroDiv = document.getElementById('desempeno-financiero-div');
                    let indicadoresFinancierosDiv = document.getElementById('indicadores-financieros-div');
                    let impuestoIvaDiv = document.getElementById('impuesto-iva-div');
                    let ingresosDiv = document.getElementById('ingresos');
                    let costoVentasDiv = document.getElementById('costo-ventas');
                    let utilidadDiv = document.getElementById('utilidad');
                    let gastosAdministracionDiv = document.getElementById('gastos-administracion');
                    let gastosVentasDiv = document.getElementById('gastos-ventas');
                    let otrosGastosDiv = document.getElementById('otros-gastos');
                    let utilidadNetaDiv = document.getElementById('utilidad-neta');
                    let margenGananciaDiv = document.getElementById('margen-ganancia');
                    let rentabilidadActivosROADiv = document.getElementById('rentabilidad-activos-roa');
                    let rentabilidadPatrimonioROEDiv = document.getElementById('rentabilidad-patrimonio-roe');
                    let ebitdaDiv = document.getElementById('ebitda');
                    let liquidezCorrienteDiv = document.getElementById('liquidez-corriente');
                    let capitalTrabajoDiv = document.getElementById('capital-trabajo');
                    let pruebaAcidaDiv = document.getElementById('prueba-acida');
                    let nivelEndeudamientoDiv = document.getElementById('nivel-endeudamiento');
                    let ivaGeneradoDiv = document.getElementById('iva-generado');
                    let ivaDescontableDiv = document.getElementById('iva-descontable');
                    let ivaTotalDiv = document.getElementById('iva-total');
                    let ivaTotalPagarFavorDiv = document.getElementById('iva-total-pagar-favor');
                    let impuestoConsumoGeneradoDiv = document.getElementById('impuesto-consumo-generado');


                    //FILAS DE LA TABLA
                    let utilidadBruta = this.api().column(0).data()[0];
                    let utilidadOperativa = this.api().column(0).data()[1];
                    let costosFinancieros = this.api().column(0).data()[2];
                    let totalActivoCorriente = this.api().column(0).data()[3];
                    let totalActivoNoCorriente = this.api().column(0).data()[4];
                    let totalActivo = this.api().column(0).data()[5];
                    let totalPasivoCorriente = this.api().column(0).data()[6];
                    let totalPasivoNoCorriente = this.api().column(0).data()[7];
                    let totalPasivo = this.api().column(0).data()[8];
                    let totalPatrimonio = this.api().column(0).data()[9];
                    let inventarios = this.api().column(0).data()[10];
                    let depreciacion = this.api().column(0).data()[11];
                    let amortizacion = this.api().column(0).data()[12];
                    let ingresos = this.api().column(0).data()[13];
                    let costoVentas = this.api().column(0).data()[14];
                    let utilidadNeta = this.api().column(0).data()[15];
                    let margenGananciaNeta = this.api().column(0).data()[16];
                    let rentabilidadActivosROA = this.api().column(0).data()[17];
                    let rentabilidadPatrimonioROE = this.api().column(0).data()[18];
                    let ebitda = this.api().column(0).data()[19];
                    let liquidezCorriente = this.api().column(0).data()[20];
                    let capitalTrabajo = this.api().column(0).data()[21];
                    let pruebaAcida = this.api().column(0).data()[22];
                    let nivelEndeudamiento = this.api().column(0).data()[23] + '%';
                    let devoluciones = this.api().column(0).data()[24];
                    let gastosVentas = this.api().column(0).data()[25];
                    let otrosGastos = this.api().column(0).data()[26];
                    let gastosAdministracion = this.api().column(0).data()[27];
                    let operativos = this.api().column(0).data()[28];
                    let noOperativos = this.api().column(0).data()[29];
                    let ivaGenerado = this.api().column(0).data()[30];
                    let ivaDescontable = this.api().column(0).data()[31];
                    let ivaTotal = this.api().column(0).data()[32];
                    let impuestoConsumoGenerado = this.api().column(0).data()[33];
                    let gastosTotales = this.api().column(0).data()[34];
                    let mensajeUtilidad = this.api().column(0).data()[35];
                    let iaRespuesta = this.api().column(0).data()[36];
                    let fechaInicio = this.api().column(0).data()[37];
                    let fechaFin = this.api().column(0).data()[38];
                    let razonSocial = this.api().column(0).data()[39];
                    let tipo = this.api().column(0).data()[40];
                    let logoName = this.api().column(0).data()[41];
                    let nit = this.api().column(0).data()[42];
                    let firma = this.api().column(0).data()[43];
                    let nombreCompleto = this.api().column(0).data()[44];
                    let rol = this.api().column(0).data()[45];
                    let cuentasBancarias = this.api().column(0).data()[46];
                    let devolucionesGrafData = this.api().column(0).data()[47];
                    let ingresosOperacionalesGrafData = this.api().column(0).data()[48];
                    let gastosGrafData = this.api().column(0).data()[49];
                    let ivaGrafData = this.api().column(0).data()[50];
                    let carteraGrafData = this.api().column(0).data()[51];
                    let costoVentasGrafData = this.api().column(0).data()[52];
                    let costoProduccionGrafData = this.api().column(0).data()[53];
                    // let totalCuentasBancarias = this.api().column(0).data()[54];
                    // let costosUltimoMes = this.api().column(0).data()[55];
                    let impuestosDian = this.api().column(0).data()[54];
                    let retefuente = this.api().column(0).data()[55];
                    let reteIVA = this.api().column(0).data()[56];

                    //Nos aseguramos que los valores no sean undefined o null para evitar errores
                    if (ingresos == 0 && costoVentas == 0 && devoluciones == 0 && gastosVentas == 0 && otrosGastos == 0) {
                        //sweealert
                        Swal.fire({
                            icon: 'info',
                            title: '',
                            text: 'No se encontraron datos para el informe.',
                        });

                        return;
                    }

                    //habilitar graficos
                    if (ingresos == undefined || costoVentas == undefined || devoluciones ==
                        undefined || gastosVentas == undefined || otrosGastos == undefined) {
                        //ocultar  container-informe-tabs
                        containerInformeTabs.style.display = 'none';
                        //ocultar los graficos
                        containerGrafico.forEach(function(value) {
                            value.style.display = 'none';
                        });
                        return;
                    }


                    //ocultar formulario
                    formulario.style.display = 'none';

                    //mostrar los graficos y  container-informe-tabs  
                    containerGrafico.forEach(function(value) {
                        value.style.display = 'block';
                    });
                    containerInformeTabs.style.display = 'block';

                    //formato de fecha dd-mm-yyyy
                    fechaInicio = fechaInicio.split('-').reverse().join('-');
                    fechaFin = fechaFin.split('-').reverse().join('-');
                    titulo.innerHTML = `
                    <div class="d-flex justify-content-center mb-4">
                        <button class="btn btn-save" id="generar-pdf"><i class="fa-solid fa-file-pdf"></i> Generar PDF</button>
                     </div>
                    <div class="alert alert-light alert-dismissible fade show border" style="color: #003463 !important ; border-radius: 15px">
                        <strong style="font-size:17px ;">Informe Gerencial</strong><br> 
                        ${ razonSocial } -  <span id="ntmpid">${ nit }</span><br> 
                        <small style="font-size:13px ;"><i class=""><span id="fi">${ fechaInicio }</span> hasta <span id="ff">${ fechaFin }</span></i></small>
                        <a type="button" class="btn-close" href="{{ route('admin.informesgerenciales.index') }}"></a>                                
                    </div>                    
                    `;


                    //graficos   
                    crearGraficoComposicionCostosGastos(gastosVentas, gastosAdministracion, costoVentas, otrosGastos);
                    crearGraficoComposicionIngresos(operativos, noOperativos);
                    crearGraficoComposicionSituacion(ingresos, costoVentas, gastosTotales, utilidadNeta);
                    // Convertir a valores absolutos para evitar negativos en el gráfico
                    totalActivo = Math.abs(parseFloat(totalActivo));
                    totalPasivo = Math.abs(parseFloat(totalPasivo));
                    totalPatrimonio = Math.abs(parseFloat(totalPatrimonio));
                    crearGraficoBalanceSituacion(totalActivo, totalPasivo, totalPatrimonio);
                    // crearGraficoCuentasBancarias(cuentasBancarias);
                    crearGraficoDevoluciones(devolucionesGrafData);
                    crearGraficoIngresosOperacionales(ingresosOperacionalesGrafData);
                    crearGraficoGastos(gastosGrafData);
                    crearGraficoIva(ivaGrafData);
                    crearGraficoCartera(carteraGrafData);
                    crearGraficoCostoVentas(costoVentasGrafData);
                    crearGraficoCostoProduccion(costoProduccionGrafData);




                    //::::::::::: desempeño financiero
                    ingresos = parseFloat(ingresos);
                    ingresos = ingresos.toLocaleString('es-CO');
                    costoVentas = costoVentas.toLocaleString('es-CO');
                    utilidadBruta = utilidadBruta.toLocaleString('es-CO');
                    gastosAdministracion = gastosAdministracion.toLocaleString('es-CO');
                    gastosVentas = gastosVentas.toLocaleString('es-CO');
                    otrosGastos = otrosGastos.toLocaleString('es-CO');
                    utilidadNeta = utilidadNeta.toLocaleString('es-CO');

                    desempenoFinancieroDiv.innerHTML = desempenoFinancieroHTML(ingresos, costoVentas,
                        utilidadBruta, gastosAdministracion, gastosVentas, otrosGastos,
                        mensajeUtilidad, utilidadNeta);

                    //:::::::::::::: indicadores financieros
                    ebitda = ebitda.toLocaleString('es-CO');
                    capitalTrabajo = capitalTrabajo.toLocaleString('es-CO');
                    indicadoresFinancierosDiv.innerHTML = indicadoresFinancierosHTML(ebitda,
                        capitalTrabajo, margenGananciaNeta, rentabilidadActivosROA,
                        rentabilidadPatrimonioROE, liquidezCorriente, pruebaAcida,
                        nivelEndeudamiento);

                    // ::::::::::::::: iva     
                    let totalValorGenerado = 0;
                    let totalValorCompras = 0;
                    if (ivaGrafData) {
                        totalValorGenerado = ivaGrafData.reduce((acc, item) => acc + parseFloat(item.valor_generado || 0), 0);
                        totalValorCompras = ivaGrafData.reduce((acc, item) => acc + parseFloat(item.valor_compras || 0), 0);
                    }

                    ivaTotal = Math.abs(totalValorCompras - totalValorGenerado);

                    //restar reteIVA al ivaTotal
                    ivaTotal = ivaTotal - Math.abs(reteIVA);


                    //iva
                    let titleIva = '';
                    let textColorIva = '';
                    //impuesto al consumo
                    if (totalValorGenerado >= totalValorCompras) {
                        titleIva = 'IVA a pagar';
                        textColorIva = '';
                    }
                    if (totalValorGenerado < totalValorCompras) {
                        titleIva = 'Saldo a favor';
                        textColorIva = 'text-success';
                    }



                    ivaGenerado = totalValorGenerado.toLocaleString('es-CO');
                    ivaDescontable = totalValorCompras.toLocaleString('es-CO');
                    impuestosDian = impuestosDian.toLocaleString('es-CO');
                    retefuente = retefuente.toLocaleString('es-CO');
                    reteIVA = reteIVA.toLocaleString('es-CO');
                    ivaTotal = ivaTotal.toLocaleString('es-CO');

                    let ivaTotalPagarFavor = ivaTotal != 0 ? ivaTotal : 0;
                    ivaTotalPagarFavor = ivaTotalPagarFavor.toLocaleString('es-CO');
                    //crear el html del IVA
                    impuestoIvaDiv.innerHTML = impuestoIvaHTML(ivaGenerado, ivaDescontable, ivaTotalPagarFavor, titleIva, textColorIva, reteIVA, retefuente);

                    //si el impuestoConsumoGenerado es 0 no mostrar la pestana
                    if (impuestoConsumoGenerado == 0) {
                        document.getElementById('impuesto-consumo-tab').style.display = 'none';
                    } else {
                        document.getElementById('impuesto-consumo-tab').style.display = 'block';
                    }
                    //impuestoConsumoGenerado a positivo  
                    impuestoConsumoGenerado = impuestoConsumoGenerado.toLocaleString('es-CO');
                    impuestoConsumoGeneradoDiv.innerHTML = `
                    <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                        <strong>Impuesto al Consumo Generado</strong>
                        <h4 class="text-center">$ ${ impuestoConsumoGenerado }</h4>
                    </div>                            
                    `;
                    //analisis financiero ia
                    // console.log( iaRespuesta);
                    iaRespuesta = iaRespuesta.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
                    // console.log( iaRespuesta);
                    // Mostrar iaRespuesta en un textarea editable
                    document.getElementById('ia-respuesta').innerHTML = `
                        <textarea id="ia-respuesta-textarea" class="form-control" rows="5">${iaRespuesta.replace(/<[^>]*>/g, '')}</textarea>
                    `;

                    let isIA = false;

                    if (iaRespuesta !=
                        'Ocurrió un error al intentar obtener la respuesta. Por favor, inténtelo de nuevo recargando la página.') {
                        isIA = true;
                    }


                    //::::::: PDF ;;;;;;;;;;;;;;;          
                    // generar-pdf del informe gerencial

                    let documentName = 'Informe Gerencial_' + razonSocial + '_' + fechaInicio + '_' +
                        fechaFin + '.pdf';
                    let generarPdfButton = document.getElementById('generar-pdf');
                    totalActivo = totalActivo.toLocaleString('es-CO');
                    totalPasivo = totalPasivo.toLocaleString('es-CO');
                    totalPatrimonio = totalPatrimonio.toLocaleString('es-CO');

                    //al hacer click en el boton generar pdf
                    document.getElementById('generar-pdf').addEventListener('click', function() {

                        //obtener la iaRespuesta del textarea 
                        const iaRespuestaTextarea = document.getElementById('ia-respuesta-textarea');
                        let iaRespuestaValue = iaRespuestaTextarea ? iaRespuestaTextarea.value : '';
                        iaRespuesta = iaRespuestaValue.replace(/\n/g, '<br>');


                        // Crear el PDF
                        crearPDF({
                            razonSocial,
                            fechaInicio,
                            fechaFin,
                            ingresos,
                            costoVentas,
                            utilidadBruta,
                            gastosAdministracion,
                            gastosVentas,
                            otrosGastos,
                            mensajeUtilidad,
                            utilidadNeta,
                            ebitda,
                            capitalTrabajo,
                            margenGananciaNeta,
                            rentabilidadActivosROA,
                            rentabilidadPatrimonioROE,
                            liquidezCorriente,
                            pruebaAcida,
                            nivelEndeudamiento,
                            ivaGenerado,
                            ivaDescontable,
                            ivaTotalPagarFavor,
                            titleIva,
                            textColorIva,
                            impuestoConsumoGenerado,
                            iaRespuesta,
                            firma,
                            nombreCompleto,
                            rol,
                            isIA,
                            documentName,
                            ingresosOperacionalesGrafData,
                            noOperativos,
                            devolucionesGrafData, //Ingresos y Ventas ProyectadosgastosGrafData,
                            //totalCuentasBancarias, //Gastos Deducibles y Costos
                            //costosUltimoMes // Costos último mes
                            impuestosDian,
                            reteIVA,
                            retefuente
                        });
                    });

                    loadInformacionExtra(false);
                }, //end drawCallback
                initComplete: function(settings, json) {},
            });


            // Mostrar el overlay antes de cargar la página
            $(window).on('load', function() {
                $('#overlay').show();
            });
            // Ocultar el overlay después de cargar los datos
            table.on('xhr.dt', function() {
                $('#overlay').hide();
            });



        });
    </script>

@endsection
