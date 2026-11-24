<?php
if(!function_exists('any_edge_map_post_format_gallery_meta_fields')) {

    function any_edge_map_post_format_gallery_meta_fields() {

		/*** Gallery Post Format ***/

		$gallery_post_format_meta_box = any_edge_add_meta_box(
			array(
				'scope' =>	array('post'),
				'title' => esc_html__('Gallery Post Format', 'any'),
				'name' 	=> 'post_format_gallery_meta'
			)
		);

		any_edge_add_multiple_images_field(
			array(
				'name'        => 'edgtf_post_gallery_images_meta',
				'label'       => esc_html__('Gallery Images', 'any'),
				'description' => esc_html__('Choose your gallery images', 'any'),
				'parent'      => $gallery_post_format_meta_box,
			)
		);
	}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_post_format_gallery_meta_fields');
}