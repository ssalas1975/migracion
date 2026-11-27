<?php
require_once(__DIR__."/FrontalClient.php");

function frontal_client_filter_content($section = "")
{
    $blogID = get_current_blog_id();
    $url_medio = $_SERVER['HTTP_HOST'];

    $terms = get_terms(array(
        'taxonomy' => 'medios',
        'hide_empty' => false,
    ));
    
    // $cache_header_skeleton_options = json_decode(get_site_option('submit_cache_header_settings'), true);
    $cache_header_skeleton_options = json_decode(get_option('submit_cache_header_settings'), true);

    if (xlsemanal_is_amp_endpoint()) {
        // $urlSkeleton = $cache_header_skeleton_options['url_skeleton_amp']."&section=".$section."&origen=xlsemanal";
        $urlSkeleton = $cache_header_skeleton_options['url_skeleton_amp']."&section=".$section."&origen=xlsemanal-amp";
    // $urlSkeleton = $cache_header_skeleton_options['url_skeleton_amp'];
    } else {
        $urlSkeleton = $cache_header_skeleton_options['url_skeleton']."&section=".$section."&origen=xlsemanal";
    }
    
    foreach ($terms as $term) {
        $url_medioCheck = get_field('url_medio', $term);
        if ($url_medioCheck==$url_medio) {
            $custom_value= get_field('url_cabecera', $term);
            $tagSite = get_field('site', $term);
        }
    }
    

    if (strpos($url_medio, 'srv.vocento.in') != false || empty($terms) || $custom_value == null) {
        $urlSkeleton = str_replace('@@medio@@', 'elcorreo.com', $urlSkeleton);
        $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration']);
        $headHTML = $frontalCliente->getHtmlLayoutURL();
        return $headHTML;
    }

    if (xlsemanal_is_amp_endpoint()) {
        // $urlSkeleton = $cache_header_skeleton_options['url_skeleton_amp'];
        $urlSkeleton = str_replace('@@medio@@', $custom_value, $cache_header_skeleton_options['url_skeleton_amp']."&section=".$section."&origen=xlsemanal-amp");
    } else {
        // $urlSkeleton = $cache_header_skeleton_options['url_skeleton'];
        $urlSkeleton = str_replace('@@medio@@', $custom_value, $cache_header_skeleton_options['url_skeleton']."&section=".$section."&origen=xlsemanal");
        // if (strrpos($url_medio, "sevilla.abc") > 0) {
        //     $urlSkeleton = str_replace('@@medio@@', $custom_value, $cache_header_skeleton_options['url_skeleton']);
        // } else {
        //     $urlSkeleton = str_replace('@@medio@@', $custom_value, $cache_header_skeleton_options['url_skeleton']."&section=".$section."&origen=xlsemanal");
        // }
    }
    
    // $urlSkeleton = str_replace('@@medio@@', $custom_value, $cache_header_skeleton_options['url_skeleton']);
    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('pre-www.', 'pre-', $urlSkeleton);
        }
    } else {
        if (strrpos($urlSkeleton, "sevilla.abc") > 0) {
            $urlSkeleton = str_replace('www.', '', $urlSkeleton);
        }
    }



    if ($cache_header_skeleton_options['url_skeleton'] != "" || $cache_header_skeleton_options['url_skeleton'] != null) {
        $frontalCliente = new FrontalClient($urlSkeleton, 'externo/blogs', $cache_header_skeleton_options['duration']);
        $headHTML = $frontalCliente->getHtmlLayoutURL();
        return $headHTML;
    } else {
        $frontalCliente = new FrontalClient($cache_header_skeleton_options['host'], 'externo/blogs', $cache_header_skeleton_options['duration']);
        $headHTML = $frontalCliente->getHtmlLayoutFrontalService();
        return $headHTML;
    }
}
// add_filter('frontal_client_content', 'frontal_client_filter_content');

add_action('frontal_client_filter_content', 'frontal_client_filter_content');


/**
* Función para recuperar los parciales
*/
function requireToVar($file)
{
    ob_start();
    require($file);
    return ob_get_clean();
}
