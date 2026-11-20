
<?php

if($post->post_name=='equipo-trabajo') header( 'Location:'.home_url('/quienes-somos/equipo-trabajo/permanente/') );

$post = get_post( $post->ID ); $GLOBALS['current_id']=$post->ID; $GLOBALS['current_type']=$post->post_type;
if($post->post_type=='noticia'){
    $description = replace_invalid_charts(get_field('resumen'),true);
}else{
    $description = replace_invalid_charts($post->post_content);
}

if ($post->post_type == 'videocpt') {
    $seccion = 'videos';
    $subSeccion = 'video';
    $contenido=strip_tags(get_the_content());}

if ($post->post_type == 'audio') {
    $seccion = 'podcast';
    $subSeccion = 'podcast';
    $contenido=strip_tags(get_the_content());
}

if ($post->post_type == 'noticia') {
    $subSeccion = 'noticia';
    $seccion = 'noticias';
    $contenido=  str_replace('"', '\'', strip_tags(get_field('contenido')));
}

    $seccion=(is_single())?$post->post_type:$post->post_name;

?>
<!-- womennow date: 2019-10-22 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    
    <?php wp_head(); ?>

    <?php
        echo schemas_womennow($post);
    ?>

    <link rel="canonical" href="<?php echo get_permalink();?>">
    <meta property="og:url" content="<?php echo get_permalink();?>" />
    
    <?php if(is_single()){ ?>
        <link rel="amphtml" href="<?php echo get_permalink();?>amp/">
    <?php } ?>


    <link rel="icon" href="<?php echo get_template_directory_uri();?>/assets/img/favicon.ico" type="image/x-icon">

    <!-- metas -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title> <?php wp_title(); ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(is_single() && $post) {?>
        <meta name="DC.date.issued" content="<?php echo get_post_modified_time('Y-m-d\\TH:i:s')?>Z" />
        <meta name="date" content="<?php echo get_the_date('Y-m-d\\TH:i:s')?>Z" />
        <meta property="article:modified_time" content="<?php echo get_post_modified_time('Y-m-d\\TH:i:s')?>Z" />
        <meta name="robots" content="max-image-preview:large">
    <?php } ?>
    <?php
    if (is_archive() || is_page()) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($paged >= 3):
			echo '<meta name="robots" content="noindex, follow">';
		endif;
    }
    if(is_archive() && $paged==1) {
        $pagedPrev=$paged==0 || $paged==0?'':$paged-1;
        $pagedNext=$paged+1;
        if ($paged>1):
            $urlBase = explode('page/', $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); 
            $prevUrl = 'https://'.$urlBase[0].'page/'.$pagedPrev.'/'; 
            echo '<link rel="prev" href="'.$prevUrl.'" />';
        endif;
        $qobj = get_queried_object();
            query_posts(array('post_type' => array('noticia', 'videocpt', 'audio'),
                'tax_query' => array(
                    array(
                        'taxonomy' => $qobj->taxonomy,
                        'field' => 'slug',
                        'terms' => $qobj->slug
                    )
                ),
            'posts_per_page' => 5,'paged' => get_query_var('paged'))); 
        if ($paged < (($qobj->count)/5)):
            $urlBase = explode('page/', $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); 
            $nextUrl = 'https://'.$urlBase[0].'page/'.$pagedNext.'/';
            echo '<link rel="next" href="'.$nextUrl.'" />';
        endif;
    }
    ?>

    <?php 
    //if(is_page() && ($post->post_name=='te-interesa' || $post->post_name=='inversiones-finanzas'  || sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='videos') ) {
    if(is_page()){ 
        $pagedPrev=$paged==0 || $paged==0?'':$paged-1;
        $pagedNext=$paged+1;
        if ($paged>1):
            if ($_SERVER["REQUEST_URI"]!='/te-interesa/' && $_SERVER["REQUEST_URI"]!='/inversiones-finanzas/'):
                $urlBase = explode('page/', $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); 
                $prevUrl = $paged==2?'https://'.$urlBase[0]:'https://'.$urlBase[0].'page/'.$pagedPrev.'/'; 
                echo '<link rel="prev" href="'.$prevUrl.'" />';
            endif;
        endif; 
        //if ($paged < (($qobj->count)/5)):
            $urlBase = explode('page/', $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); 
            $nextUrl = 'https://'.$urlBase[0].'page/'.$pagedNext.'/';
            echo '<link rel="next" href="'.$nextUrl.'" />';
        //endif;
    }
    ?>

    <!-- CSS -->
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

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css" media="screen">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/add.css" media="screen">

    <?php $post = get_post( $post->ID ); $GLOBALS['current_id']=$post->ID; $GLOBALS['current_type']=$post->post_type; ?>

    <link rel="preconnect" href="https://static.vocstatic.com" />
    <link rel="dns-prefetch" href="https://static.vocstatic.com" />
    <link rel="preload" as="script" href="https://securepubads.g.doubleclick.net/tag/js/gpt.js" />
    <link rel="preload" as="script" href="https://sb.scorecardresearch.com/cs/7715761/beacon.js" />

    <!-- JS -->
     <script type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/js.cookie.js"></script>
      <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PSXK7CQ');
    </script>

     <script type="vocento/script">(function IIFE() {  window.vocento = {config: {  multimedia: { videoplayer: {  serviceUrl: '//api-video-player.vocento.com' }  }  } }; }());</script>


	  <script type="vocento/script"  src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
     <script type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/custom.js"></script>


     <!-- Start vocento VTM H -->
     <script type="vocento/script" src="//static.vocstatic.com/tag-manager/latest/vtm.js"
    data-legacy-comscore="7715761"
    data-legacy-adbd="true"
    data-legacy-krux="ttistq20z"
    data-analytics-id="ppvv_pro"
    data-legacy-wemass="true"
    data-append="true"></script>

    <!-- script -->
    <?php set_script($post) ?>
    <!-- script -->

    <?php if($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES"){ ?>
         <script type="vocento/script">(function IIFE() {  window.vocento.config.vocuser={
          url: {
            voccoreServiceUrl: '//pre-voccore.womennow.es/',
            prospectServiceUrl: '//pre-newsletters.vocento.com/'
          },
          urls:{
              catalog: 'https://pre-catalogo-newsletters.vocento.com/',
              commento: 'https://pre-comments.vocento.com/',
              voccore : 'https://pre-voccore.',
              voccoreDomain : 'https://pre-voccore.womennow.es/',
              prospect : '//pre-newsletters.vocento.com/',
              prospectDomain : '//pre-newsletters.womennow.es/'
          }
        };
          window.vocento.site={env: 'pre'};
        }());
        </script>
         <script type="vocento/script" src="//pre-static.vocstatic.com/prospect/2.8.0/prospect.js"> </script>
    <?php } elseif($_ENV["ENVIRON"] == "PRO") { ?>
         <script type="vocento/script">(function IIFE() {  window.vocento.config.vocuser={
          url: {
            voccoreServiceUrl: '//voccore.womennow.es/',
            prospectServiceUrl: '//newsletters.vocento.com/'
          },
            urls:{
                catalog: 'https://catalogo-newsletters.vocento.com/',
                commento: 'https://comments.vocento.com/',
                voccore : 'https://voccore.',
                voccoreDomain : 'https://voccore.womennow.es/',
                prospect : '//newsletters.vocento.com/',
                prospectDomain : '//newsletters.womennow.es/'
            }
        };
          window.vocento.site={env: 'pro'};
        }());
        </script>
         <script type="vocento/script" src="//static.vocstatic.com/prospect/2.8.0/prospect.js"> </script>
    <?php } ?>





  <script type="vocento/script" src="<?php echo get_template_directory_uri(); ?>/assets/js/sdk.min.js" onload="indigitall.init({ appKey: '8a33dcb7-ad06-466a-9567-c0b827bc8fa6', workerPath: '<?php echo get_template_directory_uri(); ?>/assets/js/worker.min.js', requestLocation: true })" async></script>


    <!-- Start Google Publicidad -->

      <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
     <script>var googletag = googletag || {}; googletag.cmd = googletag.cmd || [];</script>

    <?php //print_r($post) ?>
    <?php
    $seccion=(is_single())?$post->post_type:$post->post_name;
    if(is_page()&&($seccion=='te-interesa'||$seccion=='podcasts'||$seccion=='videos')){ $seccion.="/portada"; };
    if($seccion=='home'){ $seccion="portada"; };
    if($seccion=='videocpt'){ $seccion="videos"; };
    if($seccion=='audio'){ $seccion="podcasts"; };
    if($seccion=='noticia'){ $seccion="te-interesa"; };
    ?>

    <script>
        var _megas_sz = window.innerWidth <= 728 ? [[320,100],[320,50]] : [[980,250],[980,90],[728,90]];
        var _adunitBase = window.innerWidth <= 728 ? "4900/webm.WOMENNOW/<?php echo $seccion ?>" : "4900/Vocento.Womennow/<?php echo $seccion ?>";
        var _adunit = _adunitBase;
        googletag.cmd.push(function() {
            googletag.defineSlot(_adunit, _megas_sz, "megabanner-id").addService(googletag.pubads()).setTargeting("pos", "megabanner");
            <?php if ($post->post_type!='noticia_test'):?>
            googletag.defineSlot(_adunit, [[300,600],[300,250]], "robapaginas-id").addService(googletag.pubads()).setTargeting("pos", "robapaginas");
            googletag.defineSlot(_adunit, [[300,600],[300,250]], "robapaginas_dos-id").addService(googletag.pubads()).setTargeting("pos", "robapaginas_dos");
            <?php else: ?>
            googletag.defineSlot(_adunit, [[300,600],[300,250]], "robapaginas-id").addService(googletag.pubads()).setTargeting("pos", "robapaginas");
            <?php endif; ?>

            googletag.defineSlot(_adunit, "fluid", "boton_native-id").addService(googletag.pubads()).setTargeting("pos", "boton_native");

            googletag.defineSlot(_adunit, _megas_sz, "megabanner_dos-id").addService(googletag.pubads()).setTargeting("pos", "megabanner_dos");
            googletag.pubads().enableSingleRequest();
            googletag.pubads().collapseEmptyDivs();
            googletag.enableServices();
        });
    </script>
    <!-- End Google Publicidad -->
    

</head>


<style> .print-no { display: none !important; } </style>

<body class="<?php if($post->post_name!='home')echo $bodyclass ?>">

    <!-- Start vocento dtm+gtm+comscore -->
    <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PSXK7CQ" title="Google TagManager" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <noscript><img src="//sadbmetrics.womennow.com/b/ss/vocwomennow,voces/1/G.4--NS/1231234?pageName=&g=/js-noactive&v55=womennow&c56=womennow" width = "1" height = "1" border="0" alt="*"/></noscript>
    <noscript><esi:vars><img src="//sb.scorecardresearch.com/p?c1=2&amp;c2=7715761&amp;gdpr=1&amp;gdpr_consent=$(HTTP_COOKIE{'euconsent-v2'})" height="1" width="1" alt="*" /></esi:vars></noscript>
    <!-- End vocento dtm+gtm+comscore -->
     <script type="vocento/script" src="//static.vocstatic.com/dab/latest/strategy.js"></script>

    <!-- START: c-header -->
    <header class="c-header" >
        <div class="o-container" >
            <div class="c-header__brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" title="Ir a Home WomenNow" aria-label="Ir a Home WomenNow">
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
            </div>

            <p class="c-header__claim"><span>Eventos inspiradores</span> <span>Actualidad imprescindible</span> <span>Voces extraordinarias</span></p>
            <div class="c-header__right" data-type="sticky">

                <?php
                    $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-principal'] );
                    $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
                ?>

               

                <nav class="c-header__nav">
                    <ul class="nav">

                        <?php 
                        for ($i=0;$i<count($menuitems);$i++) {
                            $item=$menuitems[$i];
                            $current='';

                            if ($item->object_id==$post->ID) {
                                $current='current';
                            }
                            if ($item->object_id==$post->post_parent) {
                                $current='current';
                            }
                            // if($post->post_type=='noticia'&&get_field('mujeres')!=1&&$item->title=='Noticias') $current='current';
                            // if($post->post_type=='noticia'&&get_field('mujeres')==1&&$item->title=='Mujeres Extraordinarias') $current='current';
                            // if($post->post_type=='observatorio'&&$item->title=='WonLab') $current='current';?>
                            <li class="nav-item select <?php echo implode(' ', $item->classes).' '.$current; ?>">
                                <?php
                                if($menuitems[$i+1]->menu_item_parent!=0){ ?>
                                    <a href="<?php echo $menuitems[$i]->url; ?>" title="Leer <?php echo stripQuotes($item->title); ?>" aria-label="Leer <?php echo stripQuotes($item->title); ?>"> <?php echo $item->title; ?> 
                                        <img class="arrow" src="<?php echo get_template_directory_uri(); ?>/assets/img/icon/arrow-menu.svg" width="10" alt="<?php echo (str_replace('"', '\'', strip_tags($item->title))); ?>" >
                                    </a>
                                    <ul class="nav-sublist">
                                    <?php while($menuitems[$i+1]->menu_item_parent!=0){?>
                                        <li class="nav-subitem <?php echo implode(' ', $menuitems[$i+1]->classes).' '.$current; ?>">
                                            <a href="<?php echo $menuitems[$i+1]->url; ?>" title="Ir a <?php echo $menuitems[$i+1]->title; ?>" aria-label="Ir a <?php echo $menuitems[$i+1]->title; ?>"><?php echo $menuitems[$i+1]->title; ?></a>
                                        </li>
                                <?php 
                                    $i++; 
                                } ?>    
                                </ul>
                                <?php }else { ?>
                                    <a href="<?php echo $item->url; ?>" title="Ir a <?php echo $item->title; ?>" aria-label="Ir a <?php echo $item->title; ?>"> <?php echo $item->title; ?> </a>
                                <?php } ?> 
                            </li>
                        <?php } ?>
                        

                    </ul>
				</nav>


                <nav class="c-header__options">

                    <?php
                    $locations = get_nav_menu_locations(); $menu = wp_get_nav_menu_object( $locations[ 'menu-rrss'] );
                    $menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
                    ?>

                    <?php foreach( $menuitems as $item ):  ?>

                            <?php if($item->title=='Facebook'): ?>
                                <a href="<?php echo $item->url ?>" title="Ir a Facebook de WomenNow" aria-label="Ir a Facebook de WomenNow" class="btn btn-primary btn-icon btn-facebook" target="_blank" rel="nofollow noopener">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                                    </svg>
                                    <span><?php echo $item->title ?></span>
                                </a>
                            <?php elseif($item->title=='Instagram'): ?>
                                <a href="<?php echo $item->url ?>" title="Ir a Instagram de WomenNow" aria-label="Ir a Instagram de WomenNow" class="btn btn-primary btn-icon btn-instagram" target="_blank" rel="nofollow noopener" >
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                    </svg>
                                    <span><?php echo $item->title ?></span>
                                </a>
                            <?php elseif($item->title=='Twitter'): ?>
                                <a href="<?php echo $item->url ?>" title="Ir a Twitter de WomenNow" aria-label="Ir a Twitter de WomenNow" class="btn btn-primary btn-icon btn-twitter" target="_blank" rel="nofollow noopener" >
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                    </svg>
                                    <span><?php echo $item->title ?></span>
                                </a>
                            <?php elseif($item->title=='LinkedIn'): ?>
                                <a href="<?php echo $item->url ?>" title="Ir a Linkedin de WomenNow" aria-label="Ir a Linkedin de WomenNow" class="btn btn-primary btn-icon btn-lkd" target="_blank" rel="nofollow noopener" >
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                                    </svg>
                                    <span><?php echo $item->title ?></span>
                                </a>
                            <?php endif; ?>

                    <?php endforeach; ?>

                    <?php $newsletter=get_page_by_title('Newsletter'); ?>
                    <a href="<?php echo get_page_link($newsletter->ID) ?>" title="Ir a Newsletter" aria-label="Ir a Newsletter" class="btn-primary btn-news" rel="nofollow" >Newsletter</a>
                    <button class="btn-icon btn-search" title="Buscar" data-type="toggle">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z" class=""></path>
                        </svg>
                        <span><?php echo _e('Buscar','won2020_v2') ?></span>
                    </button>

                </nav>
                <div class="c-header__search">
                    <form role="search" method="get" class="search-form">
                        <input name="s" id="s" type="text" placeholder="<?php echo _e('Buscar','won2020_v2') ?>...">
                        <button class="btn btn__icon btn__icon-search" type="button" onclick="submitForm();">
                            <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path>
                            </svg>
                            <span><?php echo _e('Buscar','won2020_v2') ?></span>
                        </button>

    					<script type="vocento/script">
                            function submitForm(){
                                    var urlParam = document.getElementById("s").value;
                                    var URL = "<?php echo home_url('/search/');?>";
                                    var encordedUrl = URL+encodeURIComponent(urlParam.toLowerCase().replace("+","").normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
                                    console.log(encordedUrl);
                                    document.location.href = encordedUrl;
                                }
                                $(document).ready(function() {
                                $(window).keydown(function(event){
                                    if(event.keyCode == 13) {
                                    event.preventDefault();
                                    submitForm();
                                    return false;
                                    }
                                });
                            });
                        </script>
                    </form>
                </div>
            </div>
            <button class="c-header__nav__btn hamburger hamburger--boring" data-type="menu">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </button>
        </div>
    </header>
    <!-- END: c-header -->
