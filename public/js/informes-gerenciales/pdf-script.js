// Función para crear el texto opcional de ingresos y gastos
function crearTextoOpcional(dataOpcional, numero) {
    const [
        totalOperacionales,
        nombreUltimoMes,
        valorUltimoMes,
        noOperativos,
        totalDevoluciones,
        nombreUltimoMesDevoluciones,
        valorUltimoMesDevoluciones,
        totalGastos,
        valorUltimoMesGastos,
        nombreUltimoMesGastos,
        totalCostos,
        valorUltimoMesCostos,
    ] = dataOpcional;

    if (numero === 1) {
        return `
            <div style="margin-top: 20px;margin-bottom: 20px; font-size: 16px; line-height: 1.5; width:50%;padding-left: 20px;">
                <strong>Ingresos y Ventas Proyectados: </strong><br><br>
                • Ingresos totales anuales: $ ${totalOperacionales.toLocaleString(
                    "es-CO",
                )}<br>
                • Ingresos ${nombreUltimoMes}: $ ${valorUltimoMes.toLocaleString(
                    "es-CO",
                )}<br>
                • Otros ingresos gravables: $ ${noOperativos.toLocaleString(
                    "es-CO",
                )}<br>
                • Nota crédito anual: $ ${totalDevoluciones.toLocaleString(
                    "es-CO",
                )}<br>
                • Nota crédito ${nombreUltimoMesDevoluciones}: $ ${valorUltimoMesDevoluciones.toLocaleString(
                    "es-CO",
                )}<br>
            </div>
        `;
    }

    if (numero === 2) {
        return `
            <div style="margin-top: 20px;margin-bottom: 20px; font-size: 16px; line-height: 1.5; width:50%;padding-left: 20px;">
                <strong>Gastos Deducibles y Costos:</strong><br><br>
                • Gastos anuales: $ ${totalGastos.toLocaleString("es-CO")}<br>
                • Gasto ${nombreUltimoMesGastos}: $ ${valorUltimoMesGastos.toLocaleString(
                    "es-CO",
                )}<br>
                • Costos anuales: $ ${totalCostos.toLocaleString("es-CO")}<br>
                • Costos $ ${valorUltimoMesCostos.toLocaleString("es-CO")}
            </div>
        `;
    }

    return "";
}

function crearPDF(data) {
    // Mostrar la alerta de carga
    Swal.fire({
        title: "Generando PDF...",
        text: "Por favor, espere mientras se genera el PDF.",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    const { jsPDF } = window.jspdf;

    let header = pdfHeaderHTML(
        data.razonSocial,
        data.fechaInicio,
        data.fechaFin,
    );
    let textoInicial = textoInicialHTML(
        data.razonSocial,
        data.fechaInicio,
        data.fechaFin,
    );

    //funciones para calcular totales y valores
    const calcularTotal = (dataArray) =>
        dataArray?.reduce((acc, item) => acc + item.valor, 0) || 0;
    const obtUltimoValor = (dataArray) =>
        dataArray?.[dataArray.length - 1]?.valor || 0;
    const obtNombUltimoMes = (dataArray) => {
        const lastDate = dataArray?.[dataArray.length - 1]?.fecha;
        return lastDate
            ? new Date(lastDate).toLocaleDateString("es-ES", { month: "long" })
            : "";
    };

    const totalOperacionales = calcularTotal(
        data.ingresosOperacionalesGrafData,
    );
    const valorUltimoMes = obtUltimoValor(data.ingresosOperacionalesGrafData);
    const nombreUltimoMes = obtNombUltimoMes(
        data.ingresosOperacionalesGrafData,
    );
    const totalDevoluciones = calcularTotal(data.devolucionesGrafData);
    const valorUltimoMesDevoluciones = obtUltimoValor(
        data.devolucionesGrafData,
    );
    const nombreUltimoMesDevoluciones = obtNombUltimoMes(
        data.devolucionesGrafData,
    );
    let noOperativos = data.noOperativos || 0;
    // const totalCuentasBancarias = data.totalCuentasBancarias;

    let totalGastos = calcularTotal(data.gastosGrafData);
    let valorUltimoMesGastos = obtUltimoValor(data.gastosGrafData);
    let nombreUltimoMesGastos = obtNombUltimoMes(data.gastosGrafData);
    let totalCostos = data.costoVentas || 0;
    let valorUltimoMesCostos = data.costosUltimoMes || 0;

    let dataOpcional = [
        totalOperacionales,
        nombreUltimoMes,
        valorUltimoMes,
        noOperativos,
        totalDevoluciones,
        nombreUltimoMesDevoluciones,
        valorUltimoMesDevoluciones,
        totalGastos,
        valorUltimoMes,
        nombreUltimoMesGastos,
        totalCostos,
        valorUltimoMesCostos,
    ];

    let txtopcional1 = crearTextoOpcional(dataOpcional, 1);
    let txtopcional2 = crearTextoOpcional(dataOpcional, 2);

    let grafico1 = document.getElementById(
        "grafico-composicion-costos-gastos",
    ).innerHTML;
    let grafico2 = document.getElementById(
        "grafico-composicion-ingresos",
    ).innerHTML;
    let grafico3 = document.getElementById(
        "grafico-composicion-situacion",
    ).innerHTML;
    let grafico4 = document.getElementById(
        "grafico-balance-situacion",
    ).innerHTML;

    const getGraficoVisibleHTML = (graficoId, containerId) => {
        const graficoEl = document.getElementById(graficoId);
        const containerEl = document.getElementById(containerId);

        if (!graficoEl || !containerEl) {
            return "";
        }

        const isVisible =
            window.getComputedStyle(containerEl).display !== "none";
        const hasContent = graficoEl.innerHTML.trim() !== "";

        return isVisible && hasContent ? graficoEl.innerHTML : "";
    };

    // Extra (gráficos 5 al 11, dinámicos)
    const graficosExtra = [
        getGraficoVisibleHTML(
            "grafico-ingresos-operacionales",
            "ingresos-operacionales-container",
        ),
        getGraficoVisibleHTML("grafico-devoluciones", "devoluciones-container"),
        getGraficoVisibleHTML("grafico-gastos", "gastos-container"),
        getGraficoVisibleHTML("grafico-costo-ventas", "costo-ventas-container"),
        getGraficoVisibleHTML(
            "grafico-costo-produccion",
            "costo-produccion-container",
        ),
        getGraficoVisibleHTML("grafico-cartera", "cartera-container"),
        getGraficoVisibleHTML("grafico-iva", "iva-container"),
    ].filter((grafico) => grafico !== "");

    let grafPagInicial = "";
    let grafPagUno = graficosHTML(grafico1, grafico2);
    let grafPagDos = graficosHTML(grafico3, grafico4);
    const contenidosGraficosExtra = [];
    for (let i = 0; i < graficosExtra.length; i += 2) {
        const graficoA = graficosExtra[i];
        const graficoB = graficosExtra[i + 1] || "";
        contenidosGraficosExtra.push(graficosHTML(graficoA, graficoB));
    }

    let tabla = tablaHTML(
        data.ingresos,
        data.costoVentas,
        data.utilidadBruta,
        data.gastosAdministracion,
        data.gastosVentas,
        data.otrosGastos,
        data.mensajeUtilidad,
        data.utilidadNeta,
        data.ebitda,
        data.capitalTrabajo,
        data.margenGananciaNeta,
        data.rentabilidadActivosROA,
        data.rentabilidadPatrimonioROE,
        data.liquidezCorriente,
        data.pruebaAcida,
        data.nivelEndeudamiento,
        data.ivaGenerado,
        data.ivaDescontable,
        data.ivaTotalPagarFavor,
        data.titleIva,
        data.textColorIva,
        data.impuestoConsumoGenerado,
        data.reteIVA,
        data.retefuente,
    );

    let iaRespuestaFormato = respuestaIAHTML(data.iaRespuesta);

    //:::::::: observaciones finales
    const observacionesData = [
        {
            id: "dian",
            label: "DIAN",
        },
        {
            id: "ica",
            label: "Proyección ICA",
        },
        {
            id: "renta",
            label: "Proyección Renta",
        },
        {
            id: "simple",
            label: "Proyección Régimen Simple de Tributación",
        },
        {
            id: "cuentas",
            label: "Cuentas bancarias",
        },
    ];

    var Observaciones = document.getElementById(
        "observaciones-finales-observaciones",
    ).value;
    var Contingencia = document.getElementById(
        "observaciones-finales-contingencia",
    ).value;

    // Reemplazar saltos de línea por etiquetas <br>
    Observaciones = Observaciones.replace(/\n/g, "<br>");
    Contingencia = Contingencia.replace(/\n/g, "<br>");

    // console.log(observacionesFinalesObservaciones, observacionesFinalesContingencia);

    const observacionesFinales = observacionesData.map((data) => {
        const imagenPreview = document.getElementById(
            `imagen-preview-${data.id}`,
        );
        const isImagen =
            imagenPreview &&
            imagenPreview.src !== "#" &&
            imagenPreview.style.display !== "none";

        const texto = document.getElementById(`texto-${data.id}`).value;
        const observacionesFinales = document.getElementById(
            `observaciones-finales-${data.id}`,
        ).value;

        //verifica si tiene texto y observaciones finales
        const isObservacionesFinales = observacionesFinales.trim() !== "";
        //si tiene pagina extra cuando tiene imagen y observaciones finales
        const isPaginaExtra = isImagen && isObservacionesFinales;
        //si tiene solo observaciones finales sin imagen
        const isObservacionesFinalesOnly = isObservacionesFinales && !isImagen;

        return {
            isImagen,
            isObservacionesFinales,
            isPaginaExtra,
            isObservacionesFinalesOnly,
            formato: observacionesHTML(
                data.label,
                imagenPreview,
                texto,
                observacionesFinales,
            ),
        };
    });

    const [
        {
            isPaginaExtra: isPaginaExtraDian,
            isObservacionesFinalesOnly: isObservacionesFinalesOnlyDian,
            formato: observacionesFinalesDianFormato,
        },
        {
            isPaginaExtra: isPaginaExtraIca,
            isObservacionesFinalesOnly: isObservacionesFinalesOnlyIca,
            formato: observacionesFinalesIcaFormato,
        },
        {
            isPaginaExtra: isPaginaExtraRenta,
            isObservacionesFinalesOnly: isObservacionesFinalesOnlyRenta,
            formato: observacionesFinalesRentaFormato,
        },
        {
            isPaginaExtra: isPaginaExtraSimple,
            isObservacionesFinalesOnly: isObservacionesFinalesOnlySimple,
            formato: observacionesFinalesSimpleFormato,
        },
        {
            isPaginaExtra: isPaginaExtraCuentas,
            isObservacionesFinalesOnly: isObservacionesFinalesOnlyCuentas,
            formato: observacionesFinalesCuentasFormato,
        },
    ] = observacionesFinales;

    // Verifica si NO hay ninguna página extra de observaciones finales
    const isSetImagen =
        !isObservacionesFinalesOnlyDian &&
        !isObservacionesFinalesOnlyIca &&
        !isObservacionesFinalesOnlyRenta &&
        !isObservacionesFinalesOnlySimple &&
        !isObservacionesFinalesOnlyCuentas;

    console.log("Hay imagen", isSetImagen);

    //:::::::: fin observaciones finales

    let imagenFirmaUrl = "../../storage/users_firma/" + data.firma;
    let firmaFormato = firmaHTML(
        imagenFirmaUrl,
        data.nombreCompleto,
        data.rol,
        "Erika López Gómez",
        Observaciones,
        Contingencia,
    );

    const createPage = (content) =>
        `<div style="padding: 171px 90px;">${content}</div>`; // Ajusta el padding según sea necesario

    const paginasGraficosExtra = contenidosGraficosExtra.map((contenido) =>
        createPage(header + contenido),
    );

    let paginaInicial = createPage(textoInicial + grafPagInicial);
    let pagina2 = createPage(header + grafPagUno);
    let pagina3 = createPage(header + grafPagDos);
    let pagina4 = createPage(header + tabla);
    let pagina5 = createPage(iaRespuestaFormato);
    let pagina6 = createPage(observacionesFinalesDianFormato);
    let pagina7 = createPage(observacionesFinalesIcaFormato);
    let pagina8 = createPage(observacionesFinalesRentaFormato);
    let pagina9 = createPage(observacionesFinalesSimpleFormato);
    let pagina10 = createPage(observacionesFinalesCuentasFormato);
    let paginaFinal = createPage(firmaFormato);

    let pdfDiv = document.getElementById("pdf-div");
    pdfDiv.style.minWidth = "1024px";
    pdfDiv.style.minHeight = "1325px";
    pdfDiv.style.maxWidth = "1024px";
    pdfDiv.style.maxHeight = "1325px";
    pdfDiv.style.backgroundSize = "1024px 1325px";
    pdfDiv.style.backgroundRepeat = "no-repeat";
    pdfDiv.style.backgroundImage = `url('../../images/background/fondo_pdf.png')`;

    const pdf = new jsPDF();
    pdfDiv.innerHTML = paginaInicial;
    function addPageToPDF(pdf, pdfDiv, content, condition = true) {
        if (condition) {
            pdf.addPage();
            pdfDiv.innerHTML = content;
        }

        return html2canvas(pdfDiv, {
            scale: 3,
        }).then((canvas) => {
            const imgData = canvas.toDataURL("image/jpeg", 0.7); // Use JPEG format with 70% quality
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            if (condition) {
                pdf.addImage(imgData, "JPEG", 0, 10, pdfWidth, pdfHeight);
            }
        });
    }

    html2canvas(pdfDiv, {
        scale: 3,
        useCORS: true,
        allowTaint: true,
        logging: false,
        backgroundColor: "white",
    }).then((canvas) => {
        const imgData = canvas.toDataURL("image/jpeg", 0.7); // Use JPEG format with 70% quality
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, "JPEG", 0, 10, pdfWidth, pdfHeight);

        addPageToPDF(pdf, pdfDiv, pagina2)
            .then(() => addPageToPDF(pdf, pdfDiv, pagina3))
            .then(() => {
                return paginasGraficosExtra.reduce(
                    (promiseChain, paginaExtra) => {
                        return promiseChain.then(() =>
                            addPageToPDF(pdf, pdfDiv, paginaExtra),
                        );
                    },
                    Promise.resolve(),
                );
            })
            .then(() => addPageToPDF(pdf, pdfDiv, pagina4))
            .then(() => addPageToPDF(pdf, pdfDiv, pagina5, data.isIA))
            //si tiene pagina extra de observaciones finales
            .then(() => addPageToPDF(pdf, pdfDiv, pagina6, isPaginaExtraDian))
            .then(() => addPageToPDF(pdf, pdfDiv, pagina7, isPaginaExtraIca))
            .then(() => addPageToPDF(pdf, pdfDiv, pagina8, isPaginaExtraRenta))
            .then(() => addPageToPDF(pdf, pdfDiv, pagina9, isPaginaExtraSimple))
            .then(() =>
                addPageToPDF(pdf, pdfDiv, pagina10, isPaginaExtraCuentas),
            )
            //agrega las observaciones finales que no tuvieron imagen que es igual a no tener pagina extra
            .then(() => {
                const observacionesConTexto = [
                    {
                        content: observacionesFinalesDianFormato,
                        show: isObservacionesFinalesOnlyDian,
                    },
                    {
                        content: observacionesFinalesIcaFormato,
                        show: isObservacionesFinalesOnlyIca,
                    },
                    {
                        content: observacionesFinalesRentaFormato,
                        show: isObservacionesFinalesOnlyRenta,
                    },
                    {
                        content: observacionesFinalesSimpleFormato,
                        show: isObservacionesFinalesOnlySimple,
                    },
                    {
                        content: observacionesFinalesCuentasFormato,
                        show: isObservacionesFinalesOnlyCuentas,
                    },
                ];

                const observacionesAMostrar = observacionesConTexto
                    .filter((obs) => obs.show)
                    .map((obs) => obs.content)
                    .join("");

                // Si no hay páginas extra de observaciones, la firma va en la misma página
                if (observacionesAMostrar) {
                    pdf.addPage();
                    let contenidoFinal = observacionesAMostrar;
                    if (!isSetImagen) {
                        contenidoFinal += "<br><br>" + firmaFormato;
                    }
                    pdfDiv.innerHTML = createPage(contenidoFinal);
                    return html2canvas(pdfDiv, { scale: 3 }).then((canvas) => {
                        const imgData = canvas.toDataURL("image/jpeg", 0.7);
                        const imgProps = pdf.getImageProperties(imgData);
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight =
                            (imgProps.height * pdfWidth) / imgProps.width;
                        pdf.addImage(
                            imgData,
                            "JPEG",
                            0,
                            10,
                            pdfWidth,
                            pdfHeight,
                        );
                    });
                }
            })

            .then(() => addPageToPDF(pdf, pdfDiv, paginaFinal, isSetImagen))
            .then(() => {
                pdf.setFontSize(12);
                pdf.save(data.documentName);
                Swal.close();
                pdfDiv.innerHTML = "";
                pdfDiv.removeAttribute("style");
            });
    });
}
