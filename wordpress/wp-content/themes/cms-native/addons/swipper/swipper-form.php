<?php

require_once( dirname(__FILE__) . '/swipper-view.php');

vc_map( array(
	'name' => __( 'swipper', 'textdomain' ),
	'description' => __( 'swipper', '' ),
	'base' => 'swipper',
	'show_settings_on_create' => true,
	'category' => __( 'Vocento', 'textdomain'),
	'params' => array(
		array(
			'type' => 'attach_images',
			'holder' => 'div',
			'class' => '',
			'admin_label' => false,
			'heading' => __( 'SWIPPER SLIDER', 'textdomain' ),
			'param_name' => 'swipperslider',
		),
	)
) );

?>