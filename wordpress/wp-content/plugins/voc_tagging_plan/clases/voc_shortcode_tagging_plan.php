<?php

function voonto_head_shortcode($atts, $content = null)
{
    require __DIR__ . '/voc_taggin.php';
    $options = get_option('settings_voonto');
    $data_property_id = $options['data_poperty'];
    if ($options['data_cmp'] != null || $options['data_cmp'] != "") {
        $cmp = "data-cmp='".$options['data_cmp']."' data-cmp-position='".$options['data_cmp_position']."'";
    }
    if ($options['data_legacy_comscore'] != null || $options['data_legacy_comscore'] != "") {
        $comscore = 'data-legacy-comscore="'.$options['data_legacy_comscore'].'"';
    }
    if ($options['data_legacy_adbd'] != null || $options['data_legacy_adbd'] != "") {
        $adbd = 'data-legacy-adbd="'.$options['data_legacy_adbd'].'"';
    }
    if ($options['data_legacy_krux'] != null || $options['data_legacy_krux'] != "") {
        $krux = 'data-legacy-krux="'.$options['data_legacy_krux'].'"';
    }
    if ($options['data_analytics_id'] != null || $options['data_analytics_id'] != "") {
        $data_analytics_id = 'data-analytics-id="'. $options['data_analytics_id'].'"';
    }
    echo'
    <script src="//static.vocstatic.com/voonto2/latest/voonto.js" '.$cmp.'></script>
    <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" '.$data_analytics_id.' '.$adbd.' '.$krux.' '.$comscore.'></script>
    <script>TagManager.ready.then(function() { TagManager.set({ }).track(); });</script>
    ';
    $slug = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $variables = $tagging->serachPatron($_SERVER['REQUEST_URI']);
    $tagging->createTagmanager($variables, $slug);
}

add_shortcode('voonto_head', 'voonto_head_shortcode');


function voonto_body_shortcode($atts, $content = null)
{
    $options = get_option('settings_voonto');
    $repor_suit = $options['repor_suit'];
    if ($options['data_legacy_comscore'] != null || $options['data_legacy_comscore'] != "") {
        $comscore = 'data-legacy-comscore="'.$options['data_legacy_comscore'].'"';
    }
    $slug = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    echo'
    <noscript><img src="https://vocento.d3.sc.omtrdc.net/b/ss/'.$repor_suit.'/1/G.4--NS/123456789?pageName=&g='.$slug.'" width="1" height="1" border="0" /></noscript>
    <noscript><p><img src="//sb.scorecardresearch.com/p?c1=2&amp;c2='.$comscore.' height="1" width="1" alt="*" /></p></noscript>
    <script src="//static.vocstatic.com/dab/latest/strategy.js"></script>

    ';
}

add_shortcode('voonto_body', 'voonto_body_shortcode');

function voonto_footer_shortcode($atts, $content = null)
{
}
add_shortcode('voonto_footer', 'voonto_footer_shortcode');
