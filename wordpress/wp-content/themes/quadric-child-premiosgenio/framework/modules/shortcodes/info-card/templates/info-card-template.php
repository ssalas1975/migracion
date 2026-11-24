<?php
/**
 * InfoCard shortcode template
 */
?>

<div class="edgtf-info-card-holder">
	<div class="edgtf-info-shader">
		<div class="edgtf-image-1" <?php edgtf_quadric_inline_style($infocard_initial_image); ?>></div>
		<div class="edgtf-image-2" <?php edgtf_quadric_inline_style($hover); ?>></div>
	</div>
	<a href="<?php echo (esc_attr($link))?>" target="_blank"></a>
	<div class="edgtf-info-card-holder-inner">
		<div class="edgtf-info-card-icon-holder">
			<?php print ($icon); ?>
		</div>
		<div class="edgtf-info-card-title-holder">
			<h3 <?php edgtf_quadric_inline_style($title_style); ?>><?php echo (esc_html($title))?></h3>
		</div>
		<div class="edgtf-info-card-text-holder">
			<p <?php edgtf_quadric_inline_style($text_style); ?>><?php echo (esc_html($text))?></p>
		</div>
	</div>
</div>


