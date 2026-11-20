$(document).ready(function() {
    $('#tableData').DataTable( {
        bFilter : false,
        dom: '<"top"i>rt<"bottom"p><"clear">',
        scrollY: 400,
        scrollX: true,
        columnDefs: [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        }, {
            targets: 'no-sort',
            orderData: false
        } ],
        language: {
            lengthMenu: "Display _MENU_ records per page",
            info: "Mostrando desde la candidatura _START_ hasta la  _END_ de _TOTAL_ candidaturas",
            paginate: {
                first:      "Primera",
                last:       "Ultima",
                next:       "Siguiente",
                previous:   "Anterior"
            },
        },
    } );
    $('.clickable-link').click(function(){
        window.location = $(this).data("href");
    })
} );

