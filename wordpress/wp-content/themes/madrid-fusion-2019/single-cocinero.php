<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

<!--titular blog-->
<div class="degradado titularBlog">
  <div class="widget-title"><?php  _e( 'Cocineros', 'wp-bootstrap-starter' ); ?></div>
</div>
<!--abecedario y buscador-->
<?php get_template_part( 'template-parts/header', 'cocineros' ); ?>

<div class="container contenido-blanco">
	<div class="row">
		<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content-single-cocineros', get_post_format() );
			    the_post_navigation();
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</section><!-- #primary -->
    
    
    <aside id="secondary" class="widget-area col-sm-12 col-lg-4" role="complementary"> 
       
        <?php /*?><div class="formulario-busqueda">
            <div class="textoBuscador"><?php if (!dynamic_sidebar('noticias-buscador')) {} ?></div>
            <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
              <label>
                <span class="screen-reader-text"><?php echo _x( 'Buscar en el blog |', 'label' ) ?></span>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Buscar en el blog |', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Buscar en el blog |', 'label' ) ?>" />
              </label> 
              <input type="hidden" name="post_type" value="noticias" /> 
              <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
            </form>
        </div><?php */?>
        
        <div class="borde-superior listado-lateral">
        	<h3 class="widget-title">
            	<?php  _e( 'Recetas', 'wp-bootstrap-starter' ); ?>
            </h3>
            
                <?php
				$args = Array(
					"post_type"=>"receta",
					"posts_per_page"=>12,
					'meta_key'      => 'rel_cocinero',
                    'meta_value'    => $post->ID
					
				);
				$query = new WP_Query( $args );
				if( $query->have_posts() ): while ( $query->have_posts() ) : $query->the_post(); 
				?>
				<div class="itemListado cocineros-ficha-recetas">
					<div class="fecha">
                    
                    <!--incluyo la programación que yo uso para pintarlo-->
					<?php echo date('Y',strtotime(get_post_meta($post->ID, 'fecha', true))); ?>
                    <!-- / incluyo la programación que yo uso para pintarlo-->


                    
                    </div>
                    <div class="imagen"><?php the_post_thumbnail(); ?></div>
					<div class="texto">
						<div class="titular">
							<a href="<?php the_permalink(); ?>">
								<h3><?php the_title(); ?></h3>
							</a>
						</div>
					</div>
				</div>
				<?php endwhile; wp_reset_postdata(); else: ?>

				<!--texto provisional no hay recetas-->
	        	<div class="aviso"><?php  _e( 'Este cocinero no tiene recetas publicadas', 'wp-bootstrap-starter' ); ?></div>
	            <!-- / texto provisional no hay recetas-->

	        	<?php endif; ?>
        </div>
        
        
        <?php /*?><?php dynamic_sidebar( 'noticias-barra-lateral' ); ?><?php */?>
    </aside><!-- #secondary -->
    
    
<?php
// get_sidebar();
get_footer();
