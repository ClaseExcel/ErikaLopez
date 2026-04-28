<button id="openModalButtonAnalisis" class="btn btn-sm btn-save btn-radius mr-2 px-3"
    title="Realizar análisis financiero y fiscal por medio de la IA">
    <img src="{{ asset('images/icono_ia.svg') }}" alt="" width="23" style="filter: invert(1);">
    Análisis financiero y fiscal
</button>

<div class="modal fade bd-example-modal-lg" id="messagesModalAnalisis" tabindex="-1" role="dialog"
    aria-labelledby="messagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header text-white" style="background-color: #919396; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title" id="messagesModalLabel">
                    <img src="{{ asset('images/icono_ia.svg') }}" alt="" width="23"
                        style="filter: invert(1);">
                    Análisis financiero y fiscal con IA
                </h5>
                <button type="button" class="close" id="closeModalButtonAnalisis" data-dismiss="modal"
                    aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <div id="ia-repuesta"> {!! $iaAnalisis !!}</div>
            </div>

            <div class="modal-footer d-flex">
                <button type="button" class="btn btn-save btn-radius mx-auto" onclick="copyText( `{!! $iaAnalisis !!}`)"><i class="fa-solid fa-copy"></i> Copiar texto</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('openModalButtonAnalisis').addEventListener('click', function() {
        $('#messagesModalAnalisis').modal('show');
    });
    document.getElementById('closeModalButtonAnalisis').addEventListener('click', function() {
        $('#messagesModalAnalisis').modal('hide');
    });

    function copyText(text) {

        text = text.replace(/<[^>]*>?/gm, ''); //quitar las etiquetas html del texto

        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                title: 'Texto copiado',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                customClass: {
                    confirmButton: 'btn btn-sm btn-save',
                    cancelButton: 'btn btn-sm btn-cancel',
                    input: 'form-control shadow-none',
                    title: 'fs-5',
                },
            });
        }, function(err) {
            console.error('No se pudo copiar el texto: ', err);
        });
    }
</script>
