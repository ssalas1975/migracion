<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width, shrink-to-fit=no" />
  <title>Moka</title>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/css/font-awesome.min.css">
  <!--link rel="stylesheet" href="css/owl.carousel.min.css"-->

  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/css/bootstrap-reboot.min.css">
  <!--link rel="stylesheet" href="css/animate.min.css"-->
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()?>/css/estilos.css">

  <script src="<?php echo get_stylesheet_directory_uri()?>/js/jquery.min.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri()?>/js/jquery-ui.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri()?>/js/tether.min.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri()?>/js/bootstrap.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri()?>/js/sitio.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri()?>/js/parallax.js"></script>
  

	<!--<script src="js/TweenMax.min.js"></script>
    <script src="js/ScrollToPlugin.min.js"></script>-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113278908-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-113278908-1');
    </script>

<?php wp_head()?>

  <?php
			switch_to_blog(get_current_blog_id());
			$nameofblog = get_bloginfo( 'name' );
			//$nameofblog = str_replace("-"," ",iconv('UTF-8','ASCII//TRANSLIT',$nameofblog));
			
			function cleanString($text) {
				$utf8 = array(
					'/[áàâãªä]/u'   =>   'a',
					'/[ÁÀÂÃÄ]/u'    =>   'A',
					'/[ÍÌÎÏ]/u'     =>   'I',
					'/[íìîï]/u'     =>   'i',
					'/[éèêë]/u'     =>   'e',
					'/[ÉÈÊË]/u'     =>   'E',
					'/[óòôõºö]/u'   =>   'o',
					'/[ÓÒÔÕÖ]/u'    =>   'O',
					'/[úùûü]/u'     =>   'u',
					'/[ÚÙÛÜ]/u'     =>   'U',
					'/ç/'           =>   'c',
					'/Ç/'           =>   'C',
					'/ñ/'           =>   'n',
					'/Ñ/'           =>   'N',
					'/:/'			=>	 '',
					'/ /'			=>   ' ',
					'/_/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
					'/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
					'/[“”«»„]/u'    =>   ' ', // Double quote
					'/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
				);
				return preg_replace(array_keys($utf8), array_values($utf8), $text);
			}
			
			$nameofblog = cleanString($nameofblog);
			$nameofblog = str_replace(" ","-",$nameofblog);
			$valueUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (strpos($valueUrl, 'contacto') !== false) {
				$nameofblog = "contacto";
			}

			$author_id = $post->post_author;
			$value_voc_au = get_the_author_meta( 'user_nicename' , $author_id );

			$value_voc_s1 = strtolower($nameofblog);
			$value_voc_s2 = "";
			$value_voc_s3 = "";
			$value_voc_s4 = "";
			$value_voc_tn = "portadilla";
			$value_voc_tc = "portadilla";
			$value_voc_tcnew = "portadilla";
			$value_voc_fep = "";
			$value_voc_fem = "";
			$value_voc_top = "";


			if( is_front_page() ){
			//$value_voc_s1 = "";
			}

			if( is_category() ){
				$value_voc_s2 = "categorias";
				$category = get_the_category();
				$value_voc_s3 = $category[0]->cat_name;
			}

			if( is_author() ){
			$value_voc_s2 = "autor";
			$value_voc_s3 = get_the_author_meta('user_nicename');
			}

			if( is_date() ){
				$value_voc_s2 = "archivo";
				$value_voc_s3 = get_the_time('Y').'/'.get_the_time('m');
			}

			if( is_singular('post') )
			{
				$postname= cleanString($post->post_name);
				$value_voc_tn = $postname;
				$value_voc_tc = "noticia";
				$value_voc_tcnew = "noticia.post";
				$value_voc_fep = gmdate("Ymdshm", get_post_time( ));
				$value_voc_fem = gmdate("Ymdshm", get_post_time( ));
				
				global $post;
				$tags = wp_get_post_tags($post->ID);
				$tagCount = 0;
				$max = count($tags);
				foreach( $tags as $tag )
				{
					$value_voc_top .= $tag->slug;
					if( $tagCount >= 0 && $tagCount < ($max-1) )
					{
						$value_voc_top.= ",";
					}
					$tagCount ++;
				}
			}

			$valueUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if (strpos($valueUrl, 'temas') !== false) {
				$value_voc_s1 = "temas";
				//$value_voc_s2 = cets_get_topic_name($topicid);
				$path = parse_url($valueUrl);
				$tema = explode("/",$path['path']);
				array_pop($tema);
				$value_voc_s2 = end($tema);
			}

			if ( is_tag() ) {
				$value_voc_s2 = "etiquetas";
				$value_voc_s3 = single_tag_title();
			}


			?>
			<script>
			var vot_ate = 'opinion';
			var voc_se = 'blogs';

			var voc_s1 = '<?php echo $value_voc_s1; ?>';
			var voc_s2 = '<?php echo $value_voc_s2; ?>';
			var voc_s3 = '<?php echo $value_voc_s3; ?>';
			var voc_s4 = '<?php echo $value_voc_s4; ?>';
			var voc_tn = '<?php echo $value_voc_tn; ?>';
			var voc_ho = '';
			var voc_tc = '<?php echo $value_voc_tc; ?>';
			var voc_tcnew = '<?php echo $value_voc_tcnew; ?>';

			var cov_ed = '';
			var voc_au = '<?php echo $value_voc_au; ?>';
			var voc_fep = '<?php echo $value_voc_fep; ?>';
			var voc_fem = '<?php echo $value_voc_fem; ?>';
			var voc_or = 'RL';
			var voc_pr = '1';
			var voc_pl = '';
			var voc_pu = 'wordpress';
			var voc_ate = 'opinion';
			var voc_adp = '';
			var voc_adb = '';
			var voc_top = '<?php echo $value_voc_top; ?>';
			var voc_idi = '';
			</script>


	<!-- Start vocento DTM H v2.2.52 --> 
	<script src="//pre-static.vocstatic.com/voonto/latest/vendors-head.js"></script>
	<script src="//pre-static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="4f979df643fa11b494d7ca20407fccd7b46fd491-staging"></script>
	<script>TagManager.ready.then(function(){TagManager.track();});</script>
	<!-- End vocento DTM H -->
  </head>
  
