

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="google-site-verification" content="Tt5UUU9Ka-B0D2Khk_dcY8qxlrY1r5OmvB5U3WEEhoI" />
        
        <link rel="profile" href="https://gmpg.org/xfn/11">

        <?php wp_head(); ?>

        <link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo get_template_directory_uri();?>/images/favicon.ico" type="image/x-icon">

        <!--CSS-->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/estilos.css?r=<?php echo rand(0,100000); ?>"/>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/mediaqueries.css?r=<?php echo rand(0,100000); ?>"/>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/swiper.min.css"/>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/hamburgers.min.css"/>

        <!--JS-->
        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
        <script src="<?php echo get_template_directory_uri(); ?>/js/swiper.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.transit.js"></script>

        <script type="text/javascript"> var getLangCode = '<?php echo ICL_LANGUAGE_CODE; ?>';</script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/js.cookie.js"></script>
        
        <!-- <script> document.domain = 'www.womennow.es'; </script> -->

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PSXK7CQ');</script>
        <!-- End Google Tag Manager -->

        <!-- ADOBE DTM -->
        <script src="//static.vocstatic.com/voonto2/latest/voonto.js"></script>
        <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="6f5e794197143d0967d6b4da6d90751edbf93ed6"
        data-legacy-adbd="true" data-legacy-krux="ttistq20z" data-analytics-id="ppvv_pro" data-legacy-comscore="7715761"></script>
		<?php 
            // seccion
            $parent = get_post( $post->post_parent ); 
            $seccion=$parent->post_name;
            if($seccion=='home')$seccion='portada';
            if(is_single())$seccion='noticias';
            // titular
            $titular=$post->post_name;
            if($titular=='home')$titular='portada';
            //tipo contenido
            if(is_front_page()):$tipo_contenido='portada';
            elseif(is_single()):$tipo_contenido='detalle';
            else:$tipo_contenido='portadilla';
            endif;
        ?>
        <script>
            var media='desktop'; if(isMobile){media='mobile'}
            TagManager.ready.then(function() {
                TagManager.set({
                seccion:<?php echo '"'.$seccion.'"'; ?>,
                titular:<?php echo '"'.$titular.'"'; ?>,
                tipoContenido:<?php echo '"'.$tipo_contenido.'"'; ?>,
                autotracking:"false",
                sistemaPublicacion:"wordpress",
                idioma:getLangCode,
                plataformaContenido: media,
                origenPortada:"0",
                tipoDominio:"1",
            }).track(); });
         </script>


    </head>

    <body <?php body_class(); ?>>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PSXK7CQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <!-- ADOBE DTM (noscript) -->
        <noscript><p><img src="//sb.scorecardresearch.com/p?c1=2&amp;c2=7715761" height="1" width="1" alt="*"></p></noscript>
        <noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocwomennow,voces/1/G.4--S/1212121?pageName=&g=<?php echo home_url( add_query_arg(array())); ?>" width="1" height="1" border="0" /></noscript>
        <script src="//static.vocstatic.com/dab/latest/strategy.js"></script>
        <!-- ADOBE DTM (noscript) -->
		
       

    	<header class="container">

            <a class="cont-logo" href="<?php echo icl_get_home_url()  ?>">
                <img class="logo" src="<?php echo get_template_directory_uri();?>/images/logo.png"> 
                <img class="logo w" src="<?php echo get_template_directory_uri();?>/images/logo_b.png"> 
            </a>

            <nav class="menu">
                <?php wp_nav_menu(array( 'theme_location' => 'menu-principal','container' => 'ul')); ?>
                <nav class="rrss">
                    <a href="https://www.instagram.com/womennowsp/?hl=es"><i class="icon-inst"></i></a>
                    <a href="https://twitter.com/WomenNOWSP?lang=es"><i class="icon-twt"></i></a>
                     <a href="https://www.linkedin.com/company/womennow-summit/"><i class="icon-link"></i></a>
                </nav>
                <?php do_action('wpml_add_language_selector'); ?>
            </nav>

        </header>

        <?php $class=($post->post_name=='aviso-legal'||$post->post_name=='politica-de-privacidad'||$post->post_name=='politica-de-cookies'||$post->post_name=='privacy-policy'||$post->post_name=='legal-notice'||$post->post_name=='cookies-policy')?'negro':'';  ?>
        <div class="hamburger hamburger--squeeze <?php echo $class; ?>">
          <div class="hamburger-box">
            <div class="hamburger-inner"></div>
          </div>
        </div> 

        <nav class="menu">
            <?php wp_nav_menu(array( 'theme_location' => 'menu-principal','container' => 'ul')); ?>
            <nav class="rrss">
                <a href="https://www.instagram.com/womennowsp/?hl=es"><i class="icon-inst"></i></a>
                <a href="https://twitter.com/WomenNOWSP?lang=es"><i class="icon-twt"></i></a>
                <a href="https://www.linkedin.com/company/womennow-summit/"><i class="icon-link"></i></a>
            </nav>
             <?php do_action('wpml_add_language_selector'); ?>
        </nav>


