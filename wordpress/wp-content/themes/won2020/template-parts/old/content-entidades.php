	

	<!-- START: section -->
    <section class="section section-intro">
        <h1 class="title"><?php echo get_field('intro_pagina') ?></h1>
    </section>
    <!-- END: section -->

	<?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb( '<p>','</p>' ); } ?>

	<!-- START: section -->
	<section class="section section-partner">
	    <div class="wrap" style="color:white">

	    	<?php $posts='entidad'; $term='tipo_entidad'; $tax_terms = get_terms( $term, 'orderby=term_id'); ?>

				<?php foreach ( $tax_terms as $term ): ?>

					<h2 class="title"><?php echo $term->name ?></h2>
						<div class="item">
							<?php 
								$args = Array(
		                                'posts_per_page'=> -1,
		                                'post_type' => 'entidad',
		                                'tax_query' => array( array(
		                                        'taxonomy'  => 'tipo_entidad',
		                                        'field'     => 'name',
		                                        'terms'     => $term->name,
		                                        'operator'  => 'IN'
		                                ),),
		                                'order' => 'ASC',

		                        );
								$tax_terms_posts = get_posts( $args ); 
							?>

							<?php foreach ( $tax_terms_posts as $post ): ?>
			                    
			                        <figure class="thumb">
			                            <a href="#"> <img src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?> partner name">  </a>
			                            <figcaption class="caption">
			                                <p><?php echo get_field('contenido',false,false) ?></p>
			                            </figcaption>
			                        </figure>
			                    
			                <?php endforeach; ?>
			            </div>

	        	<?php endforeach; ?>

	    </div>
	</section>
	<!-- END: section -->
