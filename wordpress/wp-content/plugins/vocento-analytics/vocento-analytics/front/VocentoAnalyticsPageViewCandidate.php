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

}


add_action('init', array('VocentoAnalyticsPageViewCandidate', 'createPVC_rule'));
add_action('parse_request', array('VocentoAnalyticsPageViewCandidate', 'parseRequest'));