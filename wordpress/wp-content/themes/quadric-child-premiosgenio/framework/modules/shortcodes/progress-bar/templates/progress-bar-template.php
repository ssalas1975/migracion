<div class="edgtf-progress-bar">
	<<?php echo esc_attr($title_tag);?> class="edgtf-progress-title-holder clearfix">
		<span class="edgtf-progress-title" <?php edgtf_quadric_inline_style($title_style); ?>><?php echo esc_attr($title)?></span>
		<span class="edgtf-progress-number-wrapper <?php echo esc_attr($percentage_classes)?> " >
			<span class="edgtf-progress-number" <?php edgtf_quadric_inline_style($percent_style); ?>>
				<span class="edgtf-percent">0</span>
				<?php if($floating_type == 'floating_outside' ){ ?>
				<?php }?>
			</span>
		</span>
	</<?php echo esc_attr($title_tag)?>>
	<div class="edgtf-progress-content-outer" <?php edgtf_quadric_inline_style($bar_style); ?>>
		<div data-percentage=<?php echo esc_attr($percent)?> class="edgtf-progress-content" <?php edgtf_quadric_inline_style($active_background); ?>></div>
	</div>
</div>	