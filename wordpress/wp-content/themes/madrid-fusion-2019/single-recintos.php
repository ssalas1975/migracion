<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

<?php get_template_part( 'template-parts/header', 'congreso' ); ?>

<div class="container contenido-blanco single-restaurantes">

	<h1 style="display:none;"><?php the_title(); ?></h1>
    <div class="congresoPlantilla">
    	<h1 class="entry-title"><?php echo get_post_meta($post->ID, 'titular-alternativo', true);?></h1>
    </div>
	<div class="row texto-receta">
    
		<div class="col-md-3">
            	<h2><?php  /*_e( 'Contacto', 'wp-bootstrap-starter' );*/ ?></h2>
                <div class="datosRestaurante">
    				<div class="datos direccion">
                    	<?php the_field('direccion'); ?>
                	</div>
    				<div class="datos telefono">
                    	<?php echo get_post_meta($post->ID, 'telefono', true);?>
                    </div>
    				<div class="datos web">
                    	<a href="http://<?php echo get_post_meta($post->ID, 'pagina_web', true);?>" target="_blank"><?php echo get_post_meta($post->ID, 'pagina_web', true);?></a>
                    </div>
    			</div>
		  </div>
          
          <div class="col-md-6 plano">
			<?php the_field('plano'); ?>
		  </div>
          
          <div class="col-md-3">
            	<h2><?php  _e( 'Vías de acceso', 'wp-bootstrap-starter' ); ?></h2>
                <div class="datosRestaurante">
    				<div class="datos metro">
                    	<?php echo get_post_meta($post->ID, 'metro', true);?>
                	</div>
    				<div class="datos buses">
                    	<?php echo get_post_meta($post->ID, 'buses', true);?>
                    </div>
    				<div class="datos carreteras">
                    	<?php the_field('carreteras'); ?>
                    </div>
    			</div>
		  </div>
          
          
	</div>
</div>        
        
        
        <?php /*?><?php dynamic_sidebar( 'noticias-barra-lateral' ); ?><?php */?>
    </aside><!-- #secondary -->
    
    
<?php
// get_sidebar();
get_footer();
