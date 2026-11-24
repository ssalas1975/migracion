<?php

if(!function_exists('edgtf_quadric_register_top_header_areas')) {
    /**
     * Registers widget areas for top header bar when it is enabled
     */
    function edgtf_quadric_register_top_header_areas() {
        $top_bar_layout  = edgtf_quadric_options()->getOptionValue('top_bar_layout');
        $top_bar_enabled = edgtf_quadric_options()->getOptionValue('top_bar');

        if($top_bar_enabled == 'yes') {
            register_sidebar(array(
                'name'          => esc_html__('Top Bar Left', 'quadric'),
                'id'            => 'edgtf-top-bar-left',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-top-bar-widget">',
                'after_widget'  => '</div>'
            ));

            //register this widget area only if top bar layout is three columns
            if($top_bar_layout === 'three-columns') {
                register_sidebar(array(
                    'name'          => esc_html__('Top Bar Center', 'quadric'),
                    'id'            => 'edgtf-top-bar-center',
                    'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-top-bar-widget">',
                    'after_widget'  => '</div>'
                ));
            }

            register_sidebar(array(
                'name'          => esc_html__('Top Bar Right', 'quadric'),
                'id'            => 'edgtf-top-bar-right',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-top-bar-widget">',
                'after_widget'  => '</div>'
            ));
        }
    }

    add_action('widgets_init', 'edgtf_quadric_register_top_header_areas');
}

if(!function_exists('edgtf_quadric_header_standard_widget_areas')) {
    /**
     * Registers widget areas for standard header type
     */
    function edgtf_quadric_header_standard_widget_areas() {
        if(edgtf_quadric_options()->getOptionValue('header_type') == 'header-standard') {
            register_sidebar(array(
                'name'          => esc_html__('Right From Main Menu', 'quadric'),
                'id'            => 'edgtf-right-from-main-menu',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-right-from-main-menu-widget">',
                'after_widget'  => '</div>',
                'description'   => esc_html__('Widgets added here will appear on the right hand side from the main menu', 'quadric')
            ));
        }
    }

    add_action('widgets_init', 'edgtf_quadric_header_standard_widget_areas');
}

if(!function_exists('edgtf_quadric_header_vertical_widget_areas')) {
    /**
     * Registers widget areas for vertical header
     */
    function edgtf_quadric_header_vertical_widget_areas() {
        if(edgtf_quadric_options()->getOptionValue('header_type') == 'header-vertical') {
            register_sidebar(array(
                'name'          => esc_html__('Vertical Area', 'quadric'),
                'id'            => 'edgtf-vertical-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-vertical-area-widget">',
                'after_widget'  => '</div>',
                'description'   => esc_html__('Widgets added here will appear on the bottom of vertical menu', 'quadric')
            ));
        }
    }

    add_action('widgets_init', 'edgtf_quadric_header_vertical_widget_areas');
}


if(!function_exists('edgtf_quadric_register_mobile_header_areas')) {
    /**
     * Registers widget areas for mobile header
     */
    function edgtf_quadric_register_mobile_header_areas() {
        if(edgtf_quadric_is_responsive_on()) {
            register_sidebar(array(
                'name'          => esc_html__('Right From Mobile Logo', 'quadric'),
                'id'            => 'edgtf-right-from-mobile-logo',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-right-from-mobile-logo">',
                'after_widget'  => '</div>',
                'description'   => esc_html__('Widgets added here will appear on the right hand side from the mobile logo', 'quadric')
            ));
        }
    }

    add_action('widgets_init', 'edgtf_quadric_register_mobile_header_areas');
}

if(!function_exists('edgtf_quadric_register_sticky_header_areas')) {
    /**
     * Registers widget area for sticky header
     */
    function edgtf_quadric_register_sticky_header_areas() {
        if(in_array(edgtf_quadric_options()->getOptionValue('header_behaviour'), array('sticky-header-on-scroll-up','sticky-header-on-scroll-down-up'))) {
            register_sidebar(array(
                'name'          => esc_html__('Sticky Right', 'quadric'),
                'id'            => 'edgtf-sticky-right',
                'before_widget' => '<div id="%1$s" class="widget %2$s edgtf-sticky-right">',
                'after_widget'  => '</div>',
                'description'   => esc_html__('Widgets added here will appear on the right hand side in sticky menu', 'quadric')
            ));
        }
    }

    add_action('widgets_init', 'edgtf_quadric_register_sticky_header_areas');
}