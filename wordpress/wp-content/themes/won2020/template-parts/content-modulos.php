<!-- <span style="position:relative; display:block; margin-top:-1em"></span> -->

<!-- START: modulos HOME-->
<?php $current_ID=$GLOBALS['current_id']; $current_type=$GLOBALS['current_type']; $home=get_page_by_title('Home'); ?>
<?php if (have_rows('modulos', $home->ID)): while (have_rows('modulos', $home->ID)) : the_row();?>
<?php $pagina=(get_sub_field('mostrar'))?get_sub_field('mostrar'):array(); $tipo=(get_sub_field('mostrar_int'))?get_sub_field('mostrar_int'):array(); ?>

	<?php if (in_array($current_ID, $pagina)||in_array($current_type, $tipo)): ?>

		<!-- START: o-section -->
		<section class="o-section">

			<?php $posts = get_sub_field('contenido'); $cabeceras=(strpos(get_sub_field('titulo'), ',')!==false)? explode(',', get_sub_field('titulo')):''; ?>
			<?php if ($cabeceras==''): ?>
                <?php if (get_sub_field('publicidad')=='boton_native-id'): ?>
					<h4 class="c-title title_special_content">CONTENIDO ESPECIAL</h4>
				    <h4 class="c-title title_add_native"><?php echo get_sub_field('titulo'); ?></h4>
                <?php else: ?>
                    <h4 class="c-title"><?php echo get_sub_field('titulo'); ?></h4>
                <?php endif; ?>
			<?php endif; ?>
			<?php if (count($posts)<=3||get_sub_field('carrusel')==1): ?>
				<div class="u-grid u-grid-columns-<?php echo (count($posts)<=3)?count($posts):'3'; echo (get_sub_field('publicidad')!='')?'-add':''; ?>">
			<?php else: ?>
				<?php if (get_sub_field('publicidad')!=''):?>
					<div class="u-grid u-grid-columns-3-add">
					<div class="o-carousel u-grid-start-1 u-grid-col-span-2" data-type="carousel" data-carousel-items="2" data-carousel-items-xs="1"><div class="o-carousel__wrap">
				<?php else: ?>
					<div class="o-carousel" data-type="carousel" data-carousel-items="3"><div class="o-carousel__wrap">
				<?php endif; ?>
			<?php endif; ?>

			<?php $posts = get_sub_field('contenido'); if ($posts): ?>
				<?php foreach ($posts as $key => $post): setup_postdata($post); ?>
					<article class="c-article">
						<?php if ($cabeceras[$key]): ?>
							<h1 class="c-title"><?php echo $cabeceras[$key]; ?></h1>
						<?php endif; ?>
							<figure class="c-article__thumb <?php if (get_field('video')||$post->post_type=='videocpt') {
								echo 'video ';
								} if (get_field('galeria')) {
								echo 'gallery';
								} ?>">
								<img data-src="<?php echo get_site_url().get_the_post_thumbnail_url($post->post_ID,'module-imgs');?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" src="" loading="lazy">
							</figure>
							<div class="c-article__txt">
								<?php $total=110; $contenido=$total-strlen(get_the_title());?>
								<p class="c-article__tag"><?php echo get_field('pretitulo') ?></p>
								<?php if (count($posts)<=3||get_sub_field('carrusel')==1): ?>
									<h3 class="c-article__title"><a href="<?php the_permalink() ?>" title="Leer <?php echo stripQuotes(get_the_title()); ?>" aria-label="Leer <?php echo stripQuotes(get_the_title()); ?>">	
								<?php else: ?>									
									<h3 class="c-article__title"><a href="<?php the_permalink() ?>" title="Leer <?php echo (strlen(stripQuotes(get_the_title()))>=$total)?title_excerpt(stripQuotes(get_the_title()), $total):get_the_title(); ?>" aria-label="Leer <?php echo (strlen(stripQuotes(get_the_title()))>=$total)?title_excerpt(stripQuotes(get_the_title()), $total):get_the_title(); ?>">
								<?php endif; ?>
										<?php if (count($posts)<=3||get_sub_field('carrusel')==1): ?>
												<?php echo get_the_title(); ?>
										<?php else: ?>
												<?php echo (strlen(get_the_title())>=$total)?title_excerpt(get_the_title(), $total):get_the_title();  ?>
										<?php endif; ?>
								</a></h3>
							</div>
					</article>
					<?php endforeach; wp_reset_postdata(); endif; ?>
					<?php if (get_sub_field('publicidad')!=''&&get_sub_field('carrusel')==1): ?>
						<?php if (get_sub_field('publicidad')=='boton_native-id'): ?>
							<aside class='aside-native'>
							<h4 class="c-title title_special_content_mobile">CONTENIDO ESPECIAL</h4>
							<?php if($post->post_type!='noticia' || $current_type=='page'){ ?>
								<!-- START: c-add -->
								<div id="<?php echo get_sub_field('publicidad'); ?>">
									 <script type="vocento/script">
										googletag.cmd.push(function() { googletag.display("<?php echo get_sub_field('publicidad'); ?>"); });
									</script>
								</div>
							<?php } ?>
							<?php else: ?>
								<aside  class="u-grid-start-3 u-grid-row-start-1 u-grid-row-span-2">
									<?php if($post->post_type!='noticia' || $current_type=='page'){ ?>
										<!-- START: c-add -->
                                        <div id="<?php echo get_sub_field('publicidad'); ?>">
											 <script type="vocento/script">
												googletag.cmd.push(function() { googletag.display("<?php echo get_sub_field('publicidad'); ?>"); });
											</script>
										</div>
									<?php } ?>
						<?php endif; ?>

						<?php if ( is_front_page() && get_sub_field('publicidad')!='boton_native-id' ) { ?>
						<!-- START: c-newsletter -->
						<div class="newsletter-prospect-col-c">
							<?php if($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES"){ ?>
							<div class='widget-prospect' data-widget-id='5f8980232d52f418cf6520c0'> </div>
						<?php } elseif($_ENV["ENVIRON"] == "PRO") { ?>
							<div class='widget-prospect' data-widget-id='60ca01bd428b2161b2263a82'> </div>
						<?php } ?>
						</div>
						<!-- END: c-newsletter -->
						<?php }?>
						</aside>

					<?php endif; ?>
			</div>

			<?php if (count($posts)>3&&get_sub_field('carrusel')!=1): ?></div><?php endif; ?>
				<?php if (get_sub_field('publicidad')!=''&&get_sub_field('carrusel')!=1): ?>
				<aside  class="u-grid-start-3 u-grid-row-start-1 u-grid-row-span-2">
					<!-- START: c-add -->
					<div id="<?php echo get_sub_field('publicidad'); ?>">
						 <script type="vocento/script">
							googletag.cmd.push(function() { googletag.display("<?php echo get_sub_field('publicidad'); ?>"); });
						</script>
					</div>
					<!-- END: c-newsletter -->
				</aside>
			<?php endif; ?>

</section>
<!-- END: o-section -->

<?php endif; ?>

<?php  endwhile; wp_reset_postdata(); endif;  ?>
<!-- END: modulos -->
<aside  class="o-section" style="margin-top:30px;">
<div class="c-add c-add-d980x250 u-flex">
<div class="c-add__creatividad">
<!-- START: c-add -->
<div id="megabanner_dos-id">
	 <script type="vocento/script">
		googletag.cmd.push(function() { googletag.display('megabanner_dos-id'); });
	</script>
</div>
<!-- END: c-add -->
</div>
</div>
</aside>


<!-- START: o-section -->
<section class="o-section">
	<div class="c-interest">
		<p class="c-interest__title u-flex"><?php echo _e('Enlaces de Interés', 'won2020_v2') ?></p>
		<nav class="c-interest__list u-flex">
			<?php 
			$home = get_page_by_title( 'home' );
			$terms = get_field('enlaces_interes',$home->ID);
			//var_dump($terms);
			foreach ($terms as $term): ?>
				<a href="<?php echo get_term_link($term) ?>" title="Ir a <?php echo get_the_category_by_ID($term); ?>" aria-label="Ir a <?php echo get_the_category_by_ID($term); ?>" class="c-interest__list-item" ><?php echo get_the_category_by_ID($term); ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>
<!-- END: o-section -->
