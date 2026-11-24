<?php

if(!function_exists('edgtf_quadric_header_class')) {
    /**
     * Function that adds class to header based on theme options
     * @param array array of classes from main filter
     * @return array array of classes with added header class
     */
    function edgtf_quadric_header_class($classes) {
        $header_type = edgtf_quadric_get_meta_field_intersect('header_type', edgtf_quadric_get_page_id());

        $classes[] = 'edgtf-'.$header_type;

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_header_class');
}

if(!function_exists('edgtf_quadric_header_behaviour_class')) {
    /**
     * Function that adds behaviour class to header based on theme options
     * @param array array of classes from main filter
     * @return array array of classes with added behaviour class
     */
    function edgtf_quadric_header_behaviour_class($classes) {

        $classes[] = 'edgtf-'.edgtf_quadric_options()->getOptionValue('header_behaviour');

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_header_behaviour_class');
}

if(!function_exists('edgtf_quadric_menu_item_icon_position_class')) {
    /**
     * Function that adds menu item icon position class to header based on theme options
     * @param array array of classes from main filter
     * @return array array of classes with added menu item icon position class
     */
    function edgtf_quadric_menu_item_icon_position_class($classes) {

        if(edgtf_quadric_options()->getOptionValue('menu_item_icon_position') == 'top'){
            $classes[] = 'edgtf-menu-with-large-icons';
        }

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_menu_item_icon_position_class');
}

if(!function_exists('edgtf_quadric_mobile_header_class')) {
    function edgtf_quadric_mobile_header_class($classes) {
        $classes[] = 'edgtf-default-mobile-header';

        $classes[] = 'edgtf-sticky-up-mobile-header';

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_mobile_header_class');
}

if(!function_exists('edgtf_quadric_header_class_first_level_bg_color')) {
    /**
     * Function that adds first level menu background color class to header tag
     * @param array array of classes from main filter
     * @return array array of classes with added first level menu background color class
     */
    function edgtf_quadric_header_class_first_level_bg_color($classes) {

        //check if first level hover background color is set
        if(edgtf_quadric_options()->getOptionValue('menu_hover_background_color') !== ''){
            $classes[]= 'edgtf-menu-item-first-level-bg-color';
        }

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_header_class_first_level_bg_color');
}

if(!function_exists('edgtf_quadric_menu_dropdown_appearance')) {
    /**
     * Function that adds menu dropdown appearance class to body tag
     * @param array array of classes from main filter
     * @return array array of classes with added menu dropdown appearance class
     */
    function edgtf_quadric_menu_dropdown_appearance($classes) {

        if(edgtf_quadric_options()->getOptionValue('menu_dropdown_appearance') !== 'default'){
            $classes[] = 'edgtf-'.edgtf_quadric_options()->getOptionValue('menu_dropdown_appearance');
        }

        return $classes;
    }

    add_filter('body_class', 'edgtf_quadric_menu_dropdown_appearance');
}

if (!function_exists('edgtf_quadric_header_skin_class')) {

    function edgtf_quadric_header_skin_class( $classes ) {

        $id = edgtf_quadric_get_page_id();

		if(get_post_meta($id, 'edgtf_header_style_meta', true) !== ''){
			$classes[] = 'edgtf-' . get_post_meta($id, 'edgtf_header_style_meta', true);
		} else if ( edgtf_quadric_options()->getOptionValue('header_style') !== '' ) {
            $classes[] = 'edgtf-' . edgtf_quadric_options()->getOptionValue('header_style');
        }

        return $classes;

    }

    add_filter('body_class', 'edgtf_quadric_header_skin_class');

}

if (!function_exists('edgtf_quadric_header_scroll_style_class')) {

	function edgtf_quadric_header_scroll_style_class( $classes ) {

		if (edgtf_quadric_get_meta_field_intersect('enable_header_style_on_scroll') == 'yes' ) {
			$classes[] = 'edgtf-header-style-on-scroll';
		}

		return $classes;

	}

	add_filter('body_class', 'edgtf_quadric_header_scroll_style_class');

}

if(!function_exists('edgtf_quadric_header_global_js_var')) {
    function edgtf_quadric_header_global_js_var($global_variables) {

        $global_variables['edgtfTopBarHeight'] = edgtf_quadric_get_top_bar_height();
        $global_variables['edgtfStickyHeaderHeight'] = edgtf_quadric_get_sticky_header_height();
        $global_variables['edgtfStickyHeaderTransparencyHeight'] = edgtf_quadric_get_sticky_header_height_of_complete_transparency();

        return $global_variables;
    }

    add_filter('edgtf_quadric_js_global_variables', 'edgtf_quadric_header_global_js_var');
}

if(!function_exists('edgtf_quadric_header_per_page_js_var')) {
    function edgtf_quadric_header_per_page_js_var($perPageVars) {

        $perPageVars['edgtfStickyScrollAmount'] = edgtf_quadric_get_sticky_scroll_amount();

        return $perPageVars;
    }

    add_filter('edgtf_quadric_per_page_js_vars', 'edgtf_quadric_header_per_page_js_var');
}