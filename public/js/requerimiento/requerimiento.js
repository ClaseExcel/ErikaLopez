$('#estado_requerimiento').change(function () {
    documento = document.getElementById('documento');

    if ($(this).val() == 5) {
        documento.style.display = 'block';
    } else {
        documento.style.display = 'none';
    }
});
