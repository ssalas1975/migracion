<div class="edgtf-elements-holder-item <?php echo esc_attr($elements_holder_item_class); ?>" <?php echo any_edge_get_inline_attrs($elements_holder_item_data); ?> <?php echo any_edge_get_inline_style($elements_holder_item_style); ?>>
	<?php if ($clickable == 'yes') { ?>
		<a class="edgtf-eh-item-link" href="<?php echo esc_url($link) ?>" target="<?php echo esc_attr($target) ?>"></a>
	<?php } ?>
	<div class="edgtf-elements-holder-item-inner">
		<div class="edgtf-elements-holder-item-content <?php echo esc_attr($elements_holder_item_content_class); ?>" <?php echo any_edge_get_inline_style($elements_holder_item_content_style); ?>>
			<?php if(count($elements_holder_item_content_responsive) > 0){ ?>
			<style type="text/css" data-type="edgtf-elements-custom-padding" scoped>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_1280_1440']) && $elements_holder_item_content_responsive['item_padding_1280_1440'] !== ''){ ?>
					@media only screen and (min-width: 1280px) and (max-width: 1440px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_1280_1440']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_1024_1280']) && $elements_holder_item_content_responsive['item_padding_1024_1280'] !== ''){ ?>
					@media only screen and (min-width: 1024px) and (max-width: 1280px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_1024_1280']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_768_1024']) && $elements_holder_item_content_responsive['item_padding_768_1024'] !== ''){ ?>
					@media only screen and (min-width: 768px) and (max-width: 1024px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_768_1024']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_600_768']) && $elements_holder_item_content_responsive['item_padding_600_768'] !== ''){ ?>
					@media only screen and (min-width: 600px) and (max-width: 768px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_600_768']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_480_600']) && $elements_holder_item_content_responsive['item_padding_480_600'] !== ''){ ?>
					@media only screen and (min-width: 480px) and (max-width: 600px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_480_600']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_480']) && $elements_holder_item_content_responsive['item_padding_480'] !== ''){ ?>
					@media only screen and (max-width: 480px) {
						.edgtf-elements-holder-item-content.<?php echo esc_attr($elements_holder_item_content_class); ?> {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_480']); ?> !important;
						}
					}
				<?php } ?>
				</style>
			<?php } ?>
			<?php echo do_shortcode($content); ?>
		</div>
	</div>
	<?php if ($zoom_effect == 'yes') { ?>
		<div class="edgtf-elements-holder-item-image-zoom" <?php echo any_edge_get_inline_style($background_image_div_styles) ?>>
		</div>
	<?php } ?>
</div>