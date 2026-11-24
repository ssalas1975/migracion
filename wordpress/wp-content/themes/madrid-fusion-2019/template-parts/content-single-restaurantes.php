<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>


<section class="contenido-blanco">

	<header class="entry-header">
		<?php
			if ( is_single() ) :
			the_title( '<div class="contenedor-centrado"><div class="contenido-centrado"><span></span><h1 class="entry-title recetas-restaurantes">', '</h1></div></div>' );
			else :
			the_title( '<div class="contenedor-centrado"><div class="contenido-centrado"><span></span><h2 class="entry-title recetas-restaurantes"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2></div></div>' );
			endif;
			if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php wp_bootstrap_starter_posted_on(); ?>
			</div>
			<!-- .entry-meta -->
			<?php endif; ?>
	</header><!-- .entry-header -->
    
    <main id="main" class="site-main" role="main">

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <div class="row texto-receta">
		
		<div class="restaurantesCocineros col-md-9 row">
				<h2><?php  _e( 'Cocineros', 'wp-bootstrap-starter' ); ?></h2>

                <?php 

                 ////obteniendo cocineros
                 $args = array(
                    'numberposts'   => -1,
                    'post_type'     => 'cocineros',
                    'meta_key'      => 'rel_restaurante',
                    'meta_value'    => $post->ID,
                    'meta_compare' => 'like'
                    
                );
                // query
                $cocineros = new WP_Query($args);
                global $coc_select;
                 ?>

                <?php if ( $cocineros->have_posts() ) : while ( $cocineros->have_posts() ) : $cocineros->the_post(); ?>
<!-- <div class="col-md-4">
-->
                    <div style="width:33%;margin:auto">
                        <div class="itemListado cocineros-ficha-restaurante">
                            <div class="imagen"><?php the_post_thumbnail() ?></div>
                            <div class="texto">
                                <div class="titular">
                                    <a href="<?php the_permalink() ?>"><h3><?php the_title() ?></h3></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $coc_select[]= $post->ID; ?>

                <?php endwhile; endif; wp_reset_postdata(); ?>
             
			</div>
            
            <div class="restaurantesDireccion col-md-3">
            	<h2><?php  _e( 'Contacto', 'wp-bootstrap-starter' ); ?></h2>
                <div class="datosRestaurante">
    				<div class="datos direccion">
                    	<?php echo get_post_meta($post->ID, 'direccion', true);?>
                	</div>
    				<div class="datos telefono">
                    	<?php echo get_post_meta($post->ID, 'telefono', true);?>
                    </div>
    				<div class="datos web">
                    	<a href="http://<?php echo get_post_meta($post->ID, 'pagina-web', true);?>" target="_blank">
                    		<?php echo get_post_meta($post->ID, 'pagina-web', true);?>
                        </a>
                    </div>
    			</div>
		  </div>
        
		</div>
                      
                <footer class="entry-footer">
                    <?php wp_bootstrap_starter_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-## -->

