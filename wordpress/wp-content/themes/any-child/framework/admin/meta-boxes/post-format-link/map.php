<?php
if(!function_exists('any_edge_map_post_format_link_meta_fields')) {

    function any_edge_map_post_format_link_meta_fields() {

		/*** Link Post Format ***/

		$link_post_format_meta_box = any_edge_add_meta_box(
			array(
				'scope' => array('post'),
				'title' => esc_html__('Link Post Format', 'any'),
				'name' => 'post_format_link_meta'
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_link_link_meta',
				'type'        => 'text',
				'label'       => esc_html__('Link', 'any'),
				'description' => esc_html__('Enter link', 'any'),
				'parent'      => $link_post_format_meta_box,

			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_link_link_text_meta',
				'type'        => 'text',
				'label'       => esc_html__('Link Text', 'any'),
				'description' => esc_html__('Enter link text', 'any'),
				'parent'      => $link_post_format_meta_box,

			)
		);
	}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_post_format_link_meta_fields');
}