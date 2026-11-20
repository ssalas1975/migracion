	
	<!-- START: section -->
	<section class="section section-intro">
	    <h1 class="title"><?php echo get_field('intro_pagina') ?></h1>
	</section>
	<!-- END: section -->

	<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<p>','</p>' ); } ?>

	<!-- START: section -->
	<section class="section section-who">
	    <div class="wrap">
	    	<?php if (have_posts()):?>

				<?php while(have_posts()): the_post(); ?>

					<article class="person">
			            <p class="title"><?php the_title() ?></p>
			            <p class="subtitle"><?php echo get_field('contenido',false,false) ?></p>
			            <div class="list">
			                <div class="list-item" style="background: url(<?php echo get_the_post_thumbnail_url() ?>) repeat-x 0 0;"><!-- c --></div>
			            </div>
			            <nav class="share">
			                <?php if(get_field('facebook')!='' ): ?> 
		                 		<a target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook') ?>" title="">Facebook</a>
		                    <?php endif; ?>
		                    <?php if(get_field('twitter')!='' ): ?> 
		                        <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter') ?>" title="">Twitter</a>
		                    <?php endif; ?>
		                    <?php if(get_field('linkedin')!='' ): ?> 
		                        <a target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin') ?>" title="">LinkedIn</a>
		                    <?php endif; ?>
			            </nav>
			        </article>

	        	<?php endwhile; ?>

			<?php endif; ?>

	    </div>
	</section>
	<!-- END: section -->
