<div class="edgtf-svg-icons-text-holder <?php echo esc_attr($icons_class); ?>">
	<div class="edgtf-svg-icons edgtf-svg-frames-class" <?php echo edgtf_quadric_get_inline_attrs($icons_data); ?> >
		<?php echo edgtf_quadric_get_shortcode_module_template_part('templates/svg-'. $icon, 'svg-icons', '',array('color'=> 'stroke:' .$color)); ?>
	</div>
	<?php if($text != '') { ?>
		<div class="edgtf-svg-text">
			<<?php echo esc_attr($title_tag); ?> <?php echo edgtf_quadric_get_inline_style($text_style); ?>>
				<?php echo esc_attr($text); ?>
			</<?php echo esc_attr($title_tag); ?>>
		</div>
	<?php } ?>
</div>
