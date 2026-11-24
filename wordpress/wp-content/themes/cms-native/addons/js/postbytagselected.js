!function ($) {
    $('select[name="selectedtag"]').on('change', function(){ 
        // alert( $(this).find("option:selected").attr('value') ); 
        let idpost = $('.idpostseleccionado').val();
        if(idpost !== 0 || idpost !== '')
        {
            // Datos recibidos del servidor
            var data = {
                tag: $(this).find("option:selected").attr('value'),
                action: "post_bloque_tag_select_portada",
            };
            $.post( '/contentfactory/wp-content/ajaxrewrite.php', data, function(response){
                let html = '';
                response.forEach( (e) => {
                    html += '<option class="'+e.ID+'" value="'+e.ID+'">'+e.post_title+'</option>';
                })
                $('.postbytagselected').html(html);
            });
        }
	});


    let idpost = $('.idpostseleccionado').val();
    if(idpost !== 0 || idpost !== '')
    {
        // Datos recibidos del servidor
	    var data = {
	        id: idpost,
	        action: "getPostForSelectorPortada",
        };
	    
	    $.post( '/contentfactory/wp-content/ajaxrewrite.php', data, function(response){
            console.log('RESPUESTA ID POST',response);
            let html = '<option class="'+response.ID+'" value="'+response.ID+'">'+response.post_title+'</option>';
            // let html = '';
            // response.forEach( (post) => {
            //     html += '<option class="'+response.ID+'" value="'+response.ID+'">'+response.post_title+'</option>';
            // });
	        $('.postbytagselected').html(html);
	    });
    }




}(window.jQuery);