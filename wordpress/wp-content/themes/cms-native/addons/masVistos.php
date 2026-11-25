<?php


/******* MAS VISTOS O RELACIONADOS *******/

add_shortcode('vocento_mas_vistos', 'vocento_mas_vistos_function');
function vocento_mas_vistos_function($atts, $content)
{
    //si el usuario ha seleccionado la opcion de relacionados..

    if ($atts['mas_vistos'] == 'relacionados') {

        //mostrando recuadros por tags relacionados...
?>

    <?php
        $orig_post = $post;
        global $post;
        $tags = wp_get_post_tags($post->ID);

        if ($tags) {
            $host = str_replace("content-factory.", "", $_SERVER['HTTP_HOST']);
            $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';
            preg_match_all($reHost, $host, $machesHost);

            $tag_ids = array();
            foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

            $args = array(
                'tag__in' => $tag_ids,
                'post_status '      => 'publish',
                'post__not_in' => array($post->ID),
                'posts_per_page' => $atts['max_items'], // Number of related posts to display.
                'caller_get_posts' => 1,
                'update_post_meta_cache' => false,
                'cache_results'  => false,
                'no_found_rows' => true,
                'update_post_term_cache' => false,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'Medios',
                        'field'    => 'slug',
                        'terms'    => $machesHost[0][0],
                    ),
                ),
            );

            $my_query = new wp_query($args);
            $cuentaPar = 1;
            $htmlDevuelto = '';

            while ($my_query->have_posts()) {
                $my_query->the_post();
                $n_post = $my_query->post_count;
                //pintamos los posts
                $htmlDevuelto .= pintaPosts($atts, $cuentaPar, $n_post);
                $cuentaPar++;
            }
        }
        $post = $orig_post;
        wp_reset_query();

        return $htmlDevuelto;

        //si el usuario ha elegido la opción de los más visitados...
    } else {
        $host = str_replace("content-factory.", "", $_SERVER['HTTP_HOST']);
        $reHost = '/^[a-zA-Z0-9-_]+(\.){0}/';
        preg_match_all($reHost, $host, $machesHost);
        $args = array(
            'update_post_meta_cache' => false,
            'cache_results'  => false,
            'no_found_rows' => true,
            'post_status '      => 'publish',
            'update_post_term_cache' => false,
            'meta_key' => 'post_views_count',
            'orderby' => 'post_views_count',
            'nopaging'               => false,
            'posts_per_page' => $atts['max_items'], // Number of related posts to display.
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
        $my_query = new wp_query($args);

        $cuentaPar = 1;
        $htmlDevuelto = '';

        while ($my_query->have_posts()) {
            $my_query->the_post();
            $n_post = $my_query->post_count;
            //pintamos los posts
            $htmlDevuelto .= pintaPosts($atts, $cuentaPar, $n_post);
            $cuentaPar++;
        }
    }
    $post = $orig_post;
    wp_reset_query();

    return $htmlDevuelto;
}


/******* FUNCIONES EXTRA PARA METADATOS DE RELACIONADOS *******/


//extraemos los datos de la cadena para insertarlos en metadatos

function extraeDatosHeader($contenido)
{
    $r = explode('class=\'p-content\'>', $contenido);
    if (isset($r[1])) {
        $r = explode('</span></a>', $r[1]);
        $devuelve['cintillo'] = $r[0];
        $devuelve['colorCintillo'] = "p-content";
        $r1 = explode('<h1><strong>', $r[1]);
    } else {
        $r = explode('class=\'p-content-gris\'>', $contenido);
        $r = explode('</span>', $r[1]);
        $devuelve['cintillo'] = $r[0];
        $devuelve['colorCintillo'] = "p-content-gris";
        $r1 = explode('<h1><strong>', $r[2]);
    }
    if (isset($r1[1])) {
        $r1 = explode('</strong>', $r1[1]);
        $devuelve['titulo1'] = $r1[0];
        $r2 = explode('<p>', $r1[1]);
    }
    if (isset($r2[1])) {
        if (isset($r2[2])) {
            $auxImg = $r2[2];
            $r2 = explode('</p>', $r2[1]);
            $devuelve['texto'] = $r2[0];
            $r3 = explode('wp_get_attachment_url(', $auxImg);
            $r4 =  explode('<figure ><img src=\'', $auxImg);
            $r3 = explode(')', $r3[2]);
            $imgh1 = $r3[0];
        } else {
            $auxImg = $r2[1];
            $r2 = explode('</p>', $r2[1]);
            $devuelve['texto'] = $r2[0];
            $r3 = explode('wp_get_attachment_url(', $auxImg);
            $r3 = explode(')', $r3[1]);

            if (!isset($r3[1])) {
                $r3 =  explode('<img src=\'', $auxImg);
                $r3 = explode('\'></figure>', $r3[1]);
            }
            $imgh2 = $r3[0];
        }
    }
    if (isset($imgh1)) {
        $devuelve['imgHeader'] = $imgh1;
    }
    if (isset($imgh2)) {
        $devuelve['imgHeader'] = $imgh2;
    }
    //añadimos los metadatos para poder recogerlos más tarde
    addMetaRelacionados($devuelve);
}
//fin extraeDatosContenido

/******* MAS VISTOS O RELACIONADOS *******/

//añadiendo metadatos para recogerlos en las relaciones de noticias
function addMetaRelacionados($datos)
{
    if (!add_post_meta(get_the_ID(), 'cintillo', $datos['cintillo'], true)) {
        update_post_meta(get_the_ID(), 'cintillo', $datos['cintillo']);
    }
    if (!add_post_meta(get_the_ID(), 'titulo1', $datos['titulo1'], true)) {
        update_post_meta(get_the_ID(), 'titulo1', $datos['titulo1']);
    }
    if (!add_post_meta(get_the_ID(), 'texto', $datos['texto'], true)) {
        update_post_meta(get_the_ID(), 'texto', $datos['texto']);
    }
    if (!add_post_meta(get_the_ID(), 'imgHeader', $datos['imgHeader'], true)) {
        update_post_meta(get_the_ID(), 'imgHeader', $datos['imgHeader']);
    }
    if (!add_post_meta(get_the_ID(), 'colorCintillo', $datos['colorCintillo'], true)) {
        update_post_meta(get_the_ID(), 'colorCintillo', $datos['colorCintillo']);
    }
}


//pinta los posts con los metadatos existentes
function pintaPosts($atts, $num, $n_post)
{


    //asignando clases col segun el numero de posts
    if ($n_post <= 4) {
        $col = 'col-1-2';
    } else {
        if ($num <= 2) {
            $col = 'col-1-2';
        } else {
            $col = 'col-1-4';
        }
    }

    if ($num % 2 == 0) {
        $pad = 'pad-l-25';
    } else {
        $pad = 'pad-r-25';
    }
    //obteniendo datos
    $titulo1 = get_post_meta(get_the_ID(), 'titulo1', true);
    $texto = get_post_meta(get_the_ID(), 'texto', true);
    $cintillo = get_post_meta(get_the_ID(), 'cintillo', true);
    $IDimg =  get_post_meta(get_the_ID(), 'imgHeader', true);
    $imgOpen = get_post_meta(get_the_ID(), '_webdados_fb_open_graph_specific_image', true);
    $colorCintillo =  get_post_meta(get_the_ID(), 'colorCintillo', true);
    $autor = get_post_meta(get_the_ID(), 'autor', true);

    $imgAutor = get_post_meta(get_the_ID(), 'image_url', true);

    if ($autor == "") {
        $autor = "Content Factory";
    }

    //limitando  caracteres
    if (strlen($titulo1) > 90) {
        $titulo1 = substr($titulo1, 0, 90) . "...";
    }
    if (strlen($texto) > 140) {
        $texto = substr($texto, 0, 140) . "...";
    }
    //si alguno de los campos está vacío no se pinta la noticia...
    // if($titulo1 == "" || $texto == "" || $cintillo == ""|| $IDimg == "" || $colorCintillo == "") {
    // }else{
    if (is_numeric($IDimg) || $IDimg == "" || $IDimg == null) {
        if ($imgOpen != ""){
            $imgOpen = str_replace("https://ms-brandedcontent.srv.vocento.in", "", $imgOpen);
            // $imagenRel = "<img src='/contentfactory/post/".$imgOpen."'>";
            $imagenRel = '<img src="/contentfactory/post/' . $imgOpen . '">';
        }else{
            // $imagenRel = "<img src=".wp_get_attachment_url($IDimg).">";
            $imagenRel = '<img src="' . wp_get_attachment_url($IDimg) . '">';
        }
    } else {
        if ($imgOpen != "")
            // $imagenRel = "<img class='playRelacionados' src='".get_template_directory_uri()."/img/transparent-play-button.png'><img src='/contentfactory/post/".$imgOpen."'>";
            $imagenRel = '<img class="playRelacionados" src="' . get_template_directory_uri() . '/img/transparent-play-button.png"><img src="/contentfactory/post/' . $imgOpen . '">';

        else
            // $imagenRel = "<img class='playRelacionados' src='".get_template_directory_uri()."/img/transparent-play-button.png'><img src=".$IDimg.">";
            $imagenRel = '<img class="playRelacionados" src="' . get_template_directory_uri() . '/img/transparent-play-button.png"><img src="' . $IDimg . '">';
    }

    $htmlDevuelto = '';
    $htmlDevuelto .= '<aside class="voc_relacionados_2025 ' . $col . ' mobile-col-1-1 ' . $pad . ' relacionados">
        <a href="' . str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink(get_the_ID())) . '" target="_blank">
        <article>
        <div class="ribbon pc ' . $atts['el_class'] . '">
            <span>' . $cintillo . '</span>
        </div>

        <figure>
                                ' . $imagenRel . '
        </figure>
        <h1><strong>' . $titulo1 . '</strong>
            <p>' . $texto . '</p>
        </h1>
        <div class="info">
            <address><img src="' . wp_get_attachment_url($imgAutor) . '">' . $autor . '</address>
            <time>' . get_the_time('j F Y') . ' - ' . get_the_time('G:i') . '</time></div></article></a></aside>';

    //}

    return $htmlDevuelto;
}
//comprueba los metadatos en caso de cambios en el post y modifica o elimina los datos segun los cambios realizados
function compruebaMetaDatos($post_id)
{
    if (!wp_is_post_revision($post_id)) {
        $content_post = get_post($post_id);
        $content = $content_post->post_content;
        if (has_shortcode($content, 'header_uno') || has_shortcode($content, 'header_dos')) {
            //la información ya se actualiza al visitar el post actualizado
        } else {
            //borrando metas si el post no contiene header
            delete_post_meta(get_the_ID(), 'cintillo');
            delete_post_meta(get_the_ID(), 'titulo1');
            delete_post_meta(get_the_ID(), 'texto');
            delete_post_meta(get_the_ID(), 'imgHeader');
            delete_post_meta(get_the_ID(), 'colorHeader');
            delete_post_meta(get_the_ID(), 'autor');
        }
    }
}
add_action('save_post', 'compruebaMetaDatos');


/******* FUNCIONES EXTRA PARA METADATOS DE RELACIONADOS *******/



/******* MAS VISTOS O RELACIONADOS *******/

    ?>
