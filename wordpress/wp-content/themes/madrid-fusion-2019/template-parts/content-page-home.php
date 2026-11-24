<?php
/**
 * Template part for displaying page content in page.php
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
    $enable_vc = get_post_meta(get_the_ID(), '_wpb_vc_js_status', true);
    if(!$enable_vc ) {
    ?>
    <header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
    <?php } ?>

	<div class="entry-content">

    
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
                	$id_destacado=$post->ID;
            ?>




    		<!-- NOTICIA PRINCIPAL -->
            <div class="contenedor-gris">
                <div class="contenido-gris noticias-home destacado">
                    <!--aquí el módulo destacado-->
                    <div class="itemListadoCont">
                        <div class="itemListado">
                        <!--BTN INSCRIBETE-->
						<div class="btnInscripcion"><?php if (!dynamic_sidebar('noticia-destacada-home-inscripcion')) {} ?></div>
                        <!-- / BTN INSCRIBETE-->
                            <?php $video = get_field('video-noticia'); $class=(get_field('video-home'))?'video-on':'video-on not_show'; ?>
                            <div <?php echo($video!='')?'class="imagen '.$class.'" data-video="'.$video.'"':'class="imagen"'; ?> >
                                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail() ?></a>
                            </div>
                            <div class="texto">
                                <div class="taxonomia">
                                    <?php $terms=get_the_terms( $post->ID, 'blog' ); 
                                    if($terms): foreach ($terms as $key => $term) :
                                        if($key>=1){echo ', ';} ; 
                                        echo '<a href="'.home_url( "/el-blog/" ).'?n='.$term->slug.'">'.$term->name.'</a>' ;
                                    endforeach; endif; ?> 
                                </div>
                                <div class="titular">
                                    <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                                </div>
                                <div class="autor">
                                    <p><?php the_author_posts_link() ?></p>
                                </div>
                                <!--div class="fecha">
                                    <?php //la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?>
                                </div-->
                                <div class="extracto">
                                    <p><?php the_excerpt() ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/-->
                </div>
            </div>
			<!-- / NOTICIA PRINCIPAL -->
			<?php endwhile; endif; wp_reset_postdata(); ?>
            
  
           
            
            <!-- LISTADO DE NOTICIAS -->
            <div class="contenedor-gris">

                <div class="contenido-gris noticias-home row">
                    	<?php 
                            for($i=0;$i<=11;$i++): 
        		                 $args = array(
        		                    'posts_per_page' => 1,
        		                    'post_type'     => 'noticias',
        		                    "post__not_in"=>array($id_destacado),
                                    'meta_key'      => 'noticia-destacada-2',
                                    'meta_value'    => $i,
                                    'meta_compare'  => '==',
                                    'order' => 'DESC' 
        		                );
        		                $noticia = new WP_Query($args);
                                if ( $noticia->have_posts() ) : $noticia->the_post(); 
    		            ?>

                            <div class="itemListadoCont col-md-4">
                                <div class="itemListado">
                                    <?php $video = get_field('video-noticia'); $class=(get_field('video-home'))?'video-on':'video-on not_show'; ?>
                                    <div <?php echo($video!='')?'class="imagen '.$class.'" data-video="'.$video.'"':'class="imagen"'; ?> >  
                                        <a href="<?php the_permalink() ?>"><?php the_post_thumbnail() ?></a>
                                    </div>
                                    <div class="texto">
                                        <?php /*?><?php echo $p; echo $post->ID;  ?><?php */?>
                                        <div class="taxonomia">
                                            <?php $terms=get_the_terms( $post->ID, 'blog' ); 
                                            if($terms): foreach ($terms as $key => $term) :
                                                if($key>=1){echo ', ';} ; 
                                                echo '<a href="'.home_url( "/el-blog/" ).'?n='.$term->slug.'">'.$term->name.'</a>' ;
                                            endforeach; endif; ?> 
                                        </div>
                                        <div class="titular">
                                            <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                        </div>
                                        <div class="autor">
                                            <p><?php the_author_posts_link() ?></p>
                                        </div>
                                        <!--div class="fecha">
                                           <?php //la_fecha(get_post_meta($post->ID,'fecha',true),ICL_LANGUAGE_CODE);?>
                                        </div-->
                                        <div class="extracto">
                                            <p><?php the_excerpt() ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php  endif; wp_reset_postdata(); endfor; ?>
                	<!--/-->
            	</div>
            </div>
			<!-- / LISTADO DE NOTICIAS -->
   
            
            
            
            <!--VIDEO DESTACADO-->
            <?php 

                global $id_videoDestacado;
                
                 $args = array(
                    'posts_per_page'=> 1,
                    'post_type'     => 'videos',
                    'meta_key'      => 'video-destacado',
                    'meta_value'    => 1,
                    'meta_compare'  => '==',
                );
                // query
                $videoDestacado = new WP_Query($args);
                if ( $videoDestacado->have_posts() ) : while ( $videoDestacado->have_posts() ) : $videoDestacado->the_post();
                	$id_videoDestacado=$post->ID;
            ?>

            <div class="videosCont pantallaCompleta">
            	<div class="cerrar">X</div>
            	<div class="videosDesplegado"></div>
                <div class="imagenFondo">
                    <div class="id_video"><?php $contenidoDelCampo = get_post_meta($post->ID, 'id_video', true);echo $contenidoDelCampo;?></div>
                    <div class="imagen">
                        <a><?php the_post_thumbnail(); ?></a>
                    </div>
                    <div class="contenedorTexto">
                      <div class="texto">
                          <h2><?php  echo the_title(); ?></h2>
                          <p><?php the_content(); ?></p>
                          <div class="boton">
                            <button class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-modern vc_btn3-color-default"><?php  _e( 'Ver vídeo', 'wp-bootstrap-starter' ); ?></button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php endwhile; endif; wp_reset_postdata(); ?>            
            <!--VIDEO DESTACADO-->


             <!-- ENTRADAS DESTACADAS -->
             <div class="contenedor-gris">
                <div class="contenido-gris entradasDestacadas row">
                    <?php 
                        $id_cat=get_cat_id('destacada');
                        $args = array(
                            'posts_per_page'=> 6,
                            'post_type'     => 'post',
                            'cat'           => $id_cat,
                        );
                        $res = new WP_Query($args);
                        while ( $res->have_posts() ) : $res->the_post();
                    ?>
                    <!--modulo entrada destacada-->
                    <div class="itemListadoCont col-md-4">
                        <a href="<?php echo get_post_meta($post->ID,'url_link',true) ?>" target='_blank'>
                            <div class="itemListado">
                                <div class="imagen"><a><?php the_post_thumbnail() ?></a></div>
                                <div class="texto">                                
                                    <div class="titular">
                                        <h2><a href="<?php echo get_post_meta($post->ID,'url_link',true) ?>" target='_blank'><?php the_title() ?></a></h2>
                                    </div>                               
                                    <?php /*?><div class="extracto">
                                        <p><?php the_excerpt() ?></p>
                                    </div><?php */?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--/-->
                    <?php endwhile; ?>
                </div>
            </div>
            <!-- / ENTRADAS DESTACADAS -->


            <!--SUSCRIPCION-->
            <div class="suscripcionContenedor">
                <div class="suscripcion">
                    <h3 class="widget-title"><?php esc_html_e( 'Suscríbete a nuestra newsletter', 'wp-bootstrap-starter' ); ?></h3>
                    <?php if (!dynamic_sidebar('suscripcion')) {} ?>
                    <?php /*?><script src="//pre-newsletters.vocento.com/prospect/2800/js/prospect.js"></script>
                    <div class="widget-prospect" data-widget-id="5b56f1c4fa01f8f1f0f18f5a"></div><?php */?>
                </div>
            </div>
            <!-- / SUSCRIPCION-->


             <!--PUBLICIDAD-->
             <div class="contenido-gris publicidades">
                <div class="titularBut"><h3 style="height: 0px;padding: 0; margin: 0;"></h3> </div>
                <div class="page-template-page_home-recetas ultimas swiper-container tres" style="margin-top:2%">
                    <div class="swiper-wrapper">
                        <?php
                        $args = Array(
                        "post_type"=>"publicidades",
                        "posts_per_page"=>-1,
						);
						$query = new WP_Query( $args );
						while ( $query->have_posts() ) : $query->the_post(); 
						?>
                        
                            <div class="itemListado swiper-slide">
                                <div class="imagen">
                                    <a href="<?php  echo wp_specialchars_decode( get_field('url')); ?>" target="_blank">
                                        <img src="<?php  echo wp_specialchars_decode( get_field('banner')); ?>"/>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata();?>
                    </div>
        
                    <!-- Add Pagination -->
                      <div class="swiper-pagination tres"></div>
                      <!--/-->
                        <!-- Adelante - Atrás -->
                        <div class="swiper-button-prev tres"></div>
                        <div class="swiper-button-next tres"></div>
                        <!--/-->
                </div>

			</div>
            <!-- / PUBLICIDAD-->

            <!--VIDEOS-->
            <div class="titularBut"><h2 style="padding-bottom:25px;"><?php esc_html_e( 'Vídeos', 'wp-bootstrap-starter' ); ?></h2></div>

            <!-- SLIDE DE VIDEOS -->
            <div class="videosCont slide">
            <div class="cerrar">X</div>
            <div class="videosDesplegado"></div>


                <div class="cocineros swiper-container dos">



                <div class="swiper-wrapper">

                    <?php

                    $args = Array(
                        "post_type"=>"videos",
                        "post__not_in"=>array($id_videoDestacado),
                        "posts_per_page"=>-1,
                    );
                    $query = new WP_Query( $args );
                    while ( $query->have_posts() ) : $query->the_post();
                    ?>

                    <!--aquí el módulo a duplicar (3 en total)-->
                    <div class="itemListadoCont swiper-slide">
                        <div class="itemListado">
                            <div class="id_video"><?php $contenidoDelCampo = get_post_meta($post->ID, 'id_video', true);echo $contenidoDelCampo;?></div>
                            <div class="imagen">
                                <a><?php the_post_thumbnail(); ?></a>
                            </div>
                            <div class="texto">
                                <div class="titular">
                                    <h3><?php  echo the_title(); ?></h3>
                                </div>
                                <div class="extracto"><?php the_content(); ?></div>
                            </div>
                        </div>
                    </div>
                    <!--/-->

                    <?php  endwhile; wp_reset_postdata();?>

                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination dos"></div>
                <!--/-->
                <!-- Adelante - Atrás -->
                <div class="swiper-button-prev dos"></div>
                <div class="swiper-button-next dos"></div>
                <!--/-->
            </div>

            </div>
            <!-- / SLIDE DE VIDEOS -->

            <!--VIDEOS-->
            

            <!-- LISTADO DE COCINEROS -->
            <div class="contenido-gris cocineros swiper-container uno">
                
                <div class="titularBut"><h2><?php _e( '<em>Las</em> estrellas <em class="ocultar">de <strong>Reale Seguros Madrid Fusión</strong></em>', 'wp-bootstrap-starter' ); ?> 2020</h2></div>

                <div class="swiper-wrapper listadoCocineros">

                    <?php

                    $args = Array(
                        "post_type"=>"cocineros",
                        "posts_per_page"=> 4,
                        'meta_key'      => 'cocinero-destacado',
                        'meta_value'    => 1,
                        'meta_compare'  => '==',
                    );
                    $query = new WP_Query( $args );
                    while ( $query->have_posts() ) : $query->the_post(); 
                        $id_rest=get_post_meta($post->ID, 'rel_restaurante', true);
                    ?>

                    <div class="itemListadoCont swiper-slide">
                        <div class="itemListado">
                            <div class="imagen">
                                <a href="<?php echo get_permalink();?>"><?php the_post_thumbnail(); ?></a>
                            </div>
                            <div class="texto">
                                <div class="titular">
                                    <h3><a href="<?php echo get_permalink();?>"><?php  echo the_title(); ?></a></h3>
                                </div>
                                <?php if ( $id_rest[0]!=NULL ) : ?>
                                    <div class="titular2">
                                        <p><a href="<?php echo get_permalink($id_rest[0]);?>"><?php echo get_the_title($id_rest[0]); ?></a></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php  endwhile; wp_reset_postdata();?>

                </div>
                <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!--/-->
                <!-- Adelante - Atrás -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <!--/-->

            </div>
            <!-- / LISTADO DE COCINEROS -->
    

            
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() && !$enable_vc ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'wp-bootstrap-starter' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->

                    <!--SCRIPTS SLIDES-->
                    <script type="text/javascript">
                           window.onload = function () {
                               var configCocinerosHome = {
                                    on: {
                                        transitionStart: function () {
                                            if(swiper.slides.length == (this.activeIndex + 4))
                                            {
                                                var language = (pathArray.includes('en')) ? 'en' : 'es';
                                                var data = {
                                                    'action': 'getContentSwiperHome',
                                                    'offset': (this.activeIndex + 4),
                                                    'limit': 3,
                                                    'language': language,
                                                    'post_type': 'post'
                                                }
                                                var url = '/wp-content/ajaxrewrite.php';
                                                console.log(data);
                                                jQuery.post(url, data, function(response) {
                                                    swiper.appendSlide([response]);
                                                });
                                            }
                                        },
                                    },
                                };
    
                                Swiper.use(configCocinerosHome);

                                // COCINEROS
        
                              var swiper = new Swiper('.swiper-container.uno', {
                                slidesPerView: 4,
                                spaceBetween: 30,
                                // init: false,
                                //pagination: {
                                //  el: '.swiper-pagination',
                                //  clickable: true,
                                //},
                                navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                                },
								autoplay: {
								  delay: 4000,
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
                                if( swiper.slides.length <= 5) 
                                {
                                    var pathArray = window.location.pathname.split('/');
                                    var language = (pathArray.includes('en')) ? 'en' : 'es';
                                    var data = {
                                        'action': 'getContentSwiperHome',
                                        'offset': 0,
                                        'limit' : 5,
                                        'language': language,
                                        'post_type': 'post'
                                    }
                                    var url = '/wp-content/ajaxrewrite.php';
                                    console.log(data);
                                    jQuery.post(url, data, function(response) {
                                        // console.log('carga inicial:', response);
                                        swiper.appendSlide([response]);
                                    });
                                }
        
                              // VIDEOS
        
                              var swiper2 = new Swiper('.swiper-container.dos', {
                                slidesPerView: 3,
                                spaceBetween: 0,
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
                                    spaceBetween: 0,
                                  },
                                  600: {
                                    slidesPerView: 1,
                                    spaceBetween: 0,
                                  },
                                }
                              });
                              //swiper2.slideNext();
							  
							  
							  // PUBLICIDADES
        
                              var swiper3 = new Swiper('.swiper-container.tres', {
                                slidesPerView: 3,
                                spaceBetween: 30,
                                // init: false,
                                pagination: {
                                  el: '.swiper-pagination.tres',
                                  clickable: true,
                                },
                                navigation: {
                                nextEl: '.swiper-button-next.tres',
                                prevEl: '.swiper-button-prev.tres',
                                },
								autoplay: {
								  delay: 5000,
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
