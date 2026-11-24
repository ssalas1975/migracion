<?php
if(!function_exists('edgtf_quadric_tabs_typography_styles')){
	function edgtf_quadric_tabs_typography_styles(){
		$selector = '.edgtf-tabs .edgtf-tabs-nav li a';
		$tabs_tipography_array = array();
		$font_family = edgtf_quadric_options()->getOptionValue('tabs_font_family');

		if(edgtf_quadric_is_font_option_valid($font_family)){
			$tabs_tipography_array['font-family'] = edgtf_quadric_get_font_option_val($font_family);
		}
		
		$text_transform = edgtf_quadric_options()->getOptionValue('tabs_text_transform');
        if(!empty($text_transform)) {
			$tabs_tipography_array['text-transform'] = $text_transform;
        }

        $font_style = edgtf_quadric_options()->getOptionValue('tabs_font_style');
        if(!empty($font_style)) {
			$tabs_tipography_array['font-style'] = $font_style;
        }

        $letter_spacing = edgtf_quadric_options()->getOptionValue('tabs_letter_spacing');
        if($letter_spacing !== '') {
			$tabs_tipography_array['letter-spacing'] = edgtf_quadric_filter_px($letter_spacing).'px';
        }

        $font_weight = edgtf_quadric_options()->getOptionValue('tabs_font_weight');
        if(!empty($font_weight)) {
			$tabs_tipography_array['font-weight'] = $font_weight;
        }

        echo edgtf_quadric_dynamic_css($selector, $tabs_tipography_array);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_tabs_typography_styles');
}

if(!function_exists('edgtf_quadric_tabs_inital_color_styles')){
	function edgtf_quadric_tabs_inital_color_styles(){
		$selector = '.edgtf-tabs .edgtf-tabs-nav li a';
		$styles = array();

		if(edgtf_quadric_options()->getOptionValue('tabs_color')) {
            $styles['color'] = edgtf_quadric_options()->getOptionValue('tabs_color');
        }

		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_tabs_inital_color_styles');
}
if(!function_exists('edgtf_quadric_tabs_active_color_styles')){
	function edgtf_quadric_tabs_active_color_styles(){
		$selector = array(
			'.edgtf-tabs .edgtf-tabs-nav li.ui-state-active a',
			'.edgtf-tabs .edgtf-tabs-nav li.ui-state-hover a',
			'.edgtf-tabs.edgtf-horizontal .edgtf-tabs-nav',
			'.edgtf-tabs.edgtf-vertical .edgtf-tabs-nav'
		);
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('tabs_color_active')) {
            $styles['color'] = edgtf_quadric_options()->getOptionValue('tabs_color_active');
        }
		if(edgtf_quadric_options()->getOptionValue('tabs_border_color_active')) {
            $styles['border-color'] = edgtf_quadric_options()->getOptionValue('tabs_border_color_active');
        }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_tabs_active_color_styles');
}