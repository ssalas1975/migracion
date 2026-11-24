<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>
<header class="entry-header">

	<div style="display: none;"><?php the_terms( $post->ID, 'noticias' );?> </div>
    <div class="entry-meta taxonomia">

        <?php $terms=get_the_terms( $post->ID, 'blog' ); 
        if($terms): foreach ($terms as $key => $term) :
            if($key>=1){echo ', ';} ; 
            $link=(ICL_LANGUAGE_CODE=='es')? home_url( '/el-blog/seccion/' ):home_url( '/the-blog/section/' );
            echo '<a href="'.$link.$term->slug.'">'.$term->name.'</a>' ;
        endforeach; endif; ?> 
            
            
        </div>
            
    
	<?php
        if ( is_single() ) :
        the_title( '<h1 class="entry-title">', '</h1>' );
        else :
        the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
        endif;
                
        if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php wp_bootstrap_starter_posted_on(); ?>
            </div>
        <!-- .entry-meta -->
        <?php
    endif; ?>
</header><!-- .entry-header -->
<section id="primary" class="content-area col-sm-12 col-lg-8">
		
        <main id="main" class="site-main" role="main">

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <!-- <div class="post-thumbnail"> -->
                <div <?php $video = get_field('video-noticia'); echo($video!='')?'class="post-thumbnail video-on" data-video="'.$video.'"':'class="post-thumbnail"'; ?> >
                    <?php the_post_thumbnail(); ?>
                </div>
                
                <div class="leyenda">
                	<?php 
						$contenidoDelCampo = get_post_meta($post->ID, 'descripcion_de_la_imagen', true);
						echo $contenidoDelCampo;
					 ?>
					<?php /*?><?php 
						$thumb_id = get_post_thumbnail_id(); 
						$thumbnail_details = get_posts(array('p' => $thumb_id, 'post_type' => 'attachment'));
						$leyenda = $thumbnail_details[0]->post_excerpt;
					?><?php */?>
					<?= esc_html($leyenda); ?>
                </div>
                
                <div class="subtitular borde-superior">
                	<h2>
                    <?php 
						$contenidoDelCampo = get_post_meta($post->ID, 'subtitulo', true);
						echo $contenidoDelCampo;
					 ?>
                     </h2>
                </div>
                
                <div class="infoAdicional borde-superior">
                	<div class="izqa">
                    	<div class="autor"><?php the_author(); ?></div>
                        <div class="fecha">
                        	<?php la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?>
                        </div>
                    </div>
                    <div class="dcha">
                    	<?php if (!dynamic_sidebar('noticias-compartir')) {} ?>
                    </div>
                </div>
                
                <div class="entry-content">
                    <?php
                    if ( is_single() ) :
                        the_content();
                    else :
                        the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wp-bootstrap-starter' ) );
                    endif;
            
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-bootstrap-starter' ),
                            'after'  => '</div>',
                        ) );
                    ?>
                </div><!-- .entry-content -->
            
                <footer class="entry-footer">
                    <?php wp_bootstrap_starter_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-## -->

