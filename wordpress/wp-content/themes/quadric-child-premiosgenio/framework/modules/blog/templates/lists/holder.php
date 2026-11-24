<?php if(($sidebar == 'default')||($sidebar == '') || ($sidebar == 'no-sidebar')) : ?>
	<?php edgtf_quadric_get_blog_type($blog_type); ?>
<?php elseif($sidebar == 'sidebar-33-right' || $sidebar == 'sidebar-25-right'): ?>
	<div <?php echo edgtf_quadric_sidebar_columns_class(); ?>>
		<div class="edgtf-column1 edgtf-content-left-from-sidebar">
			<div class="edgtf-column-inner">
				<?php edgtf_quadric_get_blog_type($blog_type); ?>
			</div>
		</div>
		<div class="edgtf-column2">
			<?php get_sidebar(); ?>
		</div>
	</div>
<?php elseif($sidebar == 'sidebar-33-left' || $sidebar == 'sidebar-25-left'): ?>
	<div <?php echo edgtf_quadric_sidebar_columns_class(); ?>>
		<div class="edgtf-column1">
			<?php get_sidebar(); ?>
		</div>
		<div class="edgtf-column2 edgtf-content-right-from-sidebar">
			<div class="edgtf-column-inner">
				<?php edgtf_quadric_get_blog_type($blog_type); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

