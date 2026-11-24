<?php

if(!function_exists('edgtf_quadric_header_top_bar_styles')) {
    /**
     * Generates styles for header top bar
     */
    function edgtf_quadric_header_top_bar_styles() {
        global $edgtf_quadric_options;

        if($edgtf_quadric_options['top_bar_height'] !== '') {
            echo edgtf_quadric_dynamic_css('.edgtf-top-bar', array('height' => $edgtf_quadric_options['top_bar_height'].'px'));
            echo edgtf_quadric_dynamic_css('.edgtf-top-bar .edgtf-logo-wrapper a', array('max-height' => $edgtf_quadric_options['top_bar_height'].'px'));
        }

        if($edgtf_quadric_options['top_bar_in_grid'] == 'yes') {
            $top_bar_grid_selector = '.edgtf-top-bar .edgtf-grid .edgtf-vertical-align-containers';
            $top_bar_grid_styles = array();
            if($edgtf_quadric_options['top_bar_grid_background_color'] !== '') {
                $grid_background_color    = $edgtf_quadric_options['top_bar_grid_background_color'];
                $grid_background_transparency = 1;

                if(edgtf_quadric_options()->getOptionValue('top_bar_grid_background_transparency')) {
                    $grid_background_transparency = edgtf_quadric_options()->getOptionValue('top_bar_grid_background_transparency');
                }

                $grid_background_color = edgtf_quadric_rgba_color($grid_background_color, $grid_background_transparency);
                $top_bar_grid_styles['background-color'] = $grid_background_color;
            }

            echo edgtf_quadric_dynamic_css($top_bar_grid_selector, $top_bar_grid_styles);
        }

        $background_color = edgtf_quadric_options()->getOptionValue('top_bar_background_color');
        $top_bar_styles = array();
        if($background_color !== '') {
            $background_transparency = 1;
            if(edgtf_quadric_options()->getOptionValue('top_bar_background_transparency') !== '') {
               $background_transparency = edgtf_quadric_options()->getOptionValue('top_bar_background_transparency');
            }

            $background_color = edgtf_quadric_rgba_color($background_color, $background_transparency);
            $top_bar_styles['background-color'] = $background_color;
        }

        echo edgtf_quadric_dynamic_css('.edgtf-top-bar', $top_bar_styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_header_top_bar_styles');
}


if(!function_exists('edgtf_quadric_header_standard_menu_area_styles')) {
    /**
     * Generates styles for header standard menu
     */
    function edgtf_quadric_header_standard_menu_area_styles() {
        global $edgtf_quadric_options;

        $menu_area_header_standard_styles = array();
		$menu_area_header_standard_icons_styles = array();

        if($edgtf_quadric_options['menu_area_background_color_header_standard'] !== '') {
            $menu_area_background_color        = $edgtf_quadric_options['menu_area_background_color_header_standard'];
            $menu_area_background_transparency = 1;

            if($edgtf_quadric_options['menu_area_background_transparency_header_standard'] !== '') {
                $menu_area_background_transparency = $edgtf_quadric_options['menu_area_background_transparency_header_standard'];
            }

            $menu_area_header_standard_styles['background-color'] = edgtf_quadric_rgba_color($menu_area_background_color, $menu_area_background_transparency);
        }

        if($edgtf_quadric_options['menu_area_height_header_standard'] !== '') {
            $max_height = intval(edgtf_quadric_filter_px($edgtf_quadric_options['menu_area_height_header_standard']) * 0.9).'px';
            echo edgtf_quadric_dynamic_css('.edgtf-header-standard .edgtf-page-header .edgtf-logo-wrapper a', array('max-height' => $max_height));

            $menu_area_header_standard_styles['height'] = edgtf_quadric_filter_px($edgtf_quadric_options['menu_area_height_header_standard']).'px';
            $menu_area_header_standard_icons_styles['width'] = edgtf_quadric_filter_px($edgtf_quadric_options['menu_area_height_header_standard']).'px';

        }

        echo edgtf_quadric_dynamic_css('.edgtf-header-standard .edgtf-page-header .edgtf-menu-area', $menu_area_header_standard_styles);
        echo edgtf_quadric_dynamic_css('.edgtf-header-standard .edgtf-menu-area .edgtf-vertical-align-containers .edgtf-position-right-inner .edgtf-search-opener, .edgtf-header-standard .edgtf-menu-area .edgtf-vertical-align-containers .edgtf-position-right-inner .edgtf-side-menu-button-opener', $menu_area_header_standard_icons_styles);


        $menu_area_grid_header_standard_styles = array();

        if($edgtf_quadric_options['menu_area_in_grid_header_standard'] == 'yes' && $edgtf_quadric_options['menu_area_grid_background_color_header_standard'] !== '') {
            $menu_area_grid_background_color        = $edgtf_quadric_options['menu_area_grid_background_color_header_standard'];
            $menu_area_grid_background_transparency = 1;

            if($edgtf_quadric_options['menu_area_grid_background_transparency_header_standard'] !== '') {
                $menu_area_grid_background_transparency = $edgtf_quadric_options['menu_area_grid_background_transparency_header_standard'];
            }

            $menu_area_grid_header_standard_styles['background-color'] = edgtf_quadric_rgba_color($menu_area_grid_background_color, $menu_area_grid_background_transparency);
        }

        echo edgtf_quadric_dynamic_css('.edgtf-header-standard .edgtf-page-header .edgtf-menu-area .edgtf-grid .edgtf-vertical-align-containers', $menu_area_grid_header_standard_styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_header_standard_menu_area_styles');
}

if(!function_exists('edgtf_quadric_vertical_menu_styles')) {
    /**
     * Generates styles for sticky haeder
     */
    function edgtf_quadric_vertical_menu_styles() {

        $vertical_header_styles = array();

        $vertical_header_selectors = array(
            '.edgtf-header-vertical .edgtf-vertical-area-background'
        );

        if(edgtf_quadric_options()->getOptionValue('vertical_header_background_color') !== '') {
            $vertical_header_styles['background-color'] = edgtf_quadric_options()->getOptionValue('vertical_header_background_color');
        }

        if(edgtf_quadric_options()->getOptionValue('vertical_header_transparency') !== '') {
            $vertical_header_styles['opacity'] = edgtf_quadric_options()->getOptionValue('vertical_header_transparency');
        }

        if(edgtf_quadric_options()->getOptionValue('vertical_header_background_image') !== '') {
            $vertical_header_styles['background-image'] = 'url('.edgtf_quadric_options()->getOptionValue('vertical_header_background_image').')';
        }


        echo edgtf_quadric_dynamic_css($vertical_header_selectors, $vertical_header_styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_vertical_menu_styles');
}


if(!function_exists('edgtf_quadric_sticky_header_styles')) {
    /**
     * Generates styles for sticky haeder
     */
    function edgtf_quadric_sticky_header_styles() {
        global $edgtf_quadric_options;

        if($edgtf_quadric_options['sticky_header_in_grid'] == 'yes' && $edgtf_quadric_options['sticky_header_grid_background_color'] !== '') {
            $sticky_header_grid_background_color        = $edgtf_quadric_options['sticky_header_grid_background_color'];
            $sticky_header_grid_background_transparency = 1;

            if($edgtf_quadric_options['sticky_header_grid_transparency'] !== '') {
                $sticky_header_grid_background_transparency = $edgtf_quadric_options['sticky_header_grid_transparency'];
            }

            echo edgtf_quadric_dynamic_css('.edgtf-page-header .edgtf-sticky-header .edgtf-grid .edgtf-vertical-align-containers', array('background-color' => edgtf_quadric_rgba_color($sticky_header_grid_background_color, $sticky_header_grid_background_transparency)));
        }

        if($edgtf_quadric_options['sticky_header_background_color'] !== '') {

            $sticky_header_background_color              = $edgtf_quadric_options['sticky_header_background_color'];
            $sticky_header_background_color_transparency = 1;

            if($edgtf_quadric_options['sticky_header_transparency'] !== '') {
                $sticky_header_background_color_transparency = $edgtf_quadric_options['sticky_header_transparency'];
            }

            echo edgtf_quadric_dynamic_css('.edgtf-page-header .edgtf-sticky-header .edgtf-sticky-holder', array('background-color' => edgtf_quadric_rgba_color($sticky_header_background_color, $sticky_header_background_color_transparency)));
        }

        if($edgtf_quadric_options['sticky_header_height'] !== '') {
            $max_height = intval(edgtf_quadric_filter_px($edgtf_quadric_options['sticky_header_height']) * 0.9).'px';

            echo edgtf_quadric_dynamic_css('.edgtf-page-header .edgtf-sticky-header', array('height' => $edgtf_quadric_options['sticky_header_height'].'px'));
            echo edgtf_quadric_dynamic_css('.edgtf-page-header .edgtf-sticky-header .edgtf-logo-wrapper a', array('max-height' => $max_height));
        }

        $sticky_menu_item_styles = array();
        if($edgtf_quadric_options['sticky_color'] !== '') {
            $sticky_menu_item_styles['color'] = $edgtf_quadric_options['sticky_color'];
        }
        if($edgtf_quadric_options['sticky_google_fonts'] !== '-1') {
            $sticky_menu_item_styles['font-family'] = edgtf_quadric_get_formatted_font_family($edgtf_quadric_options['sticky_google_fonts']);
        }
        if($edgtf_quadric_options['sticky_fontsize'] !== '') {
            $sticky_menu_item_styles['font-size'] = $edgtf_quadric_options['sticky_fontsize'].'px';
        }
        if($edgtf_quadric_options['sticky_lineheight'] !== '') {
            $sticky_menu_item_styles['line-height'] = $edgtf_quadric_options['sticky_lineheight'].'px';
        }
        if($edgtf_quadric_options['sticky_texttransform'] !== '') {
            $sticky_menu_item_styles['text-transform'] = $edgtf_quadric_options['sticky_texttransform'];
        }
        if($edgtf_quadric_options['sticky_fontstyle'] !== '') {
            $sticky_menu_item_styles['font-style'] = $edgtf_quadric_options['sticky_fontstyle'];
        }
        if($edgtf_quadric_options['sticky_fontweight'] !== '') {
            $sticky_menu_item_styles['font-weight'] = $edgtf_quadric_options['sticky_fontweight'];
        }
        if($edgtf_quadric_options['sticky_letterspacing'] !== '') {
            $sticky_menu_item_styles['letter-spacing'] = $edgtf_quadric_options['sticky_letterspacing'].'px';
        }

        $sticky_menu_item_selector = array(
            '.edgtf-main-menu.edgtf-sticky-nav > ul > li > a'
        );

        echo edgtf_quadric_dynamic_css($sticky_menu_item_selector, $sticky_menu_item_styles);

        $sticky_menu_item_hover_styles = array();
        if($edgtf_quadric_options['sticky_hovercolor'] !== '') {
            $sticky_menu_item_hover_styles['color'] = $edgtf_quadric_options['sticky_hovercolor'];
        }

        $sticky_menu_item_hover_selector = array(
            '.edgtf-main-menu.edgtf-sticky-nav > ul > li:hover > a',
            '.edgtf-main-menu.edgtf-sticky-nav > ul > li.edgtf-active-item:hover > a',
            'body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-sticky-nav > ul > li:hover > a',
            'body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-sticky-nav > ul > li.edgtf-active-item:hover > a'
        );

        echo edgtf_quadric_dynamic_css($sticky_menu_item_hover_selector, $sticky_menu_item_hover_styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_sticky_header_styles');
}

if(!function_exists('edgtf_quadric_fixed_header_styles')) {
    /**
     * Generates styles for fixed haeder
     */
    function edgtf_quadric_fixed_header_styles() {
        global $edgtf_quadric_options;

        if($edgtf_quadric_options['fixed_header_grid_background_color'] !== '') {

            $fixed_header_grid_background_color              = $edgtf_quadric_options['fixed_header_grid_background_color'];
            $fixed_header_grid_background_color_transparency = 1;

            if($edgtf_quadric_options['fixed_header_grid_transparency'] !== '') {
                $fixed_header_grid_background_color_transparency = $edgtf_quadric_options['fixed_header_grid_transparency'];
            }

            echo edgtf_quadric_dynamic_css('.edgtf-header-type1 .edgtf-fixed-wrapper.fixed .edgtf-grid .edgtf-vertical-align-containers,
                                    .edgtf-header-type3 .edgtf-fixed-wrapper.fixed .edgtf-grid .edgtf-vertical-align-containers',
                array('background-color' => edgtf_quadric_rgba_color($fixed_header_grid_background_color, $fixed_header_grid_background_color_transparency)));
        }

        if($edgtf_quadric_options['fixed_header_background_color'] !== '') {

            $fixed_header_background_color              = $edgtf_quadric_options['fixed_header_background_color'];
            $fixed_header_background_color_transparency = 1;

            if($edgtf_quadric_options['fixed_header_transparency'] !== '') {
                $fixed_header_background_color_transparency = $edgtf_quadric_options['fixed_header_transparency'];
            }

            echo edgtf_quadric_dynamic_css('.edgtf-header-type1 .edgtf-fixed-wrapper.fixed .edgtf-menu-area,
                                    .edgtf-header-type3 .edgtf-fixed-wrapper.fixed .edgtf-menu-area',
                array('background-color' => edgtf_quadric_rgba_color($fixed_header_background_color, $fixed_header_background_color_transparency)));
        }

    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_fixed_header_styles');
}

if(!function_exists('edgtf_quadric_main_menu_styles')) {
    /**
     * Generates styles for main menu
     */
    function edgtf_quadric_main_menu_styles() {
        global $edgtf_quadric_options;

        if($edgtf_quadric_options['menu_color'] !== '' || $edgtf_quadric_options['menu_fontsize'] != '' || $edgtf_quadric_options['menu_fontstyle'] !== '' || $edgtf_quadric_options['menu_fontweight'] !== '' || $edgtf_quadric_options['menu_texttransform'] !== '' || $edgtf_quadric_options['menu_letterspacing'] !== '' || $edgtf_quadric_options['menu_google_fonts'] != "-1") { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li > a,
            .edgtf-page-header #lang_sel > ul > li > a,
            .edgtf-page-header #lang_sel_click > ul > li > a,
            .edgtf-page-header #lang_sel ul > li:hover > a{
            <?php if($edgtf_quadric_options['menu_color']) { ?> color: <?php echo esc_attr($edgtf_quadric_options['menu_color']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['menu_google_fonts'] != "-1") { ?>
                font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['menu_google_fonts'])); ?>', sans-serif;
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_fontsize'] !== '') { ?> font-size: <?php echo esc_attr($edgtf_quadric_options['menu_fontsize']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['menu_fontstyle'] !== '') { ?> font-style: <?php echo esc_attr($edgtf_quadric_options['menu_fontstyle']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['menu_fontweight'] !== '') { ?> font-weight: <?php echo esc_attr($edgtf_quadric_options['menu_fontweight']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['menu_texttransform'] !== '') { ?> text-transform: <?php echo esc_attr($edgtf_quadric_options['menu_texttransform']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['menu_letterspacing'] !== '') { ?> letter-spacing: <?php echo esc_attr($edgtf_quadric_options['menu_letterspacing']); ?>px; <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_google_fonts'] != "-1") { ?>
            .edgtf-page-header #lang_sel_list{
            font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['menu_google_fonts'])); ?>', sans-serif !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_hovercolor'] !== '') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
            .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a,
            body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
            body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a,
            .edgtf-page-header #lang_sel ul li a:hover,
            .edgtf-page-header #lang_sel_click > ul > li a:hover{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_hovercolor']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_activecolor'] !== '') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a,
            body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_activecolor']); ?>;
            }
        <?php } ?>        

        <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item' && ($edgtf_quadric_options['menu_text_background_color'] !== '' || $edgtf_quadric_options['enable_manu_item_border'] == 'yes')) { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner{
            <?php if($edgtf_quadric_options['menu_text_background_color'] !== '') { ?> background-color: <?php echo esc_attr($edgtf_quadric_options['menu_text_background_color']); ?>; <?php } ?>

            <?php if($edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>

                <?php if($edgtf_quadric_options['menu_item_border_radius'] !== '') { ?> border-radius: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_radius']); ?>px;<?php } ?>

                <?php if($edgtf_quadric_options['menu_item_border_style_style'] !== '') { ?> border-style: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_style_style']); ?>; <?php } ?>


                <?php if($edgtf_quadric_options['menu_item_border_width'] !== '') { ?> border-width: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_width']); ?>px; <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?> border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_color']); ?>; <?php } ?>

                <?php if($edgtf_quadric_options['menu_item_border_style'] !== '') { ?>

                    <?php if($edgtf_quadric_options['menu_item_border_style'] == 'top_bottom_borders') { ?>
                        border-left: none;
                        border-right: none;
                    <?php } ?>

                    <?php if($edgtf_quadric_options['menu_item_border_style'] == 'right_border') { ?>
                        border-left: none;
                        border-top: none;
                        border-bottom: none;
                    <?php } ?>

                    <?php if($edgtf_quadric_options['menu_item_border_style'] == 'bottom_border') { ?>
                        border-left: none;
                        border-top: none;
                        border-right: none;
                    <?php } ?>

                <?php } ?>
            <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_border_style'] == 'right_border') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li:last-child > a span.item_inner{
            border-right: none;
            }

            .edgtf-main-menu.edgtf-default-nav > ul > li:last-child > a{
            border-right: none;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li > a{
            <?php if($edgtf_quadric_options['menu_text_background_color'] !== '') { ?> background-color: <?php echo esc_attr($edgtf_quadric_options['menu_text_background_color']); ?>; <?php } ?>

            <?php if($edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
                <?php if($edgtf_quadric_options['menu_item_border_width'] !== '') { ?> border-width: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_width']); ?>px; <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?> border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_color']); ?>; <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_border_radius'] !== '') { ?>border-radius: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_radius']); ?>px;<?php } ?>

                <?php if($edgtf_quadric_options['menu_item_border_style_style'] !== '') { ?> border-style: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_style_style']); ?>; <?php } ?>

                <?php if($edgtf_quadric_options['menu_item_border_style'] !== '') { ?>

                    <?php if(($edgtf_quadric_options['menu_item_border_style'] == 'top_bottom_borders')) { ?>
                        border-left: none;
                        border-right: none;
                    <?php } ?>

                    <?php if(($edgtf_quadric_options['menu_item_border_style'] == 'right_border')) { ?>
                        border-left: none;
                        border-top: none;
                        border-bottom: none;
                    <?php } ?>
                    <?php if(($edgtf_quadric_options['menu_item_border_style'] == 'bottom_border')) { ?>
                        border-left: none;
                        border-top: none;
                        border-right: none;
                    <?php } ?>

                <?php } ?>
            <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_hover_background_color'] !== '') {
            $menu_hover_background_color = $edgtf_quadric_options['menu_hover_background_color'];

            if($edgtf_quadric_options['menu_hover_background_color_transparency'] !== '') {
                $menu_hover_background_color_rgb = edgtf_quadric_hex2rgb($menu_hover_background_color);
                $menu_hover_background_color     = 'rgba('.$menu_hover_background_color_rgb[0].', '.$menu_hover_background_color_rgb[1].', '.$menu_hover_background_color_rgb[2].', '.$edgtf_quadric_options['menu_hover_background_color_transparency'].')';
            } ?>

            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a span.item_inner {
                background-color: <?php echo esc_attr($menu_hover_background_color); ?>;
                }
            <?php } elseif($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a {
                background-color: <?php echo esc_attr($menu_hover_background_color); ?>;
                }
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_active_background_color'] !== '') {
            $menu_active_background_color = $edgtf_quadric_options['menu_active_background_color'];

            if($edgtf_quadric_options['menu_active_background_color_transparency'] !== '') {
                $menu_active_background_color_rgb = edgtf_quadric_hex2rgb($menu_active_background_color);
                $menu_active_background_color     = 'rgba('.$menu_active_background_color_rgb[0].', '.$menu_active_background_color_rgb[1].', '.$menu_active_background_color_rgb[2].', '.$edgtf_quadric_options['menu_active_background_color_transparency'].')';
            }
            ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner {
                background-color: <?php echo esc_attr($menu_active_background_color); ?>;
                }
            <?php } elseif($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a {
                background-color: <?php echo esc_attr($menu_active_background_color); ?>;
                }
            <?php } ?>
        <?php } ?>


        <?php if($edgtf_quadric_options['menu_light_hovercolor'] !== '') { ?>
            .light .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
            .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_light_hovercolor']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_light_activecolor'] !== '') { ?>
            .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_light_activecolor']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_dark_hovercolor'] !== '') { ?>
            .dark .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
            .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_dark_hovercolor']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_dark_activecolor'] !== '') { ?>
            .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
            color: <?php echo esc_attr($edgtf_quadric_options['menu_dark_activecolor']); ?>;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>

            <?php if($edgtf_quadric_options['menu_light_border_color'] !== '') { ?>
                <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner,
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                    border-color: <?php echo esc_attr($edgtf_quadric_options['menu_light_border_color']); ?> !important;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
                    border-color: <?php echo esc_attr($edgtf_quadric_options['menu_light_border_color']); ?> !important;
                    }
                <?php } ?>
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_dark_border_color'] !== '') { ?>
                <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner,
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                    border-color: <?php echo esc_attr($edgtf_quadric_options['menu_dark_border_color']); ?>!important;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
                    border-color: <?php echo esc_attr($edgtf_quadric_options['menu_dark_border_color']); ?>!important;
                    }
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_lineheight'] != "" || $edgtf_quadric_options['menu_padding_left_right'] !== '') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner{
            <?php if($edgtf_quadric_options['menu_lineheight'] !== '') { ?> line-height: <?php echo esc_attr($edgtf_quadric_options['menu_lineheight']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['menu_padding_left_right']) { ?> padding: 0  <?php echo esc_attr($edgtf_quadric_options['menu_padding_left_right']); ?>px; <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_margin_left_right'] !== '') { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li{
            margin: 0  <?php echo esc_attr($edgtf_quadric_options['menu_margin_left_right']); ?>px;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?>
                .light .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner{
                border-color: #fff;
                }
                .dark .edgtf-main-menu.edgtf-default-nav > ul > li a span.item_inner{
                border-color: #000;
                }
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '') { ?>
                .light .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner{
                border-color: #fff;
                }
                .dark .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner{
                border-color: #000;
                }
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_active_border_color']) { ?>
                .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                border-color: #fff;
                }
                .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                border-color: #000;
                }
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['header_style'] == 'light') { ?>

                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?>
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li > a{
                    border-color: #fff;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '') { ?>
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a{
                    border-color: #fff;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_active_border_color'] !== '') { ?>
                    .light .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
                    border-color: #fff;
                    }
                <?php } ?>
            <?php } ?>
            <?php if($edgtf_quadric_options['header_style'] == 'dark') { ?>

                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?>
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li > a{
                    border-color: #000;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '') { ?>
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a{
                    border-color: #000;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_active_border_color'] !== '') { ?>
                    .dark .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
                    border-color: #000;
                    }
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_border_style'] == 'bottom_border_double' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_color']); ?>;
                    display:block;
                    }
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a span.item_inner{
                    border:none;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_active_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_active_border_color']); ?>;
                    }
                    .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                    border:none;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_hover_border_color']); ?>;
                    }
                    .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner{
                    border:none;
                    }
                <?php } ?>
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                <?php if($edgtf_quadric_options['menu_item_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_border_color']); ?>;
                    display:block;
                    }
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a{
                    border:none;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_active_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_active_border_color']); ?>;
                    }
                    nav.main_menu > ul > li.edgtf-active-item > a{
                    border:none;
                    }
                <?php } ?>
                <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '') { ?>
                    .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a:before,
                    .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a:after{
                    background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_hover_border_color']); ?>;
                    }
                    .edgtf-main-menu.edgtf-default-nav > ul > li > a{
                    border:none;
                    }
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_border_style'] == 'right_border' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:last-child > a span.item_inner{
                border-right:none;
                }
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:last-child > a {
                border-right:none;
                }
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_hover_border_color'] !== '' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a span.item_inner,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a span.item_inner,
                header:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li:hover a span.item_inner{
                border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_hover_border_color']); ?>;
                }
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item:hover > a,
                header:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li:hover > a{
                border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_hover_border_color']); ?>;
                }
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['menu_item_active_border_color'] !== '' && $edgtf_quadric_options['enable_manu_item_border'] == 'yes') { ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'small_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner,
                header:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a span.item_inner{
                border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_active_border_color']); ?>;
                }
            <?php } ?>
            <?php if($edgtf_quadric_options['menu_item_style'] == 'large_item') { ?>
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item,
                .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item a,
                header:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu.edgtf-default-nav > ul > li.edgtf-active-item > a{
                border-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_active_border_color']); ?>;
                }
            <?php } ?>
        <?php } ?>

        <?php if($edgtf_quadric_options['enable_menu_item_separators'] == "yes") { ?>
            .edgtf-main-menu.edgtf-default-nav > ul > li > a span.plus{
            display: block;
            background-color: <?php echo esc_attr($edgtf_quadric_options['menu_item_separators_color']); ?>;
            }
        <?php } ?>        

        <?php
        if($edgtf_quadric_options['dropdown_background_color'] != "" || $edgtf_quadric_options['dropdown_background_transparency'] != "") {

            //dropdown background and transparency styles
            $dropdown_bg_color_initial        = '#ffffff';
            $dropdown_bg_transparency_initial = 1;

            $dropdown_bg_color        = $edgtf_quadric_options['dropdown_background_color'] !== "" ? $edgtf_quadric_options['dropdown_background_color'] : $dropdown_bg_color_initial;
            $dropdown_bg_transparency = $edgtf_quadric_options['dropdown_background_transparency'] !== "" ? $edgtf_quadric_options['dropdown_background_transparency'] : $dropdown_bg_transparency_initial;

            $dropdown_bg_color_rgb = edgtf_quadric_hex2rgb($dropdown_bg_color);

            ?>

            .edgtf-drop-down .second .inner ul,
            .edgtf-drop-down .second .inner ul li ul,
            .shopping_cart_dropdown,
            li.narrow .second .inner ul,
            .edgtf-main-menu.edgtf-default-nav #lang_sel ul ul,
            .edgtf-main-menu.edgtf-default-nav #lang_sel_click  ul ul,
            .header-widget.widget_nav_menu ul ul,
            .edgtf-drop-down .wide.wide_background .second{
            background-color: <?php echo esc_attr($dropdown_bg_color); ?>;
            background-color: rgba(<?php echo esc_attr($dropdown_bg_color_rgb[0]); ?>,<?php echo esc_attr($dropdown_bg_color_rgb[1]); ?>,<?php echo esc_attr($dropdown_bg_color_rgb[2]); ?>,<?php echo esc_attr($dropdown_bg_transparency); ?>);
            }

        <?php } //end dropdown background and transparency styles ?>

        <?php if($edgtf_quadric_options['dropdown_top_separator_color'] !== '') { ?>
            .edgtf-drop-down .second{
            border-top-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_top_separator_color']); ?>;
            }
        <?php } ?>

        <?php
        if($edgtf_quadric_options['dropdown_top_padding'] !== '' || $edgtf_quadric_options['dropdown_border_around'] == "no") {

            $menu_inner_ul_top = 15; //default value without border
            if($edgtf_quadric_options['dropdown_top_padding'] !== '') {
                ?>
                li.narrow .second .inner ul,
                .edgtf-drop-down .wide .second .inner > ul{
                padding-top: <?php echo esc_attr($edgtf_quadric_options['dropdown_top_padding']); ?>px;
                }
                <?php
                $menu_inner_ul_top = $edgtf_quadric_options['dropdown_top_padding']; //overwrite default value
            } ?>
            <?php if($edgtf_quadric_options['dropdown_border_around'] == "yes") {
                $menu_inner_ul_top += 1; //top border is 1px
            }
            ?>
            .edgtf-drop-down .narrow .second .inner ul li ul,
            body.edgtf-slide-from-bottom .edgtf-drop-down .narrow .second .inner ul li:hover ul,
            body.edgtf-slide-from-top .narrow .second .inner ul li:hover ul{
            top:-<?php echo esc_attr($menu_inner_ul_top); ?>px;
            }

        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_bottom_padding'] !== '') { ?>
            li.narrow .second .inner ul,
            .edgtf-drop-down .wide .second .inner > ul{
            padding-bottom: <?php echo esc_attr($edgtf_quadric_options['dropdown_bottom_padding']); ?>px;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['enable_dropdown_top_separator'] == "no") { ?>
            .edgtf-drop-down .second{
            border-top: 0 !important;
            }
        <?php } ?>

        <?php
        $dropdown_separator_full_width = 'no';
        if($edgtf_quadric_options['enable_dropdown_separator_full_width'] == "yes") {
            $dropdown_separator_full_width = $edgtf_quadric_options['enable_dropdown_separator_full_width']; ?>
            .edgtf-drop-down .second .inner > ul > li:last-child > a,
            .edgtf-drop-down .second .inner > ul > li > ul > li:last-child > a,
            .edgtf-drop-down .second .inner > ul > li > ul > li > ul > li:last-child > a{
            border-bottom:1px solid transparent;
            }

        <?php }
        if($dropdown_separator_full_width !== 'yes' && $edgtf_quadric_options['dropdown_separator_color'] !== '') {
            ?>
            .edgtf-drop-down .second .inner ul li a,
            .header-widget.widget_nav_menu ul.menu li ul li a {
            border-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_separator_color']); ?>;
            }
        <?php } ?>
        <?php
        if($dropdown_separator_full_width == 'yes') {
            ?>
            .edgtf-drop-down .second .inner ul li,
            .header-widget.widget_nav_menu ul.menu li ul li{
            border-bottom: 1px solid <?php echo esc_attr($edgtf_quadric_options['dropdown_separator_color']); ?>;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_top_position'] !== '') { ?>
            header .edgtf-drop-down .second {
            top: <?php echo esc_attr($edgtf_quadric_options['dropdown_top_position']).'%;'; ?>
            }
        <?php } ?>


        <?php if($edgtf_quadric_options['dropdown_border_around'] == "yes" && $edgtf_quadric_options['dropdown_border_around_color'] !== '') { ?>
            .edgtf-drop-down .second .inner > ul,
            li.narrow .second .inner ul,
            .edgtf-drop-down .narrow .second .inner ul li ul,
            .shopping_cart_dropdown,
            .shopping_cart_dropdown ul li{
            border-color:  <?php echo esc_attr($edgtf_quadric_options['dropdown_border_around_color']); ?>;
            }

        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_border_around'] == "no") { ?>
            .edgtf-drop-down .second .inner>ul,
            li.narrow .second .inner ul,
            .edgtf-drop-down .narrow .second .inner ul li ul{
            border: none;
            }

            .edgtf-drop-down .second .inner ul.right li ul{
            margin-left: 0;
            }

        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_color'] !== '' || $edgtf_quadric_options['dropdown_fontsize'] !== '' || $edgtf_quadric_options['dropdown_lineheight'] !== '' || $edgtf_quadric_options['dropdown_fontstyle'] !== '' || $edgtf_quadric_options['dropdown_fontweight'] !== '' || $edgtf_quadric_options['dropdown_google_fonts'] != "-1" || $edgtf_quadric_options['dropdown_texttransform'] !== '' || $edgtf_quadric_options['dropdown_letterspacing'] !== '') { ?>
            .edgtf-drop-down .second .inner > ul > li > a,
            .edgtf-drop-down .second .inner > ul > li > h4,
            .edgtf-drop-down .wide .second .inner > ul > li > h4,
            .edgtf-drop-down .wide .second .inner > ul > li > a,
            .edgtf-drop-down .wide .second ul li ul li.menu-item-has-children > a,
            .edgtf-drop-down .wide .second .inner ul li.sub ul li.menu-item-has-children > a,            
            .edgtf-main-menu.edgtf-default-nav #lang_sel ul li li a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel_click ul li ul li a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel ul ul a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel_click ul ul a{
            <?php if(!empty($edgtf_quadric_options['dropdown_color'])) { ?> color: <?php echo esc_attr($edgtf_quadric_options['dropdown_color']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_google_fonts'] != "-1") { ?>
                font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['dropdown_google_fonts'])); ?>', sans-serif !important;
            <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontsize'] !== '') { ?> font-size: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontsize']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_lineheight'] !== '') { ?> line-height: <?php echo esc_attr($edgtf_quadric_options['dropdown_lineheight']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontstyle'] !== '') { ?> font-style: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontstyle']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontweight'] !== '') { ?>font-weight: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontweight']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_texttransform'] !== '') { ?> text-transform: <?php echo esc_attr($edgtf_quadric_options['dropdown_texttransform']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_letterspacing'] !== '') { ?> letter-spacing: <?php echo esc_attr($edgtf_quadric_options['dropdown_letterspacing']); ?>px;  <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_color'] !== '') { ?>
            .shopping_cart_dropdown ul li
            .item_info_holder .item_left a,
            .shopping_cart_dropdown ul li .item_info_holder .item_right .amount,
            .shopping_cart_dropdown .cart_bottom .subtotal_holder .total,
            .shopping_cart_dropdown .cart_bottom .subtotal_holder .total_amount{
            color: <?php echo esc_attr($edgtf_quadric_options['dropdown_color']); ?>;
            }
        <?php } ?>

        <?php if(!empty($edgtf_quadric_options['dropdown_hovercolor'])) { ?>
            .edgtf-drop-down .second .inner > ul > li:hover > a,
            .edgtf-drop-down .wide .second ul li ul li.menu-item-has-children:hover > a,
            .edgtf-drop-down .wide .second .inner ul li.sub ul li.menu-item-has-children:hover > a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel ul li li:hover a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel_click ul li ul li:hover a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel ul li:hover > a,
            .edgtf-main-menu.edgtf-default-nav #lang_sel_click ul li:hover > a{
            color: <?php echo esc_attr($edgtf_quadric_options['dropdown_hovercolor']); ?> !important;
            }
        <?php } ?>

        <?php if(!empty($edgtf_quadric_options['dropdown_background_hovercolor'])) { ?>
            .edgtf-drop-down li:not(.wide) .second .inner > ul > li:hover{
            background-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_background_hovercolor']); ?>;
            }
        <?php } ?>

        <?php if(!empty($edgtf_quadric_options['dropdown_padding_top_bottom'])) { ?>
            .edgtf-drop-down .wide .second>.inner>ul>li.sub>ul>li>a,
            .edgtf-drop-down .second .inner ul li a,
            .edgtf-drop-down .wide .second ul li a,
            .edgtf-drop-down .second .inner ul.right li a{
            padding-top: <?php echo esc_attr($edgtf_quadric_options['dropdown_padding_top_bottom']); ?>px;
            padding-bottom: <?php echo esc_attr($edgtf_quadric_options['dropdown_padding_top_bottom']); ?>px;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_wide_color'] !== '' || $edgtf_quadric_options['dropdown_wide_fontsize'] !== '' || $edgtf_quadric_options['dropdown_wide_lineheight'] !== '' || $edgtf_quadric_options['dropdown_wide_fontstyle'] !== '' || $edgtf_quadric_options['dropdown_wide_fontweight'] !== '' || $edgtf_quadric_options['dropdown_wide_google_fonts'] !== "-1" || $edgtf_quadric_options['dropdown_wide_texttransform'] !== '' || $edgtf_quadric_options['dropdown_wide_letterspacing'] !== '') { ?>
            .edgtf-drop-down .wide .second .inner > ul > li > a{
            <?php if($edgtf_quadric_options['dropdown_wide_color'] !== '') { ?> color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_color']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_google_fonts'] != "-1") { ?>
                font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['dropdown_wide_google_fonts'])); ?>', sans-serif !important;
            <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontsize'] !== '') { ?> font-size: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontsize']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_lineheight'] !== '') { ?> line-height: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_lineheight']); ?>px; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontstyle'] !== '') { ?> font-style: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontstyle']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontweight'] !== '') { ?>font-weight: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontweight']); ?>; <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_texttransform'] !== '') { ?> text-transform: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_texttransform']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_letterspacing'] !== '') { ?> letter-spacing: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_letterspacing']); ?>px;  <?php } ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_wide_hovercolor'] !== '') { ?>
            .edgtf-drop-down .wide .second .inner > ul > li:hover > a {
            color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_hovercolor']); ?> !important;
            }
        <?php } ?>

        <?php if(!empty($edgtf_quadric_options['dropdown_wide_background_hovercolor'])) { ?>
            .edgtf-drop-down .wide .second .inner > ul > li:hover > a{
            background-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_background_hovercolor']); ?>
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_wide_padding_top_bottom'] !== '') { ?>
            .edgtf-drop-down .wide .second>.inner > ul > li.sub > ul > li > a,
            .edgtf-drop-down .wide .second .inner ul li a,
            .edgtf-drop-down .wide .second ul li a,
            .edgtf-drop-down .wide .second .inner ul.right li a{
            padding-top: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_padding_top_bottom']); ?>px;
            padding-bottom: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_padding_top_bottom']); ?>px;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_color_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_fontsize_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_lineheight_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_fontstyle_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_fontweight_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_google_fonts_thirdlvl'] != "-1" || $edgtf_quadric_options['dropdown_texttransform_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_letterspacing_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .second .inner ul li.sub ul li a{
            <?php if($edgtf_quadric_options['dropdown_color_thirdlvl'] !== '') { ?> color: <?php echo esc_attr($edgtf_quadric_options['dropdown_color_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_google_fonts_thirdlvl'] != "-1") { ?>
                font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['dropdown_google_fonts_thirdlvl'])); ?>', sans-serif;
            <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontsize_thirdlvl'] !== '') { ?> font-size: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontsize_thirdlvl']); ?>px;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_lineheight_thirdlvl'] !== '') { ?> line-height: <?php echo esc_attr($edgtf_quadric_options['dropdown_lineheight_thirdlvl']); ?>px;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontstyle_thirdlvl'] !== '') { ?> font-style: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontstyle_thirdlvl']); ?>;   <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_fontweight_thirdlvl'] !== '') { ?> font-weight: <?php echo esc_attr($edgtf_quadric_options['dropdown_fontweight_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_texttransform_thirdlvl'] !== '') { ?> text-transform: <?php echo esc_attr($edgtf_quadric_options['dropdown_texttransform_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_letterspacing_thirdlvl'] !== '') { ?> letter-spacing: <?php echo esc_attr($edgtf_quadric_options['dropdown_letterspacing_thirdlvl']); ?>px;  <?php } ?>
            }
        <?php } ?>
        <?php if($edgtf_quadric_options['dropdown_hovercolor_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .second .inner ul li.sub ul li:not(.flex-active-slide):hover > a:not(.flex-prev):not(.flex-next),
            .edgtf-drop-down .second .inner ul li ul li:not(.flex-active-slide):hover > a:not(.flex-prev):not(.flex-next){
            color: <?php echo esc_attr($edgtf_quadric_options['dropdown_hovercolor_thirdlvl']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_background_hovercolor_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .second .inner ul li.sub ul li:hover,
            .edgtf-drop-down .second .inner ul li ul li:hover{
            background-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_background_hovercolor_thirdlvl']); ?>;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_wide_color_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_fontsize_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_lineheight_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_fontstyle_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_fontweight_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_google_fonts_thirdlvl'] != "-1" || $edgtf_quadric_options['dropdown_wide_texttransform_thirdlvl'] !== '' || $edgtf_quadric_options['dropdown_wide_letterspacing_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .wide .second .inner ul li.sub ul li a,
            .edgtf-drop-down .wide .second ul li ul li a{
            <?php if($edgtf_quadric_options['dropdown_wide_color_thirdlvl'] !== '') { ?> color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_color_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_google_fonts_thirdlvl'] != "-1") { ?>
                font-family: '<?php echo esc_attr(str_replace('+', ' ', $edgtf_quadric_options['dropdown_wide_google_fonts_thirdlvl'])); ?>', sans-serif;
            <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontsize_thirdlvl'] !== '') { ?> font-size: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontsize_thirdlvl']); ?>px;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_lineheight_thirdlvl'] !== '') { ?> line-height: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_lineheight_thirdlvl']); ?>px;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontstyle_thirdlvl'] !== '') { ?> font-style: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontstyle_thirdlvl']); ?>;   <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_fontweight_thirdlvl'] !== '') { ?> font-weight: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_fontweight_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_texttransform_thirdlvl'] !== '') { ?> text-transform: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_texttransform_thirdlvl']); ?>;  <?php } ?>
            <?php if($edgtf_quadric_options['dropdown_wide_letterspacing_thirdlvl'] !== '') { ?> letter-spacing: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_letterspacing_thirdlvl']); ?>px;  <?php } ?>
            }
        <?php } ?>
        <?php if($edgtf_quadric_options['dropdown_wide_hovercolor_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .wide .second .inner ul li.sub ul li:not(.flex-active-slide) > a:not(.flex-prev):not(.flex-next):hover,
            .edgtf-drop-down .wide .second .inner ul li ul li:not(.flex-active-slide) > a:not(.flex-prev):not(.flex-next):hover{
            color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_hovercolor_thirdlvl']); ?> !important;
            }
        <?php } ?>

        <?php if($edgtf_quadric_options['dropdown_wide_background_hovercolor_thirdlvl'] !== '') { ?>
            .edgtf-drop-down .wide .second .inner ul li.sub ul li:hover,
            .edgtf-drop-down .wide .second .inner ul li ul li:hover{
            background-color: <?php echo esc_attr($edgtf_quadric_options['dropdown_wide_background_hovercolor_thirdlvl']); ?>;
            }
        <?php }
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_main_menu_styles');
}

if(!function_exists('edgtf_quadric_vertical_main_menu_styles')) {
    /**
     * Generates styles for vertical main main menu
     */
    function edgtf_quadric_vertical_main_menu_styles() {
        $dropdown_styles = array();

        if(edgtf_quadric_options()->getOptionValue('vertical_dropdown_background_color') !== '') {
            $dropdown_styles['background-color'] = edgtf_quadric_options()->getOptionValue('vertical_dropdown_background_color');
        }

        $dropdown_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-dropdown-float .menu-item .second',
            '.edgtf-header-vertical .edgtf-vertical-dropdown-float .second .inner ul ul'
        );

        echo edgtf_quadric_dynamic_css($dropdown_selector, $dropdown_styles);

        $fist_level_styles = array();
        $fist_level_hover_styles = array();

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_color') !== '') {
            $fist_level_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_color');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_google_fonts') !== '-1') {
            $fist_level_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontsize') !== '') {
            $fist_level_styles['font-size'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_lineheight') !== '') {
            $fist_level_styles['line-height'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_texttransform') !== '') {
            $fist_level_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontstyle') !== '') {
            $fist_level_styles['font-style'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontweight') !== '') {
            $fist_level_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_letter_spacing') !== '') {
            $fist_level_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_letter_spacing').'px';
        }

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_1st_hover_color') !== '') {
            $fist_level_hover_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_1st_hover_color');
        }

        $first_level_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu > ul > li > a'
        );
        $first_level_hover_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu > ul > li > a:hover',
            '.edgtf-header-vertical .edgtf-vertical-menu > ul > li > a.edgtf-active-item'
        );

        echo edgtf_quadric_dynamic_css($first_level_selector, $fist_level_styles);
        echo edgtf_quadric_dynamic_css($first_level_hover_selector, $fist_level_hover_styles);

        $second_level_styles = array();
        $second_level_hover_styles = array();

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_color') !== '') {
            $second_level_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_color');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_google_fonts') !== '-1') {
            $second_level_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontsize') !== '') {
            $second_level_styles['font-size'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_lineheight') !== '') {
            $second_level_styles['line-height'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_texttransform') !== '') {
            $second_level_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontstyle') !== '') {
            $second_level_styles['font-style'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontweight') !== '') {
            $second_level_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_letter_spacing') !== '') {
            $second_level_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_letter_spacing').'px';
        }

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_hover_color') !== '') {
            $second_level_hover_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_2nd_hover_color');
        }

        $second_level_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner > ul > li > a'
        );

        $second_level_hover_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner > ul > li > a:hover',
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner > ul > li > a.edgtf-active-item'
        );

        echo edgtf_quadric_dynamic_css($second_level_selector, $second_level_styles);
        echo edgtf_quadric_dynamic_css($second_level_hover_selector, $second_level_hover_styles);

        $third_level_styles = array();
        $third_level_hover_styles = array();

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_color') !== '') {
            $third_level_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_color');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_google_fonts') !== '-1') {
            $third_level_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_google_fonts'));
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontsize') !== '') {
            $third_level_styles['font-size'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontsize').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_lineheight') !== '') {
            $third_level_styles['line-height'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_lineheight').'px';
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_texttransform') !== '') {
            $third_level_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_texttransform');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontstyle') !== '') {
            $third_level_styles['font-style'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontstyle');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontweight') !== '') {
            $third_level_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_fontweight');
        }
        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_letter_spacing') !== '') {
            $third_level_styles['letter-spacing'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_letter_spacing').'px';
        }

        if(edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_hover_color') !== '') {
            $third_level_hover_styles['color'] = edgtf_quadric_options()->getOptionValue('vertical_menu_3rd_hover_color');
        }

        $third_level_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner ul li ul li a'
        );

        $third_level_hover_selector = array(
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner ul li ul li a:hover',
            '.edgtf-header-vertical .edgtf-vertical-menu .second .inner ul li ul li a.edgtf-active-item'
        );

        echo edgtf_quadric_dynamic_css($third_level_selector, $third_level_styles);
        echo edgtf_quadric_dynamic_css($third_level_hover_selector, $third_level_hover_styles);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_vertical_main_menu_styles');
}