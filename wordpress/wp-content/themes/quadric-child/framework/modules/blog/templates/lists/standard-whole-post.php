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
			edgtf_quadric_pagination($blog_query->max_num_pages, $blog_page_range, $paged);
		}
	?>
</div>
