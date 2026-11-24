<div class="edgtf-price-table <?php echo implode(' ', $pricing_table_classes);?>">
	<div class="edgtf-price-table-inner">
		<?php if($active == 'yes'){ ?>
			<div class="edgtf-active-text">
				<span class="edgtf-active-text-inner">
					<?php echo esc_attr($active_text) ?>
				</span>
			</div>
		<?php } ?>
		<ul>
			<li class="edgtf-table-title" <?php edgtf_quadric_inline_style($title_holder_style); ?>>
				<span class="edgtf-title-content" <?php edgtf_quadric_inline_style($title_style); ?>><?php echo esc_attr($title) ?></span>
			</li>
			<li class="edgtf-table-prices">
				<div class="edgtf-price-in-table">
					<sup class="edgtf-value"><?php echo esc_attr($currency) ?></sup>
					<span class="edgtf-price"><?php echo esc_attr($price)?></span>
					<span class="edgtf-mark"><?php echo esc_attr($price_period)?></span>
				</div>	
			</li>
			<li class="edgtf-table-content" <?php edgtf_quadric_inline_style($content_holder_style); ?>>
				<?php echo do_shortcode($content)?>
			</li>
			<?php 
			if($show_button == "yes" && $button_text !== ''){ ?>
				<li class="edgtf-price-button">
					<a href="<?php echo esc_url($link); ?>">
						<?php echo esc_attr($button_text); ?>
					</a>
				</li>				
			<?php } ?>
		</ul>
	</div>
</div>
