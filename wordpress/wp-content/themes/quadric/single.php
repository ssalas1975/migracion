<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<?php get_template_part( 'title' ); ?>
<?php get_template_part('slider'); ?>
	<div class="edgtf-container">
		<?php do_action('edgtf_quadric_after_container_open'); ?>
		<div class="edgtf-container-inner">
			<?php edgtf_quadric_get_blog_single(); ?>
		</div>
		<?php do_action('edgtf_quadric_before_container_close'); ?>
	</div>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>