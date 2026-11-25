!function ($) {
	//Aparezca la mano al pasar por paginacion
	$(document).ajaxStart(function() {
	    $('ul.paginacion li').css({'cursor': 'pointer'});
	}).ajaxStop(function() {
	    $('ul.paginacion li').css({'cursor': 'pointer'});
	});
	window.onload = function() {
		$('ul.paginacion li').css({'cursor': 'pointer'});
	}


	function art_pag_contenido_adicionales(page,id,slug){
	    var ajax_url = "/wp-admin/admin-ajax.php";
	    var ccs_class = $(".modulo-noticia").attr('class');
	    ccs_class = ccs_class.replace("modulo-noticia ","");

	    // Datos recibidos del servidor
	    var data = {
	    	class: ccs_class,
	    	id: id,
	        page: page,
	        slug: slug,
	        action: "art-pag-destacados-adicionales",
	    };
	    $.post( '/contentfactory/post/wp-content/ajaxrewrite.php', data, function(response){
	        $('.destacados-adicionales').html(response);
	    });
	}



	$(document).on('click', 'ul.paginacion li', function () {
	    var id = $(this).find('a').attr('data-postid');
	    var page = $(this).find('a').attr('data-page');
	    var slug = $(this).find('a').attr('data-slug');
	    art_pag_contenido_adicionales(page,id,slug);
	});



}(window.jQuery);