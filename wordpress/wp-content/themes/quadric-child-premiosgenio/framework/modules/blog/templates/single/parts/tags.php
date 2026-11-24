<?php if(edgtf_quadric_options()->getOptionValue('blog_single_show_tags') == 'yes') { ?>
	<div class="edgtf-single-tags-holder">
		<h6 class="edgtf-single-tags-title"><?php esc_html_e('Post Tags:', 'quadric'); ?></h6>
		<div class="edgtf-tags">
			<?php the_tags('', '', ''); ?>
		</div>
	</div>
<?php } ?>