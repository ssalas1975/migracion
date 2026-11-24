<?php
/**
* Template Name: Home - Recetas
 */

get_header(); ?>

<!--titular-->
<div class="degradado titularBlog">
	<h1 class="widget-title"><?php the_title(); ?></h1>
</div>

<!--abecedario y buscador-->
<?php get_template_part( 'template-parts/header', 'recetas' ); ?>
 
    
<div class="container clear">    
      
	<div class="row">
		

    <section id="primary" class="content-area col-sm-12">
    
    	
    
    
		<main id="main" class="site-main" role="main">
			
			<?php
			 while ( have_posts() ) : the_post();

			 	get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			 	if ( comments_open() || get_comments_number() ) :
			 		comments_template();
			 	endif;

			 endwhile; // End of the loop.
			?>
            
              <?php
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $busqueda = ( get_query_var( 'find' ) ) ? urldecode(get_query_var( 'find' )) : 1; ?>

                <?php  if( $busqueda==1 ) : 
                    $args = Array(
                        "post_type"=>"recetas",
                        "posts_per_page"=>12,
                        'paged' => $paged,
                        'orderby' => 'title',
                        'order'   => 'asc',
                    );
                else:
                    $args = Array(
                        "post_type"=>"recetas",
                        "posts_per_page"=>12,
                        'paged' => $paged,
                        's' => $busqueda,
                    );
                endif;

                $query = new WP_Query( $args );?>

                <?php if( $busqueda!=1 ) : ?>

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
                
                <br>

            
            <!-- LISTADO DE RECETAS -->
            <div class="contenido-blanco recetas row">

                
                <?php 
                while ( $query->have_posts() ) : $query->the_post(); 
                    $id_coc=get_post_meta($post->ID, 'rel_cocinero', true);
                ?>
               
            
                <!--aquí el módulo a duplicar (12 en total)-->
                <div class="col-md-3 itemListadoCont">
                    <div class="itemListado">
                        <div class="imagen">
                            <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail(); ?></a>
                        </div>
                        <div class="texto">
                            <div class="taxonomia ano">
                                <p><a href="#"><?php echo date('Y',strtotime(get_post_meta($post->ID, 'fecha', true))); ?></a></h3>
                            </div>
                            <?php if ( $id_coc!=NULL ) : ?>
                                <div class="taxonomia cocinero">
                                    <p><a href="<?php echo get_permalink($id_coc);?>"><?php echo get_the_title($id_coc); ?></a></p>
                                </div>
                            <?php endif; ?>
                            <div class="titular">
                                <h2><a href="<?php echo get_permalink();?>"><?php  echo the_title(); ?></a></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/-->

                <?php  endwhile; wp_reset_postdata();?>
                
            </div>
			<!-- / LISTADO DE COCINEROS -->
            
            
            <div class="contenedor-centrado">
                <div class="contenido-centrado paginacion">

                <?php 
                    echo paginate_links( array(
                        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'total'        => $query->max_num_pages,
                        'current'      => max( 1, get_query_var( 'paged' ) ),
                        'format'       => '?page=%#%',
                            // 'format'       => 'page/%#%/',
                        'show_all'     => false,
                        'type'         => 'plain',
                        'end_size'     => 1,
                        'mid_size'     => 5,
                        'prev_next'    => true,
                        'prev_text'    => sprintf( '%1$s', __( '<<', 'text-domain' ) ),
                        'next_text'    => sprintf( '%1$s', __( '>>', 'text-domain' ) ),
                        'add_args'     => false,
                        'add_fragment' => '',
                    ) );
                ?>

                </div>
            </div>
            

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
