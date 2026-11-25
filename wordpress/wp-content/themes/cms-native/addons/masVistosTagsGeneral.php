<?php


/******* MAS VISTOS O RELACIONADOS *******/

add_shortcode( 'vocento_mas_vistos_tags_general', 'vocento_mas_vistos_tags_general_function' );
function vocento_mas_vistos_tags_general_function( $atts, $content ) {
    // Attributes
	$atts = shortcode_atts(
		array(
            'post_type' => '',
            'mas_vistos' => '',
			'max_items' => '',
			'tag_name' => '',
			'el_class' => '',
		),
		$atts,
		'post_relacionados'
	);

    $tags = explode(",",$atts['tag_name']);
    array_pop($tags);
    $idTags = [];
    foreach( $tags as $tag) {
        $tag = get_term_by('name', $tag,'post_tag');
        array_push($idTags, $tag->term_id);
    }


    if($atts['mas_vistos']=='relacionados'){
?>

    <?php
        $orig_post = $post;
        global $post;
        $htmlDevuelto = "";
        $tags = wp_get_post_tags($post->ID);

        if ($atts['tag_name']) {
            $host = str_replace("content-factory.","",$_SERVER['HTTP_HOST']);
            $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';
            preg_match_all($reHost, $host , $machesHost);

            $tag_ids = array();
            foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

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
            $cuentaPar = 1;

            while( $my_query->have_posts() ) {
                $my_query->the_post();
                 $n_post = $my_query->post_count;
                //pintamos los posts
                $htmlDevuelto .=pintaPostsTagsGeneral($atts, $cuentaPar, $n_post);
                $cuentaPar++;
            }
        }
        $post = $orig_post;
        wp_reset_query();

        return $htmlDevuelto;

        //si el usuario ha elegido la opción de los más visitados...
    }else{
            $host = str_replace("content-factory.","",$_SERVER['HTTP_HOST']);
            $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';
            preg_match_all($reHost, $host , $machesHost);
        $args = array(
            'update_post_meta_cache' => false,
            'cache_results'  => false,
          'no_found_rows' => true,
          'post_status '      => 'publish',
          'update_post_term_cache' => false,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'post_views_count',
                                    'nopaging'               => false,
                                    'posts_per_page'=> $atts['max_items'], // Number of related posts to display.
                        'order' => 'DESC',
                        'meta_query' => array(
                                array(
                                'key' => 'post_views_count',
                                'compare' => 'LIKE',
                                )
                            ),
                        'tax_query' => array(
                                array(
                                    'taxonomy' => 'Medios',
                                    'field'    => 'slug',
                                    'terms'    => $machesHost[0][0],
                                ),
                            ),
                        );
        //ahora mostramos los post mas visitados
        $my_query = new wp_query( $args );

        $cuentaPar = 1;

        while( $my_query->have_posts() ) {
            $my_query->the_post();
			$n_post = $my_query->post_count;
			//pintamos los posts
			$htmlDevuelto .=pintaPostsTagsGeneral($atts, $cuentaPar, $n_post);
            $cuentaPar++;
        }
    }
    $post = $orig_post;
    wp_reset_query();

    return $htmlDevuelto;

}


/******* FUNCIONES EXTRA PARA METADATOS DE RELACIONADOS *******/


//extraemos los datos de la cadena para insertarlos en metadatos

function extraeDatosHeaderTagsGeneral($contenido){
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
    addMetaRelacionadosTagsGeneral($devuelve);

}
//fin extraeDatosContenido

/******* MAS VISTOS O RELACIONADOS *******/

//añadiendo metadatos para recogerlos en las relaciones de noticias
function addMetaRelacionadosTagsGeneral($datos){
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


//pinta los posts con los metadatos existentes
function pintaPostsTagsGeneral($atts, $num, $n_post){


      //asignando clases col segun el numero de posts
    if($n_post <=4 ){
        $col = 'col-1-2';
    }else{
        if($num<=2){
            $col = 'col-1-2';
        }else{
            $col = 'col-1-4';
        }
    }

    if ($num%2==0){
        $pad= 'pad-l-25';
    }else{
        $pad= 'pad-r-25';
    }
    //obteniendo datos
    $titulo1 = get_post_meta( get_the_ID(), 'titulo1', true );
    $texto= get_post_meta( get_the_ID(), 'texto', true );
    $cintillo = get_post_meta( get_the_ID(), 'cintillo', true );
    $IDimg =  get_post_meta( get_the_ID(), 'imgHeader', true );
    $imgOpen = get_post_meta( get_the_ID(), '_webdados_fb_open_graph_specific_image', true );



    
    $findme   = 'ms-brandedcontent.srv.vocento.in';
    $pos = strpos($imgOpen, $findme);
    if ($pos === false) {
        $imgOpen = '/contentfactory/post' . $imgOpen;
    } else {
        $imgOpen = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', $imgOpen);
    }






    $colorCintillo =  get_post_meta( get_the_ID(), 'colorCintillo', true );
    $autor = get_post_meta( get_the_ID(), 'autor', true );

    $imgAutor = get_post_meta( get_the_ID(), 'image_url', true );
    $imageVideoHtml = '';
    if (strpos($src, 'videoId') !== false) {
        $imageVideoHtml = "<img class='playRelacionados' src='".get_template_directory_uri()."/img/transparent-play-button.png'>";
    }

	if($autor==""){
		$autor = "Content Factory";
	}

    //limitando  caracteres
    if(strlen($titulo1)>90){
        $titulo1 = substr($titulo1, 0, 90)."...";
    }
    if(strlen($texto)>140){
        $texto = substr($texto, 0, 140)."...";
    }
    //si alguno de los campos está vacío no se pinta la noticia...
    // if($titulo1 == "" || $texto == "" || $cintillo == ""|| $IDimg == "" || $colorCintillo == "") {
    // }else{
        if(is_numeric($IDimg)){
            if($imgOpen!="")
                $imagenRel = "<img class='imagenrelacionada 1' src='".$imgOpen."'>";
            else
                $imagenRel = "<img class='imagenrelacionada 2' src=".wp_get_attachment_url($IDimg).">";
        }else{
            if($imgOpen!="")
                $imagenRel = $imageVideoHtml."<img class='imagenrelacionada 3' src='".$imgOpen."'>";
            else
                $imagenRel = $imageVideoHtml."<img class='imagenrelacionada 4' src=".$IDimg.">";
        }

        $urlenlace = 'inicio';
        $enlace = '<a href="'. str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink(get_the_ID())).'" target="_blank">';
        $htmlDevuelto .= '<aside class="voc_relacionados_tags_2025 '.$col.' mobile-col-1-1 '.$pad.' relacionados">
        '.$enlace.'
        <article>
        <div class="ribbon pc 1'.$atts['el_class'].'">
            <span>'. $cintillo.'</span>
        </div>

        <figure>
                                '.$imagenRel.'
        </figure>
        <h1><strong>'.$titulo1.'</strong>
            <p>'. $texto .'</p>
        </h1>
        <div class="info">
            <address><img class="imagenrelacionada" src="'.wp_get_attachment_url( $imgAutor ).'">'.$autor.'</address>
            <time>'.get_the_time('j F Y').' - '.get_the_time('G:i').'</time></div></article></a></aside>';

    //}

 return $htmlDevuelto;
}
//comprueba los metadatos en caso de cambios en el post y modifica o elimina los datos segun los cambios realizados
function compruebaMetaDatosTagsGeneral( $post_id ){
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
add_action('save_post', 'compruebaMetaDatosTagsGeneral');


add_action('admin_enqueue_scripts', 'testgeneral');
function testgeneral() {
    wp_enqueue_script('jquery_ui_related', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '3', true);
}

vc_add_shortcode_param( 'tag_field', 'tag_related_field', get_template_directory_uri().'/js/addons_js/relacionadostags.js');
function tag_related_fieldGeneral( $param, $value ) {

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

add_action( 'wp_ajax_predictivetagsgeneral', 'predictivetagsgeneral' );
add_action( 'wp_ajax_nopriv_predictivetagsgeneral', 'predictivetagsgeneral' );
function predictivetagsgeneral() {
	$tags =get_tags();
	$jsonTags = [];
	foreach ( $tags as $tag ) {
		array_push($jsonTags, $tag->name);
	}
	exit( json_encode($jsonTags) );
}

?>
