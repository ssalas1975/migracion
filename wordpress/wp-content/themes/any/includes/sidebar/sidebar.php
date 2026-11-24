<?php

if(!function_exists('any_edge_register_sidebars')) {
    /**
     * Function that registers theme's sidebars
     */
    function any_edge_register_sidebars() {

        register_sidebar(array(
            'name' => esc_html__('Sidebar','any'),
            'id' => 'sidebar',
            'description' =>  esc_html__('Default Sidebar','any'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="edgtf-widget-title">',
            'after_title' => '</h6>'
        ));

    }

    add_action('widgets_init', 'any_edge_register_sidebars');
}

if(!function_exists('any_edge_add_support_custom_sidebar')) {
    /**
     * Function that adds theme support for custom sidebars. It also creates AnyEdgeSidebar object
     */
    function any_edge_add_support_custom_sidebar() {
        add_theme_support('AnyEdgeSidebar');
        if (get_theme_support('AnyEdgeSidebar')) new AnyEdgeSidebar();
    }

    add_action('after_setup_theme', 'any_edge_add_support_custom_sidebar');
}
