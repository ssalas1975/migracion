<?php 

if(!function_exists('edgtf_quadric_accordions_typography_styles')){
	function edgtf_quadric_accordions_typography_styles(){
		$selector = '.edgtf-accordion-holder .edgtf-title-holder';
		$styles = array();
		
		$font_family = edgtf_quadric_options()->getOptionValue('accordions_font_family');
		if(edgtf_quadric_is_font_option_valid($font_family)){
			$styles['font-family'] = edgtf_quadric_get_font_option_val($font_family);
		}
		
		$text_transform = edgtf_quadric_options()->getOptionValue('accordions_text_transform');
       if(!empty($text_transform)) {
           $styles['text-transform'] = $text_transform;
       }

       $font_style = edgtf_quadric_options()->getOptionValue('accordions_font_style');
       if(!empty($font_style)) {
           $styles['font-style'] = $font_style;
       }

       $letter_spacing = edgtf_quadric_options()->getOptionValue('accordions_letter_spacing');
       if($letter_spacing !== '') {
           $styles['letter-spacing'] = edgtf_quadric_filter_px($letter_spacing).'px';
       }

       $font_weight = edgtf_quadric_options()->getOptionValue('accordions_font_weight');
       if(!empty($font_weight)) {
           $styles['font-weight'] = $font_weight;
       }

       echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_accordions_typography_styles');
}

if(!function_exists('edgtf_quadric_accordions_inital_title_color_styles')){
	function edgtf_quadric_accordions_inital_title_color_styles(){
		$selector = '.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder';
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('accordions_title_color')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('accordions_title_color');
       }
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_accordions_inital_title_color_styles');
}

if(!function_exists('edgtf_quadric_accordions_active_title_color_styles')){
	
	function edgtf_quadric_accordions_active_title_color_styles(){
		$selector = array(
			'.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder.ui-state-active',
			'.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder.ui-state-hover'
		);
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('accordions_title_color_active')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('accordions_title_color_active');
       }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_accordions_active_title_color_styles');
}
if(!function_exists('edgtf_quadric_accordions_inital_icon_color_styles')){
	
	function edgtf_quadric_accordions_inital_icon_color_styles(){
		$selector = '.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder .edgtf-accordion-icon';
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('accordions_icon_color')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('accordions_icon_color');
       }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_accordions_inital_icon_color_styles');
}
if(!function_exists('edgtf_quadric_accordions_active_icon_color_styles')){
	
	function edgtf_quadric_accordions_active_icon_color_styles(){
		$selector = array(
			'.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder.ui-state-active  .edgtf-accordion-icon',
			'.edgtf-accordion-holder.edgtf-initial .edgtf-title-holder.ui-state-hover  .edgtf-accordion-icon'
		);
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('accordions_icon_color_active')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('accordions_icon_color_active');
       }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_accordions_active_icon_color_styles');
}

if(!function_exists('edgtf_quadric_boxed_accordions_inital_color_styles')){
	function edgtf_quadric_boxed_accordions_inital_color_styles(){
		$selector = '.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder';
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_color')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_color');
           echo edgtf_quadric_dynamic_css('.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder .edgtf-accordion-mark', array('color' => edgtf_quadric_options()->getOptionValue('boxed_accordions_color')));
       }
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color')) {
           $styles['background-color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color');
           echo edgtf_quadric_dynamic_css('.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder .edgtf-accordion-mark', array('background-color' => edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color')));
       }
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_border_color')) {
           $styles['border-color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_border_color');
       }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_boxed_accordions_inital_color_styles');
}
if(!function_exists('edgtf_quadric_boxed_accordions_active_color_styles')){

	function edgtf_quadric_boxed_accordions_active_color_styles(){
		$selector = array(
			'.edgtf-accordion-holder.edgtf-boxed.ui-accordion .edgtf-title-holder.ui-state-active',
			'.edgtf-accordion-holder.edgtf-boxed.ui-accordion .edgtf-title-holder.ui-state-hover'
		);
		$selector_icons = array(
			'.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder.ui-state-active .edgtf-accordion-mark',
			'.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder.ui-state-hover .edgtf-accordion-mark'
		);
		$styles = array();
		
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_color_active')) {
           $styles['color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_color_active');
           echo edgtf_quadric_dynamic_css($selector_icons, array('color' => edgtf_quadric_options()->getOptionValue('boxed_accordions_color_active')));
       }
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color_active')) {
           $styles['background-color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color_active');
           echo edgtf_quadric_dynamic_css($selector_icons, array('background-color' => edgtf_quadric_options()->getOptionValue('boxed_accordions_back_color_active')));
       }
		if(edgtf_quadric_options()->getOptionValue('boxed_accordions_border_color_active')) {
           $styles['border-color'] = edgtf_quadric_options()->getOptionValue('boxed_accordions_border_color_active');
       }
		
		echo edgtf_quadric_dynamic_css($selector, $styles);
	}
	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_boxed_accordions_active_color_styles');
}