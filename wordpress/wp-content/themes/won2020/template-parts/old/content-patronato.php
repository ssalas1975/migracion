   
    <!-- START: section -->
    <section class="section section-intro">
        <h1 class="title"><?php echo get_field('intro_pagina') ?></h1>
    </section>
    <!-- END: section -->
    <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<p>','</p>' ); } ?>
    <!-- START: section -->
    <section class="section section-patronato">
        <div class="wrap">
            <div class="item">

    			<?php if (have_posts()): $count=-1; ?>

    				<?php while(have_posts()): the_post(); $count++; if($wp_query->post_count/2<$count):?>
                        <article class="grid-4 order-1">
                            <figure class="thumb">
                                 <img src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="thumb member">
                                 <figcaption class="share">
                                 	<?php if(get_field('facebook')!='' ): ?> 
                                 		<a target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook') ?>" title="">Facebook</a>
    		                        <?php endif; ?>
    		                        <?php if(get_field('twitter')!='' ): ?> 
    		                            <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter') ?>" title="">Twitter</a>
    		                        <?php endif; ?>
    		                        <?php if(get_field('linkedin')!='' ): ?> 
    		                            <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin') ?>" title="">LinkedIn</a>
    		                        <?php endif; ?>
                                  </figcaption>
                             </figure>
                             <div class="txt">
                                 <h3 class="title"><a href="#" title=""><?php the_title() ?></a></h3>
                                 <p class="subtitle"><?php echo get_field('contenido',false,false) ?></p>
                             </div>
                        </article>
                	<?php endif; endwhile; ?>

    			<?php endif; ?>

            </div>

            <div class="item">
    			
    			<?php if (have_posts()): $count=-1;?>

    				<?php while(have_posts()): the_post();  $count++; if($wp_query->post_count/2>$count):?>
                        <article class="grid-6 order-1">
                            <figure class="thumb">
                                 <img src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="thumb member">
                                 <figcaption class="share">
                                 	<?php if(get_field('facebook')!='' ): ?> 
                                 		<a target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook') ?>" title="">Facebook</a>
    		                        <?php endif; ?>
    		                        <?php if(get_field('twitter')!='' ): ?> 
    		                            <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter') ?>" title="">Twitter</a>
    		                        <?php endif; ?>
    		                        <?php if(get_field('linkedin')!='' ): ?> 
    		                            <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin') ?>" title="">LinkedIn</a>
    		                        <?php endif; ?>
                                  </figcaption>
                             </figure>
                             <div class="txt">
                                 <h3 class="title"><a href="#" title=""><?php the_title() ?></a></h3>
                                 <p class="subtitle"><?php echo get_field('contenido',false,false) ?></p>
                             </div>
                        </article>
                	<?php endif; endwhile; ?>

    			<?php endif; ?>

            </div>

        </div>
    </section>
    <!-- END: section -->