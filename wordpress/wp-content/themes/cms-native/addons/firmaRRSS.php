<?php



/******* FIRMA RRSS *******/


add_shortcode('vocento_firmaRRSS', 'Vocento_firmaRRSS_function');
function Vocento_firmaRRSS_function($atts)
{
    extract(shortcode_atts(array(
        'autorfirma'     =>  '',
        'image_url'=>'',
        'el_class'      => ''
    ), $atts));
    //obteniendo titulo para compartir en RRSS
    //$titulo1 = get_post_meta( get_the_ID(), 'titulo1', true );
    if (isset($atts['el_class'])) {
        $clasePerso = $atts['el_class'];
    } else {
        $clasePerso = "";
    }
    //firma autor
    if (isset($atts['autorfirma'])) {
        $autorfirma = $atts['autorfirma'];
    } else {
        $autorfirma = 'Content Factory' ;
    }
    //añadiendo metadatos para relacionados
    if (! add_post_meta(get_the_ID(), 'autor', $autorfirma, true)) {
        update_post_meta(get_the_ID(), 'autor', $autorfirma);
    }

    if (! add_post_meta(get_the_ID(), 'image_url', $atts['image_url'], true)) {
        update_post_meta(get_the_ID(), 'image_url', $atts['image_url']);
    }

    //obteniendo imagen
    $imgAutor="";
    if (isset($atts['image_url'])) {
        $imgAutor = '<img src="'.wp_get_attachment_url($atts['image_url']).'">';
    }
    //fecha de modificación o creación si es nuevo
    $fecha = get_the_modified_date('j F, Y') .' - '. get_the_modified_time('G:i');
    if (!isset($fecha)) {
        $fecha = get_the_time('j F, Y') .' - '. get_the_time('G:i') ;
    }

    $url = preg_replace("/content-factory./", '', $_SERVER['HTTP_HOST']);
    $permalink = str_replace("https://ms-brandedcontent.srv.vocento.in", "/contentfactory/post", get_the_permalink());
    return "<div class='firmaRRSS ".$clasePerso."'>
       <div class='info'><address class='firmaAutor' data-autor='".$autorfirma."'>".$imgAutor." ". $autorfirma ."</address><time>
         ".$fecha."</time></div>
           <div class='compartir'><p>Compartir</p><ul>
                <li class='facebook'>
                            <a href='https://www.facebook.com/sharer/sharer.php?u=https://www.".$url.$permalink."' data-voc-vtm-id='compartirrrss' data-voc-vtm-info='facebook' target='_blank' title='Compartelo en Facebook' rel='nofollow'>
                        <i class='facebook'></i>
                    </a>
                </li><li class='twitter'>
                    <a href='https://twitter.com/intent/tweet?text=https://www.".$url.$permalink."' ata-voc-vtm-id='compartirrrss' data-voc-vtm-info='twitter' class='twitter' target='_blank' title='Compartelo en Twitter' rel='nofollow'>
                        <i class='twitter'></i>
                    </a>
                </li><li class='whatsapp'>
                    <a href='whatsapp://send?text=https://www.".$url.$permalink."' data-voc-vtm-id='compartirrrss' data-voc-vtm-info='whatsapp' class='whatsapp' target='_blank' title='Compartelo en whatsapp' rel='nofollow'>
                        <i class='whatsapp'></i>
                    </a>
                </li>
    </ul></div></div>";
}

/******* FIRMA RRSS *******/
