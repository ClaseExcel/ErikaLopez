const colores = [
    "#3fbdee",
    "#4a5057",
    "#5CC7F2",
    "#6e7479",
    "#7DD5F5",
    "#626973",
    "#3fbdee99",
    "#485e7499",
    "#5CC7F299",
    "#2c537699",
    "#7DD5F599",
    "#5077ac99",
];

function crearGraficoComposicionCostosGastos(
    gastosVentas = 0,
    gastosAdministracion = 0,
    costoVentas = 0,
    otrosGastos = 0,
) {
    //convertir a float
    gastosVentas = Math.abs(parseFloat(gastosVentas));
    gastosAdministracion = Math.abs(parseFloat(gastosAdministracion));
    costoVentas = Math.abs(parseFloat(costoVentas));
    otrosGastos = Math.abs(parseFloat(otrosGastos));

    // Data should be an array of values, not objects.
    let data = [costoVentas, gastosVentas, gastosAdministracion, otrosGastos];

    //column chart
    Highcharts.chart("grafico-composicion-costos-gastos", {
        chart: {
            type: "column",
        },
        title: {
            text: "Composición de los costos y gastos",
        },
        xAxis: {
            categories: [
                "Costos de Ventas",
                "Gastos de Ventas",
                "Gastos de Administración",
                "Otros Gastos",
            ],
        },
        yAxis: {
            title: {
                text: "Valores",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Valores",
                data: data,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

function crearGraficoComposicionIngresos(
    operativos = 311782314,
    noOperativos = 32875824,
) {
    //convertir a float
    operativos = Math.abs(parseFloat(operativos));
    noOperativos = Math.abs(parseFloat(noOperativos));

    // Data should be an array of values, not objects.
    let data = [operativos, noOperativos];

    //column chart
    Highcharts.chart("grafico-composicion-ingresos", {
        chart: {
            type: "column",
        },
        title: {
            text: "Composición de los ingresos",
        },
        xAxis: {
            categories: ["Ingresos Operativos", "Ingresos No Operativos"],
        },
        yAxis: {
            title: {
                text: "Valores",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Valores",
                data: data,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

function crearGraficoComposicionSituacion(
    ingresos = 311782314,
    costoVentas = 328782734,
    gastosTotales = 43254632,
    utilidadNeta = 84793045,
) {
    //convertir a float
    ingresos = Math.abs(parseFloat(ingresos));
    costoVentas = Math.abs(parseFloat(costoVentas));
    gastosTotales = Math.abs(parseFloat(gastosTotales));
    utilidadNeta = parseFloat(utilidadNeta);
    // Data should be an array of values, not objects.
    let data = [ingresos, costoVentas, gastosTotales, utilidadNeta];
    // Add data labels

    //gráfico de barras
    Highcharts.chart("grafico-composicion-situacion", {
        chart: {
            type: "column",
        },
        title: {
            text: "Composición de la situación",
        },
        xAxis: {
            categories: [
                "Ingresos",
                "Costos ventas",
                "Gastos totales",
                "Utilidad/Perdida",
            ],
        },
        yAxis: {
            title: {
                text: "Valores",
            },
            min: Math.min(...data, 0),
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Valores",
                data: data,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

function crearGraficoBalanceSituacion(
    totalActivo = 3252346,
    totalPasivo = 23578235,
    totalPatrimonio = 28656465,
) {
    // //convertir a float
    // totalActivo = Math.abs(parseFloat(totalActivo));
    // totalPasivo = Math.abs(parseFloat(totalPasivo));
    // totalPatrimonio = Math.abs(parseFloat(totalPatrimonio));

    // Data should be an array of values, not objects.
    let data = [totalActivo, totalPasivo, totalPatrimonio];

    //grfico de barras
    Highcharts.chart("grafico-balance-situacion", {
        chart: {
            type: "column",
        },
        title: {
            text: "Balance de situación",
        },
        xAxis: {
            categories: ["Total Activo", "Total Pasivo", "Patrimonio"],
        },
        yAxis: {
            title: {
                text: "Valores",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Valores",
                data: data,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}
//crear grafico cuentas bancarias
function crearGraficoCuentasBancarias(cuentasBancarias) {
    //si no hay datos, no crear el grafico
    if (cuentasBancarias.length === 0) {
        document.getElementById("grafico-cuentas-bancarias").style.display =
            "none";
        return;
    }

    let fecha = cuentasBancarias.map((cuenta) => cuenta.fecha);
    let valores = cuentasBancarias.map((cuenta) => parseFloat(cuenta.valor));
    let bancos = cuentasBancarias.map(
        (cuenta) => cuenta.empresa_banco.banco.nombre,
    );
    // Calcular el total de cuentas bancarias
    let totalCuentasBancarias = valores.reduce((acc, val) => acc + val, 0);

    // Agregar una última columna con el total
    fecha.push("Total");
    valores.push(totalCuentasBancarias);
    bancos.push("Total");

    //gráfico de barras horizontales
    Highcharts.chart("grafico-cuentas-bancarias", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Cuentas bancarias",
        },
        xAxis: {
            type: "category",
            categories: fecha.map((date, index) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                let banco = bancos[this.point.index];
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b><br>" +
                    (banco !== "Total" ? banco + "<br>" : "") +
                    "$" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Configuración específica para barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        let banco = bancos[this.point.index];
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            (banco !== "Total" ? "<br>" + banco : "") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

//crear grafico devoluciones
function crearGraficoDevoluciones(devoluciones) {
    console.log("Devoluciones recibidas para el gráfico:", devoluciones);
    //si no hay datos, no crear el grafico
    if (devoluciones.length === 0) {
        const devolucionesContainer = document.getElementById(
            "devoluciones-container",
        );
        devolucionesContainer.style.display = "none";
        return;
    }

    let fechas = devoluciones.map((devolucion) => devolucion.fecha);
    let valores = devoluciones.map((devolucion) =>
        parseFloat(devolucion.valor),
    );

    // Calcular el total de devoluciones
    let totalDevoluciones = valores.reduce((acc, val) => acc + val, 0);

    // Agregar una última columna con el total
    fechas.push("Total");
    valores.push(totalDevoluciones);

    // Gráfico de barras horizontales
    Highcharts.chart("grafico-devoluciones", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Devoluciones en ventas",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

function crearGraficoIngresosOperacionales(ingresosOperacionales) {
    //console.log(
    //     "Ingresos operacionales recibidos para el gráfico:",
    //     ingresosOperacionales,
    // );
    //si no hay datos, no crear el grafico
    if (ingresosOperacionales.length === 0) {
        const ingresosOperacionalesContainer = document.getElementById(
            "ingresos-operacionales-container",
        );
        ingresosOperacionalesContainer.style.display = "none";
        return;
    }

    let fechas = ingresosOperacionales.map((ingreso) => ingreso.fecha);
    let valores = ingresosOperacionales.map((ingreso) =>
        parseFloat(ingreso.valor),
    );

    // Calcular el total de ingresos operacionales
    let totalIngresos = valores.reduce((acc, val) => acc + val, 0);

    // Agregar una última columna con el total
    fechas.push("Total");
    valores.push(totalIngresos);

    // Gráfico de barras horizontales
    Highcharts.chart("grafico-ingresos-operacionales", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Ingresos Operacionales",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}

function crearGraficoGastos(gastos) {
    //console.log("Gastos recibidos para el gráfico:", gastos);
    //si no hay datos, no crear el grafico
    if (gastos.length === 0) {
        const gastosContainer = document.getElementById("gastos-container");
        gastosContainer.style.display = "none";
        return;
    }

    let fechas = gastos.map((gasto) => gasto.fecha);
    let valores = gastos.map((gasto) => parseFloat(gasto.valor));

    // Calcular el total de gastos
    let totalGastos = valores.reduce((acc, val) => acc + val, 0);

    // Agregar una última columna con el total
    fechas.push("Total");
    valores.push(totalGastos);

    // Gráfico de barras horizontales
    Highcharts.chart("grafico-gastos", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Gastos",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
}
function crearGraficoIva(ivaGrafData) {
    // console.log("Datos de IVA recibidos para el gráfico:", ivaGrafData);
    
    // Filtrar registros que tengan fecha válida
    const ivaFiltrado = ivaGrafData.filter(iva => 
        iva.fecha !== null && 
        iva.fecha !== undefined && 
        iva.fecha !== ""
    );
    
    // console.log("Datos de IVA después del filtrado:", ivaFiltrado);

    //si no hay datos, no crear el grafico
    if (!ivaFiltrado || ivaFiltrado.length === 0) {
        const ivaContainer = document.getElementById("iva-container");
        const elGen = document.getElementById("grafico-iva-generado");
        const elComp = document.getElementById("grafico-iva-compras");
        ivaContainer.style.display = "none";
        if (elGen) elGen.style.display = "none";
        if (elComp) elComp.style.display = "none";
        return;
    }

    let fechas = ivaFiltrado.map((iva) => iva.fecha);
    let valoresGenerados = ivaFiltrado.map((iva) => parseFloat(iva.valor_generado));
    let valoresCompras = ivaFiltrado.map((iva) => parseFloat(iva.valor_compras));
    let año = "N/A";
    if (fechas.length > 0 && fechas && typeof fechas === "string") {
        año = fechas.split("-");
    }
    año = "(" + año + ")";

    // Calcular el total de IVA generado y descontable
    let totalGenerados = valoresGenerados.reduce((acc, val) => acc + val, 0);
    let totalCompras = valoresCompras.reduce((acc, val) => acc + val, 0);

    // Agregar una última columna con el total
    fechas.push("Total");
    valoresGenerados.push(totalGenerados);
    valoresCompras.push(totalCompras);

    // Encontrar el valor mínimo para ajustar el eje Y y mostrar negativos
    let minGenerado = Math.min(...valoresGenerados, 0);
    let minCompras = Math.min(...valoresCompras, 0);

    // Gráfico de barras horizontales para IVA generado
    Highcharts.chart("grafico-iva-generado", {
        chart: {
            type: "bar",
        },
        title: {
            text: "IVA Generado en ventas" + ` ${año}`,
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [_, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: minGenerado, // Permitir negativos
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico-iva"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                name: "IVA Generado",
                data: valoresGenerados,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico-iva">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });

    // Gráfico de barras horizontales para IVA descontable
    Highcharts.chart("grafico-iva-compras", {
        chart: {
            type: "bar",
        },
        title: {
            text: "IVA Descontable por compras" + ` ${año}`,
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [_, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            min: minCompras, // Permitir negativos
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico-iva"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                name: "IVA Descontable",
                data: valoresCompras,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico-iva">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
} //fin crear grafico iva

function crearGraficoCartera(cartera) {
    //console.log("Cartera recibida para el gráfico:", cartera);
    //si no hay datos, no crear el grafico
    if (cartera.length === 0) {
        const carteraContainer = document.getElementById("cartera-container");
        carteraContainer.style.display = "none";
        return;
    }

    let fechas = cartera.map((item) => item.fecha);
    let valores = cartera.map((item) => parseFloat(item.valor));
    // Calcular el total de cartera
    // let totalCartera = valores.reduce((acc, val) => acc + val, 0);
    // Agregar una última columna con el total
    // fechas.push("Total");
    // valores.push(totalCartera);
    // Gráfico de barras horizontales
    Highcharts.chart("grafico-cartera", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Cartera",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                // if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            // min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
} //fin crear grafico cartera

function crearGraficoCostoVentas(costoVentasGrafData) {
    //console.log("Costo de ventas recibido para el gráfico:", costoVentasGrafData);
    //si no hay datos, no crear el grafico
    if (costoVentasGrafData.length === 0) {
        const costoVentasContainer = document.getElementById(
            "costo-ventas-container",
        );
        costoVentasContainer.style.display = "none";
        return;
    }

    let fechas = costoVentasGrafData.map((item) => item.fecha);
    let valores = costoVentasGrafData.map((item) => parseFloat(item.valor));
    // Calcular el total de costo de ventas
    let totalCostoVentas = valores.reduce((acc, val) => acc + val, 0);
    // Agregar una última columna con el total
    fechas.push("Total");
    valores.push(totalCostoVentas);
    // Gráfico de barras horizontales
    Highcharts.chart("grafico-costo-ventas", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Costo de Ventas",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            // min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
} //fin crear grafico costo de ventas
function crearGraficoCostoProduccion(costoProduccionGrafData) {
    //si no hay datos, no crear el grafico
    if (costoProduccionGrafData.length === 0) {
        const costoProduccionContainer = document.getElementById(
            "costo-produccion-container",
        );
        costoProduccionContainer.style.display = "none";
        return;
    }

    let fechas = costoProduccionGrafData.map((item) => item.fecha);
    let valores = costoProduccionGrafData.map((item) => parseFloat(item.valor));
    // Calcular el total de costo de produccion
    let totalCostoProduccion = valores.reduce((acc, val) => acc + val, 0);
    // Agregar una última columna con el total
    fechas.push("Total");
    valores.push(totalCostoProduccion);
    // Gráfico de barras horizontales
    Highcharts.chart("grafico-costo-produccion", {
        chart: {
            type: "bar", // Cambiar a 'bar' para gráfico horizontal
        },
        title: {
            text: "Costo de Producción",
        },
        xAxis: {
            type: "category",
            categories: fechas.map((date) => {
                if (date === "Total") return date;
                const [year, month] = date.split("-");
                const months = [
                    "ene",
                    "feb",
                    "mar",
                    "abr",
                    "may",
                    "jun",
                    "jul",
                    "ago",
                    "sep",
                    "oct",
                    "nov",
                    "dic",
                ];
                return `${months[parseInt(month) - 1]} ${year}`;
            }),
            title: {
                text: null,
            },
        },
        yAxis: {
            // min: 0,
            title: {
                text: "Valores",
                align: "high",
            },
            labels: {
                overflow: "justify",
            },
        },
        tooltip: {
            formatter: function () {
                return (
                    '<span class="texto-grafico"><b>' +
                    this.point.category +
                    "</b>: $" +
                    this.y.toLocaleString("es-CO") +
                    "</span>"
                );
            },
        },
        series: [
            {
                cursor: "pointer",
                name: "Fechas",
                data: valores,
                colorByPoint: true,
                colors: colores,
            },
        ],
        exporting: {
            enabled: false,
        },
        plotOptions: {
            bar: {
                // Cambiar a 'bar' para aplicar configuraciones específicas de barras horizontales
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return (
                            '<span class="texto-grafico">$' +
                            this.y.toLocaleString("es-CO") +
                            "</span>"
                        );
                    },
                },
            },
        },
    });
} //fin crear grafico costo de produccion