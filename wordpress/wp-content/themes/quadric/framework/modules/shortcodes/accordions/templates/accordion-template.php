<?php

?>
<<?php echo esc_attr($title_tag)?> class="clearfix edgtf-title-holder <?php echo esc_attr($accordion_class); ?>">
	<?php
	if($icon != ''){
		$icon_html = edgtf_quadric_icon_collections()->renderIcon($icon, $icon_pack);
		?>
		<span class="edgtf-accordion-icon">
			<?php print $icon_html; ?>
		</span>
	<?php
		}
	?>
	<span class="edgtf-tab-title">
		<span class="edgtf-tab-title-inner">
			<?php echo esc_attr($title)?>
		</span>
	</span>
	<span class="edgtf-accordion-mark">
		<span class="edgtf-accordion-mark-icon">
			<span class="icon_plus"></span>
			<span class="icon_minus-06"></span>
		</span>
	</span>
</<?php echo esc_attr($title_tag)?>>
<div class="edgtf-accordion-content">
	<div class="edgtf-accordion-content-inner">
		<?php echo do_shortcode($content)?>
	</div>
</div>
