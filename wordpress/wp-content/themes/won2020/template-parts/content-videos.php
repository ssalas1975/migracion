
<?php 	query_posts(array(
		'post_type' => 'videocpt',  
		'posts_per_page' => 6,
		'paged' => get_query_var('paged'),
		'post__not_in' => array (get_field('video_destacado')[0]->ID)
		)); 	
		?>


<!-- START: o-section -->
<section class="o-section">
	<h1 class="c-title-small u-color-cyan"><?php echo _e('Videos','won2020_v2'); ?><?php if ($paged > 1): echo ' - Página '.$paged; endif; ?></h1>

	<?php $posts = get_field('video_destacado'); foreach( $posts as $post): setup_postdata($post);?>
		<!-- START:destacado -->
		<article class="c-article degree-max">
                <figure class="c-article__thumb video">
                    <img data-src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" src="" loading="lazy">
                </figure>
                <div class="c-article__txt">
                    <p class="c-article__tag"><?php echo get_field('pretitulo') ?></p>
                    <h3 class="c-article__title"><a href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>"><?php the_title(); ?></a></h3>
                </div>
		</article>
		<!-- END:destacado-->
		<?php endforeach; wp_reset_postdata();?>  

	
	<!-- START:resto -->
	<p class="c-title"><?php echo _e('Más videos','won2020_v2') ?></p>
	<div class="u-grid u-grid-columns-3">

				<?php while(have_posts()): the_post();?>
					<article class="c-article">
									<figure class="c-article__thumb video">
														<img data-src="<?php echo get_site_url().get_the_post_thumbnail_url($post->post_ID,'video-imgs') ?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" src="" loading="lazy">
										</figure>
									<div class="c-article__txt">
													<p class="c-article__tag"><?php echo get_field('pretitulo') ?></p>
													<h3 class="c-article__title"><a href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>"><?php the_title() ?></a></h3>
									</div>
					</article>
				<?php endwhile ?>
					<!-- <div>
								<a class="c-list__link u-mt40 u-mb40" href="#" title="">Ver más vídeos</a>
					</div> -->
	</div>

	<?php global $wp_query; if($wp_query->found_posts>6): ?>
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

<?php get_template_part( 'template-parts/content', 'modulos' ); ?>