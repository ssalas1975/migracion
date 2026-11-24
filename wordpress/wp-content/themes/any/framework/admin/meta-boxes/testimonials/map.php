<?php
if(!function_exists('any_edge_map_testimonials_meta_fields')) {

    function any_edge_map_testimonials_meta_fields() {

        //Testimonials

        $testimonial_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('testimonials'),
                'title' => esc_html__('Testimonial', 'any'),
                'name' => 'testimonial_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name'        	=> 'edgtf_testimonial_title',
                    'type'        	=> 'text',
                    'label'       	=> esc_html__('Title', 'any'),
                    'description' 	=> esc_html__('Enter testimonial title', 'any'),
                    'parent'      	=> $testimonial_meta_box,
                )
            );


            any_edge_add_meta_box_field(
                array(
                    'name'        	=> 'edgtf_testimonial_author',
                    'type'        	=> 'text',
                    'label'       	=>  esc_html__('Author', 'any'),
                    'description' 	=>  esc_html__('Enter author name', 'any'),
                    'parent'      	=> $testimonial_meta_box,
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        	=> 'edgtf_testimonial_author_position',
                    'type'        	=> 'text',
                    'label'       	=>  esc_html__('Job Position', 'any'),
                    'description' 	=>  esc_html__('Enter job position', 'any'),
                    'parent'      	=> $testimonial_meta_box,
                )
            );

            any_edge_add_meta_box_field(
                array(
                    'name'        	=> 'edgtf_testimonial_text',
                    'type'        	=> 'text',
                    'label'       	=>  esc_html__('Text', 'any'),
                    'description' 	=>  esc_html__('Enter testimonial text', 'any'),
                    'parent'      	=> $testimonial_meta_box,
                )
            );
        }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_testimonials_meta_fields');
}