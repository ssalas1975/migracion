<?php $sidebar = any_edge_sidebar_layout(); ?>
<?php get_header(); ?>
<?php 

$blog_page_range = any_edge_get_blog_page_range();
$max_number_of_pages = any_edge_get_max_number_of_pages();

if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }
?>
<?php any_edge_get_title(); ?>
	<div class="edgtf-container">
		<?php do_action('any_edge_after_container_open'); ?>
		<div class="edgtf-container-inner clearfix">
			<div class="edgtf-container">
				<?php do_action('any_edge_after_container_open'); ?>
				<div class="edgtf-container-inner" >
					<div class="edgtf-blog-holder edgtf-blog-type-standard edgtf-search-page">
				<?php if(have_posts()) : while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="edgtf-post-content">
							<div class="edgtf-post-text">
								<div class="edgtf-post-text-inner">
									<h2>
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
									</h2>
									<?php
										any_edge_read_more_button();
									?>
								</div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>
					<?php
						if(any_edge_options()->getOptionValue('pagination') == 'yes') {
							any_edge_pagination($max_number_of_pages, $blog_page_range, $paged);
						}
					?>
					<?php else: ?>
					<div class="entry">
						<p><?php esc_html_e('No posts were found.', 'any'); ?></p>
					</div>
					<?php endif; ?>
				</div>
				<?php do_action('any_edge_before_container_close'); ?>
			</div>
			</div>
		</div>
		<?php do_action('any_edge_before_container_close'); ?>
	</div>
	<?php do_action('any_edge_after_container_close'); ?>
<?php get_footer(); ?>