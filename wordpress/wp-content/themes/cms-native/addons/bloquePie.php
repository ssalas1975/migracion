<?php

/******* BLOQUE PIE *******/
add_shortcode('vocento_bloque_pie', 'bloque_pie_function');
function bloque_pie_function($atts, $content)
{
    extract(shortcode_atts(array(
        'el_class'    => '',
    ), $atts));
    
    // $url = preg_replace("/content-factory/", 'http://www', $_SERVER['HTTP_HOST']);
    $url = preg_replace("/content-factory./", '', $_SERVER['HTTP_HOST']);
    $permalink = get_the_permalink();
    $html = "
    <!--11111111111 ".$_SERVER['HTTP_HOST']." -->
        <div class='bloque'>
         <div class='compartir'><p>Compartir</p><ul>
                <li class='facebook'>
                            <a href='https://www.facebook.com/sharer/sharer.php?u=".$url.$permalink."' target='_blank' title='Compartelo en Facebook' rel='nofollow'>
                        <i class='facebook'></i>
                    </a>
                </li><li class='twitter dd'>
                    <a href='https://twitter.com/intent/tweet?text=".$url.$permalink."' class='twitter' target='_blank' title='Compartelo en Twitter' rel='nofollow'>
                        <i class='twitter'></i>
                    </a>
                </li><li class='whatsapp'>
                    <a href='whatsapp://send?text=".$url.$permalink."' class='whatsapp' target='_blank' title='Compartelo en whatsapp' rel='nofollow'>
                        <i class='whatsapp'></i>
                    </a>
                </li>
            </ul></div>
        <div class='pie-ad ".$el_class."'>
            <p>".$content."</p>
        </div></div>";
    //registrando script para extracci�n de datos de pi�
    wp_enqueue_script('datosPie_script', '/wp-content/themes/cms-native/js/addons_js/moduloemergente.js');
    return $html;
}
/******* BLOQUE PIE *******/
