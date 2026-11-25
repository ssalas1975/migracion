   <?php
   //obtenemos la URL del medio que ha la solicitud
   $url_solicita = 'http://'.$_SERVER['HTTP_HOST'];

   if (is_preview() && isset($_GET["medio"])) {
       $url_solicita=$_GET["medio"];
   }

   //extreamos la url del contenido solicitado para el campo requerido de la categoria

    $url_medio_backend = 'http://' . $_SERVER['HTTP_HOST'];
    $re = '/(.*?)\./m';
    preg_match_all($re, $url_medio_backend, $matches, PREG_SET_ORDER, 0);
   $args = array(
        'post_type' => 'cabecera',
        'post_status' => 'publish',
        'tax_query' => array(
        array(
            'taxonomy' => 'Medios',
            'terms' => array( $matches[1][1] ),
            'field' => 'slug',
        )
        ),
        'posts_per_page' => -1
    );
    $postscabecera = get_posts( $args );


   if (!empty($postscabecera)) {
        foreach($postscabecera as $post){
            $htmlfooter = get_field('html_footer', $post->ID);
            echo $htmlfooter;
        }

    }else{
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
    }


    ?>

    <?php

    $host = str_replace("http://content-factory","https://www", $url_solicita);
    $path = str_replace("http://ms-brandedcontent.srv.vocento.in",$host."/contentfactory/post/", get_template_directory_uri());

    ?>

<!-- JavaScript plugins comes here -->

<script src="<?php echo $path ?>/js/waypoints.min.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Waypoints -->
<script src="<?php echo $path ?>/js/jquery.tooltipster.min.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Tool tip -->
<script src="<?php echo $path ?>/js/contentshare.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Contentshare -->
<script src="<?php echo $path ?>/js/funzioni.js<?php echo "?".rand(0, 1000);?>"></script> <!-- FUNZIONI Js file -->
<script src="<?php echo $path ?>/js/animacion-interna.js<?php echo "?".rand(0, 1000);?>"></script> <!-- animacion Js file -->
<script src="<?php echo $path ?>/js/evenZoom.js<?php echo "?".rand(0, 1000);?>"></script><!-- animacion Js file -->
<script src="<?php echo $path ?>/js/swiper.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Swiper JS -->
<script src="<?php echo $path ?>/js/swiper-set.js<?php echo "?".rand(0, 1000);?>"></script> <!-- Initialize Swiper -->
<script src="<?php echo $path ?>/js/jquery.appear.js<?php echo "?".date("Y-m-d-h-m-s", time());?>"></script> <!-- Autoplay -->
<script src="<?php echo $path ?>/js/baguetteBox.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo $path ?>/js/highlight.min.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo $path ?>/js/gallerymodal.js<?php echo "?".rand(0, 1000);?>"></script>
<script src="<?php echo $path ?>/js/customninjaform.js<?php echo "?v2.0.9"?>"></script>
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script> Voonto.ready.then(function(app) {
    app.load('https://static.vocstatic.com/video-player/1.3.3/video-player.js');
    }); </script>


   <?php wp_footer();
       do_action('vocento_analytics_before_body_end');
       ?>

</body>
</html>
