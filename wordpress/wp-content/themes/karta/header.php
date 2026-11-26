<?php
/**
 * Prueba The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Karta
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<?php include ($_SERVER["DOCUMENT_ROOT"].'/wp-content/themes/semillas_head.php'); ?>

    <meta name="robots" content="noindex,nofollow" />
</head>

<body <?php body_class(); ?><?php if ( is_page() ) { karta_background_image_url(); } ?>>
<!-- Blog -->
<br>

	<?php include ($_SERVER["DOCUMENT_ROOT"].'/wp-content/themes/semillas_body.php') ;?>


	<script>
                if(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/Android/i)){
                document.write('<div align=center><a href=http://viajescoolturetas.laverdad.es><img src=//proyectos.laverdad.es/img/logo-viajes-movil.png /></div>');
                } else {
                document.write('<div align=center><a href=http://viajescoolturetas.laverdad.es><img src=//proyectos.laverdad.es/img/logo-viajes.png /></div>');
                }
  </script>

	<div id="page" class="site">
			<header id="masthead" class="site-header" role="banner">
				<div class="container">
					<div class="row">
						<div class="col-xs-12" >
							<div class="site-branding">
									</div>
			        <!-- .site-branding -->

					<!-- #masthead -->

										<nav id="site-navigation" class="main-navigation" role="navigation">
								<div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu1">
										<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'karta' ); ?></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
								<div class="collapse navbar-collapse" id="menu1">
								<?php
								if (has_nav_menu('primary')) {
									wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'main-navigation__menu', 'container' => '', 'menu_id' => 'primary-menu', 'walker' => new Karta_Walker_Nav_Menu ) );
								}

								if ( is_active_sidebar( 'modals-1' ) ) {
									dynamic_sidebar( 'modals-1' );
								}
								?>
								</div>
							</nav><!-- #site-navigation -->

						</div>
					</div>
				</div>
			</header>

			<div id="content" class="site-content">
