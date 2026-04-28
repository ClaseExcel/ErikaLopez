@extends('layouts.admin')
@section('title',"Agregar Cliente")
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Mensajes de sesión -->
                @if (session('message2'))
                    <div class="alert alert-{{ session('color') }} border-0 alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <strong>{{ session('message2') }}</strong>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Título y descripción -->
                <div class="text-center mb-4">
                    <h4 class="mb-3">Carga Masiva de Clientes</h4>
                    <p class="text-muted">Selecciona un cliente, una fecha y carga el archivo correspondiente.</p>
                </div>

                <!-- Formulario -->
                <form action="{{ route('admin.clientes.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="row mb-3">
                        <!-- Selección de cliente -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <style>
                                    /* Ajusta la altura del select2 */
                                    .select2-selection {
                                        height: 55px !important;
                                    }

                                    .select2-selection__rendered {
                                        line-height: 55px !important; /* Esto asegura que el texto esté centrado */
                                    }

                                </style>
                                <select class="form-select {{ $errors->has('cliente') ? 'is-invalid' : '' }} select2" name="cliente" id="cliente">
                                    <option value="">Seleccionar cliente</option>
                                    @foreach ($companias as $compania)
                                        <option value="{{ $compania->NIT }}" data-id="{{ $compania->razon_social }}" data-siggo="{{ $compania->tipo }}">
                                            {{ $compania->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="cliente">Cliente <b class="text-danger">*</b></label>
                                @if($errors->has('cliente'))
                                    <div class="invalid-feedback">{{ $errors->first('cliente') }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Fecha reporte -->
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input class="form-control {{ $errors->has('fechareporte') ? 'is-invalid' : '' }}" 
                                    type="date" name="fechareporte" id="fechareporte" disabled placeholder="Selecciona desde el calendario" style="cursor: pointer;" value="{{ old('fechareporte', '') }}">
                                <label for="fechareporte">Fecha reporte <b class="text-danger">*</b></label>
                                @if($errors->has('fechareporte'))
                                    <div class="invalid-feedback">{{ $errors->first('fechareporte') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botón Ver Fechas Existentes -->
                    <input type="hidden" id="fechasExistentes" value="{{ $fechasExistentes->toJson() }}">
                    <div class="text-center mb-3">
                        <a href="{{ route('admin.clientes.existentes') }}" class="btn btn-outline-info btn-lg" target="_blank">
                            <i class="fas fa-calendar-alt"></i> Ver Fechas Existentes
                        </a>
                        <button id="delete-button" type="button" class="btn btn-outline-info btn-lg"><i class="fa-solid fa-trash"></i> Eliminar Movimientos</button>
                    </div>

                    <!-- Input para archivo -->
                    <div class="form-floating mb-3">
                        <input type="hidden" name="masivo" value="1"/>
                        <input type="file" name="file" class="dropify" accept=".xlsx,.xls,.xlsm">
                        <label for="file">Seleccionar archivo</label>
                    </div>  
                
                    <!-- Botón de enviar -->
                    <button class="btn btn-save btn-lg btn-block" id="load-file-button">
                        <i class="fas fa-cloud-upload-alt"></i> Importar archivo
                    </button>
                </form>
                <!-- Formulario para eliminar movimientos desde js se llenan los datos  -->
                <form id="delete-form" action="{{ route('admin.clientes.delete') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.2/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa Select2
            $('#cliente').select2({
                width: '100%',
                minimumResultsForSearch: 10
            });

            // Alerta inicial
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Seleccione la fecha',
                text: 'Por favor, utiliza el calendario desplegable para seleccionar una fecha.',
                showConfirmButton: false,
                timer: 7000,
                timerProgressBar: true
            });

            const fechasExistentes = JSON.parse(document.getElementById('fechasExistentes').value);
            const fechaInput = document.getElementById('fechareporte');
            const clienteSelect = document.getElementById('cliente');
            const deleteButton = document.getElementById('delete-button');
            const loadFileButton = document.getElementById('load-file-button');
            let alertaMostrada = false;

            // Deshabilitar entrada manual
            fechaInput.addEventListener('keydown', e => e.preventDefault());

            // Evento cuando se selecciona una empresa (cliente)
            $('#cliente').on('select2:select', function (e) {
                const selectedNit = e.params.data.id;

                if (selectedNit) {
                    fechaInput.disabled = false;

                    const verificarFecha = () => {
                        const selectedDate = new Date(fechaInput.value);
                        const selectedYear = selectedDate.getFullYear();
                        const selectedMonth = selectedDate.getMonth() + 1;

                        const existeFecha = fechasExistentes.some(fecha =>
                            fecha.Nit === selectedNit &&
                            fecha.year_reporte == selectedYear &&
                            fecha.month_reporte == selectedMonth
                        );

                        if (existeFecha) {
                            if (!alertaMostrada) {
                                alertaMostrada = true;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Fecha duplicada',
                                    text: 'Ya existen fechas para esta empresa en el mes y año seleccionados. Solo puedes eliminar movimientos.',
                                }).then(() => {
                                    alertaMostrada = false;
                                });
                            }

                            loadFileButton.disabled = true;
                            deleteButton.disabled = false;
                            clienteSelect.disabled = true;
                            fechaInput.disabled = true;
                        } else {
                            loadFileButton.disabled = false;
                            deleteButton.disabled = true;
                            clienteSelect.disabled = false;
                            fechaInput.disabled = false;
                            alertaMostrada = false;
                        }
                    };

                    // Asociar el evento a fecha
                    fechaInput.removeEventListener('change', verificarFecha);
                    fechaInput.removeEventListener('input', verificarFecha);
                    fechaInput.addEventListener('change', verificarFecha);
                    fechaInput.addEventListener('input', verificarFecha);
                } else {
                    fechaInput.disabled = true;
                    fechaInput.value = '';
                }
            });

            // Dropify
            $('.dropify').dropify({
                messages: {
                    default: 'Clic aquí',
                    replace: 'Arrastra y suelta o haz clic para reemplazar',
                    remove: 'Remover',
                    error: 'Lo siento, el archivo es demasiado grande'
                }
            });

            // Mostrar alerta de carga
            loadFileButton.addEventListener('click', function () {
                Swal.fire({
                    title: 'Cargando archivo...',
                    html: 'Espera por favor.',
                    timer: 10000,
                    timerProgressBar: true,
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });
            });

            // Eliminar movimientos
            document.getElementById('delete-button').addEventListener('click', function () {
                try {
                    const cliente = document.getElementById('cliente').value;
                    const fechareporte = document.getElementById('fechareporte').value;

                    if (!cliente || !fechareporte) {
                        alert('Por favor, seleccione un cliente y una fecha antes de eliminar los movimientos.');
                        return;
                    }

                    const deleteForm = document.getElementById('delete-form');
                    deleteForm.innerHTML = '';

                    const clienteInput = document.createElement('input');
                    clienteInput.type = 'hidden';
                    clienteInput.name = 'delete_cliente';
                    clienteInput.value = cliente;

                    const fechaInputHidden = document.createElement('input');
                    fechaInputHidden.type = 'hidden';
                    fechaInputHidden.name = 'delete_fechareporte';
                    fechaInputHidden.value = fechareporte;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    deleteForm.appendChild(csrfToken);
                    deleteForm.appendChild(clienteInput);
                    deleteForm.appendChild(fechaInputHidden);

                    deleteForm.submit();
                } catch (error) {
                    console.error('Error al enviar el formulario:', error);
                }
            });
        });
    </script>
@endsection
