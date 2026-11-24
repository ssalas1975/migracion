<div class="edgtf-elements-holder-item <?php echo esc_attr($elements_holder_item_class); ?> <?php echo esc_attr($elements_holder_item_content_class); ?>" <?php echo edgtf_quadric_get_inline_style($elements_holder_item_style); ?>>
	<div class="edgtf-elements-holder-item-inner">
		<div class="edgtf-elements-holder-item-content" <?php echo edgtf_quadric_get_inline_style($elements_holder_item_content_style); ?>>
			<?php if(count($elements_holder_item_content_responsive) > 0 || $background_hover_color !== ''){ ?>
			<style type="text/css" data-type="edgtf-elements-custom-padding" scoped>
				<?php if($background_hover_color !== ''){ ?>
					.edgtf-elements-holder-item.<?php echo esc_attr($elements_holder_item_content_class); ?>:hover{
						background-color: <?php echo esc_attr($background_hover_color); ?> !important;
					}
				<?php } ?>
				<?php if(count($elements_holder_item_content_responsive) > 0){ ?>

				<?php if(isset($elements_holder_item_content_responsive['item_padding_1280_1366']) && $elements_holder_item_content_responsive['item_padding_1280_1366'] != ''){ ?>
					@media only screen and (min-width: 1280px) and (max-width: 1366px) {
						.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_1280_1366']); ?> !important;
						}
					}
				<?php } ?>

				<?php if(isset($elements_holder_item_content_responsive['item_padding_1024_1280']) && $elements_holder_item_content_responsive['item_padding_1024_1280'] != ''){ ?>
					@media only screen and (min-width: 1024px) and (max-width: 1280px) {
						.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
							padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_1024_1280']); ?> !important;
						}
					}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_768_1024']) && $elements_holder_item_content_responsive['item_padding_768_1024'] != ''){ ?>
				@media only screen and (min-width: 768px) and (max-width: 1024px) {
					.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
						padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_768_1024']); ?> !important;
					}
				}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_600_768']) && $elements_holder_item_content_responsive['item_padding_600_768'] != ''){ ?>
				@media only screen and (min-width: 600px) and (max-width: 768px) {
					.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
						padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_600_768']); ?> !important;
					}
				}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_480_600']) && $elements_holder_item_content_responsive['item_padding_480_600'] != ''){ ?>
				@media only screen and (min-width: 480px) and (max-width: 600px) {
					.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
						padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_480_600']); ?> !important;
					}
				}
				<?php } ?>
				<?php if(isset($elements_holder_item_content_responsive['item_padding_480']) && $elements_holder_item_content_responsive['item_padding_480'] != ''){ ?>
				@media only screen and (max-width: 480px) {
					.edgtf-elements-holder .<?php echo esc_attr($elements_holder_item_content_class); ?> .edgtf-elements-holder-item-content {
						padding: <?php echo esc_attr($elements_holder_item_content_responsive['item_padding_480']); ?> !important;
					}
				}
				<?php } ?>
				<?php } ?>
				</style>
			<?php } ?>
			<?php echo do_shortcode($content); ?>
		</div>
	</div>
</div>