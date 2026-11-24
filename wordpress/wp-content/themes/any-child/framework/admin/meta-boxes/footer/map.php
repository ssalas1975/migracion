<?php
if(!function_exists('any_edge_map_footer_meta_fields')) {

    function any_edge_map_footer_meta_fields() {

        $footer_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('page', 'portfolio-item', 'post'),
                'title' => esc_html__('Footer', 'any'),
                'name' => 'footer_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name' => 'edgtf_disable_footer_meta',
                    'type' => 'yesno',
                    'default_value' => 'no',
                    'label' => esc_html__('Disable Footer for this Page', 'any'),
                    'description' => esc_html__('Enabling this option will hide footer on this page', 'any'),
                    'parent' => $footer_meta_box,
                )
            );
        }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_footer_meta_fields');
}