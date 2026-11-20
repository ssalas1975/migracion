<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
		$nectar_theme_version = nectar_get_theme_version();
		wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
		
    if ( is_rtl() ) {
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
		}
}

function file_get_contents_curl($atts) {
	$default = array(
			'link' => '#',
	);
	$a = shortcode_atts($default, $atts);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $a['link']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

	$data = curl_exec($ch);
	curl_close($ch);

	$re = '/<p>\n*<\/p>/m';
	$subst = "";
	$data = preg_replace($re, $subst, $data);

	return $data;





}
add_shortcode('googleform', 'file_get_contents_curl');