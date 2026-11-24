<?php

if(!function_exists('edgtf_quadric_register_sidebars')) {
    /**
     * Function that registers theme's sidebars
     */
    function edgtf_quadric_register_sidebars() {

        register_sidebar(array(
            'name' => 'Sidebar',
            'id' => 'sidebar',
            'description' => 'Default Sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6>',
            'after_title' => '</h6>'
        ));

    }

    add_action('widgets_init', 'edgtf_quadric_register_sidebars');
}

if(!function_exists('edgtf_quadric_add_support_custom_sidebar')) {
    /**
     * Function that adds theme support for custom sidebars. It also creates edgtf_sidebar object
     */
    function edgtf_quadric_add_support_custom_sidebar() {
        add_theme_support('edgtf_sidebar');
        if (get_theme_support('edgtf_sidebar')) new edgtf_quadric_sidebar();
    }

    add_action('after_setup_theme', 'edgtf_quadric_add_support_custom_sidebar');
}
