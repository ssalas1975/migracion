<?php
if(!function_exists('any_edge_map_title_meta_fields')) {

    function any_edge_map_title_meta_fields() {

        $title_meta_box = any_edge_add_meta_box(
            array(
                'scope' => array('page', 'portfolio-item', 'post'),
                'title' => esc_html__('Title', 'any'),
                'name' => 'title_meta'
            )
        );

            any_edge_add_meta_box_field(
                array(
                    'name' => 'edgtf_show_title_area_meta',
                    'type' => 'select',
                    'default_value' => '',
                    'label' => esc_html__('Show Title Area', 'any'),
                    'description' => esc_html__('Disabling this option will turn off page title area', 'any'),
                    'parent' => $title_meta_box,
                    'options' => array(
                        '' => '',
                        'no' => esc_html__('No', 'any'),
                        'yes' => esc_html__('Yes', 'any')
                    ),
                    'args' => array(
                        "dependence" => true,
                        "hide" => array(
                            "" => "",
                            "no" => "#edgtf_edgtf_show_title_area_meta_container",
                            "yes" => ""
                        ),
                        "show" => array(
                            "" => "#edgtf_edgtf_show_title_area_meta_container",
                            "no" => "",
                            "yes" => "#edgtf_edgtf_show_title_area_meta_container"
                        )
                    )
                )
            );

            $show_title_area_meta_container = any_edge_add_admin_container(
                array(
                    'parent' => $title_meta_box,
                    'name' => 'edgtf_show_title_area_meta_container',
                    'hidden_property' => 'edgtf_show_title_area_meta',
                    'hidden_value' => 'no'
                )
            );


                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_in_grid_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Title Area in Grid', 'any'),
                        'description' => esc_html__('Choose wheter for title content to be in grid', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'options' => array(
                            '' => '',
                            'yes' => esc_html__('Yes', 'any'),
                            'no' => esc_html__('No', 'any')
                        )
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_type_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Title Area Type', 'any'),
                        'description' => esc_html__('Choose title type', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'options' => array(
                            '' => '',
                            'standard' => esc_html__('Standard', 'any'),
                            'breadcrumb' => esc_html__('Breadcrumb', 'any')
                        ),
                        'args' => array(
                            "dependence" => true,
                            "hide" => array(
                                "standard" => "",
                                "standard" => "",
                                "breadcrumb" => "#edgtf_edgtf_title_area_type_meta_container"
                            ),
                            "show" => array(
                                "" => "#edgtf_edgtf_title_area_type_meta_container",
                                "standard" => "#edgtf_edgtf_title_area_type_meta_container",
                                "breadcrumb" => ""
                            )
                        )
                    )
                );

                $title_area_type_meta_container = any_edge_add_admin_container(
                    array(
                        'parent' => $show_title_area_meta_container,
                        'name' => 'edgtf_title_area_type_meta_container',
                        'hidden_property' => 'edgtf_title_area_type_meta',
                        'hidden_value' => '',
                        'hidden_values' => array('breadcrumb'),
                    )
                );

                    any_edge_add_meta_box_field(
                        array(
                            'name' => 'edgtf_title_area_enable_breadcrumbs_meta',
                            'type' => 'select',
                            'default_value' => '',
                            'label' => esc_html__('Enable Breadcrumbs', 'any'),
                            'description' => esc_html__('This option will display Breadcrumbs in Title Area', 'any'),
                            'parent' => $title_area_type_meta_container,
                            'options' => array(
                                '' => '',
                                'no' => esc_html__('No', 'any'),
                                'yes' => esc_html__('Yes', 'any')
                            ),
                        )
                    );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_animation_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Animations', 'any'),
                        'description' => esc_html__('Choose an animation for Title Area', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'options' => array(
                            '' => '',
                            'no' => esc_html__('No Animation', 'any'),
                            'right-left' => esc_html__('Text right to left', 'any'),
                            'left-right' => esc_html__('Text left to right', 'any')
                        )
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_vertial_alignment_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Vertical Alignment', 'any'),
                        'description' => esc_html__('Specify title vertical alignment', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'options' => array(
                            '' => '',
                            'header_bottom' => esc_html__('From Bottom of Header', 'any'),
                            'window_top' => esc_html__('From Window Top', 'any')
                        )
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_content_alignment_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Horizontal Alignment', 'any'),
                        'description' => esc_html__('Specify title horizontal alignment', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'options' => array(
                            '' => '',
                            'left' => esc_html__('Left', 'any'),
                            'center' => esc_html__('Center', 'any'),
                            'right' => esc_html__('Right', 'any')
                        )
                    )
                );

        		any_edge_add_meta_box_field(
        			array(
        				'name'			=> 'edgtf_title_area_text_size_meta',
        				'type'			=> 'select',
        				'default_value'	=> '',
        				'label'			=> esc_html__('Text Size', 'any'),
        				'description'	=> esc_html__('Choose a default Title size', 'any'),
        				'parent'		=> $show_title_area_meta_container,
        				'options'		=> array(
        					'' => '',
        					'small'     => esc_html__('Small', 'any'),
        					'medium'    => esc_html__('Medium', 'any'),
        					'large'     => esc_html__('Large', 'any')


        				)
        			)
        		);

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_text_color_meta',
                        'type' => 'color',
                        'label' => esc_html__('Title Color', 'any'),
                        'description' => esc_html__('Choose a color for title text', 'any'),
                        'parent' => $show_title_area_meta_container
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_breadcrumb_color_meta',
                        'type' => 'color',
                        'label' => esc_html__('Breadcrumb Color', 'any'),
                        'description' => esc_html__('Choose a color for breadcrumb text', 'any'),
                        'parent' => $show_title_area_meta_container
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_background_color_meta',
                        'type' => 'color',
                        'label' => esc_html__('Background Color', 'any'),
                        'description' => esc_html__('Choose a background color for Title Area', 'any'),
                        'parent' => $show_title_area_meta_container
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_hide_background_image_meta',
                        'type' => 'yesno',
                        'default_value' => 'no',
                        'label' => esc_html__('Hide Background Image', 'any'),
                        'description' => esc_html__('Enable this option to hide background image in Title Area', 'any'),
                        'parent' => $show_title_area_meta_container,
                        'args' => array(
                            "dependence" => true,
                            "dependence_hide_on_yes" => "#edgtf_edgtf_hide_background_image_meta_container",
                            "dependence_show_on_yes" => ""
                        )
                    )
                );

                $hide_background_image_meta_container = any_edge_add_admin_container(
                    array(
                        'parent' => $show_title_area_meta_container,
                        'name' => 'edgtf_hide_background_image_meta_container',
                        'hidden_property' => 'edgtf_hide_background_image_meta',
                        'hidden_value' => 'yes'
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_background_image_meta',
                        'type' => 'image',
                        'label' => esc_html__('Background Image', 'any'),
                        'description' => esc_html__('Choose an Image for Title Area', 'any'),
                        'parent' => $hide_background_image_meta_container
                    )
                );

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_title_area_background_image_responsive_meta',
                        'type' => 'select',
                        'default_value' => '',
                        'label' => esc_html__('Background Responsive Image', 'any'),
                        'description' => esc_html__('Enabling this option will make Title background image responsive', 'any'),
                        'parent' => $hide_background_image_meta_container,
                        'options' => array(
                            '' => '',
                            'no' => esc_html__('No', 'any'),
                            'yes' => esc_html__('Yes', 'any')
                        ),
                        'args' => array(
                            "dependence" => true,
                            "hide" => array(
                                "" => "",
                                "no" => "",
                                "yes" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta"
                            ),
                            "show" => array(
                                "" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta",
                                "no" => "#edgtf_edgtf_title_area_background_image_responsive_meta_container, #edgtf_edgtf_title_area_height_meta",
                                "yes" => ""
                            )
                        )
                    )
                );

                $title_area_background_image_responsive_meta_container = any_edge_add_admin_container(
                    array(
                        'parent' => $hide_background_image_meta_container,
                        'name' => 'edgtf_title_area_background_image_responsive_meta_container',
                        'hidden_property' => 'edgtf_title_area_background_image_responsive_meta',
                        'hidden_value' => 'yes'
                    )
                );

                    any_edge_add_meta_box_field(
                        array(
                            'name' => 'edgtf_title_area_background_image_parallax_meta',
                            'type' => 'select',
                            'default_value' => '',
                            'label' => esc_html__('Background Image in Parallax', 'any'),
                            'description' => esc_html__('Enabling this option will make Title background image parallax', 'any'),
                            'parent' => $title_area_background_image_responsive_meta_container,
                            'options' => array(
                                '' => '',
                                'no' => esc_html__('No', 'any'),
                                'yes' => esc_html__('Yes', 'any'),
                                'yes_zoom' => esc_html__('Yes, with zoom out', 'any')
                            )
                        )
                    );

                any_edge_add_meta_box_field(array(
                    'name' => 'edgtf_title_area_height_meta',
                    'type' => 'text',
                    'label' => esc_html__('Height', 'any'),
                    'description' => esc_html__('Set a height for Title Area', 'any'),
                    'parent' => $show_title_area_meta_container,
                    'args' => array(
                        'col_width' => 2,
                        'suffix' => 'px'
                    )
                ));

                any_edge_add_meta_box_field(array(
                    'name' => 'edgtf_title_area_subtitle_meta',
                    'type' => 'text',
                    'default_value' => '',
                    'label' => esc_html__('Subtitle Text', 'any'),
                    'description' => esc_html__('Enter your subtitle text', 'any'),
                    'parent' => $show_title_area_meta_container,
                    'args' => array(
                        'col_width' => 6
                    )
                ));

                any_edge_add_meta_box_field(
                    array(
                        'name' => 'edgtf_subtitle_color_meta',
                        'type' => 'color',
                        'label' => esc_html__('Subtitle Color', 'any'),
                        'description' => esc_html__('Choose a color for subtitle text', 'any'),
                        'parent' => $show_title_area_meta_container
                    )
                );

        		any_edge_add_meta_box_field(
        			array(
        				'name'			=> 'edgtf_subtitle_size_meta',
        				'type'			=> 'select',
        				'default_value'	=> '',
        				'label'			=> esc_html__('Subtitle Text Size', 'any'),
        				'description'	=> esc_html__('Choose a default subtitle size', 'any'),
        				'parent'		=> $show_title_area_meta_container,
        				'options'		=> array(
        					'' => '',
        					'small'     => esc_html__('Small', 'any'),
        					'medium'    => esc_html__('Medium', 'any'),
        					'large'     => esc_html__('Large', 'any')
        				)
        			)
        		);
            }

    add_action('any_edge_meta_boxes_map', 'any_edge_map_title_meta_fields');
}