<?php

//Embeber post de instagram
function shortcode_add_instagram_exportacion($insta_url)
{
	$blockInstagram=
		'<blockquote title="cualquier titulo Instagram" class="instagram-media" data-instgrm-permalink="' . $insta_url[0] . '" data-instgrm-version="13" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
			<div style="padding:16px;"> 
				<a href="' . $insta_url[0] . '" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank"> 
					<div style=" display: flex; flex-direction: row; align-items: center;"> 
						<div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div> 
						<div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;"> 
							<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> 
							<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div>
						</div>
					</div>
					<div style="padding: 19% 0;"></div>
					<div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
						<svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-511.000000, -20.000000)" fill="#000000">
									<g>
										<path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
										</path>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<div style="padding-top: 8px;"> 
						<div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;"> Ver esta publicación en Instagram</div>
					</div>
					<div style="padding: 12.5% 0;"></div>
					<div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
						<div> 
							<div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> 
							<div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> 
							<div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div>
						</div>
						<div style="margin-left: 8px;"> 
							<div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> 
							<div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div>
						</div>
						<div style="margin-left: auto;"> 
							<div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> 
							<div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> 
							<div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div>
						</div>
					</div> 
					<div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;"> 
						<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;"></div> 
						<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;"></div>
					</div>
				</a>
				<p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
					<a href="' . $insta_url[0] . '" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;" target="_blank">Publicación compartida
					</a>
				</p>
			</div>
		</blockquote> 
	
		<script async src="//www.instagram.com/embed.js"></script>';

		return $blockInstagram;
}

add_shortcode('addinstagram_exportacion', 'shortcode_add_instagram_exportacion');


/**Embeber Video de Youtube
 * Función que se encarga de añadir el código necesario para el embeber Video de Youtube, mediante un shortcode y diferentes atributos que se encargan de cambiar algunos atributos del reproductor
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				valor de la url del video a embeber
 *  			$atributos['title']				titulo del video a embeber
 *  			$atributos['loop']				valor 1 el reproductor reproduce el video inicial una y otra vez - debe ir acompañado del atributo de playlist
 *  			$atributos['playlist']			lista separada por comas de ID de videos a mostrar al finalizar el video inicial. Si se desea solo un video se deber enviar el id del mismo video o no informar éste atributo
 *  			$atributos['controls']			indica si se muestran o no los controles del reproductor de video. valor 0 no se muestra. 1 y 2 se muestran, 2 mejora el rendimiento en Iframe
 *  			$atributos['showinfo']			si se establece el valor del parámetro en 0, el reproductor no muestra información como el título del video o el usuario que lo subió antes de comenzar la reproducción.
 *  			$atributos['color']				especifica el color que se utiliza en la barra de progreso del reproductor para destacar la cantidad de video ya vista por el usuario.
 *  			$atributos['modestbranding']	permite usar un reproductor de YouTube sin que se muestre un logotipo de YouTube. Establece el valor de parámetro en 1 para evitar que se muestre el logotipo de YouTube en la barra de control. Ten en cuenta que se seguirá mostrando una etiqueta pequeña de YouTube en la esquina superior derecha de un video en pausa cuando el cursor del mouse del usuario se desplace sobre el reproductor. Configurar el parámetro color en white desactiva la opción modestbranding.	
 * @return string del código necesario para embeber el Video de Youtube
*/
function shortcode_add_youtube_exportacion($atributos)
{
	
	$atributos = shortcode_atts ( array (
		'url' 		=> 'https://www.youtube.com/embed/Yl81OLIp2QI',
		'title' 	=> 'Youtube WON',
		'loop' 		=> 1,		//valor 1 el reproductor reproduce el video inicial una y otra vez - debe ir acompañado del atributo de playlist
		'playlist' 	=> '', 		//lista separada por comas de ID de videos a mostrar al finalizar el video inicial. Si se desea solo un video se deber enviar el id del mismo video o no informar éste atributo
		'controls' 	=> 2,		//indica si se muestran o no los controles del reproductor de video. valor 0 no se muestra. 1 y 2 se muestran, 2 mejora el rendimiento en Iframe
		'showinfo' 	=> 0,		//si se establece el valor del parámetro en 0, el reproductor no muestra información como el título del video o el usuario que lo subió antes de comenzar la reproducción.
		'color'		=> 'white',	//especifica el color que se utiliza en la barra de progreso del reproductor para destacar la cantidad de video ya vista por el usuario
		'modestbranding' => 1	//permite usar un reproductor de YouTube sin que se muestre un logotipo de YouTube. Establece el valor de parámetro en 1 para evitar que se muestre el logotipo de YouTube en la barra de control. Ten en cuenta que se seguirá mostrando una etiqueta pequeña de YouTube en la esquina superior derecha de un video en pausa cuando el cursor del mouse del usuario se desplace sobre el reproductor. Configurar el parámetro color en white desactiva la opción modestbranding. 
		), $atributos );

	$atributos['playlist'] = $atributos['playlist']!=''?$atributos['playlist']:preg_replace('/[^z-z]*(embed\/\b)/i', '', $atributos['url']);

	$video_yotube = '<iframe width="100%" height="410" src="'.$atributos['url'].'?color='.$atributos['color'].'&controls='.$atributos['controls'].'&showinfo='.$atributos['showinfo'].'&loop=1&playlist='.$atributos['playlist'].'&modestbranding='.$atributos['modestbranding'].'" title="'.$atributos['title'].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

	return $video_yotube;
}

add_shortcode('addyoutube_exportacion', 'shortcode_add_youtube_exportacion');

//Embeber post de twitter
function shortcode_add_twitter_exportacion($atributos)
{
	$url = "https://publish.twitter.com/oembed?url=".$atributos[0];
	try
	{
		// $json = file_get_contents($url, 0, stream_context_create(["https"=>["timeout"=>10]]));
        $context = stream_context_create(["ssl" => [
            "timeout"          =>10,
            "verify_peer"      => false,
            "verify_peer_name" => false]
          ]);
        $json = file_get_contents($url, 0, $context);
          
		if(false === $json){
			$tweet = '';
		}else{
			$data = json_decode($json,true);
			$tweet =  $data['html'];
		}
	}
	catch (Exception $e)
	{
		$tweet = '';
	}


	return $tweet;
		
}

add_shortcode('addtwitter_exportacion', 'shortcode_add_twitter_exportacion');


/*Embeber post de tiktok
 * Función que se encarga de añadir el código necesario para el embeber Post de TikTok, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				Valor de la url del post a embeber. Valor obligatorio a informar
 *  			$atributos['width']			    Ancho de la publicación. Mínimo de 325 píxeles y máximo de 605. Déjalo en blanco si quieres aplicar un ancho variable. Valor por defecto 500px
 * @return string del código necesario para embeber el Post de TikTok
 * Ejemplo shortcode: [addtiktok url="https://www.tiktok.com/@latoneira/video/7063920006995791109" width=500]
 */
function shortcode_add_tiktok_exportacion($atributos)
{
	$urlPost = $atributos['url'];
	$idPost = explode( '/', $urlPost );
	$i = count($idPost)-1;
	$idPost = $idPost[$i];
	$widthPost = $atributos['width']?$atributos['width']:605;
	$tiktok = '';

	if ($urlPost){
		$tiktok .= '<blockquote title="Tiktok WON" class="tiktok-embed" cite="'.$urlPost.'" data-video-id="'.$idPost.'" style="max-width: '.$widthPost.'px;min-width: 325px;" >';
		$tiktok .= '<section>';
		$tiktok .= '</section>';
		$tiktok .= '</blockquote>';
		$tiktok .= '<script async src="https://www.tiktok.com/embed.js"></script>';
	}
	return $tiktok;
		
}

add_shortcode('addtiktok_exportacion', 'shortcode_add_tiktok_exportacion');


/**Embeber Post de Facebook
 * Función que se encarga de añadir el código necesario para el embeber Post de Facebook, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				Valor de la url del post a embeber. Valor obligatorio a informar
 *  			$atributos['datalazy']			True significa que se utilizará el mecanismo de carga diferida del navegador al establecer el atributo iframe en loading="lazy". Valor por defecto true
 *  			$atributos['datawidth']			Ancho de la publicación. Mínimo de 350 píxeles y máximo de 750. Déjalo en blanco si quieres aplicar un ancho variable. Valor por defecto 500px
 *  			$atributos['datashowtext']		Se aplica a las publicaciones con foto. Establécelo en true para incluir el texto de la publicación de Facebook, si lo hay. Valor por defecto true.
 * @return string del código necesario para embeber el Post de Facebook
 * Ejemplo shortcode: [addfacebook url="https://www.facebook.com/welifefestival/posts/333712615368647" datashowtext=false]
*/


function shortcode_add_facebook_exportacion($atributos){

	$urlPost = $atributos['url'];
	$dataLazy = $atributos['datalazy'];
	$dataLazy = $dataLazy?$dataLazy:true;
	$dataWidth = $atributos['datawidth'];
	$dataWidth = $dataWidth?$dataWidth:750;
	$datashowText = $atributos['datashowtext'];
	$datashowText = $datashowText?$datashowText:true;

	$fb_insert = '';

	if ($urlPost){

		$fb_insert= '<div class="shortcode-facebook">';
		$fb_insert.= '<div id="fb-root"></div>';
		$fb_insert.= '<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v13.0" nonce="o62H2nYO"></script>';
		$fb_insert.= '<div class="fb-post" data-href="'.$urlPost.'" data-width="'.$dataWidth.'" data-show-text="'.$datashowText.'">';
		$fb_insert.= '<blockquote title="Facebook WON" cite="https://www.facebook.com/welifefestival/posts/pfbid02ZRjZCqV7ZHTcndG12Q6NwANQHm5bRveBPEE1m9wfnDhbfAomMS6BfYonJkqneLwul" class="fb-xfbml-parse-ignore">';
		$fb_insert.= '</blockquote></div>';
		$fb_insert.= '</div>';
	}

	return $fb_insert;
}

add_shortcode('addfacebook_exportacion', 'shortcode_add_facebook_exportacion');


/*Embeber video de brightcove
 * Función que se encarga de añadir el código necesario para el embeber Video de brightcove, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['id']				Valor del id del video a embeber. Valor obligatorio a informar
 * 				$atributos['autoplay']			Opción para tener activo o no el autoplay del video. Valor opcional
 * @return string del código necesario para embeber el Video de brightcove
 * Ejemplo shortcode: [addbrightcove id="6286777721001" autoplay="false"]
 */
function shortcode_add_brightcove_exportacion($atributos)
{
	$idVideo = $atributos['id'];
	$autoplay = $atributos['autoplay']?$atributos['autoplay']:'true';
	
	$brightcove = '';

	$brightcove = '	<div style="display: block; position: relative; margin-bottom:20px">
						<div style="padding-top: 56.25%;">
							<iframe src="//players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId='.$idVideo.'&autoplay='.$autoplay.'" allowfullscreen webkitallowfullscreen mozallowfullscreen autoplay style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;" title="Video '.$idVideo.'">
							</iframe>
						</div>
					</div>
					<div style="display:none">
						<a href="https://players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId='.$idVideo.'&autoplay='.$autoplay.'"></a>
						en 
						<a href="https://www.womennow.es/videos" title="Ver Vídeos">Vídeos</a>
					</div>';

	return $brightcove;
		
}

add_shortcode('addbrightcove_exportacion', 'shortcode_add_brightcove_exportacion');