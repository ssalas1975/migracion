<?php

/**
 * summit2020 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package summit2020
 */

if (!function_exists('summit2020_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function summit2020_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on summit2020, use a find and replace
		 * to change 'summit2020' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('summit2020', get_template_directory() . '/languages');

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
			'menu-principal' => esc_html__('menu-principal', 'summit2020'),
			'menu-rrss' => esc_html__('Redes Sociales', 'summit2020'),
			'menu-footer' => esc_html__('Footer', 'summit2020'),
			'menu-idioma' => esc_html__('Idioma', 'summit2020'),
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

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('summit2020_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support('custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		));
	}
endif;
add_action('after_setup_theme', 'summit2020_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function summit2020_content_width()
{
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters('summit2020_content_width', 640);
}
add_action('after_setup_theme', 'summit2020_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function summit2020_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Registrate', 'summit2020'),
		'id'            => 'registrate',
		'description'   => esc_html__('Añadir widget de registro.', 'summit2020'),
		'before_widget' => '<nav class="section section-nav">',
		'after_widget'  => '</nav>'
	));
}
add_action('widgets_init', 'summit2020_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function summit2020_scripts()
{
	wp_enqueue_style('summit2020-style', get_stylesheet_uri());

	wp_enqueue_script('summit2020-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

	wp_enqueue_script('summit2020-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'summit2020_scripts');


////////////////////////////
////////////////////////////
////////////////////////////

/* QUITANDO GUTENBERG */
add_filter('use_block_editor_for_post_type', '__return_false', 100);


function set_script($post)
{

	$origen = ($_SERVER['HTTP_REFERER'] == (get_home_url() . '/')) ? '1' : '0';
	$url = ($post->post_name != 'home') ? home_url(add_query_arg(array())) : '/';
	$seccion = ($post->post_name != 'home') ? $post->post_name : 'portada';
	$tipoContenido = ($post->post_name != 'home') ? 'portadilla' : 'portada';
?>
	<script>
		TagManager.ready.then(function() {
			TagManager.set({
				url: "<?php echo $url ?>"
				seccion: "<?php echo $seccion ?>",
				subseccion1: "",
				subseccion2: "",
				subseccion3: "",
				titular: "<?php echo $seccion ?>",
				tipoDominio: "1",
				tipoContenido: "<?php echo $tipoContenido ?>",
				origenPortada: "<?php echo $origen; ?>",
				idioma: "<?php echo ICL_LANGUAGE_CODE; ?>"
			}).track();
		});
	</script>

<?php
}


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
