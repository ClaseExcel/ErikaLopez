$(function() {


    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
        className: 'btn border-0 rounded py-0 '
    })
    
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": -1 }
        ],
        order: [],
        scrollX: false,
        pageLength: 10,        
        dom: 'lBfrtip',
    });

    $.fn.dataTable.ext.classes.sPageButton = '';
    
});