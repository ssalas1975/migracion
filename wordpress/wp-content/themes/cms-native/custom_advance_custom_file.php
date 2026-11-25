<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_6abe6b2012b0a',
	'title' => 'content scripts',
	'fields' => array(
		array(
			'key' => 'field_6abe6b2ef1e92',
			'label' => 'url scripts',
			'name' => 'url_scripts',
			'type' => 'textarea',
			'instructions' => 'Introducir las urls separadas por coma',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 8,
			'new_lines' => '',
		),
		array(
			'key' => 'field_6ae47c9625392',
			'label' => 'scripts en línea',
			'name' => 'scripts_en_linea',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_6abe72f29cc46',
			'label' => 'url css',
			'name' => 'url_css',
			'type' => 'textarea',
			'instructions' => 'Introducir las urls separadas por coma',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => '',
			'rows' => 8,
			'new_lines' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 99,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
