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
  <div class="widget-title"><?php  _e( 'Recetas', 'wp-bootstrap-starter' ); ?></div>
</div>
<!--abecedario y buscador-->
<?php get_template_part( 'template-parts/header', 'recetas' ); ?>

<div class="container contenido-blanco">
	<div class="row">
		<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content-single-recetas', get_post_format() );
			    the_post_navigation();
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</section><!-- #primary -->
    
	</div><!-- .row -->
	</div><!-- .container -->
	</div><!-- #content -->    
    
    
    <aside class="pie-recetas contenedor-gris"> 
       
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
        
        <div class="contenido-gris">

        
        <!--relacionadas-->
        <div class="titularBut">
            <h3 class="widget-title">
                <?php /*?><?php dynamic_sidebar( 'recetas-relacionadas' ); ?><?php */?>
                <?php  _e( '<strong><em class="ocultar">Otras</em></strong> <em>del mismo</em> autor', 'wp-bootstrap-starter' ); ?>
            </h3>
        </div>
            
        <div class="page-template-page_home-recetas otras swiper-container">

        	<div class="swiper-wrapper">

	        	<?php
				$id_coc=get_post_meta($post->ID, 'rel_cocinero', true);
				$args = Array(

					"post_type"=>"receta",
					"posts_per_page"=>9,
					'meta_query' => array(
					    array(
					     'key' => 'rel_cocinero',
					     'value' => $id_coc
					    ),
					),
					"post__not_in"=>array($post->ID),
				);
				query_posts($args);

				if( have_posts() ): while ( have_posts() ) : the_post(); ?>
	        	
				
					<div class="itemListado swiper-slide">
						<div class="imagen">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
						<div class="texto">
	                    
	                    	<!--incluyo la clase ano y la programación que yo uso para pintarlo-->
							<div class="taxonomia ano">
								<?php echo date('Y',strtotime(get_post_meta($post->ID, 'fecha', true))); ?>
	                        </div>
	                        <!--incluyo la programación que yo uso para pintarlo-->
	                        
	                        <!--incluyo el div para incluir al cocinero-->
	                        <div class="taxonomia cocinero">
	                        	<p><a href="<?php echo get_permalink($id_coc);?>"><?php echo get_the_title($id_coc); ?></a></p>
	                        </div>
	                        <!-- / incluyo el div para incluir al cocinero-->
	                        
							<div class="titular">
								<a href="<?php the_permalink(); ?>">
									<h3><?php the_title(); ?></h3>
								</a>
							</div>
						</div>
					</div>
				

				<?php endwhile; wp_reset_postdata(); ?>

				

			</div>


			<!-- Add Pagination -->
			      <div class="swiper-pagination"></div>
			      <!--/-->
			        <!-- Adelante - Atrás -->
			        <div class="swiper-button-prev"></div>
			        <div class="swiper-button-next"></div>
			        <!--/-->

				<?php else:  ?>

				<!--texto provisional no hay recetas-->
                <div class="aviso"><?php  _e( 'Este cocinero no tiene recetas publicadas', 'wp-bootstrap-starter' ); ?></div>
	            <!-- / texto provisional no hay recetas-->
	        	<?php endif; ?>
		</div>

                
		<!--ultimas recetas-->
        <div class="titularBut">
            <h3 class="widget-title">
                <?php /*?><?php dynamic_sidebar( 'recetas-ultimas' ); ?><?php */?>
                 <?php  _e( '<strong>Últimas</strong> recetas', 'wp-bootstrap-starter' ); ?>
            </h3>
        </div>
            
        <div class="page-template-page_home-recetas ultimas swiper-container dos">

        	<div class="swiper-wrapper">
				<?php
				$args = Array(
					"post_type"=>"receta",
					"posts_per_page"=>9,
					'meta_query' => array(
					    array(
					     'key' => 'rel_cocinero',
					     'value' => $id_coc,
					     'compare'=>'!='
					    ),
					),
				);
				query_posts($args);
				while ( have_posts() ) : the_post(); 
					$id_coc=get_post_meta($post->ID, 'rel_cocinero', true);
				?>
				
					<div class="itemListado swiper-slide">
						<div class="imagen">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
						<div class="texto">
	                    
	                    	<!--incluyo la clase ano y la programación que yo uso para pintarlo-->
							<div class="taxonomia ano">
								<?php echo date('Y',strtotime(get_post_meta($post->ID, 'fecha', true))); ?>
	                        </div>
	                        <!--incluyo la programación que yo uso para pintarlo-->
	                        
	                        <!--incluyo el div para incluir al cocinero-->
	                        <div class="taxonomia cocinero">
	                        	<p><a href="<?php echo get_permalink($id_coc);?>"><?php echo get_the_title($id_coc); ?></a></p>
	                        </div>
	                        <!-- / incluyo el div para incluir al cocinero-->

							<div class="titular">
								<a href="<?php the_permalink(); ?>">
									<h3><?php the_title(); ?></h3>
								</a>
							</div>
						</div>
					</div>
				
				<?php endwhile; wp_reset_postdata();?>
			</div>

			<!-- Add Pagination -->
		      <div class="swiper-pagination dos"></div>
		      <!--/-->
		        <!-- Adelante - Atrás -->
		        <div class="swiper-button-prev dos"></div>
		        <div class="swiper-button-next dos"></div>
		        <!--/-->

		      <?php /*?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/assets/css/swiper.min.css" media="all" />
				<script src="<?php echo get_template_directory_uri(); ?>/inc/assets/js/swiper.min.js"></script><?php */?>
				<script type="text/javascript">
		           window.onload = function () {

		           		// swiper otras del mismo autor

		           	  var swiper = new Swiper('.swiper-container', {
		                slidesPerView: 3,
		                spaceBetween: 30,
		                // init: false,
		                pagination: {
		                  el: '.swiper-pagination',
		                  clickable: true,
		                },
		                navigation: {
		                nextEl: '.swiper-button-next',
		                prevEl: '.swiper-button-prev',
		                },
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


		              // swiper otras recetas

		              var swiper2 = new Swiper('.swiper-container.dos', {
		                slidesPerView: 3,
		                spaceBetween: 30,
		                // init: false,
		                pagination: {
		                  el: '.swiper-pagination.dos',
		                  clickable: true,
		                },
		                navigation: {
		                nextEl: '.swiper-button-next.dos',
		                prevEl: '.swiper-button-prev.dos',
		                },
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
		              //swiper2.slideNext();
		          };
		     </script>
		</div>

</div>
        
        
        <?php /*?><?php dynamic_sidebar( 'noticias-barra-lateral' ); ?><?php */?>
    </aside><!-- #secondary -->
    
    
<?php
// get_sidebar();
get_footer();
