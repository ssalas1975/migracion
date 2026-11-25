<?php


/******* LISTA VERTICAL *******/

add_shortcode( 'lista_vertical', 'listavertical_function' );
function listavertical_function( $atts ) {
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
    $html .= '<li><h2>'.$atts['encabezado'].'</h2></li>';
    foreach($data as $res)
    {
        $html .= "<li ".$res->claseExtra."><p><strong>&nbsp;".$res->encabezado."&nbsp;</strong>".$res->content."</p>";
       
        //si tiene una imagen configurada la mostramos...
        if(wp_get_attachment_url($res->image_url)!=""){
            //comprobamos si se ha configurado una imagen para hacer zoom y si es así la mostramos
            if(isset($res->image_url_zoom)){
                $html.= '  <div class="zoom evenZoom" data-zoomed="'.wp_get_attachment_url($res->image_url_zoom).'" >
                    <img class="content_anim1 '.$imgView.'" src="'.wp_get_attachment_url($res->image_url).'"  alt="zoom">
                    </div>';
            }else{
                $html.= '  
                    <img class="content_anim1 '.$imgView.'" src="'.wp_get_attachment_url($res->image_url).'">
                    ';
            }
        }

        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
/******* LISTA VERTICAL *******/
