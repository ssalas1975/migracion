<?php
/**
* Template Name: Home - Noticias
 */

get_header(); ?>

<!--titular blog-->
<div class="degradado titularBlog">
	<h1 class="widget-title"><?php the_title(); ?></h1>
</div>
<!--hamburguesa y menu-->
<?php get_template_part( 'template-parts/header', 'noticias' ); ?>

<div class="container clear">    
      
	<div class="row">
		

    <section id="primary" class="content-area col-sm-12">
    
    	
    
    
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
            
           
            
            <!-- NOTICIA PRINCIPAL -->
            <!--desparece el div contenedor-gris
            el siguiente pasa de tener .contenido-gris a .contenido-blanco
            el resto, igual que en la HOME GENERAL-->

            <?php 
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $busqueda = ( get_query_var( 'find' ) ) ? urldecode(get_query_var( 'find' )) : 1; 
                $categoria = ( get_query_var( 'seccion' ) ) ? get_query_var( 'seccion' ) : 1;
                $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
            ?>

           <?php  

                $args = array(
                    'posts_per_page'=> 1,
                    'post_type'     => 'noticias',
                    'meta_key'      => 'noticia-destacada',
                    'meta_value'    => 1,
                    'meta_compare'  => '==',
                );
                // query
                $destacada = new WP_Query($args);
                if ( $destacada->have_posts() ) : while ( $destacada->have_posts() ) : $destacada->the_post();
                    $id_destacado[]=$post->ID;
            ?>
    
            <?php if( $paged==1&&$busqueda==1&&$categoria==1 ) : ?>

            <div class="contenido-blanco noticias-home destacado">
                    <!--aquí el módulo destacado-->
                    <div class="itemListadoCont">
                        <div class="itemListado">
                            <div <?php $video = get_field('video-noticia'); echo($video!='')?'class="imagen video-on" data-video="'.$video.'"':'class="imagen"'; ?> >
                                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail() ?></a>
                            </div>
                            <div class="texto">
                                <div class="taxonomia">
                                    <?php $terms=get_the_terms( $post->ID, 'blog' ); 
                                    if($terms): foreach ($terms as $key => $term) :
                                        if($key>=1){echo ', ';} ; 
                                        $link=(ICL_LANGUAGE_CODE=='es')? home_url( '/el-blog/seccion/' ):home_url( '/the-blog/section/' );
                                        echo '<a href="'.$link.$term->slug.'">'.$term->name.'</a>' ;
                                    endforeach; endif; ?> 
                                </div>
                                <div class="titular">
                                    <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                                </div>
                                <div class="autor">
                                    <p><?php the_author_posts_link() ?></p>
                                </div>
                                <div class="fecha">
                                        <?php la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?>
                                </div>
                                <div class="extracto">
                                    <p><?php the_excerpt() ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/-->
                </div>
			<!-- / NOTICIA PRINCIPAL -->
            <?php endif; ?>

            <?php endwhile; endif; wp_reset_postdata(); ?>
            
            
            <!-- LISTADO DE NOTICIAS -->
            <?php 
                    
                if( $busqueda!=1 ) : 
                    $args = Array(
                        'posts_per_page' => 9,
                        'post_type'     => 'noticias',
                        's' => $busqueda,
                        'paged' => $paged
                    );
                    
                endif;

                $noticias = new WP_Query($args);

            ?>
                

                <?php if( $busqueda!=1 ) :  ?>
                    <!--titular de la busqueda-->
                    <div class="contenedor-gris resultadosTexto">
                        <div class="contenido-gris">
                            <?php if( $noticias->found_posts>0) : ?>
                                <h2><?php echo $noticias->found_posts; ?> <?php  _e( 'resultados de la búsqueda de', 'wp-bootstrap-starter' ); ?> <em>"<?php echo $busqueda; ?>"</em></h2>
                                <?php /*?><h3>Numero de resultados: <em>"<?php echo $noticias_all->post_count; ?>"</em></h3><?php */?>
                            <?php else: ?>
                                 <h2><?php  _e( 'No se han encontrado resultados', 'wp-bootstrap-starter' ); ?></h2>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--/-->
                    <?php endif; ?>


            <div class="contenido-blanco noticias-home row">
               	

                <!-- NOTICIAS NORMALES -->
                <?php 
                     if( $busqueda==1 ) : 

                        $args = Array(
                            'posts_per_page' => 9,
                            'post_type'     => 'noticias',
                            "post__not_in"=>$id_destacado,
                            'paged' => $paged
                        );

                        $noticias = new WP_Query($args);

                    endif;

                    if( $categoria!=1 ) : 

                        $args = Array(
                            'posts_per_page' => 9,
                            'post_type'     => 'noticias',
                            'paged' => $paged,
                            'tax_query' => array(
                                array(
                                    'taxonomy'  => 'blog',
                                    'field'     => 'slug',
                                    'terms'     => $categoria,
                                    'operator'  => 'IN'
                                ),
                            ),
                        );

                        $noticias = new WP_Query($args);
                        
                    endif;

                    if ( $noticias->have_posts() ) : while ( $noticias->have_posts() ) : $noticias->the_post();
                ?>

                <div class="itemListadoCont col-md-4">
                    <div class="itemListado">
                        <div <?php $video = get_field('video-noticia'); echo($video!='')?'class="imagen video-on" data-video="'.$video.'"':'class="imagen"'; ?> >
                            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail() ?></a>
                        </div>
                        <div class="texto">

                            <div class="taxonomia">
                                <?php $terms=get_the_terms( $post->ID, 'blog' ); 
                                if($terms): foreach ($terms as $key => $term) :
                                    if($key>=1){echo ', ';} ; 
                                    $link=(ICL_LANGUAGE_CODE=='es')? home_url( '/el-blog/seccion/' ):home_url( '/the-blog/section/' );
                                    echo '<a href="'.$link.$term->slug.'">'.$term->name.'</a>' ;
                                endforeach; endif; ?> 
                            </div>
                            <div class="titular">
                                <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                            </div>
                            <div class="autor">
                                <p><?php the_author_posts_link() ?></p>
                            </div>
                            <div class="fecha">
                                    <p><?php la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?></p>
                            </div>
                            <div class="extracto">
                                <p><?php the_excerpt() ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; endif; wp_reset_postdata(); ?>
               	<!-- NOTICIAS NORMALES -->


       	    </div>
			<!-- / LISTADO DE NOTICIAS -->
            
            <div class="contenedor-centrado">
                <div class="contenido-centrado paginacion">

                <?php 
                        echo paginate_links( array(
                            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'total'        => $noticias->max_num_pages,
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            'format'       => '?page=%#%',
                            // 'format'       => 'page/%#%/',
                            'show_all'     => false,
                            'type'         => 'plain',
                            'end_size'     => 1,
                            'mid_size'     => 5,
                            'prev_next'    => false,
                            'prev_text'    => sprintf( '%1$s', __( '<<', 'text-domain' ) ),
                            'next_text'    => sprintf( '%1$s', __( '>>', 'text-domain' ) ),
                            'add_args'     => false,
                            'add_fragment' => '',
                        ) );
                    ?>

                </div>
            </div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
