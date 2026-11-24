<?php
if(!function_exists('any_edge_design_styles')) {
    /**
     * Generates general custom styles
     */
    function any_edge_design_styles() {

        $preload_background_styles = array();

        if(any_edge_options()->getOptionValue('preload_pattern_image') !== ""){
            $preload_background_styles['background-image'] = 'url('.any_edge_options()->getOptionValue('preload_pattern_image').') !important';
        }else{
            $preload_background_styles['background-image'] = 'url('.esc_url(EDGE_ASSETS_ROOT."/img/preload_pattern.png").') !important';
        }

        echo any_edge_dynamic_css('.edgtf-preload-background', $preload_background_styles);

		if (any_edge_options()->getOptionValue('google_fonts')){
			$font_family = any_edge_options()->getOptionValue('google_fonts');
			if(any_edge_is_font_option_valid($font_family)) {
				echo any_edge_dynamic_css('body', array('font-family' => any_edge_get_font_option_val($font_family)));
			}
		}

        if(any_edge_options()->getOptionValue('first_color') !== "") {
            $color_selector = array(
				'h1 a:hover',
				'h2 a:hover',
				'h3 a:hover',
				'h4 a:hover',
				'h5 a:hover',
				'h6 a:hover',
				'.edgtf-comment-holder .edgtf-comment-text .comment-edit-link',
				'.edgtf-comment-holder .edgtf-comment-text .comment-reply-link',
				'.edgtf-comment-holder .edgtf-comment-text .replay',
				'.edgtf-pagination-holder .edgtf-pagination li a',
				'.edgtf-pagination-holder .edgtf-pagination li.active span',
				'.edgtf-page-not-found-part .edgtf-404-text',
				'#edgtf-back-to-top.edgtf-light>.edgtf-icon-stack',
				'.edgtf-main-menu>ul>li.edgtf-active-item>a',
				'.edgtf-header-vertical .edgtf-vertical-dropdown-float .edgtf-menu-second .edgtf-menu-inner ul li a:hover',
				'.edgtf-header-vertical .edgtf-vertical-menu ul li a:hover',
				'.edgtf-mobile-header .edgtf-mobile-nav a:hover',
				'.edgtf-mobile-header .edgtf-mobile-nav h4:hover',
				'.edgtf-mobile-header .edgtf-mobile-menu-opener a:hover',
				'.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs a',
				'.edgtf-title .edgtf-title-holder .edgtf-breadcrumbs span',
				'.edgtf-portfolio-single-holder.full-screen-slider .edgtf-portfolio-slider-content .edgtf-portfolio-title',
				'.edgtf-portfolio-single-holder .edgtf-portfolio-related-holder .edgtf-portfolio-back-btn',
				'.edgtf-team.main-info-below-image .edgtf-team-social-wrapp .edgtf-icon-shortcode',
				'.edgtf-team.main-info-below-image .edgtf-team-social-wrapp .edgtf-icon-shortcode a',
				'.edgtf-counter-holder .edgtf-counter-icon .edgtf-icon-shortcode',
				'.edgtf-counter-holder .edgtf-counter',
				'.countdown-section',
				'.edgtf-message .edgtf-message-inner a.edgtf-close',
				'.edgtf-ordered-list ol>li:before',
				'.edgtf-unordered-list ul>li:before',
				'.edgtf-testimonials-holder .edgtf-testimonial-icon',
				'.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-table-prices .edgtf-value',
				'.edgtf-price-table .edgtf-price-table-inner ul li.edgtf-table-prices .edgtf-price',
				'.edgtf-price-table.edgtf-active .edgtf-price-table-inner ul li.edgtf-table-title .edgtf-title-content',
				'.edgtf-tabs .edgtf-tabs-nav li.ui-state-active a',
				'.edgtf-tabs .edgtf-tabs-nav li.ui-state-hover a',
				'.edgtf-accordion-holder .edgtf-title-holder',
				'.edgtf-accordion-holder.edgtf-style-grey .edgtf-title-holder',
				'.edgtf-accordion-holder.edgtf-style-white .edgtf-title-holder',
				'.edgtf-blog-list-holder .edgtf-item-info-section .edgtf-info-btm-left>div a:hover',
				'.edgtf-blog-slider .edgtf-item-info-section .edgtf-info-btm-left>div a:hover',
				'.edgtf-btn.edgtf-btn-solid-white',
				'.edgtf-btn.edgtf-btn-outline',
				'.edgtf-btn.edgtf-btn-transparent',
				'.edgtf-dropcaps',
				'.edgtf-portfolio-list-holder-outer.edgtf-ptf-standard article .edgtf-item-text-holder .edgtf-item-title',
				'.edgtf-portfolio-list-holder-outer.edgtf-ptf-hover-trim article .edgtf-ptf-read-more',
				'.edgtf-portfolio-filter-holder .edgtf-portfolio-filter-holder-inner ul li span',
				'.edgtf-social-share-holder.edgtf-list li a',
				'.widget ul li a',
				'.widget a',
				'.widget.widget_recent_entries ul a',
				'.widget .tagcloud a',
				'.widget.widget_calendar #next a',
				'.widget.widget_calendar #prev a',
				'.edgtf-sidebar .widget.widget_archive li:hover',
				'.wpb_widgetised_column .widget.widget_archive li:hover',
				'.edgtf-blog-holder article .edgtf-post-info-bottom .edgtf-post-info-bottom-left>div a:hover',
				'.edgtf-blog-holder article .edgtf-post-info-bottom .edgtf-post-info-bottom-right>div a:hover',
				'.edgtf-blog-holder article .edgtf-post-mark',
				'.edgtf-blog-holder article .edgtf-post-link-title',
				'.edgtf-blog-holder article .edgtf-quote-author',
				'.edgtf-blog-holder.edgtf-blog-single article.format-link:hover .edgtf-post-link-title',
				'.edgtf-blog-holder.edgtf-blog-single article.format-link:hover .edgtf-post-mark',
				'.edgtf-blog-holder.edgtf-blog-single article.format-link:hover .edgtf-post-title',
				'.edgtf-blog-holder.edgtf-blog-single article.format-link:hover .edgtf-quote-author',
				'.edgtf-blog-holder.edgtf-blog-single article.format-quote:hover .edgtf-post-link-title',
				'.edgtf-blog-holder.edgtf-blog-single article.format-quote:hover .edgtf-post-mark',
				'.edgtf-blog-holder.edgtf-blog-single article.format-quote:hover .edgtf-post-title',
				'.edgtf-blog-holder.edgtf-blog-single article.format-quote:hover .edgtf-quote-author',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-link:hover .edgtf-post-link-title',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-link:hover .edgtf-post-mark',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-link:hover .edgtf-post-title',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-link:hover .edgtf-quote-author',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-quote:hover .edgtf-post-link-title',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-quote:hover .edgtf-post-mark',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-quote:hover .edgtf-post-title',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-quote:hover .edgtf-quote-author',
				'.edgtf-blog-infinite-scroll-button-holder .edgtf-blog-infinite-scroll-button a',
				'.edgtf-filter-blog-holder li.edgtf-active',
				'.edgtf-woocommerce-page .price>.amount',
				'.edgtf-woocommerce-page ins',
				'.woocommerce .price>.amount',
				'.woocommerce ins',
				'.woocommerce-pagination .page-numbers',
				'.edgtf-single-product-related-products-holder .products .edgtf-related-glob',
				'.edgtf-single-product-wrapper-top .out-of-stock',
				'.edgtf-single-product-summary .product_meta>span a:hover',
				'.summary .group_table td.label a',
				'.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link.is-active a',
				'.woocommerce-account .woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link:hover a',
				'.edgtf-shopping-cart-dropdown ul li a:hover',
				'.edgtf-shopping-cart-dropdown .edgtf-item-info-holder .edgtf-item-left:hover',
				'.woocommerce.widget button:hover',
				'.woocommerce.widget input[type=submit]:hover',
				'.widget_price_filter .price_slider_amount .price_label'
            );

            $color_important_selector = array(

            );

            $background_color_selector = array(
				'.edgtf-st-loader .pulse',
				'.edgtf-st-loader .double_pulse .double-bounce1',
				'.edgtf-st-loader .double_pulse .double-bounce2',
				'.edgtf-st-loader .cube',
				'.edgtf-st-loader .rotating_cubes .cube1',
				'.edgtf-st-loader .rotating_cubes .cube2',
				'.edgtf-st-loader .stripes>div',
				'.edgtf-st-loader .wave>div',
				'.edgtf-st-loader .two_rotating_circles .dot1',
				'.edgtf-st-loader .two_rotating_circles .dot2',
				'.edgtf-st-loader .five_rotating_circles .container1>div',
				'.edgtf-st-loader .five_rotating_circles .container2>div',
				'.edgtf-st-loader .five_rotating_circles .container3>div',
				'.edgtf-st-loader .atom .ball-1:before',
				'.edgtf-st-loader .atom .ball-2:before',
				'.edgtf-st-loader .atom .ball-3:before',
				'.edgtf-st-loader .atom .ball-4:before',
				'.edgtf-st-loader .clock .ball:before',
				'.edgtf-st-loader .mitosis .ball',
				'.edgtf-st-loader .lines .line1',
				'.edgtf-st-loader .lines .line2',
				'.edgtf-st-loader .lines .line3',
				'.edgtf-st-loader .lines .line4',
				'.edgtf-st-loader .fussion .ball',
				'.edgtf-st-loader .fussion .ball-1',
				'.edgtf-st-loader .fussion .ball-2',
				'.edgtf-st-loader .fussion .ball-3',
				'.edgtf-st-loader .fussion .ball-4',
				'.edgtf-st-loader .wave_circles .ball',
				'.edgtf-st-loader .pulse_circles .ball',
				'.flex-control-paging.flex-control-nav li a .flex-active',
				'.flex-control-paging.flex-control-nav li a:hover',
				'.edgtf-header-vertical .edgtf-vertical-menu>ul>li>a:before',
				'.edgtf-header-vertical .edgtf-vertical-menu>ul>li>a:after',
				'.edgtf-header-expanding .edgtf-menu-appear .edgtf-line',
				'footer .edgtf-footer-ingrid-border-holder-outer',
				'.edgtf-fullscreen-menu-opener .edgtf-fullscreen-icon .edgtf-line',
				'.edgtf-message',
				'.edgtf-progress-bar.edgtf-progress-bar-light .edgtf-progress-content-outer',
				'.edgtf-pie-chart-doughnut-holder .edgtf-pie-legend ul li .edgtf-pie-color-holder',
				'.edgtf-pie-chart-pie-holder .edgtf-pie-legend ul li .edgtf-pie-color-holder',
				'.edgtf-tabs.edgtf-tab-boxed .edgtf-tabs-nav li a',
				'.edgtf-accordion-holder .edgtf-title-holder.ui-state-active',
				'.edgtf-accordion-holder .edgtf-title-holder.ui-state-hover',
				'.edgtf-accordion-holder.edgtf-style-grey .edgtf-title-holder.ui-state-active',
				'.edgtf-accordion-holder.edgtf-style-grey .edgtf-title-holder.ui-state-hover',
				'.edgtf-accordion-holder.edgtf-style-white .edgtf-title-holder.ui-state-active',
				'.edgtf-accordion-holder.edgtf-style-white .edgtf-title-holder.ui-state-hover',
				'.edgtf-btn.edgtf-btn-solid',
				'.edgtf-dropcaps.edgtf-circle',
				'.edgtf-dropcaps.edgtf-square',
				'.carousel .carousel-indicators:not(.thumbnails) li.active',
				'#multiscroll-nav ul li .active span',
				'.edgtf-vss-light-slider #multiscroll-nav li .active span',
				'.edgtf-vss-light-slider #multiscroll-nav li:hover span',
				'.edgtf-vss-dark-slider #multiscroll-nav li .active span',
				'.edgtf-vss-dark-slider #multiscroll-nav li:hover span',
				'.edgtf-shop-gallery .edgtf-product-badge',
				'.edgtf-shop-gallery .edgtf-btn.edgtf-btn-solid',
				'.widget .tagcloud a:hover',
				'.edgtf-blog-holder.edgtf-blog-single article.format-link .edgtf-post-text',
				'.edgtf-blog-holder.edgtf-blog-single article.format-quote .edgtf-post-text',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-link .edgtf-post-text',
				'.edgtf-blog-holder.edgtf-blog-type-masonry article.format-quote .edgtf-post-text',
				'.edgtf-woocommerce-page .product .edgtf-product-badge',
				'.woocommerce .product .edgtf-product-badge',
				'.edgtf-woocommerce-page .added_to_cart',
				'.woocommerce .added_to_cart',
				'.woocommerce-account input[type=submit]',
				'.woocommerce-checkout input[type=submit]',
				'.woocommerce.widget button',
				'.woocommerce.widget input[type=submit]',
				'.widget_price_filter .ui-slider .ui-slider-handle',
				'.slick-slider .edgtf-slick-dots li.slick-active'
			);

            $background_color_important_selector = array(
				'.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-hover-bg):hover',
				'.edge-nav .tp-bullet.selected',
				'.edge-nav .tp-bullet:hover',
            );

            $border_color_selector = array(
				'.edgtf-st-loader .pulse_circles .ball',
				'.flex-control-paging.flex-control-nav li a',
				'.edgtf-message',
				'.edgtf-btn.edgtf-btn-outline',
				'.widget .tagcloud a:hover',
				'.edgtf-woocommerce-page .added_to_cart',
				'.woocommerce .added_to_cart',
				'.woocommerce.widget button',
				'.woocommerce.widget input[type=submit]',
				'.slick-slider .edgtf-slick-dots li'
			);

			$border_color_important_selector = array(
				'.edgtf-btn.edgtf-btn-outline:not(.edgtf-btn-custom-border-hover):hover'
			);

			$border_bottom_color_selector = array(
				'.edgtf-pagination-holder .edgtf-pagination li a:hover,.edgtf-pagination-holder .edgtf-pagination li.active span',
				'.edgtf-single-links-pages .edgtf-single-links-pages-inner>a:hover',
				'.woocommerce-pagination .page-numbers.current,.woocommerce-pagination .page-numbers.current:hover,.woocommerce-pagination .page-numbers:hover'
			);

            echo any_edge_dynamic_css($color_selector, array('color' => any_edge_options()->getOptionValue('first_color')));
            echo any_edge_dynamic_css($color_important_selector, array('color' => any_edge_options()->getOptionValue('first_color').'!important'));
            echo any_edge_dynamic_css('::selection', array('background' => any_edge_options()->getOptionValue('first_color')));
            echo any_edge_dynamic_css('::-moz-selection', array('background' => any_edge_options()->getOptionValue('first_color')));
            echo any_edge_dynamic_css($background_color_selector, array('background-color' => any_edge_options()->getOptionValue('first_color')));
            echo any_edge_dynamic_css($background_color_important_selector, array('background-color' => any_edge_options()->getOptionValue('first_color').'!important'));
            echo any_edge_dynamic_css($border_color_selector, array('border-color' => any_edge_options()->getOptionValue('first_color')));
            echo any_edge_dynamic_css($border_color_important_selector, array('border-color' => any_edge_options()->getOptionValue('first_color').'!important'));
            echo any_edge_dynamic_css($border_bottom_color_selector, array('border-bottom-color' => any_edge_options()->getOptionValue('first_color')));
        }

		if (any_edge_options()->getOptionValue('page_background_color')) {
			$background_color_selector = array(
				'.edgtf-wrapper-inner',
				'.edgtf-full-width',
				'.edgtf-content',
				'.edgtf-content-inner > .edgtf-container'
			);
			echo any_edge_dynamic_css($background_color_selector, array('background-color' => any_edge_options()->getOptionValue('page_background_color')));
		}

		if (any_edge_options()->getOptionValue('selection_color')) {
			echo any_edge_dynamic_css('::selection', array('background' => any_edge_options()->getOptionValue('selection_color')));
			echo any_edge_dynamic_css('::-moz-selection', array('background' => any_edge_options()->getOptionValue('selection_color')));
		}

		$boxed_background_style = array();
		if (any_edge_options()->getOptionValue('page_background_color_in_box')) {
			$boxed_background_style['background-color'] = any_edge_options()->getOptionValue('page_background_color_in_box');
		}

		if (any_edge_options()->getOptionValue('boxed_background_image')) {
			$boxed_background_style['background-image'] = 'url('.esc_url(any_edge_options()->getOptionValue('boxed_background_image')).')';
			if(any_edge_options()->getOptionValue('boxed_background_image_repeating') == 'yes') {
				$boxed_background_style['background-position'] = '0px 0px';
				$boxed_background_style['background-repeat'] = 'repeat';
			} else {
				$boxed_background_style['background-position'] = 'center 0px';
				$boxed_background_style['background-repeat'] = 'repeat';
			}
		}


		if (any_edge_options()->getOptionValue('boxed_background_image_attachment')) {
			$boxed_background_style['background-attachment'] = (any_edge_options()->getOptionValue('boxed_background_image_attachment'));
		}

		echo any_edge_dynamic_css('.edgtf-boxed .edgtf-wrapper', $boxed_background_style);
    }

    add_action('any_edge_style_dynamic', 'any_edge_design_styles');
}

if (!function_exists('any_edge_h1_styles')) {

    function any_edge_h1_styles() {

        $h1_styles = array();

        if(any_edge_options()->getOptionValue('h1_color') !== '') {
            $h1_styles['color'] = any_edge_options()->getOptionValue('h1_color');
        }
        if(any_edge_options()->getOptionValue('h1_google_fonts') !== '-1') {
            $h1_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h1_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h1_fontsize') !== '') {
            $h1_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h1_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h1_lineheight') !== '') {
            $h1_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h1_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h1_texttransform') !== '') {
            $h1_styles['text-transform'] = any_edge_options()->getOptionValue('h1_texttransform');
        }
        if(any_edge_options()->getOptionValue('h1_fontstyle') !== '') {
            $h1_styles['font-style'] = any_edge_options()->getOptionValue('h1_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h1_fontweight') !== '') {
            $h1_styles['font-weight'] = any_edge_options()->getOptionValue('h1_fontweight');
        }
        if(any_edge_options()->getOptionValue('h1_letterspacing') !== '') {
            $h1_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h1_letterspacing')).'px';
        }

        $h1_selector = array(
            'h1'
        );

        if (!empty($h1_styles)) {
            echo any_edge_dynamic_css($h1_selector, $h1_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h1_styles');
}

if (!function_exists('any_edge_h2_styles')) {

    function any_edge_h2_styles() {

        $h2_styles = array();

        if(any_edge_options()->getOptionValue('h2_color') !== '') {
            $h2_styles['color'] = any_edge_options()->getOptionValue('h2_color');
        }
        if(any_edge_options()->getOptionValue('h2_google_fonts') !== '-1') {
            $h2_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h2_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h2_fontsize') !== '') {
            $h2_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h2_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h2_lineheight') !== '') {
            $h2_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h2_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h2_texttransform') !== '') {
            $h2_styles['text-transform'] = any_edge_options()->getOptionValue('h2_texttransform');
        }
        if(any_edge_options()->getOptionValue('h2_fontstyle') !== '') {
            $h2_styles['font-style'] = any_edge_options()->getOptionValue('h2_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h2_fontweight') !== '') {
            $h2_styles['font-weight'] = any_edge_options()->getOptionValue('h2_fontweight');
        }
        if(any_edge_options()->getOptionValue('h2_letterspacing') !== '') {
            $h2_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h2_letterspacing')).'px';
        }

        $h2_selector = array(
            'h2'
        );

        if (!empty($h2_styles)) {
            echo any_edge_dynamic_css($h2_selector, $h2_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h2_styles');
}

if (!function_exists('any_edge_h3_styles')) {

    function any_edge_h3_styles() {

        $h3_styles = array();

        if(any_edge_options()->getOptionValue('h3_color') !== '') {
            $h3_styles['color'] = any_edge_options()->getOptionValue('h3_color');
        }
        if(any_edge_options()->getOptionValue('h3_google_fonts') !== '-1') {
            $h3_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h3_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h3_fontsize') !== '') {
            $h3_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h3_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h3_lineheight') !== '') {
            $h3_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h3_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h3_texttransform') !== '') {
            $h3_styles['text-transform'] = any_edge_options()->getOptionValue('h3_texttransform');
        }
        if(any_edge_options()->getOptionValue('h3_fontstyle') !== '') {
            $h3_styles['font-style'] = any_edge_options()->getOptionValue('h3_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h3_fontweight') !== '') {
            $h3_styles['font-weight'] = any_edge_options()->getOptionValue('h3_fontweight');
        }
        if(any_edge_options()->getOptionValue('h3_letterspacing') !== '') {
            $h3_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h3_letterspacing')).'px';
        }

        $h3_selector = array(
            'h3'
        );

        if (!empty($h3_styles)) {
            echo any_edge_dynamic_css($h3_selector, $h3_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h3_styles');
}

if (!function_exists('any_edge_h4_styles')) {

    function any_edge_h4_styles() {

        $h4_styles = array();

        if(any_edge_options()->getOptionValue('h4_color') !== '') {
            $h4_styles['color'] = any_edge_options()->getOptionValue('h4_color');
        }
        if(any_edge_options()->getOptionValue('h4_google_fonts') !== '-1') {
            $h4_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h4_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h4_fontsize') !== '') {
            $h4_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h4_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h4_lineheight') !== '') {
            $h4_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h4_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h4_texttransform') !== '') {
            $h4_styles['text-transform'] = any_edge_options()->getOptionValue('h4_texttransform');
        }
        if(any_edge_options()->getOptionValue('h4_fontstyle') !== '') {
            $h4_styles['font-style'] = any_edge_options()->getOptionValue('h4_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h4_fontweight') !== '') {
            $h4_styles['font-weight'] = any_edge_options()->getOptionValue('h4_fontweight');
        }
        if(any_edge_options()->getOptionValue('h4_letterspacing') !== '') {
            $h4_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h4_letterspacing')).'px';
        }

        $h4_selector = array(
            'h4'
        );

        if (!empty($h4_styles)) {
            echo any_edge_dynamic_css($h4_selector, $h4_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h4_styles');
}

if (!function_exists('any_edge_h5_styles')) {

    function any_edge_h5_styles() {

        $h5_styles = array();

        if(any_edge_options()->getOptionValue('h5_color') !== '') {
            $h5_styles['color'] = any_edge_options()->getOptionValue('h5_color');
        }
        if(any_edge_options()->getOptionValue('h5_google_fonts') !== '-1') {
            $h5_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h5_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h5_fontsize') !== '') {
            $h5_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h5_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h5_lineheight') !== '') {
            $h5_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h5_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h5_texttransform') !== '') {
            $h5_styles['text-transform'] = any_edge_options()->getOptionValue('h5_texttransform');
        }
        if(any_edge_options()->getOptionValue('h5_fontstyle') !== '') {
            $h5_styles['font-style'] = any_edge_options()->getOptionValue('h5_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h5_fontweight') !== '') {
            $h5_styles['font-weight'] = any_edge_options()->getOptionValue('h5_fontweight');
        }
        if(any_edge_options()->getOptionValue('h5_letterspacing') !== '') {
            $h5_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h5_letterspacing')).'px';
        }

        $h5_selector = array(
            'h5'
        );

        if (!empty($h5_styles)) {
            echo any_edge_dynamic_css($h5_selector, $h5_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h5_styles');
}

if (!function_exists('any_edge_h6_styles')) {

    function any_edge_h6_styles() {

        $h6_styles = array();

        if(any_edge_options()->getOptionValue('h6_color') !== '') {
            $h6_styles['color'] = any_edge_options()->getOptionValue('h6_color');
        }
        if(any_edge_options()->getOptionValue('h6_google_fonts') !== '-1') {
            $h6_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('h6_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('h6_fontsize') !== '') {
            $h6_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('h6_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('h6_lineheight') !== '') {
            $h6_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('h6_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('h6_texttransform') !== '') {
            $h6_styles['text-transform'] = any_edge_options()->getOptionValue('h6_texttransform');
        }
        if(any_edge_options()->getOptionValue('h6_fontstyle') !== '') {
            $h6_styles['font-style'] = any_edge_options()->getOptionValue('h6_fontstyle');
        }
        if(any_edge_options()->getOptionValue('h6_fontweight') !== '') {
            $h6_styles['font-weight'] = any_edge_options()->getOptionValue('h6_fontweight');
        }
        if(any_edge_options()->getOptionValue('h6_letterspacing') !== '') {
            $h6_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('h6_letterspacing')).'px';
        }

        $h6_selector = array(
            'h6'
        );

        if (!empty($h6_styles)) {
            echo any_edge_dynamic_css($h6_selector, $h6_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_h6_styles');
}

if (!function_exists('any_edge_text_styles')) {

    function any_edge_text_styles() {

        $text_styles = array();

        if(any_edge_options()->getOptionValue('text_color') !== '') {
            $text_styles['color'] = any_edge_options()->getOptionValue('text_color');
        }
        if(any_edge_options()->getOptionValue('text_google_fonts') !== '-1') {
            $text_styles['font-family'] = any_edge_get_formatted_font_family(any_edge_options()->getOptionValue('text_google_fonts'));
        }
        if(any_edge_options()->getOptionValue('text_fontsize') !== '') {
            $text_styles['font-size'] = any_edge_filter_px(any_edge_options()->getOptionValue('text_fontsize')).'px';
        }
        if(any_edge_options()->getOptionValue('text_lineheight') !== '') {
            $text_styles['line-height'] = any_edge_filter_px(any_edge_options()->getOptionValue('text_lineheight')).'px';
        }
        if(any_edge_options()->getOptionValue('text_texttransform') !== '') {
            $text_styles['text-transform'] = any_edge_options()->getOptionValue('text_texttransform');
        }
        if(any_edge_options()->getOptionValue('text_fontstyle') !== '') {
            $text_styles['font-style'] = any_edge_options()->getOptionValue('text_fontstyle');
        }
        if(any_edge_options()->getOptionValue('text_fontweight') !== '') {
            $text_styles['font-weight'] = any_edge_options()->getOptionValue('text_fontweight');
        }
        if(any_edge_options()->getOptionValue('text_letterspacing') !== '') {
            $text_styles['letter-spacing'] = any_edge_filter_px(any_edge_options()->getOptionValue('text_letterspacing')).'px';
        }

        $text_selector = array(
            'p'
        );

        if (!empty($text_styles)) {
            echo any_edge_dynamic_css($text_selector, $text_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_text_styles');
}

if (!function_exists('any_edge_link_styles')) {

    function any_edge_link_styles() {

        $link_styles = array();

        if(any_edge_options()->getOptionValue('link_color') !== '') {
            $link_styles['color'] = any_edge_options()->getOptionValue('link_color');
        }
        if(any_edge_options()->getOptionValue('link_fontstyle') !== '') {
            $link_styles['font-style'] = any_edge_options()->getOptionValue('link_fontstyle');
        }
        if(any_edge_options()->getOptionValue('link_fontweight') !== '') {
            $link_styles['font-weight'] = any_edge_options()->getOptionValue('link_fontweight');
        }
        if(any_edge_options()->getOptionValue('link_fontdecoration') !== '') {
            $link_styles['text-decoration'] = any_edge_options()->getOptionValue('link_fontdecoration');
        }

        $link_selector = array(
            'a',
            'p a'
        );

        if (!empty($link_styles)) {
            echo any_edge_dynamic_css($link_selector, $link_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_link_styles');
}

if (!function_exists('any_edge_link_hover_styles')) {

    function any_edge_link_hover_styles() {

        $link_hover_styles = array();

        if(any_edge_options()->getOptionValue('link_hovercolor') !== '') {
            $link_hover_styles['color'] = any_edge_options()->getOptionValue('link_hovercolor');
        }
        if(any_edge_options()->getOptionValue('link_hover_fontdecoration') !== '') {
            $link_hover_styles['text-decoration'] = any_edge_options()->getOptionValue('link_hover_fontdecoration');
        }

        $link_hover_selector = array(
            'a:hover',
            'p a:hover'
        );

        if (!empty($link_hover_styles)) {
            echo any_edge_dynamic_css($link_hover_selector, $link_hover_styles);
        }

        $link_heading_hover_styles = array();

        if(any_edge_options()->getOptionValue('link_hovercolor') !== '') {
            $link_heading_hover_styles['color'] = any_edge_options()->getOptionValue('link_hovercolor');
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
            echo any_edge_dynamic_css($link_heading_hover_selector, $link_heading_hover_styles);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_link_hover_styles');
}

if (!function_exists('any_edge_smooth_page_transition_styles')) {

    function any_edge_smooth_page_transition_styles() {
        
        $loader_style = array();

        if(any_edge_options()->getOptionValue('smooth_pt_bgnd_color') !== '') {
            $loader_style['background-color'] = any_edge_options()->getOptionValue('smooth_pt_bgnd_color');
        }

        $loader_selector = array('.edgtf-smooth-transition-loader');

        if (!empty($loader_style)) {
            echo any_edge_dynamic_css($loader_selector, $loader_style);
        }

        $spinner_style = array();

        if(any_edge_options()->getOptionValue('smooth_pt_spinner_color') !== '') {
            $spinner_style['background-color'] = any_edge_options()->getOptionValue('smooth_pt_spinner_color');
        }

        $spinner_selectors = array(
            '.edgtf-st-loader .pulse',
            '.edgtf-st-loader .double_pulse .double-bounce1',
            '.edgtf-st-loader .double_pulse .double-bounce2',
            '.edgtf-st-loader .cube',
            '.edgtf-st-loader .rotating_cubes .cube1',
            '.edgtf-st-loader .rotating_cubes .cube2',
            '.edgtf-st-loader .stripes > div',
            '.edgtf-st-loader .wave > div',
            '.edgtf-st-loader .two_rotating_circles .dot1',
            '.edgtf-st-loader .two_rotating_circles .dot2',
            '.edgtf-st-loader .five_rotating_circles .container1 > div',
            '.edgtf-st-loader .five_rotating_circles .container2 > div',
            '.edgtf-st-loader .five_rotating_circles .container3 > div',
            '.edgtf-st-loader .atom .ball-1:before',
            '.edgtf-st-loader .atom .ball-2:before',
            '.edgtf-st-loader .atom .ball-3:before',
            '.edgtf-st-loader .atom .ball-4:before',
            '.edgtf-st-loader .clock .ball:before',
            '.edgtf-st-loader .mitosis .ball',
            '.edgtf-st-loader .lines .line1',
            '.edgtf-st-loader .lines .line2',
            '.edgtf-st-loader .lines .line3',
            '.edgtf-st-loader .lines .line4',
            '.edgtf-st-loader .fussion .ball',
            '.edgtf-st-loader .fussion .ball-1',
            '.edgtf-st-loader .fussion .ball-2',
            '.edgtf-st-loader .fussion .ball-3',
            '.edgtf-st-loader .fussion .ball-4',
            '.edgtf-st-loader .wave_circles .ball',
            '.edgtf-st-loader .pulse_circles .ball'
        );

        if (!empty($spinner_style)) {
            echo any_edge_dynamic_css($spinner_selectors, $spinner_style);
        }
    }

    add_action('any_edge_style_dynamic', 'any_edge_smooth_page_transition_styles');
}