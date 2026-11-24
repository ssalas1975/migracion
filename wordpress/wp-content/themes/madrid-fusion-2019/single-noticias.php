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
	<div class="widget-title"><?php  _e( 'El blog de Madrid Fusión', 'wp-bootstrap-starter' ); ?></div>
</div>
<!--hamburguesa, menu-->
<?php get_template_part( 'template-parts/header', 'noticias' ); ?>

<div class="container contenido-blanco">
	<div class="row">
		<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content-single-noticias', get_post_format() );
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
       
        <!--buscador-->
        
        
        <div class="formulario-busqueda">
            <form role="search" method="get" class="search-form" action="<?php echo home_url( '/el-blog/' ); ?>">
                <label>
                    <input type="search" class="search-field" placeholder="<?php  _e(' Buscar noticias |', 'wp-bootstrap-starter' ); ?>" value="<?php echo get_search_query() ?>" name="find" title="<?php  _e(' Buscar noticias |', 'wp-bootstrap-starter' ); ?>" onclick="if(this.placeholder=='<?php  _e(' Buscar noticias |', 'wp-bootstrap-starter' ); ?>') this.placeholder=''" onblur="if(this.placeholder=='') this.placeholder='<?php  _e(' Buscar noticias |', 'wp-bootstrap-starter' ); ?>'"/>
                </label> 
                <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
            </form>
        </div>
        <!-- / buscador-->



        
        <div class="suscripcionInterior suscripcion">
			<h3 class="widget-title"><?php esc_html_e( 'Suscríbete a nuestra newsletter', 'wp-bootstrap-starter' ); ?></h3>
			<?php if (!dynamic_sidebar('suscripcion')) {} ?>
            <?php /*?><script src="//pre-newsletters.vocento.com/prospect/2800/js/prospect.js"></script>
            <div class="widget-prospect" data-widget-id="5b56f1c4fa01f8f1f0f18f5a"></div><?php */?>
        </div>
        
        <div class="borde-superior listado-lateral">
        	<div class="widget-title">
            	<?php  _e( 'Últimas noticias', 'wp-bootstrap-starter' ); ?>
            </div>
            
                <?php
				$args = Array(
					"post_type"=>"noticias",
					"posts_per_page"=>3,
				);
				query_posts($args);
				while ( have_posts() ) : the_post(); 
				?>
				<div class="itemListado">
					<div <?php $video = get_field('video-noticia'); echo($video!='')?'class="imagen video-on" data-video="'.$video.'"':'class="imagen"'; ?> >
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
					</div>
					<div class="texto">
						<div class="titular">
							<a href="<?php the_permalink(); ?>">
								<h3><?php the_title(); ?></h3>
							</a>
						</div>
						<div class="autor"><?php the_author(); ?></div>
						<div class="fecha">
							<?php la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?>
                        </div>
					</div>
				</div>
				<?php endwhile; ?>
        </div>
        
        
        <?php dynamic_sidebar( 'noticias-barra-lateral' ); ?>
    </aside><!-- #secondary -->
    
    
<?php
// get_sidebar();
get_footer();
