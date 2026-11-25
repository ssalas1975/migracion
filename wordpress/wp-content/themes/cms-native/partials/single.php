<?php
//eliminando el canonical de wordpress
remove_action('wp_head', 'rel_canonical');
get_header();
//contando visitas...
setPostViews(get_the_ID());

?>
<?php if (have_posts()):
	while (have_posts()):
		the_post(); ?>
		<article class="noticia">
			<?php
			$content = get_the_content();
			$content = do_shortcode($content);
			// $content = str_replace("/contentfactory/post/wp-content/uploads/2024/", "/contentfactory/post/wp-content/uploads/sites/4/2024/", $content);
			echo $content;
			?>

			<!-- /.post -->
		<?php endwhile; ?>

	<?php else: ?>
		<h2>Whoops! Looks like we can't find this post.</h2>

	<?php endif; ?>

</article>
<?php get_footer(); ?>