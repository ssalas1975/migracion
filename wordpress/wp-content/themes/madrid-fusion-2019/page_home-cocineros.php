<?php
/**
* Template Name: Home - Cocineros
 */

get_header(); ?>

<!--titular-->
<div class="degradado titularBlog">
	<h1 class="widget-title"><?php the_title(); ?></h1>
</div>

<!--abecedario y buscador-->
<?php get_template_part( 'template-parts/header', 'cocineros' ); ?>
 
    
<div class="container clear">    
      
	<div class="row">
		

    <section id="primary" class="content-area col-sm-12">
    
    
		<main id="main" class="site-main" role="main">

			<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;?>
			<?php $letra = ( get_query_var( 'letra' ) ) ? get_query_var( 'letra' ) : 1;?>
			<?php $busqueda = ( get_query_var( 'find' ) ) ? urldecode(get_query_var( 'find' )) : 1;?>

			
			
			<?php
			 if( $paged==1&&$letra==1&&$busqueda==1 ) :
			 while ( have_posts() ) : the_post();

			 	get_template_part( 'template-parts/content', 'page' );

			 	// If comments are open or we have at least one comment, load up the comment template.
			 	if ( comments_open() || get_comments_number() ) :
			 		comments_template();
			 	endif;

			 endwhile; endif;// End of the loop.
			?>
            
            <?php if( $paged==1&&$letra==1&&$busqueda==1 ) : ?>
				<div class="titularBut">
            		<h2><?php _e( '<em>Las</em> estrellas <em class="ocultar">de <strong>Reale Seguros Madrid Fusión</strong></em>', 'wp-bootstrap-starter' ); ?> 2020</h2>
            	</div>
            <?php endif; ?>
            
             
            <div class="contenido-blanco cocineros swiper-container interior">

            	

					<!-- DESTACADO DE COCINEROS -->
	                <div class="swiper-wrapper">

	                    <?php

	                    $args = Array(
	                        "post_type"=>"cocineros",
	                        "posts_per_page"=>-1,
	                        'meta_key'      => 'cocinero-destacado',
	                        'meta_value'    => 1,
                            'meta_compare'  => '==',
	                    );
	                    $query = new WP_Query( $args );
	                    while ( $query->have_posts() ) : $query->the_post(); 
	                        $id_rest=get_post_meta($post->ID, 'rel_restaurante', true);
	                        $id_destacado[]=$post->ID;
	                    ?>

						<?php if( $paged==1&&$letra==1&&$busqueda==1 ) : ?>
	                    <!--aquí el módulo a duplicar (3 en total)-->
	                    <div class="itemListadoCont swiper-slide">
	                        <div class="itemListado">
	                            <div class="imagen">
	                                 <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail(); ?></a>
	                            </div>
	                            <div class="texto">
	                                <div class="titular">
	                                     <h2><a href="<?php echo get_permalink();?>"><?php  echo the_title(); ?></a></h2>
	                                </div>
	                                <?php if ( $id_rest[0]!=NULL ) : ?>
	                                    <div class="titular2">
	                                        <p><a href="<?php echo get_permalink($id_rest[0]);?>"><?php echo get_the_title($id_rest[0]); ?></a></p>
	                                    </div>
	                                <?php endif; ?>
	                            </div>
	                        </div>
	                    </div>
						<?php endif; ?>

	                    <?php  endwhile; wp_reset_postdata();?>

	                </div>
                    <?php if( $paged==1&&$letra==1&&$busqueda==1 ) : ?>
                    <!-- Add Pagination -->
    				<div class="swiper-pagination"></div>
	                <!--/-->
                    <!-- Adelante - Atrás -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <!--/-->
                    <?php endif; ?>

					<?php if( $paged==1 ) : ?>
	                <?php /*?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/assets/css/swiper.min.css" media="all" />
					<script src="<?php echo get_template_directory_uri(); ?>/inc/assets/js/swiper.min.js"></script><?php */?>
                    <script type="text/javascript">
                         window.onload = function () {
                            var swiper = new Swiper('.swiper-container', {
                              slidesPerView: 4,
                              spaceBetween: 30,
                              // init: false,
                              //pagination: {
                              //  el: '.swiper-pagination',
                               // clickable: true,
                              //},
							  navigation: {
								nextEl: '.swiper-button-next',
								prevEl: '.swiper-button-prev',
							  },
							  //
							  autoplay: { delay: 4000,	},
							  //
                              breakpoints: {
                                768: {
                                  slidesPerView: 2,
                                  spaceBetween: 15,
                                },
                                600: {
                                  slidesPerView: 1,
                                  spaceBetween: 15,
                                },
                              }
                            });
							//swiper.slideNext();
                        };
                    </script>

	                <?php endif; ?>

	                 <!-- / DESTACADO DE COCINEROS -->
                     <?php if( $paged==1&&$letra==1&&$busqueda==1 ) : ?>
                     <div class="separador"></div>
                     <?php endif; ?>

				</div>
				
					<?php
					
					if( $letra==1&&$busqueda==1 ):

                    $args = Array(
                        "post_type"=>"cocineros",
                        "posts_per_page"=>12,
                        "post__not_in"=>$id_destacado,
                        'meta_key' => 'apellidos',
                        'orderby'   => 'meta_value',
                        'order' => 'ASC',
                        'paged' => $paged,
                    );

                    elseif( $busqueda!=1 ):

                    $args = Array(
                        "post_type"=>"cocineros",
                        "posts_per_page"=>12,
                        'titulo' => $busqueda,
                        'order' => 'ASC',
                        'paged' => $paged,
                    );

                    else:

                    $args = Array(
                        "post_type"=>"cocineros",
                        "posts_per_page"=>12,
                        'meta_key' => 'apellidos',
                        'meta_query' => array(
                            array(
                                'key' => 'apellidos',
                                'value' => '^'.$letra.'.*',
                                'compare' => 'REGEXP',
                            )
                        ),
                        'orderby'   => 'meta_value',
                        'order' => 'ASC',
                        'paged' => $paged,
                    );

                    endif;

                    $query = new WP_Query( $args );

                    if( $busqueda!=1 ) : ?>

                    <div class="contenedor-gris resultadosTexto">
                        <div class="contenido-gris">
                            <?php if( $query->post_count>0) : ?>
                                <h2><?php echo $query->found_posts; ?> <?php  _e( 'resultados de la búsqueda de', 'wp-bootstrap-starter' ); ?> <em>"<?php echo $busqueda; ?>"</em></h2>
                                <?php /*?><h3>Numero de resultados: <em>"<?php echo $query->post_count; ?>"</em></h3><?php */?>
                            <?php else: ?>
                                 <h2><?php  _e( 'No se han encontrado resultados', 'wp-bootstrap-starter' ); ?></h2>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php endif; ?>

                    <div class="row restoCocineros contenedor-blanco">
                    
                    <div class="titularBut ponentes">
                        <h3><?php _e( 'Ponentes', 'wp-bootstrap-starter' ); ?></h3>
                    </div>

                    <?php 
                    while ( $query->have_posts() ) : $query->the_post(); 
                        $id_rest=get_post_meta($post->ID, 'rel_restaurante', true);
                    ?>



                    
                <!--aquí el módulo a duplicar (3 en total)-->
                    <div class="itemListadoCont col-md-4">
                        <div class="itemListado">
                            <div class="imagen">
                                 <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail(); ?></a>
                            </div>
                            <div class="texto">
                                <div class="titular">
                                     <h2><a href="<?php echo get_permalink();?>"><?php  echo the_title(); ?></a></h2>
                                </div>
                                <?php if ( $id_rest[0]!=NULL ) : ?>
                                    <div class="titular2">
                                        <p><a href="<?php echo get_permalink($id_rest[0]);?>"><?php echo get_the_title($id_rest[0]); ?></a></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!--/-->

                    <?php  endwhile; wp_reset_postdata();?>
				</div>
                
            <!--</div>-->
           



            
            
            <div class="contenedor-centrado">
                <div class="contenido-centrado paginacion">
                    
                    <?php 
                        echo paginate_links( array(
                            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'total'        => $query->max_num_pages,
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            // 'format'       => 'page?=%#%',
                            'format'       => 'page/%#%/',
                            'show_all'     => false,
                            'type'         => 'plain',
                            'end_size'     => 1,
                            'mid_size'     => 5,
                            'prev_next'    => false,
                            // 'prev_text'    => sprintf( '%1$s', __( '<<', 'text-domain' ) ),
                            // 'next_text'    => sprintf( '%1$s', __( '>>', 'text-domain' ) ),
                            'add_args'     => false,
                            'add_fragment' => '',
                        ) );
                    ?>
                    <!-- / PAGINACIÓN (el código que quieras)-->
                </div>
            </div>
            

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
