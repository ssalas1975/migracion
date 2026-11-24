<?php
if($_GET['search']){
    header('Location:'.get_site_url().'/search/'.$_GET['search']);
}
$post = get_post( $post->ID ); $GLOBALS['current_id']=$post->ID; $GLOBALS['current_type']=$post->post_type;
if($post->post_type=='noticia'){
    $description = replace_invalid_charts(get_field('resumen'),true);
}else{
    $description = replace_invalid_charts($post->post_content);
}
?>
<head>

    <title><?php echo replace_invalid_charts(get_the_title()).' - Women Now' ?></title>
    
    <!-- metas -->
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="amp-google-client-id-api" content="googleanalytics">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="canonical" href="<?php echo get_permalink();?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <meta name="DC.date.issued" content="<?php echo get_post_modified_time('Y-m-d\\TH:i:s')?>Z" />
    <meta name="date" content="<?php echo get_the_date('Y-m-d\\TH:i:s')?>Z" />
    <meta name="robots" content="max-image-preview:large">
    <meta property="article:modified_time" content="<?php echo get_post_modified_time('Y-m-d\\TH:i:s')?>Z" />
    	<!-- meta OG -->
	<meta property="og:site_name" content="Women Now" />
	<meta property="og:url" content="<?php echo get_permalink( ); ?>" />
    <meta property="og:description" content="<?php echo $description; ?>" />
	<meta property="og:image" content="<?php echo get_site_url().wp_get_attachment_url( get_post_thumbnail_id( ) ); ?>" />
    <meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="<?php echo replace_invalid_charts(get_the_title()) ?> "/>
    <!-- meta twcards -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="<?php echo $description; ?>" />
    <meta name="twitter:title" content="<?php echo replace_invalid_charts(get_the_title()); ?>" />
    <meta name="twitter:site" content="@WomenNOWSP" />
    <meta name="twitter:image" content="<?php echo get_site_url().wp_get_attachment_url( get_post_thumbnail_id( ) ); ?>" />
    <meta name="twitter:creator" content="@WomenNOWSP" />
    <!--carga de fuentes-->
    <link href="https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPHjc5a7duw.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPErd5a7duw.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/catamaran/v8/o-0bIpQoyXQa2RxT7-5B6Ryxs2E_6n1iPJ_a5a7duw.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0Bvr8vbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvtssbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvoMtbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0Bvq8qbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://fonts.gstatic.com/s/notoseriftc/v17/XLY9IZb5bJNDGYxLBibeHZ0BvvMpbXwuVcgUbQ3zF6M3eg1qzxeg6QwzxcSPzLZfXbm0Gs_7uos.119.woff2" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://cabeceras.vocento.com/comun/vocuser/v3/resources/fonts/nova/ProximaNova-Light.woff" rel='preload' as='font' type='font/woff2' crossorigin/>
    <link href="https://cabeceras.vocento.com/comun/vocuser/v3/resources/fonts/nova/nova_bold-webfont.woff" rel='preload' as='font' type='font/woff2' crossorigin/>
    <!--carga de fuentes-->

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <meta name="amp-consent-blocking" content="" />
    <style amp-custom>
	<?php include 'styles.php'; ?>
	</style>
    <style amp-boilerplate>
        body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
    </style>
    <noscript>
        <style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style>
    </noscript>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-consent" src="https://cdn.ampproject.org/v0/amp-consent-0.1.js"></script>
    <script async custom-element="amp-geo" src="https://cdn.ampproject.org/v0/amp-geo-0.1.js"></script>    <script async custom-element="amp-font" src="https://cdn.ampproject.org/v0/amp-font-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script async custom-element="amp-script" src="https://cdn.ampproject.org/v0/amp-script-0.1.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <!--<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>-->
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
    <script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>
    <!-- embeds -->
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>
    <script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
    <!-- video -->
    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <!-- brightcove-->
    <script async custom-element="amp-brightcove" src="https://cdn.ampproject.org/v0/amp-brightcove-0.1.js"></script>
    <!-- audio -->
    <script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>
    <!-- form/search -->
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <!-- sticky ad -->
    <script async custom-element="amp-sticky-ad" src="https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js"></script>

    <?php if ($post->post_type == 'noticia') {?>
        <!-- meta newsarticle -->
            <?php echo schemas_womennow($post); ?>
        <!-- meta newsarticle-->
    <?php } ?>

    <?php if ($post->post_type == 'videocpt') {?>
        <!-- meta videoobject -->
        <?php echo videoobject_schema($post); ?>
        <!-- meta videoobject-->
    <?php } ?>

    <?php if ($post->post_type == 'audio') {?>
        <!-- meta audioobject -->
        <?php echo audioobject_schema($post); ?>
        <!-- meta audioobject-->
    <?php } ?>

    <script async src="https://cdn.ampproject.org/v0.js"></script>
    

</head>
<body>
    <header class="c-header u-sticky">
        <div class="o-container">
            <a class="c-header__brand" href="<?php echo home_url();?>" title="">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 163.25 66.68">
                    <g fill="currentColor">
                        <path d="M27.73,21.84,31.59.51h7.25l-7,30.74H24.08L19.42,9.58,14.8,31.25H7L0,.51H7.34l4.19,21.24L15.69.51h7.5Z"/>
                        <path d="M53.26,31.8c-9.46,0-15.95-6.32-15.95-15.9S43.8,0,53.26,0,69.2,6.32,69.2,15.9,62.67,31.8,53.26,31.8Zm0-6c4.53,0,8.26-3.39,8.26-9.92S57.79,6,53.26,6,45,9.37,45,15.9,48.72,25.82,53.26,25.82Z"/>
                        <path d="M84.76,31.25,77.85,6.66V31.25h-7V.51H82.26l5.89,22,6-22h11.32V31.25h-7V6.61L91.59,31.25Z"/>
                        <path d="M108.93.51H132.5V6.87H116.18v5.38h15.43v6.41H116.18v6.19H132.5v6.4H108.93Z"/>
                        <path d="M154.25,31.25l-12.34-21.2v21.2h-7V.51h8.82l12.34,21.2V.51h7V31.25Z"/>
                        <path d="M83.66,66.12,71.33,44.92v21.2h-7V35.38h8.82l12.34,21.2V35.38h7V66.12Z"/>
                        <path d="M110,66.68c-9.45,0-15.94-6.32-15.94-15.9S100.54,34.87,110,34.87s16,6.32,16,15.91S119.41,66.68,110,66.68Zm0-6c4.54,0,8.27-3.4,8.27-9.92s-3.73-9.93-8.27-9.93-8.26,3.4-8.26,9.93S105.46,60.7,110,60.7Z"/>
                        <path d="M152.14,56.71,156,35.38h7.25L156.3,66.12h-7.81l-4.66-21.66-4.62,21.66h-7.76l-7-30.74h7.34l4.19,21.25,4.16-21.25h7.5Z"/>
                    </g>
                </svg>
            </a>

            <div class="c-header__burger">
                <div class="c-header__burger__btn" id="hamburger" tabindex="1" role="button" on="tap:hamburger.toggleClass(class='close'),menu.toggleClass(class='open')">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="c-header__sidebar" id="menu">
                <ul class="c-header__sidebar__nav">
                    <?php
                        $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-principal'] );
                        $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
                        foreach( $menuitems as $item ): ?>
                            <li class="c-header__sidebar__nav__item">
                                <a href="<?php echo $item->url ?>" title="Ir a <?php echo stripQuotes($item->title);?>" aria-label="Ir a <?php echo stripQuotes($item->title);?>"><?php echo $item->title;?></a>
                            </li>
                    <?php endforeach; ?>
                </ul>
                <nav class="c-header__options">
                    <a class="btn btn-icon btn-facebook" target="_blank" rel="nofollow noopener" href="http://www.facebook.com/WomenNOWSP" title="Ir a Facebook de WomenNow" aria-label="Ir a Facebook de WomenNow" >
                        <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                        </svg>
                        <span>Facebook</span>
                    </a>
                    <a class="btn btn-icon btn-instagram" target="_blank" rel="nofollow noopener" href="https://www.instagram.com/womennowsp/?hl=es" title="Ir a Instagram de WomenNow" aria-label="Ir a Instagram de WomenNow" >
                        <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                        </svg>
                        <span>Instagram</span>
                    </a>
                    <a class="btn btn-icon btn-twitter" target="_blank" rel="nofollow noopener" href="http://www.twitter.com/WomenNOWSP" title="Ir a Twitter de WomenNOW" aria-label="Ir a Twitter de WomenNOW">
                        <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                        </svg>
                        <span>Twitter</span>
                    </a>
                    <a class="btn btn-icon btn-lkd" target="_blank" rel="nofollow noopener" href="https://www.linkedin.com/company/womennow-summit/" title="Ir a Linkedin de WomenNow" aria-label="Ir a Linkedin de WomenNow">
                        <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                        </svg>
                        <span>LinkedIn</span>
                    </a>
                    <a class="btn-primary btn-news" rel="nofollow" href="<?php echo home_url();?>/newsletter/" title="Ir a Prospect de Newsletter" aria-label="Ir a Prospect de Newsletter">Newsletter</a>
                </nav>
                <form class="c-header__sidebar__search" method="GET" action="#" target="_top">
                    <input class="c-header__sidebar__search__txt" type="search" placeholder="Buscar..." name="search">
                    <button class="c-header__sidebar__search__btn" type="submit"><span>Buscar</span></button>
                </form>
            </div>
        </div>
    </header>

