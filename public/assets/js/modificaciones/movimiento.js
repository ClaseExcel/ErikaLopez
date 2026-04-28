const movimiento = document.querySelector("#movimiento");
const periodo = document.querySelector("#periodo");
const compania_id = document.querySelector("#compania_id"); // Asegúrate de tener el selector correcto para el id de la empresa
const tableContainer = document.querySelector("#tableContainer");
const dataTableBody = document.querySelector("#dataTable tbody");

// Cargar las cuentas contables al seleccionar una empresa
compania_id.addEventListener("change", function () {
    if (periodo.value === "") {
        showAlert("Campo obligatorio", "Por favor, completa el campo periodo.", "warning");
        compania_id.value = "";
        return;
    }

    let compania = this.value;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "../get_movimientos",  // Ajusta esta URL si es necesario
        type: "POST",
        data: { id: compania, periodo: periodo.value },
        success: function (response) {
            const movimientoSelect = document.querySelector("#movimiento");
            movimientoSelect.innerHTML = '<option value="">Seleccionar</option>'; // Limpiar las opciones anteriores

            response.forEach(function (mov) {
                const option = document.createElement("option");
                option.value = mov.cuenta;
                option.textContent = mov.cuenta;
                option.dataset.saldoinicial = mov.saldoinicial;
                option.dataset.debitos = mov.debitos;
                option.dataset.creditos = mov.creditos;
                option.dataset.saldo_mov = mov.saldo_mov;
                movimientoSelect.appendChild(option);
            });

            // Mostrar el campo de cuenta contable
            document.querySelector("#movimiento").style.display = "block";
        },
        error: function (error) {
            console.error(error);
        },
    });
});

$(document).ready(function() {
    // Cuando se selecciona una cuenta, se actualiza la tabla con los datos de esa cuenta
    $('#movimiento').on('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const tableBody = document.getElementById("dataTableBody");

        // Limpiar la tabla antes de agregar nuevas filas
        tableBody.innerHTML = "";

        // Si no se ha seleccionado ninguna opción, ocultar la tabla
        if (selectedOption.value === "") {
            document.getElementById("tableContainer").style.display = "none";
            return;
        }

        // Obtener datos desde los atributos data- del <option> seleccionado
        const cuenta = selectedOption.value;
        const saldoInicial = selectedOption.dataset.saldoinicial || "0";
        const debitos = selectedOption.dataset.debitos || "0";
        const creditos = selectedOption.dataset.creditos || "0";
        const saldoMov = selectedOption.dataset.saldo_mov || "0";

        // Crear una fila para la tabla
        const row = document.createElement("tr");

        // Llenar la fila con los datos
        row.innerHTML = `
            <td>${cuenta}</td>
            <td contenteditable="true" class="editable" data-field="saldoinicial" data-original=${saldoInicial}>${saldoInicial}</td>
            <td contenteditable="true" class="editable" data-field="debitos" data-original=${debitos}>${debitos}</td>
            <td contenteditable="true" class="editable" data-field="creditos" data-original=${creditos}>${creditos}</td>
            <td contenteditable="true" class="editable" data-field="saldo_mov" data-original=${saldoMov}>${saldoMov}</td>
            <td>
                <button type="submit" class="btn btn-primary btn-save" data-cuenta="${cuenta}" disabled>Guardar</button>
            </td>
        `;

        // Añadir la fila al tbody de la tabla
        tableBody.appendChild(row);

        // Hacer visible la tabla
        document.getElementById("dataTable").style.display = "block";
    });

    // Botón de reiniciar
    $('#reiniciar').on('click', function() {
        // Revertir todos los valores a su estado original
        $('#dataTableBody').find('tr').each(function() {
            $(this).find('td.editable').each(function() {
                const originalValue = $(this).data('original');
                $(this).text(originalValue);
                $(this).attr('contenteditable', 'true');
            });
        });

        // Habilitar todas las celdas editables
        $('td.editable').prop('contenteditable', true);

        // Ocultar el botón de reiniciar nuevamente
        $('#reiniciar').hide();
    });
});

// Deshabilitar todas las celdas editables excepto la seleccionada
document.querySelector("#dataTable").addEventListener("click", function (e) {
    if (e.target.hasAttribute("contenteditable") && e.target.getAttribute("contenteditable") === "true") {
        // Si se hace clic en una celda editable, desactivar las otras
        const editableCells = document.querySelectorAll('.editable');

        editableCells.forEach(function(cell) {
            if (cell !== e.target) {
                cell.setAttribute('contenteditable', 'false');  // Desactivar las otras celdas
            }
        });

        // Mostrar el botón de reiniciar
        $('#reiniciar').show();
    }
});

// Detectar cuando un campo ha sido editado
document.querySelector("#dataTable").addEventListener("input", function (e) {
    // Solo reaccionar si el cambio es en una celda editable
    if (e.target.hasAttribute("contenteditable") && e.target.getAttribute("contenteditable") === "true") {
        const row = e.target.closest("tr"); // Obtener la fila correspondiente
        const saveButton = row.querySelector('.btn-save'); // Obtener el botón de guardar de esa fila

        // Verificar si algún campo ha sido editado y activar el botón de guardar
        const editableCells = row.querySelectorAll('[contenteditable="true"]');
        let fieldEdited = false;

        editableCells.forEach(function(cell) {
            // Si el contenido de la celda cambia respecto al valor original
            if (cell.textContent.trim() !== cell.getAttribute('data-original')) {
                fieldEdited = true;
            }
        });

        // Activar el botón de guardar solo si algún campo fue editado
        saveButton.disabled = !fieldEdited;  // Si no se editó, desactivamos el botón
    }
});

// Detener la edición y mostrar el botón de reiniciar
document.querySelector("#dataTable").addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-save")) {
        e.preventDefault(); // ← CLAVE: Prevenir el comportamiento por defecto del botón
        const row = e.target.closest("tr"); // Obtenemos la fila donde está el botón
        const cuenta = row.getAttribute("data-cuenta"); // Obtenemos el valor de la cuenta de la fila
        
        // Obtener el valor original de la celda modificada
        const saldo_original = row.querySelector('[data-field="saldoinicial"]') ? row.querySelector('[data-field="saldoinicial"]').getAttribute("data-original") : "";
        // Obtener los valores de las celdas editables
        const saldoinicial = row.querySelector('[data-field="saldoinicial"]') ? row.querySelector('[data-field="saldoinicial"]').textContent.trim() : "";
        const debitos = row.querySelector('[data-field="debitos"]') ? row.querySelector('[data-field="debitos"]').textContent.trim() : "";
        const creditos = row.querySelector('[data-field="creditos"]') ? row.querySelector('[data-field="creditos"]').textContent.trim() : "";
        const saldo_mov = row.querySelector('[data-field="saldo_mov"]') ? row.querySelector('[data-field="saldo_mov"]').textContent.trim() : "";

        // Detectar cuál campo fue editado (solo los campos editables)
        let campo_modificado = "";
        let valor_ajustado = "";  // Variable para el valor ajustado
        const editableCells = row.querySelectorAll('[contenteditable="true"]');
        
        editableCells.forEach(function(cell) {
            // Si el valor de la celda es diferente al original, sabemos que fue modificado
            if (cell.textContent.trim() !== cell.getAttribute('data-original')) {
                campo_modificado = cell.getAttribute('data-field');
                valor_ajustado = cell.textContent.trim();  // Asignamos el valor editado
            }
        });

        // Establecer los valores en el formulario oculto
        const form = document.querySelector("#formularioMovimientos");
        form.querySelector("input[name='campo_modificado']").value = campo_modificado;  // Campo modificado
        form.querySelector("input[name='valor_ajustado']").value = valor_ajustado;  // Valor editado por el usuario
        form.querySelector("input[name='saldoinicial']").value = saldoinicial;  // Valor original del saldo inicial
        form.querySelector("input[name='debitos']").value = debitos;  // Valor original de debitos
        form.querySelector("input[name='creditos']").value = creditos;  // Valor original de créditos
        form.querySelector("input[name='saldo_original']").value = saldo_original;  // Valor original antes de la modificación
        
        // Enviar el formulario de manera tradicional
        form.submit();
    }
});



