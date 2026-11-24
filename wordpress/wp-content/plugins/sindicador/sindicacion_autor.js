jQuery(document).ready(function($) {

    jQuery( "#sindicacionBlogs" ).click(function(evt) {

    	var security = jQuery('#ajax_admin_options_nonce').text();
    	jQuery('#sindicacionBlogs').hide('slow', function() {
    		
    	});

    	var contenidoAjax = jQuery('#mensajeSindicar').html('<p>Ejecutando la sindicacion...</p>');
    	// Creamos el objecto
	  	var data = {
		    action: 'sindicacion_data_action', // esta es la acción que usamos arriba como alias para wp_ajax
	  	}

		  // ajaxurl es una variable por defecto que utiliza wordpress
		  // para hacer referencia a '/wp-admin/admin-ajax.php'
		jQuery.post(ajaxurl, data, function(response) {
	    
	    /*
	     Lo valores correspondiente a la respuesta son 0, 1, y -1 localizados en ajax_save_data().
	    */
	    	contenidoAjax.html('<p>Sindicación finalizada correctamente</p>');
	    	jQuery('#sindicacionBlogs').show('slow', function() {
	    		
	    	});

	   
		    // Ahora condicionamos la respuesta del servidor
		    if( response == 0) {
		      // La solicitud se ha realizado correctamente
		    } else if( response == 1 ) {
		      // La solicitud se ha realizado correctamente, pero el almacenamiento de datos no
		    } else if( response == -1 ) {
		      // Error en la solicitud del control de seguridad para el nonce
		    }
		});

	});



         
});