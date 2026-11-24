<div class="edgtf-item-text-holder">
	<?php
		echo $category_html;
	?>

	<a class="edgtf-ptf-read-more" title="Go to Project" href="<?php echo esc_url($item_link); ?>" data-type="portfolio_list">
		<span class="arrow_right"></span>
	</a>

	<<?php echo esc_attr($title_tag)?> class="edgtf-item-title">
		<a class ="edgtf-portfolio-title-link" href="<?php echo esc_url($item_link); ?>">
			<?php echo esc_attr(get_the_title()); ?>
		</a>
	</<?php echo esc_attr($title_tag)?>>
</div>