<?php
if(!function_exists('any_edge_map_post_format_audio_meta_fields')) {

    function any_edge_map_post_format_audio_meta_fields() {

		/*** Audio Post Format ***/

		$audio_post_format_meta_box = any_edge_add_meta_box(
			array(
				'scope' =>	array('post'),
				'title' =>	esc_html__('Audio Post Format', 'any'),
				'name' 	=> 'post_format_audio_meta'
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_audio_link_meta',
				'type'        => 'text',
				'label'       => esc_html__('Link', 'any'),
				'description' => esc_html__('Enter audion link', 'any'),
				'parent'      => $audio_post_format_meta_box,

			)
		);
	}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_post_format_audio_meta_fields');
}