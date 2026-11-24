      		
          <?php 

            global $año;
            global $cumbre;
            global $año_actual;
            global $num_cumbre;

            $año=(get_query_var('ano'))?get_query_var('ano'):$año_actual;

            ////recupero el id de la cumbre de este año
            $cumbre = get_posts( array(
                'fields' => 'ids',
                "post_type"=>"cumbres",
                "posts_per_page"=>-1,
                'meta_query' => array(
                    array(
                        'key' => 'fecha',
                        'value' => '^'.$año.'.*',
                        'compare' => 'REGEXP',
                    ),
                ),
            )) ;

            $idioma_cumbre0 = apply_filters( 'wpml_post_language_details', NULL, $cumbre[0] );
            $num_cumbre=(ICL_LANGUAGE_CODE==$idioma_cumbre0['language_code'])?$cumbre[0]:$cumbre[1];

          ?>

          <div class="imagenFondo cabeceraCongresos">
                <div class="itemListado">
                    <div class="imagen">
                        <a><?php echo get_post_meta( $num_cumbre, 'imagen-de-cabecera', true); ?> </a>
                    </div>
                </div>
                <div class="contenedorTexto">
                  <div class="texto">

                      <p class="edicion"><?php  _e( 'Edición', 'wp-bootstrap-starter' ); ?></p>
                      <p class="ano"><?php echo $año ?></p>

                      <?php if($año==$año_actual): ?>
                      	<?php
            							wp_nav_menu( array(
            							'theme_location'    => 'inscripcion',
            							'container'       => 'div',
            							'container_id'    => 'inscripcion',
            							'container_class' => 'inscripcion',
            							'menu_id'         => false,
            							'menu_class'      => 'navbar-nav',
            							'depth'           => 3,
            							'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
            							'walker'          => new wp_bootstrap_navwalker()
            						) );
            					?>
                    <?php endif; ?>

                    </div>
                </div>
      		</div>
            
            
<!--hamburguesa-->
<div class="menu-blog-contenedor hamburguesa-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">
			<div class="menu-blog hamburguesa-blog"><?php  _e( 'El evento', 'wp-bootstrap-starter' ); ?></div>
        </div>
    </div>
</div>
<!--menu-->
<div class="menu-blog-contenedor">
	<div class="contenedor-centrado">
    	<div class="contenido-centrado">
			<?php

                    $menu=(get_query_var('ano'))?'congresoEdicionesPasadas':'congreso';
					wp_nav_menu(array(
						'theme_location'  => $menu,
						'container'       => '',
						'container_id'    => 'congresoMenu',
						//'container_class' => 'collapse navbar-collapse justify-content-end',
						'menu_id'         => false,
						'menu_class'      => 'congresoMenu',
						'depth'           => 3,
						'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
						'walker'          => new wp_bootstrap_navwalker(),
						'link_before'=>'<h3>',
						'link_after'=>'</h3>'
					));
                ?>
        </div>
    </div>
</div>
<!--años-->
	<div class="menu-blog-contenedor congresoAnos">
        <div class="contenedor-centrado">
            <div class="contenido-centrado">
                <ul>
                    <?php 
                        for ($year=($año_actual-1); $year>2002 ; $year--) { 
                            $link=(ICL_LANGUAGE_CODE=='es')?'congreso/':'congress/';
                            echo '<li><a href="'.home_url('/').$link.get_query_var('pagename').'/'.$year.'/'.'">'.$year.'</a></li>';
                        }
                     ?>
                </ul>
                <script type="text/javascript">
                    var ano=<?php echo (get_query_var('ano'))?get_query_var('ano'):0; ?>;
                    jQuery(document).ready(function(){
                        if(ano){for(i=0; i<jQuery('.congresoMenu li a').length-1; i++){
                           jQuery('.congresoMenu li a')[i].href+=ano+'/'}
                        }
                    })
                </script>
            </div>
        </div>
    </div>