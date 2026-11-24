<?php
$blog_archive_pages_classes = any_edge_blog_archive_pages_classes(any_edge_get_default_blog_list());
?>
<?php get_header(); ?>
<?php any_edge_get_title(); ?>
<div class="<?php echo esc_attr($blog_archive_pages_classes['holder']); ?>">
	<?php do_action('any_edge_after_container_open'); ?>
	<div class="<?php echo esc_attr($blog_archive_pages_classes['inner']); ?>">
		<?php any_edge_get_blog(any_edge_get_default_blog_list()); ?>
	</div>
	<?php do_action('any_edge_before_container_close'); ?>
</div>
<?php do_action('any_edge_after_container_close'); ?>
<?php get_footer(); ?>
