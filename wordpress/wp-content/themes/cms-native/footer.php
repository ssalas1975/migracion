   <?php
   //obtenemos la URL del medio que ha la solicitud
   $url_solicita = 'http://'.$_SERVER['HTTP_HOST'];

   if (is_preview() && isset($_GET["medio"])) {
       $url_solicita=$_GET["medio"];
   }

   //extreamos la url del contenido solicitado para el campo requerido de la categoria

   $pie = valueByUrlMedio(get_the_ID(), $url_solicita, 'url_pie');
   $newperformance = $_GET['newperformance'];
    if($newperformance == 'validatenewperformance'){
        $checkSlug = valueByUrlMedio(get_the_ID(), $url_medio, 'slug');
        $re = '/\.(.*?)\./m';
        preg_match_all($re, $pie, $matches, PREG_SET_ORDER, 0);
        $frontalClient = new CacheExternalFile(1500);
        $url = $pie;
        $fileName = 'pie_'.$matches[0][1].'.html';
        $html = $frontalClient::getHtmlLayoutURL($url, $fileName);
    }else{
        $html = getContentH($pie);
    }

   //pintamos los datos de la cabecera
//    $html = getContentH($pie);
   $subst = '';
   $re = '/<script(.*)voonto.js(.*)<\/script>/m';
   $result = preg_replace($re, $subst, $html);
    echo $result;
    ?>

<!-- JavaScript plugins comes here -->

<script src="<?php echo get_template_directory_uri() ?>/js/waypoints.min.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Waypoints -->
<script src="<?php echo get_template_directory_uri() ?>/js/jquery.tooltipster.min.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Tool tip -->
<script src="<?php echo get_template_directory_uri() ?>/js/contentshare.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Contentshare -->
<script src="<?php echo get_template_directory_uri() ?>/js/funzioni.js<?php echo "?".rand(0, 1000);?>"></script> <!-- FUNZIONI Js file -->
<script src="<?php echo get_template_directory_uri() ?>/js/animacion-interna.js<?php echo "?".rand(0, 1000);?>"></script> <!-- animacion Js file -->
<script src="<?php echo get_template_directory_uri() ?>/js/evenZoom.js<?php echo "?".rand(0, 1000);?>"></script><!-- animacion Js file -->


<script src="<?php echo get_template_directory_uri() ?>/js/swiper.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Swiper JS -->
<script src="<?php echo get_template_directory_uri() ?>/js/swiper-set.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Initialize Swiper -->

<script src="<?php echo get_template_directory_uri() ?>/js/jquery.appear.js<?php echo "?".date("Y-m-d-h-m-s", time());?>"></script> <!-- Autoplay -->

<script src="<?php echo get_template_directory_uri() ?>/js/baguetteBox.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/highlight.min.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/gallerymodal.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/customninjaform.js<?php echo "?v2.0.9"?>"></script>
<script> Voonto.ready.then(function(app) { 
    app.load('https://static.vocstatic.com/video-player/1.3.3/video-player.js'); 
    }); </script>

   <?php wp_footer();
       do_action('vocento_analytics_before_body_end');
       ?>

</body>
</html>
