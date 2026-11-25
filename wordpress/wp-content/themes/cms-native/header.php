<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php
    //insertando canonical si está definido
    if ($_SERVER['HTTPS'] === 1 || $_SERVER['HTTPS'] === true) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    //obtenemos la URL del medio que ha la solicitud
    $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    $getValueMedio = 'http://' . $_SERVER['HTTP_HOST'];
    if (is_preview() && isset($_GET["medio"])) {
        $url_medio = $_GET["medio"];
    }
    $posInternalServer = strpos($url_medio, 'srv.vocento.in');
    if ($posInternalServer !== false) {
        $url_medio = "http://content-factory.elcorreo.com";
    } else {
        $url_medio = 'http://' . $_SERVER['HTTP_HOST'];
    }

    $canonicalURL = str_replace("http://", $protocol, get_post_meta(get_the_ID(), 'canonical-url', true));

    $pos = strpos($canonicalURL, 'contentfactory/post/eslaliga/');
    if ($pos === false) {
        if ($canonicalURL != "") {
            echo '<link rel="canonical" href="' . $canonicalURL . '" />';
        }
    } else {
        $canonicalURL = str_replace("contentfactory/post/", "", $canonicalURL);
        if ($canonicalURL != "") {
            echo '<link rel="canonical" href="' . $canonicalURL . '" />';
        }
    }


    $keywords = get_post_meta(get_the_ID(), 'keyword-url', true);
    if ($keywords != "") {
        echo '<meta name="keywords" content="' . $keywords . '"> ';
    }
    //unique id for version css
    $d = date("d");
    $m = date("m");
    $y = date("Y");
    $t = time();
    $dmt = $d . $m . $y . $t;
    if ($url_medio !== "http://content-factory.sevilla.abc.es") {
        $url_medio = str_replace("http://content-factory", "https://www", $url_medio);
    } else {
        $url_medio = str_replace("http://content-factory.", "https://", $url_medio);
    }
    // $url_medio = str_replace("http://content-factory", "https://www", $url_medio);
    ?>

    <meta property="og:url" content="<?php echo url_facebook(); ?>" />
    <meta name="twitter:url" content="<?php echo url_facebook(); ?>" />


    <?php
    $url_medio_backend = 'http://' . $_SERVER['HTTP_HOST'];
    $publisher = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'publisher');
    $pagesidfb = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'pages_id_fb');
    $appidfb = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'app_id_fb');
    ?>

    <meta property="article:publisher" content="<?php echo $publisher; ?>" />
    <meta property="fb:pages" content="<?php echo $pagesidfb; ?>" />
    <meta property="fb:app_id" content="<?php echo $appidfb; ?>" />


    <meta http-equiv="X-UA-Compatible" content="IE=100" />
    <title><?php the_title() ?></title>
    <!-- <script src="<?php echo $url_medio; ?>/js/jquery-2.1.3.min.js"></script> -->
    <script src="<?php echo get_template_directory_uri() ?>/js/jquery-2.1.3.min.js"></script>
    <?php wp_head() ?>


    <link href="<?php echo site_url(); ?>/wp-content/uploads/css/style.css?<?php echo $dmt; ?>" rel="stylesheet"
        media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/animate.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/gridcontentfactory.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/tooltipster.min.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/font.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/slide.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <link href="<?php echo $url_medio . get_template_directory_uri(); ?>/css/vocentomediaplayer.css?<?php echo $dmt; ?>"
        rel="stylesheet" media="screen">
    <script src="<?php echo $url_medio . get_template_directory_uri(); ?>/js/addons_js/vocentomediaplayer.js"></script>

    <?php

    $re = '/(.*?)\./m';
    preg_match_all($re, $url_medio_backend, $matches, PREG_SET_ORDER, 0);

    $args = array(
        'post_type' => 'cabecera',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'Medios',
                'terms' => array($matches[1][1]),
                'field' => 'slug',
            )
        ),
        'posts_per_page' => -1
    );
    $postscabecera = get_posts($args);

    $url_favicon = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'url_favicon');


    if (!empty($postscabecera)) {
        foreach ($postscabecera as $post) {
            $csscabecera = get_field('codigo_css', $post->ID);
            $csscabecera = str_replace('<p>', '', $csscabecera);
            $csscabecera = str_replace('</p>', '', $csscabecera);
            $csscabecera = str_replace('</br>', '', $csscabecera);
            $csscabecera = str_replace('<br />', '', $csscabecera);
            $csscabecera = str_replace('«', '"', $csscabecera);
            $csscabecera = str_replace('»', '"', $csscabecera);
            echo '<style>' . $csscabecera . '</style>';
            $javascriptcabecera = get_field('java_script_cabecera', $post->ID);
            $javascriptcabecera = str_replace('<p>', '', $javascriptcabecera);
            $javascriptcabecera = str_replace('</p>', '', $javascriptcabecera);
            $javascriptcabecera = str_replace('</br>', '', $javascriptcabecera);
            $javascriptcabecera = str_replace('<br />', '', $javascriptcabecera);
            $javascriptcabecera = str_replace('«', '"', $javascriptcabecera);
            $javascriptcabecera = str_replace('»', '"', $javascriptcabecera);
            echo '<script>' . $javascriptcabecera . '</script>';
        }
    }

    ?>

    <link rel="icon" type="image/png" href="<?php echo $url_favicon; ?>/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?php echo $url_favicon; ?>/favicon.svg" />
    <link rel="shortcut icon" href="<?php echo $url_favicon; ?>/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $url_favicon; ?>/apple-touch-icon.png" />
    <link rel="manifest" href="<?php echo $url_favicon; ?>/site.webmanifest" />

    <?php
    //insertando modulo emergente en header
    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace("http://content-factory.", "", $actual_link);

    $moduloEmergente = pinta_modulo_emergente();
    $hostRequest = strpos($actual_link, 'content-factory');


    if ($hostRequest === false) {
        if (!checkdomain(get_the_ID(), $url_medio) && !is_404() && !is_preview()) {
            header("HTTP/1.1 404 Not Found");
            header("Location: /404");
        }
    }

    // else {
    //     if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
    //         $url_medio = 'http://pre-contentfactory.elcorreo.com';
    //     } else {
    //         $url_medio = 'http://content-factory.elcorreo.com';
    //     }
    // }

    // if (!checkdomain(get_the_ID(), $url_medio) && !is_404() && !is_preview()) {
    //     header("HTTP/1.1 404 Not Found");
    //     header("Location: /404");
    // }


    $cssmedio = valueByUrlMedio(get_the_ID(), $getValueMedio, 'css');

    // $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $res = valueByUrlMedio(get_the_ID(), $url_medio, 'url_medio');

    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        $mrf_canonical = str_replace('http://content-factory.', 'https://www.', $res);
    } else {
        $mrf_canonical = str_replace('http://content-factory.', 'https://www.', $res);
    }

    if (($_ENV["ENVIRON"] == "DES") || ($_ENV["ENVIRON"] == "PRE")) {
        $res = str_replace('http', 'https', $res);
        $mrf_canonical_content = str_replace($res, $mrf_canonical . '/contentfactory/post/', $actual_link);
    } else {
        $res = str_replace('http', 'https', $res);
        $mrf_canonical_content = str_replace($res, $mrf_canonical . '/contentfactory/post/', $actual_link);
    }

    echo '<meta property="mrf:canonical" content="' . $mrf_canonical_content . '" />';

    //comporbamos si existe css para el medio
    if ($cssmedio) {
        ?>
        <link href="<?php echo $cssmedio['url']; ?><?php echo "?" . $dmt; ?>" rel="stylesheet" media="screen">
        <script>
            (function IIFE() {
                window.vocento = {
                    config: {
                        multimedia: {
                            videoplayer: {
                                serviceUrl: '//api-video-player.vocento.com'
                            }
                        }
                    }
                };
            }());
        </script>
        <?php
    } else {
        //pintamos css general
        $mods = get_theme_mods();
        if ($mods["file-upload"] != null) {
            $urlCSS2 = limpiaURL($mods["file-upload"]); ?>
            <link href="<?php echo $urlCSS2 ?>" rel="stylesheet" media="screen">

            <?php
        }
    }

    ?>

    <?php

    $nameofblog = get_bloginfo('name');
    $nameofblog = str_replace(" ", "-", iconv('UTF-8', 'ASCII//TRANSLIT', $nameofblog));

    $author_id = $post->post_author;
    $value_voc_au = get_the_author_meta('user_nicename', $author_id);

    $value_voc_s1 = $nameofblog;
    $value_voc_s2 = "";
    $value_voc_s3 = "";
    $value_voc_s4 = "";
    $value_voc_tn = "portadilla";
    $value_voc_tc = "portadilla";
    $value_voc_tcnew = "portadilla";
    $value_voc_fep = "";
    $value_voc_fem = "";
    $value_voc_top = "";


    if (is_front_page()) {
        $value_voc_s1 = $nameofblog;
    }

    if (is_category()) {
        $value_voc_s2 = "categorias";
        $category = get_the_category();
        $value_voc_s3 = $category[0]->cat_name;
    }

    if (is_author()) {
        $value_voc_s2 = "autor";
        $value_voc_s3 = get_the_author_meta('user_nicename');
    }

    if (is_date()) {
        $value_voc_s2 = "archivo";
        $value_voc_s3 = get_the_time('Y') . '/' . get_the_time('m');
    }

    if (is_singular('post')) {
        $value_voc_tn = $post->post_name;
        $value_voc_tc = "noticia";
        $value_voc_tcnew = "noticia.post";
        $value_voc_fep = gmdate("Ymdshm", get_post_time());
        $value_voc_fem = gmdate("Ymdshm", get_post_time());

        global $post;
        $tags = wp_get_post_tags($post->ID);
        $tagCount = 0;
        $max = count($tags);
        foreach ($tags as $tag) {
            $value_voc_top .= $tag->slug;
            if ($tagCount >= 0 && $tagCount < ($max - 1)) {
                $value_voc_top .= ",";
            }
            $tagCount++;
        }
    }

    $valueUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (strpos($valueUrl, 'temas') !== false) {
        $value_voc_s1 = "temas";
        $value_voc_s2 = cets_get_topic_name($topicid);
    }

    if (is_tag()) {
        $value_voc_s2 = "etiquetas";
        $value_voc_s3 = single_tag_title();
    }

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $voc_pl = 'contenido-web';
    if (preg_match("/mobile/i", $useragent) || preg_match("/tablet/i", $useragent)) {
        $voc_pl = 'm-responsive';
    }

    ?>

    <?php

    global $post;
    $checkSlug = valueByUrlMedio(get_the_ID(), $url_medio, 'slug');
    $checkUrlMedio = valueSlugByUrlMedio(get_the_ID(), $url_medio);
    $canonicalURL2 = str_replace("http://", $protocol, get_post_meta(get_the_ID(), 'canonical-url', true));

    $pos = strpos($canonicalURL2, 'contentfactory/post/eslaliga/');
    if ($pos === false) {
        ?>
        <script>
            var voc_s1 = '';
            var voc_s2 = '';
            var voc_s3 = '';
            var voc_s4 = '';

            var cov_ed = 'nacional';
            var voc_or = 'RL';
            var voc_pu = 'wordpress';
            var voc_adp = '';
            var voc_adb = '';
            var voc_idi = '';

            var ns_site = '<?php echo $checkUrlMedio; ?>';
            var name = '<?php echo "content-factory" . $post->post_name; ?>';
            var voc_site = '<?php echo $checkUrlMedio; ?>';
            var voc_se = 'content-factory';
            var voc_tn = '<?php echo $post->post_name; ?>';
            var voc_ho = '1';
            var voc_tc = 'noticia';
            var voc_tcnew = 'noticia-post';
            var voc_ed = 'nacional';
            var voc_au = 'content-factory';
            var voc_fep = '<?php echo get_the_date("Ymdhms") ?>';
            var voc_fem = '<?php echo get_the_date("Ymdhms") ?>';
            var voc_pr = '1';
            var voc_pl = '';
            var voc_ate = 'no';
            var voc_top = '<?php echo $value_voc_top; ?>';
        </script>
        <?php
    }


    ?>
    <link href="<?php echo get_template_directory_uri() ?>/css/addons.css" rel="stylesheet" media="screen">

    <?php

    $tags = wp_get_post_tags($post->ID);
    $validate = 0;
    foreach ($tags as $tag) {
        if ($tag->name == 'eslaliga') {
            $validate = 1;
        }
    }

    if (function_exists('generate_header_tagmanager')) {
        if ($validate == 1) {
            generate_header_tagmanager(false);
        } else {
            generate_header_tagmanager(true);
        }
    } ?>

    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>



    <script>

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
            if (consent) {
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({ event: 'consentGranted' });
            }
        });

    </script>

</head>

<?php
$url_medio_backend = 'http://' . $_SERVER['HTTP_HOST'];
$clasebody = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'clase_body');
//URL REQUEST
//extreamos la url del contenido solicitado para el campo requerido de la categoria
$cabecera = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'url_cabecera');
$minicabecera = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'url_cabecera_mini');
$img_logo_mobile = valueByUrlMedio(get_the_ID(), $url_medio_backend, "logo_del_medio_mobile");
$img_logo_mobile['url'] = limpiaURL($img_logo_mobile['url']);
$img_logo_desktop = valueByUrlMedio(get_the_ID(), $url_medio_backend, "logo_del_medio");
$img_logo_desktop['url'] = limpiaURL($img_logo_desktop['url']);
do_action('vocento_analytics_script');
?>

<body class="<?php if ($clasebody) {
    echo $clasebody;
} ?>">
    <?php do_action('vocento_analytics_after_body_start'); ?>
    <?php if (function_exists('generate_body_tagmanager')) {
        generate_body_tagmanager($canonicalURL);
    } ?>
    <?php
    /**
     * Comentamos la llamada para incluir el hook que cargaba script en la cabecera
     *
     * do_action('js_after_body');
     */
    ?>
    <div class="scrollbar">
        <div class="bar"></div>
    </div>

    <?php echo $moduloEmergente; ?>

    <?php
    if (empty($postscabecera)) {
        ?>
        <nav id="main_navigation">
            <?php
            // echo getContentNavMobile($minicabecera);

            $htmlContentNavMobile = getContentNavMobile($minicabecera);
            $subst = '';
            $re = '/<script(.*)voonto.js(.*)<\/script>/m';
            $result = preg_replace($re, $subst, $htmlContentNavMobile);
            echo $result;
            ?>
        </nav>
    <?php } ?>


    <div class="content">
        <?php
        if (empty($postscabecera)) {
            ?>
            <header id="modulo_cabecera">
                <a class="hamburger" title="Despliega el menú" id="navigation_trigger">?</a>
                <a class="home_link" title="Enlace a la página principal" href="/">
                    <span class="logo_holder">
                        <img src="<?php echo get_site_url() . $img_logo_mobile['url']; ?>"></span>
                </a>
            </header>
        <?php } ?>
        <header class="cabecera">

            <?php
            // echo getContentH($cabecera);
            if (!empty($postscabecera)) {
                foreach ($postscabecera as $post) {
                    $htmlcabecera = get_field('html_cabecera', $post->ID);
                    echo $htmlcabecera;
                }
            } else {
                $htmlContentH = "";
                $newperformance = $_GET['newperformance'];
                if ($newperformance == 'validatenewperformance') {
                    $checkSlug = valueByUrlMedio(get_the_ID(), $url_medio_backend, 'slug');
                    $re = '/\.(.*?)\./m';
                    preg_match_all($re, $cabecera, $matches, PREG_SET_ORDER, 0);
                    $frontalClient = new CacheExternalFile(1500);
                    $url = $cabecera;
                    $fileName = 'cabecera_' . $matches[0][1] . '.html';
                    $htmlContentH = $frontalClient::getHtmlLayoutURL($url, $fileName);
                } else {
                    $htmlContentH = getContentH($cabecera);
                }
                $subst = '';
                $re = '/<script(.*)voonto.js(.*)<\/script>/m';
                $result = preg_replace($re, $subst, $htmlContentH);
                echo $result;
            }


            ?>
        </header>

        <script>
            jQuery(document).ready(function () {
                jQuery(".logo > img").attr("src", "<?php echo get_site_url() . $img_logo_desktop['url']; ?>");
            });
        </script>
        <?php
        /*$res = valueTermIdByUrlMedio(get_the_ID(),$url_medio);
    echo $res;*/
        ?>
