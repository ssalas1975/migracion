<?php if ( has_post_thumbnail() ) { ?>
	<div class="edgtf-post-image">
		<?php edgtf_quadric_get_module_template_part('templates/parts/top-date', 'blog'); ?>
			<?php the_post_thumbnail('full'); ?>
	</div>
<?php } ?>