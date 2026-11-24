<?php

if ( ! function_exists('any_edge_reset_options_map') ) {
	/**
	 * Reset options panel
	 */
	function any_edge_reset_options_map() {

		any_edge_add_admin_page(
			array(
				'slug'  => '_reset_page',
				'title' => esc_html__('Reset', 'any'),
				'icon'  => 'fa fa-retweet'
			)
		);

		$panel_reset = any_edge_add_admin_panel(
			array(
				'page'  => '_reset_page',
				'name'  => 'panel_reset',
				'title' => esc_html__('Reset', 'any')
			)
		);

		any_edge_add_admin_field(array(
			'type'	=> 'yesno',
			'name'	=> 'reset_to_defaults',
			'default_value'	=> 'no',
			'label'			=> esc_html__('Reset to Defaults', 'any'),
			'description'	=> esc_html__('This option will reset all Edge Options values to defaults', 'any'),
			'parent'		=> $panel_reset
		));

	}

	add_action( 'any_edge_options_map', 'any_edge_reset_options_map', 21 );

}