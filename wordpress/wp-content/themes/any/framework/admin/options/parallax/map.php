<?php

if ( ! function_exists('any_edge_parallax_options_map') ) {
	/**
	 * Parallax options page
	 */
	function any_edge_parallax_options_map()
	{

		$panel_parallax = any_edge_add_admin_panel(
			array(
				'page'  => '_elements_page',
				'name'  => 'panel_parallax',
				'title' => esc_html__('Parallax', 'any')
			)
		);

		any_edge_add_admin_field(array(
			'type'			=> 'onoff',
			'name'			=> 'parallax_on_off',
			'default_value'	=> 'off',
			'label'			=> esc_html__('Parallax on touch devices', 'any'),
			'description'	=> esc_html__('Enabling this option will allow parallax on touch devices', 'any'),
			'parent'		=> $panel_parallax
		));

		any_edge_add_admin_field(array(
			'type'			=> 'text',
			'name'			=> 'parallax_min_height',
			'default_value'	=> '400',
			'label'			=> esc_html__('Parallax Min Height', 'any'),
			'description'	=> esc_html__('Set a minimum height for parallax images on small displays (phones, tablets, etc.)', 'any'),
			'args'			=> array(
				'col_width'	=> 3,
				'suffix'	=> 'px'
			),
			'parent'		=> $panel_parallax
		));

	}

	add_action( 'any_edge_options_elements_map', 'any_edge_parallax_options_map');

}