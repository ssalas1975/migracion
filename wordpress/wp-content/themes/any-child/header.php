<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php
    /**
     * @see any_edge_header_meta() - hooked with 10
     * @see edgt_user_scalable - hooked with 10
     */
    ?>
	<?php if (!any_edge_is_ajax_request()) {
        do_action('any_edge_header_meta');
    } ?>

	<?php if (!any_edge_is_ajax_request()) {
        wp_head();
    } ?>

    <script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true" data-cmp-position="bottom"></script>
    <script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-analytics-id="ppoo_pro" data-legacy-comscore="7715761"></script>

    <script>
    TagManager.ready.then(function() {
        TagManager.set({}).track();
        }   
    );
    </script>

    <!-- GTM -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];var f=d.getElementsByTagName(s)[0],    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-KCS4TJP');</script>
    <!-- GTM -->


</head>

<body <?php body_class();?>>
<?php if (!any_edge_is_ajax_request()) {
        any_edge_get_side_area();
    } ?>


<?php
if (any_edge_options()->getOptionValue('smooth_page_transitions') == "yes") {
        $ajax_class = 'edgtf-mimic-ajax'; ?>
<div class="edgtf-smooth-transition-loader <?php echo esc_attr($ajax_class); ?>">
    <div class="edgtf-st-loader">
        <div class="edgtf-st-loader1">
            <?php any_edge_loading_spinners(); ?>
        </div>
    </div>
</div>
<?php
    } ?>

<div class="edgtf-wrapper">
	<?php do_action('any_edge_after_wrapper_open'); ?>
    <div class="edgtf-wrapper-inner">
        <?php if (!any_edge_is_ajax_request()) {
        any_edge_get_header();
    } ?>

        <?php if ((!any_edge_is_ajax_request()) && any_edge_options()->getOptionValue('show_back_button') == "yes") { ?>
            <a id='edgtf-back-to-top'  href='#'>
                <span class="edgtf-icon-stack">
                     <?php
                        any_edge_icon_collections()->getBackToTopIcon('font_elegant');
                    ?>
                </span>
                <span class="edgtf-icon-stack">
                     <?php
                        any_edge_icon_collections()->getBackToTopIcon('font_elegant');
                    ?>
                </span>
            </a>
        <?php } ?>
        <?php if (!any_edge_is_ajax_request()) {
                        any_edge_get_full_screen_menu();
                    } ?>

        <div class="edgtf-content" <?php any_edge_content_elem_style_attr(); ?>>
            <?php if (any_edge_is_ajax_enabled()) { ?>
            <div class="edgtf-meta">
                <?php do_action('any_edge_ajax_meta'); ?>
                <span id="edgtf-page-id"><?php echo esc_html(get_queried_object_id()); ?></span>
                <div class="edgtf-body-classes"><?php echo esc_html(implode(',', get_body_class())); ?></div>
            </div>
            <?php } ?>
            <div class="edgtf-content-inner">