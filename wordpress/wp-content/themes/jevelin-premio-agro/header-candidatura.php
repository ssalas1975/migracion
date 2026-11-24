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

	<?php
        $currentBlogId = get_current_blog_id();
        // if ($currentBlogId == 10 || $currentBlogId == 12) {
        //     echo '
		// 	<script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-gdpr-migrate="cookie_layer"></script>
		// 	<script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-self-hosted="false" data-property-id="6f5e794197143d0967d6b4da6d90751edbf93ed6" data-analytics-id="ppvv_pro" data-legacy-adbd="true" data-legacy-krux="q8cvcf01d" data-legacy-comscore="7715761" data-legacy-chartbeat="mujerhoy.com"></script> 
		// 	<script>(function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src="https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);})(window,document,"script","dataLayer","GTM-KD27TC");</script>
		// 	<script>TagManager.ready.then(function(){TagManager.track();});</script>
		// 	';
        // }
    ?>

</head>
<body <?php body_class(); ?>>

<?php
    $currentBlogId = get_current_blog_id();
    if ($currentBlogId == 10 || $currentBlogId == 12) {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo '
		<script src="//static.vocstatic.com/dab/latest/strategy.js"></script>
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KD27TC" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<noscript><p><img src="https://sb.scorecardresearch.com/p?c1=2&amp;c2=7715761&amp;ns_site=mujerhoy" height="1" width="1" alt="*" style="height:1px;"></p></noscript>
		<noscript><img src="https://vocento.d3.sc.omtrdc.net/b/ss/vocgofamily,voces/1/G.4--NS/1231231?pageName=&g=/without-js" width="1" height="1" border="0" /></noscript>
		';
    }
?>

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
                get_template_part('inc/templates/titlebar');
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

		<!--<section class="sh-section sh-section-6ea2b3d048179f79a0282912c0fa86e5 fw-main-row sh-section-visibility-everywhere">
			<div class="sh-section-container container-fluid">
				<div class="fw-row">
					<div class="sh-column sh-column-fae4f5b42f3144053702f81ce860aa97 fw-col-xs-12">
						<div class="sh-column-wrapper">
							<div id="single-image-21771c4764d7c4dac0e0c0bc800ef14a" class="sh-single-image">
								<div class="sh-single-image-container">
									<img class="sh-image-url" src="/wp-content/uploads/sites/15/2020/06/DG_Interior.png" alt="dg_interior">
								</div>
							</div>
							<div class="sh-heading" id="heading-318289fd0315213e5317295af10a070a">
								<h1 class="sh-heading-content size-default text-center">CANDIDATURAS JURADO</h1>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>-->

		<section class="sh-section sh-section-6ea2b3d048179f79a0282912c0fa86e5 fw-main-row sh-section-visibility-everywhere">
			<div class="sh-section-container container-fluid">
				<div class="fw-row">
					<div class="sh-column sh-column-fae4f5b42f3144053702f81ce860aa97 fw-col-xs-12 fw-col-sm-2">
						<div class="sh-column-wrapper">
						</div>
					</div>
					<div class="sh-column sh-column-947593f5acdeca50f68bddef2a0245eb fw-col-xs-12 fw-col-sm-8 sh-column-mobile-padding">
						<div class="sh-column-wrapper">
							<div id="single-image-21771c4764d7c4dac0e0c0bc800ef14a" class="sh-single-image">
								<div class="sh-single-image-container">
									<img class="sh-image-url" src="/wp-content/uploads/sites/15/2023/10/cab-candidaturas.png" alt="aperturas-candidaturas-ive">
								</div>
							</div>
						</div>
					</div>
					<div class="sh-column sh-column-fe0b7a9e5c8e87c767b9547621960c70 fw-col-xs-12 fw-col-sm-2">
						<div class="sh-column-wrapper">
						</div>
					</div>
				</div>
			</div>
		</section>


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
