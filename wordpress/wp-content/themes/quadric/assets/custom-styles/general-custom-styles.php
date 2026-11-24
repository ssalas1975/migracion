<?php
if(!function_exists('edgtf_quadric_design_styles')) {
    /**
     * Generates general custom styles
     */
    function edgtf_quadric_design_styles() {

        $preload_background_styles = array();

        if(edgtf_quadric_options()->getOptionValue('preload_pattern_image') !== ""){
            $preload_background_styles['background-image'] = 'url('.edgtf_quadric_options()->getOptionValue('preload_pattern_image').') !important';
        }else{
            $preload_background_styles['background-image'] = 'url('.esc_url(EDGE_ASSETS_ROOT."/img/preload_pattern.png").') !important';
        }

        echo edgtf_quadric_dynamic_css('.edgtf-preload-background', $preload_background_styles);

		if (edgtf_quadric_options()->getOptionValue('google_fonts')){
			$font_family = edgtf_quadric_options()->getOptionValue('google_fonts');
			if(edgtf_quadric_is_font_option_valid($font_family)) {
				echo edgtf_quadric_dynamic_css('body', array('font-family' => edgtf_quadric_get_font_option_val($font_family)));
			}
		}

        if(edgtf_quadric_options()->getOptionValue('first_color') !== "") {
            $color_selector = array(
                'h1 a:hover',
                'h2 a:hover',
                'h3 a:hover',
                'h4 a:hover',
                'h5 a:hover',
                'h6 a:hover',
                'a',
                'p a',
                '.edgtf-main-menu ul li:hover a, .edgtf-main-menu ul li.edgtf-active-item a',
                'body:not(.edgtf-menu-item-first-level-bg-color) .edgtf-main-menu > ul > li:hover > a, .edgtf-main-menu > ul > li.edgtf-active-item > a',
                '.edgtf-drop-down .second .inner > ul > li:hover > a',
                '.edgtf-drop-down .second .inner ul li.sub ul li:hover > a',
                '.edgtf-drop-down .wide .second .inner > ul > li > a:hover',                             
                '.edgtf-header-vertical .edgtf-vertical-dropdown-float .second .inner ul li a:hover',
                '.edgtf-header-vertical .edgtf-vertical-dropdown-toggle .second .inner ul li a:hover',
                '.edgtf-header-vertical .edgtf-vertical-menu ul li a:hover',
                '.edgtf-mobile-header .edgtf-mobile-nav a:hover, .edgtf-mobile-header .edgtf-mobile-nav h4:hover',
                '.edgtf-mobile-header .edgtf-mobile-menu-opener a:hover',
                '.edgtf-side-menu-button-opener:hover',
                'nav.edgtf-fullscreen-menu ul li a:hover',
                'nav.edgtf-fullscreen-menu ul li ul li a',
                '.edgtf-search-slide-header-bottom .edgtf-search-submit:hover',
                '.edgtf-search-cover .edgtf-search-close a:hover',
                '.edgtf-message .edgtf-message-inner a.edgtf-close i:hover',
                '.edgtf-ordered-list ol > li:before',
                '.edgtf-icon-list-item .edgtf-icon-list-icon-holder .edgtf-icon-list-icon-holder-inner i',
                '.edgtf-icon-list-item .edgtf-icon-list-icon-holder .edgtf-icon-list-icon-holder-inner .font_elegant',
                '.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-table-prices .edgtf-value',
                '.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-table-prices .edgtf-price',
                '.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-table-prices .edgtf-mark',
                '.edgtf-tabs .edgtf-tabs-nav li.ui-state-active a .edgtf-icon-frame',
                '.edgtf-tabs .edgtf-tabs-nav li.ui-state-hover a .edgtf-icon-frame',
                '.edgtf-btn.edgtf-btn-outline',
                '#submit_comment:hover',
                '.post-password-form input[type="submit"]:hover',
                'input.wpcf7-form-control.wpcf7-submit:hover',
				'.edgtf-icon-list-item .edgtf-icon-list-icon-holder-inner i',
				'.edgtf-icon-list-item .edgtf-icon-list-icon-holder-inner .edgtf-icon-font-elegant',
				'.edgtf-ordered-list ol>li:before',
				'.edgtf-portfolio-filter-holder .edgtf-portfolio-filter-holder-inner ul li.active span',
				'.edgtf-portfolio-filter-holder .edgtf-portfolio-filter-holder-inner ul li.current span',
				'.edgtf-portfolio-list-holder article .edgtf-item-icons-holder a',
				'.edgtf-portfolio-list-holder.edgtf-ptf-standard article .edgtf-item-icons-holder a:hover',
                '.edgtf-icon-slider-container .edgtf-icon-slider-controls-holder',
                '.edgtf-pie-chart-holder .edgtf-to-counter',
				'.edgtf-pie-chart-holder .edgtf-percent-mark',
                '.amount',
                '.edgtf-drop-down .wide .second ul li .edgtf-featured-icon.icon-fire',
                '.edgtf-drop-down .second .inner ul li.sub a i.edgtf-menu-arrow',
                '.edgtf-main-menu .second ul li a .menu_icon',
                '.edgtf-main-menu .second a:hover > .item_outer .item_text .menu_icon',
                '.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs a',
                '.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs span',
                '.edgtf-portfolio-single-holder .edgtf-portfolio-single-nav a:hover',
                '.edgtf-unordered-list.edgtf-line ul > li:before',
                '.edgtf-progress-bar .edgtf-progress-number-wrapper.edgtf-static .edgtf-progress-number',
                '.edgtf-pie-chart-with-icon-holder .edgtf-percentage-with-icon i',
                '.edgtf-pie-chart-with-icon-holder .edgtf-percentage-with-icon span',
                '.edgtf-tabs .edgtf-tabs-nav li.ui-state-active a .edgtf-icon-frame',
                '.edgtf-tabs .edgtf-tabs-nav li.ui-state-hover a .edgtf-icon-frame',
                '.edgtf-accordion-holder .edgtf-title-holder.ui-state-active .edgtf-accordion-icon',
                '.edgtf-accordion-holder .edgtf-title-holder.ui-state-hover .edgtf-accordion-icon',
                '.edgtf-accordion-holder .edgtf-title-holder.ui-state-active .edgtf-accordion-mark',
                '.edgtf-accordion-holder .edgtf-title-holder.ui-state-hover .edgtf-accordion-mark',
                '.edgtf-blog-list-holder .edgtf-item-info-section',
                '.edgtf-blog-list-holder.edgtf-boxes .edgtf-image-hover .edgtf-image-hover-inner .edgtf-hover-icon',
                'blockquote .edgtf-icon-quotations-holder',
                '.edgtf-video-button-play .edgtf-video-button-wrapper:hover',
                '.edgtf-dropcaps',
                '.edgtf-portfolio-filter-holder .edgtf-portfolio-filter-holder-inner ul li:hover span',
                '.edgtf-portfolio-list-holder article .edgtf-item-icons-holder a',
                '.edgtf-iwt .edgtf-icon-element',
                '.edgtf-icon-slider-nav-icon.active',
                '.edgtf-icon-slider-nav-icon:hover',
                '.edgtf-team-slider-container .owl-prev:hover',
                '.edgtf-team-slider-container .owl-next:hover',
                '.edgtf-sidebar .widget ul li:hover a',
                '.edgtf-sidebar .widget ul li:hover:after',
                '.edgtf-sidebar .widget .tagcloud a:hover',
                '.edgtf-sidebar .widget .edgtf-blog-list .edgtf-post-info-date',
                '.edgtf-owl-slider .owl-nav .edgtf-prev-icon:hover i',
                '.edgtf-owl-slider .owl-nav .edgtf-next-icon:hover i',
                '.edgtf-carousel-holder .edgtf-carousel.owl-carousel .owl-nav .edgtf-prev-icon:hover i',
                '.edgtf-carousel-holder .edgtf-carousel.owl-carousel .owl-nav .edgtf-next-icon:hover i',
                '.edgtf-image-gallery .owl-nav .edgtf-prev-icon:hover i',
                '.edgtf-image-gallery .owl-nav .edgtf-next-icon:hover i ',
                '.edgtf-portfolio-slider-holder .edgtf-portfolio-list-holder.owl-carousel .owl-nav .edgtf-prev-icon:hover i',
                '.edgtf-portfolio-slider-holder .edgtf-portfolio-list-holder.owl-carousel .owl-nav .edgtf-next-icon:hover i',
                '.single-portfolio-item div.pp_default a.pp_next:hover',
                '.single-portfolio-item div.pp_default a.pp_previous:hover',
                '.edgtf-portfolio-single-holder .edgtf-big-image-corner .edgtf-social-share-holder.edgtf-dropdown .edgtf-social-share-dropdown ul li a:hover',
				'.edgtf-pagination li:hover a',
				'.edgtf-side-menu .widget li:hover a',
				'.edgtf-fullscreen-search-holder .edgtf-search-submit:hover',
                '.woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a',
                '.woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover'
            );

            $color_important_selector = array(
                '.edgtf-btn.edgtf-btn-solid:not(.edgtf-btn-custom-hover-color):hover',
                '.edgtf-number-holder-inner:hover .edgtf-number',
            );

            $background_color_selector = array(
                '.edgtf-header-vertical .edgtf-vertical-dropdown-toggle .second:after',
                '.edgtf-header-vertical .edgtf-vertical-menu > ul > li > a:before',
                '.edgtf-header-vertical .edgtf-vertical-menu > ul > li > a:after',
                '.edgtf-header-vertical.edgtf-vertical-header-hidden .edgtf-vertical-menu-hidden-button-line',
                '.edgtf-header-vertical.edgtf-vertical-header-hidden .edgtf-vertical-menu-hidden-button-line:after',
                '.edgtf-header-vertical.edgtf-vertical-header-hidden .edgtf-vertical-menu-hidden-button-line:before',
                '.edgtf-fullscreen-menu-opener:hover .edgtf-line',
                '.edgtf-fullscreen-menu-opener.opened:hover .edgtf-line:after',
                '.edgtf-fullscreen-menu-opener.opened:hover .edgtf-line:before',
                '.edgtf-icon-shortcode.circle, .edgtf-icon-shortcode.square',
                '.edgtf-price-table.edgtf-active .edgtf-active-text',
                '.edgtf-pie-chart-doughnut-holder .edgtf-pie-legend ul li .edgtf-pie-color-holder',
                '.edgtf-pie-chart-pie-holder .edgtf-pie-legend ul li .edgtf-pie-color-holder',
                '.edgtf-btn.edgtf-btn-solid',
                '#submit_comment',
                '.post-password-form input[type="submit"]',
                'input.wpcf7-form-control.wpcf7-submit',
				'#edgtf-back-to-top',
				'.edgtf-portfolio-list-holder.edgtf-ptf-standard article .edgtf-item-icons-holder a',
                '.edgtf-main-menu > ul > li > a span.plus',
                '.edgtf-drop-down .second a:not(.no_link) .item_inner:after',
                '.edgtf-drop-down .wide .second > .inner > ul > li > a:after',
                '.edgtf-drop-down .narrow ul li a .item_outer:before',
                '.edgtf-header-standard .edgtf-menu-area .edgtf-vertical-align-containers .edgtf-position-right-inner .edgtf-side-menu-button-opener',
                '.edgtf-header-standard .edgtf-sticky-header .edgtf-position-right-inner .edgtf-side-menu-button-opener',
                '.edgtf-team.main-info-on-hover .edgtf-team-social-holder .edgtf-icon-shortcode:hover',
                '.edgtf-call-to-action',
                '.edgtf-image-2',
                '.edgtf-unordered-list.edgtf-square ul > li:before',
                '.edgtf-unordered-list.edgtf-dropcaps.edgtf-circle ul > li:before',
                '.edgtf-unordered-list.edgtf-circle ul > li:before',
                '.edgtf-icon-list-item-square .edgtf-icon-list-icon-holder-inner i',
                '.edgtf-icon-list-item-square .edgtf-icon-list-icon-holder-inner .edgtf-icon-font-elegant',
                '.edgtf-progress-bar .edgtf-progress-content-outer .edgtf-progress-content',
                '.edgtf-testimonials.owl-carousel .owl-dots .owl-dot.active span',
                '.edgtf-testimonials.owl-carousel.light .owl-dot.active span',
                '.edgtf-testimonials.owl-carousel.dark .owl-dot.active span',
                '.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-price-button a',
                '.edgtf-price-table.edgtf-active .edgtf-active-text',
                '.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder.ui-state-active .edgtf-accordion-mark',
                '.edgtf-accordion-holder.edgtf-boxed .edgtf-title-holder.ui-state-hover .edgtf-accordion-mark',
                '.edgtf-blog-list-holder.edgtf-boxes > ul > li:hover .edgtf-item-date',
                '.edgtf-dropcaps.edgtf-square, .edgtf-dropcaps.edgtf-circle',
                '.edgtf-zooming-slider .edgtf-zooming-slider-icon-holder',
                '.edgtf-zooming-slider .owl-dot.active span',
                '.edgtf-team-slider-container .edgtf-icon-shortcode:hover',
                '.edgtf-team-slider-container .owl-dot',
                '.fs-container:hover .fs-cont-date',
                '.woocommerce.widget input[type="submit"]',
                '.woocommerce.widget button',
				'.edgtf-drop-down .wide .second ul ul li a:not(.edgtf-item-with-icon) .item_outer:before',
				'.edgtf-pagination li.active span',
				'.edgtf-main-menu > ul > li > a .item_title:after',
				'.edgtf-fs-nav .nav_but:before'
            );

            $border_color_selector = array(
                '.edgtf-drop-down .second',
                '.edgtf-progress-bar .edgtf-progress-number-wrapper.edgtf-floating .edgtf-down-arrow',
                '.edgtf-btn.edgtf-btn-solid',
                '.edgtf-btn.edgtf-btn-outline',
                '#submit_comment',
                '.post-password-form input[type="submit"]',
                'input.wpcf7-form-control.wpcf7-submit',
                '.wpcf7-form-control.wpcf7-text:focus',
                '.wpcf7-form-control.wpcf7-number:focus',
                '.wpcf7-form-control.wpcf7-date:focus',
                '.wpcf7-form-control.wpcf7-textarea:focus',
                '.wpcf7-form-control.wpcf7-select:focus',
                '.wpcf7-form-control.wpcf7-quiz:focus',
                '#respond textarea:focus',
                '#respond input[type="text"]:focus',
                '.post-password-form input[type="password"]:focus',
				'.edgtf-portfolio-list-holder article .edgtf-item-icons-holder a',
				'.edgtf-portfolio-slider-holder .edgtf-portfolio-list-holder.owl-carousel .owl-buttons .edgtf-prev-icon',
				'.edgtf-portfolio-slider-holder .edgtf-portfolio-list-holder.owl-carousel .owl-buttons .edgtf-next-icon',              
                '.post-password-form input[type="submit"]',
                '.edgtf-fullscreen-search-holder .edgtf-field-holder'
            );

            $stroke_color_selector = array(
                '.edgtf-zig-zag-separator-holder .edgtf-zig-zag-separator'                
            );

            $stroke_color_selector_important_selector = array(
                '.edgtf-svg-icons-text-holder:hover svg'                
            );
            
            echo edgtf_quadric_dynamic_css($color_selector, array('color' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css($color_important_selector, array('color' => edgtf_quadric_options()->getOptionValue('first_color').'!important'));
            echo edgtf_quadric_dynamic_css('::selection', array('background' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css('::-moz-selection', array('background' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css($background_color_selector, array('background-color' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css($border_color_selector, array('border-color' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css($stroke_color_selector, array('stroke' => edgtf_quadric_options()->getOptionValue('first_color')));
            echo edgtf_quadric_dynamic_css($stroke_color_selector_important_selector, array('stroke' => edgtf_quadric_options()->getOptionValue('first_color').'!important'));
        }

		if (edgtf_quadric_options()->getOptionValue('page_background_color')) {
			$background_color_selector = array(
				'.edgtf-wrapper-inner',
				'.edgtf-content'
			);
			echo edgtf_quadric_dynamic_css($background_color_selector, array('background-color' => edgtf_quadric_options()->getOptionValue('page_background_color')));
		}

		if (edgtf_quadric_options()->getOptionValue('selection_color')) {
			echo edgtf_quadric_dynamic_css('::selection', array('background' => edgtf_quadric_options()->getOptionValue('selection_color')));
			echo edgtf_quadric_dynamic_css('::-moz-selection', array('background' => edgtf_quadric_options()->getOptionValue('selection_color')));
		}

		$boxed_background_style = array();
		if (edgtf_quadric_options()->getOptionValue('page_background_color_in_box')) {
			$boxed_background_style['background-color'] = edgtf_quadric_options()->getOptionValue('page_background_color_in_box');
		}

		if (edgtf_quadric_options()->getOptionValue('boxed_background_image')) {
			$boxed_background_style['background-image'] = 'url('.esc_url(edgtf_quadric_options()->getOptionValue('boxed_background_image')).')';
			$boxed_background_style['background-position'] = 'center 0px';
			$boxed_background_style['background-repeat'] = 'no-repeat';
		}

		if (edgtf_quadric_options()->getOptionValue('boxed_pattern_background_image')) {
			$boxed_background_style['background-image'] = 'url('.esc_url(edgtf_quadric_options()->getOptionValue('boxed_pattern_background_image')).')';
			$boxed_background_style['background-position'] = '0px 0px';
			$boxed_background_style['background-repeat'] = 'repeat';
		}

		if (edgtf_quadric_options()->getOptionValue('boxed_background_image_attachment')) {
			$boxed_background_style['background-attachment'] = (edgtf_quadric_options()->getOptionValue('boxed_background_image_attachment'));
		}

		echo edgtf_quadric_dynamic_css('.edgtf-boxed .edgtf-wrapper', $boxed_background_style);


		$calc_plus_left_2_style = array();
		$calc_plus_left_2_style['left'] = 'calc(100% + 2px)';
		echo edgtf_quadric_dynamic_css('.edgtf-main-menu > ul > li:hover .item_title:before,  .edgtf-main-menu > ul > li:hover .item_title:after', $calc_plus_left_2_style);

		$calc_plus_left_1_style = array();
		$calc_plus_left_1_style['left'] = 'calc(100% + 1px)';
		echo edgtf_quadric_dynamic_css('.edgtf-drop-down .second li > a:hover .item_inner:before, .edgtf-drop-down .second li > a:hover .item_inner:after', $calc_plus_left_1_style);

		$calc_plus_top_1_style = array();
		$calc_plus_top_1_style['top'] = 'calc(100% + 1px)';
		echo edgtf_quadric_dynamic_css('.edgtf-drop-down .second', $calc_plus_top_1_style);
    }

    add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_design_styles');
}

if (!function_exists('edgtf_quadric_h1_styles')) {

	function edgtf_quadric_h1_styles() {

		$h1_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h1_color') !== '') {
			$h1_styles['color'] = edgtf_quadric_options()->getOptionValue('h1_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h1_google_fonts') !== '-1') {
			$h1_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h1_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h1_fontsize') !== '') {
			$h1_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h1_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h1_lineheight') !== '') {
			$h1_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h1_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h1_texttransform') !== '') {
			$h1_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h1_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h1_fontstyle') !== '') {
			$h1_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h1_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h1_fontweight') !== '') {
			$h1_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h1_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h1_letterspacing') !== '') {
			$h1_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h1_letterspacing')).'px';
		}

		$h1_selector = array(
			'h1'
		);

		if (!empty($h1_styles)) {
			echo edgtf_quadric_dynamic_css($h1_selector, $h1_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h1_styles');
}

if (!function_exists('edgtf_quadric_h2_styles')) {

	function edgtf_quadric_h2_styles() {

		$h2_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h2_color') !== '') {
			$h2_styles['color'] = edgtf_quadric_options()->getOptionValue('h2_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h2_google_fonts') !== '-1') {
			$h2_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h2_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h2_fontsize') !== '') {
			$h2_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h2_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h2_lineheight') !== '') {
			$h2_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h2_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h2_texttransform') !== '') {
			$h2_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h2_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h2_fontstyle') !== '') {
			$h2_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h2_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h2_fontweight') !== '') {
			$h2_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h2_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h2_letterspacing') !== '') {
			$h2_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h2_letterspacing')).'px';
		}

		$h2_selector = array(
			'h2'
		);

		if (!empty($h2_styles)) {
			echo edgtf_quadric_dynamic_css($h2_selector, $h2_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h2_styles');
}

if (!function_exists('edgtf_quadric_h3_styles')) {

	function edgtf_quadric_h3_styles() {

		$h3_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h3_color') !== '') {
			$h3_styles['color'] = edgtf_quadric_options()->getOptionValue('h3_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h3_google_fonts') !== '-1') {
			$h3_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h3_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h3_fontsize') !== '') {
			$h3_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h3_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h3_lineheight') !== '') {
			$h3_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h3_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h3_texttransform') !== '') {
			$h3_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h3_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h3_fontstyle') !== '') {
			$h3_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h3_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h3_fontweight') !== '') {
			$h3_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h3_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h3_letterspacing') !== '') {
			$h3_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h3_letterspacing')).'px';
		}

		$h3_selector = array(
			'h3'
		);

		if (!empty($h3_styles)) {
			echo edgtf_quadric_dynamic_css($h3_selector, $h3_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h3_styles');
}

if (!function_exists('edgtf_quadric_h4_styles')) {

	function edgtf_quadric_h4_styles() {

		$h4_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h4_color') !== '') {
			$h4_styles['color'] = edgtf_quadric_options()->getOptionValue('h4_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h4_google_fonts') !== '-1') {
			$h4_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h4_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h4_fontsize') !== '') {
			$h4_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h4_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h4_lineheight') !== '') {
			$h4_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h4_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h4_texttransform') !== '') {
			$h4_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h4_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h4_fontstyle') !== '') {
			$h4_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h4_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h4_fontweight') !== '') {
			$h4_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h4_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h4_letterspacing') !== '') {
			$h4_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h4_letterspacing')).'px';
		}

		$h4_selector = array(
			'h4'
		);

		if (!empty($h4_styles)) {
			echo edgtf_quadric_dynamic_css($h4_selector, $h4_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h4_styles');
}

if (!function_exists('edgtf_quadric_h5_styles')) {

	function edgtf_quadric_h5_styles() {

		$h5_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h5_color') !== '') {
			$h5_styles['color'] = edgtf_quadric_options()->getOptionValue('h5_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h5_google_fonts') !== '-1') {
			$h5_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h5_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h5_fontsize') !== '') {
			$h5_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h5_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h5_lineheight') !== '') {
			$h5_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h5_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h5_texttransform') !== '') {
			$h5_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h5_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h5_fontstyle') !== '') {
			$h5_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h5_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h5_fontweight') !== '') {
			$h5_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h5_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h5_letterspacing') !== '') {
			$h5_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h5_letterspacing')).'px';
		}

		$h5_selector = array(
			'h5'
		);

		if (!empty($h5_styles)) {
			echo edgtf_quadric_dynamic_css($h5_selector, $h5_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h5_styles');
}

if (!function_exists('edgtf_quadric_h6_styles')) {

	function edgtf_quadric_h6_styles() {

		$h6_styles = array();

		if(edgtf_quadric_options()->getOptionValue('h6_color') !== '') {
			$h6_styles['color'] = edgtf_quadric_options()->getOptionValue('h6_color');
		}
		if(edgtf_quadric_options()->getOptionValue('h6_google_fonts') !== '-1') {
			$h6_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('h6_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('h6_fontsize') !== '') {
			$h6_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h6_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h6_lineheight') !== '') {
			$h6_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h6_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('h6_texttransform') !== '') {
			$h6_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('h6_texttransform');
		}
		if(edgtf_quadric_options()->getOptionValue('h6_fontstyle') !== '') {
			$h6_styles['font-style'] = edgtf_quadric_options()->getOptionValue('h6_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('h6_fontweight') !== '') {
			$h6_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('h6_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('h6_letterspacing') !== '') {
			$h6_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('h6_letterspacing')).'px';
		}

		$h6_selector = array(
			'h6'
		);

		if (!empty($h6_styles)) {
			echo edgtf_quadric_dynamic_css($h6_selector, $h6_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_h6_styles');
}

if (!function_exists('edgtf_quadric_text_styles')) {

	function edgtf_quadric_text_styles() {

		$text_styles = array();

		if(edgtf_quadric_options()->getOptionValue('text_color') !== '') {
			$text_styles['color'] = edgtf_quadric_options()->getOptionValue('text_color');
		}
		if(edgtf_quadric_options()->getOptionValue('text_google_fonts') !== '-1') {
			$text_styles['font-family'] = edgtf_quadric_get_formatted_font_family(edgtf_quadric_options()->getOptionValue('text_google_fonts'));
		}
		if(edgtf_quadric_options()->getOptionValue('text_fontsize') !== '') {
			$text_styles['font-size'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('text_fontsize')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('text_lineheight') !== '') {
			$text_styles['line-height'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('text_lineheight')).'px';
		}
		if(edgtf_quadric_options()->getOptionValue('text_text_transform') !== '') {
			$text_styles['text-transform'] = edgtf_quadric_options()->getOptionValue('text_text_transform');
		}
		if(edgtf_quadric_options()->getOptionValue('text_fontstyle') !== '') {
			$text_styles['font-style'] = edgtf_quadric_options()->getOptionValue('text_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('text_fontweight') !== '') {
			$text_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('text_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('text_letter_spacing') !== '') {
			$text_styles['letter-spacing'] = edgtf_quadric_filter_px(edgtf_quadric_options()->getOptionValue('text_letter_spacing')).'px';
		}

		$text_selector = array(
			'p'
		);

		if (!empty($text_styles)) {
			echo edgtf_quadric_dynamic_css($text_selector, $text_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_text_styles');
}

if (!function_exists('edgtf_quadric_link_styles')) {

	function edgtf_quadric_link_styles() {

		$link_styles = array();

		if(edgtf_quadric_options()->getOptionValue('link_color') !== '') {
			$link_styles['color'] = edgtf_quadric_options()->getOptionValue('link_color');
		}
		if(edgtf_quadric_options()->getOptionValue('link_fontstyle') !== '') {
			$link_styles['font-style'] = edgtf_quadric_options()->getOptionValue('link_fontstyle');
		}
		if(edgtf_quadric_options()->getOptionValue('link_fontweight') !== '') {
			$link_styles['font-weight'] = edgtf_quadric_options()->getOptionValue('link_fontweight');
		}
		if(edgtf_quadric_options()->getOptionValue('link_fontdecoration') !== '') {
			$link_styles['text-decoration'] = edgtf_quadric_options()->getOptionValue('link_fontdecoration');
		}

		$link_selector = array(
			'a',
			'p a'
		);

		if (!empty($link_styles)) {
			echo edgtf_quadric_dynamic_css($link_selector, $link_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_link_styles');
}

if (!function_exists('edgtf_quadric_link_hover_styles')) {

	function edgtf_quadric_link_hover_styles() {

		$link_hover_styles = array();

		if(edgtf_quadric_options()->getOptionValue('link_hovercolor') !== '') {
			$link_hover_styles['color'] = edgtf_quadric_options()->getOptionValue('link_hovercolor');
		}
		if(edgtf_quadric_options()->getOptionValue('link_hover_fontdecoration') !== '') {
			$link_hover_styles['text-decoration'] = edgtf_quadric_options()->getOptionValue('link_hover_fontdecoration');
		}

		$link_hover_selector = array(
			'a:hover',
			'p a:hover'
		);

		if (!empty($link_hover_styles)) {
			echo edgtf_quadric_dynamic_css($link_hover_selector, $link_hover_styles);
		}

		$link_heading_hover_styles = array();

		if(edgtf_quadric_options()->getOptionValue('link_hovercolor') !== '') {
			$link_heading_hover_styles['color'] = edgtf_quadric_options()->getOptionValue('link_hovercolor');
		}

		$link_heading_hover_selector = array(
			'h1 a:hover',
			'h2 a:hover',
			'h3 a:hover',
			'h4 a:hover',
			'h5 a:hover',
			'h6 a:hover'
		);

		if (!empty($link_heading_hover_styles)) {
			echo edgtf_quadric_dynamic_css($link_heading_hover_selector, $link_heading_hover_styles);
		}
	}

	add_action('edgtf_quadric_style_dynamic', 'edgtf_quadric_link_hover_styles');
}