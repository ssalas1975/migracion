jQuery(document).ready(function($) {

    jQuery( "#genereasxmlWOW" ).click(function(evt) {

		let postType = document.querySelector('#posttype').value;
		let numeropost = document.querySelector('#numeropost').value;
		var totalPosts = 0;
		var contador = 0;

		var data = {
		    action: 'xml_count_post',
			postType: postType,
	  	}

		  jQuery('#genereasxmlWOW').hide('slow', function() { });
		  var contenidoAjax = jQuery('#mensajeWOW').html('<p>Ejecutando la sindicacion...</p>');

		jQuery.post(ajaxurl, data, function(response) {
			var security = jQuery('#ajax_admin_options_nonce').text();
			totalPosts = response.publish;
			generarXML(postType, numeropost, totalPosts, contador)
		});

		
	});

	function downloadXML()
	{
		var data = { action: 'download_xml_action' }
		jQuery.post(ajaxurl, data, function(response) {});
	}

	jQuery( "#generearzipWOW" ).click(function(evt) {
		var data = { action: 'download_xml_action' }
		jQuery.post(ajaxurl, data, function(response) { 
			jQuery('#descarga').show('slow', function(response) {
				console.log('Se ha descargado todo', response);
			}); 
		});
	});


	function generarXML(postType, numeropost, totalpost, offset = 0)
	{
		
	  	var data = {
		    action: 'xml_data_action',
			postType: postType,
			numeropost: numeropost,
			offset: offset, 
			totalpost: totalpost
	  	}
		
		jQuery.post(ajaxurl, data, function(response) {
			console.log('RESPONSE', response);
		    if( response[4] == 0) {
				var contenidoAjax = jQuery('#mensajeWOW');
				contenidoAjax.html('<p>Generación finalizada correctamente</p>');
				jQuery('#genereasxmlWOW	').show('slow', function() {});
		    } else if( response[4] === 1 ) {
			  generarXML(response[0], response[1], response[2], response[3]);
		    }
		});

	}



         
});