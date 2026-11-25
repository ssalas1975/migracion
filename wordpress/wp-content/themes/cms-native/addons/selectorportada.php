<?php

// Create Shortcode selector_portada
// Use the shortcode: [selector_portada tagvalue="" postvalue="" backgroundimage="" logoimg="" subtitlelogo="" tituloderecha="" antetituloderecha="" cuerpotextoderecha=""]
function create_selectorportada_shortcode($atts)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'selectedtag' => '',
            'postbytagselected' => '',
            'backgroundimage' => '',
            'logoimg' => '',
            'subtitlelogo' => '',
            'tituloderecha' => '',
            'antetituloderecha' => '',
            'cuerpotextoderecha' => '',
            'clase_seccion' => '',
        ),
        $atts,
        'selector_portada'
    );
    // Attributes in var
    $selectedtag = $atts['selectedtag'];
    $postbytagselected = $atts['postbytagselected'];
    $backgroundimage = $atts['backgroundimage'];
    $logoimg = $atts['logoimg'];
    $subtitlelogo = $atts['subtitlelogo'];
    $tituloderecha = $atts['tituloderecha'];
    $antetituloderecha = $atts['antetituloderecha'];
    $cuerpotextoderecha = $atts['cuerpotextoderecha'];
    $clase_seccion = $atts['clase_seccion'];

    $tag = get_term_by('slug', $selectedtag, 'post_tag');
    $post = get_post($postbytagselected);

    $idImagenAutor = get_post_meta($post->ID, 'image_url', true);
    // $idImagenCabecera = get_post_meta($post->ID, 'imagencabecera', true);
    // $idImagenCabecera = '/contentfactory/post'.get_post_meta($post->ID, '_webdados_fb_open_graph_specific_image', true);
    $idImagenCabecera = get_post_meta($post->ID, '_webdados_fb_open_graph_specific_image', true);
    $idImagenCabecera = str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", $idImagenCabecera);



    $autor = get_post_meta($post->ID, 'autor', true);
    $texto = get_post_meta($post->ID, 'texto', true);
    $titulo1 = get_post_meta($post->ID, 'titulo1', true);

    // $permalink = get_permalink($post->ID);
    $permalink = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', get_permalink($post->ID));

    $fecha = get_the_date('j F, Y', $post) . ' - ' . get_the_date('G:i', $post);


    $findme = 'https://ms-brandedcontent.srv.vocento.in';
    $pos = strpos($idImagenCabecera, $findme);




    if ($pos !== false) {
        $idImagenCabecera = str_replace('https://ms-brandedcontent.srv.vocento.in', '/contentfactory/post', $idImagenCabecera);
    } else {
        $findme = 'contentfactory/post';
        $pos = strpos($idImagenCabecera, $findme);
        if ($pos == false) {
            echo '<!-- ___________----____ ';
            var_dump($idImagenCabecera);
            echo '-->';
            $idImagenCabecera = '/contentfactory/post' . $idImagenCabecera;
        } else {
            echo '<!-- ___________----___------ ____ ';
            var_dump($idImagenCabecera);
            echo '-->';
        }
    }


    // Output Code
    $output = '';
    $output .= '
    <!-- el div con la clase vacia es el div padre personalizable en el addonn -->
    <div class="' . $clase_seccion . '">
    <div class="destacado-portada">
        <div class="bloque-a">
        <article class="modulo-noticia">
            <figure> <a href="' . $permalink . '"> 
            <!-- esta imagen la trae del addon cabecero 2222222 --> 
            <img src="' . $idImagenCabecera . '"> </a> </figure>
            <div class="content-a"> <a href="' . $permalink . '"> 
            <!-- p.nombrecategoria es el span.p-content sobre titulo del addon cabecero -->
            <p class="nombrecategoria">' . $tag->name . '</p>
            <!-- h1 es el titulo del addon cabecero -->
            <h1>' . $titulo1 . '</h1>
            <!-- este p es la entradilla del addon cabecero -->
            <p>' . $texto . '</p>
            
            <!-- este info reune la imagen del autor, el autor y la fecha del post -->
            <div class="info">
                <address>
                <img src="' . wp_get_attachment_image_src(intval($idImagenAutor), 'full')[0] . '">' . $autor . '
                </address>
                <time>' . $fecha . '</time>
            </div>
            </a> </div>
        </article>
        </div>
        <div class="bloque-b">
        <div class="logo-marca">
            <p>' . $subtitlelogo . '</p>
            <img src="' . wp_get_attachment_image_src(intval($logoimg), 'full')[0] . '"> </div>
        <h1>' . $tituloderecha . '</h1>
        <p class="subtitulo">' . $antetituloderecha . '</p>
        <p class="entradilla">' . $cuerpotextoderecha . '</p>
        </div>
    </div>
    </div>';

    return $output;
}
add_shortcode('selector_portada', 'create_selectorportada_shortcode');

vc_add_shortcode_param('postbytagselected', 'field_postbytagselected', get_template_directory_uri(__FILE__) . '/addons/js/postbytagselected.js');
function field_postbytagselected($settings, $value)
{
    echo '
    <div class="postcategoryselected">
        <input type="hidden" class="idpostseleccionado" value="' . esc_attr($value) . '" name="idpostseleccionado"/>
    </div>
    ';

    return '
    <select name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-input wpb-select postbytagselected" data-option="alessandro">
    <option class="alessandro" value="0" selected="selected">Selecciona...</option>
    </select>
    ';
}

// Create Selector Portada element for Visual Composer
add_action('vc_before_init', 'selectorportada_integrateWithVC');
function selectorportada_integrateWithVC()
{
    $tags = get_tags();
    $arrayTags = [];
    foreach ($tags as $tag) {
        $arrayTags[$tag->name] = $tag->slug;
    }
    vc_map(array(
        'name' => __('Selector Portada', 'textdomain'),
        'base' => 'selector_portada',
        'show_settings_on_create' => true,
        'category' => __('Content', 'textdomain'),
        'params' => array(
            array(
                "type" => "dropdown",
                "heading" => __('Tag', 'textdomain'),
                "param_name" => "selectedtag",
                "value" => $arrayTags,
                'save_always' => true,
                "admin_label" => true
            ),
            array(
                "type" => "postbytagselected",
                'holder' => 'div',
                'admin_label' => true,
                'heading' => __('Post Por Tags', 'textdomain'),
                "class" => "",
                "param_name" => "postbytagselected",
            ),
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Imagen Fondo', 'textdomain'),
                'param_name' => 'backgroundimage',
            ),
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Logo', 'textdomain'),
                'param_name' => 'logoimg',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Subtitle Logo', 'textdomain'),
                'param_name' => 'subtitlelogo',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Título', 'textdomain'),
                'param_name' => 'tituloderecha',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Ante Título', 'textdomain'),
                'param_name' => 'antetituloderecha',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Cuerpo Texto', 'textdomain'),
                'param_name' => 'cuerpotextoderecha',
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
