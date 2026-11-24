<?php
if(!function_exists('any_edge_map_carousel_meta_fields')) {

    function any_edge_map_carousel_meta_fields() {

        //Carousels

        $carousel_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('carousels'),
                'title' => esc_html__('Carousel', 'any'),
                'name' => 'carousel_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_carousel_image',
                    'type'        => 'image',
                    'label'       => esc_html__('Carousel Image', 'any'),
                    'description' => esc_html__('Choose carousel image (min width needs to be 215px)', 'any'),
                    'parent'      => $carousel_meta_box
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_carousel_hover_image',
                    'type'        => 'image',
                    'label'       => esc_html__('Carousel Hover Image', 'any'),
                    'description' => esc_html__('Choose carousel hover image (min width needs to be 215px)', 'any'),
                    'parent'      => $carousel_meta_box
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_carousel_item_link',
                    'type'        => 'text',
                    'label'       => esc_html__('Link', 'any'),
                    'description' => esc_html__('Enter the URL to which you want the image to link to (e.g. http://www.example.com)', 'any'),
                    'parent'      => $carousel_meta_box
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        => 'edgtf_carousel_item_target',
                    'type'        => 'selectblank',
                    'label'       => esc_html__('Target', 'any'),
                    'description' => esc_html__('Specify where to open the linked document', 'any'),
                    'parent'      => $carousel_meta_box,
                    'options' => array(
                    	'_self'    => esc_html__('Self', 'any'),
                    	'_blank'   => esc_html__('Blank', 'any')
                	)
                )
            );
        }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_carousel_meta_fields');
}