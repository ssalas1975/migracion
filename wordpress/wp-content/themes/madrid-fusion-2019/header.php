<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php do_shortcode( "[voonto_head]" ); ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];
        var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-NM5D5PF');
    </script>
<?php wp_head(); ?>
<!--XERIA-->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/assets/css/xeria.css" media="all" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/assets/css/xeria-responsive.css" media="all" />
<script src="<?php echo get_template_directory_uri(); ?>/inc/assets/js/xeria.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/assets/css/swiper.min.css" media="all" />
<script src="<?php echo get_template_directory_uri(); ?>/inc/assets/js/swiper.min.js"></script>
<!--XERIA-->
</head>

<body <?php body_class(); ?>>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NM5D5PF" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php do_shortcode( "[voonto_body]" ); ?>

<!-- video-noticia -->
<section class="video-noticia">
    <span class="close"></span>
</section>
<!-- video-noticia -->

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
        <div class="container">

            <nav class="navbar navbar-expand-xl p-0 horizontal">
                <div class="navbar-brand">
                    <?php if ( get_theme_mod( 'wp_bootstrap_starter_logo' ) ): ?>
                        <a href="<?php echo esc_url( home_url( '/' )); ?>">
                            <img src="<?php echo esc_attr(get_theme_mod( 'wp_bootstrap_starter_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                        </a>
                    <?php else : ?>
                        <a class="site-title" href="<?php echo esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>
                    <?php endif; ?>

                </div>

                <button class="navbar-toggler hamburguesa" type="button" data-toggle="collapse" data-target="#main-nav-mov" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse justify-content-end collapse" id="main-nav">
				<div class="contMenu">

				<?php
					wp_nav_menu(array(
						'theme_location'    => 'principal',
						'container'       => 'div',
						'container_id'    => 'main-nav-cont',
						//'container_class' => 'collapse navbar-collapse justify-content-end',
						'menu_id'         => false,
						'menu_class'      => 'navbar-nav',
						'depth'           => 3,
						'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
						'walker'          => new wp_bootstrap_navwalker()
					));
                ?>

                <!--buscador-->
                <div class="busquedas">
                <div class="formulario-busqueda expandible">
                    <div class="expandir"></div>
                    <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                        <label>
                            <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>" value="<?php /*?><?php echo get_search_query() ?><?php */?>" name="s" title="<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>'"/>
                        </label>
                        <input type="hidden" name="ord" value="rel"/>
                        <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
                    </form>
                </div>
                </div>
                <!-- / buscador-->


                </div>
                    <div class="menu-superior-derecha">
                        <?php if (!dynamic_sidebar('menu-superior-derecha')) {} ?>
                    </div>
                </div>

                <?php
				wp_nav_menu( array(
				'theme_location'    => 'inscripcion',
                'container'       => 'div',
                'container_id'    => 'inscripcion',
                'container_class' => 'inscripcion',
                'menu_id'         => false,
                'menu_class'      => 'navbar-nav',
                'depth'           => 3,
                'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                'walker'          => new wp_bootstrap_navwalker()
				) );
				?>

            </nav>

            <!---------------MENU MOVIL----------------------->
            <nav class="navbar navbar-expand-xl p-0 menuGris">



                <div class="navbar-collapse justify-content-end collapse mov" id="main-nav-mov">

                <button class="navbar-toggler hamburguesa" type="button" data-toggle="collapse" data-target="#main-nav-mov" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

				<?php
					wp_nav_menu(array(
						'theme_location'    => 'desplegable',
						'container'       => 'div',
						'container_id'    => 'main-nav-cont',
						//'container_class' => 'collapse navbar-collapse justify-content-end',
						'menu_id'         => false,
						'menu_class'      => 'navbar-nav',
						'depth'           => 3,
						'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
						'walker'          => new wp_bootstrap_navwalker()
					));
                ?>
                <div class="menu-superior-derecha">

                    <!--buscador-->
                  <div class="formulario-busqueda">
                      <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                          <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
                          <label>
                              <input required pattern=".{3,}" oninvalid="alert('<?php  _e( 'Tus busquedas deben tener al menos 3 caracteres.', 'wp-bootstrap-starter' ); ?>')" type="search" class="search-field" placeholder="<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>" value="<?php /*?><?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?><?php */?>" name="s" title="<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e( 'Buscar en la Web |', 'wp-bootstrap-starter' ); ?>'"/>
                          </label>
                          <input type="hidden" name="ord" value="rel"/>
                      </form>
                  </div>
                    <!-- / buscador-->

					<?php if (!dynamic_sidebar('menu-superior-derecha')) {} ?>
                </div>
                </div>
            </nav>
            <!---------------MENU MOVIL----------------------->




        </div>
	</header><!-- #masthead -->
    <?php if(is_front_page() && !get_theme_mod( 'header_banner_visibility' )): ?>
        <?php /*?>
		<div id="page-sub-header" <?php if(has_header_image()) { ?>style="background-image: url('<?php header_image(); ?>');" <?php } ?>>
            <div class="container">
                <h1>
                    <?php
                    if(get_theme_mod( 'header_banner_title_setting' )){
                        echo get_theme_mod( 'header_banner_title_setting' );
                    }else{
                        echo 'Wordpress + Bootstrap';
                    }
                    ?>
                </h1>
                <p>
                    <?php
                    if(get_theme_mod( 'header_banner_tagline_setting' )){
                        echo get_theme_mod( 'header_banner_tagline_setting' );
                }else{
                        echo esc_html__('To customize the contents of this header banner and other elements of your site, go to Dashboard > Appearance > Customize','wp-bootstrap-starter');
                    }
                    ?>
                </p>
                <a href="#content" class="page-scroller"><i class="fa fa-fw fa-angle-down"></i></a>
            </div>
        </div>
		<?php */?>
    <?php endif; ?>
	<div id="content" class="site-content">
    <?php endif; ?>
