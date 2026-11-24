<?php

/******* MARKER ANIMATED *******/

add_shortcode( 'vocento_marquer_animated', 'marker_animated_function' );
function marker_animated_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'animacionway'    => '',
        'el_class'    => '',
    ), $atts ) );
    
    //comprobamos si est� activada la opci�n de animar el contenido
    if(isset($atts['animacionway'])){
        
        wp_enqueue_script( 'wayMarker', '/wp-content/themes/cms-native/js/addons_js/waypoints/markerWay.js');
        
        $content = str_replace('<span style="text-decoration: underline;">','<strong class="subrallado_2">',$content);
        $content = str_replace('</span>','</strong>',$content);

    }else{
        $content = str_replace('<span style="text-decoration: underline;">','<strong class="subrallado_2 active">',$content);   
        $content = str_replace('</span>','</strong>',$content);

    }
    $content = str_replace("<p>","<p class='parrafocount'>", $content);

    $html .= '<p class="'.$el_class.' parrafocount">'.$content.'</p>';

    return $html;
}
/******* MARKER ANIMATED *******/


?>
