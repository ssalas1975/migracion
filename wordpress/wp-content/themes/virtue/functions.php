<?php

/**
 * Add functions files
 *
 * @package Virtue Theme
 */

/**
 * Language setup
 */
function virtue_lang_setup()
{
	load_theme_textdomain('virtue', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'virtue_lang_setup');

/*
 * Init Theme Options
 */
require_once trailingslashit(get_template_directory()) . 'themeoptions/framework.php';                 // Options framework.
require_once trailingslashit(get_template_directory()) . 'themeoptions/options.php';                   // Options settings.
require_once trailingslashit(get_template_directory()) . 'themeoptions/options/virtue_extension.php';  // Options framework extension.

/*
 * Init Theme Startup/Core utilities/classes
 */
require_once trailingslashit(get_template_directory()) . 'lib/classes/class-virtue-plugin-check.php';          // Check plugin class.
require_once trailingslashit(get_template_directory()) . 'lib/utils.php';                                      // Utility functions.
require_once trailingslashit(get_template_directory()) . 'lib/init.php';                                       // Initial theme setup and constants
require_once trailingslashit(get_template_directory()) . 'lib/sidebar.php';                                    // Sidebar class
require_once trailingslashit(get_template_directory()) . 'lib/config.php';                                     // Configuration
require_once trailingslashit(get_template_directory()) . 'lib/cleanup.php';                                    // Cleanup
require_once trailingslashit(get_template_directory()) . 'lib/elementor/elementor-support.php';                // Elementor Support
require_once trailingslashit(get_template_directory()) . 'lib/nav.php';                                        // Custom nav modifications
require_once trailingslashit(get_template_directory()) . 'lib/metaboxes.php';                           // Custom metaboxes
require_once trailingslashit(get_template_directory()) . 'lib/comments.php';                            // Custom comments modifications
require_once trailingslashit(get_template_directory()) . 'lib/image-functions.php';                     // Image functions
require_once trailingslashit(get_template_directory()) . 'lib/class-virtue-get-image.php';              // Image Class
require_once trailingslashit(get_template_directory()) . 'lib/custom.php';                              // Custom functions
require_once trailingslashit(get_template_directory()) . 'lib/kadence-toolkit-plugin.php';              // Plugin Activation.

/*
* Woomcommerce Support
*/
require_once trailingslashit(get_template_directory()) . 'lib/woocommerce/woo-core-hooks.php';           // Woocommerce Core functions
require_once trailingslashit(get_template_directory()) . 'lib/woocommerce/woo-archive-hooks.php';        // Woocommerce Archive functions
require_once trailingslashit(get_template_directory()) . 'lib/woocommerce/woo-single-product-hooks.php'; // Woocommerce single product functions
require_once trailingslashit(get_template_directory()) . 'lib/woo-account.php';                          // Woocommerce account functions.

/*
 * Template Hooks
 */
require_once trailingslashit(get_template_directory()) . 'lib/authorbox.php';                             // Author box
require_once trailingslashit(get_template_directory()) . 'lib/template_hooks/portfolio_hooks.php';        // Portfolio Template Hooks
require_once trailingslashit(get_template_directory()) . 'lib/template_hooks/post_hooks.php';             // Post Template Hooks
require_once trailingslashit(get_template_directory()) . 'lib/template_hooks/page_hooks.php';             // Post Template Hooks.

/*
 * Init Widgets
 */
require_once trailingslashit(get_template_directory()) . 'lib/widgets.php';                               // Sidebars and widgets.

/*
 * Load Scripts
 */
require_once trailingslashit(get_template_directory()) . 'lib/admin-scripts.php';                         // Admin Scripts
require_once trailingslashit(get_template_directory()) . 'lib/scripts.php';                               // Scripts and stylesheets
require_once trailingslashit(get_template_directory()) . 'lib/custom-css.php';                            // Fontend Custom CSS.

/**
 * Note: Do not add any custom code here. Please use a custom plugin or child theme so that your customizations aren't lost during updates.
 * https://www.kadencewp.com/child-themes/
 */
add_action('admin_init', function () {
	// Redirect any user trying to access comments page
	global $pagenow;

	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}

	// Remove comments metabox from dashboard
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

	// Disable support for comments and trackbacks in post types
	foreach (get_post_types() as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
	remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
});
