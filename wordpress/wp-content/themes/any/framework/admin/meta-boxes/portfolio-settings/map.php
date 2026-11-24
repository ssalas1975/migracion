<?php

if(!function_exists('any_edge_map_portfolio_settings')) {
    function any_edge_map_portfolio_settings() {
        $meta_box = any_edge_add_meta_box(array(
            'scope' => 'portfolio-item',
            'title' => esc_html__('Portfolio Settings', 'any'),
            'name'  => 'portfolio_settings_meta_box'
        ));

        any_edge_add_meta_box_field(array(
            'name'        => 'edgtf_portfolio_single_template_meta',
            'type'        => 'select',
            'label'       => esc_html__('Portfolio Type', 'any'),
            'description' => esc_html__('Choose a default type for Single Project pages', 'any'),
            'parent'      => $meta_box,
            'options'     => array(
                ''                   => esc_html__('Default', 'any'),
                'small-images'       => esc_html__('Portfolio small images left', 'any'),
                'small-images-right' => esc_html__('Portfolio small images right', 'any'),
                'small-slider'       => esc_html__('Portfolio small slider left', 'any'),
                'small-slider-right' => esc_html__('Portfolio small slider right', 'any'),
                'big-images'         => esc_html__('Portfolio big images', 'any'),
                'wide-images'        => esc_html__('Portfolio wide images left', 'any'),
                'wide-images-right'  => esc_html__('Portfolio wide images right', 'any'),
                'big-slider'         => esc_html__('Portfolio big slider', 'any'),
                'wide-slider'        => esc_html__('Portfolio wide slider', 'any'),
                'gallery'            => esc_html__('Portfolio gallery', 'any'),
                'small-masonry'      => esc_html__('Portfolio small masonry', 'any'),
                'big-masonry'        => esc_html__('Portfolio big masonry', 'any'),
                'split-screen'       => esc_html__('Portfolio split screen', 'any'),
                'full-screen-slider' => esc_html__('Portfolio full screen slider', 'any'),
                'custom'             => esc_html__('Portfolio custom', 'any'),
                'full-width-custom'  => esc_html__('Portfolio full width custom', 'any'),
            )
        ));

        $all_pages = array();
        $pages     = get_pages();
        foreach($pages as $page) {
            $all_pages[$page->ID] = $page->post_title;
        }

        any_edge_add_meta_box_field(array(
            'name'        => 'portfolio_single_back_to_link',
            'type'        => 'select',
            'label'       => esc_html__('"Back To" Link', 'any'),
            'description' => esc_html__('Choose "Back To" page to link from portfolio Single Project page', 'any'),
            'parent'      => $meta_box,
            'options'     => $all_pages
        ));

        any_edge_add_meta_box_field(array(
            'name'        => 'portfolio_external_link',
            'type'        => 'text',
            'label'       => esc_html__('Portfolio External Link', 'any'),
            'description' => esc_html__('Enter URL to link from Portfolio List page', 'any'),
            'parent'      => $meta_box,
            'args'        => array(
                'col_width' => 3
            )
        ));

        any_edge_add_meta_box_field(array(
            'name'        => 'portfolio_masonry_dimenisions',
            'type'        => 'select',
            'label'       => esc_html__('Dimensions for Masonry', 'any'),
            'description' => esc_html__('Choose image layout when it appears in Masonry type portfolio lists', 'any'),
            'parent'      => $meta_box,
            'options'     => array(
                'default'            => esc_html__('Default', 'any'),
                'large_width'        => esc_html__('Large width', 'any'),
                'large_height'       => esc_html__('Large height', 'any'),
                'large_width_height' => esc_html__('Large width/height', 'any')
            )
        ));
    }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_portfolio_settings');
}