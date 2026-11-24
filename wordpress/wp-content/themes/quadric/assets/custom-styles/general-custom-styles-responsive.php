<?php
if(!function_exists('edgtf_quadric_design_responsive_styles')) {
    /**
     * Generates general responsive custom styles
     */
    function edgtf_quadric_design_responsive_styles() {

        $parallax_style = array();
        if (edgtf_quadric_options()->getOptionValue('parallax_min_height') !== '') {
            $parallax_style['height'] = 'auto !important';
			$parallax_min_height = edgtf_quadric_options()->getOptionValue('parallax_min_height');
			$parallax_style['min-height'] = edgtf_quadric_filter_px($parallax_min_height) . 'px';

		}

		echo edgtf_quadric_dynamic_css('.edgtf-section.edgtf-parallax-section-holder, .touch .edgtf-parallax-section-holder.edgtf-parallax-section-holder-touch-disabled', $parallax_style);
    }

    add_action('edgtf_quadric_style_dynamic_responsive_480', 'edgtf_quadric_design_responsive_styles');
    add_action('edgtf_quadric_style_dynamic_responsive_480_768', 'edgtf_quadric_design_responsive_styles');
}