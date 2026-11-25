<?php

// Create Shortcode imagen_2027
// Use the shortcode: [imagen_2027 htmlposttype=""]
function create_imagen_2027_shortcode($atts)
{
	// Attributes
	$atts = shortcode_atts(
		array(
			'gcimagen2027' => '',
			'gctipoimagen' => '',
			'gcpieimagen2027' => ''
		),
		$atts,
		'imagen_2027',
	);

	$gcimagen2027 = wp_get_attachment_url($atts['gcimagen2027']);
	$gctipoimagen = $atts['gctipoimagen'];
	$gcpieimagen2027 = $atts['gcpieimagen2027'];

	$html = '
	<figure class="imagen-2027 '.$gctipoimagen.'">
    <img class=""
        src="'.$gcimagen2027.'"
        class="attachment-large size-large" alt="" loading="lazy">
    <figcaption>
        <p class="pie-foto-2027">'.$gcpieimagen2027.'</p>
    </figcaption>
	</figure>
	';
	return $html;
}
add_shortcode('imagen_2027', 'create_imagen_2027_shortcode');

// Create Modulo HTML element for Visual Composer
add_action('vc_before_init', 'imagen_2027_integrateWithVC');
function imagen_2027_integrateWithVC()
{

	vc_map(
		array(
			'name' => __('Modulo Imagen 2027', 'textdomain'),
			'base' => 'imagen_2027',
			'show_settings_on_create' => true,
			'category' => __('Vocento 2027', 'textdomain'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Tipo de Imagen', 'textdomain'),
					'param_name' => 'gctipoimagen',
					'value' => array('columna','grande'),
				),
				array(
					'type' => 'attach_image',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Imagen', 'textdomain'),
					'param_name' => 'gcimagen2027',
				),
				array(
          'type' => 'textfield',
          'holder' => 'div',
          'class' => '',
          'admin_label' => true,
          'heading' => __('Pie Imagen', 'textdomain'),
          'param_name' => 'gcpieimagen2027',
        ),
			)
		)
	);
}
