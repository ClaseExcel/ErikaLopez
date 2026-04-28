
<div class="d-flex align-items-center gap-2 mb-3">
    <select id="tipo-vista" class="form-select w-auto">
        <option value="resumen">Vista Resumida</option>
        <option value="detalle">Vista Detallada</option>
    </select>
    <button type="button" class="btn btn-light border btn-radius px-4" id="btn-validar">
        Validador Informe
    </button>
</div>
<!-- Modal -->
<style>
    .tables-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1rem;
}

.table-container {
    border: 1px solid #dee2e6;
    border-radius: .5rem;
    overflow: hidden;
    background: #fff;
}

.table-title {
   
    color: white;
    padding: .5rem .75rem;
    font-weight: bold;
}

.table-container .table {
    margin-bottom: 0;
}

.table-container .table thead th {
    background: #f8f9fa;
    white-space: nowrap;
}

.table-container .table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

</style>
<div class="modal fade" id="modalValidacion" tabindex="-1">
  <div class="modal-dialog modal-xl" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Validación Estados Financieros</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="tables-wrapper" id="resultados-validacion">
          <!-- Aquí se insertan las tablas por JS -->
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    const anio1 = {{ \Carbon\Carbon::parse($fecha_real)->year }};
    const anio2 = {{ \Carbon\Carbon::parse($fecha_real)->year - 1 }};

    const secciones = {
        
        'Estado de resultados acumulado': [
            'Ingresos de actividades ordinarias (Acumulado)',
        ],
        'Activo Corriente': [
            'Efectivo y equivalentes al efectivo','Inversiones','Cuentas comerciales y otras cuentas por cobrar',
            'Activos por impuestos corrientes','Inventarios','Anticipos y avances','Otros activos'
        ],
        'Activo No Corriente': [
            'Inversiones no corriente','Propiedades planta y equipos','Activos Intangibles',
            'Impuesto diferido'
        ],
        'Pasivo Corriente': [
            'Obligaciones financieras','Cuentas comerciales y otras cuentas por pagar','Cuentas por pagar',
            'Pasivos por Impuestos Corrientes','Beneficios a empleados', 'Anticipos y avances recibidos', 'Otros Pasivos'
        ],
        'Pasivo No Corriente': [
            'Obligaciones Financieras','Cuentas por pagar comerciales y otras cuentas por pagar','Pasivos Contingentes',
            'Pasivo por impuesto diferido','Otros pasivos no corrientes'
        ],
        'Patrimonio': [
            'Capital social','Superavit de capital','Reservas','Utilidad y/o perdidas del ejercicio','Resultado del ejercicio',
            'Utilidad y/o perdidas acumuladas','Ganancias acumuladas - Adopcion por primera vez','Dividendos o participacion',
            'Superavit de Capital Valorizacion'
        ],
        'Estado de Resultados Notas': [
            'Ingresos de actividades ordinarias',
            'Costos de venta',
            'Ingresos financieros',
            'Otros ingresos',
            'Gastos de administración',
            'Gastos de ventas',
            'Gastos financieros',
            'Otros gastos',
            'Gastos impuesto de renta y cree'
        ],
        'Totales Generales': [
            'Utilidad Bruta',
            'Utilidad (Pérdida) operativa',
            'Utilidad (Pérdida) antes de impuestos de renta',
            'Utilidad (Perdida) Neta del periodo',
            'Total activo',
            'Total pasivos corrientes',
            'Total pasivos no corrientes',
            'Total Pasivo',
            'Total patrimonio',
            'Ecuación Contable'
        ]
    };

    document.getElementById('btn-validar').addEventListener('click', function () {
        const tipoVista = document.getElementById('tipo-vista').value;

        fetch('{{ route("admin.estadosfinancieros.validar", ["nit" => $nit, "fecha" => $fecha_real]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ informeData: @json($informeData2) })
        })
        .then(res => res.json())
        .then(data => {
            const fmt = (v) => {
                const n = Number(v);
                return Number.isFinite(n)
                    ? n.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
                    : '0';
            };
            const badge = (ok) => ok
                ? '<span class="badge bg-success">Cuadrado</span>'
                : '<span class="badge bg-danger">Descuadrado</span>';

            let html = `<div class="row g-3">`;

            Object.keys(secciones).forEach(nombreSeccion => {
                let filasHTML = "";
                let filasCount = 0;

                data.forEach(r => {
                    const inSeccion = secciones[nombreSeccion].includes(r.descripcion);
                    const todosCeros =
                        (Number(r.totalCalculadoA1) || 0) === 0 &&
                        (Number(r.valorInformeA1)   || 0) === 0 &&
                        (Number(r.totalCalculadoA2) || 0) === 0 &&
                        (Number(r.valorInformeA2)   || 0) === 0;

                    if (inSeccion && !todosCeros) {
                        if (tipoVista === "detalle") {
                            filasHTML += `
                                <tr>
                                    <td>${r.descripcion}</td>
                                    <td class="text-end">${fmt(r.totalCalculadoA1)}</td>
                                    <td class="text-end">${fmt(r.valorInformeA1)}</td>
                                    <td class="text-center">${badge(!!r.cuadradoA1)}</td>
                                    <td class="text-end">${fmt(r.totalCalculadoA2)}</td>
                                    <td class="text-end">${fmt(r.valorInformeA2)}</td>
                                    <td class="text-center">${badge(!!r.cuadradoA2)}</td>
                                </tr>
                            `;
                        } else {
                            // RESUMEN: Descripción | Estado año1 | Estado año2
                            filasHTML += `
                                <tr>
                                    <td>${r.descripcion}</td>
                                    <td class="text-center">${badge(!!r.cuadradoA1)}</td>
                                    <td class="text-center">${badge(!!r.cuadradoA2)}</td>
                                </tr>
                            `;
                        }
                        filasCount++;
                    }
                });

                if (filasCount > 0) {
                    const thead = (tipoVista === "detalle")
                        ? `
                            <tr>
                                <th>Descripción</th>
                                <th class="text-end"> Valor cargado.${anio1}</th>
                                <th class="text-end">Total PDF. ${anio1}</th>
                                <th class="text-center">Estado ${anio1}</th>
                                <th class="text-end">Valor cargado. ${anio2}</th>
                                <th class="text-end">Total PDF. ${anio2}</th>
                                <th class="text-center">Estado ${anio2}</th>
                            </tr>
                          `
                        : `
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Estado ${anio1}</th>
                                <th class="text-center">Estado ${anio2}</th>
                            </tr>
                          `;

                    html += `
                        <div class="col-12 col-lg-6">
                            <div class="border rounded shadow-sm h-100">
                                <div class="card-header text-white p-2 fw-bold">${nombreSeccion}</div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0 align-middle">
                                        <thead class="table-light">
                                            ${thead}
                                        </thead>
                                        <tbody>
                                            ${filasHTML}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            html += `</div>`;
            document.getElementById('resultados-validacion').innerHTML = html;

            // Mostrar modal (Bootstrap 4 con jQuery)
            $('#modalValidacion').modal('show');
        })
        .catch(err => {
            console.error(err);
            document.getElementById('resultados-validacion').innerHTML =
                `<div class="alert alert-danger">Ocurrió un error al validar.</div>`;
            $('#modalValidacion').modal('show');
        });
    });
</script>

