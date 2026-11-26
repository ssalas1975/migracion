<?php
$logo = jevelin_option_image('logo');
if( !$logo ) :
	$logo = get_template_directory_uri().'/img/logo.png';
endif;
?>
<header id="top" class="amp-wp-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
	<div class="amp-wp-header-logo">
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?>" />
			<span class="amp-site-title">
				<?php echo esc_html( wptexturize( $this->get( 'blog_name' ) ) ); ?>
			</span>
		</a>
	</div>
</header>
