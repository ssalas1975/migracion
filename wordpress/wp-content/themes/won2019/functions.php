<?php

/**
 * won2019 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package won2019
 */

if (!function_exists('won2019_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function won2019_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on won2019, use a find and replace
		 * to change 'won2019' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('won2019', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'menu-principal' => esc_html__('Principal', 'won2019'),
			'menu-summit' => esc_html__('Summit', 'won2019'),
			'menu-legales' => esc_html__('Legales', 'won2019'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));
	}
endif;
add_action('after_setup_theme', 'won2019_setup');




/* LIMITAR EXTRACTOS */
function custom_excerpt_length($length)
{
	return 40;
}
add_filter('excerpt_length', 'custom_excerpt_length', 100);

/*  ZONAS DE WIDGETS */
function won2019_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Link Footer', 'wp-bootstrap-starter'),
		'id'            => 'btn_summit',
		'before_widget' => '', 'after_widget'  => '',
	));
	register_sidebar(array(
		'name'          => esc_html__('Link Programa', 'wp-bootstrap-starter'),
		'id'            => 'btn_programa',
		'before_widget' => '', 'after_widget'  => '',
	));
}
add_action('widgets_init', 'won2019_widgets_init');


/*  CONVERSION DE FECHA */
$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

function la_fecha($fecha, $idioma, $dia = true)
{
	global $dias;
	global $meses;
	$fecha = explode('/', $fecha);
	$fecha = strtotime($fecha[2] . $fecha[1] . $fecha[0]);
	if ($dia) : echo ($idioma == 'es') ? date("d", $fecha) . ' de ' . $meses[date("n", $fecha) - 1] . '<br>(' . $dias[date("w", $fecha)] . ')' : date("d", $fecha) . ' ' . date("F (l)", $fecha);
	else : echo ($idioma == 'es') ? date("d", $fecha) . ' de ' . $meses[date("n", $fecha) - 1] : date("d", $fecha);
	endif;
}

/*  SHORTCODES [widget widget="btn_summit"]*/
function getWidget($atts)
{
	return dynamic_sidebar($atts['widget']);
}
add_shortcode('widget', 'getWidget');


/* DASHICONS */
function load_dashicons_front_end()
{
	wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'load_dashicons_front_end');

function wporg_simple_role()
{
	$role = get_role('simple_role');
	$role->add_cap('access_awesomeness');
	$role->add_cap('delete_others_posts');
	$role->add_cap('delete_others_pages');
	$role->add_cap('delete_pages');
	$role->add_cap('delete_posts');
	$role->add_cap('delete_private_pages');
	$role->add_cap('delete_private_posts');
	$role->add_cap('delete_published_pages');
	$role->add_cap('delete_published_posts');
	$role->add_cap('edit_others_pages');
	$role->add_cap('edit_others_posts');
	$role->add_cap('edit_pages');
	$role->add_cap('edit_posts');
	$role->add_cap('edit_private_pages');
	$role->add_cap('edit_private_posts');
	$role->add_cap('edit_published_pages');
	$role->add_cap('edit_published_posts');
	$role->add_cap('manage_categories');
	$role->add_cap('manage_links');
	$role->add_cap('moderate_comments');
	$role->add_cap('publish_pages');
	$role->add_cap('publish_posts');
	$role->add_cap('read');
	$role->add_cap('read_private_pages');
	$role->add_cap('read_private_posts');
	$role->add_cap('upload_files');
}
// Add the simple_role.
add_action('init', 'wporg_simple_role');












// /**
//  * Set the content width in pixels, based on the theme's design and stylesheet.
//  *
//  * Priority 0 to make it available to lower priority callbacks.
//  *
//  * @global int $content_width
//  */
// function won2019_content_width() {
// 	// This variable is intended to be overruled from themes.
// 	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
// 	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// 	$GLOBALS['content_width'] = apply_filters( 'won2019_content_width', 640 );
// }
// add_action( 'after_setup_theme', 'won2019_content_width', 0 );

// /**
//  * Register widget area.
//  *
//  * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
//  */
// function won2019_widgets_init() {
// 	register_sidebar( array(
// 		'name'          => esc_html__( 'Sidebar', 'won2019' ),
// 		'id'            => 'sidebar-1',
// 		'description'   => esc_html__( 'Add widgets here.', 'won2019' ),
// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</section>',
// 		'before_title'  => '<h2 class="widget-title">',
// 		'after_title'   => '</h2>',
// 	) );
// }
// add_action( 'widgets_init', 'won2019_widgets_init' );

// /**
//  * Enqueue scripts and styles.
//  */
// function won2019_scripts() {
// 	wp_enqueue_style( 'won2019-style', get_stylesheet_uri() );

// 	wp_enqueue_script( 'won2019-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

// 	wp_enqueue_script( 'won2019-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

// 	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
// 		wp_enqueue_script( 'comment-reply' );
// 	}
// }
// add_action( 'wp_enqueue_scripts', 'won2019_scripts' );

// /**
//  * Implement the Custom Header feature.
//  */
// require get_template_directory() . '/inc/custom-header.php';

// /**
//  * Custom template tags for this theme.
//  */
// require get_template_directory() . '/inc/template-tags.php';

// /**
//  * Functions which enhance the theme by hooking into WordPress.
//  */
// require get_template_directory() . '/inc/template-functions.php';

// /**
//  * Customizer additions.
//  */
// require get_template_directory() . '/inc/customizer.php';

// /**
//  * Load Jetpack compatibility file.
//  */
// if ( defined( 'JETPACK__VERSION' ) ) {
// 	require get_template_directory() . '/inc/jetpack.php';
// }

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
