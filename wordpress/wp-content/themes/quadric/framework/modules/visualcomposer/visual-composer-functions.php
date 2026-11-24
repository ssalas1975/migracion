<?php

if(!function_exists('edgtf_quadric_vc_grid_elements_enabled')) {

	/**
	 * Function that checks if Visual Composer Grid Elements are enabled
	 *
	 * @return bool
	 */
	function edgtf_quadric_vc_grid_elements_enabled() {

		return (edgtf_quadric_options()->getOptionValue('enable_grid_elements') == 'yes') ? true : false;

	}
}

if(!function_exists('edgtf_quadric_visual_composer_grid_elements')) {

	/**
	 * Removes Visual Composer Grid Elements post type if VC Grid option disabled
	 * and enables Visual Composer Grid Elements post type
	 * if VC Grid option enabled
	 */
	function edgtf_quadric_visual_composer_grid_elements() {

		if(!edgtf_quadric_vc_grid_elements_enabled()) {
			remove_action('init', 'vc_grid_item_editor_create_post_type');
		}
	}

	add_action('vc_after_init', 'edgtf_quadric_visual_composer_grid_elements', 12);
}

if(!function_exists('edgtf_quadric_grid_elements_ajax_disable')) {
	/**
	 * Function that disables ajax transitions if grid elements are enabled in theme options
	 */
	function edgtf_quadric_grid_elements_ajax_disable() {
		global $edgtf_quadric_options;

		if(edgtf_quadric_vc_grid_elements_enabled()) {
			$edgtf_quadric_options['page_transitions'] = '0';
		}
	}

	add_action('wp', 'edgtf_quadric_grid_elements_ajax_disable');
}


if(!function_exists('edgtf_quadric_get_vc_version')) {
	/**
	 * Return Visual Composer version string
	 *
	 * @return bool|string
	 */
	function edgtf_quadric_get_vc_version() {
		if(edgtf_quadric_visual_composer_installed()) {
			return WPB_VC_VERSION;
		}

		return false;
	}
}