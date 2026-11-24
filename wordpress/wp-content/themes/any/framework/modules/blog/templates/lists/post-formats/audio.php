<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="edgtf-post-content">
		<?php any_edge_get_module_template_part('templates/lists/parts/image', 'blog'); ?>
		<?php any_edge_get_module_template_part('templates/parts/audio', 'blog'); ?>
		<div class="edgtf-post-text">
			<div class="edgtf-post-text-inner">
				<?php any_edge_get_module_template_part('templates/lists/parts/title', 'blog'); ?>

				<?php  if ($type == 'standard-whole-post') {
					the_content();
				}
				else{
					any_edge_excerpt($excerpt_length);
				}
				?>
				<?php any_edge_get_module_template_part('templates/lists/parts/pages-navigation', 'blog');  ?>

				<div class="edgtf-post-info-bottom">
					<div class="edgtf-post-info-bottom-left">
						<?php any_edge_post_info(array(
							'date' => 'yes',
							'author' => $show_author,
							'category' => $show_category,
							'comments' => 'yes',
							'like' => 'yes'
						)) ?>
					</div>
					<div class="edgtf-post-info-bottom-right">
						<?php echo any_edge_get_button_html(array(
							'type' => 'transparent',
							'link' => get_the_permalink(),
							'text' => esc_html__('Read More', 'any'),
							'icon_pack'    => 'font_elegant',
							'fe_icon'      => 'arrow_right',
						)); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>