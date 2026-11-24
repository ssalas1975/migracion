<?php



/******* PÁRRAFO *******/

add_shortcode( 'vocento_wp_text', 'parrafo_function' );
function parrafo_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'el_class'      => ''
    ), $atts ) );

    $html = '
        <p class="'.$el_class.' parrafocount">'.$content.'</p>
    ';
    return $html;
}
/******* PÁRRAFO *******/
