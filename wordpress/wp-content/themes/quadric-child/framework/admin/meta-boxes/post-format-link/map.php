<?php

/*** Link Post Format ***/

$link_post_format_meta_box = edgtf_quadric_add_meta_box(
	array(
		'scope' => array('post'),
		'title' => 'Link Post Format',
		'name' => 'post-format-link-meta'
	)
);

edgtf_quadric_add_meta_box_field(
	array(
		'name'        => 'edgtf_post_link_link_meta',
		'type'        => 'text',
		'label'       => 'Link',
		'description' => 'Enter link',
		'parent'      => $link_post_format_meta_box,

	)
);

