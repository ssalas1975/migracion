<div class="edgtf-pie-chart-with-icon-holder">
	<div class="edgtf-percentage-with-icon" <?php echo edgtf_quadric_get_inline_attrs($pie_chart_data); ?> <?php edgtf_quadric_inline_style($pie_chart_canvas_style); ?>>
		<?php print $icon; ?>
	</div>
	<div class="edgtf-pie-chart-text" <?php edgtf_quadric_inline_style($pie_chart_style)?>>
		<p><?php echo esc_html($text); ?></p>		
		<<?php echo esc_html($title_tag)?> class="edgtf-pie-title">
			<?php echo esc_html($title); ?>
		</<?php echo esc_html($title_tag)?>>
	</div>
</div>