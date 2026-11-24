// JavaScript Document
/*
NOTICIAS */
/*function altoImagenPrimeraNoticia() {
	jQuery(".vc_grid-loading").change(function(){
		altoTexto = jQuery(".page-template-page_home-noticias .vc_grid-item:first-child .vc_gitem-row-position-top:nth-child(2)").height();
		console.log("alto texto = "+altoTexto);
		(".page-template-page_home-noticias .vc_grid-item:first-child .vc_gitem-row-position-top:nth-child(1)").css({'height':altoTexto});
	});
	jQuery('.vc_grid-container').unbind().bind(function(event, data) {
	   altoTexto = jQuery(".page-template-page_home-noticias .vc_grid-item:first-child .vc_gitem-row-position-top:nth-child(2)").height();
		console.log("alto texto = "+altoTexto);
		(".page-template-page_home-noticias .vc_grid-item:first-child .vc_gitem-row-position-top:nth-child(1)").css({'height':altoTexto});
});
}
*/
function hamburguesaBlog() {
    jQuery(".hamburguesa-blog-contenedor").click(function(){
        if (jQuery(".hamburguesa-blog-contenedor").hasClass("activo")){
            jQuery(".hamburguesa-blog-contenedor").removeClass("activo");
            jQuery(".menu-blog-contenedor").removeClass("activo");
            // posicion del buscador en home
            jQuery(".page-template-page_home-noticias .formulario-busqueda").css({'top':posicionTop});
        } else {
            jQuery(".hamburguesa-blog-contenedor").addClass("activo");
            jQuery(".menu-blog-contenedor").addClass("activo");
            // posicion del buscador en home
            altoMenu = jQuery(".menu-blog-contenedor.activo ul").innerHeight();
            console.log("alto menu desplegado = "+altoMenu);
            posicionTopDespelgado = posicionTop + altoMenu;
            console.log("nueva posicion top buscador = "+posicionTopDespelgado);
            jQuery(".page-template-page_home-noticias .formulario-busqueda").css({'top':posicionTopDespelgado});
        }
    });
    jQuery(".js-wpml-ls-item-toggle.wpml-ls-item-toggle").click(function(){
        jQuery(".wpml-ls-legacy-dropdown .wpml-ls-sub-menu").css({'visibility':'visible'});
    });
}
//
function congresoAnos() {
    jQuery("li.anosAnteriores").click(function(){
        if (jQuery(".congresoAnos").hasClass("activo2")){
            jQuery("li.anosAnteriores").removeClass("activo");
            jQuery(".congresoAnos").removeClass("activo2");
        } else {
            jQuery("li.anosAnteriores").addClass("activo");
            jQuery(".congresoAnos").addClass("activo2");
        }
    });
}
//
function posicionTopBusqueda() {
    altoTitular = jQuery(".titularBlog").innerHeight();
    console.log("alto titular = "+altoTitular);
    altoMenu = jQuery(".menu-blog-contenedor").innerHeight();
    console.log("alto menu = "+altoMenu);
    //
    altoAutores = jQuery(".fondo-gris").innerHeight();
    altoAutoresTexto = jQuery(".fondo-gris.nombre-autores").innerHeight();
    altoAutoresTotal = altoAutores + altoAutoresTexto;
    console.log("alto autores = "+altoAutoresTotal);
    //
    anchoVentana = jQuery( window ).width();
    if (anchoVentana <=990) {
        posicionTop = altoTitular + altoMenu + altoAutoresTotal;
        altoBusqueda = jQuery(".formulario-busqueda").innerHeight();
        jQuery(".page-template-page_home-noticias .contenido-blanco").css({'padding-top':altoBusqueda});
    }
    else {
        posicionTop = altoTitular + altoMenu;
        jQuery(".page-template-page_home-noticias .contenido-blanco").css({'padding-top':'0'});
    }
	
    console.log("posicion top = "+posicionTop);
    //
    jQuery(".page-template-page_home-noticias .formulario-busqueda").css({'top':posicionTop});
}
//
function textoCampoFormulario() {
    textoDelBuscador = jQuery(".textoBuscador").text();
    console.log("texto del buscador = "+textoDelBuscador);
    jQuery("input.search-field").attr("placeholder",textoDelBuscador);
    jQuery("input.search-field").attr("value",textoDelBuscador);
    jQuery("input.search-field").attr("title",textoDelBuscador);
    jQuery(".screen-reader-text").text(textoDelBuscador);
}
//
function altoFotoCocinero() {
    anchoVentana = jQuery( window ).width();
    if (jQuery("body").hasClass("single-cocineros") && anchoVentana >=601){
        anchoImagen = jQuery(".cocinero-restaurante .post-thumbnail img").innerWidth();
        altoImagen = jQuery(".cocinero-restaurante .post-thumbnail img").innerHeight();
        console.log("ancho="+anchoImagen+" alto="+altoImagen);
        altoTexto = jQuery(".cocinero-restaurante .datosRestaurante").innerHeight();
        anchoContenedorImagen = jQuery(".cocinero-restaurante .post-thumbnail").innerWidth();
        margenImagenHorizontal = (anchoContenedorImagen-anchoImagen)/2;
        //margenImagenVertical = (altoImagen-altoTexto)/2;
        if (anchoImagen<=altoImagen) {
            jQuery(".cocinero-restaurante .post-thumbnail img").css({
                'width':anchoContenedorImagen,
                'height':'auto',
                //'margin-top':margenImagenVertical
            });
            jQuery(".cocinero-restaurante .post-thumbnail").css({
                'height':altoTexto,
                'overflow':'hidden'
            });
        }
        else {
            jQuery(".cocinero-restaurante .post-thumbnail img").css({
                'width':'auto',
                'height':altoTexto,
                'margin-left':margenImagenHorizontal
            });
        }
    } else {
        jQuery(".cocinero-restaurante .post-thumbnail img").removeAttr('style');
        jQuery(".cocinero-restaurante .post-thumbnail").removeAttr('style');
    }
}
//
function cierreContenedoresPrincipales() {
    if (jQuery("body").hasClass("single-recetas")){
        jQuery(".cierre-contenedores-principales").html("eoooo");
        console.log("borraaa");
	
    }
}
function altoFotoCocineroReceta() {
    anchoVentana = jQuery( window ).width();
    if (jQuery("body").hasClass("single-recetas") && anchoVentana >=769){
        altoImagenReceta = jQuery(".imagen-titulo-cocinero .post-thumbnail").innerHeight();
        altoTextoReceta = jQuery(".imagen-titulo-cocinero .entry-header").innerHeight();
        if(altoImagenReceta<altoTextoReceta) {
            jQuery(".cocineros-ficha-restaurante").css({
                'min-height':altoTextoReceta,
            });
        } 
        else if (altoImagenReceta>altoTextoReceta) {
            jQuery(".cocineros-ficha-restaurante").css({
                'min-height':altoImagenReceta,
            });
        }
    } else {
        jQuery(".cocineros-ficha-restaurante").removeAttr('style');
    }
}
function altoFotonoticiasHome() {
    anchoVentana = jQuery( window ).width();
    if (jQuery("body").hasClass("home") && anchoVentana >=769){
        altoCaja = jQuery(".contenido-gris.noticias-home .itemListadoCont:nth-child(5) .itemListado").innerHeight();
        console.log("alto caja "+altoCaja)
        jQuery(".contenido-gris.noticias-home .itemListadoCont:nth-child(4) .itemListado").css({
            'height':altoCaja,
        });
    }
    if (jQuery("body").hasClass("home") && anchoVentana <=768){
        jQuery(".contenido-gris.noticias-home .itemListadoCont:nth-child(4) .itemListado").css({
            'height':'auto',
        });
    }
}
//
function verVideoHome() {
    jQuery(".imagenFondo .boton button").click(function(){
        jQuery(".imagenFondo").addClass("video");
        jQuery( ".imagenFondo" ).html("<iframe src='https://player.vimeo.com/video/246775620?autoplay=1&title=0&byline=0&portrait=0' width='1250' height='703' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
    });
}
function buscadorExpandible() {
    jQuery(".site-content .formulario-busqueda.expandible .expandir").click(function(){
        jQuery(".site-content .formulario-busqueda.expandible").addClass("activo");
        jQuery('.site-content .formulario-busqueda.expandible .search-field').focus();
    });
    jQuery(".contMenu .formulario-busqueda.expandible .expandir").click(function(){
        jQuery(".contMenu .formulario-busqueda.expandible").addClass("activo");
        jQuery('.contMenu .formulario-busqueda.expandible .search-field').focus();
    });
}
function collapseSearchField() {
    jQuery(".search-field").on('blur', function() {
        jQuery(this).parent().parent().parent().removeClass("activo");
    });
}
//
function verVideosHome() {
    jQuery(".videosCont .swiper-slide,.videosCont .imagenFondo").click(function(){
        ID=jQuery(".id_video", this).text();
        console.log(ID);
    });
    /*jQuery(".videosCont .col-md-4.uno").click(function(){
		ID="198736328";
	});
	jQuery(".videosCont .col-md-4.dos").click(function(){
		ID="151188968";
	});
	jQuery(".videosCont .col-md-4.tres").click(function(){
		ID="116793620";
	});*/
    jQuery(".videosCont .swiper-slide").click(function(){
        jQuery(".videosCont.slide .cerrar").addClass("activo");
        jQuery(".videosCont.slide .videosDesplegado").addClass("activo");
        jQuery( ".videosCont.slide .videosDesplegado" ).html("<iframe src='https://player.vimeo.com/video/"+ID+"?autoplay=1&title=0&byline=0&portrait=0' width='1250' height='703' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
    });
    jQuery(".videosCont.pantallaCompleta .imagenFondo").click(function(){
        jQuery(".videosCont.pantallaCompleta .cerrar").addClass("activo");
        jQuery(".videosCont.pantallaCompleta .videosDesplegado").addClass("activo");
        jQuery( ".videosCont.pantallaCompleta .videosDesplegado" ).html("<iframe src='https://player.vimeo.com/video/"+ID+"?autoplay=1&title=0&byline=0&portrait=0' width='1250' height='703' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
    });
    jQuery(".videosCont .cerrar").click(function(){
        jQuery(".videosCont .cerrar").removeClass("activo");
        jQuery(".videosCont .videosDesplegado").removeClass("activo");
        jQuery( ".videosCont .videosDesplegado" ).html("");
    });
}
//
/*function etiquetasFichaCocinero() {
	if (jQuery("body").hasClass("single-recetas")){
		textoReceta=jQuery(".texto-receta").html()
		console.log(textoReceta);
		jQuery(".texto-receta").text(textoReceta);
		
		setTimeout(function(){ 
			jQuery(".texto-receta").text(function(buscayreemplaza, reemplaza) {
				return reemplaza.replace('&#60;strong&#62;Bizcocho tipo brownie:&#60;/strong&#62;', '} --></p> <p class="MsoNormal"><div class="entry-content borde-superior col-md-3">');
			});
		}, 1000);
		jQuery(".texto-receta").html(function(buscayreemplaza, reemplaza) {
			return reemplaza.replace('<strong><i>', '</div><div class="entry-content borde-superior col-md-9"><h2>');
		});
		jQuery(".texto-receta").html(function(buscayreemplaza, reemplaza) {
			return reemplaza.replace('</strong></i>', '</h2>');
		});
		jQuery(".texto-receta").html(function(buscayreemplaza, reemplaza) {
			return reemplaza.replace('<strong><i>', '<h2>');
		});
		jQuery(".texto-receta").html(function(buscayreemplaza, reemplaza) {
			return reemplaza.replace('</strong></i>', 'ESTO TAMBIEN LO PONGO YO</h2></div>');
		});
	}
}*/
//
function columnasReceta() {
    if (jQuery("body").hasClass("single-recetas")) {
        textoDeIngredientes = jQuery(".col-md-3").html();
        console.log("texto del ingrediente = "+textoDeIngredientes);
        if (textoDeIngredientes=="") {
            jQuery(".col-md-3").css({ 
                'background-color':'#cacaca',
            });
        }
        /*jQuery("input.search-field").attr("placeholder",textoDelBuscador);
		jQuery("input.search-field").attr("value",textoDelBuscador);
		jQuery("input.search-field").attr("title",textoDelBuscador);
		jQuery(".screen-reader-text").text(textoDelBuscador);*/
    }
	
}
// CENTRADO DE IMAGENES
/*function centradoImagenes() {
	console.log("centrado de imagenes");
	jQuery(".vc_gitem-link.vc_single_image-wrapper.vc_box_border_grey").each(function() { 
        // Altura del contenedor 
        alturaContImg = jQuery(this).innerHeight();
		console.log(alturaContImg);
		jQuery(this).addClass("imgVertical");
		/*jQuery(this).css({ 
				'height':alturaContImg,
			});*/
// Altura de la imagen 
//alturaImg = jQuery(this +" img").height();
/*if (alturaContImg==alturaImg) {
    jQuery(this).addClass("imgVertical");
} else if (alturaContImg>alturaImg) {
    jQuery(this).addClass("imgHorizontal");
}
}); 
}*/
//
function incluyeCaracteres() {
    jQuery(".search-form .search-submit").click(function(){
        caracteres = jQuery(".search-form .search-field").val();
        console.log(caracteres);
        idioma = jQuery("html").attr("lang")
        console.log(idioma);
        if(caracteres.length <= 3) {
            if (idioma == "es-ES") {
                // ALERTA
                alert("Escribe más de 3 caracteres");
                // ESCRIBIR EN EL CAMPO
                jQuery(".search-form .search-field").val("escribe más de 3 caracteres");
                setTimeout( function(){
                    jQuery(".search-form .search-field").val(caracteres);
                },2000);
            } else {
                // ALERTA
                alert("Write more than 3 characters");
                // ESCRIBIR EN EL CAMPO
                jQuery(".search-form .search-field").val("Write more than 3 characters");
                setTimeout( function(){
                    jQuery(".search-form .search-field").val(caracteres);
                },2000);
            }
        }
    });
}
//
function pestanasPrograma() {
    jQuery(".pestanas .fecha").click(function(){
        jQuery(".pestanas .fecha").removeClass("activo");
        jQuery(".programa").removeClass("activo");
        jQuery(this).addClass("activo");
        if (jQuery(".pestanas .fecha.dia1").hasClass("activo")){
            jQuery(".programa1").addClass("activo");
            jQuery(".dia1").addClass("activo");
        }
        if (jQuery(".pestanas .fecha.dia2").hasClass("activo")){
            jQuery(".programa2").addClass("activo");
            jQuery(".dia2").addClass("activo");
        }
        if (jQuery(".pestanas .fecha.dia3").hasClass("activo")){
            jQuery(".programa3").addClass("activo");
            jQuery(".dia3").addClass("activo");
        }
        if (jQuery(".pestanas .fecha.dia4").hasClass("activo")){
            jQuery(".programa4").addClass("activo");
            jQuery(".dia4").addClass("activo");
        }
    });
}
//
function programaAnchoEstrella() {
    anchoTotal = jQuery(".programa .col-md-10").width();
    anchoCirculo = jQuery(".programa .col-md-10 .circulo").width();
    anchoTexto = anchoTotal-anchoCirculo-10;
    jQuery(".programa .col-md-10 .datos").css({'width':anchoTexto});
}
//
function cabeceraCongresos() {
    rutaImagen = jQuery(".cabeceraCongresos img").attr('src');
    console.log(rutaImagen);
    jQuery(".cabeceraCongresos img").css({
        'opacity':'0',
        '-webkit-opacity':'0',
        '-moz-opacity':'0',
    });
    jQuery(".cabeceraCongresos .imagen").css({
        'background-image':'url('+rutaImagen+')',
        'background-position':'center top',
        'background-size':'auto 100%',
    });
    jQuery(".cabeceraCongresos .imagen a").css({
        'background-image':'none',
    });
}

//////////////////////
//////////////////////
//// menu idioma vars
terms_es=Array(
	'congreso',				//00
	'el-blog',				//01
	'cocineros', 			//02
	'recetas', 				//03
	'restaurantes',			//04
	////
	'seccion',				//05
	'letra',				//06
	'continente',			//07
	'pais',					//08
	////
	'europa',
	'africa',
	'america-del-norte',
	'america-del-sur',
	'asia',
	'oceania'			
	/////
);
terms_en=Array(
	'congress',				
	'the-blog',				
	'chefs', 				
	'recipes', 				
	'restaurants',			
	////
	'section',				
	'letter',				
	'continent',			
	'country',				
	////
	'europe',
	'africa',
	'north-america',
	'south-america',
	'asia',
	'oceania'		
	/////	
);

var url,link,trans,check_languaje_menu=false;
function vars_menu_idioma(){

    if(!check_languaje_menu){
        check_languaje_menu=true;
        var selector=jQuery('li[class^="wpml-ls-slot"]:not(.wpml-ls-current-language)')

        link=selector.find('a').attr('href')
        /// añado los parametros
        url=location.href;
        url=url.split('/');
        ///letra-letter
        link=set_link('5');
        link=set_link('6');
        link=set_link('7');
        link=set_link('8');
        //ano
        congreso=isset_in('congreso',url)+isset_in('congress',url); 
        if(congreso!=0){ ano=url[7]; if(ano.length===4&&ano.indexOf(20)===0){ link+=ano } }
        //page
        page=isset_in('page',url); if(page){link+='page/'+(url[++page])+'/'}

        ///////////////////////
        selector.find('a').attr('href',link)
        console.log(jQuery('li[class^="wpml-ls-slot"]:not(.wpml-ls-current-language)').find('a').attr('href'))
    }
	
}
function isset_in(what,where){
    for(i=0;i<where.length;i++) if(where[i] == what) return i ;
    return false;
}
function set_link(num){
    var_es=isset_in(terms_es[num],url); 
    if(var_es){link+=terms_en[num]+'/'+trans(url[++var_es],'en')+'/'}
    var_en=isset_in(terms_en[num],url); 
    if(var_en){link+=terms_es[num]+'/'+trans(url[++var_en],'es')+'/'}
    return link;
}
function trans(arg,idioma){
    if(idioma=='en'){ trans=terms_en[terms_es.indexOf(arg)];
    }else{ trans=terms_es[terms_en.indexOf(arg)];}
    return (trans!=null)?trans:arg;
}
///////////////////////
//////////////////////

jQuery(document).ready(function(){
    //altoImagenPrimeraNoticia();
    hamburguesaBlog();
    /* NO AUTORES */ // posicionTopBusqueda();
    //textoCampoFormulario();
    altoFotoCocinero();
    //cierreContenedoresPrincipales();
    //etiquetasFichaCocinero();
    altoFotoCocineroReceta();
    columnasReceta();
    altoFotonoticiasHome();
    //verVideoHome();
    verVideosHome();
    buscadorExpandible();
    collapseSearchField();
    congresoAnos();
    //incluyeCaracteres();
    pestanasPrograma();
    programaAnchoEstrella();
    cabeceraCongresos();
    setTimeout(function () { altoFotoCocinero(); }, 500);

    //////
    vars_menu_idioma()
	
});
jQuery(window).resize(function(){
    //altoImagenPrimeraNoticia()
    /* NO AUTORES */ // posicionTopBusqueda();
    altoFotoCocinero();	
    altoFotoCocineroReceta();
    altoFotonoticiasHome();
    programaAnchoEstrella();
});
jQuery(window).load(function(){
    //centradoImagenes();
    altoFotoCocinero();
    ///playVideo
    playVideo()
});

 ///////////////////////////////////////////////
///CUANDO PINCHAMOS EN LA IMAGEN DE UNA NOTICIA
function playVideo(){
    jQuery('.video-on.not_show').click(function(ev){
        window.location.href = jQuery(this).find('a').attr('href');
    })
    jQuery('.video-on:not(.not_show)').click(function(ev){
        console.log('gdfgdf');
        
        ev.preventDefault()
        ///set URL
        url_video=jQuery(this).attr('data-video')
        //vimeo
        if(url_video.indexOf('vimeo')>-1){
            array_url=url_video.split('/'); 
            iframe='<iframe src="https://player.vimeo.com/video/'+array_url[array_url.length-1]+'?autoplay=0" width="640" height="360" frameborder="0"  allow="autoplay; fullscreen" allowfullscreen></iframe>';
        } else if(url_video.indexOf('youtube')>-1){
            array_url=url_video.split('=');
            iframe='<iframe width="560" height="315" src="https://www.youtube.com/embed/'+array_url[array_url.length-1]+'?autoplay=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } else { 
            iframe='<video controls src="'+url_video+'"></video>';
        }
        ////
        jQuery(iframe).insertAfter('.video-noticia .close')
        ///animamos
        jQuery('.video-noticia').css({'display':'block'})
        jQuery('.video-noticia iframe').css({'opacity':'0','margin-top':'25%'})
        jQuery('.video-noticia').css({'opacity':'0'}).animate({'opacity':'1'},325,function(){
            jQuery('.video-noticia iframe').animate({'opacity':'1','margin-top':'0%'},300)
        })
    })
    jQuery('.video-noticia .close').click(function(){
        ///animamos
        jQuery('.video-noticia iframe').animate({'opacity':'0','margin-top':'-25%'},200,function(){
            jQuery('.video-noticia').animate({'opacity':'0'},350,function(){
                jQuery('.video-noticia').css({'display':'none'})
                jQuery('.video-noticia iframe,.video-noticia video').remove()
            })
        })
    })
}