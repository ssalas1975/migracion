<?php

/**
 * VocentoAnalyticsPageViewCandidate short summary.
 *
 * VocentoAnalyticsPageViewCandidate description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VocentoAnalyticsPageViewCandidate
{
    static function generateXML()
    {

        header('Content-type: text/xml');

        $container = VocentoAnalyticsContainer::getFrontContainer();

        $html = file_get_contents(vcanly_PLUGIN_PATH . 'vocento-analytics/inc/comscore/page_view_candidate.xml');

        $html = str_replace("##site_name##", $container->comScore->data['voc_site'], $html);

        return $html;
    }

    static function createPVC_rule()
    {

        add_rewrite_rule(
            'page_view_candidate.xml',
            'index.php?vcanly_pvc',
            'top'
        );
        flush_rewrite_rules();
    }

    static function parseRequest(&$wp)
    {

        if ($wp->matched_query && strpos($wp->matched_query, 'vcanly_pvc') !== false) {
            echo VocentoAnalyticsPageViewCandidate::generateXML();
            exit();
        }


        return;
    }
    
    static function load_script_DTM_head()
    {
        $container = VocentoAnalyticsContainer::getFrontContainer();
        $datapropertyid = $container->dtm->data["datapropertyid"];
        if ($container->dtm->enable) {
            $html = file_get_contents(getcwd() . '/wp-content/plugins/vocento-analytics/vocento-analytics/inc/dtm/header.html');
            $html = str_replace("@datapropertyid@", $datapropertyid, $html); 
            echo $html;
        }



    }

    static function load_noscrip_Content()
    {
        $container = VocentoAnalyticsContainer::getFrontContainer();
        $reporsuit = $container->dtm->data["reportsuit"];
        $id = $container->dtm->data["id"];
        
        if ($container->dtm->enable) {
            $html = file_get_contents(getcwd() . '/wp-content/plugins/vocento-analytics/vocento-analytics/inc/dtm/body.html');
            $html = str_replace("@voces@", $reporsuit, $html);

            switch_to_blog( get_current_blog_id() );
            $html = str_replace("@number@", get_option('dtmid'), $html);
            restore_current_blog();
            
            
            $html = str_replace("@permalink@", get_permalink(), $html);
            return $html;
        }
    }

}


add_action('init', array('VocentoAnalyticsPageViewCandidate', 'createPVC_rule'));
add_filter( 'the_content', array('VocentoAnalyticsPageViewCandidate', 'load_noscrip_Content') );
add_action('wp_head', array('VocentoAnalyticsPageViewCandidate', 'load_script_DTM_head') );
add_action('parse_request', array('VocentoAnalyticsPageViewCandidate', 'parseRequest'));