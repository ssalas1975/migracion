<?php get_header(); ?>

	<main>

		<!----------------------- Noticias ----------------------------------->

		<section class="cabecera noticias container">
	        <span class="color_title"></span>
	        <span class="title">News</span>
	    </section>

		<?php if( $post->post_type=='noticias'): ?>

			<?php 

	        $args = array(
	            'posts_per_page' => -1,
	            'post_type'     => 'noticias',
	            'meta_query' => array(
	                array( 'key' => 'destacada','value' => 1 )
	            ),
	            'order' => 'DESC' 
	        );
	        $destacadas = new WP_Query($args); 

	        while($destacadas->have_posts()){
	        	 $destacadas->the_post();
	        	 $destacadas_id[]=$post->ID;
	        }

	        $args = array(
	            'posts_per_page' => -1,
	            'post__not_in' => $destacadas_id,
	            'post_type'     => 'noticias',
	            'order' => 'DESC' 
	        );
	        $noticias = new WP_Query($args); 

	        ?>

			<?php if($destacadas->have_posts()||$noticias->have_posts()): ?>
				<section class="news index container">
		            <ul>

						<!-- ////////////// -->
		            	<!-- las destacadas -->
		            	<?php 
	                        for($i=0;$i<=4;$i++): 
	                             $args = array(
	                                'posts_per_page' => 1,
	                                'post_type'     => 'noticias',
	                                'meta_query' => array(
	                                    'relation' => 'AND',
	                                    array(
	                                        'relation' => 'AND',
	                                        array( 'key' => 'destacada','value' => 1 ),
	                                        array('key' => 'noticias_destacadas', 'value' => $i )
	                                    ),
	                                ),
	                                'order' => 'DESC' 
	                        );
	                        $destcadas = new WP_Query($args);
	                    ?>

	                    <?php if ( $destcadas->have_posts() ) : $destcadas->the_post();  ?>
	                        <li>
	                            <a href="<?php the_permalink() ?>">
	                                <?php while(have_rows('imagenes-noticia')){ the_row(); 
	                                    if(get_sub_field('imagen')!=''){ $img=get_sub_field('imagen')['url']; break; } 
	                                } ?>
	                                <span class="imagen" style="background-image: url('<?php echo $img ?>')">
	                                    <span class="hover"><i class="icon-lupa center"></i></span>
	                                </span>
	                                <h1><?php the_title() ?></h1>
	                                <p><?php echo get_the_excerpt()?></p>
	                            </a>
	                        </li>
	                    <?php  endif; wp_reset_postdata(); endfor; ?>
	                    <!-- //////// -->

						<!-- //////// -->
	                    <!-- el resto -->
		            	<?php while($noticias->have_posts()): $noticias->the_post() ?>
							<li>
			                    <a href="<?php the_permalink() ?>">
			                    	<?php while(have_rows('imagenes-noticia')){ the_row(); 
			                    		if(get_sub_field('imagen')!=''){ $img=get_sub_field('imagen')['url']; break; } 
			                    	} ?>
			                        <span class="imagen" style="background-image: url('<?php echo $img ?>')">
			                            <span class="hover"><i class="icon-lupa center"></i></span>
			                        </span>
			                        <h1><?php the_title() ?></h1>
			                        <p><?php echo get_the_excerpt()?></p>
			                    </a>
			                </li>
		            	<?php endwhile; ?>
		            	<!-- //////// -->

		            </ul>
		        </section>
			<?php endif; ?>

		<!----------------------- Noticias ----------------------------------->
		<?php else: ?>


			
		<?php endif; ?>

	</main>

<?php get_footer(); ?>
