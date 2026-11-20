<?php
/**
 * Header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">

	<?php if (jevelin_option('responsive_layout')) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php endif; ?>

	<?php
    /* Callback for older WordPress versions */
    if (!function_exists('has_site_icon') || ! has_site_icon()) : ?>
		<?php if (jevelin_option_image('favicon')) : ?>
			<link rel="icon" type="image/png" href="<?php echo jevelin_option_image('favicon'); ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php wp_head(); ?>

	<!-- Start vocento VTM H --> 
    <!-- <script src="//static.vocstatic.com/voonto2/latest/voonto.js"></script>
    <script src="//static.vocstatic.com/tag-manager/latest/vtm.js"
    data-legacy-comscore="7715761"
    data-analytics-id="ppoo_<?php //echo mb_strtolower($_ENV["ENVIRON"]); ?>"></script> -->


    <style type="text/css"> a#CybotCookiebotDialogPoweredbyCybot, div#CybotCookiebotDialogPoweredByText, div#CybotCookiebotDialogDetailFooter { display: none; } </style> 

    <!-- <script>TagManager.ready.then(function(){TagManager.track();});</script> -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-K269P5');</script>
    <!-- End vocento VTM H -->

    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="0d6d982c-e8be-4462-bbd6-267879ec8a81" data-blockingmode="auto" type="text/javascript"></script>
<script id="CookieDeclaration" src="https://consent.cookiebot.com/0d6d982c-e8be-4462-bbd6-267879ec8a81/cd.js" type="text/javascript" async></script>


</head>
<body <?php body_class(); ?>>

<!-- Start vocento dtm+gtm+comscore -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K269P5" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<noscript><img src="//sadbmetrics.forobeready.es/b/ss/vocvocentoeventos,voces/1/G.4--S/1111112?pageName=&g=/js-noactive&v55=vocentoeventos&c56=vocentoeventos" width = "1" height = "1" border="0" /></noscript>
<noscript><img src="//sb.scorecardresearch.com/p?c1=2&amp;c2=7715761&amp" height="1" width="1" alt="*" /></noscript>
<!-- End vocento dtm+gtm+comscore -->


<?php
    /* Get Titlebar options */
    $titlebar_style_val = jevelin_post_option(jevelin_page_id(), 'header_style');
    $titlebar_style_val2 = (isset($titlebar_style_val['header_style'])) ? esc_attr($titlebar_style_val['header_style']) : 'default';

    /* Inlcude page loader HTML */
    get_template_part('inc/templates/page_loader');

    /* Header popup search */
    if (jevelin_option('header_search_style', 'style1') != 'style1') :
        get_template_part('inc/headers/header-search');
    endif;
?>

	<?php
        if (jevelin_header_right()) :
            /* Inlcude right header HTML */
            get_template_part('inc/headers/header-right-1');
        endif;
    ?>

	<div id="page-container" class="<?php echo jevelin_header_page_container(); ?>">
		<?php
            /* Inlcude page notice HTML */
            get_template_part('inc/templates/notice');

            /* Inlcude breadcrumbs HTML, if bottom titlebar style */
            if ($titlebar_style_val2 == 'bottom_titlebar') :
                //get_template_part('inc/templates/titlebar' );
            endif;
        ?>


		<?php if (jevelin_post_option(jevelin_page_id(), 'header', 'on') != 'off') : ?>
			<header class="primary-mobile<?php echo jevelin_header_mobile_style(); ?>">
				<?php /* Inlcude mobile header */
                    get_template_part('inc/headers/header-mobile');
                ?>
			</header>
			<header class="primary-desktop<?php echo jevelin_header_desktop_style(); ?>">
				<?php /* Inlcude desktop header */
                    get_template_part('inc/headers/header-'.jevelin_header_layout());
                ?>
			</header>
		<?php endif; ?>


		<?php
            /* Inlcude breadcrumbs HTML, if not bottom titlebar style */
            if ($titlebar_style_val2 != 'bottom_titlebar') :
                //get_template_part('inc/templates/titlebar');
            endif;

            $class = '';
            if (jevelin_post_option(jevelin_page_id(), 'page_layout', 'default') != 'full') {
                $class = ' sh-page-layout-default';
            } elseif (jevelin_post_option(jevelin_page_id(), 'page_layout', 'default') == 'full') {
                $class = ' sh-page-layout-full';
            }

            if (jevelin_post_option(jevelin_page_id(), 'page_layout', 'default') == 'default' ||
               (jevelin_post_option(jevelin_page_id(), 'page_layout', 'default') == 'global_default' && jevelin_option('global_page_layout') == 'default')) :
                $post = get_post();
                if ($post && preg_match('/vc_row/', $post->post_content)) :
                    $class = '';
                endif;
            endif;
        ?>


        <?php /* Unyson Builder Empty Space Preview */
        if (current_user_can('administrator') &&
        function_exists('fw_ext_page_builder_is_builder_post') && fw_ext_page_builder_is_builder_post(jevelin_page_id())) : ?>
            <span class="sh-unyson-frontend-test" style="opacity: 0;" title="Toogle page builder empty spaces visually">
                <i class="icon icon-eye"></i>
            </span>
	    <?php endif; ?>


			<div id="wrapper">
				<?php /* Option to show blog page editor content above blog */
                wp_reset_postdata();
                if (jevelin_post_option(jevelin_page_id(), 'page_blog_editor', 'off') == 'on') : ?>
					<?php if (function_exists('fw_ext_page_builder_is_builder_post') && fw_ext_page_builder_is_builder_post(jevelin_page_id())) : ?>
						<div class="blog-editor-content-container blog-fw-editor-content-container">
							<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
						</div>
					<?php elseif (get_the_content(jevelin_page_id())) : ?>
						<div class="blog-editor-content-container">
							<div class="container">
								<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>


				<div class="content-container<?php echo esc_attr($class); ?>">
				<?php if (jevelin_post_option(jevelin_page_id(), 'page_layout', 'default') != 'full') : ?>
					<div class="container entry-content">
				<?php endif; ?>


				<?php
                    /* Inlcude white borders option HTML */
                    get_template_part('inc/templates/white_borders');
                ?>
