<?php

/**
 * won2020 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package won2020
 * 
 */

if (!function_exists('won2020_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function won2020_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on won2020, use a find and replace
		 * to change 'won2020' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('won2020', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'menu-principal' => esc_html__('Principal', 'won2020'),
			'menu-rrss' => esc_html__('Redes Sociales', 'won2020'),
			'menu-legales' => esc_html__('Legales', 'won2020'),
			'menu-idioma' => esc_html__('Idioma', 'won2020'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('won2020_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support('custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		));
	}
endif;
add_action('after_setup_theme', 'won2020_setup');


/* LIMITAR EXTRACTOS */
function custom_excerpt_length($length)
{
	return 40;
}
add_filter('excerpt_length', 'custom_excerpt_length', 100);

/*  ZONAS DE WIDGETS */
function won2020_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Link Footer', 'wp-bootstrap-starter'),
		'id'            => 'btn_summit',
		'before_widget' => '', 'after_widget'  => '',
	));
	register_sidebar(array(
		'name'          => esc_html__('Link Programa', 'wp-bootstrap-starter'),
		'id'            => 'btn_programa',
		'before_widget' => '', 'after_widget'  => '',
	));
}
add_action('widgets_init', 'won2020_widgets_init');


/*  SHORTCODES [widget widget="btn_summit"]*/
function getWidget($atts)
{
	return dynamic_sidebar($atts['widget']);
}
add_shortcode('widget', 'getWidget');


// Remove dashicons in frontend for unauthenticated users
add_action('wp_enqueue_scripts', 'bs_dequeue_dashicons');
function bs_dequeue_dashicons()
{
	if (!is_user_logged_in()) {
		wp_deregister_style('dashicons');
	}
}

/* QUITANDO GUTENBERG */
add_filter('use_block_editor_for_post_type', '__return_false', 100);


/* PONIENDO ENLACE */
$withLink = ['proyecto', 'red', 'observatorio', 'barometro', 'comentario', 'informe', 'women-borntodare', 'noticias-won', 'sucede-que', 'prensa'];
function ifLink($type)
{
	global $withLink;
	return (in_array($type, $withLink)) ? true : false;
}


/* THEME CLEAN */
$cleanTheme = ['nuestros-valores', 'noticias', 'noticia', 'quienes-somos', 'videos', 'podcast', 'observatorio', 'mujeres-extraordinarias', 'newsletter'];

function themeClean($type)
{
	global $cleanTheme;
	return (in_array($type, $cleanTheme) || is_404()) ? true : false;
}

// /* SI ES PAGINA */
// $postPage=['prioridades','blog'];
// function ifPage($type){
// 	global $postPage;
// 	return (in_array($type,$postPage))?true:false;
// }

/* QUITANDO ENLACES EN LA MIGA */
// $removeLink =['Quienes somos','Equipo de trabajo','Summit','Wonlab','Papers'];
// function wpseo_remove_breadcrumb_link( $link_output , $link ){
// 	global $removeLink;
// 	foreach ($removeLink as $link_txt) {
// 		if( $link['text'] == $link_txt ) {$link_output = $link_txt ;}
// 	}
// 	return $link_output;
// }
// add_filter( 'wpseo_breadcrumb_single_link' ,'wpseo_remove_breadcrumb_link', 10 ,2);

/* QUITANDO TODOS LOS ENLACES EN LA MIGA */
function wpseo_remove_all_breadcrumb_link($link_output, $link)
{
	$link_output = $link['text'];
	return $link_output;
}
add_filter('wpseo_breadcrumb_single_link', 'wpseo_remove_all_breadcrumb_link', 10, 2);

// EXCERPT PARA PROYECTOS
function excerpt($title, $cutOffLength)
{

	$charAtPosition = "";
	$titleLength = strlen($title);

	do {
		$cutOffLength++;
		$charAtPosition = substr($title, $cutOffLength, 1);
	} while ($cutOffLength < $titleLength && $charAtPosition != " ");

	return substr($title, 0, $cutOffLength) . '...';
}

// TESA EXCERPT PARA LOS TITULARES DEL MODULO DE PORTADA
function title_excerpt($title, $cutOffLength)
{
	$string = substr($title, 0, $cutOffLength + 1);
	return substr($string, 0, strrpos($string, ' ')) . '...';
}

/* GET ANCESTOR ID */
function get_the_top_ancestor_id()
{
	global $post;
	if ($post->post_parent) {
		$ancestors = array_reverse(get_post_ancestors($post->ID));
		return $ancestors[0];
	} else {
		return $post->ID;
	}
}

function set_script($post)
{

	// echo 'home: '.get_home_url().'/'.'<br>';
	// echo 'anterior: '.$_SERVER['HTTP_REFERER'];

	$seccion = $post->post_name;
	if ($post->post_type == 'noticia') $seccion = 'noticias';
	if ($post->post_type == 'observatorio') $seccion = 'observatorio';
	if ($post->post_name == 'observatorio') $seccion = 'wonlab';
	///
	$subseccion1 = '';
	if ($post->post_type == 'noticia') $subseccion1 = 'noticia';
	if ($post->post_type == 'observatorio') $subseccion1 = 'informe';
	if ($post->post_name == 'observatorio') $subseccion1 = 'observatorio';
	///
	$titular = $post->post_name;
	if ($post->post_type == 'noticia') $titular = $post->post_title;
	if ($post->post_type == 'observatorio') $titular = $post->post_title;
	///
	$origen = ($_SERVER['HTTP_REFERER'] == (get_home_url() . '/')) ? '1' : '0';

?>
	<script type="vocento/script">

		TagManager.ready.then(function() {
			var plataformaContenido = 'contenido-web';
			if (typeof getTipoDispositivo === "function") {
				var tipoDispositivo = getTipoDispositivo();
				if (tipoDispositivo === 'mobile') {
					plataformaContenido = 'm-responsive';
				} else {
					plataformaContenido = 'contenido-web';
				}
			}
			TagManager.set({
				url: "<?php echo home_url(add_query_arg(array())) ?>",
				seccion: "<?php echo $seccion ?>",
				subseccion1: "<?php echo $subseccion1 ?>",
				subseccion2: "",
				subseccion3: "",
				plataformaContenido: plataformaContenido,
				titular: "<?php echo addslashes($titular); ?>",
				tipoDominio: "1",
				<?php if ($post->post_type == 'noticia') { ?>
					<?php
					if (have_rows('galeria')) {
						$tipoContenido = 'fotogaleria';
						$tipoContenidoA = 'fotogaleria-vertical';
						$slot = 0;
					?>
						slot: "<?php echo $slot; ?>",
					<?php
					} else {
						$tipoContenido = 'noticia';
						$tipoContenidoA = 'noticia-estandar';
					}
					?>
					tipoContenido: "<?php echo $tipoContenido; ?>",
					tipoContenidoA: "<?php echo $tipoContenidoA; ?>",
					fechaPrimeraPublicacion: "<?php echo $post->post_date ?>",
					fechaModificacion: "<?php echo $post->post_modified ?>",
				<?php } else if ($post->post_type == 'observatorio') { ?>
					tipoContenido: "informe",
					tipoContenidoA: "informe-observatorio",
					fechaPrimeraPublicacion: "<?php echo $post->post_date ?>",
					fechaModificacion: "<?php echo $post->post_modified ?>",
				<?php } else if ($seccion == 'home') { ?>
					tipoContenido: "portada",
					tipoContenidoA: "",
					fechaPrimeraPublicacion: "",
					fechaModificacion: "",
				<?php } else { ?>
					tipoContenido: "portadilla",
					tipoContenidoA: "",
					fechaPrimeraPublicacion: "",
					fechaModificacion: "",
				<?php } ?>
				origenPortada: "<?php echo $origen; ?>",
				idioma: "<?php echo ICL_LANGUAGE_CODE; ?>"
			}).track();
		});
	</script>

<?php
}



// VARIABLES
// registro la variable
// function add_var($public_query_vars) {
//     $public_query_vars[] = 's_video';
//     return $public_query_vars;
// }
// add_filter('query_vars', 'add_var');

// // reescribo las reglas
// function do_rewrite() {
//     add_rewrite_rule('^videos/([^/]*)/?', 'index.php?pagename=videos&s_video=$matches[1]','top');
//     add_rewrite_rule('^podcast/([^/]*)/?', 'index.php?pagename=podcast&s_video=$matches[1]','top');
// }
// add_action('init', 'do_rewrite');

// // REDIRECT SEARCH
// function permalink_search() {
//      global $wp_rewrite;
//      ///search invidual
//      if ( strpos( $_SERVER['REQUEST_URI'], "?s_video=" )) {
//         global $wp;
//         wp_redirect(  home_url( $wp->request )."/".urlencode( get_query_var( 's_video' ))."/" ); exit();
//      }
//  }

// add_action( 'template_redirect', 'permalink_search' );


////BREADCRUMB
add_filter('wpseo_breadcrumb_single_link', 'wpseo_remove_breadcrumb_link', 10, 2);

function wpseo_remove_breadcrumb_link($link_output, $link)
{

	$link_output = str_replace('Página', '', $link['text']);

	return $link_output;
}


// POPULAR POST
function count_view($post_id)
{
	$fechas_key = 'date_view';
	$views_last_week = 'views_last_week';
	$fechas = get_post_meta($post_id, $fechas_key, true);
	// si no existe
	if ($fechas == '') {
		delete_post_meta($post_id, $fechas_key);
		delete_post_meta($post_id, $views_last_week);
		add_post_meta($post_id, $fechas_key, array());
		add_post_meta($post_id, $views_last_week, '0');
	} else {
		array_push($fechas, date('Ymd'));
		update_post_meta($post_id, $fechas_key, $fechas);
		///calculo cuantas fechas hay dentro de la ultima semana
		$num_visitas_lw = 0;
		foreach ($fechas as $key => $fecha) {
			$last_week = date("Ymd", strtotime("-7 days"));
			// echo date('Ymd',strtotime($fecha)).' > '.$last_week.'<br>';
			if (date('Ymd', strtotime($fecha)) >= $last_week) $num_visitas_lw++;
		}
		///meto el valor
		update_post_meta($post_id, $views_last_week, $num_visitas_lw);
	}
}

function set_view_post($post_id)
{
	if (!is_single()) {
		return;
	}
	if (empty($post_id)) {
		global $post;
		$post_id = $post->ID;
	}
	count_view($post_id);
}

add_action('wp_head', 'set_view_post');

// function count_view($post_id) {
// 	$count_key = 'popular_posts';
// 	$count = get_post_meta($post_id, $count_key, true);
// 	if ($count == '') {	$count = 0; delete_post_meta($post_id, $count_key); add_post_meta($post_id, $count_key, '0'); }
// 	else {	$count++; update_post_meta($post_id, $count_key, $count);	}
// }

// function set_view_post($post_id) {
// 	if (!is_single()){ return;}
// 	if (empty($post_id)) {	global $post; $post_id = $post->ID; }
// 	count_view($post_id);
// }

// add_action('wp_head', 'set_view_post');

// SEARCH
function wpb_change_search_url()
{
	if (is_search() && !empty($_GET['s'])) {
		wp_redirect(home_url("/search/") . urlencode(get_query_var('s')));
		exit();
	}
}
add_action('template_redirect', 'wpb_change_search_url');


add_filter('run_wptexturize', '__return_false');


/***************** AMP **************/

//define('WON_AMP_QUERY_VAR', 'amp');

add_rewrite_endpoint(WON_AMP_QUERY_VAR, EP_ALL);
//add_post_type_support('post', WON_AMP_QUERY_VAR);
//add_post_type_support('page', WON_AMP_QUERY_VAR);
//add_post_type_support('noticias', WON_AMP_QUERY_VAR);


add_filter('request', 'check_amp');
function check_amp(array $query_vars)
{

	//var_dump($query_vars);
	// echo '<br>';
	// var_dump($query_vars['amp']);

	if ($query_vars['amp'] === '') {
		add_filter('template_include', 'amp_page_template', 99);
	}
	return $query_vars;
}

/***************** AMP **************/



define('AMP_QUERY_VAR', apply_filters('amp_query_var', 'amp'));

add_rewrite_endpoint(AMP_QUERY_VAR, EP_PERMALINK);

function amp_page_template($template)
{

	if (get_query_var(AMP_QUERY_VAR, false) !== false) {
		if (is_single()) {
			// Ensure PHP agent is available
			if (\extension_loaded('newrelic')) {
				// Disable newRelic JS
				if (function_exists('newrelic_disable_autorum')) {
					newrelic_disable_autorum();
				}
			}
			$template = get_template_directory() .  '/template-parts/amp/single.php';
		}
	}
	return $template;
}

function acf_replace_amp_tags($titlePost = '', $field, $post_type = '', $iscontent = false)
{
	$dom = new DomDocument();
	if ($iscontent == false) {
		@$dom->loadHTML(mb_convert_encoding($field, 'HTML-ENTITIES', 'utf-8'));
	} else {
		//entran los shortcodes
		$dom->loadHTML(mb_convert_encoding(wpautop($field), 'HTML-ENTITIES', 'utf-8'));
	}
	$field = amp_img_replace($dom);
	$field = amp_twitter_replace($dom);
	$field = amp_youtube_replace($dom);
	$field = amp_instagram_replace($titlePost, $dom);
	$field = amp_brightcove_replace($dom);
	$field = amp_podcast_replace($dom);
	$field = ($post_type !== 'noticia') ? $field : amp_ad_insert($dom);
	$search = ['<iframe', '</iframe>', '<img', '</img>', '<html>', '</html>', '<body>', '</body>', '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', 'loading="lazy"'];
	$replace = ['<amp-iframe', '</amp-iframe>', '<amp-img', '</amp-img>', '', '', '', '', '', ''];

	return str_replace($search, $replace, $field);
}

function amp_img_replace($dom)
{


	$iframes = $dom->getElementsByTagName('img');

	foreach ($iframes as $iframe) {

		$a = $dom->createElement('amp-img');
		$a->setAttribute('width', $iframe->getAttribute('width'));
		$a->setAttribute('height', $iframe->getAttribute('height'));
		$a->setAttribute('layout', 'responsive');
		$a->setAttribute('src', $iframe->getAttribute('src'));
		$iframe->parentNode->replaceChild($a, $iframe);
	}

	return $dom->saveHTML();
}

function amp_twitter_replace($dom)
{
	$iframes = $dom->getElementsByTagName('blockquote');
	$twitterIframes = array();

	foreach ($iframes as $iframe) {
		if ($iframe->getAttribute('class') === 'twitter-tweet') {
			$twitterIframes[] = $iframe;
		}
	}

	foreach ($twitterIframes as $iframe) {
		$links = $iframe->lastChild->getElementsByTagName('a');
		foreach ($links as $link) {
			if (preg_match('/\btwitter\b/', $link->getAttribute('href'))) {
				$twitterURL = explode('/', $link->getAttribute('href'));
				$twitterId = explode('?', $twitterURL[count($twitterURL) - 1]);
			}
		}
		$a = $dom->createElement('amp-twitter');
		$a->setAttribute('width', 480);
		$a->setAttribute('height', 270);
		$a->setAttribute('layout', 'responsive');
		$a->setAttribute('data-tweetid', $twitterId[0]);
		$iframe->parentNode->replaceChild($a, $iframe);
	}
	return $dom->saveHTML();
}

function amp_youtube_replace($dom)
{

	$iframes = $dom->getElementsByTagName('iframe');
	$youtubeIframes = array();
	foreach ($iframes as $iframe) {
		if (preg_match('/\byoutube\b/', $iframe->getAttribute('src'))) {
			$youtubeIframes[] = $iframe;
		}
	}

	foreach ($youtubeIframes as $iframe) {
		$iframe->getAttribute('src');
		$youtubeURL = explode('/', $iframe->getAttribute('src'));
		$youtubeId = explode('?', $youtubeURL[count($youtubeURL) - 1]);
		$a = $dom->createElement('amp-youtube');
		$a->setAttribute('width', 480);
		$a->setAttribute('height', 270);
		$a->setAttribute('layout', 'responsive');
		$a->setAttribute('data-videoid', $youtubeId[0]);
		$iframe->parentNode->replaceChild($a, $iframe);
	}

	return $dom->saveHTML();
}

function amp_podcast_replace($dom)
{

	$iframes = $dom->getElementsByTagName('iframe');
	$podcastIframes = array();
	foreach ($iframes as $iframe) {
		if (preg_match('/\bspotify\b/', $iframe->getAttribute('src'))) {
			$podcastIframes[] = $iframe;
		}
		if (preg_match('/\bomny\b/', $iframe->getAttribute('src'))) {
			$podcastIframes[] = $iframe;
		}
	}

	foreach ($podcastIframes as $iframe) {
		$iframe->getAttribute('src');
		$podcastURL = $iframe->getAttribute('src');
		$a = $dom->createElement('amp-iframe');
		$a->setAttribute('width', 480);
		$a->setAttribute('height', 232);
		$a->setAttribute('layout', 'responsive');
		$a->setAttribute('src', $podcastURL);
		$iframe->parentNode->replaceChild($a, $iframe);
	}

	return $dom->saveHTML();
}

function amp_brightcove_replace($dom)
{

	$iframes = $dom->getElementsByTagName('iframe');
	$bcIframes = array();
	foreach ($iframes as $iframe) {
		if (preg_match('/\bplayers.brightcove.net\b/', $iframe->getAttribute('src'))) {
			$bcIframes[] = $iframe;
		}
	}

	foreach ($bcIframes as $iframe) {
		$iframe->getAttribute('src');
		$bcId = explode('?videoId=', $iframe->getAttribute('src'));
		$el = $dom->createElement('amp-brightcove');
		$el->setAttribute('width', 480);
		$el->setAttribute('height', 270);
		$el->setAttribute('layout', 'responsive');
		$el->setAttribute('data-video-id', $bcId[1]);
		$el->setAttribute('data-player', 'EimEVWH14r');
		$el->setAttribute('data-account', '6019218709001');
		$iframe->parentNode->replaceChild($el, $iframe);
	}

	return $dom->saveHTML();
}

function amp_instagram_replace($titlePost = '', $dom)
{
	while (($r = $dom->getElementsByTagName("script")) && $r->length) {
		$r->item(0)->parentNode->removeChild($r->item(0));
	}


	$iframes = $dom->getElementsByTagName('blockquote');
	$instaIframes = array();
	foreach ($iframes as $iframe) {
		if ($iframe->getAttribute('class') === 'instagram-media') {
			$instaIframes[] = $iframe;
		}
	}

	foreach ($instaIframes as $iframe) {
		$iframe->getAttribute('data-instgrm-permalink');
		$instaId = explode('/', str_replace('https://www.instagram.com/p/', '', $iframe->getAttribute('data-instgrm-permalink')));
		$a = $dom->createElement('amp-instagram');
		$a->setAttribute('width', 1);
		$a->setAttribute('height', 1);
		$a->setAttribute('layout', 'responsive');
		$a->setAttribute('data-shortcode', $instaId[0]);
		$a->setAttribute('title', 'Instagram: ' . $titlePost);
		$iframe->parentNode->replaceChild($a, $iframe);
	}


	return $dom->saveHTML();
}

function amp_ad_insert($dom)
{
	$paragraphs = $dom->getElementsByTagName('p');

	$roba1 = $dom->createElement('amp-ad');
	$roba1->setAttribute('data-loading-strategy', '0.5');
	$roba1->setAttribute('data-npa-on-unknown-consent', 'true');
	$roba1->setAttribute('data-block-on-consent', 'true'); //CMV true?
	$roba1->setAttribute('width', '300');
	$roba1->setAttribute('height', '600');
	$roba1->setAttribute('style', 'display:block; margin: 15px auto;');
	$roba1->setAttribute('type', 'doubleclick');
	$roba1->setAttribute('data-slot', '/4900/webm.WOMENNOW/AMP/' . sanitize_title(get_the_category()[0]->cat_name));
	$roba1->setAttribute('json', '{"targeting":{"pos":"robapaginas"}}');
	$roba1->setAttribute('data-multi-size', '300x250');
	$roba1->setAttribute('data-multi-size-validation', 'false');

	$roba2 = $dom->createElement('amp-ad');
	$roba2->setAttribute('data-loading-strategy', '0.5');
	$roba2->setAttribute('data-npa-on-unknown-consent', 'true');
	$roba2->setAttribute('data-block-on-consent', 'true'); //CMV true?
	$roba2->setAttribute('width', '300');
	$roba2->setAttribute('height', '600');
	$roba2->setAttribute('style', 'display:block; margin:15px auto;');
	$roba2->setAttribute('type', 'doubleclick');
	$roba2->setAttribute('data-slot', '/4900/webm.WOMENNOW/AMP/' . sanitize_title(get_the_category()[0]->cat_name));
	$roba2->setAttribute('json', '{"targeting":{"pos":"robapaginas_dos"}}');
	$roba2->setAttribute('data-multi-size', '300x250');
	$roba2->setAttribute('data-multi-size-validation', 'false');

	$boton_native = $dom->createElement('amp-ad');
	$boton_native->setAttribute('data-loading-strategy', '0.5');
	$boton_native->setAttribute('data-npa-on-unknown-consent', 'true');
	$boton_native->setAttribute('data-block-on-consent', 'true'); //CMV true?
	$boton_native->setAttribute('layout', 'fluid');
	$boton_native->setAttribute('height', 'fluid');
	$boton_native->setAttribute('type', 'doubleclick');
	$boton_native->setAttribute('data-slot', '/4900/webm.WOMENNOW/AMP/' . sanitize_title(get_the_category()[0]->cat_name));
	$boton_native->setAttribute('json', '{"targeting":{"pos":"boton_native"}}');
	$boton_native->setAttribute('data-multi-size-validation', 'false');

	$paragraphs[1]->appendChild($roba1);
	$paragraphs[2]->appendChild($boton_native);
	if ($paragraphs->length >= 5) {
		$paragraphs[4]->appendChild($roba2);
	}

	return $dom->saveHTML();
}

function amp_replace_audio_iframe($audio, $title)
{
	$dom = new DomDocument();
	$dom->loadHTML($audio);

	$src = $dom->getElementsByTagName('iframe');
	$audio_url = $src[0]->getAttribute('src');
	$audio_title = replace_invalid_charts($title);

	$amp_audio = "<amp-iframe class='iframe'
					style='max-height:220px' 
                    width='200'
                    height='200'
                    sandbox='allow-scripts allow-popups allow-popups-to-escape-sandbox allow-same-origin' 
                    layout='responsive' 
                    frameborder='0' 
                    src='" . $audio_url . "'>
                    <amp-img src='https://static-1.ivoox.com/img/web/comunidad_LG.jpg' 
                        placeholder 
                        width='500' 
                        height='500' 
                        layout='responsive' 
                        alt='" . $audio_title . "'>
                    </amp-img>
                </amp-iframe>";

	return $amp_audio;
}

function replace_invalid_charts($string, $isDescription = false)
{
	$invalidCharts = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
	$validCharts = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
	$string = wp_strip_all_tags(strip_shortcodes($string));
	if ($isDescription) {
		$string = str_replace(array('&', '\'', '"', '<blockquote>', '</blockquote>', '<br>', '<br />'), array('', '', '', '', '', '', '', '', '', '', ''), $string);
	} else {
		$string = str_replace(array('&', '\'', '"', '<blockquote>', '</blockquote>', '<br>', '<br />'), array('', '&#039;', '&quot;', '', '', '', '', '', '', '', ''), $string);
	}
	$string = str_replace($invalidCharts, $validCharts, $string);
	return $string;
}

function add_lazyload($content)
{
	$content = preg_replace("/<img(.*?)(src=|srcset=)(.*?)>/i", '<img$1data-$2$3>', $content);
	$content = preg_replace('/(<img[^><]*)\/>/i', '$1 loading="lazy" />', $content);
	$content = preg_replace('/(<img[^><]*)\/>/i', '$1 src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="/>', $content);
	return $content;
}


function my_acf_load_value($value, $post_id, $field)
{

	if (is_string($value)) {
		if ($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES") {
			$value = str_replace('href="https://prems-mhsites.srv.vocento.in/womennow/', 'href="https://pre-www.womennow.es/',  $value);
		} else {
			$value = str_replace('href="https://ms-mhsites.srv.vocento.in/womennow/', 'href="https://www.womennow.es/',  $value);
		}
	}

	return $value;
}

add_filter('acf/load_value', 'my_acf_load_value', 10, 3);


function check_paragraphs($field)
{
	$dom = new DomDocument();
	$dom->loadHTML(mb_convert_encoding($field, 'HTML-ENTITIES', 'utf-8'));
	$paragraphs = $dom->getElementsByTagName('p');
	if ($paragraphs->length < 5) {
		return true;
	} else {
		return false;
	}
}

/**Reemplazo a Tags del DOM
 * Función que se encarga de añadir y/o reemplazas el código necesario al contenido DOM de POST con el objetico de añadir atributos necesarios ó requerido de shortcode como el título del iframe
 * @param 		$urlPost 				Url del post que embebe el shortocode
 * 				$titlepost				Title del post que embebe el shortocode
 *  			$dom			        Contenido - DOM del post que embebe el shortocode
 *   			$post_type			    Tipo de post que embebe el shortocode
 *    			$iscontent			    Si es contenido the_content() ó es un campo personalizado, valor default: false
 * @return DOM modificado con los atributos necesarios y nuevos.
 **/
function acf_replace_tags($urlpost, $titlepost, $field, $post_type = '', $iscontent = false)
{
	//echo 'Entra a funcion acf_replace_tags';
	$dom = new DomDocument();
	if ($iscontent == false) {
		@$dom->loadHTML(mb_convert_encoding($field, 'HTML-ENTITIES', 'utf-8'));
	} else {
		//entran los shortcodes
		$dom->loadHTML(mb_convert_encoding(wpautop($field), 'HTML-ENTITIES', 'utf-8'));
	}

	$field = instagram_replace($urlpost, $titlepost, $dom);
	$field = tiktok_replace($urlpost, $titlepost, $dom);

	return $field;
}

/**Reemplazo a Embebido-shortcode Post de Instagram
 * Función que se encarga de añadir el código necesario al embebido-shortcode del Post de Instagram, para añadir atributos como el título del iframe
 * @param 		$urlPost 				Url del post que embebe el shortocode de Instagram
 * 				$titlepost				Title del post que embebe el shortocode de Instagram
 *  			$dom			        Contenido - DOM del post que embebe el shortocode de Instagram
 * @return DOM modificado con los atributos necesarios y nuevos.
 **/

function instagram_replace($urlpost, $titlepost, $dom)
{

	$iframes = $dom->getElementsByTagName('blockquote');
	$instaIframes = array();
	foreach ($iframes as $iframe) {
		if ($iframe->getAttribute('class') === 'instagram-media') {
			$instaIframes[] = $iframe;
		}
	}

	foreach ($instaIframes as $iframe) {
		$iframe->getAttribute('data-instgrm-permalink');
		$instaId = explode('/', str_replace('https://www.instagram.com/p/', '', $iframe->getAttribute('data-instgrm-permalink')));
		$a = $dom->createElement('iframe');
		$urlHexa = str_replace('/', '%2F', $urlpost);
		$urlHexa = str_replace(':', '%3A', $urlHexa);
		$a->setAttribute('src', 'https://www.instagram.com/p/' . $instaId[0] . '/embed/?cr=1&v=13&wp=599&rd=' . $urlHexa);
		$a->setAttribute('title', 'Instagram: ' . $titlepost);
		$a->setAttribute('class', 'instagram-media instagram-media-rendered');
		$a->setAttribute('id', 'instagram-embed-0');
		$a->setAttribute('allowtransparency', 'true');
		$a->setAttribute('allowfullscreen', 'true');
		$a->setAttribute('frameborder', '0');
		$a->setAttribute('height', '747');
		$a->setAttribute('data-instgrm-payload-id', '0');
		$a->setAttribute('scrolling', 'no');
		$a->setAttribute('style', 'background: white; max-width: 540px; width: calc(100% - 2px); border-radius: 3px; border: 1px solid rgb(219, 219, 219); box-shadow: none; display: block; margin: 0px 0px 12px; min-width: 326px; padding: 0px;');

		$iframe->parentNode->replaceChild($a, $iframe);
	}

	return $dom->saveHTML();
}

/**Reemplazo a Embebido-shortcode Post de TikTok
 * Función que se encarga de añadir el código necesario al embebido-shortcode del Post de TikTok, para añadir atributos como el título del iframe
 * @param 		$urlPost 				Url del post que embebe el shortocode de TikTok
 * 				$titlepost				Title del post que embebe el shortocode de TikTok
 *  			$dom			        Contenido - DOM del post que embebe el shortocode de TikTok
 * @return DOM modificado con los atributos necesarios y nuevos.
 **/

function tiktok_replace($urlpost, $titlepost, $dom)
{

	$iframes = $dom->getElementsByTagName('blockquote');

	$tiktokIframes = array();
	foreach ($iframes as $iframe) {
		if ($iframe->getAttribute('class') === 'tiktok-embed') {
			$tiktokIframes[] = $iframe;
		}
	}

	foreach ($tiktokIframes as $iframe) {
		$tiktokId = $iframe->getAttribute('data-video-id');
		$a = $dom->createElement('iframe');
		$b = $dom->$a->createElement('blockquote');
		$urlHexa = str_replace('/', '%2F', $urlpost);
		$urlHexa = str_replace(':', '%3A', $urlHexa);
		$a->setAttribute('src', 'https://www.tiktok.com/embed/v2/' . $tiktokId . '?lang=es-ES&referrer=' . $urlHexa);
		$a->setAttribute('title', 'Tiktok: ' . $titlepost);
		$a->setAttribute('name', '__tt_embed__v58904919642258530');
		$a->setAttribute('sandbox', 'allow-popups allow-popups-to-escape-sandbox allow-scripts allow-top-navigation allow-same-origin');
		$a->setAttribute('style', 'width: 100%; height: 757px; display: block; visibility: unset; max-height: 757px;');
		$b->setAttribute('title', 'Tiktok: ' . $titlepost);

		$iframe->parentNode->replaceChild($a, $iframe);
		//$dom->saveHTML();
		//$iframe->insertBefore($b, $a);
		//$block = $iframes->insertBefore($b, $iframes);
		$iframe->parentNode->parentNode->replaceChild($b, $iframe);
	}

	return $dom->saveHTML();
}


add_theme_support('post-thumbnails');
add_image_size('module-imgs', 460, 322, true);
add_image_size('video-imgs', 407, 285, true);
add_image_size('lomas-imgs', 215, 215, true);
add_image_size('portada-noticias-imgs', 360, 252, true);
add_image_size('newsarticle', 1200, 630, true);

function newsarticle_schema($post)
{

	$description = replace_invalid_charts(get_field('resumen'), true);
	$subSeccion = 'noticia';
	$seccion = 'noticias';
	if ($post->post_content == '') {
		$contenido =  str_replace('"', '\'', strip_tags(get_field('contenido')));
	} else {
		$contenido =  str_replace('"', '\'', strip_tags($post->post_content));
	}
	$script = '<script type="application/ld+json">';
	$script .= '{"@context":"https://schema.org","@type":"NewsArticle","mainEntityOfPage":{"@type":"WebPage","@id":"' . get_permalink() . '"},"headline":"' . str_replace('"', '\'', strip_tags(get_the_title())) . '","image":{"@type":"ImageObject","url":"' . site_url() . get_the_post_thumbnail_url($post->post_ID, 'newsarticle') . '","width":1200,"height":630},"datePublished":"' . get_the_date('Y-m-d\\TH:i:s') . '+02:00","publisher":{"@type":"NewsMediaOrganization","name":"Women Now","logo":{"@type":"ImageObject","url":"' . get_template_directory_uri() . '/assets/img/logowomennow260x106.svg","width":260,"height":106}},"author":{"@type":"Person","name":"WomenNow"},"description":"' . $description . '","articleBody":"' . $contenido . '","articleSection":"' . $seccion . ',' . $subSeccion . '","dateModified":"' . get_post_modified_time('Y-m-d\\TH:i:s') . '+02:00"}';
	$script .= '</script>';

	return $script;
}

function videoobject_schema($post)
{

	$url = "https://api-video-player.vocento.com/videos/source/brightcove/account/womennow/" . get_field('idvideo') . ".json";
	try {
		$json = file_get_contents($url, 0, stream_context_create(["https" => ["timeout" => 10]]));
		if (false === $json) {
			$milliseconds = 0;
		} else {
			$data = json_decode($json, true);
			$milliseconds =  $data['video']['metadata']['duration'];
		}
	} catch (Exception $e) {
		$milliseconds = 0;
	}

	$seconds = floor($milliseconds / 1000);
	$minutes = floor($seconds / 60);
	$hours = floor($minutes / 60);
	$seconds %= 60;
	$minutes %= 60;
	$script = '<script type="application/ld+json">[{
						"@context":"https://schema.org",
						"@type":"VideoObject",
						"name":"' . get_field("pretitulo") . '",
						"description":"' . str_replace('"', '\'', strip_tags(get_the_title())) . '",
						"thumbnailUrl":"' . site_url() . get_the_post_thumbnail_url($post->post_ID, "newsarticle") . '",
						"uploadDate":"' . get_the_date("Y-m-d\\TH:i:s") . 'Z",
						"publisher":{
							"@type":"Organization",
							"name":"Women Now",
							"logo":{
								"@type":"ImageObject",
								"url":"' . get_template_directory_uri() . '/assets/img/logowomennow260x106.svg",
								"width":260,
								"height":106}},
						"embedUrl":"http://players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId=' . get_field("idvideo") . '",
						"duration":"PT' . $hours . 'H' . $minutes . 'M' . $seconds . 'S"}]
		   		   </script>';

	return $script;
}

function audioobject_schema($post)
{

	$seconds = 0;
	$minutes = 0;
	$hours = 0;
	$audio = get_field('audio');
	preg_match('/(?<!_)src=([\'"])?(.*?)\\1/', $audio, $match);
	$embedUrl = substr($match[0], 5, -1);
	$description = strip_tags(str_replace('"', "'", $post->post_content));

	try {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $embedUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch);

		preg_match("/<div class=\"duration\">(.*?)<\/div>/i", $result, $matches);
		$time = explode(':', $matches[0]);
		if (!$time[2]) {
			$minutes = substr($time[0], -2);
			$seconds = substr($time[1], 0, -6);
		} else {
			$hours = substr($time[0], -2);
			$minutes = $time[1];
			$seconds = substr($time[2], 0, -6);
		}
	} catch (Exception $e) {
	}

	$script = '<script type="application/ld+json">[{
					"@context":"https://schema.org",
					"@type":"PodcastEpisode",
					"url":"' . get_permalink() . '",
					"name":"' . get_the_title() . '",
					"datePublished":"' . get_the_date("Y-m-d\\TH:i:s") . 'Z",
					"timeRequired":"PT' . $hours . 'H' . $minutes . 'M' . $seconds . 'S",
					"description":"' . $description . '",
					"partOfSeries":{
						"@type": "PodcastSeries",
						"name":"' . get_field("pretitulo") . '",
						"url":"' . site_url() . '/podcast/"
					}}]
				</script>';
	return $script;
}

function podcastseries_schema()
{

	$script = '<script type="application/ld+json">{
				"@context": "https://schema.org/",
				"@type": "PodcastSeries",
				"image": "' . get_the_post_thumbnail_url() . '",
				"url":"' . get_permalink() . '",
				"name":"' . get_the_title() . '",
				"description": "' . get_the_title() . '",
				"author":{
					"@type": "Organization",
					"name": "WomenNOW",
					"url": "' . site_url() . '"
					}}
			  </script>';

	return $script;
}

function schemas_womennow($post)
{

	$script = '';

	$post = get_post($post->ID);
	$GLOBALS['current_id'] = $post->ID;
	$GLOBALS['current_type'] = $post->post_type;
	if ($post->post_type == 'noticia') {
		$description = replace_invalid_charts(get_field('resumen'), true);
	} else {
		$description = replace_invalid_charts($post->post_content);
	}

	if ($post->post_type == 'videocpt') {
		$seccion = 'videos';
		$subSeccion = 'video';
		$contenido = strip_tags(get_the_content());
	}

	if ($post->post_type == 'audio') {
		$seccion = 'podcast';
		$subSeccion = 'podcast';
		$contenido = strip_tags(get_the_content());
	}

	if ($post->post_type == 'noticia') {
		$subSeccion = 'noticia';
		$seccion = 'noticias';
		$contenido =  str_replace('"', '\'', strip_tags(get_field('contenido')));
	}

	$seccion = (is_single()) ? $post->post_type : $post->post_name;

	$homePage = get_page_by_title('Home');
	$postID = $post->ID;

	$urlPage = get_permalink(0, false);

	if (is_archive() || is_page()) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}
	if (get_field('_yoast_wpseo_title') && $paged == 1) :
		$titleSEO = str_replace('%%paged%%', ' WomenNow', get_field('_yoast_wpseo_title'));
	elseif (get_field('_yoast_wpseo_title') && $paged > 1) :
		$titleSEO = str_replace('%%paged%%', 'Página ' . $paged . ' - WomenNow', get_field('_yoast_wpseo_title'));
		$urlPage = get_permalink(0, false) . 'page/' . $paged . '/';
	else :
		$titleSEO = wp_title('-', false, 'RIGHT');
	endif;

	$titleSEO_Home = get_field('_yoast_wpseo_title', $homePage->ID);
	$metaDesc = get_field('_yoast_wpseo_metadesc');
	$metaDesc_HOME = get_field('_yoast_wpseo_metadesc', $homePage->ID);
	$datePage = $post->post_date ? $post->post_date : $post->post_modified;
	$dateModified = $post->post_modified ? $post->post_modified : $datePage;
	$datePage = str_replace(' ', 'T', $datePage) . '+02:00';
	$postImg = get_posts(array('p' => get_post_thumbnail_id($post->ID), 'post_type' => 'attachment'));
	$leyendaImg = $postImg[0]->post_excerpt != '' ? $postImg[0]->post_excerpt : get_field('pie_foto', $post->ID);

	$posItem = 0;
	$ItemListElement = '';
	if ($seccion == 'home') {
		//Slide
		if (have_rows('slide', $homePage->ID)) : while (have_rows('slide')) : the_row();
				$posItem++;
				$namePost = preg_replace('/"/', "'", get_sub_field('grupo')['titulo']);
				$namePost = preg_replace('/“/', "'", $namePost);
				$ItemListElement .= $posItem == 1 ? '{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . get_sub_field('grupo2')['enlace'] . '"}' : ',{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . get_sub_field('grupo2')['enlace'] . '"}';
			endwhile;
			wp_reset_postdata();
		endif;
	} else if ($seccion == 'te-interesa' || $seccion == 'podcasts' || $seccion == 'videos' || $seccion == 'noticia' || $seccion == 'inversiones-finanzas') {
		switch ($post->post_name) {
			case 'te-interesa':
				$nameSEO = 'Te interesa';
				$postsSEO = query_posts(array('post_type' => 'noticia',  'posts_per_page' => 5, 'paged' => get_query_var('paged')));
				//$catDesc = category_description( $catID )?category_description( $catID[0] ):get_field('_yoast_wpseo_metadesc');
				break;
			case 'inversiones-finanzas':
				$nameSEO = 'Money';
				$postsSEO = query_posts(array('post_type' => 'noticia',  'posts_per_page' => 5, 'cat' => (get_cat_ID('Money & Career')), 'paged' => get_query_var('paged')));
				//$postsSEO = query_posts(array('post_type' => 'noticia',  'posts_per_page' => 5, 'cat' => '38', 'paged' => get_query_var('paged')));
				//$pageDesc = category_description(38)?category_description(38):get_field('_yoast_wpseo_metadesc');
				break;
			case 'videos':
				$nameSEO = 'Videos';
				$postsSEO = query_posts(array('post_type' => 'videocpt', 'posts_per_page' => 6, 'paged' => get_query_var('paged'), 'post__not_in' => array(get_field('video_destacado')[0]->ID)));
				break;
			case 'podcasts':
				$nameSEO = 'Podcasts';
				$postsSEO = query_posts(array());
				//$pageDesc = category_description( $catID )?category_description( $catID[0] ):get_field('_yoast_wpseo_metadesc');
				break;
		}

		if (is_archive() || is_page()) {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}

		//Slide de la página de Videos
		if ($seccion == 'videos') {
			$videosPage = get_page_by_title('Videos');
			$videosList = get_field('video_destacado', $videosPage->ID);
			foreach ($videosList as $video) : setup_postdata($video);
				$posItem++;
				$namePost = preg_replace('/"/', "'", $video->post_title);
				$namePost = preg_replace('/“/', "'", $namePost);
				$datePost = $video->post_modified;
				$dateModified_Video = $datePost > $dateModified ? $datePost : $dateModified;
				$ItemListElement .= $posItem == 1 ? '{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . get_permalink($video->ID) . '"}' : ',{"@type":"ListItem","position":' . $posItem . ',"name":"' . $video->post_title . '","url":"' . get_permalink($video->ID) . '"}';
			endforeach;
			$dateModified = $dateModified_Video > $dateModified ? str_replace(' ', 'T', $dateModified_Video) . '+02:00' : $dateModified;
		}

		//podcast de la Página de Podcasts
		if ($seccion == 'podcasts') {
			$podcastsPage = get_page_by_title('Podcasts');
			$podcastsList = get_field('listado_podcasts', $podcastsPage->ID);
			foreach ($podcastsList as $podcast) {
				$posItem++;
				$namePost = preg_replace('/"/', "'", $podcast['titulo_podcast']);
				$namePost = preg_replace('/“/', "'", $namePost);
				$datePost = $podcast->post_modified;
				$dateModified_Podcast = $datePost > $dateModified ? $datePost : $dateModified;
				$ItemListElement .= $posItem == 1 ? '{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . $podcast['url_podcast'] . '"}' : ',{"@type":"ListItem","position":' . $posItem . ',"name":"' . $podcast['titulo_podcast'] . '","url":"' . $podcast['url_podcast'] . '"}';
			}
			$dateModified = $dateModified_Podcast > $dateModified ? str_replace(' ', 'T', $dateModified_Podcast) . '+02:00' : $dateModified;
		}

		if (have_posts()) : if ((count($postsSEO)) > 0) :
				//while ( have_posts() ) : the_post();
				foreach ($postsSEO as $key => $postSEO) : setup_postdata($postSEO);
					if ($postSEO->post_title != 'Home') {
						$posItem++;
						$namePost = preg_replace('/"/', "'", $postSEO->post_title);
						$namePost = preg_replace('/“/', "'", $namePost);
						$datePost = $postSEO->post_modified;
						$dateModified = $datePost > $dateModified ? $datePost : $dateModified;
						$ItemListElement .= $posItem == 1 ? '{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . home_url('/noticias/') . $postSEO->post_name . '"}' : ',{"@type":"ListItem","position":' . $posItem . ',"name":"' . $postSEO->post_title . '","url":"' . home_url('/noticias/') . $postSEO->post_name . '"}';
					}
				endforeach;
				wp_reset_postdata();
				//endwhile;
				$dateModified = str_replace(' ', 'T', $dateModified) . '+02:00';
			endif;
		endif;
		wp_reset_postdata();
	}

	//Modulos: Te interesa, Contenido Especial (sino es publicidad), Actualidad, No te pierdas
	$current_ID = $GLOBALS['current_id'];
	$current_type = $GLOBALS['current_type'];

	if (have_rows('modulos', $homePage->ID)) :
		if ($seccion != 'podcasts') {
			while (have_rows('modulos', $homePage->ID)) : the_row();
				$pagina = (get_sub_field('mostrar')) ? get_sub_field('mostrar') : array();
				$tipo = (get_sub_field('mostrar_int')) ? get_sub_field('mostrar_int') : array();
				if (in_array($current_ID, $pagina) || in_array($current_type, $tipo)) :
					$postsSEO = get_sub_field('contenido');
					if ($postsSEO) :
						foreach ($postsSEO as $key => $postSEO) : setup_postdata($postSEO);
							$namePost = preg_replace('/"/', "'", $postSEO->post_title);
							$namePost = preg_replace('/“/', "'", $namePost);
							$posItem++;
							$ItemListElement .= $posItem == 1 ? '{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . get_permalink($postSEO->ID) . '"}' : ',{"@type":"ListItem","position":' . $posItem . ',"name":"' . $namePost . '","url":"' . get_permalink($postSEO->ID) . '"}';
						endforeach;
						wp_reset_postdata();
					endif;
				endif;
			endwhile;
			wp_reset_postdata();
		}
	endif;

	if ($seccion == 'home') {

		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"https://schema.org","@type":"WebSite","@id":"' . esc_url(get_home_url()) . '#website","url":"' . esc_url(get_home_url()) . '","name":"' . $titleSEO_Home . '","description":"' . $metaDesc_HOME . '","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"' . esc_url(get_home_url()) . '?s={search_term_string}"},"query-input":"required name=search_term_string"},"inLanguage":"es"}';
		$script .= '</script>
		';
	} else if (((!is_single()) && ($paged == 1) && ($seccion == 'te-interesa' || $seccion == 'podcasts' || $seccion == 'videos' || $seccion == 'noticia' || $seccion == 'inversiones-finanzas'))) {

		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"https://schema.org","@type":"WebPage","name":"' . $titleSEO . '","description":"' . $metaDesc . '","url":"' . $urlPage . '","publisher":{"@type":"NewsMediaOrganization","name":"WomenNow"},"datePublished":"' . $datePage . '","dateModified":"' . $dateModified . '","image":{"@type":"ImageObject","url":"' . esc_url(get_template_directory_uri()) . '/assets/img/logowomennow260x106.svg","height":"260","width":"106"}}';
		$script .= '</script>
		';
	} else if ($post->post_type == 'noticia') {
		$dateModified = str_replace(' ', 'T', $dateModified) . '+02:00';
		$imgPpal = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
	}

	if ((is_single()) || (($paged == 1) && ($seccion == 'home' || $seccion == 'te-interesa' || $seccion == 'podcasts' || $seccion == 'videos' || $seccion == 'noticia' || $seccion == 'inversiones-finanzas'))) {
		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"https://schema.org","@type":"NewsMediaOrganization","name":"' . $titleSEO_Home . '","url":"' . esc_url(home_url('/')) . '","sameAs":["https://www.facebook.com/WomenNowSP/","https://twitter.com/WomenNOWSP"],"description":"' . $metaDesc_HOME . '","logo":{"@type":"ImageObject","url":"' . esc_url(get_template_directory_uri()) . '/assets/img/logowomennow260x106.svg","width":260,"height":106},"location":{"@type":"Place","name":"WomenNow","hasMap":"https://www.google.com/maps/place/Josefa+Valc%C3%A1rcel+40+bis/@40.4483538,-3.6423305,19z/data=!3m1!4b1!4m8!1m2!2m1!1sWomenNow!3m4!1s0xd422f8a1113eca5:0x2412f5acc8ce8093!8m2!3d40.4484761!4d-3.6411714","geo":{"@type":"GeoCoordinates","latitude":"40.4483538","longitude":"-3.6423305"},"address":{"@type":"PostalAddress","streetAddress":"C. de Josefa Valcárcel","addressLocality":"40 bis","addressRegion":"Madrid","postalCode":"28027","addressCountry":"ES"}}}';
		$script .= '</script>
		';

		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"http://schema.org","@type":"SiteNavigationElement","hasPart":[' . schema_menu_items() . ']}';
		$script .= '</script>
		';
	}

	if (($paged == 1) && ($seccion == 'te-interesa' || $seccion == 'podcasts' || $seccion == 'videos' || $seccion == 'noticia' || $seccion == 'inversiones-finanzas')) {
		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"name":"WomenNow","@id":"' . esc_url(get_home_url()) . '"}},{"@type":"ListItem","position":2,"item":{"name":"' . $nameSEO . '"}}]}';
		$script .= '</script>
		';
	}

	if (($paged == 1) && ($seccion == 'home' || $seccion == 'te-interesa' || $seccion == 'podcasts' || $seccion == 'videos' || $seccion == 'noticia' || $seccion == 'inversiones-finanzas')) {
		$script .= '<script type="application/ld+json">';
		$script .= '{"@context":"https://schema.org","@type":"ItemList","url":"' . $urlPage . '","name":"' . $titleSEO . '"';
		$script .= $ItemListElement != '' ? ',"ItemListElement":[' . $ItemListElement . ']' : '';
		$script .= '}';
		$script .= '</script>
		';
	}

	if ($post->post_type == 'noticia') {
		$script .= '<!-- meta newsarticle -->';
		$script .= newsarticle_schema($post);
		$script .= '<!-- meta newsarticle-->
		';
	}

	if ($post->post_type == 'videocpt') {
		$script .= '<!-- meta videoobject -->';
		$script .= videoobject_schema($post);
		$script .= '<!-- meta videoobject-->
		';
	}

	if ($post->post_type == 'audio') {
		$script .= '<!-- meta audioobject -->';
		$script .= audioobject_schema($post);
		$script .= '<!-- meta audioobject-->
		';
	}

	if ($post->post_name == 'una_habitacion_propia' || $post->post_name == 'santander_womennow') {
		$script .= '<!-- meta podcastseries -->';
		$script .= podcastseries_schema();
		$script .= '<!-- meta podcastseries-->
		';
	}

	wp_reset_postdata();
	wp_reset_query();
	return ($script);
}

function remove_page_from_query_string($query_string)
{
	if ($query_string['name'] == 'page' && isset($query_string['page'])) {
		unset($query_string['name']);
		list($delim, $page_index) = split('/', $query_string['page']);
		$query_string['paged'] = $page_index;
	}
	return $query_string;
}
add_filter('request', 'remove_page_from_query_string');

function fix_category_pagination($qs)
{
	if (isset($qs['category_name']) && isset($qs['paged'])) {
		$qs['post_type'] = get_post_types($args = array(
			'public'   => true,
			'_builtin' => false
		));
		array_push($qs['post_type'], 'post');
	}
	return $qs;
}
add_filter('request', 'fix_category_pagination');


add_action('save_post', 'rmcache_clean', 99);
function rmcache_clean($post_id)
{
	if ($_ENV["ENVIRON"] == "PRO") {
		$urlCache = 'http://rmcache.srv.vocento.in/akamai/invalidate/url';
		$urlCacheAMP = 'http://rmcache.srv.vocento.in/amp/invalidate/url';
		if ($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES") {
			$postURL = str_replace('https://prems-mhsites.srv.vocento.in/womennow/', 'https://pre-www.womennow.es/', get_permalink());
		} else {
			$postURL = str_replace('https://ms-mhsites.srv.vocento.in/womennow/', 'https://www.womennow.es/', get_permalink());
		}
		curlCall($urlCache, $postURL, 'urls');
		curlCall($urlCacheAMP, $postURL . 'amp', 'url');
	}
}

function curlCall($urlAPI, $postURL, $param)
{

	$ch = curl_init($urlAPI);
	$payload = json_encode(array($param => $postURL));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_exec($ch);
	curl_close($ch);
}

//Embeber post de instagram
function shortcode_add_instagram($insta_url)
{
	$blockInstagram =
		'<blockquote title="cualquier titulo Instagram" class="instagram-media" data-instgrm-permalink="' . $insta_url[0] . '" data-instgrm-version="13" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
			<div style="padding:16px;"> 
				<a href="' . $insta_url[0] . '" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank"> 
					<div style=" display: flex; flex-direction: row; align-items: center;"> 
						<div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div> 
						<div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;"> 
							<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> 
							<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div>
						</div>
					</div>
					<div style="padding: 19% 0;"></div>
					<div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
						<svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g transform="translate(-511.000000, -20.000000)" fill="#000000">
									<g>
										<path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
										</path>
									</g>
								</g>
							</g>
						</svg>
					</div>
					<div style="padding-top: 8px;"> 
						<div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;"> Ver esta publicación en Instagram</div>
					</div>
					<div style="padding: 12.5% 0;"></div>
					<div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
						<div> 
							<div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> 
							<div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> 
							<div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div>
						</div>
						<div style="margin-left: 8px;"> 
							<div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> 
							<div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div>
						</div>
						<div style="margin-left: auto;"> 
							<div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> 
							<div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> 
							<div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div>
						</div>
					</div> 
					<div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;"> 
						<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;"></div> 
						<div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;"></div>
					</div>
				</a>
				<p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
					<a href="' . $insta_url[0] . '" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;" target="_blank">Publicación compartida
					</a>
				</p>
			</div>
		</blockquote> 
	
		<script async src="//www.instagram.com/embed.js"></script>';

	return $blockInstagram;
}

add_shortcode('addinstagram', 'shortcode_add_instagram');


/**Embeber Video de Youtube
 * Función que se encarga de añadir el código necesario para el embeber Video de Youtube, mediante un shortcode y diferentes atributos que se encargan de cambiar algunos atributos del reproductor
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				valor de la url del video a embeber
 *  			$atributos['title']				titulo del video a embeber
 *  			$atributos['loop']				valor 1 el reproductor reproduce el video inicial una y otra vez - debe ir acompañado del atributo de playlist
 *  			$atributos['playlist']			lista separada por comas de ID de videos a mostrar al finalizar el video inicial. Si se desea solo un video se deber enviar el id del mismo video o no informar éste atributo
 *  			$atributos['controls']			indica si se muestran o no los controles del reproductor de video. valor 0 no se muestra. 1 y 2 se muestran, 2 mejora el rendimiento en Iframe
 *  			$atributos['showinfo']			si se establece el valor del parámetro en 0, el reproductor no muestra información como el título del video o el usuario que lo subió antes de comenzar la reproducción.
 *  			$atributos['color']				especifica el color que se utiliza en la barra de progreso del reproductor para destacar la cantidad de video ya vista por el usuario.
 *  			$atributos['modestbranding']	permite usar un reproductor de YouTube sin que se muestre un logotipo de YouTube. Establece el valor de parámetro en 1 para evitar que se muestre el logotipo de YouTube en la barra de control. Ten en cuenta que se seguirá mostrando una etiqueta pequeña de YouTube en la esquina superior derecha de un video en pausa cuando el cursor del mouse del usuario se desplace sobre el reproductor. Configurar el parámetro color en white desactiva la opción modestbranding.	
 * @return string del código necesario para embeber el Video de Youtube
 */
function shortcode_add_youtube($atributos)
{

	$atributos = shortcode_atts(array(
		'url' 		=> 'https://www.youtube.com/embed/Yl81OLIp2QI',
		'title' 	=> 'Youtube WON',
		'loop' 		=> 1,		//valor 1 el reproductor reproduce el video inicial una y otra vez - debe ir acompañado del atributo de playlist
		'playlist' 	=> '', 		//lista separada por comas de ID de videos a mostrar al finalizar el video inicial. Si se desea solo un video se deber enviar el id del mismo video o no informar éste atributo
		'controls' 	=> 2,		//indica si se muestran o no los controles del reproductor de video. valor 0 no se muestra. 1 y 2 se muestran, 2 mejora el rendimiento en Iframe
		'showinfo' 	=> 0,		//si se establece el valor del parámetro en 0, el reproductor no muestra información como el título del video o el usuario que lo subió antes de comenzar la reproducción.
		'color'		=> 'white',	//especifica el color que se utiliza en la barra de progreso del reproductor para destacar la cantidad de video ya vista por el usuario
		'modestbranding' => 1	//permite usar un reproductor de YouTube sin que se muestre un logotipo de YouTube. Establece el valor de parámetro en 1 para evitar que se muestre el logotipo de YouTube en la barra de control. Ten en cuenta que se seguirá mostrando una etiqueta pequeña de YouTube en la esquina superior derecha de un video en pausa cuando el cursor del mouse del usuario se desplace sobre el reproductor. Configurar el parámetro color en white desactiva la opción modestbranding. 
	), $atributos);

	$atributos['playlist'] = $atributos['playlist'] != '' ? $atributos['playlist'] : preg_replace('/[^z-z]*(embed\/\b)/i', '', $atributos['url']);

	$video_yotube = '<section>
						<iframe width="100%" height="410" src="' . $atributos['url'] . '?color=' . $atributos['color'] . '&controls=' . $atributos['controls'] . '&showinfo=' . $atributos['showinfo'] . '&loop=1&playlist=' . $atributos['playlist'] . '"&modestbranding=' . $atributos['modestbranding'] . '" title="' . $atributos['title'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</section>';

	return $video_yotube;
}

add_shortcode('addyoutube', 'shortcode_add_youtube');

//Embeber post de twitter
function shortcode_add_twitter($atributos)
{
	$url = "https://publish.twitter.com/oembed?url=" . $atributos[0];
	try {
		$json = file_get_contents($url, 0, stream_context_create(["https" => ["timeout" => 10]]));
		if (false === $json) {
			$tweet = '';
		} else {
			$data = json_decode($json, true);
			$tweet =  $data['html'];
		}
	} catch (Exception $e) {
		$tweet = '';
	}


	return $tweet;
}

add_shortcode('addtwitter', 'shortcode_add_twitter');


/*Embeber post de tiktok
 * Función que se encarga de añadir el código necesario para el embeber Post de TikTok, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				Valor de la url del post a embeber. Valor obligatorio a informar
 *  			$atributos['width']			    Ancho de la publicación. Mínimo de 325 píxeles y máximo de 605. Déjalo en blanco si quieres aplicar un ancho variable. Valor por defecto 500px
 * @return string del código necesario para embeber el Post de TikTok
 * Ejemplo shortcode: [addtiktok url="https://www.tiktok.com/@latoneira/video/7063920006995791109" width=500]
 */
function shortcode_add_tiktok($atributos)
{
	$urlPost = $atributos['url'];
	$idPost = explode('/', $urlPost);
	$i = count($idPost) - 1;
	$idPost = $idPost[$i];
	$widthPost = $atributos['width'] ? $atributos['width'] : 605;
	$tiktok = '';

	if ($urlPost) {
		$tiktok .= '<blockquote title="Tiktok WON" class="tiktok-embed" cite="' . $urlPost . '" data-video-id="' . $idPost . '" style="max-width: ' . $widthPost . 'px;min-width: 325px;" >';
		$tiktok .= '<section>';
		$tiktok .= '</section>';
		$tiktok .= '</blockquote>';
		$tiktok .= '<script async src="https://www.tiktok.com/embed.js"></script>';
	}
	return $tiktok;
}

add_shortcode('addtiktok', 'shortcode_add_tiktok');


/**Embeber Post de Facebook
 * Función que se encarga de añadir el código necesario para el embeber Post de Facebook, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['url']				Valor de la url del post a embeber. Valor obligatorio a informar
 *  			$atributos['datalazy']			True significa que se utilizará el mecanismo de carga diferida del navegador al establecer el atributo iframe en loading="lazy". Valor por defecto true
 *  			$atributos['datawidth']			Ancho de la publicación. Mínimo de 350 píxeles y máximo de 750. Déjalo en blanco si quieres aplicar un ancho variable. Valor por defecto 500px
 *  			$atributos['datashowtext']		Se aplica a las publicaciones con foto. Establécelo en true para incluir el texto de la publicación de Facebook, si lo hay. Valor por defecto true.
 * @return string del código necesario para embeber el Post de Facebook
 * Ejemplo shortcode: [addfacebook url="https://www.facebook.com/welifefestival/posts/333712615368647" datashowtext=false]
 */


function shortcode_add_facebook($atributos)
{

	$urlPost = $atributos['url'];
	$dataLazy = $atributos['datalazy'];
	$dataLazy = $dataLazy ? $dataLazy : true;
	$dataWidth = $atributos['datawidth'];
	$dataWidth = $dataWidth ? $dataWidth : 750;
	$datashowText = $atributos['datashowtext'];
	$datashowText = $datashowText ? $datashowText : true;

	$fb_insert = '';

	if ($urlPost) {

		$fb_insert = '<div class="shortcode-facebook">';
		$fb_insert .= '<div id="fb-root"></div>';
		$fb_insert .= '<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v13.0" nonce="o62H2nYO"></script>';
		$fb_insert .= '<div class="fb-post" data-href="' . $urlPost . '" data-width="' . $dataWidth . '" data-show-text="' . $datashowText . '">';
		$fb_insert .= '<blockquote title="Facebook WON" cite="https://www.facebook.com/welifefestival/posts/pfbid02ZRjZCqV7ZHTcndG12Q6NwANQHm5bRveBPEE1m9wfnDhbfAomMS6BfYonJkqneLwul" class="fb-xfbml-parse-ignore">';
		$fb_insert .= '</blockquote></div>';
		$fb_insert .= '</div>';
	}

	return $fb_insert;
}

add_shortcode('addfacebook', 'shortcode_add_facebook');


/*Embeber video de brightcove
 * Función que se encarga de añadir el código necesario para el embeber Video de brightcove, mediante un shortcode y diferentes atributos que se encargan de cambiar algunas funcionalidad
 * @param array $atributos 						Array con cada uno de los atributos utilizados en el shortcode
 * 				$atributos['id']				Valor del id del video a embeber. Valor obligatorio a informar
 * 				$atributos['autoplay']			Opción para tener activo o no el autoplay del video. Valor opcional
 * @return string del código necesario para embeber el Video de brightcove
 * Ejemplo shortcode: [addbrightcove id="6286777721001" autoplay="false"]
 */
function shortcode_add_brightcove($atributos)
{
	$idVideo = $atributos['id'];
	$autoplay = $atributos['autoplay'] ? $atributos['autoplay'] : 'true';

	$brightcove = '';

	$brightcove = '	<div style="display: block; position: relative; margin-bottom:20px">
						<div style="padding-top: 56.25%;">
							<iframe src="//players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId=' . $idVideo . '&autoplay=' . $autoplay . '" allowfullscreen webkitallowfullscreen mozallowfullscreen autoplay style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;" title="Video ' . $idVideo . '">
							</iframe>
						</div>
					</div>
					<div style="display:none">
						<a href="https://players.brightcove.net/6019218709001/rnKhcG3nS_default/index.html?videoId=' . $idVideo . '&autoplay=' . $autoplay . '"></a>
						en 
						<a href="https://www.womennow.es/videos" title="Ver Vídeos">Vídeos</a>
					</div>';

	return $brightcove;
}

add_shortcode('addbrightcove', 'shortcode_add_brightcove');


function custom_robots($robots)
{
	$protocolo = ($_ENV["ENVIRON"] != 'PRE') ? 'http://' : 'https://';
	$urlSitemap = $_ENV["ENVIRON"] != 'PRO' ? $protocolo . $_SERVER['HTTP_HOST'] . '/sitemap_index.xml' : 'https://www.womennow.es/es/sitemap_index.xml';
	$blog_id = $GLOBALS['current_blog']->blog_id;
	if ($blog_id !== (1))
		$robots = '
User-agent: *
Disallow: /condiciones-de-uso/
Disallow: /aviso-legal/
Disallow: /newsletter/
Disallow: /search/*
Disallow: /cgi-bin/
Disallow: /wp-admin/
Disallow: /wp-content/plugins/
Disallow: /wp-content/cache/
Disallow: /wp-content/themes/
Disallow: */aggregate* 
Sitemap: ' . $urlSitemap . '

User-agent: Mediapartners-Google*
Allow: /

User-agent: Googlebot-Image
Allow: /wp-content/uploads/

User-agent: Twitterbot
Allow: /wp-content/uploads/*
Disallow: *
';
	return $robots;
}
add_filter('robots_txt', 'custom_robots');

//rewrites
function add_rewrites()
{
	//robots.txt
	add_rewrite_rule(
		'^robots.txt$',
		'index.php?robots=1',
		'top'
	);
}

add_action('init', 'add_rewrites');

function add_specific_menu_atts($atts, $item, $args)
{
	if ($args->theme_location == 'menu-legales') {
		$atts['rel'] = 'nofollow';
	}

	return $atts;
}
add_filter('nav_menu_link_attributes', 'add_specific_menu_atts', 10, 3);

# -*- coding: utf-8 -*-
/**
 * Plugin Name: T5 Add page number to title
 * Description: Adds <code> | Page $number</code> to the page title.
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */

if (!function_exists('t5_add_page_number')) {
	function t5_add_page_number($s)
	{
		global $wp_query;
		//if(is_page() && (sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='te-interesa' || sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='inversiones-finanzas'  || sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='videos') ) {
		if (is_page()) {
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			query_posts(array('post_type' => 'noticia'));
			$totalPage = floor(($wp_query->found_posts) / 5);
			!empty($page) && 1 < $page && $paged = $page;
			$paged > 1 && $s .= ' - ' . sprintf(__('Página: %s de ' . $totalPage . ' - WomenNow'), $paged);
		}
		wp_reset_query();
		return $s;
	}

	add_filter('wp_title', 't5_add_page_number', 100, 1);
	add_filter('wpseo_metadesc', 't5_add_page_number', 100, 1);
}

add_filter('wpseo_title', 'filter_title');
function filter_title($title)
{
	global $wp_query;
	//if(is_page() && (sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='te-interesa' || sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='inversiones-finanzas' || sanitize_post( $GLOBALS['wp_the_query']->get_queried_object()->post_name )=='videos') ) {
	if (is_page()) {
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		query_posts(array('post_type' => 'noticia'));
		$totalPage = floor(($wp_query->found_posts) / 5);
		!empty($page) && 1 < $page && $paged = $page;

		$paged > 1 && $title .= ' - ' . sprintf(__('Página: %s'), $paged) . ' de ' . $totalPage . ' - WomenNow';
	}
	wp_reset_query();
	return $title;
}

function schema_menu_items()
{
	$script = '';
	$menuID = get_nav_menu_locations()['menu-principal'];
	foreach (wp_get_nav_menu_items($menuID) as $key => $navItem) {
		if ($navItem->url != '' && $navItem->url != '#') {
			if ($key >= 1) $script .= ',';
			$script .= '{"name":"' . $navItem->title . '","url":"' . $navItem->url . '"}';
		}
	}
	return $script;
}


add_image_size('won-principal', 617, 432, true);
add_image_size('won-articulo', 617, 432, true);
add_image_size('won-galeria', 617, 432, true);


add_filter('wpseo_canonical', '__return_false');
add_filter('wpseo_opengraph_url', '__return_false');
add_filter('wpseo_json_ld_output', '__return_false');

function change_content_urls($postid)
{
	$meta = get_post_meta($postid, "_xmlsf_image_featured", true);
	if (!empty($meta)) {
		$re = '/^\/wp-content/m';
		$subst = 'https://www.womennow.es/wp-content';
		$meta["loc"] = preg_replace($re, $subst, $meta["loc"]);
		update_post_meta($postid, '_xmlsf_image_featured', $meta);
	}
}

add_action('update_post', 'change_content_urls', 10);
add_action('save_post', 'change_content_urls', 10);


//Se comenta por si fuera necesario ejecutarlo de nuevo
// if(is_admin()){

// 	$podcast = get_posts(array("post_type"=>"audio",'numberposts' => -1,'meta_key' => '_xmlsf_image_featured'));
// 	foreach($podcast as $post){
// 		change_content_urls($post->ID);
// 	}

// 	$video = get_posts(array("post_type"=>"videocpt",'numberposts' => -1,'meta_key' => '_xmlsf_image_featured'));
// 	foreach($video as $post){
// 		change_content_urls($post->ID);
// 	}

// 	$noticia = get_posts(array("post_type"=>"noticia",'numberposts' => -1,'meta_key' => '_xmlsf_image_featured'));
// 	foreach($noticia as $post){
// 		change_content_urls($post->ID);
// 	}

// }

function get_srcset($imageId, $imageUrl = null)
{

	if (!$imageId && !$imageUrl) return ('src="' . wp_get_attachment_url($imageId) . '"');
	elseif (!$imageId && $imageUrl) return ('src="' . $imageUrl . '"');

	$srcset = wp_get_attachment_image_srcset($imageId);
	$imgsUrls = explode(", ", $srcset);
	$imgsAbsolutaUrl = '';
	$i = 0;
	foreach ($imgsUrls as $imgUrl) {
		$i++;
		$urlExplode = explode("wp-content", $imgUrl);
		$imgsAbsolutaUrl .= $i == 1 ? get_site_url() . '/wp-content' . $urlExplode[1] : ', ' . get_site_url() . '/wp-content' . $urlExplode[1];
		//$imgsAbsolutaUrl .= (preg_replace('/[a-zA-Z0-9_](?=wp-content)/',get_home_url().'/', $imgUrl)).', ';
	}

	//$srcsetImgs = 'srcset="'.$imgsAbsolutaUrl.'" sizes="(max-width: 350px) 300px, (max-width: 600px) 768px, (max-width: 750px) 800px, (max-width: 1000px) 1024px, 1200px"';
	$srcsetImgs = 'data-src="' . $imgsAbsolutaUrl . '" srcset="' . $imgsAbsolutaUrl . '"';

	return ($srcsetImgs);
}

function stripQuotes($string)
{
	return str_replace('"', "", $string);
}

add_action('admin_init', function () {
	// Redirect any user trying to access comments page
	global $pagenow;

	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}

	// Remove comments metabox from dashboard
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	// Disable support for comments and trackbacks in post types
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
	remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
});
