<?php

if (!function_exists('any_edge_search_opener_icon_size')) {

	function any_edge_search_opener_icon_size()
	{

		if (any_edge_options()->getOptionValue('header_search_icon_size')) {
			echo any_edge_dynamic_css('.edgtf-search-opener, .edgtf-header-standard .edgtf-search-opener', array(
				'font-size' => any_edge_filter_px(any_edge_options()->getOptionValue('header_search_icon_size')) . 'px'
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_opener_icon_size');

}

if (!function_exists('any_edge_search_opener_icon_colors')) {

	function any_edge_search_opener_icon_colors()
	{

		if (any_edge_options()->getOptionValue('header_search_icon_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-opener', array(
				'color' => any_edge_options()->getOptionValue('header_search_icon_color')
			));
		}

		if (any_edge_options()->getOptionValue('header_search_icon_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-opener:hover', array(
				'color' => any_edge_options()->getOptionValue('header_search_icon_hover_color')
			));
		}

		if (any_edge_options()->getOptionValue('header_light_search_icon_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-light-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener,
			.edgtf-light-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener,
			.edgtf-light-header .edgtf-top-bar .edgtf-search-opener', array(
				'color' => any_edge_options()->getOptionValue('header_light_search_icon_color') . ' !important'
			));
		}

		if (any_edge_options()->getOptionValue('header_light_search_icon_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-light-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener:hover,
			.edgtf-light-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener:hover,
			.edgtf-light-header .edgtf-top-bar .edgtf-search-opener:hover', array(
				'color' => any_edge_options()->getOptionValue('header_light_search_icon_hover_color') . ' !important'
			));
		}

		if (any_edge_options()->getOptionValue('header_dark_search_icon_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-dark-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener,
			.edgtf-dark-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener,
			.edgtf-dark-header .edgtf-top-bar .edgtf-search-opener', array(
				'color' => any_edge_options()->getOptionValue('header_dark_search_icon_color') . ' !important'
			));
		}
		if (any_edge_options()->getOptionValue('header_dark_search_icon_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-dark-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener:hover,
			.edgtf-dark-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener:hover,
			.edgtf-dark-header .edgtf-top-bar .edgtf-search-opener:hover', array(
				'color' => any_edge_options()->getOptionValue('header_dark_search_icon_hover_color') . ' !important'
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_opener_icon_colors');

}

if (!function_exists('any_edge_search_opener_icon_background_colors')) {

	function any_edge_search_opener_icon_background_colors()
	{

		if (any_edge_options()->getOptionValue('search_icon_background_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-opener', array(
				'background-color' => any_edge_options()->getOptionValue('search_icon_background_color')
			));
		}

		if (any_edge_options()->getOptionValue('search_icon_background_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-opener:hover', array(
				'background-color' => any_edge_options()->getOptionValue('search_icon_background_hover_color')
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_opener_icon_background_colors');
}

if (!function_exists('any_edge_search_opener_text_styles')) {

	function any_edge_search_opener_text_styles()
	{
		$text_styles = array();

		if (any_edge_options()->getOptionValue('search_icon_text_color') !== '') {
			$text_styles['color'] = any_edge_options()->getOptionValue('search_icon_text_color');
		}
		if (any_edge_options()->getOptionValue('search_icon_text_fontsize') !== '') {
			$text_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_icon_text_fontsize')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_icon_text_lineheight') !== '') {
			$text_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_icon_text_lineheight')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_icon_text_texttransform') !== '') {
			$text_styles['text-transform'] = any_edge_options()->getOptionValue('search_icon_text_texttransform');
		}
		if (any_edge_options()->getOptionValue('search_icon_text_google_fonts') !== '-1') {
			$text_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('search_icon_text_google_fonts')) . ', sans-serif';
		}
		if (any_edge_options()->getOptionValue('search_icon_text_fontstyle') !== '') {
			$text_styles['font-style'] = any_edge_options()->getOptionValue('search_icon_text_fontstyle');
		}
		if (any_edge_options()->getOptionValue('search_icon_text_fontweight') !== '') {
			$text_styles['font-weight'] = any_edge_options()->getOptionValue('search_icon_text_fontweight');
		}		
		if (any_edge_options()->getOptionValue('search_icon_text_letterspacing') !== '') {
			$text_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_icon_text_letterspacing')).'px';
		}

		if (!empty($text_styles)) {
			echo any_edge_dynamic_css('.edgtf-search-icon-text', $text_styles);
		}
		if (any_edge_options()->getOptionValue('search_icon_text_color_hover') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-opener:hover .edgtf-search-icon-text', array(
				'color' => any_edge_options()->getOptionValue('search_icon_text_color_hover')
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_opener_text_styles');
}

if (!function_exists('any_edge_search_opener_spacing')) {

	function any_edge_search_opener_spacing()
	{
		$spacing_styles = array();

		if (any_edge_options()->getOptionValue('search_padding_left') !== '') {
			$spacing_styles['padding-left'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_padding_left')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_padding_right') !== '') {
			$spacing_styles['padding-right'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_padding_right')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_margin_left') !== '') {
			$spacing_styles['margin-left'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_margin_left')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_margin_right') !== '') {
			$spacing_styles['margin-right'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_margin_right')) . 'px';
		}

		if (!empty($spacing_styles)) {
			echo any_edge_dynamic_css('.edgtf-search-opener', $spacing_styles);
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_opener_spacing');
}

if (!function_exists('any_edge_search_bar_background')) {

	function any_edge_search_bar_background()
	{

		if (any_edge_options()->getOptionValue('search_background_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-cover, .edgtf-search-fade .edgtf-fullscreen-search-holder .edgtf-fullscreen-search-table, .edgtf-fullscreen-search-overlay, .edgtf-search-slide-window-top, .edgtf-search-slide-window-top input[type="text"]', array(
				'background-color' => any_edge_options()->getOptionValue('search_background_color')
			));
		}
	}

	add_action('any_edge_style_dynamic', 'any_edge_search_bar_background');
}

if (!function_exists('any_edge_search_text_styles')) {

	function any_edge_search_text_styles()
	{
		$text_styles = array();

		if (any_edge_options()->getOptionValue('search_text_color') !== '') {
			$text_styles['color'] = any_edge_options()->getOptionValue('search_text_color');
			$placeholder_webkit = array(
				'.edgtf-search-slide-window-top input[type="text"]::-webkit-input-placeholder',
				'.edgtf-search-cover input[type="text"]::-webkit-input-placeholder',
				'.edgtf-form-holder .edgtf-search-field::-webkit-input-placeholder',
				'.edgtf-fullscreen-search-opened .edgtf-form-holder .edgtf-search-field::-webkit-input-placeholder'
			);
			$placeholder_moz1 = array(
				'.edgtf-search-slide-window-top input[type="text"]:-moz-input-placeholder',
				'.edgtf-search-cover input[type="text"]:-moz-input-placeholder',
				'.edgtf-form-holder .edgtf-search-field:-moz-input-placeholder',
				'edgtf-fullscreen-search-opened .edgtf-form-holder .edgtf-search-field:-moz-input-placeholder'
			);
			$placeholder_moz2 = array(
				'.edgtf-search-slide-window-top input[type="text"]::-moz-input-placeholder',
				'.edgtf-search-cover input[type="text"]::-moz-input-placeholder',
				'.edgtf-form-holder .edgtf-search-field::-moz-input-placeholder',
				'edgtf-fullscreen-search-opened .edgtf-form-holder .edgtf-search-field::-moz-input-placeholder'
			);
			$placeholder_ms = array(
				'.edgtf-search-slide-window-top input[type="text"]:-ms-input-placeholder',
				'.edgtf-search-cover input[type="text"]:-ms-input-placeholder',
				'.edgtf-form-holder .edgtf-search-field:-ms-input-placeholder',
				'edgtf-fullscreen-search-opened .edgtf-form-holder .edgtf-search-field:-ms-input-placeholder'
			);
			echo any_edge_dynamic_css($placeholder_webkit,array('color' => $text_styles['color']));
			echo any_edge_dynamic_css($placeholder_moz1,array('color' => $text_styles['color']));
			echo any_edge_dynamic_css($placeholder_moz2,array('color' => $text_styles['color']));
			echo any_edge_dynamic_css($placeholder_ms,array('color' => $text_styles['color']));
		}
		if (any_edge_options()->getOptionValue('search_text_fontsize') !== '') {
			$text_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_text_fontsize')) . 'px';
		}
		if (any_edge_options()->getOptionValue('search_text_texttransform') !== '') {
			$text_styles['text-transform'] = any_edge_options()->getOptionValue('search_text_texttransform');
		}
		if (any_edge_options()->getOptionValue('search_text_google_fonts') !== '-1') {
			$text_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('search_text_google_fonts')) . ', sans-serif';
		}
		if (any_edge_options()->getOptionValue('search_text_fontstyle') !== '') {
			$text_styles['font-style'] = any_edge_options()->getOptionValue('search_text_fontstyle');
		}
		if (any_edge_options()->getOptionValue('search_text_fontweight') !== '') {
			$text_styles['font-weight'] = any_edge_options()->getOptionValue('search_text_fontweight');
		}
		if (any_edge_options()->getOptionValue('search_text_letterspacing') !== '') {
			$text_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('search_text_letterspacing')) . 'px';
		}

		if (!empty($text_styles)) {
			echo any_edge_dynamic_css('.edgtf-search-cover input[type="text"], .edgtf-fullscreen-search-opened .edgtf-form-holder .edgtf-search-field, .edgtf-search-slide-window-top input[type="text"]', $text_styles);
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_text_styles');
}

if (!function_exists('any_edge_search_icon_styles')) {

	function any_edge_search_icon_styles()
	{

		if (any_edge_options()->getOptionValue('search_icon_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-cover input[type="submit"], .edgtf-fullscreen-search-holder .edgtf-search-submit, .edgtf-search-slide-window-top .edgtf-icon-search', array(
				'color' => any_edge_options()->getOptionValue('search_icon_color')
			));
		}
		if (any_edge_options()->getOptionValue('search_icon_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-cover input[type="submit"]:hover, .edgtf-fullscreen-search-holder .edgtf-search-submit:hover,.edgtf-search-slide-window-top .edgtf-icon-search:hover', array(
				'color' => any_edge_options()->getOptionValue('search_icon_hover_color')
			));
		}
		if (any_edge_options()->getOptionValue('search_icon_size') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-cover input[type="submit"], .edgtf-fullscreen-search-holder .edgtf-search-submit, .edgtf-search-slide-window-top .edgtf-icon-search', array(
				'font-size' => any_edge_filter_px(any_edge_options()->getOptionValue('search_icon_size')) . 'px'
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_icon_styles');
}

if (!function_exists('any_edge_search_close_icon_styles')) {

	function any_edge_search_close_icon_styles()
	{

		if (any_edge_options()->getOptionValue('search_close_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i, .edgtf-search-cover .edgtf-search-close a, .edgtf-fullscreen-search-holder .edgtf-fullscreen-search-close-container a', array(
				'color' => any_edge_options()->getOptionValue('search_close_color')
			));
		}
		if (any_edge_options()->getOptionValue('search_close_hover_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i:hover, .edgtf-search-cover .edgtf-search-close a:hover, .edgtf-fullscreen-search-holder .edgtf-fullscreen-search-close-container a:hover', array(
				'color' => any_edge_options()->getOptionValue('search_close_hover_color')
			));
		}
		if (any_edge_options()->getOptionValue('search_close_size') !== '') {
			echo any_edge_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i, .edgtf-search-cover .edgtf-search-close a, .edgtf-fullscreen-search-holder .edgtf-fullscreen-search-close-container a', array(
				'font-size' => any_edge_filter_px(any_edge_options()->getOptionValue('search_close_size')) . 'px'
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_close_icon_styles');
}

if (!function_exists('any_edge_search_border_styles')) {

	function any_edge_search_border_styles()
	{

		if (any_edge_options()->getOptionValue('search_border_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-fullscreen-search-holder .edgtf-field-holder', array(
				'border-color' => any_edge_options()->getOptionValue('search_border_color')
			));
		}
		if (any_edge_options()->getOptionValue('search_border_focus_color') !== '') {
			echo any_edge_dynamic_css('.edgtf-fullscreen-search-holder .edgtf-field-holder .edgtf-line', array(
				'background-color' => any_edge_options()->getOptionValue('search_border_focus_color')
			));
		}

	}

	add_action('any_edge_style_dynamic', 'any_edge_search_border_styles');
}

?>
