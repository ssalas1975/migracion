<?php

/**
 * adon para incluir codigo javascript en la cabecera y en el cuerpo
 */

 // Create Shortcode javascriptelement
// Use the shortcode: [javascriptelement cod_cabecera="" cod_cuerpo=""]
// function create_javascriptelement_shortcode($atts) {
// 	// Attributes
// 	$atts = shortcode_atts(
// 		array(
// 			'id_field' => '',
// 			'cod_cabecera' => '',
// 			'cod_cuerpo' => '',
// 		),
// 		$atts,
// 		'javascriptelement'
// 	);
// 	// Attributes in var
// 	$id_field = $atts['id_field'];

// }
// add_shortcode( 'javascriptelement', 'create_javascriptelement_shortcode' );

// Create javascript_element element for Visual Composer
// Create javascript_element element for Visual Composer
add_action( 'vc_before_init', 'javascriptelement_general' );
function javascriptelement_general() {
	vc_map( array(
		'name' => __( 'javascript_element_general', 'textdomain' ),
		'description' => __( 'Incrusta código javascript en la cabecera', '' ),
		'base' => 'javascriptelementgeneral',
		'show_settings_on_create' => false,
		'category' => __( 'Content', 'textdomain'),
		'params' => array(
			array(
				'type' => 'textarea_raw_html',
				'holder' => 'div',
				'class' => '',
				'admin_label' => false,
				'heading' => __( 'Código Header', 'textdomain' ),
				'param_name' => 'con_header',
			),
			array(
				'type' => 'textarea_raw_html',
				'holder' => 'div',
				'class' => '',
				'admin_label' => false,
				'heading' => __( 'Código Body', 'textdomain' ),
				'param_name' => 'con_body',
			),
		)
	) );
}



add_action( 'save_post', 'save_metas' ,99,2);
function save_metas( $post_id,$post )
{
    if(!empty($post->post_content)){
		$contenido=$post->post_content;

		$reg = "\[(\[?)(javascriptelementgeneral)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)";
		preg_match_all( '/' . $reg . '/s', $contenido, $matches );
		$re = '/\="{1}(.+)\"{1}/m';
		preg_match_all($re, $matches[3][0], $match, PREG_SET_ORDER, 0);
		$porciones = explode('"', $match[0][1]);

		$contentHeader = rawurldecode( base64_decode( strip_tags( $porciones[0] ) ) );
		update_post_meta($post_id,'head_field',$contentHeader);

		$contentBody = rawurldecode( base64_decode( strip_tags( $porciones[2] ) ) );
		update_post_meta($post_id,'body_field',$contentBody);
	}
}
