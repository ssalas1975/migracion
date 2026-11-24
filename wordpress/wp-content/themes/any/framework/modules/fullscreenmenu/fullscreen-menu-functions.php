<?php

if(!function_exists('any_edge_register_full_screen_menu_nav')) {
    function any_edge_register_full_screen_menu_nav() {
	    register_nav_menus(
		    array(
			    'popup-navigation' => esc_html__('Fullscreen Navigation', 'any')
		    )
	    );
    }

	add_action('after_setup_theme', 'any_edge_register_full_screen_menu_nav');
}

if ( !function_exists('any_edge_register_full_screen_menu_sidebars') ) {

	function any_edge_register_full_screen_menu_sidebars() {

		register_sidebar(array(
			'name' => 'Fullscreen Menu Top',
			'id' => 'fullscreen_menu_above',
			'description' => 'This widget area is rendered above fullscreen menu',
			'before_widget' => '<div class="%2$s edgtf-fullscreen-menu-above-widget">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="edgtf-fullscreen-widget-title">',
			'after_title' => '</h4>'
		));

		register_sidebar(array(
			'name' => 'Fullscreen Menu Bottom',
			'id' => 'fullscreen_menu_below',
			'description' => 'This widget area is rendered in the bottom of the fullscreen menu',
			'before_widget' => '<div class="%2$s edgtf-fullscreen-menu-below-widget">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="edgtf-fullscreen-widget-title">',
			'after_title' => '</h4>'
		));

	}

	add_action('widgets_init', 'any_edge_register_full_screen_menu_sidebars');

}

if(!function_exists('any_edge_get_fullscreen_menu_styles')) {
	/**
	 * Sets per page styles for fullscreen menu
	 *
	 * @param $styles
	 *
	 * @return array
	 */
	function any_edge_get_fullscreen_menu_styles($styles) {
		$id            = any_edge_get_page_id();
		$class_prefix  = any_edge_get_unique_page_class();
		$fullscreen_style = array();

		$icon_bg_color     = get_post_meta($id, 'edgtf_fullscreen_menu_icon_background_color_meta', true);
		$icon_bg_hover_color     = get_post_meta($id, 'edgtf_fullscreen_menu_icon_background_hover_color_meta', true);

		$fullscreen_icon_selector = array(
			$class_prefix.' .edgtf-fullscreen-menu-opener:not(.opened)'
		);

		$fullscreen_icon_hover_selector = array(
			$class_prefix.' .edgtf-fullscreen-menu-opener:not(.opened):hover'
		);

		if($icon_bg_color !== '') {
			$styles[] = any_edge_dynamic_css($fullscreen_icon_selector,array('background-color' => $icon_bg_color));
		}

		if($icon_bg_hover_color !== '') {
			$styles[] = any_edge_dynamic_css($fullscreen_icon_hover_selector,array('background-color' => $icon_bg_hover_color));
		}

		return $styles;
	}

	add_filter('any_edge_add_page_custom_style', 'any_edge_get_fullscreen_menu_styles');
}

if(!function_exists('any_edge_fullscreen_menu_body_class')) {
	/**
	 * Function that adds body classes for different full screen menu types
	 *
	 * @param $classes array original array of body classes
	 *
	 * @return array modified array of classes
	 */
	function any_edge_fullscreen_menu_body_class($classes) {

		if ( is_active_widget( false, false, 'edgtf_full_screen_menu_opener' )  || any_edge_get_meta_field_intersect('header_type', any_edge_get_page_id()) == 'header-full-screen'  ) {

			$classes[] = 'edgtf-' . any_edge_options()->getOptionValue('fullscreen_menu_animation_style');

		}

		if (any_edge_get_meta_field_intersect('fullscreen_menu_icon_background_color',any_edge_get_page_id()) !== '' || any_edge_get_meta_field_intersect('fullscreen_menu_icon_background_hover_color',any_edge_get_page_id()) !== '') {
			$classes[] = 'edgtf-fullscreen-icon-with-bckg';
		}


		return $classes;
	}

	add_filter('body_class', 'any_edge_fullscreen_menu_body_class');
}

if ( !function_exists('any_edge_get_full_screen_menu') ) {
	/**
	 * Loads fullscreen menu HTML template
	 */
	function any_edge_get_full_screen_menu() {

		if ( is_active_widget( false, false, 'edgtf_full_screen_menu_opener' ) || any_edge_get_meta_field_intersect('header_type', any_edge_get_page_id()) == 'header-full-screen'  ) {

			$parameters = array(
				'fullscreen_menu_in_grid' => any_edge_options()->getOptionValue('fullscreen_in_grid') === 'yes' ? true : false
			);

			any_edge_get_module_template_part('templates/fullscreen-menu', 'fullscreenmenu', '', $parameters);

		}

	}

}

if ( !function_exists('any_edge_get_full_screen_menu_navigation') ) {
	/**
	 * Loads fullscreen menu navigation HTML template
	 */
	function any_edge_get_full_screen_menu_navigation() {

		any_edge_get_module_template_part('templates/parts/navigation', 'fullscreenmenu');

	}

}