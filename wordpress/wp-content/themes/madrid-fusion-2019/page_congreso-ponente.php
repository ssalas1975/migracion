<?php
/**
* Template Name: Congreso - Ponentes
 */

get_header(); ?>

<?php get_template_part( 'template-parts/header', 'congreso' ); ?>

 <?php 


    // busco en cada cocinero si tiene ese id en rel_
    $cocineros = get_posts( array(
        'fields' => 'ids',
        "post_type"=>"cocineros",
        "posts_per_page"=>-1,
    ));

    $cocineros_cumbre=array();
    foreach ($cocineros as $cocinero) {
        $rel_cumbre=get_post_meta($cocinero, 'rel_cumbre', true);
        if($rel_cumbre!=NULL){
            $id_cumbre=(string)$num_cumbre;
            $cocineros_cumbre[]=(in_array($id_cumbre, $rel_cumbre))?$cocinero:null;
        }
    }
    $cocineros_cumbre=array_filter($cocineros_cumbre);
    sort($cocineros_cumbre);

    /// hago el listado con los cocineros seleccionados
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;        
    $args = Array(
        "post_type"=>"cocineros",
        "posts_per_page"=>12,
        'post__in'=>$cocineros_cumbre,
        'meta_key' => 'apellidos',
        'orderby'   => 'meta_value',
        'order' => 'ASC',
        'paged' => $paged,
    );
    $query = new WP_Query( $args );

?>
 
<div class="container clear congresoPlantilla">
    	<div class="row">
    		<section id="primary" class="content-area col-sm-12">
        
                <main id="main" class="site-main page-template-page_home-cocineros" role="main">
        			<div class="row restoCocineros contenedor-blanco">

                		<h1 class="entry-title">
                            <span><?php the_title(); echo (ICL_LANGUAGE_CODE=='es')?' de':' of'; ?> </span><?php echo get_the_title($num_cumbre) ?>
                        </h1>

                        <?php if($query->have_posts()&&$num_cumbre!=NULL&&count($cocineros_cumbre)>=1): ?>
                        <?php  while ( $query->have_posts() ) :$query->the_post();
                            $id_rest=get_post_meta($post->ID, 'rel_restaurante',true); 
                        ?>
                            
                            <div class="itemListadoCont col-md-4">
                                <div class="itemListado">
                                    <div class="imagen">
                                         <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail(); ?></a>
                                    </div>
                                    <div class="texto">
                                        <div class="titular">
                                             <h2><a href="<?php echo get_permalink();?>"><?php  echo the_title();?></a></h2>
                                        </div>
                                        <?php if ( $id_rest[0]!=NULL ) : ?>
                                            <div class="titular2">
                                                <h3><a href="<?php echo get_permalink($id_rest[0]);?>"><?php echo get_the_title($id_rest[0]); ?></a></h3>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php  endwhile; wp_reset_postdata();?>

                        <?php else: ?> 

                            <p>SIN CONTENIDO</p>

                        <?php endif; ?>
                        
                    </div>

                    <?php if($query->have_posts()&&$num_cumbre!=NULL&&count($cocineros_cumbre)>=1): ?>

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
                            </div>
                        </div>
                    
                    <?php endif; ?>


        		</main><!-- #main -->
        	</section><!-- #primary -->

<?php
get_footer();
