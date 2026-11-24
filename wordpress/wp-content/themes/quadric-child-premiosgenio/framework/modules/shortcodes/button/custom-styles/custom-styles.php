<?php

if(!function_exists('edgtf_quadric_button_typography_styles')) {
    /**
     * Typography styles for all button types
     */
    function edgtf_quadric_button_typography_styles() {
        $selector = '.edgtf-btn';
        $styles = array();

        $font_family = edgtf_quadric_options()->getOptionValue('button_font_family');
        if(edgtf_quadric_is_font_option_valid($font_family)) {
            $styles['font-family'] = edgtf_quadric_get_font_option_val($font_family);
        }

        $text_transform = edgtf_quadric_options()->getOptionValue('button_text_transform');
        if(!empty($text_transform)) {
            $styles['text-transform'] = $text_transform;
        }

        $font_style = edgtf_quadric_options()->getOptionValue('button_font_style');
        if(!empty($font_style)) {
            $styles['font-style'] = $font_style;
        }

        $letter_spacing = edgtf_quadric_options()->getOptionValue('button_letter_spacing');
        if($letter_spacing !== '') {
            $styles['letter-spacing'] = edgtf_quadric_filter_px($letter_spacing).'px';
        }

        $font_weight = edgtf_quadric_options()->getOptionValue('button_font_weight');
        if(!empty($font_weight)) {
            $styles['font-weight'] = $font_weight;
        }

        echo edgtf_quadric_dynamic_css($selector, $styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_button_typography_styles');
}

if(!function_exists('edgtf_quadric_button_outline_styles')) {
    /**
     * Generate styles for outline button
     */
    function edgtf_quadric_button_outline_styles() {
        //outline styles
        $outline_styles   = array();
        $outline_selector = '.edgtf-btn.edgtf-btn-outline';

        if(edgtf_quadric_options()->getOptionValue('btn_outline_text_color')) {
            $outline_styles['color'] = edgtf_quadric_options()->getOptionValue('btn_outline_text_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_border_color')) {
            $outline_styles['border-color'] = edgtf_quadric_options()->getOptionValue('btn_outline_border_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_text_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline.edgtf-btn-icon .edgtf-btn-text',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_text_border_color'))
            );
        }

        echo edgtf_quadric_dynamic_css($outline_selector, $outline_styles);

        //outline hover styles
        if(edgtf_quadric_options()->getOptionValue('btn_outline_hover_text_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => edgtf_quadric_options()->getOptionValue('btn_outline_hover_text_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_hover_bg_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => edgtf_quadric_options()->getOptionValue('btn_outline_hover_bg_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_hover_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-border-hover):hover',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_hover_border_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_text_hover_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-border-hover):hover .edgtf-btn-text',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_text_hover_border_color').'!important')
            );
        }

    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_button_outline_styles');
}

if(!function_exists('edgtf_quadric_button_outline_white_styles')) {
    /**
     * Generate styles for outline white button
     */
    function edgtf_quadric_button_outline_white_styles() {
        //outline styles
        $outline_white_styles   = array();
        $outline_white_selector = '.edgtf-btn.edgtf-btn-outline-white';

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_text_color')) {
            $outline_white_styles['color'] = edgtf_quadric_options()->getOptionValue('btn_outline_white_text_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_border_color')) {
            $outline_white_styles['border-color'] = edgtf_quadric_options()->getOptionValue('btn_outline_white_border_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_text_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline-white.edgtf-btn-icon .edgtf-btn-text',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_white_text_border_color'))
            );
        }

        echo edgtf_quadric_dynamic_css($outline_white_selector, $outline_white_styles);

        //outline hover styles
        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_text_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline-white:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_text_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_bg_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline-white:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_bg_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline-white:not(.edgtf-btn-custom-border-hover):hover',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_white_hover_border_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_outline_white_text_hover_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-outline-white:not(.edgtf-btn-custom-border-hover):hover .edgtf-btn-text',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_outline_white_text_hover_border_color').'!important')
            );
        }

    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_button_outline_white_styles');
}

if(!function_exists('edgtf_quadric_button_solid_styles')) {
    /**
     * Generate styles for solid type buttons
     */
    function edgtf_quadric_button_solid_styles() {
        //solid styles
        $solid_selector = '.edgtf-btn.edgtf-btn-solid';
        $solid_styles = array();

        if(edgtf_quadric_options()->getOptionValue('btn_solid_text_color')) {
            $solid_styles['color'] = edgtf_quadric_options()->getOptionValue('btn_solid_text_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_solid_border_color')) {
            $solid_styles['border-color'] = edgtf_quadric_options()->getOptionValue('btn_solid_border_color');
        }

        if(edgtf_quadric_options()->getOptionValue('btn_solid_bg_color')) {
            $solid_styles['background-color'] = edgtf_quadric_options()->getOptionValue('btn_solid_bg_color');
        }

        echo edgtf_quadric_dynamic_css($solid_selector, $solid_styles);

        //solid hover styles
        if(edgtf_quadric_options()->getOptionValue('btn_solid_hover_text_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-color):hover',
                array('color' => edgtf_quadric_options()->getOptionValue('btn_solid_hover_text_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_solid_hover_bg_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-bg):hover',
                array('background-color' => edgtf_quadric_options()->getOptionValue('btn_solid_hover_bg_color').'!important')
            );
        }

        if(edgtf_quadric_options()->getOptionValue('btn_solid_hover_border_color')) {
            echo edgtf_quadric_dynamic_css(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-bg):hover',
                array('border-color' => edgtf_quadric_options()->getOptionValue('btn_solid_hover_border_color').'!important')
            );
        }
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_button_solid_styles');
}