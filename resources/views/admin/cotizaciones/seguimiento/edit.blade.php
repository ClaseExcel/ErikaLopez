@extends('layouts.admin')
@section('title', 'Seguimiento de cotización: ' . $cotizacion->numero_cotizacion)
@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.cotizaciones.show', $cotizacion->id) }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <form method="POST" action="{{ route('admin.cotizacion-seguimiento.update', $seguimiento->id) }}"
                    enctype="multipart/form-data" id="cotizacion">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <span class="fw-normal"><i class="fa-solid fa-file-invoice"></i>&nbsp; Seguimiento de la
                            cotización: <span class="fw-bold">#{{ $cotizacion->numero_cotizacion }}</span> </span>
                    </div>
                    <div class="card-body">

                        <div class="col-xl-12 mb-3">
                            <label for="observaciones" class="fw-normal">Observaciones: <b class="text-danger">*</b></label>
                            <textarea id="observaciones" name="observaciones"> {!! old('observaciones', $seguimiento->observaciones) !!} </textarea>
                            @if ($errors->has('observaciones'))
                                <span class="text-danger">{{ $errors->first('observaciones') }}</span>
                            @endif
                        </div>

                        <div class="col-xl-12">
                            <div class="form-floating mb-2">
                                <div class="row d-flex justify-content-between ">
                                    <div class="col-10">
                                        <label>
                                           Documento
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <label class="input-group-text bg-transparent" for="documento"><i
                                                    class="fas fa-file-upload"></i> &nbsp; </label>
                                            <input type="file" id="documento" name="documento" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <hr style="height: 0 !important; width: 100%; border-top: 1px dashed rgb(215 215 215);">
                        </div>

                        <div class="col-xl-12 mb-3">
                            <label for="observacion_proximo_seguimiento" class="fw-normal">Observación del próximo
                                seguimiento: </label>
                            <textarea id="observacion_proximo_seguimiento" name="observacion_proximo_seguimiento"> 
                                {!! old('observacion_proximo_seguimiento', $seguimiento_cotizacion->observacion_proximo_seguimiento) !!} </textarea>
                            @if ($errors->has('observacion_proximo_seguimiento'))
                                <span class="text-danger">{{ $errors->first('observacion_proximo_seguimiento') }}</span>
                            @endif
                        </div>

                        <input type="hidden" name="cotizacion_id" value="{{ $cotizacion->id }}">

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>

                    </div>
                </form>
            </div>


        </div>
    </div>


@endsection
@section('scripts')
    <script>
        ClassicEditor.create(document.querySelector('#observaciones'), {
                toolbar: {
                    items: [
                        'bold', 'italic', '|', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'undo', 'redo',
                    ],
                    shouldNotGroupWhenFull: true
                },
                link: {
                    decorators: {
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Abre en una ventana nueva',
                            defaultValue: true,
                            attributes: {
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor.create(document.querySelector('#observacion_proximo_seguimiento'), {
                toolbar: {
                    items: [
                        'bold', 'italic', '|', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'undo', 'redo',
                    ],
                    shouldNotGroupWhenFull: true
                },
                link: {
                    decorators: {
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Abre en una ventana nueva',
                            defaultValue: true,
                            attributes: {
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
