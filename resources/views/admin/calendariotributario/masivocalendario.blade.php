@extends('layouts.admin')
@section('title', 'Calendario Tributario')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
<div class="form-group">
    <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.calendario.index') }}">
        <i class="fas fa-arrow-circle-left"></i> Atrás
    </a>
</div>
    <div class="row">
        @if (session('message2'))
                    <div class="row px-2">
                        <div class="alert alert-{{ session('color') }} border-0 alert-dismissible fade show d-flex align-items-center"
                            role="alert">
                            <div class="d-flex flex-grow-1">
                                <div>
                                    <i class="fa-solid fa-circle-info"></i> <b>{{ session('message2') }}</b>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header fw-5">
                    Calendario tributario
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('admin.calendario.exportExcelActualizarcalendario')}}">
                        @csrf
                        <div class="form-floating mb-4">
                            <button type="submit" class="btn btn-save btn-radius px-4"><i class="fa-solid fa-download"></i> Descargar Plantilla</button>
                            {{-- <a href="{{asset('data/CalendarioTributario/MasivoCalendario.xlsx')}}"
                                    download="MasivoCalendario.xlsx" class="btn btn-save btn-radius px-4"><i class="fa-solid fa-download"></i> Descargar
                                    Plantilla</a> --}}
                        </div>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.calendario.masivo') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-0">
                            <div class="form-floating mb-4">
                                <input type="file" name="masivo" class="dropify"
                                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/vnd.ms-excel.sheet.macroenabled.12" />
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-save btn-radius px-4" id="load-file-button" type="submit">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Importar Archivo
                            </button>
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(session('file_upload_completed'))
    <script>
        setTimeout(function() {
            Swal.close();
        }, 2000); // Cierra el SweetAlert después de 2 segundos
    </script>
@endif
@endsection
@section('scripts')
    <script src="{{ asset('js/actividadcliente/show.js') }}" defer></script>
       <!-- jQuery file upload -->
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }} "></script>
       <script>
           $(document).ready(function() {
               // Translated
               $('.dropify').dropify({
                   messages: {
                       default: 'Arrastra y suelta o haz click',
                       replace: 'Arrastra y suelta o haz clic para reemplazar',
                       remove: 'Remover',
                       error: 'Lo siento, el archivo es demasiado grande'
                   }
               });
               var drDestroy = $('#input-file-to-destroy').dropify();
               drDestroy = drDestroy.data('dropify')
               $('#toggleDropify').on('click', function(e) {
                   e.preventDefault();
                   if (drDestroy.isDropified()) {
                       drDestroy.destroy();
                   } else {
                       drDestroy.init();
                   }
               })
           });
           document.getElementById('load-file-button').addEventListener('click', function() {
           Swal.fire({
               title: 'Cargando archivo...',
               html: 'Espera por favor.',
               timer: 150000, 
               timerProgressBar: true,
               icon: 'info',
               allowOutsideClick: false, // Evita que se cierre haciendo clic fuera
               showConfirmButton: false,
               customClass: {
                   container: 'custom-swal-container' // Agrega una clase personalizada para el contenedor del SweetAlert
               },
               onBeforeOpen: () => {
                   Swal.showLoading();
               }
           });
       });
       </script>
@endsection
