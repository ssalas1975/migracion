<div class="edgtf-blog-holder edgtf-blog-type-standard">
	<?php
		if($blog_query->have_posts()) : while ( $blog_query->have_posts() ) : $blog_query->the_post();
			edgtf_quadric_get_post_format_html($blog_type);
		endwhile;
		else:
			edgtf_quadric_get_module_template_part('templates/parts/no-posts', 'blog');
		endif;
	?>
	<?php
		if(edgtf_quadric_options()->getOptionValue('pagination') == 'yes') {
			if (edgtf_quadric_options()->getOptionValue('standard_pagination_type') == 'previous-next') { ?>
				<div class="edgtf-prev-next-pagination">
					<?php if (get_previous_posts_link('', $blog_query->max_num_pages)) : ?>
						<span class="edgtf-prev-posts-link">
							<?php previous_posts_link(esc_html__('Newer Posts', 'quadric'), $blog_query->max_num_pages); ?>
						</span>
					<?php endif; ?>
					<?php if (get_next_posts_link('', $blog_query->max_num_pages)) : ?>
						<span class="edgtf-next-posts-link">
							<?php next_posts_link(esc_html__('Older Posts', 'quadric'), $blog_query->max_num_pages); ?>
						</span>
					<?php endif; ?>
				</div>
				<?php
			} else {
				edgtf_quadric_pagination($blog_query->max_num_pages, $blog_page_range, $paged);
			}
		}
	?>
</div>
