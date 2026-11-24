<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<section id="primary" class="content-area col-sm-12 col-lg-8">

        <main id="main" class="site-main" role="main">
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            
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
                
                <div class="cocinero-restaurante">
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    
                    <?php $restaurantes=get_post_meta($post->ID, 'rel_restaurante', true) ?>

                    <?php if(count($restaurantes)<=1): ?>
                    <!--datos de 1 restaurante-->
                    <div class="datosRestaurante">
                        <?php $id_rest=get_post_meta($post->ID, 'rel_restaurante', true); ?>
                         <div class="itemListado cocineros-ficha-restaurante">
                            <div class="titular">
                                <a href="<?php echo get_permalink($id_rest[0])?>"><h2><?php echo get_the_title($id_rest[0]) ?></h2></a>
                            </div>
                            <div class="datos direccion"><?php echo get_post_meta($id_rest[0], 'direccion', true) ?></div>
                            <div class="datos telefono"><?php echo get_post_meta($id_rest[0], 'telefono', true) ?></div>
                            <div class="datos web">
								<?php /*?><?php echo get_post_meta($id_rest[0], 'pagina_web', true) ?><?php */?>
                                <a href="http://<?php echo get_post_meta($id_rest[0], 'pagina-web', true) ?>" target="_blank">
                                    <?php /*?><?php
                   					$contenidoDelCampo = get_post_meta($post->ID, 'pagina-web', true);
									echo $contenidoDelCampo; ?><?php */?>
                                    <?php echo get_post_meta($id_rest[0], 'pagina-web', true) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- / datos de 1 restaurante-->
                    
                    <?php else: ?>
                    
                    <!--datos de VARIOS restaurantes-->
                    <div class="datosRestaurante">
                        <?php $id_rest=get_post_meta($post->ID, 'rel_restaurante', true); ?>
                         <div class="itemListado cocineros-ficha-restaurante">
                            <div class="titular">
                                <h2></h2>
                            </div>
                            <!--cada restaurante-->
                            <?php foreach ($restaurantes as $rest): ?>
                                <div class="datos varios">
    								<a href="<?php echo get_permalink($rest)?>"><?php echo get_the_title($rest) ?></a><br/>
                                    <span><?php echo get_post_meta($rest, 'telefono', true) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- / datos de VARIOS restaurante-->
                    
                     <?php endif; ?>
                    
                    
                </div>
                
                <div class="infoAdicional borde-superior">
                	<div class="izqa">
                    	<div class="autor"><?php the_author(); ?></div>
                        <div class="fecha"><?php the_date(); ?></div>
                    </div>
                    <div class="dcha">
                    	<?php if (!dynamic_sidebar('noticias-compartir')) {} ?>
                    </div>
                </div>
                
                <div class="entry-content borde-superior">
                	<div class="redesCocinero">
                    	<?php
                        $contenidoDelCampoTwitter = get_post_meta($post->ID, 'twitter', true);
						$contenidoDelCampoInstagram = get_post_meta($post->ID, 'instagram', true);
						if($contenidoDelCampoTwitter != ''){ ?>
							<a class="twitter" href="<?php echo $contenidoDelCampoTwitter; ?>" target="_blank">Twitter</a> <?php
						}
						if($contenidoDelCampoInstagram != ''){ ?>
							<a class="instagram" href="<?php echo $contenidoDelCampoInstagram; ?>" target="_blank">Instagram</a> <?php
						}
                    	?>
                        
                    </div>
                    <?php
                   		echo wp_specialchars_decode(get_post_meta($post->ID, 'descripcion', true));
                    ?>
                </div><!-- .entry-content -->
            
                <footer class="entry-footer">
                    <?php wp_bootstrap_starter_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-## -->

