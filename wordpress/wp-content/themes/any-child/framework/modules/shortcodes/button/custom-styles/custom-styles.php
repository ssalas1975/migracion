<?php

if(!function_exists('any_edge_button_typography_styles')) {
    /**
     * Typography styles for all button types
     */
    function any_edge_button_typography_styles() {
        $selector = '.edgtf-btn';
        $styles = array();

        $font_family = any_edge_options()->getOptionValue('button_font_family');
        if(any_edge_is_font_option_valid($font_family)) {
            $styles['font-family'] = any_edge_get_font_option_val($font_family);
        }

        $text_transform = any_edge_options()->getOptionValue('button_text_transform');
        if(!empty($text_transform)) {
            $styles['text-transform'] = $text_transform;
        }

        $font_style = any_edge_options()->getOptionValue('button_font_style');
        if(!empty($font_style)) {
            $styles['font-style'] = $font_style;
        }

        $letter_spacing = any_edge_options()->getOptionValue('button_letter_spacing');
        if($letter_spacing !== '') {
            $styles['letter-spacing'] = any_edge_filter_px($letter_spacing).'px';
        }

        $font_weight = any_edge_options()->getOptionValue('button_font_weight');
        if(!empty($font_weight)) {
            $styles['font-weight'] = $font_weight;
        }

        echo any_edge_dynamic_css($selector, $styles);
    }

    add_action('any_edge_style_dynamic', 'any_edge_button_typography_styles');
}

if(!function_exists('any_edge_button_outline_styles')) {
    /**
     * Generate styles for outline button
     */
    function any_edge_button_outline_styles() {
        //outline styles
        $outline_styles   = array();
        $outline_selector = '.edgtf-btn.edgtf-btn-outline';

        if(any_edge_options()->getOptionValue('btn_outline_text_color')) {
            $outline_styles['color'] = any_edge_options()->getOptionValue('btn_outline_text_color');
        }

        if(any_edge_options()->getOptionValue('btn_outline_border_color')) {
            $outline_styles['border-color'] = any_edge_options()->getOptionValue('btn_outline_border_color');
        }

        echo any_edge_dynamic_css($outline_selector, $outline_styles);

        //outline hover styles
        if(any_edge_options()->getOptionValue('btn_outline_hover_text_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => any_edge_options()->getOptionValue('btn_outline_hover_text_color').'!important')
            );
        }

        if(any_edge_options()->getOptionValue('btn_outline_hover_bg_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => any_edge_options()->getOptionValue('btn_outline_hover_bg_color').'!important')
            );
        }

        if(any_edge_options()->getOptionValue('btn_outline_hover_border_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-border-hover):hover',
                array('border-color' => any_edge_options()->getOptionValue('btn_outline_hover_border_color').'!important')
            );
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_button_outline_styles');
}

if(!function_exists('any_edge_button_solid_styles')) {
    /**
     * Generate styles for solid type buttons
     */
    function any_edge_button_solid_styles() {
        //solid styles
        $solid_selector = '.edgtf-btn.edgtf-btn-solid';
        $solid_styles = array();

        if(any_edge_options()->getOptionValue('btn_solid_text_color')) {
            $solid_styles['color'] = any_edge_options()->getOptionValue('btn_solid_text_color');
        }

        if(any_edge_options()->getOptionValue('btn_solid_border_color')) {
            $solid_styles['border-color'] = any_edge_options()->getOptionValue('btn_solid_border_color');
            $solid_styles['border-width'] = '1px';
            $solid_styles['border-style'] = 'solid';
        }

        if(any_edge_options()->getOptionValue('btn_solid_bg_color')) {
            $solid_styles['background-color'] = any_edge_options()->getOptionValue('btn_solid_bg_color');
        }

        //solid hover styles
        if(any_edge_options()->getOptionValue('btn_solid_hover_text_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => any_edge_options()->getOptionValue('btn_solid_hover_text_color').'!important')
            );
        }

        if(any_edge_options()->getOptionValue('btn_solid_hover_bg_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => any_edge_options()->getOptionValue('btn_solid_hover_bg_color').'!important')
            );
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:after',
                array('display' => 'none')
            );
        }

        if(any_edge_options()->getOptionValue('btn_solid_hover_border_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-border-hover):hover',
                array('border-color' => any_edge_options()->getOptionValue('btn_solid_hover_border_color').'!important')
            );

			if (!isset($solid_styles['border-width'])){
				$solid_styles['border-width'] = '1px';
				$solid_styles['border-style'] = 'solid';
				$solid_styles['border-color'] = 'transparent';
			}
        }

        echo any_edge_dynamic_css($solid_selector, $solid_styles);
    }

    add_action('any_edge_style_dynamic', 'any_edge_button_solid_styles');
}


if(!function_exists('any_edge_button_solid_white_styles')) {
    /**
     * Generate styles for solid white type buttons
     */
    function any_edge_button_solid_white_styles() {
        //solid white styles
        $solid_white_selector = '.edgtf-btn.edgtf-btn-solid-white';
        $solid_white_styles = array();

        if(any_edge_options()->getOptionValue('btn_solid_white_text_color')) {
            $solid_white_styles['color'] = any_edge_options()->getOptionValue('btn_solid_white_text_color');
        }

        if(any_edge_options()->getOptionValue('btn_solid_white_border_color')) {
            $solid_white_styles['border-color'] = any_edge_options()->getOptionValue('btn_solid_white_border_color');
            $solid_white_styles['border-width'] = '1px';
            $solid_white_styles['border-style'] = 'solid';
        }

        if(any_edge_options()->getOptionValue('btn_solid_white_bg_color')) {
            $solid_white_styles['background-color'] = any_edge_options()->getOptionValue('btn_solid_white_bg_color');
        }

        //solid hover styles
        if(any_edge_options()->getOptionValue('btn_solid_white_hover_text_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid-white:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => any_edge_options()->getOptionValue('btn_solid_white_hover_text_color').'!important')
            );
        }

        if(any_edge_options()->getOptionValue('btn_solid_white_hover_bg_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid-white:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => any_edge_options()->getOptionValue('btn_solid_white_hover_bg_color').'!important')
            );
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid-white:after',
                array('display' => 'none')
            );
        }

        if(any_edge_options()->getOptionValue('btn_solid_white_hover_border_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid-white:not(.edgtf-btn-custom-border-hover):hover',
                array('border-color' => any_edge_options()->getOptionValue('btn_solid_white_hover_border_color').'!important')
            );

			if (!isset($solid_white_styles['border-width'])){
				$solid_white_styles['border-width'] = '1px';
				$solid_white_styles['border-style'] = 'solid';
				$solid_white_styles['border-color'] = 'transparent';
			}
        }

        echo any_edge_dynamic_css($solid_white_selector, $solid_white_styles);
    }

    add_action('any_edge_style_dynamic', 'any_edge_button_solid_white_styles');
}

if(!function_exists('any_edge_button_transparent_styles')) {
    /**
     * Generate styles for transparent type buttons
     */
    function any_edge_button_transparent_styles() {

        if(any_edge_options()->getOptionValue('btn_transparent_text_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-transparent',
                array('color' => any_edge_options()->getOptionValue('btn_transparent_text_color'))
            );
        }

        //solid hover styles
        if(any_edge_options()->getOptionValue('btn_transparent_hover_text_color')) {
            echo any_edge_dynamic_css(
                '.edgtf-btn.edgtf-btn-transparent:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => any_edge_options()->getOptionValue('btn_transparent_hover_text_color').'!important')
            );
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_button_transparent_styles');
}