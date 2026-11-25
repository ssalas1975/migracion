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
add_action( 'vc_before_init', 'javascriptelement_integrateWithVC' );
function javascriptelement_integrateWithVC() {
	vc_map( array(
		'name' => __( 'javascript_element', 'textdomain' ),
		'description' => __( 'Incrusta código javascript en la cabecera', '' ),
		'base' => 'javascriptelement',
		'show_settings_on_create' => true,
		'category' => __( 'Content', 'textdomain'),
		'params' => array(
			array(
				'type' => 'idpost_field',
				'param_name' => 'id_field',
				'value' => array(),
			),
			array(
				'type' => 'head_field',
				'param_name' => 'cod_cabecera',
				'heading' => __( 'Código Cabecera', 'textdomain' ),
				'value' => array(),
			),
			array(
				'type' => 'body_field',
				'param_name' => 'cod_cuerpo',
				'heading' => __( 'Código Cuerpo', 'textdomain' ),
				'value' => array(),
			),
		)
	) );
}

vc_add_shortcode_param( 'idpost_field', 'idpost_field_function', get_template_directory_uri() . '/js/javascript_js.js');
function idpost_field_function( $param, $value ) {
	$param_line = '';
	$param_line .= "<input type='hidden' value='".$value."' name='".esc_attr( $param["param_name"])."' class='wpb_vc_param_value wpb-input wpb-select ". esc_attr( $param["param_name"] )." ". esc_attr($param["type"])." contentvalues idpostfield'>";
	return $param_line;
}


vc_add_shortcode_param( 'head_field', 'head_field_function', get_template_directory_uri().'/js/javascript_js.js');
function head_field_function( $param, $value ) {
	$param_line = '';
	$param_line .= '<textarea rows="4" cols="50" name="'.esc_attr( $param["param_name"]).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param["param_name"] ).' '. esc_attr($param["type"]).' contentvalues head_field">'.$value.'</textarea>';
	$param_line .= "<input type='hidden' value='".admin_url( "admin-ajax.php" )."' id='adminajaxurl_js_field' name='adminajaxurl_js_field' />";
	return  $param_line;
}

vc_add_shortcode_param( 'body_field', 'body_field_function', get_template_directory_uri().'/js/javascript_js.js');
function body_field_function( $param, $value ) {
	$param_line = '';
	$param_line .= '<textarea rows="4" cols="50" name="'.esc_attr( $param["param_name"]).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param["param_name"] ).' '. esc_attr($param["type"]).' contentvalues body_field">'.$value.'</textarea>';
	$param_line .= "<input type='hidden' value='".admin_url( "admin-ajax.php" )."' id='adminajaxurl_js_field' name='adminajaxurl_js_field' />";
	return  $param_line;
}

