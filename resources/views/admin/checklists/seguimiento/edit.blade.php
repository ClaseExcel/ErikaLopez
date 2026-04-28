@extends('layouts.admin')
@section('title', 'Editar seguimiento de checklist contable')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-back  border btn-radius" onclick="location.href='{{ route('admin.checklist_empresas.show', $seguimiento->checklist_empresa_id) }}'">
                <i class="fas fa-arrow-circle-left"></i> Atrás
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-list-check"></i> Editar seguimiento de checklist contable
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.seguimiento_checklist.update', $seguimiento->id) }}">
                        @csrf
                        @method('PUT')
    
                        @include('admin.checklists.seguimiento.fields') 
                           
                        <div class="form-group text-end">
                            <button class="btn btn-save btn-radius px-4" type="submit">
                                 Guardar
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
        $(document).ready(function() {
            $("#multiple-checkboxes4").select2({
                dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
                containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
                closeOnSelect: false // Evitar que se cierre al seleccionar
            });
            // Agregar funcionalidad al botón "Seleccionar Todo"
            $("#selectAllButton4").on("click", function() {
                $("#multiple-checkboxes4").find("option").prop("selected", true);
                $("#multiple-checkboxes4").trigger(
                "change"); // Actualizar el select2 después de la selección
            });
            // Agregar funcionalidad al botón "Deseleccionar Todo"
            $("#deselectAllButton4").on("click", function() {
                $("#multiple-checkboxes4").find("option").prop("selected", false);
                $("#multiple-checkboxes4").trigger(
                "change"); // Actualizar el select2 después de la desselección
            });
        });
    </script>
@endsection