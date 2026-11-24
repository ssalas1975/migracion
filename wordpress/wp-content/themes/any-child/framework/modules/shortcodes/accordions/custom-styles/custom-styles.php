<?php 

if(!function_exists('any_edge_accordions_typography_styles')){
	function any_edge_accordions_typography_styles(){
		$selector = '.edgtf-accordion-holder .edgtf-title-holder';
		$styles = array();
		
		$font_family = any_edge_options()->getOptionValue('accordions_font_family');
		if(any_edge_is_font_option_valid($font_family)){
			$styles['font-family'] = any_edge_get_font_option_val($font_family);
		}
		
		$text_transform = any_edge_options()->getOptionValue('accordions_text_transform');
       if(!empty($text_transform)) {
           $styles['text-transform'] = $text_transform;
       }

       $font_style = any_edge_options()->getOptionValue('accordions_font_style');
       if(!empty($font_style)) {
           $styles['font-style'] = $font_style;
       }

       $letter_spacing = any_edge_options()->getOptionValue('accordions_letter_spacing');
       if($letter_spacing !== '') {
           $styles['letter-spacing'] = any_edge_filter_px($letter_spacing).'px';
       }

       $font_weight = any_edge_options()->getOptionValue('accordions_font_weight');
       if(!empty($font_weight)) {
           $styles['font-weight'] = $font_weight;
       }

       echo any_edge_dynamic_css($selector, $styles);
	}
	add_action('any_edge_style_dynamic', 'any_edge_accordions_typography_styles');
}

if(!function_exists('any_edge_accordions_initial_color_styles')){

	function any_edge_accordions_initial_color_styles(){

		$selector = '.edgtf-accordion-holder .edgtf-title-holder';
		$styles = array();
		
		if(any_edge_options()->getOptionValue('accordions_title_color')) {
			$styles['color'] = any_edge_options()->getOptionValue('accordions_title_color');
		}
		
		if(any_edge_options()->getOptionValue('accordions_back_color')) {
			$styles['background-color'] = any_edge_options()->getOptionValue('accordions_back_color');
		}

		echo any_edge_dynamic_css($selector, $styles);
	}

	add_action('any_edge_style_dynamic', 'any_edge_accordions_initial_color_styles');
}

if(!function_exists('any_edge_accordions_active_color_styles')){
	
	function any_edge_accordions_active_color_styles(){
		$selector = array(
			'.edgtf-accordion-holder .edgtf-title-holder.ui-state-active',
			'.edgtf-accordion-holder .edgtf-title-holder.ui-state-hover'
		);
		$styles = array();
		
		if(any_edge_options()->getOptionValue('accordions_title_color_active')) {
			$styles['color'] = any_edge_options()->getOptionValue('accordions_title_color_active');
		}
		
		if(any_edge_options()->getOptionValue('accordions_back_color_active')) {
			$styles['background-color'] = any_edge_options()->getOptionValue('accordions_back_color_active');
		}

		echo any_edge_dynamic_css($selector, $styles);
	}

	add_action('any_edge_style_dynamic', 'any_edge_accordions_active_color_styles');
}