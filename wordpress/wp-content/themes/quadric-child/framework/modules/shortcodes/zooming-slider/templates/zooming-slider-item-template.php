<?php
$icon_html = edgtf_quadric_icon_collections()->renderIcon($icon, $icon_pack, $params);
?>
<div class="edgtf-zooming-slider-slide" <?php echo edgtf_quadric_get_inline_style($background_image); ?>>
	<div class="edgtf-zooming-slider-shader" <?php echo edgtf_quadric_get_inline_style($background_color); ?>></div>
	<div class="edgtf-zooming-slider-slide-inner">
		<div class='edgtf-zooming-slider-icon-holder'><?php print $icon_html; ?></div>
		<?php echo do_shortcode($content); ?>
	</div>
</div>