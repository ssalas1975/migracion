<?php
/**
 * General.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('General', 'theme-options', LANGUAGE_ZONE), "type" => "heading" );

/**
 * Top logo.
 */
$options[] = array(	"name" => _x('Logo in header', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'header-logo_regular',
		// full / uri_only
		"mode"		=> 'full',
		"type"		=> 'upload',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'header-logo_hd',
		// full / uri_only
		"mode"		=> 'full',
		"type"		=> 'upload',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Bottom logo.
 */
$options[] = array(	"name" => _x('Logo in bottom line', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'bottom_bar-logo_regular',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'bottom_bar-logo_hd',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Floating logo.
 */
$options[] = array(	"name" => _x('Floating menu', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Show logo', 'theme-options', 'presscore'),
		"id"		=> 'general-floating_menu_show_logo',
		"std"		=> '0',
		"type"		=> 'radio',
		"options"	=> $yes_no_options
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'Logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'general-floating_menu_logo_regular',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

	// uploader
	$options[] = array(
		"name"		=> _x( 'High-DPI (retina) logo', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> 'general-floating_menu_logo_hd',
		"type"		=> 'upload',
		"mode"		=> 'full',
		'std'		=> array( '', 0 )
	);

$options[] = array(	"type" => "block_end");

/**
 * Background.
 */
$options[] = array(	"name" => _x('Background', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "general-bg_color",
		"std"	=> "#252525",
		"type"	=> "color"
	);

	// slider
	$options[] = array(
		"name"      => _x( 'Opacity', 'theme-options', LANGUAGE_ZONE ),
		"id"        => "general-bg_opacity",
		"std"       => 100, 
		"type"      => "slider",
		"options"   => array( 'java_hide_if_not_max' => true )
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// colorpicker
		$options[] = array(
			"desc"  => '',
			"name"  => _x( 'Internet Explorer color', 'theme-options', LANGUAGE_ZONE ),
			"id"    => "general-bg_ie_color",
			"std"   => "#252525",
			"type"  => "color"
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// background_img
	$options[] = array(
		'name' 			=> _x( 'Choose/upload background image', 'theme-options', LANGUAGE_ZONE ),
		'id' 			=> 'general-bg_image',
		'preset_images' => $backgrounds_general_bg_image,
		'std' 			=> array(
			'image'			=> '',
			'repeat'		=> 'repeat',
			'position_x'	=> 'center',
			'position_y'	=> 'center'
		),
		'type'			=> 'background_img'
	);

	// checkbox
	$options[] = array(
		"name"      => _x( 'Fullscreen', 'theme-options', LANGUAGE_ZONE ),
		"id"    	=> 'general-bg_fullscreen',
		"type"  	=> 'checkbox',
		'std'   	=> 0
	);

$options[] = array(	"type" => "block_end");

/**
 * Layout.
 */
$options[] = array(	"name" => _x('Layout', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Layout', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-layout',
		"std"		=> 'wide',
		"type"		=> 'radio',
		"options"	=> presscore_themeoptions_get_general_layout_options(),
		"show_hide"	=> array( 'boxed' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// colorpicker
		$options[] = array(
			"name"	=> _x( 'Background color', 'theme-options', LANGUAGE_ZONE ),
			"id"	=> "general-boxed_bg_color",
			"std"	=> "#ffffff",
			"type"	=> "color"
		);

		// background_img
		$options[] = array(
			'type' 			=> 'background_img',
			'id' 			=> 'general-boxed_bg_image',
			'name' 			=> _x( 'Choose/upload background image', 'theme-options', LANGUAGE_ZONE ),
			'preset_images' => $backgrounds_general_boxed_bg_image,
			'std' 			=> array(
				'image'			=> '',
				'repeat'		=> 'repeat',
				'position_x'	=> 'center',
				'position_y'	=> 'center'
			),
		);

		// checkbox
		$options[] = array(
			"name"      => _x( 'Fullscreen ', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-boxed_bg_fullscreen',
			"type"  	=> 'checkbox',
			'std'   	=> 0
		);

	$options[] = array( 'type' => 'js_hide_end' );

$options[] = array(	"type" => "block_end");

/**
 * Title.
 */
$options[] = array(	"name" => _x('Titles & Breadcrumbs', 'theme-options', 'presscore'), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Show titles and breadcrumbs', 'theme-options', 'presscore'),
		"id"		=> 'general-show_titles',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $yes_no_options,
		"show_hide"	=> array( '1' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// bg image
		$options[] = array(
			"name"      => _x('Title align', 'theme-options', LANGUAGE_ZONE),
			"id"        => "general-title_align",
			"std"       => 'center',
			"type"      => "radio",
			"options"   => array(
				'center'    => _x( 'Center', 'backend options', LANGUAGE_ZONE ),
				'left'      => _x( 'Left', 'backend options', LANGUAGE_ZONE ),
				'right'     => _x( 'Right', 'backend options', LANGUAGE_ZONE )
			)
		);

	$options[] = array( 'type' => 'js_hide_end' );

	// radio
	$options[] = array(
		"name"		=> _x('Breadcrumbs', 'theme-options', 'presscore'),
		"id"		=> 'general-show_breadcrumbs',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $on_off_options
	);

$options[] = array(	"type" => "block_end");


/**
 * Responsive.
 */
$options[] = array(	"name" => _x('Responsiveness', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Responsive layout', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-responsive',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $en_dis_options
	);

$options[] = array(	"type" => "block_end");

/**
 * High-DPI (retina) images.
 */
$options[] = array(	"name" => _x('High-DPI (retina) images', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('High-DPI (retina) images', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-hd_images',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $en_dis_options
	);

$options[] = array(	"type" => "block_end");

/**
 * Color accent.
 */
$options[] = array(	"name" => _x('Color accent', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// colorpicker
	$options[] = array(
		"name"	=> _x( 'Color', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> "general-accent_bg_color",
		"std"	=> "#D73B37",
		"type"	=> "color"
	);

$options[] = array(	"type" => "block_end");


/**
 * Border Radius.
 */
$options[] = array(	"name" => _x('Border Radius', 'theme-options', 'presscore'), "type" => "block_begin" );

	// input
	$options[] = array(
		"name"		=> _x( 'Border Radius (px)', 'theme-options', 'presscore' ),
		"id"		=> 'general-border_radius',
		"std"		=> '8',
		"type"		=> 'text',
		"sanitize"	=> 'dimensions'
	);

$options[] = array(	"type" => "block_end");


/**
 * Dividers.
 */
$options[] = array(	"name" => _x('Dividers', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// bg image
	$options[] = array(
		"name"      => _x('Thick divider style', 'theme-options', LANGUAGE_ZONE),
		"id"        => "general-thick_divider_style",
		"std"       => 'style-1',
		"type"      => "images",
		"options"   => array(
			'style-1'	=> '/inc/admin/assets/images/dividers-icons/d00.jpg',
			'style-1-1'	=> '/inc/admin/assets/images/dividers-icons/d01.jpg',
			'style-1-2'	=> '/inc/admin/assets/images/dividers-icons/d02.jpg',
			'style-1-3'	=> '/inc/admin/assets/images/dividers-icons/d03.jpg',
			'style-2'	=> '/inc/admin/assets/images/dividers-icons/d04.jpg',
			'style-2-1'	=> '/inc/admin/assets/images/dividers-icons/d05.jpg',
			'style-2-2'	=> '/inc/admin/assets/images/dividers-icons/d06.jpg',
			'style-3'	=> '/inc/admin/assets/images/dividers-icons/d07.jpg',
			'style-3-1'	=> '/inc/admin/assets/images/dividers-icons/d08.jpg',
			'style-3-2'	=> '/inc/admin/assets/images/dividers-icons/d09.jpg',
			'style-4'	=> '/inc/admin/assets/images/dividers-icons/d10.jpg',
			'style-4-1'	=> '/inc/admin/assets/images/dividers-icons/d11.jpg',
			'style-4-2'	=> '/inc/admin/assets/images/dividers-icons/d12.jpg',
			'style-5'	=> '/inc/admin/assets/images/dividers-icons/d13.jpg',
		)
	);

	// bg image
	$options[] = array(
		"name"      => _x('Thin divider style', 'theme-options', LANGUAGE_ZONE),
		"id"        => "general-thin_divider_style",
		"std"       => 'style-1',
		"type"      => "images",
		"options"   => array(
			'style-1'	=> '/inc/admin/assets/images/dividers-icons/d-small-01.jpg',
			'style-2'	=> '/inc/admin/assets/images/dividers-icons/d-small-02.jpg',
			'style-3'	=> '/inc/admin/assets/images/dividers-icons/d-small-03.jpg',
		)
	);

$options[] = array(	"type" => "block_end");

/**
 * Prev / Next buttons.
 */
$options[] = array(	"name" => _x('Prev / Next buttons', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// checkbox
	$options[] = array(
		"name"      => _x( 'Show in blog posts', 'theme-options', LANGUAGE_ZONE ),
		"id"    	=> 'general-next_prev_in_blog',
		"type"  	=> 'checkbox',
		'std'   	=> 1
	);

	// checkbox
	$options[] = array(
		"name"      => _x( 'Show in portfolio projects', 'theme-options', LANGUAGE_ZONE ),
		"id"    	=> 'general-next_prev_in_portfolio',
		"type"  	=> 'checkbox',
		'std'   	=> 1
	);

$options[] = array(	"type" => "block_end");

/**
 * Show author info on blog post pages.
 */
$options[] = array(	"name" => _x('Show author info in blog posts', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// checkbox
	$options[] = array(
		"name"      => _x( 'Show author info in blog posts', 'theme-options', LANGUAGE_ZONE ),
		"id"    	=> 'general-show_author_in_blog',
		"type"  	=> 'checkbox',
		'std'   	=> 1
	);

$options[] = array(	"type" => "block_end");

/**
 * Tracking code (e.g. Google analytics).
 */
$options[] = array(	"name" => _x('Tracking code (e.g. Google analytics)', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// textarea
	$options[] = array(
		"name"      => _x( 'Tracking code (e.g. Google analytics)', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> "general-tracking_code",
		"std"		=> false,
		"type"		=> 'textarea',
		"sanitize"	=> 'without_sanitize'
	);

$options[] = array(	"type" => "block_end");

/**
 * Favicon.
 */
$options[] = array(	"name" => _x('Favicon', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// uploader
	$options[] = array(
		"name"	=> _x( 'Upload image', 'theme-options', LANGUAGE_ZONE ),
		"id"	=> 'general-favicon',
		"type"	=> 'upload',
		'std'	=> ''
	);

$options[] = array(	"type" => "block_end");

/**
 * Custom css
 */
$options[] = array(	"name" => _x('Custom CSS', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// textarea
	$options[] = array(
		"name"      => _x( 'Custom CSS', 'theme-options', LANGUAGE_ZONE ),
		"id"		=> "general-custom_css",
		"std"		=> false,
		"type"		=> 'textarea',
		"sanitize"	=> 'without_sanitize'
	);

$options[] = array(	"type" => "block_end");

/**
 * Related posts settings.
 */
$options[] = array(	"name" => _x('Related posts settings', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	//*************************************************************************************************
	// Blog
	//*************************************************************************************************

	// title
	$options[] = array( "type" => 'title', "name" => _x('Blog posts', 'theme-options', LANGUAGE_ZONE) );

	// radio
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x('Show related posts', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-show_rel_posts',
		"std"		=> '0',
		"type"		=> 'radio',
		"options"	=> $yes_no_options,
		"show_hide"	=> array( '1' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// input
		$options[] = array(
			"name"		=> _x( 'Title', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_posts_head_title',
			"std"		=> __('Related posts', LANGUAGE_ZONE),
			"type"		=> 'text',
		);

		// input
		$options[] = array(
			"name"		=> _x( 'Maximum number of related posts', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_posts_max',
			"std"		=> 6,
			"type"		=> 'text',
			// number
			"sanitize"	=> 'ppp'
		);

	$options[] = array( 'type' => 'js_hide_end' );

	//*************************************************************************************************
	// Portfolio
	//*************************************************************************************************

	// divider
	$options[] = array( "type" => 'divider' );

	// title
	$options[] = array( "type" => 'title', "name" => _x('Portfolio projects', 'theme-options', LANGUAGE_ZONE) );

	// radio
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x('Show related projects', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-show_rel_projects',
		"std"		=> '0',
		"type"		=> 'radio',
		"options"	=> $yes_no_options,
		"show_hide"	=> array( '1' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// input
		$options[] = array(
			"name"		=> _x( 'Title', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_projects_head_title',
			"std"		=> __('Related projects', LANGUAGE_ZONE),
			"type"		=> 'text',
		);

		// input
		$options[] = array(
			"name"		=> _x( 'Maximum number of projects posts', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_projects_max',
			"std"		=> 12,
			"type"		=> 'text',
			// number
			"sanitize"	=> 'ppp'
		);

		// radio
		$options[] = array(
			"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_meta',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
		);

		// radio
		$options[] = array(
			"name"		=> _x('Show titles', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_title',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
		);

		// radio
		$options[] = array(
			"name"		=> _x('Show excerpts', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_excerpt',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
		);

		// radio
		$options[] = array(
			"name"		=> _x('Show links', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_link',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
		);

		// radio
		$options[] = array(
			"name"		=> _x('Show "Details" button', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_details',
			"std"		=> '1',
			"type"		=> 'radio',
			"options"	=> $yes_no_options,
		);

		// input
		$options[] = array(
			"name"		=> _x( 'Related posts height for fullwidth posts (px)', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_projects_fullwidth_height',
			"std"		=> 210,
			"type"		=> 'text',
			// number
			"sanitize"	=> 'ppp'
		);

		// radio
		$options[] = array(
			"name"		=> _x('Related posts width for fullwidth posts', 'theme-options', LANGUAGE_ZONE),
			"id"		=> 'general-rel_projects_fullwidth_width_style',
			"std"		=> 'prop',
			"type"		=> 'radio',
			"options"	=> $prop_fixed_options,
			"show_hide"	=> array( 'fixed' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// input
			$options[] = array(
				"name"		=> _x( 'Width (px)', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_fullwidth_width',
				"std"		=> '210',
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

		$options[] = array( 'type' => 'js_hide_end' );

		// input
		$options[] = array(
			"name"		=> _x( 'Related posts height for posts with sidebar (px)', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_projects_height',
			"std"		=> 180,
			"type"		=> 'text',
			// number
			"sanitize"	=> 'ppp'
		);

		// radio
		$options[] = array(
			"name"		=> _x( 'Related posts width for posts with sidebar', 'theme-options', LANGUAGE_ZONE ),
			"id"		=> 'general-rel_projects_width_style',
			"std"		=> 'prop',
			"type"		=> 'radio',
			"options"	=> $prop_fixed_options,
			"show_hide"	=> array( 'fixed' => true ),
		);

		// hidden area
		$options[] = array( 'type' => 'js_hide_begin' );

			// input
			$options[] = array(
				"name"		=> _x( 'Width (px)', 'theme-options', LANGUAGE_ZONE ),
				"id"		=> 'general-rel_projects_width',
				"std"		=> '180',
				"type"		=> 'text',
				// number
				"sanitize"	=> 'ppp'
			);

		$options[] = array( 'type' => 'js_hide_end' );

	$options[] = array( 'type' => 'js_hide_end' );

$options[] = array(	"type" => "block_end");

/**
 * Meta information settings.
 */
$options[] = array(	"name" => _x('Meta information settings', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	//*************************************************************************************************
	// Blog
	//*************************************************************************************************

	// title
	$options[] = array( "type" => 'title', "name" => _x('Blog & posts', 'theme-options', LANGUAGE_ZONE) );

	// radio
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-blog_meta_on',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $yes_no_options,
		"show_hide"	=> array( '1' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Post format', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_postformat',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Date', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_date',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Author', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_author',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Categories', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_categories',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Comments', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_comments',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Tags', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-blog_meta_tags',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array( 'type' => 'js_hide_end' );

	//*************************************************************************************************
	// Portfolio
	//*************************************************************************************************

	// divider
	$options[] = array( "type" => 'divider' );

	// title
	$options[] = array( "type" => 'title', "name" => _x('Portfolio projects', 'theme-options', LANGUAGE_ZONE) );

	// radio
	$options[] = array(
		"desc"		=> '',
		"name"		=> _x('Show meta information', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-portfolio_meta_on',
		"std"		=> '1',
		"type"		=> 'radio',
		"options"	=> $yes_no_options,
		"show_hide"	=> array( '1' => true ),
	);

	// hidden area
	$options[] = array( 'type' => 'js_hide_begin' );

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Date', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-portfolio_meta_date',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Author', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-portfolio_meta_author',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Categories', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-portfolio_meta_categories',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

		// checkbox
		$options[] = array(
			"desc"  	=> '',
			"name"      => _x( 'Comments', 'theme-options', LANGUAGE_ZONE ),
			"id"    	=> 'general-portfolio_meta_comments',
			"type"  	=> 'checkbox',
			'std'   	=> 1
		);

	$options[] = array( 'type' => 'js_hide_end' );

$options[] = array(	"type" => "block_end");

/**
 * Smooth scroll.
 */
$options[] = array(	"name" => _x('Smooth scroll', 'theme-options', LANGUAGE_ZONE), "type" => "block_begin" );

	// radio
	$options[] = array(
		"name"		=> _x('Smooth scroll', 'theme-options', LANGUAGE_ZONE),
		"id"		=> 'general-smooth_scroll',
		"std"		=> 'on',
		"type"		=> 'radio',
		"options"	=> array(
			'on'			=> _x( 'always on', 'theme-options', LANGUAGE_ZONE ),
			'off'			=> _x( 'always off', 'theme-options', LANGUAGE_ZONE ),
			'on_parallax'	=> _x( 'on only on pages with parallax', 'theme-options', LANGUAGE_ZONE )
		)
	);

$options[] = array(	"type" => "block_end");
