<?php

if(!function_exists('edgtf_quadric_get_zig_zag_html')) {
	/**
	 * Calls zig-zag shortcode with given parameters and returns it's output
	 * @param $params
	 *
	 * @return mixed|string
	 */
	function edgtf_quadric_get_zig_zag_html($params) {
		$zig_zag_html = edgtf_quadric_execute_shortcode('edgtf_zig_zag_separator', $params);
		$zig_zag_html = str_replace("\n", '', $zig_zag_html);
		return $zig_zag_html;
	}
}