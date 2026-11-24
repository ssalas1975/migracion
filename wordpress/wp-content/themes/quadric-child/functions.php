<?php

/***  Enqueue child theme stylesheet ***/

function wp_edge_quark_child_enqueue_style() {
	wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
	wp_enqueue_style( 'childstyle' );
}
add_action( 'wp_enqueue_scripts', 'wp_edge_quark_child_enqueue_style', 11);

function wp_edge_quark_child_enqueue_js() {
	// wp_dequeue_script( 'edgtf_quadric_modules' );
	wp_register_script( 'childscript', get_stylesheet_directory_uri() . '/custom-scripts.js' , '', '', true);
	wp_enqueue_script( 'childscript' );
}
add_action( 'wp_enqueue_scripts', 'wp_edge_quark_child_enqueue_js', 11);


function exclude_widget_categories($args){
	$exclude = "5,6,7,8,9,10,11,12,14,16,19,20,23,24,25,26,66,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159"; // The IDs of the excluding categories
	$args["exclude"] = $exclude;
	return $args;
}
add_filter("widget_categories_args","exclude_widget_categories");

function edgtf_quadric_read_more_button($option = '', $class = '') {
	if($option != '') {
		$show_read_more_button = edgtf_quadric_options()->getOptionValue($option) == 'yes';
	}else {
		$show_read_more_button = 'yes';
	}
	if($show_read_more_button && !edgtf_quadric_post_has_read_more() && !post_password_required()) {
		echo edgtf_quadric_get_button_html(array(
			'size'         => 'small',
			'link'         => get_the_permalink(),
			'text'         => esc_html__('Ver más', 'quadric'),
			'custom_class' => $class
		));
	}
}


/**
*
* cargar css para premios genio
*/

/*function custom_css_premios_genio() {
	$blog_id = get_current_blog_id();
	if( 9 == $blog_id )
	{
		wp_register_style( 'custom-style-premiosgenio', get_stylesheet_directory_uri() .'/style-premiosgenio.css');
		wp_enqueue_style( 'custom-style-premiosgenio',130 );


	}
}*/
/*
add_action( 'wp_enqueue_scripts', 'custom_css_premios_genio' );
*/

function custom_css_premios_genio() {
	$blog_id = get_current_blog_id();
	if( 6 == $blog_id )
	{
		wp_enqueue_style( 'tu-estilo-id', get_stylesheet_directory_uri() .'/style-premiosgenio.css' );
	}
};
add_action( 'get_footer', 'custom_css_premios_genio' );




/* permitir subir archivos con extensión svg*/
add_filter( 'upload_mimes', 'custom_upload_mimes' );
function custom_upload_mimes( $existing_mimes = array() ) {
    // Add the file extension to the array
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['svgz'] = 'image/svg+xml';
    return $existing_mimes;
}




function custom_slider_premiosgenio(){
	wp_enqueue_style( 'custom-slide', get_stylesheet_directory_uri() .'/assets/css/slide.css' );
	wp_enqueue_style( 'custom-slide2', get_stylesheet_directory_uri() .'/assets/css/stile.css' );
	wp_enqueue_script('custom-newscript', get_stylesheet_directory_uri() .'/assets/js/swiper.js' );
	wp_enqueue_script('custom-newscript2', get_stylesheet_directory_uri() .'/assets/js/swiper-set.js' );
}
add_action( 'get_footer', 'custom_slider_premiosgenio' );



add_filter( 'map_meta_cap', 'vocento_users_unfiltered', 1, 3 );
function vocento_users_unfiltered( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) )
		$caps = array( 'unfiltered_html' );
	return $caps;
}