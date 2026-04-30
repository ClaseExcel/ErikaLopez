//desempenoFinancieroHTML
function desempenoFinancieroHTML(
    ingresos,
    costoVentas,
    utilidadBruta,
    gastosAdministracion,
    gastosVentas,
    otrosGastos,
    mensajeUtilidad,
    utilidadNeta,
) {
    return `
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Ingresos </strong>
            <h4 class="text-center">$ ${ingresos}</h4>
        </div>
    </div>
    <div class="col-12 col sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Costos de Ventas</strong>
            <h4 class="text-center">$ ${costoVentas}</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Utilidad bruta</strong>
            <h4 class="text-center">$ ${utilidadBruta}</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Gastos de Administración</strong>
            <h4 class="text-center">$ ${gastosAdministracion}</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Gastos de Ventas</strong>
            <h4 class="text-center">$ ${gastosVentas}</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
            <strong>Otros Gastos</strong>
            <h4 class="text-center">$ ${otrosGastos}</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border  ${mensajeUtilidad == "Pérdida operativa" ? "text-danger" : ""}" role="alert" role="alert">
            <strong>${mensajeUtilidad}</strong>
            <h4 class="text-center">$ ${utilidadNeta}</h4>
        </div>
    </div>
`;
}

//indicadoresFinancierosHTML
function indicadoresFinancierosHTML(
    ebitda,
    capitalTrabajo,
    margenGananciaNeta,
    rentabilidadActivosROA,
    rentabilidadPatrimonioROE,
    liquidezCorriente,
    pruebaAcida,
    nivelEndeudamiento,
) {
    return `
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong> EBITDA</strong>
                             <h4 class="text-center">$ ${ebitda}</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>Capital de Trabajo</strong>
                             <h4 class="text-center">$ ${capitalTrabajo}</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>Margen de Ganancia</strong>
                             <h4 class="text-center">${margenGananciaNeta}%</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>ROA</strong>
                             <h4 class="text-center">${rentabilidadActivosROA}%</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>ROE</strong>
                             <h4 class="text-center">${rentabilidadPatrimonioROE}%</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>Liquidez Corriente</strong>
                             <h4 class="text-center">${liquidezCorriente}</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>Prueba Ácida</strong>
                             <h4 class="text-center">${pruebaAcida}</h4>
                         </div>
                     </div>
                     <div class="col-12 col-sm-6 col-xl-3">
                         <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                             <strong>Nivel de Endeudamiento</strong>
                             <h4 class="text-center">${nivelEndeudamiento}</h4>
                         </div>
                     </div>
                 `;
}

//impuestoIvaHTML
function impuestoIvaHTML(
    ivaGenerado,
    ivaDescontable,
    ivaTotalPagarFavor,
    titleIva,
    textColorIva,
    reteIVA,
    retefuente = 0
) {
    // console.log(ivaGenerado, ivaDescontable, ivaTotalPagarFavor, reteIVA, retefuente);

    if (
        ivaGenerado === "0" &&
        ivaDescontable === "0" &&
        reteIVA === "0" &&
        ivaTotalPagarFavor === "0"
    ){
        // console.log("Solo se muestra retefuente");
        return `
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border ${textColorIva}" role="alert">
                <strong>Retefuente</strong>
                <h4 class="text-center">$ ${retefuente}</h4>
            </div>
        </div>
    `;
    }
        return `
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                <strong>IVA Generado</strong>
                <h4 class="text-center">$ ${ivaGenerado}</h4>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                <strong>IVA Descontable</strong>
                <h4 class="text-center">$ ${ivaDescontable}</h4>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border" role="alert">
                <strong>ReteIVA</strong>
                <h4 class="text-center">$ ${reteIVA}</h4>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border ${textColorIva}" role="alert">
                <strong>${titleIva}</strong>
                <h4 class="text-center">$ ${ivaTotalPagarFavor}</h4>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="alert alert-light alert-data alert-gradient fade show text-center px-3 border ${textColorIva}" role="alert">
                <strong>Retefuente</strong>
                <h4 class="text-center">$ ${retefuente}</h4>
            </div>
        </div>
    `;
}

//######## PDF ########
function pdfHeaderHTML(razonSocial, fechaInicio, fechaFin) {
    return `
     <div>                                   
          <h3 style="color:#3fbdee ; margin-bottom: 2px">Informe Gerencial</h3>
          <div style="font-size:16px">
              ${razonSocial} <br> <span class="font-italic">${fechaInicio} hasta ${fechaFin}<span>
          </div>
     </div>
     `;
}

function textoInicialHTML(razonSocial, fechaInicio, fechaFin) {
    return `
        <p style="text-align: justify; font-size:19px">Señores:  </br>
        <strong>${razonSocial}</strong></br></br></p>            
        <p style="text-align: justify; font-size:19px"><strong>Asunto: Informe de proyección   ${fechaInicio} a ${fechaFin}</strong></p></br>
        
        <p style="text-align: justify; font-size:19px">
            El presente informe gerencial ha sido elaborado por Erika López Gómez para ${razonSocial} con el propósito de presentar un análisis 
            detallado de las cifras contables correspondientes al período  ${fechaInicio} a ${fechaFin}. Este documento tiene como objetivo proporcionar información 
            relevante y clara que apoye la evaluación de la situación financiera, el 
            desempeño operativo y los flujos de efectivo de la empresa, facilitando la 
            toma de decisiones estratégicas. 
        </p><br>
        <p style="text-align: justify; font-size:19px">
            El análisis incluye una revisión de los principales indicadores financieros, 
            tendencias clave, variaciones significativas respecto a períodos anteriores y 
            factores relevantes que han influido en los resultados obtenidos. Además, 
            se destacan áreas de oportunidad y se proponen recomendaciones 
            enfocadas en fortalecer la sostenibilidad financiera y el crecimiento a largo 
            plazo de ${razonSocial}. 
        </p><br>

        <p style="text-align: justify; font-size:19px">
            Agradecemos la confianza depositada en nuestros servicios y reiteramos 
            nuestro compromiso de proporcionar información financiera de calidad 
            que aporte valor a la gestión de ${razonSocial}. 
        </p>
     `;
}

function graficosHTML(
    graficoUno = null,
    graficoDos = null,
    opcional = null,
    lugar = null,
) {
    if (opcional != null) {
        if (lugar == "left") {
            return `
                    <div style="display: flex; justify-content:between; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
                        ${opcional}
                        ${graficoUno ? graficoUno : ""}           
                    </div>
                    ${
                        graficoDos
                            ? `
                    <div style="display: flex; justify-content:center; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
                        ${graficoDos}
                    </div>`
                            : ""
                    }
            `;
        }

        if (lugar == "right") {
            return `
                    <div style="display: flex; justify-content:between; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
                        ${graficoUno ? graficoUno : ""} 
                        ${opcional}            
                    </div>
                    ${
                        graficoDos
                            ? `
                    <div style="display: flex; justify-content:center; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
                        ${graficoDos}
                    </div>`
                            : ""
                    }
            `;
        }
    }

    return `
        ${
            graficoUno
                ? `
        <div style="display: flex; justify-content:center; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
            ${graficoUno}           
        </div>`
                : ""
        }
        ${
            graficoDos
                ? `
        <div style="display: flex; justify-content:center; margin-top: 50px; padding: 10px; border:solid 1px #ddd; background: white; border-radius: 15px">
            ${graficoDos}
        </div>`
                : ""
        }
    `;
}

function tablaHTML(
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
    reteIVA,
    retefuente,
) {

    console.log(
        ivaGenerado,
        ivaDescontable,
        ivaTotalPagarFavor,
        reteIVA,
        retefuente,
    );

    var datosIVA = `
            <table class="table table-sm table-bordered mb-4">   
            <tr style="font-size:19px">
                <td colspan="2" class="text-center" style="background-color:#3fbdee ;color:white"><strong>Impuestos DIAN</td>
            </tr>
            <tr style="font-size:16px">
                <td>IVA Generado</td>
                <td style="text-align: right ; width:50%">$ ${ivaGenerado}</td>
            </tr>
            <tr style="font-size:16px">
                <td>IVA Descontable</td>
                <td style="text-align: right ; width:50%">$ ${ivaDescontable}</td>
            </tr>
            <tr style="font-size:16px">
                <td>ReteIVA</td>
                <td style="text-align: right ; width:50%">$ ${reteIVA}</td>
            </tr>
            <tr style="font-size:16px">
                <td>Retefuente</td>
                <td style="text-align: right ; width:30%">$ ${retefuente}</td>
            </tr> 
            <tr style="font-size:16px">
                <td> ${titleIva}</td>
                <td style="text-align: right ; width:50%">$ ${ivaTotalPagarFavor}</td>
            </tr>
        </table>
    `;   
    
    if (
        ivaGenerado === "0" &&
        ivaDescontable === "0" &&
        reteIVA === "0" &&
        ivaTotalPagarFavor === "0"
    ){
        console.log("Solo se muestra retefuente en la tabla");
        datosIVA = `
        <table class="table table-sm table-bordered mb-4">   
            <tr style="font-size:19px">
                <td colspan="2" class="text-center" style="background-color:#3fbdee ;color:white"><strong>Retefuente</td>
            </tr> 
            <tr style="font-size:16px">
                <td>Retefuente</td>
                <td style="text-align: right ; width:30%">$ ${retefuente}</td>
            </tr>
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr> 
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr> 
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr>    
           <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr> 
        </table>
        `;
    }



    return `
    <div style="margin-top: 50px;">
        <table class="table table-sm table-bordered mb-4">
           <tr style="font-size:19px"> 
               <td colspan="2" class="text-center" style="background-color:#3fbdee ;color:white"><strong>Desempeño financiero</strong></td>
           </tr>
           <tr style="font-size:16px">
               <td>Ingresos</td>
               <td style="text-align: right ; width:30%">$ ${ingresos}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Costos de Ventas</td>
               <td style="text-align: right ; width:30%">$ ${costoVentas}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Utilidad bruta</td>
               <td style="text-align: right ; width:30%">$ ${utilidadBruta}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Gastos de Administración</td>
               <td style="text-align: right ; width:30%">$ ${gastosAdministracion}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Gastos de Ventas</td>
               <td style="text-align: right ; width:30%">$ ${gastosVentas}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Otros Gastos</td>
               <td style="text-align: right ; width:30%">$ ${otrosGastos}</td>
           </tr>
           <tr style="font-size:16px">
               <td>${mensajeUtilidad}</td>
               <td style="text-align: right ; width:30%">$ ${utilidadNeta}</td>
           </tr>
        </table>
        <table class="table table-sm table-bordered mb-4">
           <tr style="font-size:19px">
               <td colspan="2" class="text-center" style="background-color:#3fbdee ;color:white"><strong>Indicadores financieros</strong></td>
           </tr>
           <tr style="font-size:16px">
               <td>EBITDA</td>
               <td style="text-align: right ; width:30%">$  ${ebitda}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Capital de Trabajo</td>
               <td style="text-align: right ; width:30%">$  ${capitalTrabajo}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Margen de Ganancia</td>
               <td style="text-align: right ; width:30%"> ${margenGananciaNeta}%</td>
           </tr>
           <tr style="font-size:16px">
               <td>ROA</td>
               <td style="text-align: right ; width:30%"> ${rentabilidadActivosROA}%</td>
           </tr>
           <tr style="font-size:16px">
               <td>ROE</td>
               <td style="text-align: right ; width:30%"> ${rentabilidadPatrimonioROE}%</td>
           </tr>
           <tr style="font-size:16px">
               <td>Liquidez Corriente</td>
               <td style="text-align: right ; width:30%"> ${liquidezCorriente}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Prueba Ácida</td>
               <td style="text-align: right ; width:30%"> ${pruebaAcida}</td>
           </tr>
           <tr style="font-size:16px">
               <td>Nivel de Endeudamiento</td>
               <td style="text-align: right ; width:30%"> ${nivelEndeudamiento}</td>
           </tr>
        </table>   
     </div>
    <div style="display: flex">
        ${datosIVA}
        <table class="table table-sm table-bordered mb-4">
            <tr style="font-size:19px">
                <td colspan="2" class="text-center" style="background-color:#3fbdee ;color:white"><strong>Impuesto al consumo generado</strong></td>
            </tr>
            <tr style="font-size:16px">
                <td>Impuesto al consumo generado</td>
                <td style="text-align: right ; width:30%">$ ${impuestoConsumoGenerado}</td>
            </tr> 
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr> 
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr> 
            <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr>    
           <tr style="font-size:16px">
                <td colspan="1"> &nbsp; </td>
            </tr>                                                             
        </table>
    </div>
    `;
}

function respuestaIAHTML(iaRespuesta) {
    // Reemplaza los saltos de línea por <br> para respetar el formato del textarea
    const formattedRespuesta = iaRespuesta.replace(/\n/g, "<br>");
    return `                                           
        <h4 >Análisis de indicadores financieros (IA) </h4><br><br>
        <div style="font-size: 19px;">${formattedRespuesta}</div>
    `;
}

function observacionesHTML(
    titulo = "",
    imagenPreview = false,
    texto,
    observacionesFinales = "",
) {
    observacionesFinales = observacionesFinales.replace(/\n/g, "<br>");

    if (
        (imagenPreview === false ||
            !imagenPreview ||
            imagenPreview.style.display === "none") &&
        observacionesFinales.trim() !== ""
    ) {
        return `
            <h4>${titulo}</h4>
            <p style="font-size: 19px;">${observacionesFinales}</p>
        `;
    }

    return `
        <h4>${titulo}</h4><br><br>
        <div style="display: flex; flex-direction: column; align-items: center; max-height: 400px; max-width: 100%;">
            <div>${imagenPreview.outerHTML}</div>
            <div style="font-size: 19px;" class="font-italic">
            ${texto}
            </div>
            <p style="font-size: 19px;"><br>${observacionesFinales}</p>
        </div>
    `;
}

function firmaHTML(
    imagenFirmaUrl,
    nombreCompleto,
    rol,
    brandName,
    observaciones = "",
    contingencia = "",
) {
    return `
        <p style="text-align: justify; font-size:19px">
            ${observaciones}
        </p>
        <p style="text-align: justify; font-size:19px">
            ${contingencia}
        </p>
        <p style="text-align: justify; font-size:19px">
            La falta de diligencia en el cumplimiento de sus deberes puede generar sanciones considerables
            y requerimientos de las entidades gubernamentales. ${brandName} sigue todos los
            protocolos necesarios para evitar estas situaciones; por lo tanto, se exime de responsabilidad si
            alguna de las anteriores circunstancias llega a ser materializable.
        </p><br><br>
        <p style="text-align: justify; font-size:19px">
            <Atentamente, <br>  
            <img src="${imagenFirmaUrl}" alt="firma" style="width: 200px; height: 100px;">
            <br>
            <strong>${nombreCompleto}</strong> <br>
            ${rol}<br>
            ${brandName}
        </p>
    `;
}
