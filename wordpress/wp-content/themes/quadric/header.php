<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php edgtf_quadric_wp_title(); ?>
    <?php
    /**
     * @see edgt_quadric_header_meta() - hooked with 10
     * @see edgt_user_scalable - hooked with 10
     */
    ?>
	<?php do_action('edgtf_quadric_header_meta'); ?>

	<?php wp_head(); ?>

    <!-- ADOBE ANALYTICS -->
<script src="//static.vocstatic.com/voonto2/latest/voonto.js " data-cmp="true"></script>
<script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="4f979df643fa11b494d7ca20407fccd7b46fd491"></script>
<script>TagManager.ready.then(function() {TagManager.set({}).track();});</script>
<!-- FIN ADOBE ANALYTICS -->
</head>

<body <?php body_class(); ?>>
<?php edgtf_quadric_get_side_area(); ?>
<div class="edgtf-wrapper">
    <div class="edgtf-wrapper-inner">
        <?php edgtf_quadric_get_header(); ?>

        <?php if(edgtf_quadric_options()->getOptionValue('show_back_button') == "yes") { ?>
            <a id='edgtf-back-to-top'  href='#'>
                <span class="edgtf-icon-stack">
                     <?php
                        edgtf_quadric_icon_collections()->getBackToTopIcon('font_elegant');
                    ?>
                </span>
				<span class="edgtf-back-to-top-icon-hover">
						 <?php edgtf_quadric_icon_collections()->getBackToTopIcon('font_elegant'); ?>
				</span>
            </a>
        <?php } ?>

        <div class="edgtf-content" <?php edgtf_quadric_content_elem_style_attr(); ?>>
            <div class="edgtf-content-inner">

            <!-- Start vocento DTM B -->
<noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocvocento,voces/1/G.4--S/838777838?pageName=&g=<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" width="1" height="1" border="0" /></noscript>
<!-- End vocento DTM B --> 	