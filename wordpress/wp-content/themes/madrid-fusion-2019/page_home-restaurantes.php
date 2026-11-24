<?php
/**
* Template Name: Home - Restaurantes
 */

get_header(); ?>

<!--titular-->
<div class="degradado titularBlog">
	<h1 class="widget-title"><?php the_title(); ?></h1>
</div>

<!--abecedario y buscador-->
<?php get_template_part( 'template-parts/header', 'restaurantes' ); ?>


               
 
    
<div class="container clear">    
      
	<div class="row">
		

    <section id="primary" class="content-area col-sm-12">
    
                    
            <main id="main" class="site-main contenido-blanco" role="main">

            <div class="row recetas-home">
                
                 <?php 
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $busqueda = ( get_query_var( 'find' ) ) ? get_query_var( 'find' ) : 1;
                    $pais = ( get_query_var( 'id_pais' ) ) ? urldecode(get_query_var( 'id_pais' )) : 1;
                    $cont = ( get_query_var( 'cont' ) ) ? urldecode(get_query_var( 'cont' )) : 1;

                    if($busqueda==1&&$pais==1&&$cont==1):

                        $continentes = get_terms( "continentes", array( "hide_empty" => 1 ) );

                        foreach ($continentes as $key => $continente) : ?>
                            
                            <div class="itemListado uno <?php echo $continente->slug ?>">
                            	<a href="<?php echo (ICL_LANGUAGE_CODE=='es')?home_url('/restaurantes/continente/'):home_url('/restaurants/continent/'); echo $continente->slug ?>">
                                <div class="iconoPais"></div>
                                <div class="titular"><h2><?php echo $continente->name ?></h2></div>
                                <div class="titular2"><?php echo $continente->count  ?> <?php  _e( 'restaurantes', 'wp-bootstrap-starter' ); ?></div>
                                </a>
                            </div>
                            <!-- / -->

                        <?php endforeach;endif ?>

            </div>

            <?php

                if($busqueda==1&&$pais==1&&$cont==1):

                    $args = Array(
                        "post_type"=>"restaurantes",
                        "posts_per_page"=>12,
                        'paged' => $paged,
                        'orderby' => 'title',
                        'order'   => 'asc',
                    );

                elseif($busqueda!=1):

                    $args = Array(
                        "post_type"=>"restaurantes",
                        "posts_per_page"=>12,
                        "s" => $busqueda,
                        'paged' => $paged,
                        'orderby' => 'title',
                        'order'   => 'asc',
                    );

                elseif($pais!=1):

                    $args = Array(
                        "post_type"=>"restaurantes",
                        "posts_per_page"=>12,
                        'meta_query' => array(
                            array(
                                'key' => 'id_pais',
                                'value' => $pais,
                                'compare' => 'LIKE'
                            ),
                        ),
                        'paged' => $paged,
                        'orderby' => 'title',
                        'order'   => 'asc',
                    );

                elseif($cont!=1):

                    $args = Array(
                        "post_type"=>"restaurantes",
                        "posts_per_page"=>12,
                        'tax_query' => array(
                            array(
                                'taxonomy'  => 'continentes',
                                'field'     => 'slug',
                                'terms'     => $cont,
                                'operator'  => 'IN'
                            ),
                        ),
                        'paged' => $paged,
                        'orderby' => 'title',
                        'order'   => 'asc',
                    );

                endif;

                $query = new WP_Query( $args );

            ?>
			
            
            <?php  if($busqueda!=1||$pais!=1||$cont!=1): ?>
    
                <!--titular de la siguiente página -->
                <div class="titularBut total">
                    <?php if($busqueda!=1): ?>
                	   <p>Total</p>
                    <?php elseif($pais!=1): ?>
                        <p><?php echo $pais ?></p>
                    <?php else: ?>
                        <p><?php $term = get_term_by('slug', $cont, 'continentes'); echo $term->name; ?></p>
                    <?php endif; ?>
                </div>
                <div class="resultados"><?php echo $query->found_posts ?> <?php  _e( 'resultados', 'wp-bootstrap-starter' ); ?></div>
                <!-- / -->

                <!-- LISTADO DE RESTAURANTES -->
            <div class="contenido-blanco recetas row resultado">

                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            
                <!--aquí el módulo a duplicar (12 en total)-->
                <div class="col-md-3 itemListadoCont">
                    <div class="itemListado">
                        <div class="texto">
                            <div class="titular">
                                <h3><a href="<?php echo get_permalink();?>"><?php  echo the_title(); ?></a></h3>
                            </div>
                            <div class="separador"></div>
                            <div class="fecha">
                                <p><?php echo get_post_meta($post->ID, 'id_pais', true) ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/-->

                <?php  endwhile; wp_reset_postdata();?>
                
            </div>
            <!-- /  -->
            
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


            <?php endif; ?>
            
            
            
			
			
			
			
			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
