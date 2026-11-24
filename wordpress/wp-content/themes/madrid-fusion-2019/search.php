<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>
<div class="container">
	<div class="row">

	<section id="primary">
		<main id="main" class="site-main" role="main">

		<?php

		if (  have_posts() ) : ?>

			<!--titular de la busqueda-->
                    <div class="contenedor-gris resultadosTexto">
                        <div class="contenido-gris">
                                <h2>
                                <?php printf( esc_html__( 'Resultados de la búsqueda de: "%s"', 'wp-bootstrap-starter' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
                                <?php /*?><h3>Numero de resultados: <em>"<?php echo $noticias_all->post_count; ?>"</em></h3><?php */?>
                                <div class="ordenar">
                                <?php _e( 'Ordenar por', 'wp-bootstrap-starter' ); ?> 
                                <a href="<?php echo add_query_arg( 'ord', 'rel'); ?>" class="<?php if($ord=='rel'){echo 'activo';} ?>"><?php _e( 'Relevancia', 'wp-bootstrap-starter' ); ?></a> 
                                <a href="<?php echo add_query_arg( 'ord', 'desc'); ?>" class="<?php if($ord=='desc'){echo 'activo';} ?>"><?php _e( 'Últimos', 'wp-bootstrap-starter' ); ?></a> 
                                <a href="<?php echo add_query_arg( 'ord', 'asc'); ?>" class="<?php if($ord=='asc'){echo 'activo';} ?>"><?php _e( 'Antiguos', 'wp-bootstrap-starter' ); ?></a></div>
                        </div>
                    </div>
                    <!--/--> 

			<div  class="contenido-blanco resultadosItem">
            	<div class="row">
			<?php
			
			while (  have_posts() ) :  the_post();

				get_template_part( 'template-parts/content', 'search' );

			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
        
        
        	</div>

              <div class="contenedor-centrado">
                <div class="contenido-centrado paginacion">

                <?php 

                global $wp_query;

                    echo paginate_links( array(
                        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'total'        => $wp_query->max_num_pages,
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
      

        </div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
//get_sidebar();
get_footer();
