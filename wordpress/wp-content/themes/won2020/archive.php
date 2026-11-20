<?php get_header(); ?>

<?php
	$args = array(
        'posts_per_page'=>5,
        'post_type'=> 'any',
        'meta_key'=>'views_last_week',
        'orderby'=>'meta_value_num',
        'order'=>'DESC'
	);
	$lomasvisto = new WP_Query($args);
?>

<?php $paged = $paged==0?1:$paged; ?>

	    <!-- START: content-->
			<main class="content">
				<div class="o-container">

        	        <?php 
                    $qobj = get_queried_object();
				    query_posts(array('post_type' => array('noticia', 'videocpt', 'audio'),
                        'tax_query' => array(
                            array(
                                'taxonomy' => $qobj->taxonomy,
                                'field' => 'slug',
                                'terms' => $qobj->slug
                            )
                        ),
                    'posts_per_page' => 5,'paged' => get_query_var('paged'))); 
                    ?>

			<!-- START: o-section -->
			<section class="o-section">
                 <div class="u-grid u-grid-columns-3-add">
                     <div class=" u-grid-start-1 u-grid-col-span-2 u-mb40 u-border-top">
                        <div class="u-border-bottom">
                                <h1 class="c-title u-border-none u-mb-0">
                                    <?php echo $qobj->name; ?>
                                    <?php if ($paged > 1):
                                        echo ' - Página '.$paged;
                                    endif; ?>
                                </h1>
                                <p class="c-subtitle"><?php echo $qobj->category_description; ?> </p>
                        </div>
                        <div class="u-grid u-grid-columns-2 u-mb40 u-border-bottom">
                                <div></div>
                                <!-- START: pagenavi -->
                                <nav class="wp-pagenavi" role="navigation">
                                    <?php wp_pagenavi(); ?>
                                </nav>
                                <!-- END: pagenavi -->
                        </div>
                        <div class="c-list">

							<?php while(have_posts()): the_post(); $total=185; $contenido=$total-strlen(get_the_title());?>
                             <!-- START: c-list__item-->
                             <article class="c-list__item">
                                 <figure class="c-list__item__thumb">
                                      <img src="<?php echo get_site_url().get_the_post_thumbnail_url($post->post_ID,'small') ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?>">
                                  </figure>
                                 <div class="c-list__item__txt">
                                     <h3 class="c-list__item__title"><?php the_title() ?></h3>
                                     <p class="c-list__item__subtitle"><?php echo excerpt(get_field('resumen',false,false),$contenido) ?>
									<a href="<?php the_permalink(); ?>" title="Leer más <?php echo $qobj->taxonomy; ?>" aria-label="Leer más <?php echo $qobj->taxonomy; ?>" class="c-list__item__link" >Leer más</a></p>
                                 </div>
                             </article>
							<!-- END: c-list__item-->
							<?php endwhile; ?>

                         </div>
                        <div class="u-grid u-grid-columns-2 u-mb40 u-border-top">
                             <div><!-- c  --></div>
                            <!-- START: pagenavi -->
                            <nav class="wp-pagenavi u-mt40" role="navigation">
                                    <?php wp_pagenavi(); ?>
                            </nav>
                             <!-- END: pagenavi -->
                         </div>
                     </div>
                     <aside class="u-grid-start-3 u-grid-row-start-1 u-grid-row-span-2">
                          <!-- START: c-lomas -->
                           <div class="c-lomas u-mb40 u-border-top">
                                <h3 class="c-lomas__title"><?php echo _e('Lo más visto','won2020_v2') ?></h3>
                                <figure class="c-lomas__thumb">
                                    <img src="<?php echo get_site_url().get_the_post_thumbnail_url($lomasvisto->posts[0]->ID) ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($lomasvisto->posts[0]->post_title))); ?>">
                                </figure>

                                <?php if ($lomasvisto->have_posts()):?>
                                    <ol class="c-lomas__list">
                                            <?php  while ($lomasvisto->have_posts()): $lomasvisto->the_post(); ?>
                                                <li class="c-lomas__list-item"><a href="<?php the_permalink(); ?>" title="Leer lo más visto: <?php stripQuotes(the_title()); ?>" aria-label="Leer lo más visto: <?php stripQuotes(the_title()); ?>"><?php the_title(); ?></a></li>
                                            <?php endwhile; ?>
                                    </ol>
                                <?php endif; ?>
                           </div>
                           <!-- END: c-lomas -->

                          <!-- START: c-add -->
                            <div id="skyscraper_der-id">
                                <script>
                                    googletag.cmd.push(function() { googletag.display('skyscraper_der-id'); });
                                </script>
                            </div>
                          <!-- END: c-add -->

                     </aside>
                 </div>
            </section>
            <!-- END: o-section -->

     </div>
    </main>
    <!-- END: content-->


<?php get_footer(); ?>



