@extends('layouts.admin')
@section('title', 'Agregar Movimientos')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                @if (session('message2'))
                    <div class="alert alert-{{ session('color') }} border-0 alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <strong>{{ session('message2') }}</strong>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="text-center mb-4">
                    <button id="import-button" class="btn btn-back border btn-radius btn-lg mx-2">Importar Movimientos</button>
                    <button id="delete-button" class="btn btn-back border btn-radius btn-lg mx-2">Eliminar Movimientos</button>
                    <a href="{{ route('admin.clientes.existentes') }}" class="btn btn-back border btn-radius btn-lg mx-2" target="_blank">Ver Fechas Existentes</a>
                    <a href="{{ route('admin.movimientos.balance') }}" class="btn btn-light border btn-radius btn-lg mx-2">
                        Descargar Formato Excel
                    </a>
                </div>
                <!-- Formulario para importar movimientos -->
                <form id="import-form" action="{{ route('admin.movimientos.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <input type="hidden" id="tipo-empresa" name="tipo-empresa" value="">
                            <div class="form-floating">
                                <select class="form-select {{ $errors->has('cliente') ? 'is-invalid' : '' }}" name="cliente" id="cliente">
                                    <option value="">Seleccionar</option>
                                    @foreach ($companias as $compania)
                                        <option value="{{ $compania->NIT }}" data-id="{{ $compania->razon_social }}" data-siggo="{{ $compania->tipo }}">
                                            {{ $compania->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="cliente">Cliente <b class="text-danger">*</b></label>
                                @if ($errors->has('cliente'))
                                    <div class="invalid-feedback">{{ $errors->first('cliente') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input class="form-control {{ $errors->has('fechareporte') ? 'is-invalid' : '' }}" type="date" name="fechareporte" id="fechareporte" value="{{ old('fechareporte', '') }}" disabled min="1900-01-01" 
                                max="2099-12-31">
                                <label for="fechareporte">Fecha reporte <b class="text-danger">*</b></label>
                                @if ($errors->has('fechareporte'))
                                    <div class="invalid-feedback">{{ $errors->first('fechareporte') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="fechasExistentes" value="{{ $fechasExistentes->toJson() }}">
                    
                    <div class="form-floating mb-3">
                        <input type="hidden" name="masivo" value="1">
                        <input type="file" name="file" class="dropify" accept=".xlsx,.xls,.xlsm">
                        <label for="file">Seleccionar archivo</label>
                    </div>

                    <button class="btn btn-save btn-lg btn-block" id="load-file-button">
                        <i class="fas fa-cloud-upload-alt"></i> Importar archivo
                    </button>
                </form>

                <!-- Formulario para eliminar movimientos -->
                <form id="delete-form" action="{{ route('admin.movimientos.delete') }}" method="POST" style="display: none;">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select class="form-select {{ $errors->has('delete_cliente') ? 'is-invalid' : '' }}" name="delete_cliente" id="delete_cliente">
                                    <option value="">Seleccionar</option>
                                    @foreach ($companias as $compania)
                                        <option value="{{ $compania->NIT }}">
                                            {{ $compania->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="delete_cliente">Cliente <b class="text-danger">*</b></label>
                                @if ($errors->has('delete_cliente'))
                                    <div class="invalid-feedback">{{ $errors->first('delete_cliente') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input class="form-control {{ $errors->has('delete_fechareporte') ? 'is-invalid' : '' }}" type="date" name="delete_fechareporte" id="delete_fechareporte" value="{{ old('delete_fechareporte', '') }}" min="1900-01-01" 
                                max="2099-12-31">
                                <label for="delete_fechareporte">Fecha reporte <b class="text-danger">*</b></label>
                                @if ($errors->has('delete_fechareporte'))
                                    <div class="invalid-feedback">{{ $errors->first('delete_fechareporte') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-lg btn-block" id="delete-file-button">
                        <i class="fas fa-trash-alt"></i> Eliminar movimientos
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- jQuery file upload -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }} "></script>
    <script>
         
        $(document).ready(function () {
          // ✅ Inicializar Select2 primero
                $('#cliente').select2({
                    placeholder: "Seleccionar cliente",
                    width: '100%',
                    matcher: function(params, data) {
                        if ($.trim(params.term) === '') {
                            return data;
                        }
                        if (typeof data.text === 'undefined') {
                            return null;
                        }
                        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            return data;
                        }
                        return null;
                    }
                });

                $('#delete_cliente').select2({
                    placeholder: "Seleccionar cliente",
                    width: '100%',
                    matcher: function(params, data) {
                        if ($.trim(params.term) === '') {
                            return data;
                        }
                        if (typeof data.text === 'undefined') {
                            return null;
                        }
                        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            return data;
                        }
                        return null;
                    }
                })
                // ✅ Asegura z-index alto si hay conflictos visuales
                $('.select2-container').css('z-index', 9999);
                $('.select2-selection').css('height', '55px');

                // ✅ Inicializar Dropify después de que todo lo anterior esté listo
                $('.dropify').dropify({
                        messages: {
                            default: 'Arrastra y suelta un archivo aquí o haz click',
                            replace: 'Arrastra y suelta un archivo o haz clic para reemplazar',
                            remove: 'Remover',
                            error: 'Lo siento, el archivo es demasiado grande'
                        }
                    });

                
        });
    // //validar fechas para no permitir subir datos duplicados 
    
        $(document).ready(function () {
            const fechasExistentes = JSON.parse(document.getElementById('fechasExistentes').value);
            const fechaInput = document.getElementById('fechareporte');
            const tipoEmpresaInput = document.getElementById('tipo-empresa');

            // Función para verificar si la fecha ya existe
            function verificarFecha(selectedNit, fechaValue) {
                const selectedDate = new Date(fechaValue);
                const selectedYear = selectedDate.getFullYear();
                const selectedMonth = selectedDate.getMonth() + 1;

                const dateExists = fechasExistentes.some(fecha =>
                    fecha.Nit === selectedNit &&
                    fecha.year_reporte == selectedYear &&
                    fecha.month_reporte == selectedMonth
                );

                if (dateExists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fecha duplicada',
                        text: 'Ya existen fechas para esta empresa en el mes y año seleccionados.',
                    }).then(() => {
                        fechaInput.value = '';
                    });
                }
            }

            // Al cambiar el cliente
            $('#cliente').on('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const selectedNit = this.value;
                const tipoSiigo = selectedOption.getAttribute('data-siggo') || '';

                tipoEmpresaInput.value = tipoSiigo;

                if (selectedNit) {
                    fechaInput.disabled = false;

                    // Asegurar que no se acumulen múltiples listeners
                    fechaInput.removeEventListener('change', fechaInput._listener || (() => {}));

                    const listener = function () {
                        verificarFecha(selectedNit, this.value);
                    };

                    fechaInput._listener = listener;
                    fechaInput.addEventListener('change', listener);
                } else {
                    fechaInput.disabled = true;
                    fechaInput.value = '';
                    tipoEmpresaInput.value = '';
                }
            });

            // Si hay opción ya seleccionada al cargar
            const selectedOption = document.getElementById('cliente').selectedOptions[0];
            if (selectedOption) {
                const tipoSiigo = selectedOption.getAttribute('data-siggo') || '';
                tipoEmpresaInput.value = tipoSiigo;
            }
        });

        document.getElementById('load-file-button').addEventListener('click', function(event) {
            event.preventDefault();
            const fileInput = document.querySelector('input[name="file"]');
            const file = fileInput.files[0];
            const tipoEmpresa = document.getElementById('tipo-empresa').value;
            if (tipoEmpresa === 'PYME' && file) {
                procesarArchivoPYME(file);
                return;
            }

            // 👉 Para cualquier otro tipo, enviar formulario normal
            if (tipoEmpresa) {
                document.getElementById('import-form').submit();
                return;
            }

            // 👉 Si no tiene tipo válido
            Swal.fire({
                title: 'Error',
                text: 'Por favor, verifique el programa contable de la empresa',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        });

        function procesarArchivoPYME(file) {
                 Swal.fire({
                    title: 'Cargando archivo...',
                    html: '<progress id="progress-bar" max="100" value="0" style="width: 100%;"></progress>',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet);
                    const selectElement = document.getElementById('cliente');
                    const feechareporte = document.getElementById('fechareporte').value;
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const clienteSeleccionado = selectedOption.getAttribute('data-id');

                    if (json[0]) {
                        const clienteClave = Object.keys(json[0])[0];
                        const partesNombreCliente = clienteClave.split('- ');
                        if (clienteSeleccionado !== partesNombreCliente[1]) {
                            Swal.fire({
                                title: 'Error',
                                text: 'El nombre del cliente no coincide con el cliente seleccionado.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        const nitCliente = selectElement.value;
                        const fechaReporte = feechareporte;

                        const columnMapping = {
                            'Siigo - MATERIALES SU CONSTRUCCION S.A.': 'cuenta_descripcion',
                            '__EMPTY': 'cuenta',
                            '__EMPTY_1': 'descripcionct',
                            '__EMPTY_2': 'saldoinicial',
                            '__EMPTY_3': 'comprobante',
                            '__EMPTY_4': 'fecha',
                            '__EMPTY_5': 'nit_sl',
                            '__EMPTY_6': 'nombre',
                            '__EMPTY_7': 'descripcion',
                            '__EMPTY_8': 'inventario_cruce_cheque',
                            '__EMPTY_9': 'base',
                            '__EMPTY_10': 'cc_scc',
                            '__EMPTY_11': 'debitos',
                            '__EMPTY_12': 'creditos',
                            '__EMPTY_13': 'saldo_mov',
                            'DIC/27/2023': 'observacion_sl'
                        };

                        function excelDateToJSDate(excelDate, lastValidDate) {
                            if (typeof excelDate !== 'number' || excelDate <= 0) {
                                return lastValidDate;
                            }
                            const date = new Date((excelDate - (25567 + 2)) * 86400 * 1000);
                            const year = date.getFullYear();
                            const month = ('0' + (date.getMonth() + 1)).slice(-2);
                            const day = ('0' + date.getDate()).slice(-2);
                            return `${year}-${month}-${day}`;
                        }

                        const datosJSON = json.slice(4);
                        let lastValidDate = null;

                        const datosMapeados = datosJSON.map((fila) => {
                            const filaMapeada = {};
                            Object.entries(columnMapping).forEach(([clave, valor]) => {
                                let cellValue = fila[clave];
                                if (cellValue != null) {
                                    if (typeof cellValue === 'string') {
                                        cellValue = cellValue.trim();
                                    } else {
                                        cellValue = String(cellValue).trim();
                                    }
                                } else {
                                    cellValue = '';
                                }

                                if (valor === 'fecha') {
                                    const fechaConvertida = excelDateToJSDate(cellValue,
                                        lastValidDate);
                                    if (fechaConvertida !== lastValidDate) {
                                        lastValidDate = fechaConvertida;
                                    }
                                    filaMapeada[valor] = fechaConvertida;
                                } else if (valor === 'descripcion') {
                                    // Eliminar todos los espacios de la cadena para 'descripcion'
                                    filaMapeada[valor] = cellValue.replace(/\s+/g, '');
                                } else {
                                    filaMapeada[valor] = cellValue;
                                }
                            });
                            filaMapeada['Nit'] = nitCliente;
                            filaMapeada['fecha_reporte'] = fechaReporte;
                            return filaMapeada;
                        });


                        const chunkSize = 1000;
                        let progress = 0;

                        function sendChunk(i) {
                            if (i < datosMapeados.length) {
                                const chunk = datosMapeados.slice(i, i + chunkSize);
                                sendDataToServer(chunk).then(() => {
                                    progress += chunk.length;
                                    document.getElementById('progress-bar').value = (progress /
                                        datosMapeados.length) * 100;
                                    setTimeout(() => sendChunk(i + chunkSize),
                                    1000); // Esperar 1 segundos antes de enviar el siguiente chunk
                                }).catch(error => {
                                    Swal.close();
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Hubo un error al guardar los datos: ' + error
                                            .message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            } else {
                                Swal.close();
                                Swal.fire({
                                    title: 'Éxito',
                                    text: 'Todos los datos han sido guardados correctamente.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Refrescar la página
                                        location.reload();
                                    }
                                });
                            }
                        }

                        sendChunk(0);
                    }
                };
                reader.readAsArrayBuffer(file);
        }

        function sendDataToServer(data) {
            return fetch('{{ route('admin.movimientos.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos guardados en el servidor:', data);
                })
                .catch(error => {
                    console.error('Error al enviar datos al servidor:', error);
                    throw error;
                });
        }





        // Cambio entre formularios
        document.getElementById('import-button').addEventListener('click', function() {
            document.getElementById('import-form').style.display = 'block';
            document.getElementById('delete-form').style.display = 'none';
        });

        document.getElementById('delete-button').addEventListener('click', function() {
            document.getElementById('import-form').style.display = 'none';
            document.getElementById('delete-form').style.display = 'block';
        });

        document.getElementById('delete-file-button').addEventListener('click', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando movimientos...',
                        html: 'Espera por favor.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-form').submit();
                }
            });
        });
    </script>
@endsection
