<?php $sidebar = edgtf_quadric_sidebar_layout(); ?>
<?php get_header(); ?>
<?php get_template_part( 'title' ); ?>
<?php get_template_part('slider'); ?>
	<div class="edgtf-container">
		<?php do_action('edgtf_quadric_after_container_open'); ?>
		<div class="edgtf-container-inner clearfix">
			<div class="edgtf-container">
				<?php do_action('edgtf_quadric_after_container_open'); ?>
				<div class="edgtf-container-inner" >
					<div class="edgtf-blog-holder edgtf-blog-type-standard">
				<?php if(have_posts()) : while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="edgtf-post-content">
							<div class="edgtf-post-text">
								<div class="edgtf-post-text-inner">
									<h2>
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
									</h2>
									<?php
										edgtf_quadric_read_more_button();
									?>
								</div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>
					<?php
						if(edgtf_quadric_options()->getOptionValue('pagination') == 'yes') {
							edgtf_quadric_pagination();
						}
					?>
					<?php else: ?>
					<div class="entry">
						<?php 
							print do_shortcode('[edgtf_number number="Search" position="left" font_size="110" font_family="Raleway" font_weight="700" letter_spacing="1"]');
							print do_shortcode('[edgtf_separator type="transparent" top_margin="-45" bottom_margin="0"]');				
						?>
						<h2><?php esc_html_e('No posts were found.', 'quadric'); ?></h2>
					</div>
					<?php endif; ?>
				</div>
				<?php do_action('edgtf_quadric_before_container_close'); ?>
			</div>
			</div>
		</div>
		<?php do_action('edgtf_quadric_before_container_close'); ?>
	</div>
<?php get_footer(); ?>