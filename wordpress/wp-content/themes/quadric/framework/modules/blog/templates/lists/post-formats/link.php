<?php
	$background_style = array();
	if(has_post_thumbnail()){

		$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
		$background_image_object = edgtf_quadric_resize_image($post_thumbnail_id, '', 1100, 400);
		$background_image_src = $background_image_object['img_url'];
		$background_style[] = 'background-image:url('.$background_image_src.');';
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="edgtf-post-content">
		<div class="edgtf-post-text" <?php echo edgtf_quadric_get_inline_style($background_style) ?>>
			<?php edgtf_quadric_get_module_template_part('templates/parts/top-date', 'blog'); ?>
			<div class="edgtf-post-text-inner">
				<div class="edgtf-post-mark">
					<span class="edgtf-link-mark icon-link"></span>
				</div>
				<?php edgtf_quadric_get_module_template_part('templates/lists/parts/title', 'blog', '', array('title_tag' => $title_tag)); ?>
			</div>
		</div>
		<?php if($share != 'no' && $like != 'no' && $print != 'no')  {?>
			<div class="edgtf-post-info edgtf-post-info-bottom">
				<?php edgtf_quadric_post_info(array('share' => $share, 'like' => $like, 'print' => $print)) ?>
			</div>
		<?php } ?>
	</div>
</article>