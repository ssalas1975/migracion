<?php


/******* LISTA VERTICAL *******/

add_shortcode( 'lista_vertical_comienzo', 'lista_vertical_comienzo_function' );
function lista_vertical_comienzo_function( $atts ) {
    extract( shortcode_atts( array(
        'titulo'      => '',
        'animacionway'    => '',
        'el_class'    => '',
        'values'      => '',
    ), $atts ) );

    //echo rawurldecode($values);
    //echo $titulo;
    $data = json_decode(rawurldecode($values));
    //print_r($data);
    //comprobando si se ha marcado el checkbox de animacion para inscrutar el script
    if($atts['animacionway']=="true"){
        wp_enqueue_script( 'wayListaVImg', '/wp-content/themes/cms-native/js/addons_js/waypoints/listaVerticalWay.js');

        $html .= '<ul id="zero" class="listavertical '.$el_class.'">';
        $imgView = "";
    }else{
        $html .= '<ul id="zero" class="listavertical '.$el_class.'">';       
        $imgView = "animated fadeIn active";
    }
    $html .= '<li><h2>'.$atts['titulo'].'</h2></li>';
   
    return $html;
}
/******* LISTA VERTICAL *******/
