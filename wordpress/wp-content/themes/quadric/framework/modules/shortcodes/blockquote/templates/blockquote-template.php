<?php
/**
 * Blockquote shortcode template
 */
?>

<blockquote class="edgtf-blockquote-shortcode" <?php edgtf_quadric_inline_style($blockquote_style); ?> >
	<span class="edgtf-icon-quotations-holder">
		<img src="<?php echo EDGE_ASSETS_ROOT . '/css/img/blockquote-icon.png'  ?>" />
	</span>
	<<?php echo esc_attr($blockquote_title_tag); ?> class="edgtf-blockquote-text">
	<span><?php echo esc_attr($text); ?></span>
	</ <?php echo esc_attr($blockquote_title_tag);?>>
</blockquote>