@extends('layouts.admin')
@section('title', 'Actualizar gestión')
@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <div class="form-group">
        <a class="btn btn-back  border btn-radius px-4" href="{{ route('admin.gestiones.index') }}">
            <i class="fas fa-arrow-circle-left"></i> Atrás
        </a>
    </div>


    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i> Actualizar gestión
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.gestiones.update', $gestion->id) }}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.gestiones.fields')

                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

     ClassicEditor
            .create(document.querySelector('#detalle_visita'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                        'removeFormat', '|', 'link', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'undo', 'redo',
                        '-',
                        'alignment', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
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

     ClassicEditor
            .create(document.querySelector('#compromisos'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                        'removeFormat', '|', 'link', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'undo', 'redo',
                        '-',
                        'alignment', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
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


             ClassicEditor
            .create(document.querySelector('#hallazgos'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                        'removeFormat', '|', 'link', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'undo', 'redo',
                        '-',
                        'alignment', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
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

            ClassicEditor
            .create(document.querySelector('#compromisos_cliente'), {
                toolbar: {
                    items: [
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                        'removeFormat', '|', 'link', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'undo', 'redo',
                        '-',
                        'alignment', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
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