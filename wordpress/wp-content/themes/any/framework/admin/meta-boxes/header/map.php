<?php
if(!function_exists('any_edge_map_header_meta_fields')) {

    function any_edge_map_header_meta_fields() {

		$header_meta_box = any_edge_add_meta_box(
		    array(
		        'scope' => array('page', 'portfolio-item', 'post'),
		        'title' => esc_html__('Header', 'any'),
		        'name' => 'header_meta'
		    )
		);

		$temp_holder_show		= '';
		$temp_holder_hide		= '';
		$temp_array_standard	= array();
		$temp_array_vertical	= array();
		$temp_array_full_screen	= array();
		$temp_array_behaviour	= array();
		switch (any_edge_options()->getOptionValue('header_type')) {

			case 'header-standard':
				$temp_holder_show = '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_behaviour_meta_container';
				$temp_holder_hide = '#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container,#edgtf_edgtf_header_expanding_type_meta_container';

				$temp_array_standard = array(
					'hidden_value' => 'default',
					'hidden_values' => array('header-vertical','header-full-screen','header-expanding')
				);
				$temp_array_vertical = array(
					'hidden_values' => array('','header-standard','header-full-screen','header-expanding')
				);
				$temp_array_full_screen = array(
					'hidden_values' => array('','header-standard', 'header-vertical','header-expanding')
				);
				$temp_array_expanding = array(
					'hidden_values' => array('','header-standard', 'header-vertical','header-full-screen')
				);
				$temp_array_behaviour = array(
					'hidden_value' => 'header-vertical'
				);
				break;

			case 'header-vertical':
				$temp_holder_show = '#edgtf_edgtf_header_vertical_type_meta_container';
				$temp_holder_hide = '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container,#edgtf_edgtf_header_behaviour_meta_container,#edgtf_edgtf_header_expanding_type_meta_container';

				$temp_array_standard = array(
					'hidden_values' => array('', 'header-vertical', 'header-full-screen','header-expanding')
				);
				$temp_array_vertical = array(
					'hidden_value' => 'default',
					'hidden_values' => array('header-standard','header-full-screen','header-expanding')
				);
				$temp_array_full_screen = array(
					'hidden_values' => array('','header-standard', 'header-vertical','header-expanding')
				);
				$temp_array_expanding = array(
					'hidden_values' => array('','header-standard', 'header-vertical','header-full-screen')
				);
				$temp_array_behaviour = array(
					'hidden_value' => 'default',
					'hidden_values' => array('','header-vertical')
				);
				break;
			case 'header-full-screen':
				$temp_holder_show = '#edgtf_edgtf_header_full_screen_type_meta_container,#edgtf_edgtf_header_behaviour_meta_container';
				$temp_holder_hide = '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_expanding_type_meta_container';
				$temp_array_standard = array(
					'hidden_values' => array('', 'header-vertical', 'header-standard','header-expanding')
				);

				$temp_array_vertical = array(
					'hidden_values' => array('', 'header-standard','header-full-screen','header-expanding')
				);

				$temp_array_full_screen = array(
					'hidden_value' => 'default',
					'hidden_values' => array('header-vertical','header-full-screen','header-expanding')
				);
				$temp_array_expanding = array(
					'hidden_values' => array('','header-standard', 'header-vertical','header-full-screen')
				);
				$temp_array_behaviour = array(
					'hidden_value' => 'header-vertical'
				);
				break;
			case 'header-expanding':
				$temp_holder_show = '#edgtf_edgtf_header_expanding_type_meta_container,#edgtf_edgtf_header_behaviour_meta_container';
				$temp_holder_hide = '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container';
				$temp_array_standard = array(
					'hidden_values' => array('', 'header-vertical', 'header-standard','header-full-screen')
				);

				$temp_array_vertical = array(
					'hidden_values' => array('', 'header-standard','header-full-screen','header-expanding')
				);

				$temp_array_full_screen = array(
					'hidden_values' => array('','header-vertical','header-full-screen','header-expanding')
				);
				$temp_array_expanding = array(
					'hidden_value' => 'default',
					'hidden_values' => array('header-standard', 'header-vertical','header-full-screen')
				);
				$temp_array_behaviour = array(
					'hidden_value' => 'header-vertical'
				);
				break;
		}

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_header_type_meta',
				'type' => 'select',
				'default_value' => '',
				'label' => esc_html__('Choose Header Type', 'any'),
				'description' => esc_html__('Select header type layout', 'any'),
				'parent' => $header_meta_box,
				'options' => array(
					'' => esc_html__('Default', 'any'),
					'header-standard' => esc_html__('Standard Header Layout', 'any'),
					'header-vertical' => esc_html__('Vertical Header Layout', 'any'),
					'header-full-screen' => esc_html__('Full Screen Header Layout', 'any'),
					'header-expanding' => esc_html__('Expanding Header Layout', 'any')
				),
				'args' => array(
					"dependence" => true,
					"hide" => array(
						"" => $temp_holder_hide,
						'header-standard' 		=> '#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container,#edgtf_edgtf_header_expanding_type_meta_container',
						'header-vertical' 		=> '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container,#edgtf_edgtf_header_expanding_type_meta_container',
						'header-full-screen'	=> '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_expanding_type_meta_container',
						'header-expanding'	    => '#edgtf_edgtf_header_standard_type_meta_container,#edgtf_edgtf_header_vertical_type_meta_container,#edgtf_edgtf_header_full_screen_type_meta_container'
					),
					"show" => array(
						"" => $temp_holder_show,
						"header-standard" 		=> '#edgtf_edgtf_header_standard_type_meta_container',
						"header-vertical" 		=> '#edgtf_edgtf_header_vertical_type_meta_container',
						"header-full-screen" 	=> '#edgtf_edgtf_header_full_screen_type_meta_container',
						"header-expanding"	 	=> '#edgtf_edgtf_header_expanding_type_meta_container'
					)
				)
			)
		);
		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_header_style_meta',
				'type' => 'select',
				'default_value' => '',
				'label' => esc_html__('Header Skin', 'any'),
				'description' => esc_html__('Choose a header style to make header elements (logo, main menu, side menu button) in that predefined style', 'any'),
				'parent' => $header_meta_box,
				'options' => array(
					'' => '',
					'light-header' => esc_html__('Light', 'any'),
					'dark-header' => esc_html__('Dark', 'any')
				)
			)
		);

		any_edge_add_meta_box_field(
			array(
				'parent' => $header_meta_box,
				'type' => 'select',
				'name' => 'edgtf_enable_header_style_on_scroll_meta',
				'default_value' => '',
				'label' => esc_html__('Enable Header Style on Scroll', 'any'),
				'description' => esc_html__('Enabling this option, header will change style depending on row settings for dark/light style', 'any'),
				'options' => array(
					'' => '',
					'no' 	=> esc_html__('No', 'any'),
					'yes' 	=> esc_html__('Yes', 'any')
				)
			)
		);

		any_edge_add_meta_box_field(
			array(
				'parent' => $header_meta_box,
				'type' => 'text',
				'name' => 'edgtf_menu_area_padding_header_full_width_meta',
				'default_value' => '',
				'label' => esc_html__('Full Width Header Left/Right Padding', 'any'),
				'description' => esc_html__('Set left/right padding for header full width with px or %', 'any'),
				'args' => array(
					'col_width' => 3
				)
			)
		);

		$header_standard_type_meta_container = any_edge_add_admin_container(
			array_merge(
				array(
					'parent' => $header_meta_box,
					'name' => 'edgtf_header_standard_type_meta_container',
					'hidden_property' => 'edgtf_header_type_meta',

				),
				$temp_array_standard
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_color_header_standard_meta',
				'type' => 'color',
				'label' => esc_html__('Background Color', 'any'),
				'description' => esc_html__('Choose a background color for header area', 'any'),
				'parent' => $header_standard_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_transparency_header_standard_meta',
				'type' => 'text',
				'label' => esc_html__('Background Transparency', 'any'),
				'description' => esc_html__('Choose a transparency for the header background color (0 = fully transparent, 1 = opaque)', 'any'),
				'parent' => $header_standard_type_meta_container,
				'args' => array(
					'col_width' => 2
				)
			)
		);

		$header_vertical_type_meta_container = any_edge_add_admin_container(
			array_merge(
				array(
					'parent' => $header_meta_box,
					'name' => 'edgtf_header_vertical_type_meta_container',
					'hidden_property' => 'edgtf_header_type_meta',
					'hidden_values' => array('header-standard')
				),
				$temp_array_vertical
			)
		);

		any_edge_add_meta_box_field(array(
			'name'        => 'edgtf_vertical_header_background_color_meta',
			'type'        => 'color',
			'label'       => esc_html__('Background Color', 'any'),
			'description' => esc_html__('Set background color for vertical menu', 'any'),
			'parent'      => $header_vertical_type_meta_container
		));

		any_edge_add_meta_box_field(array(
			'name'        => 'edgtf_vertical_header_transparency_meta',
			'type'        => 'text',
			'label'       => esc_html__('Background Transparency', 'any'),
			'description' => esc_html__('Enter transparency for vertical menu (value from 0 to 1)', 'any'),
			'parent'      => $header_vertical_type_meta_container,
			'args'        => array(
				'col_width' => 1
			)
		));

		any_edge_add_meta_box_field(
			array(
				'name'          => 'edgtf_vertical_header_background_image_meta',
				'type'          => 'image',
				'default_value' => '',
				'label'         => esc_html__('Background Image', 'any'),
				'description'   => esc_html__('Set background image for vertical menu', 'any'),
				'parent'        => $header_vertical_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_disable_vertical_header_background_image_meta',
				'type' => 'yesno',
				'default_value' => 'no',
				'label' => esc_html__('Disable Background Image', 'any'),
				'description' => esc_html__('Enabling this option will hide background image in Vertical Menu', 'any'),
				'parent' => $header_vertical_type_meta_container
			)
		);

		$header_full_screen_type_meta_container = any_edge_add_admin_container(
			array_merge(
				array(
					'parent' => $header_meta_box,
					'name' => 'edgtf_header_full_screen_type_meta_container',
					'hidden_property' => 'edgtf_header_type_meta',

				),
				$temp_array_full_screen
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_color_header_full_screen_meta',
				'type' => 'color',
				'label' => esc_html__('Background Color', 'any'),
				'description' => esc_html__('Choose a background color for Full Screen header area', 'any'),
				'parent' => $header_full_screen_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_transparency_header_full_screen_meta',
				'type' => 'text',
				'label' => esc_html__('Background Transparency', 'any'),
				'description' => esc_html__('Choose a transparency for the Full Screen header background color (0 = fully transparent, 1 = opaque)', 'any'),
				'parent' => $header_full_screen_type_meta_container,
				'args' => array(
					'col_width' => 2
				)
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_fullscreen_menu_icon_background_color_meta',
				'type' => 'color',
				'label' => esc_html__('Icon Background Color', 'any'),
				'description' => esc_html__('Choose a background color for Full Screen Icon', 'any'),
				'parent' => $header_full_screen_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_fullscreen_menu_icon_background_hover_color_meta',
				'type' => 'color',
				'label' => esc_html__('Icon Background Hover Color', 'any'),
				'description' => esc_html__('Choose a background hover color for Full Screen Icon', 'any'),
				'parent' => $header_full_screen_type_meta_container
			)
		);

		$header_expanding_type_meta_container = any_edge_add_admin_container(
			array_merge(
				array(
					'parent' => $header_meta_box,
					'name' => 'edgtf_header_expanding_type_meta_container',
					'hidden_property' => 'edgtf_header_type_meta',

				),
				$temp_array_expanding
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_color_header_expanding_meta',
				'type' => 'color',
				'label' => esc_html__('Background Color', 'any'),
				'description' => esc_html__('Choose a background color for Expanding header area', 'any'),
				'parent' => $header_expanding_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_area_background_transparency_header_expanding_meta',
				'type' => 'text',
				'label' => esc_html__('Background Transparency', 'any'),
				'description' => esc_html__('Choose a transparency for the Expanding header background color (0 = fully transparent, 1 = opaque)', 'any'),
				'parent' => $header_expanding_type_meta_container,
				'args' => array(
					'col_width' => 2
				)
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_icon_background_color_header_expanding_meta',
				'type' => 'color',
				'label' => esc_html__('Menu Icon Background Color', 'any'),
				'description' => esc_html__('Choose a background color for Expanding header menu icon', 'any'),
				'parent' => $header_expanding_type_meta_container
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_icon_background_transparency_header_expanding_meta',
				'type' => 'text',
				'label' => esc_html__('Menu Icon Background Transparency', 'any'),
				'description' => esc_html__('Choose a transparency for the Expanding header menu icon background color (0 = fully transparent, 1 = opaque)', 'any'),
				'parent' => $header_expanding_type_meta_container,
				'args' => array(
					'col_width' => 2
				)
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name' => 'edgtf_menu_icon_background_spread_header_expanding_meta',
				'type' => 'select',
				'default_value' => '',
				'parent' => $header_expanding_type_meta_container,
				'label' => esc_html__('Spread Icon Background', 'any'),
				'description' => esc_html__('Enable this option to enable spreading of Icon background over header', 'any'),
				'options' => array(
					'' => '',
					'no' 	=> esc_html__('No', 'any'),
					'yes'	=> esc_html__('Yes', 'any')
				)
			)
		);

		$header_behaviour_meta_container = any_edge_add_admin_container(
			array_merge(
				array(
					'parent' => $header_meta_box,
					'name' => 'edgtf_header_behaviour_meta_container',
					'hidden_property' => 'edgtf_header_type_meta',

				),
				$temp_array_behaviour
			)
		);

		any_edge_add_meta_box_field(
			array(
				'name'            => 'edgtf_scroll_amount_for_sticky_meta',
				'type'            => 'text',
				'label'           => esc_html__('Scroll amount for sticky header appearance', 'any'),
				'description'     => esc_html__('Define scroll amount for sticky header appearance', 'any'),
				'parent'          => $header_behaviour_meta_container,
				'args'            => array(
					'col_width' => 2,
					'suffix'    => 'px'
				),
				'hidden_property' => 'edgtf_header_behaviour',
				'hidden_values'   => array("sticky-header-on-scroll-up", "fixed-on-scroll")
			)
		);


		any_edge_add_admin_section_title(array(
			'name'   => 'top_bar_section_title',
			'parent' => $header_meta_box,
			'title'  => esc_html__('Top Bar', 'any')
		));

		$top_bar_global_option      = any_edge_options()->getOptionValue('top_bar');
		$top_bar_default_dependency = array(
			'' => '#edgtf_top_bar_container_no_style'
		);

		$top_bar_show_array = array(
			'yes' => '#edgtf_top_bar_container_no_style'
		);

		$top_bar_hide_array = array(
			'no' => '#edgtf_top_bar_container_no_style'
		);

		if($top_bar_global_option === 'yes') {
			$top_bar_show_array = array_merge($top_bar_show_array, $top_bar_default_dependency);
			$temp_top_no = array(
				'hidden_value' => 'no'
			);
		} else {
			$top_bar_hide_array = array_merge($top_bar_hide_array, $top_bar_default_dependency);
			$temp_top_no = array(
				'hidden_values'   => array('','no')
			);
		}


		any_edge_add_meta_box_field(array(
			'name'          => 'edgtf_top_bar_meta',
			'type'          => 'select',
			'label'         => esc_html__('Enable Top Bar on This Page', 'any'),
			'description'   => esc_html__('Enabling this option will enable top bar on this page', 'any'),
			'parent'        => $header_meta_box,
			'default_value' => '',
			'options'       => array(
				''    => esc_html__('Default', 'any'),
				'yes' => esc_html__('Yes', 'any'),
				'no'  => esc_html__('No', 'any')
			),
			'args' => array(
				"dependence" => true,
				'show'       => $top_bar_show_array,
				'hide'       => $top_bar_hide_array
			)
		));

		$top_bar_container = any_edge_add_admin_container_no_style(array_merge(array(
			'name'            => 'top_bar_container_no_style',
			'parent'          => $header_meta_box,
			'hidden_property' => 'edgtf_top_bar_meta'
		),
			$temp_top_no));

		any_edge_add_meta_box_field(array(
			'name'    => 'edgtf_top_bar_skin_meta',
			'type'    => 'select',
			'label'   => esc_html__('Top Bar Skin', 'any'),
			'options' => array(
				''      => esc_html__('Default', 'any'),
				'light' => esc_html__('Light', 'any'),
				'dark'  => esc_html__('Dark', 'any')
			),
			'parent'  => $top_bar_container
		));

		any_edge_add_meta_box_field(array(
			'name'   => 'edgtf_top_bar_background_color_meta',
			'type'   => 'color',
			'label'  => esc_html__('Top Bar Background Color', 'any'),
			'parent' => $top_bar_container
		));

		any_edge_add_meta_box_field(array(
			'name'        => 'edgtf_top_bar_background_transparency_meta',
			'type'        => 'text',
			'label'       => esc_html__('Top Bar Background Color Transparency', 'any'),
			'description' => esc_html__('Set top bar background color transparenct. Value should be between 0 and 1', 'any'),
			'parent'      => $top_bar_container,
			'args'        => array(
				'col_width' => 3
			)
		));
	}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_header_meta_fields');
}