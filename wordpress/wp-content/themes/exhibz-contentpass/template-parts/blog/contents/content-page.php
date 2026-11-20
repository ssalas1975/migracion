<?php die('kkkkkkkkkkkk'); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (has_post_thumbnail() && !post_password_required()): ?>
		<div class="entry-thumbnail post-media post-image">
			<?php
			the_post_thumbnail('full');
			?>
		</div>
	<?php endif; ?>
	<div class="post-body clearfix">
		<!-- Article content -->
		<div class="entry-content clearfix">
			<?php
			die('kkkkkkkkkkkk');
			if (is_search()) {
				the_excerpt();
			} else {
				// the_content(esc_html__('Continue reading &rarr;', 'exhibz'));
				$content = get_the_content(esc_html__('Continue reading &rarr;', 'exhibz'));

				$re = '/<iframe(.*?)<\/iframe>/msx';
				$content = do_shortcode($content);
				preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
				foreach ($matches as $match) {
					$content = str_replace($match[0], '<script type="cookiewall/html" class="my-class">' . $match[0] . '</script>', $content);
				}

				$re = '/<script(.*)src="(.*?).twitter(.*?)><\/script>/msx';
				preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
				foreach ($matches as $match) {
					$content = str_replace($match[3], $match[3] . ' type="cookiewall/script"', $content);
				}

				echo $content;

				exhibz_link_pages();
			}
			?>
		</div> <!-- end entry-content -->
	</div> <!-- end post-body -->
</article>