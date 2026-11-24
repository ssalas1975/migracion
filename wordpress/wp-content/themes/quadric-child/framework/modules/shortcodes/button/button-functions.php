<?php

if(!function_exists('edgtf_quadric_get_button_html')) {
    /**
     * Calls button shortcode with given parameters and returns it's output
     * @param $params
     *
     * @return mixed|string
     */
    function edgtf_quadric_get_button_html($params) {
        $button_html = edgtf_quadric_execute_shortcode('edgtf_button', $params);
        $button_html = str_replace("\n", '', $button_html);
        return $button_html;
    }
}