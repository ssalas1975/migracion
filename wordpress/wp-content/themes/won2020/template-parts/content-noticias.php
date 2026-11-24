
<?php 	query_posts(array('post_type' => 'noticia',  'posts_per_page' => 5, 'paged' => get_query_var('paged'))); 	?>

<!-- START: o-section -->
<section class="o-section">

<?php
	//$paged = $paged==0?1:$paged; 
	global $wp_query;
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	//print_r(query_posts(array('post_type' => 'noticia')));
	! empty ( $page ) && 1 < $page && $paged = $page;
?>

	<div class="u-grid u-grid-columns-2 u-mb40 u-border-bottom">
				<h1 class="c-title-small u-color-cyan u-border-none u-mb-0">
					Te interesa
					<?php if ($paged > 1):
						echo ' - Página '.$paged;
					endif; ?>
				</h1>
				<!-- START: pagenavi -->
				<nav class="wp-pagenavi" role="navigation">
							<?php wp_pagenavi(); ?>
				</nav>
				<!-- END: pagenavi -->
	</div>
	<div class="c-list">

					<!-- START: c-list__item-->
						<?php while(have_posts()): the_post(); $total=185; $contenido=$total-strlen(get_the_title());?>
							<!-- START: c-list__item-->
							<article class="c-list__item">
											<figure class="c-list__item__thumb">
																<img data-src="<?php echo get_site_url().get_the_post_thumbnail_url($post->post_ID,'portada-noticias-imgs') ?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" src="" loading="lazy">
												</figure>
											<div class="c-list__item__txt">
															<h2 class="c-list__item__title"><?php the_title(); ?></h2>
															<p class="c-list__item__subtitle"><?php echo excerpt(get_field('resumen',false,false),$contenido) ?>
																<a href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>" class="c-list__item__link" >Leer más</a></p>
											</div>
							</article>
							<!-- END: c-list__item-->

					<?php endwhile; ?>
					<!-- END: c-list__item-->

	</div>
	<div class="u-grid u-grid-columns-2 u-mb40 u-border-top">
					<div><!-- c  --></div>
					<!-- START: pagenavi -->
					<nav class="wp-pagenavi u-mt40" role="navigation">
							<?php wp_pagenavi(); ?>
					</nav>
					<!-- END: pagenavi -->
	</div>

</section>
<!-- END: o-section -->

<?php get_template_part( 'template-parts/content', 'modulos' ); ?>