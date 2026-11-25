<?php


/******* LISTA VERTICAL *******/

add_shortcode( 'lista_vertical_item', 'lista_vertical_item_function' );
function lista_vertical_item_function( $atts, $content ) {
    extract( shortcode_atts( array(
        'finallista'    => '',
		        'animacionway' => '',
        'el_class'    => '',
    ), $atts ) );   
    if($atts['animacionway']==true){
        $imgView = "";

    }else{		
        $imgView = "animated fadeIn active";
	}
    $html .= "<li ".$atts['el_class']."><p><strong>&nbsp;".$atts['encabezado']."&nbsp;</strong>".$content."</p>";
    
    //si tiene una imagen configurada la mostramos...
    if(wp_get_attachment_url($atts['image_url'])!=""){
        //comprobamos si se ha configurado una imagen para hacer zoom y si es as� la mostramos
        if(isset($atts['image_url_zoom'])){
            $html.= '  <div class="zoom evenZoom" data-zoomed="'.wp_get_attachment_url($atts['image_url_zoom']).'" >
                    <img class="content_anim1 '.$imgView.'" src="'.wp_get_attachment_url($atts['image_url']).'"  alt="zoom">
                    </div>';
        }else{
            $html.= '  
                    <img class="content_anim1 '.$imgView.'" src="'.wp_get_attachment_url($atts['image_url']).'">
                    ';
        }
    }else{
        if(isset($atts['image_url_zoom'])){
            $html.= '  <div class="zoom evenZoom" data-zoomed="'.wp_get_attachment_url($atts['image_url_zoom']).'" >
                    <img class="content_anim1 '.$imgView.'" src="'.$atts['image_url'].'"  alt="zoom">
                    </div>';
        }else{
            $html.= '  
                    <img class="content_anim1 '.$imgView.'" src="'.$atts['image_url'].'">
                    ';
        }
    }
    //comprobando si se ha marcado el checkbox de final de lista
    if($atts['finallista']=="true"){
        $html .= '</ul>';
    }
    return $html;
}
/******* LISTA VERTICAL *******/
