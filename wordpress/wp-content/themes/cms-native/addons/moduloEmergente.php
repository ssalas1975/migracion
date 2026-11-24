<?php



/******* MÓDULO EMERGENTE *******/

add_shortcode('vocento_modulo_emergente', 'modulo_emergente_function');
function modulo_emergente_function($atts, $content)
{
    extract(shortcode_atts(array(
        'el_class'    => '',
    ), $atts));
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    $img_logo = valueByUrlMedio(get_the_ID(), $url_medio, "logo_del_medio");

    $imgbloque = get_template_directory_uri() . '/img/cuadrati.svg';

    $categories = get_the_category(get_the_ID());
    $option = 'category_' . $categories[0]->term_id . '_url_medio';

    $valor = get_option($option);
    $dominio = "";
    $pieces = parse_url($valor);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $dominio =  str_replace("www.", "", $regs['domain']);
    }


    $html = '
        <div class="moduloemergente ' . $el_class . '">
            <button  class="close-btn"></button>
            <div class="bloque">
                <a href="http://' . $_SERVER['HTTP_HOST'] . '" title="' . strtoupper($categories[0]->name) . '.' . end(explode(".", $dominio)) . '" class="logo"><img src=' . $img_logo['url'] . ' alt="' . strtoupper($categories[0]->name) . '.' . end(explode(".", $dominio)) . '"></a>
				<div class="nativead">
				<span class="cuad-1"></span>
				<span class="cuad-2"></span>
				<span class="cuad-3"></span>
				<span class="cuad-4"></span>
				<span class="cuad-5"></span>
				</div>
                <img class="blocchi" src=' . $imgbloque . ' alt="">
                <p>' . $content . '</p>
            </div>
        </div>
    ';

    return $html;
}

function pinta_modulo_emergente()
{
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    $img_logo = valueByUrlMedio(get_the_ID(), $url_medio, "logo_del_medio");

    $imgbloque = get_template_directory_uri() . '/img/cuadrati.svg';

    $categories = get_the_category(get_the_ID());
    $option = 'category_' . $categories[0]->term_id . '_url_medio';

    $valor = get_option($option);
    $dominio = "";
    $pieces = parse_url($valor);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $dominio =  str_replace("www.", "", $regs['domain']);
    }
    $url_medio = preg_replace("/content-factory/", 'www', $_SERVER['HTTP_HOST']);

    $html = '
        <div class="moduloemergente">
            <button  class="close-btn"></button>
            <div class="bloque">
                <a href="http://' . $url_medio . '" title="' . strtoupper($categories[0]->name) . '.' . end(explode(".", $dominio)) . '" class="logo"><img src=' . $img_logo['url'] . ' alt="' . strtoupper($categories[0]->name) . '.' . end(explode(".", $dominio)) . '"></a>
				<div class="nativead">
				<span class="cuad-1"></span>
				<span class="cuad-2"></span>
				<span class="cuad-3"></span>
				<span class="cuad-4"></span>
				<span class="cuad-5"></span>
				</div>
                <img class="blocchi" src=' . $imgbloque . ' alt="">
                <p></p>
            </div>
        </div>
    ';

    return $html;
}
/******* MÓDULO EMERGENTE *******/
