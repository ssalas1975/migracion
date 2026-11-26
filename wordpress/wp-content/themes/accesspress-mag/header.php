<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package AccessPress Mag
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="google-site-verification" content="zfrO1iWWvxx9EWUuc_q-SFXEmEgFIdeQmDUJ4VlcXV8" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/wp-content/themes/semillas_head.php'); ?>

<script type='text/javascript'>
googletag.cmd.push(function() {
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[728,90],[980,90],[980,250]],  'publi-megabanner').addService(googletag.pubads()).setTargeting("pos", "megabanner");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[120,600],[120,800]],  'publi-sky_izq').addService(googletag.pubads()).setTargeting("pos", "skyscraper_izq");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[120,600],[120,800]],  'publi-sky_der').addService(googletag.pubads()).setTargeting("pos", "skyscraper_der");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,250],[300,600],[300,300]],  'publi-robapaginas').addService(googletag.pubads()).setTargeting("pos", "robapaginas")
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,250],[300,600],[300,300]],  'publi-robapaginas-2').addService(googletag.pubads()).setTargeting("pos", "robapaginas_2")
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,250],[300,600],[300,300]],  'publi-robapaginas-3').addService(googletag.pubads()).setTargeting("pos", "robapaginas_3");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,250],[300,600],[300,300]],  'publi-robapaginas-4').addService(googletag.pubads()).setTargeting("pos", "robapaginas_dos");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[728,90],[980,90],[980,250]],  'publi-megabanner-inf').addService(googletag.pubads()).setTargeting("pos", "megabanner_inf");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,90]], 'publi-boton-doble1').addService(googletag.pubads()).setTargeting("pos", "boton_doble_local");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,90]], 'publi-boton-doble2').addService(googletag.pubads()).setTargeting("pos", "boton_doble_local2");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[300,90]], 'publi-boton-doble3').addService(googletag.pubads()).setTargeting("pos", "boton_doble_local3");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton1').addService(googletag.pubads()).setTargeting("pos", "boton_local1");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton2').addService(googletag.pubads()).setTargeting("pos", "boton_local2");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton3').addService(googletag.pubads()).setTargeting("pos", "boton_local3");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton4').addService(googletag.pubads()).setTargeting("pos", "boton_local4");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton5').addService(googletag.pubads()).setTargeting("pos", "boton_local5");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton6').addService(googletag.pubads()).setTargeting("pos", "boton_local6");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton7').addService(googletag.pubads()).setTargeting("pos", "boton_local7");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton8').addService(googletag.pubads()).setTargeting("pos", "boton_local8");
googletag.defineSlot('/4900/vocento.laverdad/blogs', [[320,60],[300,45]], 'publi-boton9').addService(googletag.pubads()).setTargeting("pos", "boton_local9");
googletag.defineOutOfPageSlot('/4900/vocento.laverdad/blogs', 'publi-intext').addService(googletag.pubads()).setTargeting("pos", "layer_inpage")
googletag.defineOutOfPageSlot('/4900/vocento.laverdad/blogs', 'publi-backcss').addService(googletag.pubads()).setTargeting("pos", "backcss");
googletag.defineOutOfPageSlot('/4900/vocento.laverdad/blogs', 'layer_inter').addService(googletag.pubads());

googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>

</head>

<body <?php body_class(); ?>

<?php include($_SERVER["DOCUMENT_ROOT"] . '/wp-content/themes/semillas_body.php'); ?>

<?php do_action( 'accesspress_mag_before' ); ?>
<div id="page" class="hfeed site"><div align="center"><iframe scrolling="no" frameborder="0" style="z-index: 1; width: 1198px; height: 32px;" marginwidth="0" marginheight="0" src="https://www.laverdad.es/ticker/murcia/minicabecera.htm"></iframe></div>
    <?php
        $accesspress_mag_logo_alt = of_get_option( 'logo_alt' );
        $accesspress_mag_logo_title = of_get_option( 'logo_title' );
        $accesspress_mag_ticker_option = of_get_option( 'news_ticker_option', '1' );
        $accesspress_mag_random_icon = of_get_option( 'random_icon_option', '1' );
    ?>

    <header id="masthead" class="site-header">

        <?php
            $apmag_date_option = of_get_option( 'header_current_date_option', '' );
            /**
             * Top menu section
             */
            if( has_nav_menu( 'top_menu' ) || has_nav_menu( 'top_menu_right' ) || $apmag_date_option != '1' ){
                $top_menu_class = 'has_menu';
            } else {
                $top_menu_class = 'no_menu';
            }
        ?>
        <div class="top-menu-wrapper <?php echo esc_attr( $top_menu_class ); ?> clearfix">
            <div class="apmag-container">
            <?php if( empty( $apmag_date_option ) && $apmag_date_option != '1' ) { ?>
            <div class="current-date"><?php echo date_i18n( 'l, F j, Y' ); ?></div>
            <?php } ?>
            <?php if ( has_nav_menu( 'top_menu' ) ) { ?>
                <nav id="top-navigation" class="top-main-navigation">
                            <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Top Menu', 'accesspress-mag' ); ?></button>
                            <?php wp_nav_menu( array( 'theme_location' => 'top_menu', 'container_class' => 'top_menu_left' ) ); ?>
                </nav><!-- #site-navigation -->
            <?php } ?>
            <?php if ( has_nav_menu( 'top_menu_right' ) ) { ?>
                <nav id="top-right-navigation" class="top-right-main-navigation">
                            <button class="menu-toggle hide" aria-controls="top-right-menu" aria-expanded="false"><?php _e( 'Top Menu Right', 'accesspress-mag' ); ?></button>
                            <?php wp_nav_menu( array( 'theme_location' => 'top_menu_right', 'container_class' => 'top_menu_right' ) ); ?>
                </nav><!-- #site-navigation -->
            <?php } ?>
            </div><!-- .apmag-container -->
        </div><!-- .top-menu-wrapper -->

        <?php
             // News Ticker section
             if( $accesspress_mag_ticker_option == '1' ){ accesspress_mag_ticker(); }
        ?>

        <div class="logo-ad-wrapper clearfix">
            <div class="apmag-container">
        		<div class="site-branding">
                    <div class="sitelogo-wrap">
                        <?php if ( get_header_image() ) { ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <img src="<?php header_image(); ?>" alt="<?php echo esc_attr( $accesspress_mag_logo_alt ); ?>" title="<?php echo esc_attr( $accesspress_mag_logo_title ); ?>" />
                        </a>
                        <?php } ?>
                        <meta itemprop="name" content="<?php bloginfo( 'name' )?>" />
                    </div><!-- .sitelogo-wrap -->
                    <div class="sitetext-wrap">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                        <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                        </a>
                    </div><!-- .sitetext-wrap -->
                 </div><!-- .site-branding -->

                <?php if ( is_active_sidebar( 'accesspress-mag-header-ad' ) ) : ?>
                    <div class="header-ad">
                        <?php dynamic_sidebar( 'accesspress-mag-header-ad' ); ?>
                    </div><!--header ad-->
                <?php endif; ?>

            </div><!-- .apmag-container -->
        </div><!-- .logo-ad-wrapper -->

        <nav id="site-navigation" class="main-navigation">
			<div class="apmag-container">
                <div class="nav-wrapper">
                    <div class="nav-toggle hide">
                        <span> </span>
                        <span> </span>
                        <span> </span>
                    </div>
        			<?php
                        if( has_nav_menu( 'primary' ) ){
                            wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu' ) );
                        } else {
                            wp_page_menu();
                        }
                    ?>
                </div><!-- .nav-wrapper -->
                <?php
                    get_search_form();
                    if ( $accesspress_mag_random_icon == 1 ) { accesspress_mag_random_post(); }
                ?>
            </div><!-- .apmag-container -->
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->
    <?php do_action( 'accesspress_mag_after_header' ); ?>
	<?php do_action( 'accesspress_mag_before_main' ); ?>
	<div id="content" class="site-content">