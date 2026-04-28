<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <style>
       
        .modal-dialog {
            /* Puedes ajustar o incluso omitir estas reglas según tus necesidades */
            max-width: 55% !important;
            margin: 1.60rem auto !important;
        }

    </style>
    <div class="modal-dialog  modal-dialog-scrollable custom-swal-container" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Buscar detalles por cuenta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="searchForm">
                    @csrf
                    <div class="my-2">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <!-- Campo oculto para el nit -->
                                    <input type="hidden" name="contexto" id="contexto" value="descargar">
                                    <input type="hidden" name="nit" value="{{ $nit }}">
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fecha_inicio }}" required>
                                    <label for="fecha_inicio" class="form-label">Fecha Cuenta:</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating mb-4">
                                    <select class="form-select" id="numero_cuenta" name="numero_cuenta" required>
                                        <option value="" selected>Seleccione una Cuenta</option>
                                        @foreach ($resultados->sortBy('agrupador_cuenta') as $resultado)
                                            <?php
                                                // Verificamos si el agrupador_cuenta es "5245"
                                                if ($resultado->agrupador_cuenta === 5245) {
                                                    // Cambiamos el agrupador_cuenta a "5245/5250"
                                                    $resultado->agrupador_cuenta = "5245/5250";
                                                }
                                            ?>
                                            <option value="{{ $resultado->agrupador_cuenta }}">
                                                {{ $resultado->agrupador_cuenta }} - {{ $resultado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" required> --}}
                                    <label for="numero_cuenta" class="form-label">Número de Cuenta:</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <!-- Botón de búsqueda -->
                                <button type="button" class="btn btn-light border btn-radius px-4" onclick="searchDetails()">Buscar</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Contenedor para la tabla de resultados -->
                <div id="resultTableContainer" class="mt-4">
                    <!-- Aquí se cargará la tabla de resultados -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary border btn-radius px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function setContext(value) {
        document.getElementById('contexto').value = value;
    }
    function searchDetails() {
        const contexto = document.getElementById('contexto').value;

        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                const formData = $('#searchForm').serialize();

                $.ajax({
                    url: '{{ route("admin.estadosfinancieros.consultaporcuenta") }}',
                    type: 'POST',
                    data: formData,
                    xhrFields: contexto === 'descargar' ? { responseType: 'blob' } : {}, // Solo en "descargar" usamos blob
                    success: function(response) {
                        if (contexto === 'mostrar') {
                            // Procesar y mostrar la respuesta JSON en una tabla
                            if (response.resultados && Array.isArray(response.resultados)) {
                                let resultTable = '<table class="table table-bordered">';
                                resultTable += '<thead><tr><th>Cuenta</th><th>Nombre</th><th>Saldo Inicial</th><th>Débito</th><th>Crédito</th><th>Saldo final</th></tr></thead>';
                                resultTable += '<tbody>';
                                response.resultados.forEach(item => {
                                    resultTable += `<tr>
                                        <td>${item.cuenta}</td>
                                        <td>${item.descripcion}</td>
                                        <td>${item.saldo_anterior}</td>
                                        <td>${item.debitos}</td>
                                        <td>${item.creditos}</td>
                                        <td>${item.nuevo_saldo}</td>
                                    </tr>`;
                                });

                                resultTable += '</tbody></table>';
                                $('#resultTableContainer').html(resultTable);
                            } else {
                                console.error("La respuesta no contiene datos válidos", response);
                            }
                        } else if (contexto === 'descargar') {
                            // Descargar el archivo Excel
                            const url = window.URL.createObjectURL(response);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'consulta_por_cuenta.xlsx';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                        }
                    },
                    error: function(error) {
                        console.error("Error en la solicitud AJAX:", error);
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            }
        });
    }
</script>