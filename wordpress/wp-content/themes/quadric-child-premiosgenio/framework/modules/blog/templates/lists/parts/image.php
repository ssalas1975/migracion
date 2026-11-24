<?php if ( has_post_thumbnail() ) { ?>
	<div class="edgtf-post-image">
		<?php if(get_post_format() == 'standard' || get_post_format() == 'audio' || get_post_format() == '') {?>
			<?php edgtf_quadric_get_module_template_part('templates/lists/parts/hover', 'blog'); ?>
		<?php } ?>
		<?php edgtf_quadric_get_module_template_part('templates/parts/top-date', 'blog'); ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php the_post_thumbnail('full'); ?>
		</a>
	</div>
<?php } ?>