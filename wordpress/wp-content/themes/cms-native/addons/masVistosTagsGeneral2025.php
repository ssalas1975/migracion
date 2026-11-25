<?php


/******* MAS VISTOS O RELACIONADOS *******/

add_shortcode( 'vocento_mas_vistos_tags_general_2025', 'vocento_mas_vistos_tags_general_2025_function' );
function vocento_mas_vistos_tags_general_2025_function( $atts, $content ) {
    // Attributes
	$atts = shortcode_atts(
		array(
			'max_items' => '',
			'tag_name' => '',
			'el_class' => '',
            'height' => '',
            'perpage' => '',
            'permove' => '',
            'pagination' => '',
            'cover' => '',
            'arrows' => '',
            'flechaprev' => '',
            'flechanext' => ''
		),
		$atts,
		'post_relacionados'
	);

    $tags = explode(",",$atts['tag_name']);
    array_pop($tags);
    $NameOfTag = "";
    $idTags = [];
    foreach( $tags as $tag) {
        $tag = get_term_by('name', $tag,'post_tag');
        $NameOfTag = $tag->name;
        array_push($idTags, $tag->term_id);
    }


$flechaprev = $atts['flechaprev'];
if($flechaprev == ""){
    $flechaprev = "/contentfactory/post/wp-content/uploads/sites/4/2024/04/arrow-prev.svg";
}
$flechanext = $atts['flechanext'];
if($flechanext == ""){
    $flechanext = "/contentfactory/post/wp-content/uploads/sites/4/2024/04/flecha-next.svg";
}

    // Attributes in var
	// $swipperpost = $atts['postswipper'];
    $height = $atts['height'];
    $perpage = $atts['perpage'];
    $permove = $atts['permove'];
    $arrows = $atts['arrows'];
	// $swiper = get_field('swiper', intval($swipperpost));
    $claseextra = $atts['el_class'];

    if($claseextra === "" || $claseextra == null){
        $claseextra = 'splide-re-1';
    }




    global $post;
    $host = str_replace("content-factory.","",$_SERVER['HTTP_HOST']);
    $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';
    preg_match_all($reHost, $host , $machesHost);

    $tag_ids = array();
    // foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

    $args=array(
        //'tag__in' => $tag_ids,
        'post__not_in' => array($post->ID),
        'posts_per_page'=>$atts['max_items'], // Number of related posts to display.
        'caller_get_posts'=>1,
        'post_status '      => 'publish',
        'update_post_meta_cache' => false,
        'cache_results'  => false,
        'no_found_rows' => true,
        'update_post_term_cache' => false,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'Medios',
                'field'    => 'slug',
                'terms'    => $machesHost[0][0],
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $idTags,
                'operator' => 'IN',
            ),
        ),
    );

    $my_query = new wp_query( $args );

    // var_dump($my_query->posts);
    // die('gggggg');






    $html = "";

    if ($tags) {

        $contador = 1;

        $style = "
        <style>
        @import url('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');
        /* section relacionados nuevo */
        .relacionados-splide{width: 100%; position: relative; top: -50px; float: left;}
        .cabecera-relacionados{display: flex; justify-content: space-between; position: relative; margin-bottom: 32px; padding: 0 10%;}
        .cabecera-relacionados .titulo-relacionados{font: 400 31px/34px 'PPeiko'; color: white;}
        body .splide__arrow{background: transparent;}
        .splide__arrows button img{width: 20px; border: none;}
        body .splide__arrow--prev{right: 5em; left: unset;}
        .splide__arrow--next {right: 3em;}
        .splide__arrow--prev img{position: relative; top: -2px;}
        .splide__slide{display: flex;align-items: end; border-radius: 20px; max-width: 350px;}
        .relacionado-texto{width: 100%;}
        .relacionado-texto a{background-color: #0082c991;backdrop-filter: blur(10px);display: flex;justify-content: flex-start;align-items: center;flex-direction: column;padding: 12px;min-height: 148px;border-radius: 0 0 20px 20px;}
        .relacionado-texto h4{ font: 200 12px/18px 'Arboria'; text-transform: uppercase; text-align: center; color: white; margin-bottom: 8px;}
        .relacionado-texto h3{ font: 400 21px/27px 'PPeiko'; text-transform: none; text-align: center; color: white; margin-bottom: 8px;}
        .relacionado-texto .flecha-relacionados{ border: none; position: absolute; bottom: 20px;}
        body .splide__slide img {border-radius: 0 0 20px 20px;}
        .relacionado-texto a{min-height:188px}
        .relacionado-texto .flecha-relacionados{max-width:28px}
        </style>
        ";

    

        if($height !== null && $height !== ""){
            $height = $height;
        }else{
            $height = '500';
        }
        if($perpage !== null && $perpage !== ""){
            $perpage = $perpage;
        }else{
            $perpage = '3';
        }
        if($permove !== null && $permove !== ""){
            $permove = $permove;
        }else{
            $permove = '1';
        }
        if($arrows !== null && $arrows !== ""){
            $arrows = $arrows;
        }else{
            $arrows = 'true';
        }


        $script = "
        <script>

            var splide = new Splide( '.".$claseextra."', {
                height: '".$height."px',  // NECESARIO
                perPage: ".intval($perpage).",  // NECESARIO
                perMove: ".intval($permove).",  // NECESARIO
                pagination: false,
                cover: true, 
                arrows: ".boolval($arrows).",  // NECESARIO
                padding: '10%',
                gap: '20px',
                breakpoints: {
                750: {
                    perPage: 2,
                },
                500: {
                    perPage: 1,
                }
                }
            });
            splide.mount();
        </script>
        ";

		$html .= $style.'
		<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
		<section class="splide '.$claseextra.'" aria-label="Splide Basic HTML Example">
			<div class="cabecera-relacionados">
				<div class="splide__arrows">
					<button class="splide__arrow splide__arrow--prev">
						<img src="'.$flechaprev.'" alt="flecha prev">
					</button>
					<button class="splide__arrow splide__arrow--next">
						<img src="'.$flechanext.'" alt="flecha next">
					</button>
				</div>
			</div>
			<div class="splide__track">
				<ul class="splide__list">
		';
        // var_dump($my_query->posts);
		foreach ($my_query->posts as $row) {
// var_dump($row);
            $titulo1 = get_post_meta( $row->ID, 'titulo1', true );
            $texto= get_post_meta( $row->ID, 'texto', true );
            $cintillo = get_post_meta( $row->ID, 'cintillo', true );

            $IDimg =  get_post_meta( $row->ID, 'imgHeader', true );
            $imgOpen = get_post_meta( $row->ID, '_webdados_fb_open_graph_specific_image', true );
            $colorCintillo =  get_post_meta( $row->ID, 'colorCintillo', true );
            $autor = get_post_meta( $row->ID, 'autor', true );

            $enlace = str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink($row->ID));

            $imgAutor = get_post_meta( $row->ID, 'image_url', true );

        //     var_dump($row->ID);
        //  var_dump($imgOpen);
        //  var_dump(wp_get_attachment_url($imgOpen));
        //  die('55555');

        $imgOpen = str_replace("https://ms-brandedcontent.srv.vocento.in", "", $imgOpen);


			$html .= '
			<li class="splide__slide">
				<img src="/contentfactory/post'.$imgOpen.'" />
				<div class="relacionado-texto">
					<a href="'.$enlace.'">
						<h4>'.$NameOfTag.'</h4>
						<h3>'.$titulo1.'</h3>
						<img src="/contentfactory/post/wp-content/uploads/sites/4/2024/04/flecha-rel.svg" alt="flecha"
							class="flecha-relacionados">
					</a>
				</div>
			</li>
			';
		}

		$html .= '
					</ul>
			</div>
		</section>
        '.$script;

    }

	return $html;



}


/******* FUNCIONES EXTRA PARA METADATOS DE RELACIONADOS *******/


//extraemos los datos de la cadena para insertarlos en metadatos

function extraeDatosHeaderTagsGeneral2025($contenido){
    $r = explode('class=\'p-content\'>', $contenido);
    if (isset($r[1])){
        $r = explode('</span></a>', $r[1]);
        $devuelve['cintillo'] = $r[0];
        $devuelve['colorCintillo'] = "p-content";
        $r1 = explode('<h1><strong>', $r[1]);
    }else{
        $r = explode('class=\'p-content-gris\'>', $contenido);
        $r = explode('</span>', $r[1]);
        $devuelve['cintillo'] = $r[0];
        $devuelve['colorCintillo'] = "p-content-gris";
        $r1 = explode('<h1><strong>', $r[2]);
    }
    if (isset($r1[1])){
        $r1 = explode('</strong>', $r1[1]);
        $devuelve['titulo1'] = $r1[0];
        $r2 = explode('<p>', $r1[1]);
    }
    if (isset($r2[1])){
        if(isset($r2[2])){
            $auxImg = $r2[2];
            $r2 = explode('</p>', $r2[1]);
            $devuelve['texto'] = $r2[0];
            $r3 = explode('wp_get_attachment_url(',$auxImg);
            $r4 =  explode('<figure ><img src=\'', $auxImg);
            $r3 = explode(')', $r3[2]);
            $imgh1 = $r3[0];
        }else{
            $auxImg = $r2[1];
            $r2 = explode('</p>', $r2[1]);
            $devuelve['texto'] = $r2[0];
            $r3 = explode('wp_get_attachment_url(',$auxImg);
            $r3 = explode(')', $r3[1]);

            if(!isset($r3[1])){
                $r3 =  explode('<img src=\'', $auxImg);
                $r3 = explode('\'></figure>', $r3[1]);
            }
            $imgh2 =$r3[0];

        }
    }
    if (isset($imgh1)){
        $devuelve['imgHeader'] = $imgh1;
    }
    if (isset($imgh2)){
            $devuelve['imgHeader'] = $imgh2;
    }
    //añadimos los metadatos para poder recogerlos más tarde
    addMetaRelacionadosTagsGeneral2025($devuelve);

}
//fin extraeDatosContenido

/******* MAS VISTOS O RELACIONADOS *******/

//añadiendo metadatos para recogerlos en las relaciones de noticias
function addMetaRelacionadosTagsGeneral2025($datos){
    if ( ! add_post_meta( get_the_ID() , 'cintillo', $datos['cintillo'], true ) ) {
        update_post_meta( get_the_ID() , 'cintillo', $datos['cintillo'] );
    }
    if(! add_post_meta( get_the_ID() , 'titulo1', $datos['titulo1'], true )){
        update_post_meta( get_the_ID() , 'titulo1', $datos['titulo1'] );
    }
    if(! add_post_meta( get_the_ID() , 'texto', $datos['texto'], true )){
        update_post_meta( get_the_ID() , 'texto', $datos['texto'] );
    }
    if(! add_post_meta( get_the_ID() , 'imgHeader', $datos['imgHeader'], true )){
        update_post_meta( get_the_ID() , 'imgHeader', $datos['imgHeader'] );
    }
    if(! add_post_meta( get_the_ID() , 'colorCintillo', $datos['colorCintillo'], true )){
        update_post_meta( get_the_ID() , 'colorCintillo', $datos['colorCintillo'] );
    }
}


// //pinta los posts con los metadatos existentes
// function pintaPostsTagsGeneral2025($atts, $num, $n_post){

//     if ($swiper) {

// 		$contador = 1;

//         $style = "
//         <style>
//         @import url('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');
//         /* section relacionados nuevo */
//         .relacionados-splide{width: 100%; position: relative; top: -50px; float: left;}
//         .cabecera-relacionados{display: flex; justify-content: space-between; position: relative; margin-bottom: 32px; padding: 0 10%;}
//         .cabecera-relacionados .titulo-relacionados{font: 400 31px/34px 'PPeiko'; color: white;}
//         body .splide__arrow{background: transparent;}
//         .splide__arrows button img{width: 20px; border: none;}
//         body .splide__arrow--prev{right: 5em; left: unset;}
//         .splide__arrow--next {right: 3em;}
//         .splide__arrow--prev img{position: relative; top: -2px;}
//         .splide__slide{display: flex;align-items: end; border-radius: 20px; max-width: 350px;}
//         .relacionado-texto{width: 100%;}
//         .relacionado-texto a{background-color: #0082c991;backdrop-filter: blur(10px);display: flex;justify-content: flex-start;align-items: center;flex-direction: column;padding: 12px;min-height: 148px;border-radius: 0 0 20px 20px;}
//         .relacionado-texto h4{ font: 200 12px/18px 'Arboria'; text-transform: uppercase; text-align: center; color: white; margin-bottom: 8px;}
//         .relacionado-texto h3{ font: 400 21px/27px 'PPeiko'; text-transform: none; text-align: center; color: white; margin-bottom: 8px;}
//         .relacionado-texto .flecha-relacionados{ border: none; position: absolute; bottom: 20px;}
//         body .splide__slide img {border-radius: 0 0 20px 20px;}
//         </style>
//         ";

    

//         if($height !== null && $height !== ""){
//             $height = $height;
//         }else{
//             $height = '500';
//         }
//         if($perpage !== null && $perpage !== ""){
//             $perpage = $perpage;
//         }else{
//             $perpage = '3';
//         }
//         if($permove !== null && $permove !== ""){
//             $permove = $permove;
//         }else{
//             $permove = '1';
//         }
//         if($arrows !== null && $arrows !== ""){
//             $arrows = $arrows;
//         }else{
//             $arrows = 'true';
//         }


//         $script = "
//         <script>

//             var splide = new Splide( '.".$claseextra."', {
//                 height: '".$height."px',  // NECESARIO
//                 perPage: ".intval($perpage).",  // NECESARIO
//                 perMove: ".intval($permove).",  // NECESARIO
//                 pagination: false,
//                 cover: true, 
//                 arrows: ".boolval($arrows).",  // NECESARIO
//                 padding: '10%',
//                 gap: '20px',
//                 breakpoints: {
//                 750: {
//                     perPage: 2,
//                 },
//                 500: {
//                     perPage: 1,
//                 }
//                 }
//             });
//             splide.mount();
//         </script>
//         ";


// 		$html .= $style.'
// 		<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
// 		<section class="splide '.$claseextra.'" aria-label="Splide Basic HTML Example">
// 			<div class="cabecera-relacionados">
// 				<div class="splide__arrows">
// 					<button class="splide__arrow splide__arrow--prev">
// 						<img src="/contentfactory/post/wp-content/uploads/sites/4/2024/04/arrow-prev.svg" alt="flecha prev">
// 					</button>
// 					<button class="splide__arrow splide__arrow--next">
// 						<img src="/contentfactory/post/wp-content/uploads/sites/4/2024/04/flecha-next.svg" alt="flecha next">
// 					</button>
// 				</div>
// 			</div>
// 			<div class="splide__track">
// 				<ul class="splide__list">
// 		';
// 		foreach ($swiper as $row) {


// 			$html .= '
// 			<li class="splide__slide">
// 				<img src="'.$row['imagen'].'" />
// 				<div class="relacionado-texto">
// 					<a href="'.$row['enlace'].'">
// 						<h4>'.$row['titulo'].'</h4>
// 						<h3>'.$row['subtitulo'].'</h3>
// 						<img src="/contentfactory/post/wp-content/uploads/sites/4/2024/04/flecha-rel.svg" alt="flecha"
// 							class="flecha-relacionados">
// 					</a>
// 				</div>
// 			</li>
// 			';
// 		}

// 		$html .= '
// 					</ul>
// 			</div>
// 		</section>
//         '.$script;

// 	}






// //       //asignando clases col segun el numero de posts
// //     if($n_post <=4 ){
// //         $col = 'col-1-2';
// //     }else{
// //         if($num<=2){
// //             $col = 'col-1-2';
// //         }else{
// //             $col = 'col-1-4';
// //         }
// //     }

// //     if ($num%2==0){
// //         $pad= 'pad-l-25';
// //     }else{
// //         $pad= 'pad-r-25';
// //     }
// //     //obteniendo datos
// //     $titulo1 = get_post_meta( get_the_ID(), 'titulo1', true );
// //     $texto= get_post_meta( get_the_ID(), 'texto', true );
// //     $cintillo = get_post_meta( get_the_ID(), 'cintillo', true );
// //     $IDimg =  get_post_meta( get_the_ID(), 'imgHeader', true );
// //     $imgOpen = get_post_meta( get_the_ID(), '_webdados_fb_open_graph_specific_image', true );
// //     $colorCintillo =  get_post_meta( get_the_ID(), 'colorCintillo', true );
// //     $autor = get_post_meta( get_the_ID(), 'autor', true );

// //     $imgAutor = get_post_meta( get_the_ID(), 'image_url', true );
// //     $imageVideoHtml = '';
// //     if (strpos($src, 'videoId') !== false) {
// //         $imageVideoHtml = "<img class='playRelacionados' src='".get_template_directory_uri()."/img/transparent-play-button.png'>";
// //     }

// // 	if($autor==""){
// // 		$autor = "Content Factory";
// // 	}

// //     //limitando  caracteres
// //     if(strlen($titulo1)>90){
// //         $titulo1 = substr($titulo1, 0, 90)."...";
// //     }
// //     if(strlen($texto)>140){
// //         $texto = substr($texto, 0, 140)."...";
// //     }
// //     //si alguno de los campos está vacío no se pinta la noticia...
// //     // if($titulo1 == "" || $texto == "" || $cintillo == ""|| $IDimg == "" || $colorCintillo == "") {
// //     // }else{
// //         if(is_numeric($IDimg)){
// //             if($imgOpen!="")
// //                 $imagenRel = "<img class='imagenrelacionada' src='/contentfactory/post/".$imgOpen."'>";
// //             else
// //                 $imagenRel = "<img class='imagenrelacionada' src=".wp_get_attachment_url($IDimg).">";
// //         }else{
// //             if($imgOpen!="")
// //                 $imagenRel = $imageVideoHtml."<img class='imagenrelacionada' src='/contentfactory/post/".$imgOpen."'>";
// //             else
// //                 $imagenRel = $imageVideoHtml."<img class='imagenrelacionada' src=".$IDimg.">";
// //         }

// //         $urlenlace = 'inicio';
// //         $enlace = '<a href="'. str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink(get_the_ID())).'" target="_blank">';
// //         $htmlDevuelto .= '<aside class="voc_relacionados_tags_2025 '.$col.' mobile-col-1-1 '.$pad.' relacionados">
// //         '.$enlace.'
// //         <article>
// //         <div class="ribbon pc 1'.$atts['el_class'].'">
// //             <span>'. $cintillo.'</span>
// //         </div>

// //         <figure>
// //                                 '.$imagenRel.'
// //         </figure>
// //         <h1><strong>'.$titulo1.'</strong>
// //             <p>'. $texto .'</p>
// //         </h1>
// //         <div class="info">
// //             <address><img class="imagenrelacionada" src="'.wp_get_attachment_url( $imgAutor ).'">'.$autor.'</address>
// //             <time>'.get_the_time('j F Y').' - '.get_the_time('G:i').'</time></div></article></a></aside>';

// //     //}

// //  return $htmlDevuelto;
// }

//comprueba los metadatos en caso de cambios en el post y modifica o elimina los datos segun los cambios realizados
function compruebaMetaDatosTagsGeneral2025( $post_id ){
	if ( ! wp_is_post_revision( $post_id ) ){
        $content_post = get_post($post_id);
        $content = $content_post->post_content;
        if ( has_shortcode( $content, 'header_uno') || has_shortcode( $content, 'header_dos')) {
            //la información ya se actualiza al visitar el post actualizado
        }else{
            //borrando metas si el post no contiene header
            delete_post_meta(get_the_ID() , 'cintillo');
            delete_post_meta(get_the_ID() , 'titulo1');
            delete_post_meta(get_the_ID() , 'texto');
            delete_post_meta(get_the_ID() , 'imgHeader');
            delete_post_meta(get_the_ID() , 'colorHeader');
			delete_post_meta(get_the_ID() , 'autor');
        }
    }
}
add_action('save_post', 'compruebaMetaDatosTagsGeneral2025');


add_action('admin_enqueue_scripts', 'testgeneral2025');
function testgeneral2025() {
    wp_enqueue_script('jquery_ui_related', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '3', true);
}

vc_add_shortcode_param( 'tag_field', 'tag_related_field', get_template_directory_uri().'/js/addons_js/relacionadostags.js');
function tag_related_fieldGeneral2025( $param, $value ) {

    $input_tag = '';
    $input_tag = '
        <style>
            .ui-autocomplete { z-index: 500000 }
        </style>
    ';
    $input_tag .= "<input type='text' id='tagfield' value='".$value."' name='".esc_attr( $param["param_name"])."' class='wpb_vc_param_value wpb-input' autocomplete='off'>";
    $input_tag .= "<input type='hidden' value='".admin_url( "admin-ajax.php" )."' id='adminajaxurl' name='adminajaxurl' />";

    return $input_tag;

}

add_action( 'wp_ajax_predictivetagsgeneral2025', 'predictivetagsgeneral2025' );
add_action( 'wp_ajax_nopriv_predictivetagsgeneral2025', 'predictivetagsgeneral2025' );
function predictivetagsgeneral2025() {
	$tags =get_tags();
	$jsonTags = [];
	foreach ( $tags as $tag ) {
		array_push($jsonTags, $tag->name);
	}
	exit( json_encode($jsonTags) );
}
