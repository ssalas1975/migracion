<div class="edgtf-curtain-holder <?php echo esc_attr($holder_class); ?>" <?php echo edgtf_quadric_get_inline_style($holder_style); ?>>
	<div class="edgtf-curtains">
		<div class="edgtf-curtain edgtf-curtain-top <?php echo esc_attr($curtain_class); ?>" <?php echo edgtf_quadric_get_inline_style($curtain_style); ?>></div>
		<div class="edgtf-curtain edgtf-curtain-bottom <?php echo esc_attr($curtain_class); ?>" <?php echo edgtf_quadric_get_inline_style($curtain_style); ?>></div>
		<div class="edgtf-curtain edgtf-curtain-left <?php echo esc_attr($curtain_class); ?>" <?php echo edgtf_quadric_get_inline_style($curtain_style); ?>></div>
		<div class="edgtf-curtain edgtf-curtain-right <?php echo esc_attr($curtain_class); ?>" <?php echo edgtf_quadric_get_inline_style($curtain_style); ?>></div>
	</div>
	<div class="edgtf-curtain-holder-content" <?php echo edgtf_quadric_get_inline_style($content_style); ?>><?php echo do_shortcode($content) ?></div>
</div>
