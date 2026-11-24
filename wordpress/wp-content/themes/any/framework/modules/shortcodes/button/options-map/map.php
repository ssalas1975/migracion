<?php

if(!function_exists('any_edge_button_map')) {
    function any_edge_button_map() {
        $panel = any_edge_add_admin_panel(array(
            'title' => 'Button',
            'name'  => 'panel_button',
            'page'  => '_elements_page'
        ));

        //Typography options
        any_edge_add_admin_section_title(array(
            'name' => 'typography_section_title',
            'title' => 'Typography',
            'parent' => $panel
        ));

        $typography_group = any_edge_add_admin_group(array(
            'name' => 'typography_group',
            'title' => 'Typography',
            'description' => 'Setup typography for all button types',
            'parent' => $panel
        ));

        $typography_row = any_edge_add_admin_row(array(
            'name' => 'typography_row',
            'next' => true,
            'parent' => $typography_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $typography_row,
            'type'          => 'fontsimple',
            'name'          => 'button_font_family',
            'default_value' => '',
            'label'         => 'Font Family',
        ));

        any_edge_add_admin_field(array(
            'parent'        => $typography_row,
            'type'          => 'selectsimple',
            'name'          => 'button_text_transform',
            'default_value' => '',
            'label'         => 'Text Transform',
            'options'       => any_edge_get_text_transform_array()
        ));

        any_edge_add_admin_field(array(
            'parent'        => $typography_row,
            'type'          => 'selectsimple',
            'name'          => 'button_font_style',
            'default_value' => '',
            'label'         => 'Font Style',
            'options'       => any_edge_get_font_style_array()
        ));

        any_edge_add_admin_field(array(
            'parent'        => $typography_row,
            'type'          => 'textsimple',
            'name'          => 'button_letter_spacing',
            'default_value' => '',
            'label'         => 'Letter Spacing',
            'args'          => array(
                'suffix' => 'px'
            )
        ));

        $typography_row2 = any_edge_add_admin_row(array(
            'name' => 'typography_row2',
            'next' => true,
            'parent' => $typography_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $typography_row2,
            'type'          => 'selectsimple',
            'name'          => 'button_font_weight',
            'default_value' => '',
            'label'         => 'Font Weight',
            'options'       => any_edge_get_font_weight_array()
        ));

        //Outline type options
        any_edge_add_admin_section_title(array(
            'name' => 'type_section_title',
            'title' => 'Types',
            'parent' => $panel
        ));

        $outline_group = any_edge_add_admin_group(array(
            'name' => 'outline_group',
            'title' => 'Outline Type',
            'description' => 'Setup outline button type',
            'parent' => $panel
        ));

        $outline_row = any_edge_add_admin_row(array(
            'name' => 'outline_row',
            'next' => true,
            'parent' => $outline_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $outline_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_outline_text_color',
            'default_value' => '',
            'label'         => 'Text Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $outline_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_outline_hover_text_color',
            'default_value' => '',
            'label'         => 'Text Hover Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $outline_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_outline_hover_bg_color',
            'default_value' => '',
            'label'         => 'Hover Background Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $outline_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_outline_border_color',
            'default_value' => '',
            'label'         => 'Border Color',
            'description'   => ''
        ));

        $outline_row2 = any_edge_add_admin_row(array(
            'name' => 'outline_row2',
            'next' => true,
            'parent' => $outline_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $outline_row2,
            'type'          => 'colorsimple',
            'name'          => 'btn_outline_hover_border_color',
            'default_value' => '',
            'label'         => 'Hover Border Color',
            'description'   => ''
        ));

        //Solid type options
        $solid_group = any_edge_add_admin_group(array(
            'name' => 'solid_group',
            'title' => 'Solid Type',
            'description' => 'Setup solid button type',
            'parent' => $panel
        ));

        $solid_row = any_edge_add_admin_row(array(
            'name' => 'solid_row',
            'next' => true,
            'parent' => $solid_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_text_color',
            'default_value' => '',
            'label'         => 'Text Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_hover_text_color',
            'default_value' => '',
            'label'         => 'Text Hover Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_bg_color',
            'default_value' => '',
            'label'         => 'Background Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_hover_bg_color',
            'default_value' => '',
            'label'         => 'Hover Background Color',
            'description'   => ''
        ));

        $solid_row2 = any_edge_add_admin_row(array(
            'name' => 'solid_row2',
            'next' => true,
            'parent' => $solid_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row2,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_border_color',
            'default_value' => '',
            'label'         => 'Border Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_row2,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_hover_border_color',
            'default_value' => '',
            'label'         => 'Hover Border Color',
            'description'   => ''
        ));

      //Solid white type options
        $solid_white_group = any_edge_add_admin_group(array(
            'name' => 'solid_white_group',
            'title' => 'Solid White Type',
            'description' => 'Setup solid white button type',
            'parent' => $panel
        ));

        $solid_white_row = any_edge_add_admin_row(array(
            'name' => 'solid_white_row',
            'next' => true,
            'parent' => $solid_white_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_white_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_white_text_color',
            'default_value' => '',
            'label'         => 'Text Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_white_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_white_hover_text_color',
            'default_value' => '',
            'label'         => 'Text Hover Color',
            'description'   => ''
        ));


        any_edge_add_admin_field(array(
            'parent'        => $solid_white_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_white_hover_bg_color',
            'default_value' => '',
            'label'         => 'Hover Background Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_white_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_white_border_color',
            'default_value' => '',
            'label'         => 'Border Color',
            'description'   => ''
        ));

        $solid_white_row2 = any_edge_add_admin_row(array(
            'name' => 'solid_white_row2',
            'next' => true,
            'parent' => $solid_white_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $solid_white_row2,
            'type'          => 'colorsimple',
            'name'          => 'btn_solid_white_hover_border_color',
            'default_value' => '',
            'label'         => 'Hover Border Color',
            'description'   => ''
        ));

        //Transparent type options
        $transparent_group = any_edge_add_admin_group(array(
            'name' => 'transparent_group',
            'title' => 'Transparent Type',
            'description' => 'Setup transparent button type',
            'parent' => $panel
        ));

        $transparent_row = any_edge_add_admin_row(array(
            'name' => 'transparent_row',
            'next' => true,
            'parent' => $transparent_group
        ));

        any_edge_add_admin_field(array(
            'parent'        => $transparent_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_transparent_text_color',
            'default_value' => '',
            'label'         => 'Text Color',
            'description'   => ''
        ));

        any_edge_add_admin_field(array(
            'parent'        => $transparent_row,
            'type'          => 'colorsimple',
            'name'          => 'btn_transparent_hover_text_color',
            'default_value' => '',
            'label'         => 'Text Hover Color',
            'description'   => ''
        ));
    }

    add_action('any_edge_options_elements_map', 'any_edge_button_map');
}