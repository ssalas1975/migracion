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
		<article class="noticia 11">
			<?php
			$newperformance = $_GET['newperformance'];
			if ($newperformance == 'validatenewperformance') {
				echo '<!-- -------_________------ -->';
				addLazyLoadToImage(get_the_content());
			} else {
				the_content();
			}
			?>

			<!-- /.post -->
		<?php endwhile; ?>

	<?php else: ?>
		<h2>Whoops! Looks like we can't find this post.</h2>

	<?php endif; ?>

</article>
<?php get_footer(); ?>
</div>