<?php
/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */


//////////////
/// AÑO ACTUAL

   $año_actual=2020;

///////////////
//////////////


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
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	//
	// NOTICIAS
	/*register_sidebar( array(
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
    ) );*/
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - compartir', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-compartir',
        'description'   => esc_html__( 'Botones de compartir del plugin AddThis', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	/*register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - últimas noticias', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-ultimas-noticias',
        'description'   => esc_html__( 'El texto que aparece como titular de Últimas Noticias', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );*/
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIAS - barra lateral', 'wp-bootstrap-starter' ),
        'id'            => 'noticias-barra-lateral',
        'description'   => esc_html__( 'Widgets que se quieran incluir en la barra lateral', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => esc_html__( 'NOTICIA HOME - IINSCRIPCIÓN', 'wp-bootstrap-starter' ),
        'id'            => 'noticia-destacada-home-inscripcion',
        'description'   => esc_html__( 'Incluye aquí el enlace de la Inscripción. Dentro de las comillas de class=" " incluye la convinación de posicion horizontal (izquierda derecha) y vertical (arriba abajo). ', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	// COCINEROS
	/*register_sidebar( array(
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
    ) );*/
	// RECETAS
	/*register_sidebar( array(
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
    ) );*/
	// RESTAURANTES
	/*register_sidebar( array(
        'name'          => esc_html__( 'RESTAURANTES - titular genérico', 'wp-bootstrap-starter' ),
        'id'            => 'restaurantes-titular',
        'description'   => esc_html__( 'Texto "Restaurantes"', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );*/
	/*register_sidebar( array(
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
    ) );*/
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
	/* CABECERA CONGRESO */
	register_sidebar( array(
        'name'          => esc_html__( 'CONGRESO - cabecera', 'wp-bootstrap-starter' ),
        'id'            => 'congreso-cabecera',
        'description'   => esc_html__( 'URL de la imagen de la cabecera (pegar entre las comilla de src="AQUI" la URL de la imagen)', 'wp-bootstrap-starter' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	/*
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
    ) );*/

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
register_nav_menus( array(
	'desplegable' => __( 'Desplegable', 'wp-bootstrap-starter' )
) );
register_nav_menus( array(
	'congreso' => __( 'Congreso', 'wp-bootstrap-starter' )
) );
register_nav_menus( array(
    'congresoEdicionesPasadas' => __( 'Congreso Ediciones Pasadas', 'wp-bootstrap-starter' )
) );




/// /////////////////////////
/////////////////////////////

function add_custom_query_var( $vars ){
    $vars[] = "letra";
    $vars[] = "find";
    $vars[] = "id_pais";
    $vars[] = "cont";
    $vars[] = "seccion";
    $vars[] = "ord";
    $vars[] = "ano";
    return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var');

function get_by_title( $where, $query ) {

    global $wpdb;

    $titulo = $query->get( 'titulo' );

    if ( $titulo ) {
        $titulo_parts=explode(' ',$titulo);
        foreach ($titulo_parts as $k => $tit) {
            $where .= " AND $wpdb->posts.post_title LIKE '%$tit%'";
        }
    }

    return $where;
}
add_filter( 'posts_where', 'get_by_title', 10, 2 );


function search_filter($query) {

    if (!is_admin()&&$query->is_search&&get_query_var( 'find' )==NULL) {

        $ord=get_query_var( 'ord' );

        if($ord=='rel'){
            $destacados = get_posts( array(
                'fields' => 'ids',
                'post_type'=>'any',
                "posts_per_page"=>-1,
                's' => $s,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key'      => 'cocinero-destacado',
                        'meta_value'    => 1,
                        'meta_compare'  => '==',
                    ),
                    array(
                        'key'      => 'restaurante-destacado',
                        'meta_value'    => 1,
                        'meta_compare'  => '==',
                    ),
                    array(
                        'key'      => 'noticia-destacada',
                        'meta_value'    => 1,
                        'meta_compare'  => '==',
                    ),
                    array(
                        'key'      => 'receta-destacada',
                        'meta_value'    => 1,
                        'meta_compare'  => '==',
                    ),

                ),
                'orderby' => 'date',
                'order' => 'DESC',

            ));
        }

        $resto = get_posts( array(
            'fields' => 'ids',
            'post_type'=>array( 'cocineros', 'restaurantes', 'noticias', 'recetas'),
            "posts_per_page"=>-1,
            's' => $s,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        $res =($ord=='rel')? array_unique(array_merge( $destacados, $resto)): $resto;

        $query->set( 'post__in', $res );
        $query->set( 'posts_per_page', 12 );
        $query->set( 'post_type', array( 'cocineros', 'restaurantes', 'noticias', 'recetas') );
        $query->set( 'orderby', 'date' );
        if($ord!='rel'){ $query->set( 'order', $ord); };
    }
}
add_action('pre_get_posts','search_filter');

function link_cpt($cpt){

     switch($cpt) {
        case 'cocineros': $link='cocineros'; break;
        case 'recetas': $link='recetas'; break;
        case 'noticias': $link='el-blog'; break;
        case 'restaurantes': $link='restaurantes'; break;
        case 'restaurantes': $link='restaurantes'; break;
    }
    return esc_url( home_url('/')).$link;
}

//////////////////
// PERMALINKS

function set_rewrite($terms){
    //letra
     add_rewrite_rule($terms[0].'/'.$terms[1].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[0].'&letra=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[0].'/'.$terms[1].'/([^/]*)/?', 'index.php?pagename='.$terms[0].'&letra=$matches[1]', 'top');
    add_rewrite_rule($terms[0].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[0].'&find=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[0].'/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[0].'&paged=$matches[1]', 'top');
    add_rewrite_rule($terms[0].'/([^/]*)/?', 'index.php?pagename='.$terms[0].'&find=$matches[1]', 'top');
    //el blog
    add_rewrite_rule($terms[2].'/'.$terms[3].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[2].'&seccion=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[2].'/'.$terms[3].'/([^/]*)/?', 'index.php?pagename='.$terms[2].'&seccion=$matches[1]', 'top');
    add_rewrite_rule($terms[2].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[2].'&find=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[2].'/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[2].'&paged=$matches[1]', 'top');
    add_rewrite_rule($terms[2].'/([^/]*)/?', 'index.php?pagename='.$terms[2].'&find=$matches[1]', 'top');
    //recetas
    add_rewrite_rule($terms[4].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[4].'&find=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[4].'/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[4].'&paged=$matches[1]', 'top');
    add_rewrite_rule($terms[4].'/([^/]*)/?', 'index.php?pagename='.$terms[4].'&find=$matches[1]', 'top');
    //restaurantes
    add_rewrite_rule($terms[5].'/'.$terms[6].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[5].'&cont=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[5].'/'.$terms[6].'/([^/]*)/?', 'index.php?pagename='.$terms[5].'&cont=$matches[1]', 'top');
    add_rewrite_rule($terms[5].'/'.$terms[7].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[5].'&id_pais=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[5].'/'.$terms[7].'/([^/]*)/?', 'index.php?pagename='.$terms[5].'&id_pais=$matches[1]', 'top');
    add_rewrite_rule($terms[5].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[5].'&find=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[5].'/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[5].'&paged=$matches[1]', 'top');
    add_rewrite_rule($terms[5].'/([^/]*)/?', 'index.php?pagename='.$terms[5].'&find=$matches[1]', 'top');
    //congresos
    add_rewrite_rule($terms[8].'/'.$terms[9].'/([^/]*)/?', 'index.php?pagename='.$terms[9].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[10].'/([^/]*)/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[10].'&ano=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[10].'/page/?([0-9]{1,})/?', 'index.php?pagename='.$terms[10].'&paged=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[10].'/([^/]*)/?', 'index.php?pagename='.$terms[10].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[11].'/([^/]*)/?', 'index.php?pagename='.$terms[11].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[12].'/([^/]*)/?', 'index.php?pagename='.$terms[12].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[13].'/([^/]*)/?', 'index.php?pagename='.$terms[13].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[14].'/([^/]*)/?', 'index.php?pagename='.$terms[14].'&ano=$matches[1]', 'top');
    add_rewrite_rule($terms[8].'/'.$terms[15].'/([^/]*)/?', 'index.php?pagename='.$terms[15].'&ano=$matches[1]', 'top');
}

function rewrite_query_var() {
    // español
    $terms=Array('cocineros','letra','el-blog','seccion','recetas','restaurantes','continente','pais','congreso','programa','ponentes','talleres','conferencias-y-catas','concursos-y-premios','expositores','patrocinadores');
    set_rewrite($terms);
    // ingles
    $terms=Array('chefs','letter','the-blog','section','recipes','restaurants','continent','country','congress','program','speakers','workshops','conferences-and-tastings','contests-and-awards','exhibitors','sponsors');
    set_rewrite($terms);

}
add_action('init', 'rewrite_query_var', 10, 0);


// REDIRECT SEARCH
function permalink_search() {
     global $wp_rewrite;
     if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
         return;
    ///search general
     $search_base = $wp_rewrite->search_base;
     if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
         wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) ); exit();
     }
     ///search invidual
     if ( strpos( $_SERVER['REQUEST_URI'], "?find=" )) {
        global $wp;
        wp_redirect(  home_url( $wp->request )."/".urlencode( get_query_var( 'find' ))."/" ); exit();
     }
     ///search invidual
     if ( strpos( $_SERVER['REQUEST_URI'], "?id_pais=" )) {
        global $wp;
        $pais=(ICL_LANGUAGE_CODE=='es')? 'pais':'country';
        wp_redirect(  home_url( $wp->request )."/".$pais."/".urlencode( get_query_var( 'id_pais' ))."/" ); exit();
     }
 }

add_action( 'template_redirect', 'permalink_search' );


// LA FECHA
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
function la_fecha($date,$idioma){
    global $meses;
    $fecha=strtotime($date,true);
    echo ($idioma=='es')? date("d",$fecha).' de '.$meses[date("n",$fecha)-1].' de '.date("Y",$fecha) :  date("F j, Y",$fecha); 
}


//update relation meta post

// add_filter('the_posts', 'np_replace_empty_search', 10, 2);

// function np_replace_empty_search($posts, $wp_query){
//     $custompost= $wp_query->query_vars['post_type'];

//     if(!empty($posts)){

//         //global $sitepress;

//         //$sitepress->switch_lang( ICL_LANGUAGE_CODE );

//         switch($custompost)

//         {
//             case "agradecimientos":
//             case "conferencias":
//             case "homenajes":
//             case "concursos":
//             case "programas":

//                 foreach($posts as $postactual)

//                 {
//                     $idsantiguo=get_post_meta($postactual->ID,"id_cumbre",yes);
//                     if($idsantiguo!="")
//                     {
//                         $idsantiguo = str_pad($idsantiguo, 3, '0', STR_PAD_LEFT);
//                         if(strlen($idsantiguo)<3)
//                         {
//                             $idsantiguo="0".$idsantiguo;
//                         }
//                         $cumbrenuevo=array();
//                             $cumbre = new WP_Query( "post_type=cumbres&meta_key=id_antiguo&meta_value=".$idsantiguo);
//                             update_post_meta($postactual->ID,'rel_cumbre',$cumbre->posts[0]->ID );
//                              //$english=   icl_object_id($postactual->ID,'cumbres',false,ICL_LANGUAGE_CODE);
//                              //update_post_meta($english,'rel_cumbre',$cumbre->posts[0]->ID );
//                     }
//                 }
//                 return $posts;

//             case "recetas":
//                 foreach($posts as $postactual)
//                 {
//                     $idsantiguo=get_post_meta($postactual->ID,"id_cocinero",yes);
//                     if($idsantiguo!="")
//                     {
//                         $cumbre = new WP_Query( "post_type=cocineros&meta_key=id_antiguo&meta_value=".$idsantiguo);
//                         //añado ceros a la id de la receta
//                         if($cumbre->post_count==0)
//                         {
//                             $idsantiguo = str_pad($idsantiguo, 10, '0', STR_PAD_LEFT);
//                             $cumbre = new WP_Query( "post_type=cocineros&meta_key=id_antiguo&meta_value=".$idsantiguo);
//                         }
//                         //quito todos los ceros del idantiguo concinero  de la receta
//                         if($cumbre->post_count==0)
//                         {
//                             $idsantiguo=(int)$idsantiguo;
//                             $cumbre = new WP_Query( "post_type=cocineros&meta_key=id_antiguo&meta_value=".$idsantiguo);
//                         }
//                         update_post_meta($postactual->ID,'rel_cocinero',$cumbre->posts[0]->ID );
//                     }
//                 }
//                 return $posts;

//             case "cocineros":
//                 foreach($posts as $postactual){
//                     $allids=get_post_meta($postactual->ID,"idcumbreantiguo",yes);
//                     if($allids!="")
//                     {
//                         $idsantiguos=explode(",",$allids);
//                         $cumbrenuevo=array();
//                         foreach ($idsantiguos as $idantiguo) {
//                             $idantiguo = str_pad($idantiguo, 3, '0', STR_PAD_LEFT);
//                             $cumbre = new WP_Query( "post_type=cumbres&meta_key=id_antiguo&meta_value=".$idantiguo);
//                             $cumbresq=$cumbre->posts;
//                             if(is_array($cumbresq) && count($cumbresq)){
//                                 array_push($cumbrenuevo,$cumbresq[0]->ID);
//                             }
//                         };
//                         update_post_meta($postactual->ID,'rel_cumbre',$cumbrenuevo );
//                     }
//                     $allidsR=get_post_meta($postactual->ID,"idresturanteantiguo",yes);
//                     if($allidsR!="")
//                     {
//                         $idsantiguosR=explode(",",$allidsR);
//                         $rnuevo=array();
//                         foreach ($idsantiguosR as $idantiguo) {
//                             $idantiguo = str_pad($idantiguo, 10, '0', STR_PAD_LEFT);
//                             $rest = new WP_Query( "post_type=restaurantes&meta_key=id_antiguo&meta_value=".$idantiguo);
//                             $restq=$rest->posts;
//                             if(is_array($restq) && count($restq)){
//                                 array_push($rnuevo,$restq[0]->ID);
//                            }
//                         };
//                         update_post_meta($postactual->ID,'rel_restaurante',$rnuevo );
//                     }
//                 }
//                 return $posts;
//             default:
//                 return $posts;
//         }
//     }
//     return $posts;
// }


/*
 * Add columns to exhibition post list
 */
 function add_acf_columns ( $columns ) {
   return array_merge ( $columns, array (
     'rel_cumbre' => __ ( 'Cumbre' ),
        'rel_restaurante' => __ ( 'Restaurante' )

   ) );
 }
 add_filter ( 'manage_cocineros_posts_columns', 'add_acf_columns' );
 /*
 * Add columns to exhibition post list
 */
function allcustompost_custom_column ( $column, $post_id ) {
   switch ( $column ) {
     case 'rel_cumbre':
         $listcumbre = get_field('rel_cumbre', $post_id);
         $titulo="";
         foreach( $listcumbre as $c)
         {
             $titulo=  $titulo. "- <a href=\"".get_edit_post_link(get_post($c)->ID)."\">". get_post($c)->post_title."</a><br>  " ;
         }
         echo $titulo;
      // echo get_post_meta ( $post_id, 'rel_cumbre', true );
       break;
        case 'rel_restaurante':
         $listcumbre = get_field('rel_restaurante', $post_id);
         $titulo="";
         foreach( $listcumbre as $c)
         {
             $titulo=  $titulo. "- <a href=\"".get_edit_post_link(get_post($c)->ID)."\">".  get_post($c)->post_title."<br></a> " ;
         }
         echo $titulo;
      // echo get_post_meta ( $post_id, 'rel_cumbre', true );
       break;

   }
 }
add_action ( 'manage_cocineros_posts_custom_column', 'allcustompost_custom_column', 10, 2 );

///add columns RECETAS

/*
 * Add columns to exhibition post list para cumbres
 */
function add_acf_columns_recetas ( $columns ) {
    return array_merge ( $columns, array (
      'rel_cocinero' => __ ( 'Cocinero' ),


    ) );
}
add_filter ( 'manage_recetas_posts_columns', 'add_acf_columns_recetas' );
/*
 * Add columns to exhibition post list
 */
function allcustompost_custom_column_recetas ( $column, $post_id ) {
    switch ( $column ) {
        case 'rel_cocinero':
            $listcocinero = get_field('rel_cocinero', $post_id);
            $titulo="";
            if(isset($listcocinero))
            {
                $titulo= "<a href=\"".get_edit_post_link($listcocinero)."\">" . get_post($listcocinero)->post_title."<br>  " ;
            }
            echo $titulo;

            break;


    }
}

add_action ( 'manage_recetas_posts_custom_column', 'allcustompost_custom_column_recetas', 10, 2 );


/*
 * Add columns to exhibition post list para cumbres
 */
function add_acf_columns_general ( $columns ) {
    return array_merge ( $columns, array (
      'rel_cumbre' => __ ( 'Cumbre' ),


    ) );
}

/*
 * Add columns to exhibition post list
 */
function allcustompost_custom_column_general ( $column, $post_id ) {
    switch ( $column ) {
        case 'rel_cumbre':
            $listcumbre = get_field('rel_cumbre', $post_id);

            if(isset($listcumbre))
            {
              $titulo= "<a href=\"".get_edit_post_link($listcumbre)."\">" . get_post($listcumbre)->post_title."<br>  " ;
            }
            echo $titulo;

            break;


    }
}

add_filter ( 'manage_agradecimientos_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_programas_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_expositores_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_concursos_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_organismos_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_homenajes_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_patrocinadores_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_conferencias_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_nota_programas_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_recintos_posts_columns', 'add_acf_columns_general' );
add_filter ( 'manage_colaboradores_posts_columns', 'add_acf_columns_general' );

add_action ( 'manage_agradecimientos_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_programas_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_expositores_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_concursos_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_organismos_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_homenajes_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_patrocinadores_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_conferencias_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_nota_programas_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_recintos_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );
add_action ( 'manage_colaboradores_posts_custom_column', 'allcustompost_custom_column_general', 10, 2 );


function my_the_content_filter($content) {
	$content = do_shortcode($content );
	$content = str_replace('href="www', 'href="http://www', $content);
  return $content;
}
add_filter( 'the_content', 'my_the_content_filter' );


function save_urun_meta_date( $post_id ) {

    if(get_post_type($post_id) != "noticias")
        return;
    $value = get_post_meta($post_id, 'fecha', true);
    if($value == null || $value == "")
    {
        $date = get_post($post_id);
        $perico = get_the_date( 'Ymd', $post_id );
        update_post_meta($post_id, 'fecha', $perico);
    }
}
add_action( 'save_post', 'save_urun_meta_date' );


function km_add_unfiltered_html_capability_to_editors( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = array( 'unfiltered_html' );
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'km_add_unfiltered_html_capability_to_editors', 1, 3 );


/**
 * desactivar los comentarios
 */
function mf_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'mf_disable_comments_status', 20, 2);
add_filter('pings_open', 'mf_disable_comments_status', 20, 2);

/**
 * función carga dinámica post en los swiper
 */
add_action( 'wp_ajax_getContentSwiperHome', 'getContentSwiperHome' );
add_action( 'wp_ajax_nopriv_getContentSwiperHome', 'getContentSwiperHome' );
function getContentSwiperHome() {
    $offset = $_POST['offset'];
    $limit = $_POST['limit'];
    $lang = $_POST['language'];
    global $wpdb;
    $translatiosTable = $wpdb->get_blog_prefix().'icl_translations';
    
    // $querySelector = "
    // SELECT * FROM $wpdb->posts 
    // where $wpdb->posts.post_type='cocineros' 
    // and $wpdb->posts.post_status = 'publish'
    // and ID in (SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta where $wpdb->postmeta.meta_key = 'cocinero-destacado' and $wpdb->postmeta.meta_value=1)
    // order by $wpdb->posts.post_title asc
    // limit $limit offset $offset
    // ";
    $query = "
    SELECT * FROM $wpdb->posts 
where $wpdb->posts.post_type='cocineros'
and $wpdb->posts.post_status = 'publish'
and ID in (SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta where $wpdb->postmeta.meta_key = 'cocinero-destacado' and $wpdb->postmeta.meta_value=1
and $wpdb->postmeta.post_id in (SELECT element_id FROM $translatiosTable where language_code = '$lang'))
order by $wpdb->posts.post_title asc
limit $limit offset $offset
    ";
    $posts = $wpdb->get_results($query, ARRAY_A);
    wp_reset_query();

    $html = '';
    $html = '<!--p>'.$query.'</p-->';
    foreach($posts as $post){
		$id_rest=get_post_meta($post['ID'], 'rel_restaurante', true);
		$html .= '
        <div class="itemListadoCont swiper-slide" data-id="'.$post['ID'].'">
            <div class="itemListado">
                <div class="imagen">
                        <a href="'.apply_filters( 'wpml_permalink', get_permalink($post['ID']), $lang ).'">'.get_the_post_thumbnail($post['ID']).'</a>
                        uno</a> 
                </div>
                <div class="texto">
                    <div class="titular">
                            <h3><a href="'.apply_filters( 'wpml_permalink', get_permalink($post['ID']), $lang ).'">'.get_the_title($post['ID']).'</a></h3>
                    </div>';
                     if ( $id_rest[0]!=NULL ){
                        $hmtl .= '<div class="titular2">
                            <p><a href="'.apply_filters( 'wpml_permalink', get_permalink($id_rest[0]), $lang ).'">'.get_the_title($id_rest[0]).'</a></p>
                        </div>';
                      }
                $html .= '</div>
            </div>
        </div>
        ';
    }
    exit($html);
}


/**
 * Recuperar restaurante del cocinero en el swiper
 */
add_action( 'wp_ajax_getContentRestaurant', 'getContentRestaurant' );
add_action( 'wp_ajax_nopriv_getContentRestaurant', 'getContentRestaurant' );
function getContentRestaurant() {
    $idpost = $_POST['idpost'];
	$id_rest=get_post_meta($idpost, 'rel_restaurante', true);
    exit( json_encode($id_rest) );
}

/** */
