<?php

if ( ! function_exists('edgtf_quadric_load_elements_map') ) {
	/**
	 * Add Elements option page for shortcodes
	 */
	function edgtf_quadric_load_elements_map() {

		edgtf_quadric_add_admin_page(
			array(
				'slug' => '_elements_page',
				'title' => 'Elements',
				'icon' => 'fa fa-flag-o'
			)
		);

		do_action( 'edgtf_quadric_options_elements_map' );

	}

	add_action('edgtf_quadric_options_map', 'edgtf_quadric_load_elements_map', 10);

}