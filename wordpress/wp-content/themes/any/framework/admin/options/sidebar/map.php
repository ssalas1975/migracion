<?php

if ( ! function_exists('any_edge_sidebar_options_map') ) {

	function any_edge_sidebar_options_map() {

		any_edge_add_admin_page(
			array(
				'slug'  => '_sidebar_page',
				'title' => esc_html__('Sidebar', 'any'),
				'icon'  => 'fa fa-indent'
			)
		);

		$panel_widgets = any_edge_add_admin_panel(
			array(
				'page'  => '_sidebar_page',
				'name'  => 'panel_widgets',
				'title' => esc_html__('Widgets', 'any')
			)
		);

		/**
		 * Navigation style
		 */
		any_edge_add_admin_field(array(
			'type'			=> 'color',
			'name'			=> 'sidebar_background_color',
			'default_value'	=> '',
			'label'			=> esc_html__('Sidebar Background Color', 'any'),
			'description'	=> esc_html__('Choose background color for sidebar', 'any'),
			'parent'		=> $panel_widgets
		));

		$group_sidebar_padding = any_edge_add_admin_group(array(
			'name'		=> 'group_sidebar_padding',
			'title'		=> esc_html__('Padding', 'any'),
			'parent'	=> $panel_widgets
		));

		$row_sidebar_padding = any_edge_add_admin_row(array(
			'name'		=> 'row_sidebar_padding',
			'parent'	=> $group_sidebar_padding
		));

		any_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_top',
			'default_value'	=> '',
			'label'			=> esc_html__('Top Padding', 'any'),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		any_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_right',
			'default_value'	=> '',
			'label'			=> esc_html__('Right Padding', 'any'),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		any_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_bottom',
			'default_value'	=> '',
			'label'			=> esc_html__('Bottom Padding', 'any'),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		any_edge_add_admin_field(array(
			'type'			=> 'textsimple',
			'name'			=> 'sidebar_padding_left',
			'default_value'	=> '',
			'label'			=> esc_html__('Left Padding', 'any'),
			'args'			=> array(
				'suffix'	=> 'px'
			),
			'parent'		=> $row_sidebar_padding
		));

		any_edge_add_admin_field(array(
			'type'			=> 'select',
			'name'			=> 'sidebar_alignment',
			'default_value'	=> '',
			'label'			=> esc_html__('Text Alignment', 'any'),
			'description'	=> esc_html__('Choose text aligment', 'any'),
			'options'		=> array(
				'left' 			=> esc_html__('Left', 'any'),
				'center' 		=> esc_html__('Center', 'any'),
				'right' 		=> esc_html__('Right', 'any')
			),
			'parent'		=> $panel_widgets
		));

	}

	add_action( 'any_edge_options_map', 'any_edge_sidebar_options_map', 9);

}