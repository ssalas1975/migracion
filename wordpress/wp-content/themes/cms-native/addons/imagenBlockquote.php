<?php


/******** IMAGEN BLOQUOTE ******/

add_shortcode( 'vocento_bloquote_image', 'imagen_bloquote_function' );
function imagen_bloquote_function( $atts,$content ) {
    extract( shortcode_atts( array(
        'image_url'     => 'something',
        'texto_lateral' => 'Sin texto',
        'animacionway'    => '',
        'el_class'      => '',
        'alignment'  => '',
    ), $atts ) );

    $img = wp_get_attachment_image_src($image_url, 'large');
    $alt = get_post_meta($image_url, '_wp_attachment_image_alt', true);
    $claseCss = $el_class;

        $html = '<blockquote class="'.$alignment.' '.$claseCss.'">
                <img src='.$img[0].' alt="'.$alt.'"><p>'.$content.'</p></blockquote>';

    return $html;
}
/******** IMAGEN BLOQUOTE ******/


