<?php

// Create Shortcode modulohtml
// Use the shortcode: [modulohtml htmlposttype=""]
function create_modulohtml_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'htmlposttype' => '',
		),
		$atts,
		'modulohtml'
	);
	// Attributes in var
	$htmlposttype = $atts['htmlposttype'];
	$post = get_post(intval($htmlposttype));

	return $post->post_content;
}
add_shortcode( 'modulohtml', 'create_modulohtml_shortcode' );

// Create Modulo HTML element for Visual Composer
add_action( 'vc_before_init', 'modulohtml_integrateWithVC' );
function modulohtml_integrateWithVC() {

    $arrayCustomPost = [];
    if(is_admin()){
        $posts = get_posts([
            'post_type' => 'modulo_hmtl',
            'post_status' => 'publish',
            'numberposts' => -1
            // 'order'    => 'ASC'
          ]);
        foreach ($posts as $post) {
            $arrayCustomPost[$post->post_title] = $post->ID;
        }
    }

	vc_map( array(
		'name' => __( 'Modulo HTML', 'textdomain' ),
		'base' => 'modulohtml',
		'show_settings_on_create' => true,
		'category' => __( 'Content', 'textdomain'),
		'params' => array(
			array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Titulo', 'textdomain'),
                'param_name' => 'tituloseccion',
            ),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'admin_label' => false,
				'heading' => __( '', 'textdomain' ),
				'param_name' => 'htmlposttype',
                "value" => $arrayCustomPost,
			),
		)
	) );
}

add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {
    wp_enqueue_style( 'admin_css_htmlbloque', get_template_directory_uri() . '/addons_new/css/style.css', false, '1.0.0' );
}