
<article class="edgtf-portfolio-item mix <?php echo esc_attr($categories)?>">
	<div class="edgtf-portfolio-item-inner-holder">
		<div class = "edgtf-item-image-holder">	
			<?php
			global $wpdb;
			$siteId = get_current_blog_id();
			$tableName = 'wp_'.$siteId.'_postmeta';

			$result = $wpdb->get_results('SELECT * FROM '.$tableName.' WHERE post_id='.get_the_ID().' AND meta_key="edgt_portfolio_images"');

			if(sizeof($result)==0) :
			?>

			<div class='edgtf-portfolio-item-overlay'></div>

			<?php endif; ?>

			<?php if(sizeof($result)!=0) : ?>
				
			<div class="edgtf-blog-gallery portfolio-owl edgtf-owl-slider">

				<?php
				$metavalue = $result[0]->meta_value;
				$metavalueArray = unserialize($metavalue);
				
				foreach($metavalueArray as $metavalueItem) :
				?>

				<div>
					<a href="<?php echo esc_url($item_link) ?>"><img src="<?php echo $metavalueItem['portfolioimg']; ?>" class="attachment-full" alt="a" /></a>
				</div>

				<?php
				endforeach;
				?>

			</div>


			<?php else: ?>
			<a href='<?php echo esc_url($item_link) ?>'>
				<?php
				echo get_the_post_thumbnail(get_the_ID(),$thumb_size);
				?>
			</a>
			<?php endif; ?>
					
			<?php
			if(sizeof($result)==0) :
				print $icon_html;
			endif;
			?>
		</div>
		<div class="edgtf-item-text-holder">
			<<?php echo esc_attr($title_tag)?> class="edgtf-item-title">
				<a href="<?php echo esc_url($item_link) ?>">
					<?php echo esc_attr(get_the_title()); ?>
				</a>	
			</<?php echo esc_attr($title_tag)?>>
			<div class="edgtf-ptf-content-holder">
				<?php echo esc_attr(get_the_content()); ?>
			</div>
		</div>
	</div>
</article>
