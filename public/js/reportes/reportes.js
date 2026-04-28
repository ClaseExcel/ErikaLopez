$('#empresa').change(function () {
    let empresa = $(this).val();
    $('#usuario').prop("disabled", false);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'GET',
        url: 'informe-empresa/usuario/' + empresa,
        success: function ($responsable) {

            let responsable = JSON.parse($responsable);

            $('#usuario').empty();

            $("#usuario").append('<option value="">Seleccione un usuario</option>');

            $.each(responsable, function (index, value) {
                $("#usuario").append('<option value=' + value.id + '>' + value
                    .nombres + ' ' + value.apellidos + '</option>');
            });
        }
    })
});
