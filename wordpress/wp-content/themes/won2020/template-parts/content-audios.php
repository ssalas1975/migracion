<!-- START: o-section -->
<section class="o-section">
	<h1 class="c-title-small <?php echo $classtitle; ?>">
		<?php echo _e($post->post_title, 'won2020_v2'); ?>
		<?php if ($paged > 1): echo ' - Página '.$paged; endif; ?>
	</h1>
	<div class="c-detail__media__thumb">
		<img data-src="<?php echo get_the_post_thumbnail_url() ?>" alt="La actualidad imprescindible" loading="lazy">
	</div>


	<!-- START:resto -->
	<div class="c-list">

		<?php while (have_posts()) : the_post(); ?>

			<article class="c-list__item c-list__item-podcast">
				<figure class="c-list__item__thumb">
					<?php
					if (get_post_thumbnail_id($post->ID)) {
						echo '<img data-src="' . get_site_url().wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0] . '" alt="'.(str_replace('"', '\'', strip_tags($post->post_title))).'" src="" loading="lazy">';
					} else {
						echo '<img data-src="' . get_site_url().get_template_directory_uri() . '/assets/img/podcast/banner2020-150x150.jpg" alt="'.(str_replace('"', '\'', strip_tags($post->post_title))).'" src="" loading="lazy">';
					}
					?>
				</figure>
				<div class="c-list__item__txt">
					<h2 class="c-list__item__title"><a href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>"><?php the_title(); ?></a></h2>
					<p class="c-list__item__subtitle"><?php echo get_field('subtitulo')  ?></p>
				</div>
				
				<div class="c-list__item__play"><a href="<?php the_permalink(); ?>" title="Reproducir <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Reproducir <?php echo (stripQuotes(get_the_title())); ?>"><?php echo _e('Reproducir', 'won2020_v2') ?></a></div>

				<!-- START:links -->
				<?php $num = 0;
				$max = count(get_field('escuchalo'));
				if (have_rows('escuchalo')) : ?>
					<div class="c-list__item__social">
						Escúchalo en
						<?php while (have_rows('escuchalo')) : the_row();
							$num++; ?>
							<a href="<?php echo get_sub_field('enlace');  ?>" title="Escúchalo en <?php echo get_sub_field('plataforma'); ?>" aria-label="Escúchalo en <?php echo get_sub_field('plataforma'); ?>"><?php echo get_sub_field('plataforma'); ?></a>
							<?php $and = "";
							if ($num != $max) {
								$and = ", ";
							}
							if ($num == $max - 1) {
								$and = " y ";
							};
							echo $and;  ?>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
				<!-- END:links -->

			</article>
		<?php endwhile ?>

	</div>

	<?php global $wp_query;
	if ($wp_query->found_posts > 10) : ?>
		<div class="u-grid u-grid-columns-2 u-mb40 u-border-top" style="display:block">
			<!-- START: pagenavi -->
			<nav class="wp-pagenavi" role="navigation">
				<?php wp_pagenavi(); ?>
			</nav>
			<!-- END: pagenavi -->
		</div>
	<?php endif; ?>
	<!-- END:resto -->

</section>
<!-- END: o-section -->

<?php get_template_part('template-parts/content', 'modulos'); ?>