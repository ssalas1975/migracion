
<article class="edgtf-portfolio-item <?php echo esc_attr($article_masonry_size)?> <?php echo esc_attr($categories)?>">
	<a class ="edgtf-portfolio-link" href="<?php echo esc_url($item_link) ?>"></a>
	<div class = "edgtf-item-image-holder">
	<?php
		echo get_the_post_thumbnail(get_the_ID(),$thumb_size);
	?>				
	</div>
	<div class="edgtf-item-text-overlay">
		<div class="edgtf-item-text-overlay-inner">
			<div class="edgtf-item-text-holder">				
					<<?php echo esc_attr($title_tag)?> class="edgtf-item-title">
					<a href="<?php echo esc_url($item_link) ?>">
						<?php echo esc_attr(get_the_title()); ?>
					</a>
					</<?php echo esc_attr($title_tag)?>>				
				<?php 
				echo do_shortcode('[edgtf_zig_zag_separator top_margin="5" bottom_margin="6" animated="no" position="center"]');								
				print $category_html;
				?>
			</div>
		</div>	
	</div>
</article>
