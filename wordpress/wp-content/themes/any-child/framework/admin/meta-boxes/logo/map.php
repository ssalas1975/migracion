<?php
if(!function_exists('any_edge_map_logo_meta_fields')) {

    function any_edge_map_logo_meta_fields() {

		$general_meta_box = any_edge_add_meta_box(
		    array(
		        'scope' => array('page', 'portfolio-item', 'post'),
		        'title' => esc_html__('Logo', 'any'),
		        'name' => 'logo_meta'
		    )
		);

			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_logo_image_meta',
					'type' => 'image',
					'label' => esc_html__('Logo Image - Default', 'any'),
					'description' => esc_html__('Choose a default logo image to display ', 'any'),
					'parent' => $general_meta_box
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_logo_image_dark_meta',
					'type' => 'image',
					'label' => esc_html__('Logo Image - Dark', 'any'),
					'description' => esc_html__('Choose a default logo image to display ', 'any'),
					'parent' => $general_meta_box
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_logo_image_light_meta',
					'type' => 'image',
					'label' => esc_html__('Logo Image - Light', 'any'),
					'description' => esc_html__('Choose a default logo image to display ', 'any'),
					'parent' => $general_meta_box
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_logo_image_sticky_meta',
					'type' => 'image',
					'label' => esc_html__('Logo Image - Sticky', 'any'),
					'description' => esc_html__('Choose a default logo image to display ', 'any'),
					'parent' => $general_meta_box
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_logo_image_mobile_meta',
					'type' => 'image',
					'label' => esc_html__('Logo Image - Mobile', 'any'),
					'description' => esc_html__('Choose a default logo image to display ', 'any'),
					'parent' => $general_meta_box
				)
			);
		}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_logo_meta_fields');
}