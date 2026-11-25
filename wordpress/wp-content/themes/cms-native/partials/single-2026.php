<?php
global $post;
global $wp_query;
$host = $_SERVER['HTTP_HOST'];

// $html = frontal_client_filter_content_2026("https://www.abc.es/api/v4/headers/skeleton.html?section=home&type=responsive26&device=desktop");

$html = frontal_client_filter_content_test();


if ($html == false) {
    header("HTTP/1.1 500 Internal Server Error");
}

$re = '/<!-- block prefooter -->(.*)<!-- endblock prefooter -->/ms';
preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);
$html = str_replace($matches[0][0], '', $html);


$re = '/(.*?)\.(.*?)\./m';
preg_match_all($re, $host, $matches, PREG_SET_ORDER, 0);

$re = '/<link rel="dns-prefetch"(.*)\/>/m';
$subst = "";
$html = preg_replace($re, $subst, $html);

$html = str_replace('<!-- block css -->', load_wp_head(), $html);
$html = str_replace('<!-- endblock content -->', load_single_content($post->ID), $html);
$html = str_replace('<!-- endblock tracking -->', generate_header_tagmanager_2025(true), $html);

// $html = str_replace('<!-- block noscripttracking -->', '<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-P79V5H5" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>', $html);

$url_medio = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
if ($url_medio !== "http://content-factory.sevilla.abc.es") {
    $url_medio = str_replace("http://content-factory", "https://www", $url_medio);
} else {
    $url_medio = str_replace("http://content-factory.", "https://", $url_medio);
}

if (($_ENV["ENVIRON"] == "PRO")) {
    if (!checkdomain($post->ID, $url_medio) && !is_404() && !is_preview()) {
        // header("HTTP/1.1 404 Not Found");
        // header("Location: /404");
        wp_redirect(home_url('/404'));
    }
}


$path = "/contentfactory/post/wp-content/themes/cms-native";

echo '<!-- ___________2026_____________ -->';

$scripts = get_field('url_scripts');
if($scripts != "" || $scripts != null){
  $urlScripts = explode( ',', $scripts );
  foreach($urlScripts as $script){
    $scripts = '<script src="'.$script.'?'.uniqid().'"></script>'."\n";
  }
}else{
  echo '<!-- ____________--_____________ -->';
}

$scripts = '
<script>TagManager.ready.then(function() {TagManager.set({ tipologiaLogin: Voonto.getInstance().getUserType(), sitioRegistro: "' . $matches[0][2] . '" }).track();});</script>
<script src="' . $path . '/js/waypoints.min.js?' . rand(0, 1000) . '"></script> <!-- Waypoints -->
<script src="' . $path . '/js/contentshare.js?' . rand(0, 1000) . '"></script> <!-- Contentshare -->
<script src="' . $path . '/js/funzioni.js?' . rand(0, 1000) . '"></script> <!-- FUNZIONI Js file -->
<script src="' . $path . '/js/animacion-interna.js?' . rand(0, 1000) . '"></script> <!-- animacion Js file -->
<script src="' . $path . '/js/evenZoom.js?' . rand(0, 1000) . '"></script><!-- animacion Js file -->
<script src="' . $path . '/js/swiper.js?' . rand(0, 1000) . '"></script> <!-- Swiper JS -->
<script src="' . $path . '/js/swiper-set.js?' . rand(0, 1000) . '"></script> <!-- Initialize Swiper -->
<script src="' . $path . '/js/jquery.appear.js?' . date("Y-m-d-h-m-s", time()) . '"></script> <!-- Autoplay -->
<script src="' . $path . '/js/baguetteBox.js?' . rand(0, 1000) . '"></script>
<script src="' . $path . '/js/highlight.min.js?' . rand(0, 1000) . '"></script>
<script src="' . $path . '/js/gallerymodal.js?' . rand(0, 1000) . '"></script>
<script src="' . $path . '/js/customninjaform.js?v2.0.9"></script>
<script src="' . $path . '/js/jquery.tooltipster.min.js?' . rand(0, 1000) . '"></script> <!-- Tool tip -->
<script> Voonto.ready.then(function(app) {
    app.load("https://static.vocstatic.com/video-player/1.3.3/video-player.js");
    }); </script>';

$scripts2 = "<script>

    var dataLayer = [{
        'Site': 'contentfactory',
        'Seccion': 'contentfactory',
        'Subseccion': '',
        'Canal': '',
        'Blog': '',
        'Servicio': '',
        'Sub-Servicio': '',
        'Tipo': 'Portada'
    }];

    (
        function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true; j.src = '//www.googletagmanager.com/gtm.js?id=' + i + dl;
            j.type = 'cookiewall/script';
            f.parentNode.insertBefore(j, f);
        }
    )(window, document, 'script', 'dataLayer', 'GTM-P79V5H5');

    Voonto.getInstance().when('consent:cookies').then((consent) => {
        if(consent) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({event: 'consentGranted'});
        }
    });

</script>";

$script3 = "<script>vocento.__PROVIDER__ = {};</script>";

$html = str_replace('<!-- block trackingFooter -->', $scripts . $scripts2, $html);

// $html = str_replace('<!-- block trackingFooter -->', $scripts, $html);
$html = str_replace('<!-- block tracking -->', $script3, $html);




echo $html;
