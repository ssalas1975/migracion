<?php

if(!function_exists('any_edge_is_responsive_on')) {
    /**
     * Checks whether responsive mode is enabled in theme options
     * @return bool
     */
    function any_edge_is_responsive_on() {
        return any_edge_options()->getOptionValue('responsiveness') !== 'no';
    }
}