@extends('layouts.admin')
@section('title', 'Carga masiva de presupuesto')
@section('styles')
    <style>
        .massive-hero {
            background: linear-gradient(135deg, #006f93 0%, #2d9fc8 50%, #3FBDEE 100%);
            color: #fff;
            border-radius: 16px;
            padding: 18px 22px;
            box-shadow: 0 10px 24px rgba(63, 189, 238, 0.25);
            position: relative;
            overflow: hidden;
        }

        .massive-hero::after {
            content: '';
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
            right: -30px;
            top: -30px;
        }

        .massive-hero::before {
            content: '';
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            right: 60px;
            bottom: -20px;
        }

        .massive-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .massive-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 34px rgba(15, 23, 42, .12);
        }

        .massive-card .card-header {
            border: 0;
            padding: 18px 22px;
            font-weight: 700;
            background: #f3f7fa;
        }

        .massive-card .card-body {
            padding: 16px 22px;
        }

        .soft-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            font-size: .88rem;
            font-weight: 600;
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .upload-zone {
            border: 1.5px dashed #cbd5e1;
            border-radius: 18px;
            background: #f8fafc;
            padding: 18px;
            transition: all .2s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #3FBDEE;
            background: #f0fbff;
            box-shadow: 0 8px 22px rgba(63, 189, 238, .12);
            transform: translateY(-1px);
        }

        .upload-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #006f93 0%, #3FBDEE 100%);
            color: #fff;
            font-size: 1.25rem;
            flex: 0 0 auto;
        }

        .file-name {
            margin-top: .45rem;
            font-size: .9rem;
            color: #0b7aa6;
            font-weight: 600;
        }

        .upload-zone input[type="file"] {
            cursor: pointer;
        }

        .mini-step {
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 14px 16px;
            height: 100%;
        }

        .mini-step .icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }

        .btn-action {
            border-radius: 12px;
            padding: .72rem 1.1rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            min-height: 46px;
        }

        .alert-soft {
            border-radius: 14px;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #9f1239;
        }
    </style>
@endsection
@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a class="btn btn-back border btn-radius px-4" href="{{ route('admin.metas_empresas.index') }}">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="massive-hero">
                <div class="position-relative" style="z-index: 1;">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h3 class="mb-2 fw-bold">Carga masiva de presupuesto</h3>
                            <p class="mb-0" style="max-width: 760px; color: rgba(255,255,255,.88);">
                                Exporta una plantilla editable o sube un archivo con valores para almacenar
                                automáticamente los registros por empresa y por mes.
                            </p>
                        </div>
                        <span class="soft-badge">
                            <i class="fas fa-file-excel"></i> Presupuesto masivo
                        </span>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-12 col-md-4">
                            <div class="mini-step d-flex align-items-center gap-3">
                                <div class="icon bg-massive text-white">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">1. Exporta</div>
                                    <small class="text-muted">Descarga la plantilla base</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mini-step d-flex align-items-center gap-3">
                                <div class="icon bg-massive text-white">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">2. Diligencia</div>
                                    <small class="text-muted">Completa los valores por mes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mini-step d-flex align-items-center gap-3">
                                <div class="icon bg-massive text-white">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">3. Importa</div>
                                    <small class="text-muted">Carga y guarda los datos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card massive-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 style="color: #3FBDEE; margin: 0;">
                        <i class="fas fa-download me-2"></i>
                        Exportar plantilla
                    </h5>
                    <span class="badge bg-info-subtle text-info border border-info-subtle">Descarga</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Genera un archivo con la estructura del presupuesto para que puedas editarlo en Excel.
                    </p>
                    <form method="GET" action="{{ route('admin.metas_empresas.masiva.export') }}">
                        <div class="mb-3">
                            <label class="form-label">Empresa <b class="text-danger">*</b></label>
                            <select name="empresa_id" class="form-select" required>
                                <option value="">Selecciona una empresa</option>
                                @foreach ($empresas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Año <b class="text-danger">*</b></label>
                            <input type="number" name="anio" class="form-control" min="2000" max="2100"
                                value="{{ now()->year }}" required>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-save btn-radius px-5" type="submit">
                                <i class="fa-solid fa-cloud-arrow-down me-1"></i> Descargar plantilla
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card massive-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 style="color: #3FBDEE; margin: 0;">
                        <i class="fas fa-upload me-2"></i>
                        Cargar archivo masivo
                    </h5>
                    <span class="badge bg-info-subtle text-info border border-info-subtle">Importación</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted mb-2">
                            Si una celda viene vacía, se guardará como <b>0</b>. El sistema validará la estructura del
                            archivo.
                        </p>
                        <div class="alert alert-warning py-2 px-3 mb-0" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Las celdas del archivo <b>no deben contener fórmulas</b>, solo valores numéricos.
                        </div>
                    </div>
                    <div id="import-errors" class="alert alert-soft d-none"></div>
                    <form id="import-form" method="POST" action="{{ route('admin.metas_empresas.masiva.import') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Empresa <b class="text-danger">*</b></label>
                            <select name="empresa_id" class="form-select" required>
                                <option value="">Selecciona una empresa</option>
                                @foreach ($empresas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año <b class="text-danger">*</b></label>
                            <input type="number" name="anio" class="form-control" min="2000" max="2100"
                                value="{{ now()->year }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Archivo Excel <b class="text-danger">*</b></label>
                            <div class="upload-zone" id="upload-zone">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="upload-icon">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">Arrastra tu archivo aquí o selecciónalo</div>
                                        <small class="text-muted d-block">
                                            Formatos permitidos: .xlsx
                                        </small>
                                        <div id="file-name" class="file-name d-none"></div>
                                    </div>
                                    <label for="archivo" class="btn btn-outline-save btn-sm rounded-pill mb-0 px-3">
                                        <i class="fas fa-folder-open me-1"></i> Buscar
                                    </label>
                                </div>
                                <input type="file" id="archivo" name="archivo" class="d-none" accept=".xlsx"
                                    required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-save btn-radius px-5" type="submit">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Subir archivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            $('#import-form').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                $('#import-errors').addClass('d-none').html('');

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Listo',
                            text: res.message || 'Archivo importado correctamente',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href =
                                "{{ route('admin.metas_empresas.index') }}";
                        });
                    },
                    error: function(xhr) {
                        let html = '';

                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(function(arr) {
                                arr.forEach(function(msg) {
                                    html += `<p class="mb-1">${msg}</p>`;
                                });
                            });
                        } else if (xhr.responseJSON?.message) {
                            html = `<p class="mb-1">${xhr.responseJSON.message}</p>`;
                        } else {
                            html =
                                '<p class="mb-1">Error inesperado al importar el archivo.</p>';
                        }

                        $('#import-errors').removeClass('d-none').html(html);
                    }
                });
            });

            const fileInput = document.getElementById('archivo');
            const uploadZone = document.getElementById('upload-zone');
            const fileName = document.getElementById('file-name');

            function setSelectedFile(file) {
                if (!file) {
                    fileInput.value = '';
                    fileName.textContent = '';
                    fileName.classList.add('d-none');
                    uploadZone.classList.remove('has-file');
                    return;
                }

                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                fileName.textContent = `Archivo seleccionado: ${file.name}`;
                fileName.classList.remove('d-none');
                uploadZone.classList.add('has-file');
            }

            fileInput.addEventListener('change', function() {
                if (!this.files || this.files.length === 0) {
                    setSelectedFile(null);
                    return;
                }

                if (this.files.length > 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Solo un archivo',
                        text: 'Debes seleccionar únicamente un archivo.'
                    });
                    setSelectedFile(null);
                    return;
                }

                setSelectedFile(this.files[0]);
            });

            ['dragenter', 'dragover'].forEach(function(eventName) {
                uploadZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                uploadZone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    uploadZone.classList.remove('dragover');
                });
            });

            uploadZone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;

                if (!files || files.length === 0) {
                    return;
                }

                if (files.length > 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Solo un archivo',
                        text: 'Solo puedes arrastrar un archivo a la vez.'
                    });
                    return;
                }

                setSelectedFile(files[0]);
            });
        });
    </script>
@endsection
