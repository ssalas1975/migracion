<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	
	$nectar_options = get_nectar_theme_options();
	
	nectar_meta_viewport();
	
	// Shortcut icon fallback.
	if ( ! empty( $nectar_options['favicon'] ) && ! empty( $nectar_options['favicon']['url'] ) ) {
		echo '<link rel="shortcut icon" href="'. esc_url( nectar_options_img( $nectar_options['favicon'] ) ) .'" />';
	}
	
	wp_head();
	
?>
<script src="//static.vocstatic.com/voonto2/latest/voonto.js" data-cmp="true"></script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MQCZTF4G');</script>
<!-- End Google Tag Manager -->


</head><?php

$nectar_header_options = nectar_get_header_variables();

?><body <?php body_class(); ?> <?php nectar_body_attributes(); ?>>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MQCZTF4G"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php
	
	nectar_hook_after_body_open();
	
	nectar_hook_before_header_nav();
	
	// Boxed theme option opening div.
	if ( $nectar_header_options['n_boxed_style'] ) {
		echo '<div id="boxed">';
	}
	
	get_template_part( 'includes/partials/header/header-space' );
	
	?>
	<div id="header-outer" <?php nectar_header_nav_attributes(); ?>>
		<?php
		
		get_template_part( 'includes/partials/header/secondary-navigation' );
		
		if ('ascend' !== $nectar_header_options['theme_skin'] && 
			  'left-header' !== $nectar_header_options['header_format']) {
			get_template_part( 'includes/header-search' );
		}
		
		get_template_part( 'includes/partials/header/header-menu' );
		
		
		?>
		
	</div>
	<?php
	
	if ( ! empty( $nectar_options['enable-cart'] ) && '1' === $nectar_options['enable-cart'] ) {
		get_template_part( 'includes/partials/header/woo-slide-in-cart' );
	}
	
	if ( 'ascend' === $nectar_header_options['theme_skin'] || 
		   'left-header' === $nectar_header_options['header_format'] && 
		   'false' !== $nectar_header_options['header_search'] ) {
		get_template_part( 'includes/header-search' ); 
	}
	
  get_template_part( 'includes/partials/footer/body-border' );
  
	?>
	<div id="ajax-content-wrap">
<?php
		
		nectar_hook_after_outer_wrap_open();
