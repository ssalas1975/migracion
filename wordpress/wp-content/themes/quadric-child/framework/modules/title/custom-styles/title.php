<?php

if (!function_exists('edgtf_quadric_title_area_typography_style')) {

    function edgtf_quadric_title_area_typography_style(){

        $title_styles = array();

        if(edgtf_quadric_options()->getOptionValue('page_title_color') !== '') {
            $title_styles['color'] = edgtf_quadric_options()->getOptionValue('page_title_color');
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_google_fonts') !== '-1') {
            $title_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('page_title_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_fontsize') !== '') {
            $title_styles['font-size'] = edgtf_quadric_options()->getOptionValue('page_title_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_lineheight') !== '') {
            $title_styles['line-height'] = edgtf_quadric_options()->getOptionValue('page_title_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_texttransform') !== '') {
            $title_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('page_title_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_fontstyle') !== '') {
            $title_styles['font-style'] = edgtf_quadric_options()->getOptionValue('page_title_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_fontweight') !== '') {
            $title_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('page_title_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('page_title_letter_spacing') !== '') {
            $title_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('page_title_letter_spacing').'px';
        }

        $title_selector = array(
            '.edgtf-title .edgtf-title-holder h1'
        );

        echo edgtf_quadric_dynamic_css($title_selector, $title_styles);


        $subtitle_styles = array();

        if(edgtf_quadric_options()->getOptionValue('page_subtitle_color') !== '') {
            $subtitle_styles['color'] = edgtf_quadric_options()->getOptionValue('page_subtitle_color');
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_google_fonts') !== '-1') {
            $subtitle_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('page_subtitle_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_fontsize') !== '') {
            $subtitle_styles['font-size'] = edgtf_quadric_options()->getOptionValue('page_subtitle_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_lineheight') !== '') {
            $subtitle_styles['line-height'] = edgtf_quadric_options()->getOptionValue('page_subtitle_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_texttransform') !== '') {
            $subtitle_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('page_subtitle_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_fontstyle') !== '') {
            $subtitle_styles['font-style'] = edgtf_quadric_options()->getOptionValue('page_subtitle_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_fontweight') !== '') {
            $subtitle_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('page_subtitle_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('page_subtitle_letter_spacing') !== '') {
            $subtitle_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('page_subtitle_letter_spacing').'px';
        }

        $subtitle_selector = array(
            '.edgtf-title .edgtf-title-holder .edgtf-subtitle'
        );

        echo edgtf_quadric_dynamic_css($subtitle_selector, $subtitle_styles);


        $breadcrumb_styles = array();

        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_color') !== '') {
            $breadcrumb_styles['color'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_color');
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_google_fonts') !== '-1') {
            $breadcrumb_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('page_breadcrumb_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontsize') !== '') {
            $breadcrumb_styles['font-size'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_lineheight') !== '') {
            $breadcrumb_styles['line-height'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_texttransform') !== '') {
            $breadcrumb_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontstyle') !== '') {
            $breadcrumb_styles['font-style'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontweight') !== '') {
            $breadcrumb_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_letter_spacing') !== '') {
            $breadcrumb_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_letter_spacing').'px';
        }

        $breadcrumb_selector = array(
            '.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs a, .edgtf-title .edgtf-title-holder .edgtf-breadcrumbs span'
        );

        echo edgtf_quadric_dynamic_css($breadcrumb_selector, $breadcrumb_styles);

        $breadcrumb_selector_styles = array();
        if(edgtf_quadric_options()->getOptionValue('page_breadcrumb_hovercolor') !== '') {
            $breadcrumb_selector_styles['color'] = edgtf_quadric_options()->getOptionValue('page_breadcrumb_hovercolor');
        }

        $breadcrumb_hover_selector = array(
            '.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs a:hover'
        );

        echo edgtf_quadric_dynamic_css($breadcrumb_hover_selector, $breadcrumb_selector_styles);

    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_title_area_typography_style');

}

if (!function_exists('edgtf_quadric_title_area_background_style')) {

	function edgtf_quadric_title_area_background_style(){

		$title_background_styles = array();

		if(edgtf_quadric_options()->getOptionValue('title_area_background_pattern') !== '') {
			$title_background_styles['background-image'] = 'url(' . edgtf_quadric_options()->getOptionValue('title_area_background_pattern').')';
			$title_background_styles['background-repeat'] = 'repeat';
		}


		echo edgtf_quadric_dynamic_css('.edgtf-title', $title_background_styles);


	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_title_area_background_style');

}
