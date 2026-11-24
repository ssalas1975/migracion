<?php
/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */

if ( ! function_exists( 'wp_bootstrap_starter_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp_bootstrap_starter_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP Bootstrap Starter, use a find and replace
	 * to change 'wp-bootstrap-starter' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'wp-bootstrap-starter', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'principal' => esc_html__( 'Principal', 'wp-bootstrap-starter' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wp_bootstrap_starter_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

    function wp_boostrap_starter_add_editor_styles() {
        add_editor_style( 'custom-editor-style.css' );
    }
    add_action( 'admin_init', 'wp_boostrap_starter_add_editor_styles' );

}
endif;
add_action( 'after_setup_theme', 'wp_bootstrap_starter_setup' );


/**
 * Add Welcome message to dashboard
 */
function wp_bootstrap_starter_reminder(){
        $theme_page_url = 'https://afterimagedesigns.com/wp-bootstrap-starter/?dashboard=1';

            if(!get_option( 'triggered_welcomet')){
                $message = sprintf(__( 'Welcome to WP Bootstrap Starter Theme! Before diving in to your new theme, please visit the <a style="color: #fff; font-weight: bold;" href="%1$s" target="_blank">theme\'s</a> page for access to dozens of tips and in-depth tutorials.', 'wp-bootstrap-starter' ),
                    esc_url( $theme_page_url )
                );

                printf(
                    '<div class="notice is-dismissible" style="background-color: #6C2EB9; color: #fff; border-left: none;">
                        <p>%1$s</p>
                    </div>',
                    $message
                );
                add_option( 'triggered_welcomet', '1', '', 'yes' );
            }

}
add_action( 'admin_notices', 'wp_bootstrap_starter_reminder' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_bootstrap_starter_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wp_bootstrap_starter_content_width', 1170 );
}
add_action( 'after_setup_theme', 'wp_bootstrap_starter_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_bootstrap_starter_widgets_init() {
	register_sidebar( array(
        'name'          => esc_html__( 'Menu superior derecha', 'wp-bootstrap-starter' ),
        'id'            => 'menu-superior-derecha',
        'description'   => esc_html__( 'Espacio para el buscador y el cambio de idioma.', 'wp-bootstrap-starter' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'Suscripción', 'wp-bootstrap-starter' ),
        'id'            => 'suscripcion',
        'description'   => esc_html__( 'Formulario de suscripción.', 'wp-bootstrap-starter' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
	//
	// NOTICIAS
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - titular genérico', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-titular',
        'description'   => esc_html__( 'Texto "El blog de Madrid Fusión".', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - hamburguesa', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-hamburguesa',
        'description'   => esc_html__( 'Texto "Secciones".', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - menu', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-menu',
        'description'   => esc_html__( 'Menu de Noticias.', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - buscador', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-buscador',
        'description'   => esc_html__( 'El texto que aparece en el campo del buscador', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - compartir', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-compartir',
        'description'   => esc_html__( 'Botones de compartir del plugin AddThis', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - últimas noticias', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-ultimas-noticias',
        'description'   => esc_html__( 'El texto que aparece como titular de Últimas Noticias', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - barra lateral', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-barra-lateral',
        'description'   => esc_html__( 'Widgets que se quieran incluir en la barra lateral', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	// COCINEROS
	register_sidebar( array(
        'name'          => esc_html__( 'COCINEROS - titular genérico', 'wp-bootstrap-starter' ),
        'id'            => 'cocineros-titular',
        'description'   => esc_html__( 'Texto "Cocineros"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'COCINEROS - Búsqueda por letras', 'wp-bootstrap-starter' ),
        'id'            => 'cocineros-abecedario',
        'description'   => esc_html__( 'Búsqueda alfabética de cocineros', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'COCINEROS - buscador', 'wp-bootstrap-starter' ),
        'id'            => 'cocineros-buscador',
        'description'   => esc_html__( 'El texto que aparece en el campo del buscador', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'COCINEROS - recetas', 'wp-bootstrap-starter' ),
        'id'            => 'cocineros-recetas',
        'description'   => esc_html__( 'El texto que aparece como titular de Recetas', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	// RECETAS
	register_sidebar( array(
        'name'          => esc_html__( 'RECETAS - titular genérico', 'wp-bootstrap-starter' ),
        'id'            => 'recetas-titular',
        'description'   => esc_html__( 'Texto "Recetas"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RECETAS - buscador', 'wp-bootstrap-starter' ),
        'id'            => 'recetas-buscador',
        'description'   => esc_html__( 'El texto que aparece en el campo del buscador', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RECETAS - recetas relacionadas', 'wp-bootstrap-starter' ),
        'id'            => 'recetas-relacionadas',
        'description'   => esc_html__( 'El texto que aparece como titular de "Recetas Relacionadas"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RECETAS - últimas recetas', 'wp-bootstrap-starter' ),
        'id'            => 'recetas-ultimas',
        'description'   => esc_html__( 'El texto que aparece como titular de "Últimas recetas"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	// RESTAURANTES
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - titular genérico', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-titular',
        'description'   => esc_html__( 'Texto "Restaurantes"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - menu', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-menu',
        'description'   => esc_html__( 'Menu de Restaurantes (continentes).', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - buscador', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-buscador',
        'description'   => esc_html__( 'El texto que aparece en el campo del buscador', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - recetas relacionadas', 'wp-bootstrap-starter' ),
        'id'            => 'recetas-restaurantes',
        'description'   => esc_html__( 'El texto que aparece como titular de "Recetas de los cocineros"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - contacto', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-contacto',
        'description'   => esc_html__( 'El texto que aparece como titular de "Contactos"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - cocineros', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-cocineros',
        'description'   => esc_html__( 'El texto que aparece como titular de "Cocineros"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - enlace buscador (provisional)', 'wp-bootstrap-starter' ),
        'id'            => 'enlace-buscador-restaurante',
        'description'   => esc_html__( '', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	// PIE
	register_sidebar( array(
        'name'          => esc_html__( 'PIE - redes sociales', 'wp-bootstrap-starter' ),
        'id'            => 'pie-redes',
        'description'   => esc_html__( 'Menú de redes sociales', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'PIE - organiza', 'wp-bootstrap-starter' ),
        'id'            => 'pie-organiza',
        'description'   => esc_html__( 'Organiza Foro de debate', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'PIE - copy', 'wp-bootstrap-starter' ),
        'id'            => 'pie-copy',
        'description'   => esc_html__( 'Copyright', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'PIE - legal', 'wp-bootstrap-starter' ),
        'id'            => 'pie-legal',
        'description'   => esc_html__( 'menú de legales', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wp-bootstrap-starter' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wp-bootstrap-starter' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'wp-bootstrap-starter' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here.', 'wp-bootstrap-starter' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'wp-bootstrap-starter' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here.', 'wp-bootstrap-starter' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'wp-bootstrap-starter' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here.', 'wp-bootstrap-starter' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
	
}
add_action( 'widgets_init', 'wp_bootstrap_starter_widgets_init' );



/**
 * Enqueue scripts and styles.
 */
function wp_bootstrap_starter_scripts() {
	// load bootstrap css
	wp_enqueue_style( 'wp-bootstrap-starter-bootstrap-css', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css' );
    // fontawesome cdn
    /* XERIA
	wp_enqueue_style( 'wp-bootstrap-pro-fontawesome-cdn', '//use.fontawesome.com/releases/v5.0.12/css/all.css' );
	*/
	// load bootstrap css
	// load AItheme styles
	// load WP Bootstrap Starter styles
	wp_enqueue_style( 'wp-bootstrap-starter-style', get_stylesheet_uri() );
    if(get_theme_mod( 'theme_option_setting' ) && get_theme_mod( 'theme_option_setting' ) !== 'default') {
        wp_enqueue_style( 'wp-bootstrap-starter-'.get_theme_mod( 'theme_option_setting' ), get_template_directory_uri() . '/inc/assets/css/presets/theme-option/'.get_theme_mod( 'theme_option_setting' ).'.css', false, '' );
    }
    /* XERIA
	if(get_theme_mod( 'preset_style_setting' ) === 'poppins-lora') {
        wp_enqueue_style( 'wp-bootstrap-starter-poppins-lora-font', '//fonts.googleapis.com/css?family=Lora:400,400i,700,700i|Poppins:300,400,500,600,700' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'montserrat-merriweather') {
        wp_enqueue_style( 'wp-bootstrap-starter-montserrat-merriweather-font', '//fonts.googleapis.com/css?family=Merriweather:300,400,400i,700,900|Montserrat:300,400,400i,500,700,800' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'poppins-poppins') {
        wp_enqueue_style( 'wp-bootstrap-starter-poppins-font', '//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'roboto-roboto') {
        wp_enqueue_style( 'wp-bootstrap-starter-roboto-font', '//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'arbutusslab-opensans') {
        wp_enqueue_style( 'wp-bootstrap-starter-arbutusslab-opensans-font', '//fonts.googleapis.com/css?family=Arbutus+Slab|Open+Sans:300,300i,400,400i,600,600i,700,800' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'oswald-muli') {
        wp_enqueue_style( 'wp-bootstrap-starter-oswald-muli-font', '//fonts.googleapis.com/css?family=Muli:300,400,600,700,800|Oswald:300,400,500,600,700' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'montserrat-opensans') {
        wp_enqueue_style( 'wp-bootstrap-starter-montserrat-opensans-font', '//fonts.googleapis.com/css?family=Montserrat|Open+Sans:300,300i,400,400i,600,600i,700,800' );
    }
    if(get_theme_mod( 'preset_style_setting' ) === 'robotoslab-roboto') {
        wp_enqueue_style( 'wp-bootstrap-starter-robotoslab-roboto', '//fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700|Roboto:300,300i,400,400i,500,700,700i' );
    }
	
    if(get_theme_mod( 'preset_style_setting' ) && get_theme_mod( 'preset_style_setting' ) !== 'default') {
        wp_enqueue_style( 'wp-bootstrap-starter-'.get_theme_mod( 'preset_style_setting' ), get_template_directory_uri() . '/inc/assets/css/presets/typography/'.get_theme_mod( 'preset_style_setting' ).'.css', false, '' );
    }
	*/
	
    //Color Scheme
    /*if(get_theme_mod( 'preset_color_scheme_setting' ) && get_theme_mod( 'preset_color_scheme_setting' ) !== 'default') {
        wp_enqueue_style( 'wp-bootstrap-starter-'.get_theme_mod( 'preset_color_scheme_setting' ), get_template_directory_uri() . '/inc/assets/css/presets/color-scheme/'.get_theme_mod( 'preset_color_scheme_setting' ).'.css', false, '' );
    }else {
        wp_enqueue_style( 'wp-bootstrap-starter-default', get_template_directory_uri() . '/inc/assets/css/presets/color-scheme/blue.css', false, '' );
    }*/

	wp_enqueue_script('jquery');

    // Internet Explorer HTML5 support
    wp_enqueue_script( 'html5hiv',get_template_directory_uri().'/inc/assets/js/html5.js', array(), '3.7.0', false );
    wp_script_add_data( 'html5hiv', 'conditional', 'lt IE 9' );

	// load bootstrap js
    wp_enqueue_script('wp-bootstrap-starter-popper', get_template_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true );
	wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true );
    wp_enqueue_script('wp-bootstrap-starter-themejs', get_template_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '', true );
	wp_enqueue_script( 'wp-bootstrap-starter-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_bootstrap_starter_scripts' );


function wp_bootstrap_starter_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <div class="d-block mb-3">' . __( "To view this protected post, enter the password below:", "wp-bootstrap-starter" ) . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __( "Password:", "wp-bootstrap-starter" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__( "Submit", "wp-bootstrap-starter" ) . '" class="btn btn-primary"/></div>
    </form>';
    return $o;
}
add_filter( 'the_password_form', 'wp_bootstrap_starter_password_form' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load plugin compatibility file.
 */
require get_template_directory() . '/inc/plugin-compatibility/plugin-compatibility.php';

/**
 * Load custom WordPress nav walker.
 */
if ( ! class_exists( 'wp_bootstrap_navwalker' )) {
    require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}

/*
X
E
R
I
*/

/* LIMITAR EXTRACTOS */
function custom_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/* NUEVAS ZONAS DE MENÚ */
register_nav_menus( array(
	'inscripcion' => __( 'Inscripción', 'wp-bootstrap-starter' )
) );


/// GET VARS LETRA
function rewrite_query_var() {
    add_rewrite_rule('cocineros_/letra/([^/]*)/?', 'index.php?pagename=cocineros_&letra=$matches[1]', 'top');
}
add_action('init', 'rewrite_query_var', 10, 0);

function add_custom_query_var( $vars ){
  $vars[] = "letra"; return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var');

function redirect_letra() {
    global $wp_rewrite;
    if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
        return;

    if ( strpos( $_SERVER['REQUEST_URI'], "letra=" )) {
        wp_redirect( home_url( "cocineros_/letra/" . urlencode( get_query_var( 'letra' ) )."/" ) );
        exit();
    }
}
 
add_action( 'template_redirect', 'redirect_letra' );


/// REDIRECT SEARCH
function nice_search_redirect() {
    global $wp_rewrite;
    if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
        return;
 
    $search_base = $wp_rewrite->search_base;
    if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
        wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
        exit();
    }
}
 
add_action( 'template_redirect', 'nice_search_redirect' );