$(document).ready(function () {

Voonto.require('cookies').then(function(Cookies) {
	//Cookies.remove('women_newsletter');
	console.log('comprobar newsletter');

	console.log('cookieNewsletter:'+Cookies.get('women_newsletter'));
	console.log('capa:'+jQuery('.wrapperNewsletter').length);

	if(!Cookies.get('women_newsletter')&&jQuery('.wrapperNewsletter').length){
		console.log('mostrando modal newsletter')
		jQuery('.wrapperNewsletter').removeClass('hide')
		var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
		Cookies.set('women_newsletter', 'ok', { expires: inFifteenMinutes });
	}
})



	var tipoDispositivo = getTipoDispositivo();

	if(tipoDispositivo == "mobile"){

		jQuery('#megabanner-id').appendTo('.sticky-footer');
		$('.voc-advertising__close').click(function() {
			$('.sticky-footer').hide();
		});
		jQuery(".sticky-footer").css({"z-index" : "10000"});
		jQuery(".o-section").css({"z-index" : "unset"});

		if(jQuery('.post-type-podcast').length !== 0){
			jQuery('#robapaginas-id').appendTo('.robapaginas-mobile');
			jQuery('#robapaginas_dos-id').appendTo('.robapaginas-dos-mobile');
		}
		if(jQuery('.post-type-video').length !== 0){
			jQuery('#robapaginas-id').appendTo('.robapaginas-mobile');
			jQuery('#robapaginas_dos-id').appendTo('.robapaginas-dos-mobile');
		}
		if(jQuery('.post-type-noticia').length !== 0){
			jQuery('#robapaginas-id').appendTo(jQuery('.c-detail__body p')[1]);
			jQuery('#boton_native-id').appendTo(jQuery('.c-detail__body p')[2]);
			if(jQuery('.c-detail__body p').length < 5){
				jQuery('#robapaginas_dos-id').appendTo(jQuery('.c-detail__body p')[jQuery('.c-detail__body p').length]);
			}else{
				jQuery('#robapaginas_dos-id').appendTo(jQuery('.c-detail__body p')[4]);
			}

		}


	}else{
		jQuery('.sticky-footer').detach();
		if (jQuery('.video-player').length){
			jQuery('.video-player').attr('data-voc-video-player-autoplay', "true");
			jQuery('.video-player').attr('data-voc-video-player-muted', "true");
		}
		
	}
});


function getTipoDispositivo() {
	var width = screen.width;
	if (width < 415) {
		return "mobile";
	} else if (width >= 415 && width <= 769) {
		return "tablet";
	} else {
		return "desktop";
	}
}




