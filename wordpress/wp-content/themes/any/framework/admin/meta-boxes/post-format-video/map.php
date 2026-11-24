<?php
if(!function_exists('any_edge_map_post_format_video_meta_fields')) {

    function any_edge_map_post_format_video_meta_fields() {

		/*** Video Post Format ***/

		$video_post_format_meta_box = any_edge_add_meta_box(
			array(
				'scope' =>	array('post'),
				'title' => 	esc_html__('Video Post Format', 'any'),
				'name' 	=> 'post_format_video_meta'
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_video_type_meta',
				'type'        => 'select',
				'label'       => esc_html__('Video Type', 'any'),
				'description' => esc_html__('Choose video type', 'any'),
				'parent'      => $video_post_format_meta_box,
				'default_value' => 'youtube',
				'options'     => array(
					'youtube' => esc_html__('Youtube', 'any'),
					'vimeo' => esc_html__('Vimeo', 'any'),
					'self' => esc_html__('Self Hosted', 'any')
				),
				'args' => array(
				'dependence' => true,
				'hide' => array(
					'youtube' => '#edgtf_edgtf_video_self_hosted_container',
					'vimeo' => '#edgtf_edgtf_video_self_hosted_container',
					'self' => '#edgtf_edgtf_video_embedded_container'
				),
				'show' => array(
					'youtube' => '#edgtf_edgtf_video_embedded_container',
					'vimeo' => '#edgtf_edgtf_video_embedded_container',
					'self' => '#edgtf_edgtf_video_self_hosted_container')
			)
			)
		);

		$edgtf_video_embedded_container = any_edge_add_admin_container(
			array(
				'parent' => $video_post_format_meta_box,
				'name' => 'edgtf_video_embedded_container',
				'hidden_property' => 'edgtf_video_type_meta',
				'hidden_value' => 'self'
			)
		);

		$edgtf_video_self_hosted_container = any_edge_add_admin_container(
			array(
				'parent' => $video_post_format_meta_box,
				'name' => 'edgtf_video_self_hosted_container',
				'hidden_property' => 'edgtf_video_type_meta',
				'hidden_values' => array('youtube', 'vimeo')
			)
		);



		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_id_meta',
				'type'        => 'text',
				'label'       => esc_html__('Video ID', 'any'),
				'description' => esc_html__('Enter Video ID', 'any'),
				'parent'      => $edgtf_video_embedded_container,

			)
		);


		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_image_meta',
				'type'        => 'image',
				'label'       => esc_html__('Video Image', 'any'),
				'description' => esc_html__('Upload video image', 'any'),
				'parent'      => $edgtf_video_self_hosted_container,

			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_webm_link_meta',
				'type'        => 'text',
				'label'       => esc_html__('Video WEBM', 'any'),
				'description' => esc_html__('Enter video URL for WEBM format', 'any'),
				'parent'      => $edgtf_video_self_hosted_container,

			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_mp4_link_meta',
				'type'        => 'text',
				'label'       => esc_html__('Video MP4', 'any'),
				'description' => esc_html__('Enter video URL for MP4 format', 'any'),
				'parent'      => $edgtf_video_self_hosted_container,

			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'        => 'edgtf_post_video_ogv_link_meta',
				'type'        => 'text',
				'label'       => esc_html__('Video OGV', 'any'),
				'description' => esc_html__('Enter video URL for OGV format', 'any'),
				'parent'      => $edgtf_video_self_hosted_container,

			)
		);
	}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_post_format_video_meta_fields');
}