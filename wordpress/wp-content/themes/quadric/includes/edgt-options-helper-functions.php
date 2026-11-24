<?php

if(!function_exists('edgtf_quadric_is_responsive_on')) {
    /**
     * Checks whether responsive mode is enabled in theme options
     * @return bool
     */
    function edgtf_quadric_is_responsive_on() {
        return edgtf_quadric_options()->getOptionValue('responsiveness') !== 'no';
    }
}

if(!function_exists('edgtf_quadric_is_seo_enabled')) {
    /**
     * Checks if SEO is enabled in theme options
     * @return bool
     */
    function edgtf_quadric_is_seo_enabled() {
        return edgtf_quadric_options()->getOptionValue('disable_seo') == 'no';
    }
}