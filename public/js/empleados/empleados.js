$(document).ready(function() {
    // Inicializar el campo select múltiple con Select2
    $("#multiple-checkboxes").select2({
           dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
           containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
           closeOnSelect: false // Evitar que se cierre al seleccionar
       });
   // Agregar funcionalidad al botón "Seleccionar Todo"
   $("#selectAllButton").on("click", function() {
           $("#multiple-checkboxes").find("option").prop("selected", true);
           $("#multiple-checkboxes").trigger("change"); // Actualizar el select2 después de la selección
       });
   // Agregar funcionalidad al botón "Deseleccionar Todo"
   $("#deselectAllButton").on("click", function() {
           $("#multiple-checkboxes").find("option").prop("selected", false);
           $("#multiple-checkboxes").trigger("change"); // Actualizar el select2 después de la desselección
       });
    $("#empresas_secundarias").select2({
        dropdownCssClass: 'custom-dropdown', // Clase CSS personalizada para el menú desplegable
        containerCssClass: 'custom-container', // Clase CSS personalizada para el contenedor del select
        closeOnSelect: false // Evitar que se cierre al seleccionar
    });
    // Agregar funcionalidad al botón "Seleccionar Todo"
    $("#selectAllButtonEmpresasSecundarias").on("click", function() {
            $("#empresas_secundarias").find("option").prop("selected", true);
            $("#empresas_secundarias").trigger("change"); // Actualizar el select2 después de la selección
        });
    // Agregar funcionalidad al botón "Deseleccionar Todo"
    $("#deselectAllButtonEmpresasSecundarias").on("click", function() {
            $("#empresas_secundarias").find("option").prop("selected", false);
            $("#empresas_secundarias").trigger("change"); // Actualizar el select2 después de la desselección
        });
});