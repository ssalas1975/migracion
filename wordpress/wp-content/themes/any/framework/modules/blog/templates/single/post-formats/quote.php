<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="edgtf-post-content">
		<div class="edgtf-post-text">
			<div class="edgtf-post-text-inner clearfix">
				<div class="edgtf-post-mark edgtf-quote-mark">
					<span class="icon_quotations"></span>
				</div>
				<h4 class="edgtf-post-title">
					<?php echo esc_html(get_post_meta(get_the_ID(), "edgtf_post_quote_text_meta", true)); ?>
				</h4>
				<span class="edgtf-quote-author"><?php the_title(); ?></span>
			</div>
		</div>

		<?php the_content(); ?>

		<?php any_edge_get_module_template_part('templates/lists/parts/pages-navigation', 'blog');  ?>

		<div class="edgtf-post-info-bottom">
			<div class="edgtf-post-info-bottom-left">
				<?php any_edge_post_info(array(
					'date' => 'yes',
					'author' => 'yes',
					'category' => 'yes',
				)) ?>
			</div>
			<div class="edgtf-post-info-bottom-right">
				<?php any_edge_post_info(array(
					'comments' => 'yes',
					'like' => 'yes',
				), 'single') ?>
			</div>
		</div>
	</div>
</article>