<?php

if( !function_exists('any_edge_search_body_class') ) {
	/**
	 * Function that adds body classes for different search types
	 *
	 * @param $classes array original array of body classes
	 *
	 * @return array modified array of classes
	 */
	function any_edge_search_body_class($classes) {

		if ( is_active_widget( false, false, 'edgt_search_opener' ) ) {

			$classes[] = 'edgtf-' . any_edge_options()->getOptionValue('search_type');

			if ( any_edge_options()->getOptionValue('search_type') == 'fullscreen-search' ) {

				$classes[] = 'edgtf-' . any_edge_options()->getOptionValue('search_animation');

			}

		}
		return $classes;

	}

	add_filter('body_class', 'any_edge_search_body_class');
}

if ( ! function_exists('any_edge_get_search') ) {
	/**
	 * Loads search HTML based on search type option.
	 */
	function any_edge_get_search() {

		if ( any_edge_active_widget( false, false, 'edgt_search_opener' ) ) {

			$search_type = any_edge_options()->getOptionValue('search_type');

			if ($search_type == 'search-covers-header') {
				any_edge_set_position_for_covering_search();
				return;
			} else if ($search_type == 'search-slides-from-window-top') {
				any_edge_set_search_position_in_menu( $search_type );
				if ( any_edge_is_responsive_on() ) {
					any_edge_set_search_position_mobile();
				}
				return;
			}

			any_edge_load_search_template();

		}
	}

}

if ( ! function_exists('any_edge_set_position_for_covering_search') ) {
	/**
	 * Finds part of header where search template will be loaded
	 */
	function any_edge_set_position_for_covering_search() {

		$containing_sidebar = any_edge_active_widget( false, false, 'edgt_search_opener' );

		foreach ($containing_sidebar as $sidebar) {

			if ( strpos( $sidebar, 'top-bar' ) !== false ) {
				add_action( 'any_edge_after_header_top_html_open', 'any_edge_load_search_template');
			} else if ( strpos( $sidebar, 'main-menu' ) !== false ) {
				add_action( 'any_edge_after_header_menu_area_html_open', 'any_edge_load_search_template');
			} else if ( strpos( $sidebar, 'mobile-logo' ) !== false ) {
				add_action( 'any_edge_after_mobile_header_html_open', 'any_edge_load_search_template');
			} else if ( strpos( $sidebar, 'logo' ) !== false ) {
				add_action( 'any_edge_after_header_logo_area_html_open', 'any_edge_load_search_template');
			} else if ( strpos( $sidebar, 'sticky' ) !== false ) {
				add_action( 'any_edge_after_sticky_menu_html_open', 'any_edge_load_search_template');
			}

		}

	}

}

if ( ! function_exists('any_edge_set_search_position_in_menu') ) {
	/**
	 * Finds part of header where search template will be loaded
	 */
	function any_edge_set_search_position_in_menu( $type ) {

		add_action( 'any_edge_after_header_menu_area_html_open', 'any_edge_load_search_template');

	}
}

if ( ! function_exists('any_edge_set_search_position_mobile') ) {
	/**
	 * Hooks search template to mobile header
	 */
	function any_edge_set_search_position_mobile() {

		add_action( 'any_edge_after_mobile_header_html_open', 'any_edge_load_search_template');

	}

}

if ( ! function_exists('any_edge_load_search_template') ) {
	/**
	 * Loads HTML template with parameters
	 */
	function any_edge_load_search_template() {
		global $any_edge_IconCollections;

		$search_type = any_edge_options()->getOptionValue('search_type');

		$search_icon = '';
		$search_icon_close = '';
		if ( any_edge_options()->getOptionValue('search_icon_pack') !== '' ) {
			$search_icon = $any_edge_IconCollections->getSearchIcon( any_edge_options()->getOptionValue('search_icon_pack'), true );
			$search_icon_close = $any_edge_IconCollections->getSearchClose( any_edge_options()->getOptionValue('search_icon_pack'), true );
		}

		$parameters = array(
			'search_in_grid'		=> any_edge_options()->getOptionValue('search_in_grid') == 'yes' ? true : false,
			'search_icon'			=> $search_icon,
			'search_icon_close'		=> $search_icon_close
		);

		any_edge_get_module_template_part( 'templates/types/'.$search_type, 'search', '', $parameters );

	}

}