<?php
if(!function_exists('any_edge_map_sidebar_meta_fields')) {

    function any_edge_map_sidebar_meta_fields() {

        $custom_sidebars = any_edge_get_custom_sidebars();

        $sidebar_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('page', 'portfolio-item', 'post'),
                'title' => esc_html__('Sidebar', 'any'),
                'name' => 'sidebar_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_sidebar_meta',
                    'type'        => 'select',
                    'label'       => esc_html__('Layout', 'any'),
                    'description' => esc_html__('Choose the sidebar layout', 'any'),
                    'parent'      => $sidebar_meta_box,
                    'options'     => array(
        						''			        => esc_html__('Default', 'any'),
        						'no-sidebar'		=> esc_html__('No Sidebar', 'any'),
        						'sidebar-33-right'	=> esc_html__('Sidebar 1/3 Right', 'any'),
        						'sidebar-25-right' 	=> esc_html__('Sidebar 1/4 Right', 'any'),
        						'sidebar-33-left' 	=> esc_html__('Sidebar 1/3 Left', 'any'),
        						'sidebar-25-left' 	=> esc_html__('Sidebar 1/4 Left', 'any'),
        					)
                )
            );

        if(count($custom_sidebars) > 0) {
            any_edge_add_meta_box_field(array(
                'name' => 'edgtf_custom_sidebar_meta',
                'type' => 'selectblank',
                'label' => esc_html__('Choose Widget Area in Sidebar', 'any'),
                'description' => esc_html__('Choose Custom Widget area to display in Sidebar"', 'any'),
                'parent' => $sidebar_meta_box,
                'options' => $custom_sidebars
            ));
        }
     }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_sidebar_meta_fields');
}