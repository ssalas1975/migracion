<?php

if ( ! function_exists('any_edge_page_options_map') ) {

    function any_edge_page_options_map() {

        any_edge_add_admin_page(
            array(
                'slug'  => '_page_page',
                'title' => esc_html__('Page', 'any'),
                'icon'  => 'fa fa-file-o'
            )
        );

        $custom_sidebars = any_edge_get_custom_sidebars();

        $panel_sidebar = any_edge_add_admin_panel(
            array(
                'page'  => '_page_page',
                'name'  => 'panel_sidebar',
                'title' => esc_html__('Design Style', 'any')
            )
        );

        any_edge_add_admin_field(array(
            'name'        => 'page_sidebar_layout',
            'type'        => 'select',
            'label'       => esc_html__('Sidebar Layout', 'any'),
            'description' => esc_html__('Choose a sidebar layout for pages', 'any'),
            'default_value' => 'default',
            'parent'      => $panel_sidebar,
            'options'     => array(
                'default'			=> esc_html__('No Sidebar', 'any'),
                'sidebar-33-right'	=> esc_html__('Sidebar 1/3 Right', 'any'),
                'sidebar-25-right' 	=> esc_html__('Sidebar 1/4 Right', 'any'),
                'sidebar-33-left' 	=> esc_html__('Sidebar 1/3 Left', 'any'),
                'sidebar-25-left' 	=> esc_html__('Sidebar 1/4 Left', 'any')
            )
        ));


        if(count($custom_sidebars) > 0) {
            any_edge_add_admin_field(array(
                'name' => 'page_custom_sidebar',
                'type' => 'selectblank',
                'label' => esc_html__('Sidebar to Display', 'any'),
                'description' => esc_html__('Choose a sidebar to display on pages. Default sidebar is "Sidebar"', 'any'),
                'parent' => $panel_sidebar,
                'options' => $custom_sidebars
            ));
        }
    }

    add_action( 'any_edge_options_map', 'any_edge_page_options_map', 8);

}