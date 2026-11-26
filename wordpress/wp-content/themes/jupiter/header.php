<!DOCTYPE html>
<html <?php echo language_attributes(); ?> >
<head>
	<?php wp_head(); ?>

	<!-- ADOBE ANALYTICS -->
			<script src="//static.vocstatic.com/voonto2/latest/voonto.js " data-cmp="true" data-gdpr-migrate="viewed_cookie_policy"></script>
			<script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="7d7c0790761bf4e65ff128b37ff7d599996ca047"></script>
			<script>TagManager.ready.then(function() {TagManager.set({}).track();});</script>
			<!-- FIN ADOBE ANALYTICS -->

</head>

<body <?php body_class( mk_get_body_class( global_get_post_id() ) ); ?> <?php echo get_schema_markup( 'body' ); ?> data-adminbar="<?php echo is_admin_bar_showing(); ?>">


<!-- Start vocento DTM B -->
			<noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocelcorreoeventos,voces/1/G.4--S/838383838?pageName=&g=<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI]; ?>" width="1" height="1" border="0" /></noscript>
			<!-- End vocento DTM B --> 	

	<?php
		// Hook when you need to add content right after body opening tag. to be used in child themes or customisations.
		do_action( 'theme_after_body_tag_start' );
	?>

	<!-- Target for scroll anchors to achieve native browser bahaviour + possible enhancements like smooth scrolling -->
	<div id="top-of-page"></div>

		<div id="mk-boxed-layout">

			<div id="mk-theme-container" <?php echo is_header_transparent( 'class="trans-header"' ); ?>>

				<?php
				mk_get_header_view( 'styles', 'header-' . get_header_style() );
