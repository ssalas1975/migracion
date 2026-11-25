<?php

function create_destacadoabc_shortcode($atts)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'titulo_seccion' => '',
            'selectedtag_a' => '',
            'postbytagselected_a' => '',
            'selectedtag_b' => '',
            'postbytagselected_b' => '',
            'selectedtag_c' => '',
            'postbytagselected_c' => '',
            'clase_seccion' => '',
        ),
        $atts,
        'destacado_abc'
    );

    $titulo_seccion = $atts['titulo_seccion'];


    function iscontein($url)
    {
        $mystring = $url;
        $findme = 'https://ms-brandedcontent.srv.vocento.in';
        $pos = strpos($mystring, $findme);

        if ($pos !== false) {
            return str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', $url);
        } else {
            return '/contentfactory/post' . $url;
        }
    }


    // Attributes in var
    $element_a = $atts['postbytagselected_a'];
    $selectedtag_a = $atts['selectedtag_a'];
    $tag_a = get_term_by('slug', $selectedtag_a, 'post_tag');
    $post_a = get_post($element_a);
    $idImagenAutor_a = get_post_meta($post_a->ID, 'image_url', true);
    // $idImagenCabecera_a = get_post_meta($post_a->ID, 'imagencabecera', true);
    // $idImagenCabecera_a = '/contentfactory/post'.get_post_meta($post_a->ID, '_webdados_fb_open_graph_specific_image', true);
    $idImagenCabecera_a = iscontein(get_post_meta($post_a->ID, '_webdados_fb_open_graph_specific_image', true));
    $autor_a = get_post_meta($post_a->ID, 'autor', true);
    $texto_a = get_post_meta($post_a->ID, 'texto', true);
    $titulo1_a = get_post_meta($post_a->ID, 'titulo1', true);
    $fecha_a = get_the_date('j F, Y', $post_a) . ' - ' . get_the_date('G:i', $post_a);
    // $permalink_a = get_permalink($post_a->ID);
    $permalink_a = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', get_permalink($post_a->ID));

    $element_b = $atts['postbytagselected_b'];
    $selectedtag_b = $atts['selectedtag_b'];
    $tag_b = get_term_by('slug', $selectedtag_b, 'post_tag');
    $post_b = get_post($element_b);
    $idImagenAutor_b = get_post_meta($post_b->ID, 'image_url', true);
    // $idImagenCabecera_b = get_post_meta($post_b->ID, 'imagencabecera', true);
    // $idImagenCabecera_b = '/contentfactory/post'.get_post_meta($post_b->ID, '_webdados_fb_open_graph_specific_image', true);
    $idImagenCabecera_b = iscontein(get_post_meta($post_b->ID, '_webdados_fb_open_graph_specific_image', true));
    $autor_b = get_post_meta($post_b->ID, 'autor', true);
    $texto_b = get_post_meta($post_b->ID, 'texto', true);
    $titulo1_b = get_post_meta($post_b->ID, 'titulo1', true);
    $fecha_b = get_the_date('j F, Y', $post_b) . ' - ' . get_the_date('G:i', $post_b);
    // $permalink_b = get_permalink($post_b->ID);
    $permalink_b = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', get_permalink($post_b->ID));

    $element_c = $atts['postbytagselected_c'];
    $selectedtag_c = $atts['selectedtag_c'];
    $tag_c = get_term_by('slug', $selectedtag_c, 'post_tag');
    $post_c = get_post($element_c);
    $idImagenAutor_c = get_post_meta($post_c->ID, 'image_url', true);
    // $idImagenCabecera_c = get_post_meta($post_c->ID, 'imagencabecera', true);
    // $idImagenCabecera_c = '/contentfactory/post'.get_post_meta($post_c->ID, '_webdados_fb_open_graph_specific_image', true);
    $idImagenCabecera_c = iscontein(get_post_meta($post_c->ID, '_webdados_fb_open_graph_specific_image', true));
    $autor_c = get_post_meta($post_c->ID, 'autor', true);
    $texto_c = get_post_meta($post_c->ID, 'texto', true);
    $titulo1_c = get_post_meta($post_c->ID, 'titulo1', true);
    $fecha_c = get_the_date('j F, Y', $post_c) . ' - ' . get_the_date('G:i', $post_c);
    // $permalink_c = get_permalink($post_c->ID);
    $permalink_c = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', get_permalink($post_c->ID));

    $clase_seccion = $atts['clase_seccion'];


    $urlImg_a = '';
    if ($idImagenAutor_a == null || $idImagenAutor_a == '') {
        $urlImg_a = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_a = wp_get_attachment_image_src(intval($idImagenAutor_a), 'full')[0];
    }

    $urlImg_b = '';
    if ($idImagenAutor_b == null || $idImagenAutor_b == '') {
        $urlImg_b = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_b = wp_get_attachment_image_src(intval($idImagenAutor_b), 'full')[0];
    }

    $urlImg_c = '';
    if ($idImagenAutor_c == null || $idImagenAutor_c == '') {
        $urlImg_c = '/contentfactory/post/wp-content/uploads/sites/4/2016/11/bigote.svg';
    } else {
        $urlImg_c = wp_get_attachment_image_src(intval($idImagenAutor_c), 'full')[0];
    }


    // Output Code
    $output = '
    <!-- el div con la clase vacia es el div padre personalizable en el addonn -->
    <div class="' . $clase_seccion . '">
    <div class="destacado-abc"> 
        <!-- el h3.titulocategoria será un campo rellenable si no se rellena lo ocultamos nosotros por css -->
        <h3 class="titulocategoria">' . $titulo_seccion . '</h3>
        <div class="bloque-abc">
            <article class="modulo-noticia">
                <figure> <a href="' . $permalink_a . '"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="' . $idImagenCabecera_a . '"> </a> </figure>
                <div class="content-a"> <a href="' . $permalink_a . '"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">' . $tag_a->name . '</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>' . $titulo1_a . '</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>' . $texto_a . '</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="' . $urlImg_a . '">' . $autor_a . '
                    </address>
                    <time>' . $fecha_a . '</time>
                </div>
                </a> </div>
            </article>
            <article class="modulo-noticia">
                <figure> <a href="' . $permalink_b . '"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="' . $idImagenCabecera_b . '"> </a> </figure>
                <div class="content-a"> <a href="' . $permalink_b . '"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">' . $tag_b->name . '</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>' . $titulo1_b . '</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>' . $texto_b . '</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="' . $urlImg_b . '">' . $autor_b . '
                    </address>
                    <time>' . $fecha_b . '</time>
                </div>
                </a> </div>
            </article>
            <article class="modulo-noticia">
                <figure> <a href="' . $permalink_c . '"> 
                <!-- esta imagen la trae del addon cabecero --> 
                <img src="' . $idImagenCabecera_c . '"> </a> </figure>
                <div class="content-a"> <a href="' . $permalink_c . '"> 
                <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
                <p class="nombrecategoria">' . $tag_c->name . '</p>
                <!-- h1 es el titulo del addon cabecero -->
                <h1>' . $titulo1_c . '</h1>
                <!-- este p es la entradilla del addon cabecero -->
                <p>' . $texto_c . '</p>
                
                <!-- este info reune la imagen del autor, el autor y la fecha del post -->
                <div class="info">
                    <address>
                    <img src="' . $urlImg_c . '">' . $autor_c . '
                    </address>
                    <time>' . $fecha_c . '</time>
                </div>
                </a> </div>
            </article>
        </div>
    </div>
    </div>
    ';

    return $output;
}
add_shortcode('destacado_abc', 'create_destacadoabc_shortcode');

vc_add_shortcode_param('postbytagselected_a', 'field_postbytagselected_a', get_template_directory_uri(__FILE__) . '/addons/js/postbytagselected_a.js');
function field_postbytagselected_a($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_a" value="' . esc_attr($value) . '" name="idpostseleccionado_a"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_a" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

vc_add_shortcode_param('postbytagselected_b', 'field_postbytagselected_b', get_template_directory_uri(__FILE__) . '/addons/js/postbytagselected_b.js');
function field_postbytagselected_b($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_b" value="' . esc_attr($value) . '" name="idpostseleccionado_b"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_b" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

vc_add_shortcode_param('postbytagselected_c', 'field_postbytagselected_c', get_template_directory_uri(__FILE__) . '/addons/js/postbytagselected_c.js');
function field_postbytagselected_c($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado_c" value="' . esc_attr($value) . '" name="idpostseleccionado_c"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected_c" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

// Create destacado-abc element for Visual Composer
add_action('vc_before_init', 'destacadoabc_integrateWithVC');
function destacadoabc_integrateWithVC()
{
    $arrayTags = [];
    if (is_admin()) {
        $tags = get_tags();
        foreach ($tags as $tag) {
            $arrayTags[$tag->name] = $tag->slug;
        }
    }

    vc_map(array(
        'name' => __('destacado-abc', 'textdomain'),
        'base' => 'destacado_abc',
        'show_settings_on_create' => true,
        'category' => __('Content', 'textdomain'),
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Titulo General', 'textdomain'),
                'param_name' => 'titulo_seccion',
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_a",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_a",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags A', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_a",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_b",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_b",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags B', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_b",
            ),
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag_c",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected_c",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags C', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected_c",
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
