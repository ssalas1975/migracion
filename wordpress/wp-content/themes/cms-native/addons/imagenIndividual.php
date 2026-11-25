<?php
    
/****** IMAGEN INDIVIDUAL CON PIE DE FOTO *******/

add_shortcode( 'vocento_single_image', 'imagen_individual_function' );
function imagen_individual_function( $atts ) {
    extract( shortcode_atts( array(
        'image_url'     => 'something',
        'texto_lateral' => 'Sin texto',
        'piedefoto'     => '',
        'animacionway'     => '',
        'el_class'      => ''
    ), $atts ) );

    $img = wp_get_attachment_image($image_url, 'large');
    $claseCss = $el_class;

    //comprobando si se ha marcado la animaci�n y a�adiendola si es as�
    if($atts['animacionway']=="true"){
        wp_enqueue_script( 'wayImagenIndividual', '/wp-content/themes/cms-native/js/addons_js/waypoints/ImagenIndividualWaypoints.js');

        $html .= '
        <figure id="cuatro" class="content_anim4 imgarticle '.$claseCss.'">            
                '.$img;
        if($piedefoto !=""){
          $html .= ' <figcaption>
                <p class="parrafocount">'.$piedefoto.'</p>
            </figcaption>';
        }
        $html .= ' </figure>';
    }else{

        $html .= '
        <figure id="cuatro" class="content_anim4 animated fadeIn active imgarticle '.$claseCss.'">
           '.$img;
        if($piedefoto !=""){
            $html .= ' <figcaption>
                <p class="parrafocount">'.$piedefoto.'</p>
            </figcaption>';
        }
        $html .= ' </figure>';
    }

    return $html;
}
/****** IMAGEN INDIVIDUAL CON PIE DE FOTO *******/

