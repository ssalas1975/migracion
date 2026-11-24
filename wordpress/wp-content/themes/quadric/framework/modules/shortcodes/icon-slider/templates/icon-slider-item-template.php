<div class="edgtf-icon-slide">
	<div class="edgtf-icon-slide-container-inner">
			<div class="edgtf-slide-content" <?php edgtf_quadric_inline_style($style);?>>
				<?php echo do_shortcode($content); ?>
			</div>	
			<div class="edgtf-slide-image" <?php edgtf_quadric_inline_style($slide_image_responsive);?>>
				<img src='<?php echo esc_url($slide_image_src); ?>' alt='img'/>
			</div>				
	</div>
</div>