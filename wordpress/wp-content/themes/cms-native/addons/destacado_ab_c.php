<?php

function create_destacado_ab_c_shortcode($atts)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'titulo_seccion_ab' => '',
            'selectedtag_ab1' => '',
            'postbytagselected_ab1' => '',
            'selectedtag_ab2' => '',
            'postbytagselected_ab2' => '',
            'selectedtag_ab3' => '',
            'postbytagselected_ab3' => '',
            'selectedtag_ab4' => '',
            'postbytagselected_ab4' => '',
            'selectedtag_ab5' => '',
            'postbytagselected_ab5' => '',
            'titulo_seccion_c' => '',
            'selectedtag_c1' => '',
            'postbytagselected_c1' => '',
            'selectedtag_c2' => '',
            'postbytagselected_c2' => '',
            'clase_seccion' => '',
        ),
        $atts,
        'destacado_ab_c'
    );

    $titulo_seccion_ab = $atts['titulo_seccion_ab'];
    $titulo_seccion_c = $atts['titulo_seccion_c'];

    if ($atts['postbytagselected_ab1']) {
        $element_ab1 = $atts['postbytagselected_ab1'];
        $selectedtag_ab1 = $atts['selectedtag_ab1'];
        $tag_ab1 = get_term_by('slug', $selectedtag_ab1, 'post_tag');
        $post_ab1 = get_post($element_ab1);
        $idImagenAutor_ab1 = get_post_meta($post_ab1->ID, 'image_url', true);
        // $idImagenCabecera_ab1 = get_post_meta($post_ab1->ID, 'imagencabecera', true);
        $idImagenCabecera_ab1 = '/contentfactory/post'.get_post_meta($post_ab1->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_ab1 = get_post_meta($post_ab1->ID, 'autor', true);
        $texto_ab1 = get_post_meta($post_ab1->ID, 'texto', true);
        $titulo1_ab1 = get_post_meta($post_ab1->ID, 'titulo1', true);
        $fecha_ab1 = get_the_date('j F, Y', $post_ab1) .' - '. get_the_date('G:i', $post_ab1);
        $permalink_ab1 = get_permalink($post_ab1->ID);
    }
    if ($atts['postbytagselected_ab2']) {
        $element_ab2 = $atts['postbytagselected_ab2'];
        $selectedtag_ab2 = $atts['selectedtag_ab2'];
        $tag_ab2 = get_term_by('slug', $selectedtag_ab2, 'post_tag');
        $post_ab2 = get_post($element_ab2);
        $idImagenAutor_ab2 = get_post_meta($post_ab2->ID, 'image_url', true);
        // $idImagenCabecera_ab2 = get_post_meta($post_ab2->ID, 'imagencabecera', true);
        $idImagenCabecera_ab2 = '/contentfactory/post'.get_post_meta($post_ab2->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_ab2 = get_post_meta($post_ab2->ID, 'autor', true);
        $texto_ab2 = get_post_meta($post_ab2->ID, 'texto', true);
        $titulo1_ab2 = get_post_meta($post_ab2->ID, 'titulo1', true);
        $fecha_ab2 = get_the_date('j F, Y', $post_ab2) .' - '. get_the_date('G:i', $post_ab2);
        $permalink_ab2 = get_permalink($post_ab2->ID);
    }
    if ($atts['postbytagselected_ab3']) {
        $element_ab3 = $atts['postbytagselected_ab3'];
        $selectedtag_ab3 = $atts['selectedtag_ab3'];
        $tag_ab3 = get_term_by('slug', $selectedtag_ab3, 'post_tag');
        $post_ab3 = get_post($element_ab3);
        $idImagenAutor_ab3 = get_post_meta($post_ab3->ID, 'image_url', true);
        // $idImagenCabecera_ab3 = get_post_meta($post_ab3->ID, 'imagencabecera', true);
        $idImagenCabecera_ab3 = '/contentfactory/post'.get_post_meta($post_ab3->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_ab3 = get_post_meta($post_ab3->ID, 'autor', true);
        $texto_ab3 = get_post_meta($post_ab3->ID, 'texto', true);
        $titulo1_ab3 = get_post_meta($post_ab3->ID, 'titulo1', true);
        $fecha_ab3 = get_the_date('j F, Y', $post_ab3) .' - '. get_the_date('G:i', $post_ab3);
        $permalink_ab3 = get_permalink($post_ab3->ID);
    }
    if ($atts['postbytagselected_ab4']) {
        $element_ab4 = $atts['postbytagselected_ab4'];
        $selectedtag_ab4 = $atts['selectedtag_ab4'];
        $tag_ab4 = get_term_by('slug', $selectedtag_ab4, 'post_tag');
        $post_ab4 = get_post($element_ab4);
        $idImagenAutor_ab4 = get_post_meta($post_ab4->ID, 'image_url', true);
        // $idImagenCabecera_ab4 = get_post_meta($post_ab4->ID, 'imagencabecera', true);
        $idImagenCabecera_ab4 = '/contentfactory/post'.get_post_meta($post_ab4->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_ab4 = get_post_meta($post_ab4->ID, 'autor', true);
        $texto_ab4 = get_post_meta($post_ab4->ID, 'texto', true);
        $titulo1_ab4 = get_post_meta($post_ab4->ID, 'titulo1', true);
        $fecha_ab4 = get_the_date('j F, Y', $post_ab4) .' - '. get_the_date('G:i', $post_ab4);
        $permalink_ab4 = get_permalink($post_ab4->ID);
    }
    if ($atts['postbytagselected_ab5']) {
        $element_ab5 = $atts['postbytagselected_ab5'];
        $selectedtag_ab5 = $atts['selectedtag_ab5'];
        $tag_ab5 = get_term_by('slug', $selectedtag_ab5, 'post_tag');
        $post_ab5 = get_post($element_ab5);
        $idImagenAutor_ab5 = get_post_meta($post_ab5->ID, 'image_url', true);
        // $idImagenCabecera_ab5 = get_post_meta($post_ab5->ID, 'imagencabecera', true);
        $idImagenCabecera_ab5 = '/contentfactory/post'.get_post_meta($post_ab5->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_ab5 = get_post_meta($post_ab5->ID, 'autor', true);
        $texto_ab5 = get_post_meta($post_ab5->ID, 'texto', true);
        $titulo1_ab5 = get_post_meta($post_ab5->ID, 'titulo1', true);
        $fecha_ab5 = get_the_date('j F, Y', $post_ab5) .' - '. get_the_date('G:i', $post_ab5);
        $permalink_ab5 = get_permalink($post_ab5->ID);
    }

    if ($atts['postbytagselected_c1']) {
        $element_c1 = $atts['postbytagselected_c1'];
        $selectedtag_c1 = $atts['selectedtag_c1'];
        $tag_c1 = get_term_by('slug', $selectedtag_c1, 'post_tag');
        $post_c1 = get_post($element_c1);
        $idImagenAutor_c1 = get_post_meta($post_c1->ID, 'image_url', true);
        // $idImagenCabecera_c1 = get_post_meta($post_c1->ID, 'imagencabecera', true);
        $idImagenCabecera_c1 = '/contentfactory/post'.get_post_meta($post_c1->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_c1 = get_post_meta($post_c1->ID, 'autor', true);
        $texto_c1 = get_post_meta($post_c1->ID, 'texto', true);
        $titulo1_c1 = get_post_meta($post_c1->ID, 'titulo1', true);
        $fecha_c1 = get_the_date('j F, Y', $post_c1) .' - '. get_the_date('G:i', $post_c1);
        $permalink_c1 = get_permalink($post_c1->ID);
    }
    if ($atts['postbytagselected_c1']) {
        $element_c2 = $atts['postbytagselected_c2'];
        $selectedtag_c2 = $atts['selectedtag_c2'];
        $tag_c2 = get_term_by('slug', $selectedtag_c2, 'post_tag');
        $post_c2 = get_post($element_c2);
        $idImagenAutor_c2 = get_post_meta($post_c2->ID, 'image_url', true);
        // $idImagenCabecera_c2 = get_post_meta($post_c2->ID, 'imagencabecera', true);
        $idImagenCabecera_c2 = '/contentfactory/post'.get_post_meta($post_c2->ID, '_webdados_fb_open_graph_specific_image', true);
        $autor_c2 = get_post_meta($post_c2->ID, 'autor', true);
        $texto_c2 = get_post_meta($post_c2->ID, 'texto', true);
        $titulo1_c2 = get_post_meta($post_c2->ID, 'titulo1', true);
        $fecha_c2 = get_the_date('j F, Y', $post_c2) .' - '. get_the_date('G:i', $post_c2);
        $permalink_c2 = get_permalink($post_c2->ID);
    }


    $urlImg_ab1 ='';
    if ($idImagenAutor_ab1 == null || $idImagenAutor_ab1 == '') {
        $urlImg_ab1 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_ab1 = wp_get_attachment_image_src(intval($idImagenAutor_ab1), 'full')[0];
    }

    $urlImg_ab2 ='';
    if ($idImagenAutor_ab2 == null || $idImagenAutor_ab2 == '') {
        $urlImg_ab2 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_ab2 = wp_get_attachment_image_src(intval($idImagenAutor_ab2), 'full')[0];
    }

    $urlImg_ab3 ='';
    if ($idImagenAutor_ab3 == null || $idImagenAutor_ab3 == '') {
        $urlImg_ab3 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_ab3 = wp_get_attachment_image_src(intval($idImagenAutor_ab3), 'full')[0];
    }

    $urlImg_ab4 ='';
    if ($idImagenAutor_ab4 == null || $idImagenAutor_ab4 == '') {
        $urlImg_ab4 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_ab4 = wp_get_attachment_image_src(intval($idImagenAutor_ab4), 'full')[0];
    }

    $urlImg_ab5 ='';
    if ($idImagenAutor_ab5 == null || $idImagenAutor_ab5 == '') {
        $urlImg_ab5 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_ab5 = wp_get_attachment_image_src(intval($idImagenAutor_ab5), 'full')[0];
    }

    $urlImg_c1 ='';
    if ($idImagenAutor_c1 == null || $idImagenAutor_c1 == '') {
        $urlImg_c1 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_c1 = wp_get_attachment_image_src(intval($idImagenAutor_c1), 'full')[0];
    }

    $urlImg_c2 ='';
    if ($idImagenAutor_c2 == null || $idImagenAutor_c2 == '') {
        $urlImg_c2 = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_c2 = wp_get_attachment_image_src(intval($idImagenAutor_c2), 'full')[0];
    }

    
    

    $clase_seccion = $atts['clase_seccion'];

    $output = '
    <!-- el div con la clase vacia es el div padre personalizable en el addonn -->
    <div class="'.$clase_seccion.'">
      <div class="destacado-ab-c">
        <div class="bloque-ab"> 
          <!-- el h3.titulocategoria será un campo rellenable si no se rellena lo ocultamos nosotros por css -->
          <h3 class="titulocategoria">'.$titulo_seccion_ab.'</h3>
          <div class="bloque-a">
    ';
    if ($atts['postbytagselected_ab1']) {
        $output .= '
            <article class="modulo-noticia">
                <figure> <a href="'.$permalink_ab1.'"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="'.$idImagenCabecera_ab1.'"> </a> </figure>
                <div class="content-a"> <a href="'.$permalink_ab1.'"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">'.$tag_ab1->name.'</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>'.$titulo1_ab1.'</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>'.$texto_ab1.'</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="'.$urlImg_ab1.'">'.$autor_ab1.'
                    </address>
                    <time>'.$fecha_ab1.'</time>
                </div>
                </a> </div>
            </article>
        ';
    }
    if ($atts['postbytagselected_ab1']) {
        $output .= '
            <article class="modulo-noticia">
                <figure> <a href="'.$permalink_ab2.'"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="'.$idImagenCabecera_ab2.'"> </a> </figure>
                <div class="content-a"> <a href="'.$permalink_ab2.'"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">'.$tag_ab2->name.'</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>'.$titulo1_ab2.'</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>'.$texto_ab2.'</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="'.$urlImg_ab2.'">'.$autor_ab2.'
                    </address>
                    <time>'.$fecha_ab2.'</time>
                </div>
                </a> </div>
            </article>
        ';
    }
    if ($atts['postbytagselected_ab1']) {
        $output .= '
            <article class="modulo-noticia">
                <figure> <a href="'.$permalink_ab3.'"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="'.$idImagenCabecera_ab3.'"> </a> </figure>
                <div class="content-a"> <a href="'.$permalink_ab3.'"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">'.$tag_ab3->name.'</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>'.$titulo1_ab3.'</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>'.$texto_ab3.'</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="'.$urlImg_ab3.'">'.$autor_ab3.'
                    </address>
                    <time>'.$fecha_ab3.'</time>
                </div>
                </a> </div>
            </article>
        ';
    }
    $output .= '
          </div>
          <div class="bloque-b">
    ';
    if ($atts['postbytagselected_ab1']) {
        $output .= '
            <article class="modulo-noticia">
                <figure> <a href="'.$permalink_ab4.'"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="'.$idImagenCabecera_ab4.'"> </a> </figure>
                <div class="content-a"> <a href="'.$permalink_ab4.'"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">'.$tag_ab4->name.'</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>'.$titulo1_ab4.'</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>'.$texto_ab4.'</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="'.$urlImg_ab4.'">'.$autor_ab4.'
                    </address>
                    <time>'.$fecha_ab4.'</time>
                </div>
                </a> </div>
            </article>
        ';
    }
    if ($atts['postbytagselected_ab1']) {
        $output .= '
            <article class="modulo-noticia">
                <figure> <a href="'.$permalink_ab5.'"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="'.$idImagenCabecera_ab5.'"> </a> </figure>
                <div class="content-a"> <a href="'.$permalink_ab5.'"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">'.$tag_ab5->name.'</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>'.$titulo1_ab5.'</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>'.$texto_ab5.'</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="'.$urlImg_ab5.'">'.$autor_ab5.'
                    </address>
                    <time>'.$fecha_ab5.'</time>
                </div>
                </a> </div>
            </article>
        ';
    }
    $output .= '
          </div>
        </div>
        <div class="bloque-c"> 
          <!-- el h3.titulocategoria será un campo rellenable si no se rellena lo ocultamos nosotros por css -->
          <h3 class="titulocategoria">'.$titulo_seccion_c.'</h3>
    ';
    if ($atts['postbytagselected_ab1']) {
        $output .= '
          <article class="modulo-noticia">
            <figure> <a href="'.$permalink_c1.'"> 
            <!-- esta imagen la trae del addon cabecero --> 
            <img src="'.$idImagenCabecera_c1.'"> </a> </figure>
            <div class="content-a"> <a href="'.$permalink_c1.'"> 
            <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
            <p class="nombrecategoria">'.$tag_c1->name.'</p>
            <!-- h1 es el titulo del addon cabecero -->
            <h1>'.$titulo1_c1.'</h1>
            <!-- este p es la entradilla del addon cabecero -->
            <p>'.$texto_c1.'</p>
            
            <!-- este info reune la imagen del autor, el autor y la fecha del post -->
            <div class="info">
                <address>
                <img src="'.$urlImg_c1.'">'.$autor_c1.'
                </address>
                <time>'.$fecha_c1.'</time>
            </div>
            </a> </div>
        </article>
        ';
    }
    if ($atts['postbytagselected_ab1']) {
        $output .= '
        <article class="modulo-noticia">
            <figure> <a href="'.$permalink_c2.'"> 
            <!-- esta imagen la trae del addon cabecero --> 
            <img src="'.$idImagenCabecera_c2.'"> </a> </figure>
            <div class="content-a"> <a href="'.$permalink_c2.'"> 
            <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
            <p class="nombrecategoria">'.$tag_c2->name.'</p>
            <!-- h1 es el titulo del addon cabecero -->
            <h1>'.$titulo1_c2.'</h1>
            <!-- este p es la entradilla del addon cabecero -->
            <p>'.$texto_c2.'</p>
            
            <!-- este info reune la imagen del autor, el autor y la fecha del post -->
            <div class="info">
                <address>
                <img src="'.$urlImg_c2.'">'.$autor_c2.'
                </address>
                <time>'.$fecha_c2.'</time>
            </div>
            </a> </div>
        </article>
        ';
    }
    $output .= '
        </div>
      </div>
    </div>
    ';

    return $output;
}
add_shortcode('destacado_ab_c', 'create_destacado_ab_c_shortcode');

vc_add_shortcode_param('postbytagselected_ab1', 'field_destacado_ab1', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_ab1.js');
function field_destacado_ab1($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_ab1" value="' . esc_attr($value) . '" name="idpostseleccionado_ab1"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_ab1" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

vc_add_shortcode_param('postbytagselected_ab2', 'field_destacado_ab2', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_ab2.js');
function field_destacado_ab2($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_ab2" value="' . esc_attr($value) . '" name="idpostseleccionado_ab2"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_ab2" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}
vc_add_shortcode_param('postbytagselected_ab3', 'field_destacado_ab3', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_ab3.js');
function field_destacado_ab3($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_ab3" value="' . esc_attr($value) . '" name="idpostseleccionado_ab3"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_ab3" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}
vc_add_shortcode_param('postbytagselected_ab4', 'field_destacado_ab4', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_ab4.js');
function field_destacado_ab4($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_ab4" value="' . esc_attr($value) . '" name="idpostseleccionado_ab4"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_ab4" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}
vc_add_shortcode_param('postbytagselected_ab5', 'field_destacado_ab5', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_ab5.js');
function field_destacado_ab5($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_ab5" value="' . esc_attr($value) . '" name="idpostseleccionado_ab5"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_ab5" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

vc_add_shortcode_param('postbytagselected_c1', 'field_destacado_c1', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_c1.js');
function field_destacado_c1($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_c1" value="' . esc_attr($value) . '" name="idpostseleccionado_c1"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_c1" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}
vc_add_shortcode_param('postbytagselected_c2', 'field_destacado_c2', get_template_directory_uri(__FILE__).'/addons/js/postbytagselected_c2.js');
function field_destacado_c2($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_c2" value="' . esc_attr($value) . '" name="idpostseleccionado_c2"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_c2" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}


add_action('vc_before_init', 'destacado_ab_c_integrateWithVC');
function destacado_ab_c_integrateWithVC()
{
    $arrayTags = [];
    if(is_admin()){
        $tags = get_tags();
        foreach ($tags as $tag) {
            $arrayTags[$tag->name] = $tag->slug;
        }
    }

    vc_map(array(
        'name' => __('Destacado AB_C', 'textdomain'),
        'base' => 'destacado_ab_c',
        'show_settings_on_create' => true,
        'category' => __('Content', 'textdomain'),
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Titulo General AB', 'textdomain'),
                'param_name' => 'titulo_seccion_ab',
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_ab1",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_ab1",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags AB1', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_ab1",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_ab2",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_ab2",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags AB2', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_ab2",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_ab3",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_ab3",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags AB3', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_ab3",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_ab4",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_ab4",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags AB4', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_ab4",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_ab5",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_ab5",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags AB5', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_ab5",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_c1",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Titulo General C', 'textdomain'),
                'param_name' => 'titulo_seccion_c',
            ),
            array(
                "type" => "postbytagselected_c1",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags C1', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_c1",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_c2",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_c2",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags C2', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_c2",
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Clase General', 'textdomain'),
                'param_name' => 'clase_seccion',
            ),
        )
    ));
}
