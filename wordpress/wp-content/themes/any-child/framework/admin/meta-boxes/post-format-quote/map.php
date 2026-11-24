<?php
if(!function_exists('any_edge_map_post_format_quote_meta_fields')) {

    function any_edge_map_post_format_quote_meta_fields() {

		/*** Quote Post Format ***/

		$quote_post_format_meta_box = any_edge_add_meta_box(
			array(
				'scope' =>	array('post'),
				'title' => esc_html__('Quote Post Format', 'any'),
				'name' 	=> 'post_format_quote_meta'
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_quote_text_meta',
				'type'        => 'text',
				'label'       => esc_html__('Quote Text', 'any'),
				'description' => esc_html__('Enter Quote text', 'any'),
				'parent'      => $quote_post_format_meta_box,
			)
		);
	 }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_post_format_quote_meta_fields');
}