<?php

/**
 * Plugin Name: Vocento Analítica EsLaLiga
 * Description: Incluye la analítica para el contenido de la liga en el contenido patrocinado
 * Version: 1.0
 **/

add_action('wp_footer', 'la_liga_hook');
function la_liga_hook()
{
	$id = get_queried_object_id();


	$dispositivo = GET_DEVICE_ESLALIGA();
	$tags = wp_get_post_tags($id);
	foreach ($tags as $tag) {
		if ($tag->name == 'eslaliga') {
			echo mannagerTagCompositionEslaliga($id, $tags, $dispositivo);
		}
	}
}



function mannagerTagCompositionEslaliga($post_id, $tags, $dispositivo)
{
	$content_post = get_post($post_id);
	$totalTags = [];
	foreach ($tags as $tag) {
		array_push($totalTags, $tag->name);
	}
	$tagKw = implode(',', $totalTags);
	$title = str_replace("'", "", $content_post->post_title);
	$urlPublicacion = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$idContenido =  get_current_blog_id() . '_' . $post_id;
	$fechaCreacion = $content_post->post_date;
	$fechaPublicacion = $content_post->post_date;
	// $autor = get_the_author_meta( 'display_name', $content_post->post_author );
	$idioma = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$date = new DateTime($content_post->post_date_gmt);
	$timestamp = $date->format("U");


	$url_medio = 'http://' . $_SERVER['HTTP_HOST'];
	$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$res = valueByUrlMedio($post_id, $url_medio, 'url_medio');

	if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
		$mrf_canonical = str_replace('http://pre-content-factory.', 'https://www.', $res);
	} else {
		$mrf_canonical = str_replace('http://content-factory.', 'https://www.', $res);
	}

	if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
		$res = str_replace('http', 'https', $res);
		$mrf_canonical_content = str_replace($res, $mrf_canonical . '/contentfactory/post/', $actual_link);
	} else {
		$res = str_replace('http', 'https', $res);
		$mrf_canonical_content = str_replace($res, $mrf_canonical . '/contentfactory/post/', $actual_link);
	}

	$urlactual = $_SERVER[REQUEST_URI];
	$re = '/\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\//sm';
	$subst = "";
	$result = preg_replace($re, $subst, $urlactual);
	$result = $mrf_canonical . '/eslaliga/' . $result;

	// echo '<!-- ';
	// var_dump($actual_link);
	// var_dump($mrf_canonical);
	// var_dump($res);
	// var_dump($mrf_canonical_content);
	// var_dump($result);
	// echo ' -->';



	return "
		<script>
			document.addEventListener('DOMContentLoaded', (event) => {
				var contenido = document.querySelector('article');
				if( contenido !== null ){
			  
					const paragraphsVariable = document.querySelectorAll('.parrafocount').length;
					
					const contentParagraph = document.querySelectorAll('.parrafocount');
					let totalCharacters = '';
					contentParagraph.forEach( (e, index) => {
						totalCharacters = totalCharacters + ' ' + e.innerHTML;
					});
					const charactersVariable =  totalCharacters.length;
					
					const wordsVariable = totalCharacters.split(' ').filter(function(n) { return n != '' }).length;
					
					
					//var images = contenido.querySelectorAll('img');
					var images = contenido.querySelectorAll('img:not([class=\"imagenrelacionada\"])');
					var totalImg = 0;
					images.forEach( (e,index) => { 
						//const imageNumber = images.length - 44;
						if(index){
							let g = e.getAttribute('src');
							var n = g.includes('.svg');
							if( !g.includes('.svg') && !g.includes('logo') && g.includes('.') ){
								totalImg ++;
							}
						}
					});
					const totalImgVariable = totalImg;

					const tagKW = '" . $tagKw . "';
					const title = '" . $title . "';
					const urlPublicacion = '" . $result . "';
					const idContenido = '" . $idContenido . "';
					const fechaCreacion = '" . $fechaCreacion . "';
					const fechaPublicacion = '" . $fechaPublicacion . "';
					const autor = document.querySelector('.firmaAutor');
					const str = autor.dataset.autor;
					const res = str.split(' | ');
					const idioma = '" . $idioma . "';
					const timestamp = '" . $timestamp . "';

					const totalVideosBrigthcove = contenido.querySelectorAll('.videoCount');
					const totalVideosYoutube = contenido.querySelectorAll('.wpb_video_widget');
					const totalVideoVariable = totalVideosBrigthcove.length + totalVideosYoutube.length; 

					//  console.log('VARIABLE WORDS ', wordsVariable);
					//  console.log('VARIABLE CHARACTERS ', charactersVariable);
					//  console.log('VARIABLE PARAGRAPH ', paragraphsVariable);
					//  console.log('VARIABLE TOTAL IMG ', totalImgVariable);
					//  console.log('VARIABLE TOTAL VIDEO ', totalVideoVariable);
					//  console.log('VARIABLE KEY WORDS ', tagKW);
					//  console.log('VARIABLE TITAL ', title);
					//  console.log('VARIABLE URL ', urlPublicacion);
					//  console.log('VARIABLE ID CONTENIDO ', idContenido);
					//  console.log('VARIABLE FECHA CREACION', fechaCreacion);
					//  console.log('VARIABLE FECHA PUBLICACION ', fechaPublicacion);
					//  console.log('VARIABLE AUTOR ', autor);
					//  console.log('VARIABLE IDIOMA ', idioma);
					//  console.log('VARIABLE TIMESATAMP ', timestamp);
					// console.log('VARIABLE PLATAFORMA CONTENIDO ', plataformaContenido);

					TagManager.ready.then(function() {
						console.log('FIRMA AUTOR', autor.dataset.autor);
						TagManager.set({
							'titular': title,
							'url': urlPublicacion,
							'idNoticia': idContenido,
							'fechaPrimeraPublicacion': fechaPublicacion,
							'firmaAutor': res,
							'idioma': idioma,
							'keywords': tagKW,
							'numeroCaracteres': charactersVariable.toString(),
							'numeroPalabras': wordsVariable.toString(),
							'numeroParrafos': paragraphsVariable.toString(),
							'numeroImagenes': totalImgVariable.toString(),
							'numeroVideos': totalVideoVariable.toString(),
							'plataformaContenido': '" . $dispositivo . "',
						}).track();
					});
					
				}
			});
		</script>
	";
}


function GET_DEVICE_ESLALIGA()
{
	$device = 'contenido-web';
	if (IS_MOBILE_ESLALIGA()) {
		$device = 'm-responsive';
	} elseif (IS_TABLET_ESLALIGA()) {
		$device = 'm-responsive';
	} else {
		$device = 'contenido-web';
	}

	return $device;
}

function IS_MOBILE_ESLALIGA()
{
	$apple_phone = 'iPhone';
	$android_phone = '(?=.*\bAndroid\b)(?=.*\bMobile\b)';
	$amazon_phone = '(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)';
	$windows_phone = 'IEMobile';
	$ua = $_SERVER['HTTP_USER_AGENT'];

	return \mb_eregi($apple_phone, $ua) || \mb_eregi($amazon_phone, $ua) || \mb_eregi($android_phone, $ua) || \mb_eregi($windows_phone, $ua);
}

function IS_TABLET_ESLALIGA()
{
	$apple_phone = 'iPhone';
	$android_phone = '(?=.*\bAndroid\b)(?=.*\bMobile\b)';
	$amazon_phone = '(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)';
	$apple_tablet = 'iPad';
	$android_tablet = 'Android';
	$amazon_tablet = '(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)';
	$windows_tablet = '(?=.*\bWindows\b)(?=.*\bARM\b)';

	$ua =  $_SERVER['HTTP_USER_AGENT'];

	return (!\mb_eregi($apple_phone, $ua) && \mb_eregi($apple_tablet, $ua)) ||
		(!\mb_eregi($amazon_phone, $ua) && \mb_eregi($amazon_tablet, $ua)) ||
		(!\mb_eregi($amazon_phone, $ua) && !\mb_eregi($android_phone, $ua) &&
			(\mb_eregi($amazon_tablet, $ua) || \mb_eregi($android_tablet, $ua))) ||
		\mb_eregi($windows_tablet, $ua);
}
