<?php
if(!function_exists('any_edge_map_general_meta_fields')) {

    function any_edge_map_general_meta_fields() {

		$general_meta_box = any_edge_add_meta_box(
		    array(
		        'scope' => array('page', 'portfolio-item', 'post'),
		        'title' => esc_html__('General', 'any'),
		        'name' => 'general_meta'
		    )
		);

		    any_edge_add_meta_box_field(
		        array(
		            'name' => 'edgtf_page_background_color_meta',
		            'type' => 'color',
		            'default_value' => '',
		            'label' => esc_html__('Page Background Color', 'any'),
		            'description' => esc_html__('Choose background color for page content', 'any'),
		            'parent' => $general_meta_box
		        )
		    );
			
			any_edge_add_meta_box_field(
				array(
					'name' => 'edgtf_page_padding_meta',
					'type' => 'text',
					'default_value' => '',
					'label' => esc_html__('Page Padding', 'any'),
					'description' => esc_html__('Insert padding in format 10px 10px 10px 10px', 'any'),
					'parent' => $general_meta_box
				)
			);

		    any_edge_add_meta_box_field(
		        array(
		            'name' => 'edgtf_page_slider_meta',
		            'type' => 'text',
		            'default_value' => '',
		            'label' => esc_html__('Slider Shortcode', 'any'),
		            'description' => esc_html__('Paste your slider shortcode here', 'any'),
		            'parent' => $general_meta_box
		        )
		    );

			$passepartout_option      = any_edge_options()->getOptionValue('passepartout');
			$passepartout_default_dependency = array(
				'' => '#edgtf_passepartout_container'
			);

			$passepartout_show_array = array(
				'yes' => '#edgtf_passepartout_container'
			);

			$passepartout_hide_array = array(
				'no' => '#edgtf_passepartout_container'
			);

			if($passepartout_option === 'yes') {
				$passepartout_show_array = array_merge($passepartout_show_array, $passepartout_default_dependency);
				$temp_passepartout_no = array(
					'hidden_value' => 'no'
				);
			} else {
				$passepartout_hide_array = array_merge($passepartout_hide_array, $passepartout_default_dependency);
				$temp_passepartout_no = array(
					'hidden_values'   => array('','no')
				);
			}

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_passepartout_meta',
					'type'          => 'select',
					'default_value' => '',
					'label'         => esc_html__('Passepartout', 'any'),
					'description'   => esc_html__('Enabling this option will display passepartout around site content', 'any'),
					'parent'        => $general_meta_box,
					'options'     => array(
						''		=> esc_html__('Default', 'any'),
						'yes'	=> esc_html__('Yes', 'any'),
						'no'	=> esc_html__('No', 'any')
					),
					'args' => array(
						"dependence" => true,
						'show'       => $passepartout_show_array,
						'hide'       => $passepartout_hide_array
					)
				)
			);

			$passepartout_container = any_edge_add_admin_container(
				array_merge(
					array(
						'parent'            => $general_meta_box,
						'name'              => 'passepartout_container',
						'hidden_property'   => 'edgtf_passepartout_meta'
					),
					$temp_passepartout_no
				)
			);
			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_passepartout_color_meta',
					'type'          => 'color',
					'label'         => esc_html__('Passepartout Color', 'any'),
					'description'   => esc_html__('Choose Passepartout color.', 'any'),
					'parent'        => $passepartout_container
				)
			);
			$boxed_option      = any_edge_options()->getOptionValue('boxed');
			$boxed_default_dependency = array(
				'' => '#edgtf_boxed_container'
			);

			$boxed_show_array = array(
				'yes' => '#edgtf_boxed_container'
			);

			$boxed_hide_array = array(
				'no' => '#edgtf_boxed_container'
			);

			if($boxed_option === 'yes') {
				$boxed_show_array = array_merge($boxed_show_array, $boxed_default_dependency);
				$temp_boxed_no = array(
					'hidden_value' => 'no'
				);
			} else {
				$boxed_hide_array = array_merge($boxed_hide_array, $boxed_default_dependency);
				$temp_boxed_no = array(
					'hidden_values'   => array('','no')
				);
			}

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_boxed_meta',
					'type'          => 'select',
					'label'         => esc_html__('Boxed Layout', 'any'),
					'description'   => '',
					'parent'        => $general_meta_box,
					'default_value' => '',
					'options'     => array(
						''		=> esc_html__('Default', 'any'),
						'yes'	=> esc_html__('Yes', 'any'),
						'no'	=> esc_html__('No', 'any')
					),
					'args' => array(
						"dependence" => true,
						'show'       => $boxed_show_array,
						'hide'       => $boxed_hide_array
					)
				)
			);

			$boxed_container = any_edge_add_admin_container_no_style(
				array_merge(
					array(
						'parent'            => $general_meta_box,
						'name'              => 'boxed_container',
						'hidden_property'   => 'edgtf_boxed_meta'
					),
					$temp_boxed_no
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_page_background_color_in_box_meta',
					'type'          => 'color',
					'label'         => esc_html__('Page Background Color', 'any'),
					'description'   => esc_html__('Choose the page background color outside box.', 'any'),
					'parent'        => $boxed_container
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_boxed_background_image_meta',
					'type'          => 'image',
					'label'         => esc_html__('Background Image', 'any'),
					'description'   => esc_html__('Choose an image to be displayed in background', 'any'),
					'parent'        => $boxed_container
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_boxed_background_image_repeating_meta',
					'type'          => 'select',
					'default_value' => 'no',
					'label'         => esc_html__('Use Background Image as Pattern', 'any'),
					'description'   => esc_html__('Set this option to "yes" to use the background image as repeating pattern', 'any'),
					'parent'        => $boxed_container,
					'options'       => array(
						'no'	=>	esc_html__('No', 'any'),
						'yes'	=>	esc_html__('Yes', 'any')

					)
				)
			);

			any_edge_add_meta_box_field(
				array(
					'name'          => 'edgtf_boxed_background_image_attachment_meta',
					'type'          => 'select',
					'default_value' => 'fixed',
					'label'         => esc_html__('Background Image Behaviour', 'any'),
					'description'   => esc_html__('Choose background image behaviour', 'any'),
					'parent'        => $boxed_container,
					'options'       => array(
						'fixed'     => esc_html__('Fixed', 'any'),
						'scroll'    => esc_html__('Scroll', 'any')
					)
				)
			);
		}

    add_action('any_edge_meta_boxes_map', 'any_edge_map_general_meta_fields');
}