<?php
require_once(__DIR__ . "/FrontalClient.php");

function frontal_client_filter_content($url = 0)
{
    $blogID = get_current_blog_id();
    $url_medio = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
    $ifAMP = "";
    $tipodispositivo = '';

    $dataUrlskeleton = array(
        'origen' => '',
        'device' => ''
    );

    $terms = get_terms(array(
        'taxonomy' => 'Medios',
        'hide_empty' => false,
    ));


    $cache_header_skeleton_options = json_decode(get_option('submit_cache_header_settings'), true);
    $urlSkeleton = $cache_header_skeleton_options['url_skeleton'];
    // $dataUrlskeleton['origen'] = 'xlsemanal';
    // $dataUrlskeleton['device'] = 'desktop';
    // $tipodispositivo = 'desktop';

    $tipodispositivo = 'desktop';
    $dataUrlskeleton = [];


    $slug = "";

    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            $url_medioCheck_slug = get_field('url_medio', $term);
            $custom_value = get_field('url_cabecera', $term);
            $tagSite = get_field('site', $term);
            $slug = str_replace("http://content-factorybra", "https://www", $url_medio);
            // var_dump($url_medio);
        }
    }



    $urlSkeleton = str_replace('@@seccion@@', "home", $urlSkeleton);

    if (strpos($url_medio, 'srv.vocento.in') != false) {
        $urlSkeleton = str_replace('@@medio@@', 'elcorreo.com', $urlSkeleton);
        // $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        // $headHTML = $frontalCliente->getHtmlLayoutURL("$_SERVER[REQUEST_URI]", $ifAMP, $tipodispositivo, "home");

        $tipodispositivo = 'desktop';
        $dataUrlskeleton = [];

        $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutURL("$_SERVER[REQUEST_URI]", $ifAMP, $tipodispositivo, 'brandencontent');

        $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
        $subst = "";
        $headHTML = preg_replace($re, $subst, $headHTML);

        return $headHTML;
    }

    // //////////////////////////////////////////////////////////

    $pos = strpos($slug, "abc");

    if ($pos !== false) {
        $urlSkeleton = $cache_header_skeleton_options['url_skeleton_abc'];
        // $dataUrlskeleton['section'] = $section;
        $dataUrlskeleton['origen'] = 'xlsemanal';
    } else {
        $urlSkeleton = str_replace('https://www.@@medio@@', $slug, $cache_header_skeleton_options['url_skeleton']);
        // $dataUrlskeleton['section'] = $section;
        $dataUrlskeleton['origen'] = 'xlsemanal';
    }

    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('pre-www.', 'pre-', $urlSkeleton);
        }
    } else {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('www.', '', $urlSkeleton);
        }
    }

    // if( AkamaiHelper::GET_DEVICE() == 'desktop' )
    // {
    //     // $urlSkeleton = $urlSkeleton . '&device=desktop';
    //     $dataUrlskeleton['device'] = 'desktop';
    //     $tipodispositivo = 'desktop';
    // }

    // if(AkamaiHelper::GET_DEVICE() == 'mobile' )
    // {
    //     // $urlSkeleton = $urlSkeleton . '&device=mobile';
    //     $dataUrlskeleton['device'] = 'mobile';
    //     $tipodispositivo = 'mobile';
    // }

    $urlSkeleton = htmlentities($urlSkeleton);
    $urlSkeleton = str_replace('@@seccion@@', 'contentfactory', $urlSkeleton);
    $urlSkeleton = str_replace("http://content-factory", "https://www", $urlSkeleton);
    $url_medioCheck_slug = str_replace("http://content-factory.", "", $url_medioCheck_slug);


    if ($url != 0) {
        $urlSkeleton = "https://www.abc.es/api/v4/headers/skeleton.html";
        $dataUrlskeleton['device'] = 'desktop';
        $dataUrlskeleton['type'] = 'responsive26';


        $tipodispositivo = 'desktop';
        $dataUrlskeleton = [];


        if ($cache_header_skeleton_options['url_skeleton'] != "" || $cache_header_skeleton_options['url_skeleton'] != null) {
            // $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
            // $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');

            $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
            $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');

            $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
            $subst = "";
            $headHTML = preg_replace($re, $subst, $headHTML);

            return $headHTML;
        } else {
            $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
            $headHTML = $frontalCliente->getHtmlLayoutFrontalService($url_medioCheck_slug, $ifAMP);

            $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
            $subst = "";
            $headHTML = preg_replace($re, $subst, $headHTML);

            return $headHTML;
        }
    }


    if ($cache_header_skeleton_options['url_skeleton'] != "" || $cache_header_skeleton_options['url_skeleton'] != null) {
        // $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        // $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');


        $tipodispositivo = 'desktop';
        $dataUrlskeleton = [];

        $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');

        $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
        $subst = "";
        $headHTML = preg_replace($re, $subst, $headHTML);

        return $headHTML;
    } else {
        $frontalCliente = new FrontalClient($cache_header_skeleton_options['host'], 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutFrontalService($url_medioCheck_slug, $ifAMP);

        $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
        $subst = "";
        $headHTML = preg_replace($re, $subst, $headHTML);

        return $headHTML;
    }
}


add_action('frontal_client_filter_content', 'frontal_client_filter_content', 10, 1);




function frontal_client_filter_content_2026($url = "null")
{

    $blogID = get_current_blog_id();
    $url_medio = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";

    $medio = str_replace("http://content-factory.", "", $url_medio);

    $pos = strpos($medio, "abc");
    $urlSkeleton = "";
    $ifAMP = "";
    $tipodispositivo = 'desktop';
    $dataUrlskeleton = [];

    if ($pos !== false) {
        $urlSkeleton = "https://www.abc.es/api/v4/headers/skeleton.html?type=responsive26&headermini=true";
    } else {
        $urlSkeleton = "https://www.@@medio@@/api/v4/headers/skeleton.html?type=responsive26&headermini=true";
        $urlSkeleton = str_replace('@@medio@@', $medio, $urlSkeleton);
    }

    $re = '/(.*?)\.(.*?)/m';
    preg_match_all($re, $medio, $matches, PREG_SET_ORDER, 0);
    $medio = $matches[0][1];

    $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
    $headHTML = $frontalCliente->getHtmlLayoutURL($medio, $ifAMP, $tipodispositivo, 'brandencontent');

    $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
    $subst = "";
    $headHTML = preg_replace($re, $subst, $headHTML);

    return $headHTML;




    $ifAMP = "";
    $tipodispositivo = '';

    $dataUrlskeleton = array(
        'origen' => '',
        'device' => ''
    );

    $terms = get_terms(array(
        'taxonomy' => 'Medios',
        'hide_empty' => false,
    ));


    $cache_header_skeleton_options = json_decode(get_option('submit_cache_header_settings'), true);
    $urlSkeleton = $cache_header_skeleton_options['url_skeleton'];
    $dataUrlskeleton['origen'] = 'xlsemanal';
    $dataUrlskeleton['device'] = 'desktop';
    $tipodispositivo = 'desktop';
    $slug = "";

    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck == $url_medio) {
            $url_medioCheck_slug = get_field('url_medio', $term);
            $custom_value = get_field('url_cabecera', $term);
            $tagSite = get_field('site', $term);
            $slug = str_replace("http://content-factorybra", "https://www", $url_medio);
            // var_dump($url_medio);
        }
    }



    $urlSkeleton = str_replace('@@seccion@@', "home", $urlSkeleton);

    if (strpos($url_medio, 'srv.vocento.in') != false) {
        $urlSkeleton = str_replace('@@medio@@', 'elcorreo.com', $urlSkeleton);
        $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutURL("$_SERVER[REQUEST_URI]", $ifAMP, $tipodispositivo, "home");
        return $headHTML;
    }



    $pos = strpos($slug, "abc");

    if ($pos !== false) {
        $urlSkeleton = $cache_header_skeleton_options['url_skeleton_abc'];
        $dataUrlskeleton['origen'] = 'xlsemanal';
    } else {
        $urlSkeleton = str_replace('https://www.@@medio@@', $slug, $cache_header_skeleton_options['url_skeleton']);
        $dataUrlskeleton['origen'] = 'xlsemanal';
    }

    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('pre-www.', 'pre-', $urlSkeleton);
        }
    } else {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('www.', '', $urlSkeleton);
        }
    }



    $urlSkeleton = htmlentities($urlSkeleton);
    $urlSkeleton = str_replace('@@seccion@@', 'contentfactory', $urlSkeleton);
    $urlSkeleton = str_replace("http://content-factory", "https://www", $urlSkeleton);
    $url_medioCheck_slug = str_replace("http://content-factory.", "", $url_medioCheck_slug);


    if ($url != "null") {
        $urlSkeleton = "https://www.abc.es/api/v4/headers/skeleton.html";
        $dataUrlskeleton['device'] = 'desktop';
        $dataUrlskeleton['type'] = 'responsive26';

        $urlSkeleton = 'https://www.elcorreo.com/api/v4/headers/skeleton.html?';

        // $urlSkeleton = "https://www.abc.es/api/v4/headers/skeleton.html?section=home&type=responsive26&device=desktop";

        if ($cache_header_skeleton_options['url_skeleton'] != "" || $cache_header_skeleton_options['url_skeleton'] != null) {
            $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
            $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');
            return $headHTML;
        } else {
            $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
            $headHTML = $frontalCliente->getHtmlLayoutFrontalService($url_medioCheck_slug, $ifAMP);
            return $headHTML;
        }
    }

    if ($cache_header_skeleton_options['url_skeleton'] != "" || $cache_header_skeleton_options['url_skeleton'] != null) {
        $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutURL($url_medioCheck_slug, $ifAMP, $tipodispositivo, 'brandencontent');
        return $headHTML;
    } else {
        $frontalCliente = new FrontalClient($cache_header_skeleton_options['host'], 'externo/blogs', $cache_header_skeleton_options['duration'], $dataUrlskeleton);
        $headHTML = $frontalCliente->getHtmlLayoutFrontalService($url_medioCheck_slug, $ifAMP);
        return $headHTML;
    }
}


add_action('frontal_client_filter_content_2026', 'frontal_client_filter_content_2026', 10, 1);



function frontal_client_filter_content_test($url = 0)
{
    $blogID = get_current_blog_id();
    $url_medio = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
    $ifAMP = "";
    $tipodispositivo = '';

    $cache_header_skeleton_options = json_decode(get_option('submit_cache_header_settings'), true);
    $urlSkeleton = $cache_header_skeleton_options['url_skeleton'];


    if (str_contains($url_medio, 'srv.vocento.in')) {
        $url_medio = "elcorreo.com";
    }

    $medio = str_replace("http://content-factory.", "", $url_medio);

    $urlSkeletonABC = $cache_header_skeleton_options['url_skeleton_abc'];
    $urlSkeletonPORTALES = $cache_header_skeleton_options['url_skeleton'];

    $pos = strpos($medio, "abc");
    $urlSkeleton = "";
    $ifAMP = "";
    $tipodispositivo = 'desktop';
    $dataUrlskeleton = [];

    // $medio = 'elcorreo.com'; //-> TODO PRODUCCION


    if ($pos !== false) {
        $urlSkeleton = "https://www.abc.es/api/v4/headers/skeleton.html?type=responsive26&headermini=true";
    } else {
        $urlSkeleton = "https://www.@@medio@@/api/v4/headers/skeleton.html?type=responsive26&headermini=true";
        $urlSkeleton = str_replace('@@medio@@', $medio, $urlSkeleton);
    }

    $re = '/(.*?)\.(.*?)/m';
    preg_match_all($re, $medio, $matches, PREG_SET_ORDER, 0);
    $medio = $matches[0][1];

    $frontalCliente = new FrontalClient($urlSkeleton, 'home', 90, $dataUrlskeleton);
    $headHTML = $frontalCliente->getHtmlLayoutURL($medio, $ifAMP, $tipodispositivo, 'brandencontent');

    $re = '/<div class="v-h__n v-h__n--1">(.*?)<\/div>/ms';
    $subst = "";
    $headHTML = preg_replace($re, $subst, $headHTML);

    return $headHTML;

}


add_action('frontal_client_filter_content_test', 'frontal_client_filter_content_test', 10, 1);


/**
 * Función para recuperar los parciales
 */
function requireToVar($file)
{
    ob_start();
    require($file);
    return ob_get_clean();
}
