<?php

if (!function_exists('edgtf_quadric_side_area_slide_from_right_type_style')) {

	function edgtf_quadric_side_area_slide_from_right_type_style()
	{

		if (edgtf_quadric_options()->getOptionValue('side_area_type') == 'side-menu-slide-from-right') {

			if (edgtf_quadric_options()->getOptionValue('side_area_width') !== '' && edgtf_quadric_options()->getOptionValue('side_area_width') >= 30) {
				echo edgtf_quadric_dynamic_css('.edgtf-side-menu-slide-from-right .edgtf-side-menu', array(
					'right' => edgtf_quadric_options()->getOptionValue('side_area_width') . '%',
					'width' => edgtf_quadric_options()->getOptionValue('side_area_width') . '%'
				));
			}

			if (edgtf_quadric_options()->getOptionValue('side_area_content_overlay_color') !== '') {

				echo edgtf_quadric_dynamic_css('.edgtf-side-menu-slide-from-right .edgtf-wrapper .edgtf-cover', array(
					'background-color' => edgtf_quadric_options()->getOptionValue('side_area_content_overlay_color')
				));

			}
			if (edgtf_quadric_options()->getOptionValue('side_area_content_overlay_opacity') !== '') {

				echo edgtf_quadric_dynamic_css('.edgtf-side-menu-slide-from-right.edgtf-right-side-menu-opened .edgtf-wrapper .edgtf-cover', array(
					'opacity' => edgtf_quadric_options()->getOptionValue('side_area_content_overlay_opacity')
				));

			}
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_slide_from_right_type_style');

}

if (!function_exists('edgtf_quadric_side_area_icon_color_styles')) {

	function edgtf_quadric_side_area_icon_color_styles()
	{

		if (edgtf_quadric_options()->getOptionValue('side_area_icon_font_size') !== '') {

			echo edgtf_quadric_dynamic_css('a.edgtf-side-menu-button-opener', array(
				'font-size' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_icon_font_size')) . 'px'
			));

			if (edgtf_quadric_options()->getOptionValue('side_area_icon_font_size') > 30) {
				echo '@media only screen and (max-width: 480px) {
						a.edgtf-side-menu-button-opener {
						font-size: 30px;
						}
					}';
			}

		}

		if (edgtf_quadric_options()->getOptionValue('side_area_icon_color') !== '') {

			echo edgtf_quadric_dynamic_css('a.edgtf-side-menu-button-opener span.line', array(
				'border-color' => edgtf_quadric_options()->getOptionValue('side_area_icon_color')
			));

		}
		if (edgtf_quadric_options()->getOptionValue('side_area_icon_hover_color') !== '') {

			echo edgtf_quadric_dynamic_css('.edgtf-header-standard .edgtf-menu-area .edgtf-vertical-align-containers a.edgtf-side-menu-button-opener', array(
				'background-color' => edgtf_quadric_options()->getOptionValue('side_area_icon_background_color')
			));

		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_icon_color_styles');

}

if (!function_exists('edgtf_quadric_side_area_alignment')) {

	function edgtf_quadric_side_area_alignment()
	{

		if (edgtf_quadric_options()->getOptionValue('side_area_aligment')) {

			echo edgtf_quadric_dynamic_css('.edgtf-side-menu-slide-from-right .edgtf-side-menu, .edgtf-side-menu-slide-with-content .edgtf-side-menu, .edgtf-side-area-uncovered-from-content .edgtf-side-menu', array(
				'text-align' => edgtf_quadric_options()->getOptionValue('side_area_aligment')
			));

		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_alignment');

}

if (!function_exists('edgtf_quadric_side_area_styles')) {

	function edgtf_quadric_side_area_styles()
	{

		$side_area_styles = array();

		if (edgtf_quadric_options()->getOptionValue('side_area_background_color') !== '') {
			$side_area_styles['background-color'] = edgtf_quadric_options()->getOptionValue('side_area_background_color');
		}
		if (edgtf_quadric_options()->getOptionValue('side_area_background_image') !== '') {
			$side_area_styles['background-image'] = 'url(' .edgtf_quadric_options()->getOptionValue('side_area_background_image') . ')';
			$side_area_styles['background-size'] = 'cover';
		}
		if (edgtf_quadric_options()->getOptionValue('side_area_padding_top') !== '') {
			$side_area_styles['padding-top'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_padding_top')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_padding_right') !== '') {
			$side_area_styles['padding-right'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_padding_right')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_padding_bottom') !== '') {
			$side_area_styles['padding-bottom'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_padding_bottom')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_padding_left') !== '') {
			$side_area_styles['padding-left'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_padding_left')) . 'px';
		}

		if (!empty($side_area_styles)) {
			echo edgtf_quadric_dynamic_css('.edgtf-side-menu', $side_area_styles);
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_close_icon') == 'dark') {
			echo edgtf_quadric_dynamic_css('.edgtf-side-menu a.edgtf-close-side-menu span, .edgtf-side-menu a.edgtf-close-side-menu i', array(
				'color' => '#000000'
			));
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_close_icon_size') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-side-menu a.edgtf-close-side-menu', array(
				'height' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'width' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'line-height' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'padding' => 0,
			));
			echo edgtf_quadric_dynamic_css('.edgtf-side-menu a.edgtf-close-side-menu span, .edgtf-side-menu a.edgtf-close-side-menu i', array(
				'font-size' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'height' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'width' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
				'line-height' => edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_close_icon_size')) . 'px',
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_styles');

}

if (!function_exists('edgtf_quadric_side_area_title_styles')) {

	function edgtf_quadric_side_area_title_styles()
	{

		$title_styles = array();

		if (edgtf_quadric_options()->getOptionValue('side_area_title_color') !== '') {
			$title_styles['color'] = edgtf_quadric_options()->getOptionValue('side_area_title_color');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_fontsize') !== '') {
			$title_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_title_fontsize')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_lineheight') !== '') {
			$title_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_title_lineheight')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_texttransform') !== '') {
			$title_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('side_area_title_texttransform');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_google_fonts') !== '-1') {
			$title_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('side_area_title_google_fonts')) . ', sans-serif';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_fontstyle') !== '') {
			$title_styles['font-style'] = edgtf_quadric_options()->getOptionValue('side_area_title_fontstyle');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_fontweight') !== '') {
			$title_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('side_area_title_fontweight');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_title_letterspacing') !== '') {
			$title_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_title_letterspacing')) . 'px';
		}

		if (!empty($title_styles)) {

			echo edgtf_quadric_dynamic_css('.edgtf-side-menu-title h4, .edgtf-side-menu-title h5', $title_styles);

		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_title_styles');

}

if (!function_exists('edgtf_quadric_side_area_text_styles')) {

	function edgtf_quadric_side_area_text_styles()
	{
		$text_styles = array();

		if (edgtf_quadric_options()->getOptionValue('side_area_text_google_fonts') !== '-1') {
			$text_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('side_area_text_google_fonts')) . ', sans-serif';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_fontsize') !== '') {
			$text_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_text_fontsize')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_lineheight') !== '') {
			$text_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_text_lineheight')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_letterspacing') !== '') {
			$text_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('side_area_text_letterspacing')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_fontweight') !== '') {
			$text_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('side_area_text_fontweight');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_fontstyle') !== '') {
			$text_styles['font-style'] = edgtf_quadric_options()->getOptionValue('side_area_text_fontstyle');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_texttransform') !== '') {
			$text_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('side_area_text_texttransform');
		}

		if (edgtf_quadric_options()->getOptionValue('side_area_text_color') !== '') {
			$text_styles['color'] = edgtf_quadric_options()->getOptionValue('side_area_text_color');
		}

		if (!empty($text_styles)) {

			echo edgtf_quadric_dynamic_css('.edgtf-side-menu .widget, .edgtf-side-menu .widget.widget_search form, .edgtf-side-menu .widget.widget_search form input[type="text"], .edgtf-side-menu .widget.widget_search form input[type="submit"], .edgtf-side-menu .widget h6, .edgtf-side-menu .widget h6 a, .edgtf-side-menu .widget p, .edgtf-side-menu .widget li a, .edgtf-side-menu .widget.widget_rss li a.rsswidget, .edgtf-side-menu #wp-calendar caption,.edgtf-side-menu .widget li, .edgtf-side-menu h3, .edgtf-side-menu .widget.widget_archive select, .edgtf-side-menu .widget.widget_categories select, .edgtf-side-menu .widget.widget_text select, .edgtf-side-menu .widget.widget_search form input[type="submit"], .edgtf-side-menu #wp-calendar th, .edgtf-side-menu #wp-calendar td, .edgtf-side-menu .edgtf-social-icon-holder i.simple_social', $text_styles);

		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_text_styles');

}

if (!function_exists('edgtf_quadric_side_area_link_styles')) {

	function edgtf_quadric_side_area_link_styles()
	{
		$link_styles = array();

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_font_family') !== '-1') {
			$link_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('sidearea_link_font_family')) . ',sans-serif';
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_font_size') !== '') {
			$link_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('sidearea_link_font_size')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_line_height') !== '') {
			$link_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('sidearea_link_line_height')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_letter_spacing') !== '') {
			$link_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('sidearea_link_letter_spacing')) . 'px';
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_font_weight') !== '') {
			$link_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('sidearea_link_font_weight');
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_font_style') !== '') {
			$link_styles['font-style'] = edgtf_quadric_options()->getOptionValue('sidearea_link_font_style');
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_text_transform') !== '') {
			$link_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('sidearea_link_text_transform');
		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_color') !== '') {
			$link_styles['color'] = edgtf_quadric_options()->getOptionValue('sidearea_link_color');
		}

		if (!empty($link_styles)) {

			echo edgtf_quadric_dynamic_css('.edgtf-side-menu .widget li a, .edgtf-side-menu .widget a:not(.qbutton)', $link_styles);

		}

		if (edgtf_quadric_options()->getOptionValue('sidearea_link_hover_color') !== '') {
			echo edgtf_quadric_dynamic_css('.edgtf-side-menu .widget a:hover, .edgtf-side-menu .widget li:hover, .edgtf-side-menu .widget li:hover>a', array(
				'color' => edgtf_quadric_options()->getOptionValue('sidearea_link_hover_color')
			));
		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_link_styles');

}

if (!function_exists('edgtf_quadric_side_area_border_styles')) {

	function edgtf_quadric_side_area_border_styles()
	{

		if (edgtf_quadric_options()->getOptionValue('side_area_enable_bottom_border') == 'yes') {

			if (edgtf_quadric_options()->getOptionValue('side_area_bottom_border_color') !== '') {

				echo edgtf_quadric_dynamic_css('.edgtf-side-menu .widget', array(
					'border-bottom:' => '1px solid ' . edgtf_quadric_options()->getOptionValue('side_area_bottom_border_color'),
					'margin-bottom:' => '10px',
					'padding-bottom:' => '10px',
				));

			}

		}

	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_side_area_border_styles');

}