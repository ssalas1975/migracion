<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="edgtf-post-content">
		<div class="edgtf-post-image">
			<?php edgtf_quadric_get_module_template_part('templates/parts/video', 'blog'); ?>
		</div>
		<div class="edgtf-post-text">
			<div class="edgtf-post-text-inner">
				<?php edgtf_quadric_get_module_template_part('templates/lists/parts/title', 'blog', '', array('title_tag' => $title_tag)); ?>
				<div class="edgtf-post-info">
					<?php edgtf_quadric_post_info(array('author' => 'yes', 'category' => 'yes', 'comments' => 'yes')) ?>
				</div>
				<?php
					edgtf_quadric_excerpt($excerpt_length);
					//edgtf_read_more_button();
				?>
				<?php if($share != 'no' && $like != 'no' && $print != 'no')  {?>
					<div class="edgtf-post-info edgtf-post-info-bottom">
						<?php edgtf_quadric_post_info(array('share' => $share, 'like' => $like, 'print' => $print)) ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</article>