<?php

if (!function_exists('edgtf_quadric_search_covers_header_style')) {

	function edgtf_quadric_search_covers_header_style()
	{

		if (edgtf_quadric_options()->getOptionValue('search_height') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-header-bottom.edgtf-animated .edgtf-form-holder-outer, .edgtf-search-slide-header-bottom .edgtf-form-holder-outer, .edgtf-search-slide-header-bottom', array(
				'height' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_height')) . 'px'
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_covers_header_style');

}

if (!function_exists('edgtf_quadric_search_opener_icon_size')) {

	function edgtf_quadric_search_opener_icon_size()
	{

		if (edgtf_quadric_options()->getOptionValue('header_search_icon_size')) {
			echo edgtf_quadric_dynamic_css('.edgtf-search-opener', array(
				'font-size' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('header_search_icon_size')) . 'px'
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_opener_icon_size');

}

if (!function_exists('edgtf_quadric_search_opener_icon_colors')) {

	function edgtf_quadric_search_opener_icon_colors()
	{

		if (edgtf_quadric_options()->getOptionValue('header_search_icon_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-opener', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_search_icon_color')
			));
		}

		if (edgtf_quadric_options()->getOptionValue('header_search_icon_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-opener:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_search_icon_hover_color')
			));
		}

		if (edgtf_quadric_options()->getOptionValue('header_light_search_icon_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-light-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener,
			.edgtf-light-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener,
			.edgtf-light-header .edgtf-top-bar .edgtf-search-opener', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_light_search_icon_color') . ' !important'
			));
		}

		if (edgtf_quadric_options()->getOptionValue('header_light_search_icon_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-light-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener:hover,
			.edgtf-light-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener:hover,
			.edgtf-light-header .edgtf-top-bar .edgtf-search-opener:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_light_search_icon_hover_color') . ' !important'
			));
		}

		if (edgtf_quadric_options()->getOptionValue('header_dark_search_icon_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-dark-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener,
			.edgtf-dark-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener,
			.edgtf-dark-header .edgtf-top-bar .edgtf-search-opener', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_dark_search_icon_color') . ' !important'
			));
		}
		if (edgtf_quadric_options()->getOptionValue('header_dark_search_icon_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-dark-header .edgtf-page-header > div:not(.edgtf-sticky-header) .edgtf-search-opener:hover,
			.edgtf-dark-header.edgtf-header-style-on-scroll .edgtf-page-header .edgtf-search-opener:hover,
			.edgtf-dark-header .edgtf-top-bar .edgtf-search-opener:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('header_dark_search_icon_hover_color') . ' !important'
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_opener_icon_colors');

}

if (!function_exists('edgtf_quadric_search_opener_icon_background_colors')) {

	function edgtf_quadric_search_opener_icon_background_colors()
	{

		if (edgtf_quadric_options()->getOptionValue('search_icon_background_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-opener', array(
				'background-color' => edgtf_quadric_options()->getOptionValue('search_icon_background_color')
			));
		}

		if (edgtf_quadric_options()->getOptionValue('search_icon_background_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-opener:hover', array(
				'background-color' => edgtf_quadric_options()->getOptionValue('search_icon_background_hover_color')
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_opener_icon_background_colors');
}


if (!function_exists('edgtf_quadric_search_bar_background')) {

	function edgtf_quadric_search_bar_background()
	{

		if (edgtf_quadric_options()->getOptionValue('search_background_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-header-bottom, .edgtf-search-cover, .edgtf-search-fade .edgtf-fullscreen-search-holder .edgtf-fullscreen-search-table, .edgtf-fullscreen-search-overlay, .edgtf-search-slide-window-top, .edgtf-search-slide-window-top input[type="text"]', array(
				'background-color' => edgtf_quadric_options()->getOptionValue('search_background_color')
			));
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_bar_background');
}

if (!function_exists('edgtf_quadric_search_text_styles')) {

	function edgtf_quadric_search_text_styles()
	{
		$text_styles = array();

		if (edgtf_quadric_options()->getOptionValue('search_text_color') !== '') {
			$text_styles['color'] = edgtf_quadric_options()->getOptionValue('search_text_color');
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_fontsize') !== '') {
			$text_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_text_fontsize')) . 'px';
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_texttransform') !== '') {
			$text_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('search_text_texttransform');
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_google_fonts') !== '-1') {
			$text_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('search_text_google_fonts')) . ', sans-serif';
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_fontstyle') !== '') {
			$text_styles['font-style'] = edgtf_quadric_options()->getOptionValue('search_text_fontstyle');
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_fontweight') !== '') {
			$text_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('search_text_fontweight');
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_letterspacing') !== '') {
			$text_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_text_letterspacing')) . 'px';
		}

		if (!empty($text_styles)) {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-header-bottom input[type="text"], .edgtf-search-cover input[type="text"], .edgtf-fullscreen-search-holder .edgtf-search-field, .edgtf-search-slide-window-top input[type="text"]', $text_styles);
		}
		if (edgtf_quadric_options()->getOptionValue('search_text_disabled_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-header-bottom.edgtf-disabled input[type="text"]::-webkit-input-placeholder, .edgtf-search-slide-header-bottom.edgtf-disabled input[type="text"]::-moz-input-placeholder', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_text_disabled_color')
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_text_styles');
}

if (!function_exists('edgtf_quadric_search_label_styles')) {

	function edgtf_quadric_search_label_styles()
	{
		$text_styles = array();

		if (edgtf_quadric_options()->getOptionValue('search_label_text_color') !== '') {
			$text_styles['color'] = edgtf_quadric_options()->getOptionValue('search_label_text_color');
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_fontsize') !== '') {
			$text_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_label_text_fontsize')) . 'px';
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_texttransform') !== '') {
			$text_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('search_label_text_texttransform');
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_google_fonts') !== '-1') {
			$text_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('search_label_text_google_fonts')) . ', sans-serif';
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_fontstyle') !== '') {
			$text_styles['font-style'] = edgtf_quadric_options()->getOptionValue('search_label_text_fontstyle');
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_fontweight') !== '') {
			$text_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('search_label_text_fontweight');
		}
		if (edgtf_quadric_options()->getOptionValue('search_label_text_letterspacing') !== '') {
			$text_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_label_text_letterspacing')) . 'px';
		}

		if (!empty($text_styles)) {
			echo edgtf_quadric_dynamic_css('.edgtf-fullscreen-search-holder .edgtf-search-label', $text_styles);
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_label_styles');
}

if (!function_exists('edgtf_quadric_search_icon_styles')) {

	function edgtf_quadric_search_icon_styles()
	{

		if (edgtf_quadric_options()->getOptionValue('search_icon_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top > i, .edgtf-search-slide-header-bottom .edgtf-search-submit i, .edgtf-fullscreen-search-holder .edgtf-search-submit', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_icon_color')
			));
		}
		if (edgtf_quadric_options()->getOptionValue('search_icon_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top > i:hover, .edgtf-search-slide-header-bottom .edgtf-search-submit i:hover, .edgtf-fullscreen-search-holder .edgtf-search-submit:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_icon_hover_color')
			));
		}
		if (edgtf_quadric_options()->getOptionValue('search_icon_disabled_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-header-bottom.edgtf-disabled .edgtf-search-submit i, .edgtf-search-slide-header-bottom.edgtf-disabled .edgtf-search-submit i:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_icon_disabled_color')
			));
		}
		if (edgtf_quadric_options()->getOptionValue('search_icon_size') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top > i, .edgtf-search-slide-header-bottom .edgtf-search-submit i, .edgtf-fullscreen-search-holder .edgtf-search-submit', array(
				'font-size' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_icon_size')) . 'px'
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_icon_styles');
}

if (!function_exists('edgtf_quadric_search_close_icon_styles')) {

	function edgtf_quadric_search_close_icon_styles()
	{

		if (edgtf_quadric_options()->getOptionValue('search_close_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i, .edgtf-search-cover .edgtf-search-close i, .edgtf-fullscreen-search-close i', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_close_color')
			));
		}
		if (edgtf_quadric_options()->getOptionValue('search_close_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i:hover, .edgtf-search-cover .edgtf-search-close i:hover, .edgtf-fullscreen-search-close i:hover', array(
				'color' => edgtf_quadric_options()->getOptionValue('search_close_hover_color')
			));
		}
		if (edgtf_quadric_options()->getOptionValue('search_close_size') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-search-slide-window-top .edgtf-search-close i, .edgtf-search-cover .edgtf-search-close i, .edgtf-fullscreen-search-close i', array(
				'font-size' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('search_close_size')) . 'px'
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_search_close_icon_styles');
}

?>
