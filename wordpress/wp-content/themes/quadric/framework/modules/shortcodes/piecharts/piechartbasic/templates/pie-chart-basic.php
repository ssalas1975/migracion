<?php
/**
 * Pie Chart Basic Shortcode Template
 */
?>
<div class="edgtf-pie-chart-holder">
	<div class="edgtf-percentage" <?php echo edgtf_quadric_get_inline_attrs($pie_chart_data); ?> <?php edgtf_quadric_inline_style($pie_chart_canvas_style); ?>>
		<?php if ($type_of_central_text == "title") { ?>
			<<?php echo esc_attr($title_tag); ?> class="edgtf-pie-title">
				<?php echo esc_html($title); ?>
			</<?php echo esc_attr($title_tag); ?>>
		<?php } else { ?>
			<span class="edgtf-to-counter" <?php edgtf_quadric_inline_style($pie_chart_percentage_style); ?>>
				<?php echo esc_html($percent ); ?>				
			</span>
			<span class='edgtf-percent-mark' <?php edgtf_quadric_inline_style($pie_chart_percentage_style); ?>>%</span>
		<?php } ?>
	</div>
	<div class="edgtf-pie-chart-text" <?php edgtf_quadric_inline_style($pie_chart_style); ?>>
		<?php if ($type_of_central_text == "title") { ?>
			<span class="edgtf-to-counter">
				<?php echo esc_html($percent ); ?>
			</span>
			<span class='edgtf-percent-mark'>%</span>
		<?php } else { ?>
			<p <?php edgtf_quadric_inline_style($text_styles); ?>><?php echo esc_html($text); ?></p>
			<<?php echo esc_attr($title_tag); ?> class="edgtf-pie-title" <?php edgtf_quadric_inline_style($title_styles); ?> >
				<?php echo esc_html($title); ?>
			</<?php echo esc_attr($title_tag); ?>>
		<?php } ?>
	</div>
</div>