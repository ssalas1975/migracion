
<div class="edgtf-portfolio-related-holder">
	<h5><?php esc_html_e('Related Projects', 'quadric'); ?></h5>
	<div class="edgtf-portfolio-related clearfix">
		<?php
		$query = edgtf_quadric_get_related_post_type(get_the_ID(), array('posts_per_page' => '3'));
		if(is_object($query)) {
			if($query->have_posts()) : while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php if(has_post_thumbnail()){ ?>
			<article class="edgtf-portfolio-related-item" >
				<div class="edgtf-portfolio-item-inner-holder">		
					<div class = "edgtf-item-image-holder">
						<a class ="edgtf-portfolio-link" href="<?php echo esc_url(get_permalink()) ?>">
							<?php the_post_thumbnail('portfolio-landscape'); ?>
						</a>				
					</div>
					<div class="edgtf-item-text-overlay">
						<div class="edgtf-item-text-overlay-inner">
							<div class="edgtf-item-text-holder">
								<a class='edgtf-item-title' href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>	
							</div>
						</div>	
					</div>
				</div>
			</article>
			<?php } ?>
				<?php
			endwhile;
			endif;
		wp_reset_postdata();
		} else { ?>
			<p><?php _e('No related portfolios were found.', 'quadric'); ?></p>
		<?php	}
		?>
	</div>
</div>

