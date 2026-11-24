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

        <main id="main" class="site-main" role="main">

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="imagen-titulo-cocinero row">
                  <div class="imagen-titulo col-md-10">
                    
                    <header class="entry-header">
                      <?php
                            if ( is_single() ) :
                            the_title( '<h1 class="entry-title">', '</h1>' );
                            else :
                            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            endif;
                            if ( 'post' === get_post_type() ) : ?>
                      <div class="entry-meta">
                        <?php wp_bootstrap_starter_posted_on(); ?>
                        </div>
                      <!-- .entry-meta -->
                      <?php
                        endif; ?>
                      </header><!-- .entry-header -->                    
                    
                    <div class="post-thumbnail">
                      <?php the_post_thumbnail(); ?>
                      </div>
                    
                    </div>
                  
                  <div class="ficha-cocinero  col-md-2">

                    <?php $id_coc=get_post_meta($post->ID, 'rel_cocinero', true);?>
                    
                    <div class="itemListado cocineros-ficha-restaurante">
                      <div class="imagen">
					  	<?php echo get_the_post_thumbnail($id_coc); ?>
                      </div>
                      <div class="titular">
                        <a href="<?php echo get_permalink($id_coc)?>"><h3><?php echo get_the_title($id_coc) ?></h3></a>
                       </div>
                      <?php ?><div class="datos">
					  <?php echo date('Y',strtotime(get_post_meta($post->ID, 'fecha', true))); ?>
                      </div><?php ?>
                    
                    </div>
                </div>
                
                <div class="infoAdicional borde-superior">
                	<div class="izqa">
                    	<?php /*?><div class="autor"><?php the_author(); ?></div><?php */?>
                        <div class="fecha"><?php /*the_date(); */?></div>
                    </div>
                    <div class="dcha">
                    	<?php if (!dynamic_sidebar('noticias-compartir')) {} ?>
                    </div>
                </div>
                
				<div class="contenidoReceta">
				<?php
  				if ( $post->post_content=="" ) {
  				  get_template_part( 'template-parts/content-single-recetas-campos', get_post_format() );
  				} else {
  				  echo wp_specialchars_decode(apply_filters( 'the_content', get_the_content())); 
  				}; 
				?> 
                </div>
                
                
            
                <footer class="entry-footer">
                    <?php wp_bootstrap_starter_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-## -->

