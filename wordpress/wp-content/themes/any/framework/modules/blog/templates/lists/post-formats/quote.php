<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="edgtf-post-content">
		<div class="edgtf-post-text">
			<div class="edgtf-post-text-inner">
				<div class="edgtf-post-mark edgtf-quote-mark">
					<span class="icon_quotations"></span>
				</div>
				<h4 class="edgtf-post-title">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html(get_post_meta(get_the_ID(), "edgtf_post_quote_text_meta", true)); ?></a>
				</h4>
				<span class="edgtf-quote-author"><?php the_title(); ?></span>
			</div>
		</div>
	</div>
</article>