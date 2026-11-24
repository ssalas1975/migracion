<?php get_header(); ?>
<?php get_template_part( 'title' ); ?>
<div class="<?php echo edgtf_quadric_blog_archive_pages_classes(edgtf_quadric_get_default_blog_list())['holder']; ?>">
<?php do_action('edgtf_quadric_after_container_open'); ?>
	<div class="<?php echo  edgtf_quadric_blog_archive_pages_classes(edgtf_quadric_get_default_blog_list())['inner']; ?>">
		<?php edgtf_quadric_get_blog(edgtf_quadric_get_default_blog_list()); ?>
	</div>
<?php do_action('edgtf_quadric_before_container_close'); ?>
</div>
<?php get_footer(); ?>