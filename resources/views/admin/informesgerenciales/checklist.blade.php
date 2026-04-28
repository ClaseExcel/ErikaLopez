{{-- checklist --}}
<div class="col-12 mb-4" style="display: none" id="mostrarChecklist">
    <label class="fw-normal d-block mb-2">Gráficos que serán generados</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="2" id="ingresosOperacionales" checked>
        <label class="form-check-label" for="ingresosOperacionales">Ingresos Operacionales</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="1" id="devolucionesVentas" checked>
        <label class="form-check-label" for="devolucionesVentas">Devoluciones en ventas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="3" id="gastos" checked>
        <label class="form-check-label" for="gastos">Gastos</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="5" id="costoVentas" checked>
        <label class="form-check-label" for="costoVentas">Costo de Ventas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="6" id="cartera" checked>
        <label class="form-check-label" for="cartera">Cartera</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="7" id="costoProduccion" checked>
        <label class="form-check-label" for="costoProduccion">Costo Producción</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="4" id="iva" checked>
        <label class="form-check-label" for="iva">IVA</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="datos[]" value="0" id="ninguno">
        <label class="form-check-label" for="ninguno">Ninguno de los anteriores</label>
    </div>
</div>

<script>
    document.getElementById("ninguno").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll('input[name="datos[]"]');
        if (this.checked) {
            checkboxes.forEach(checkbox => {
                if (checkbox.id !== "ninguno") {
                    checkbox.checked = false;
                }
            });
        } else {
            checkboxes.forEach(checkbox => {
                if (checkbox.id !== "ninguno") {
                    checkbox.checked = true;
                }
            });
        }
    });

    const otherCheckboxes = document.querySelectorAll('input[name="datos[]"]:not(#ninguno)');
    otherCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            if (this.checked) {
                document.getElementById("ninguno").checked = false;
            }
        });
    });
</script>
