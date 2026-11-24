<?php
	/*
	Template Name: Blog Masonry Full Width
	*/
?>
<?php get_header(); ?>

<?php get_template_part( 'title' ); ?>
<?php get_template_part('slider'); ?>

	<div class="edgtf-full-width">
		<div class="edgtf-full-width-inner clearfix">
			<?php edgtf_quadric_get_blog('masonry-full-width'); ?>
		</div>
	</div>
<?php get_footer(); ?>