<?php

if( !function_exists('edgtf_quadric_search_body_class') ) {
	/**
	 * Function that adds body classes for different search types
	 *
	 * @param $classes array original array of body classes
	 *
	 * @return array modified array of classes
	 */
	function edgtf_quadric_search_body_class($classes) {

		if ( is_active_widget( false, false, 'edgt_search_opener' ) ) {

			$classes[] = 'edgtf-' . edgtf_quadric_options()->getOptionValue('search_type');

			if ( edgtf_quadric_options()->getOptionValue('search_type') == 'fullscreen-search' ) {

				$classes[] = 'edgtf-search-fade';

			}

		}
		return $classes;

	}

	add_filter('body_class', 'edgtf_quadric_search_body_class');
}

if ( ! function_exists('edgtf_quadric_get_search') ) {
	/**
	 * Loads search HTML based on search type option.
	 */
	function edgtf_quadric_get_search() {

		if ( edgtf_quadric_active_widget( false, false, 'edgt_search_opener' ) ) {

			$search_type = edgtf_quadric_options()->getOptionValue('search_type');

			if ($search_type == 'search-covers-header') {
				edgtf_quadric_set_position_for_covering_search();
				return;
			} else if ($search_type == 'search-slides-from-window-top' || $search_type == 'search-slides-from-header-bottom') {
				edgtf_quadric_set_search_position_in_menu( $search_type );
				if ( edgtf_quadric_is_responsive_on() ) {
					edgtf_quadric_set_search_position_mobile();
				}
				return;
			}

			edgtf_quadric_load_search_template();

		}
	}

}

if ( ! function_exists('edgtf_quadric_set_position_for_covering_search') ) {
	/**
	 * Finds part of header where search template will be loaded
	 */
	function edgtf_quadric_set_position_for_covering_search() {

		$containing_sidebar = edgtf_quadric_active_widget( false, false, 'edgt_search_opener' );

		foreach ($containing_sidebar as $sidebar) {

			if ( strpos( $sidebar, 'top-bar' ) !== false ) {
				add_action( 'edgtf_quadric_after_header_top_html_open', 'edgtf_quadric_load_search_template' );
			} else if ( strpos( $sidebar, 'main-menu' ) !== false ) {
				add_action( 'edgtf_quadric_after_header_menu_area_html_open', 'edgtf_quadric_load_search_template' );
			} else if ( strpos( $sidebar, 'mobile-logo' ) !== false ) {
				add_action( 'edgtf_quadric_after_mobile_header_html_open', 'edgtf_quadric_load_search_template' );
			} else if ( strpos( $sidebar, 'logo' ) !== false ) {
				add_action( 'edgtf_quadric_after_header_logo_area_html_open', 'edgtf_quadric_load_search_template' );
			} else if ( strpos( $sidebar, 'sticky' ) !== false ) {
				add_action( 'edgtf_quadric_after_sticky_menu_html_open', 'edgtf_quadric_load_search_template' );
			}

		}

	}

}

if ( ! function_exists('edgtf_quadric_set_search_position_in_menu') ) {
	/**
	 * Finds part of header where search template will be loaded
	 */
	function edgtf_quadric_set_search_position_in_menu( $type ) {

		add_action( 'edgtf_quadric_after_header_menu_area_html_open', 'edgtf_quadric_load_search_template' );
		if ( $type == 'search-slides-from-header-bottom' ) {
			add_action( 'edgtf_quadric_after_sticky_menu_html_open', 'edgtf_quadric_load_search_template' );
		}

	}
}

if ( ! function_exists('edgtf_quadric_set_search_position_mobile') ) {
	/**
	 * Hooks search template to mobile header
	 */
	function edgtf_quadric_set_search_position_mobile() {

		add_action( 'edgtf_quadric_after_mobile_header_html_open', 'edgtf_quadric_load_search_template' );

	}

}

if ( ! function_exists('edgtf_quadric_load_search_template') ) {
	/**
	 * Loads HTML template with parameters
	 */
	function edgtf_quadric_load_search_template() {
		global $edgtfQuadricIconCollections;

		$search_type = edgtf_quadric_options()->getOptionValue('search_type');

		$search_icon = '';
		if ( edgtf_quadric_options()->getOptionValue('search_icon_pack') !== '' ) {
			$search_icon = $edgtfQuadricIconCollections->getSearchIcon( edgtf_quadric_options()->getOptionValue('search_icon_pack'), true );
		}

		$zig_zag = '';
		if ( edgtf_quadric_options()->getOptionValue('search_type') == 'fullscreen-search' ) {
			$zig_zag .= edgtf_quadric_get_zig_zag_html(array('size'=>'medium', 'position'=>'left', 'animated'=>'no', ''));
			
		}

		$parameters = array(
			'search_in_grid'		=> edgtf_quadric_options()->getOptionValue('search_in_grid') == 'yes' ? true : false,
			'search_icon'			=> $search_icon,
			'zig_zag'					=> $zig_zag
		);

		edgtf_quadric_get_module_template_part( 'templates/types/'.$search_type, 'search', '', $parameters );

	}

}
