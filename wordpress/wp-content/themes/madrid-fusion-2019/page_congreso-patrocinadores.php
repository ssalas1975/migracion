<?php
/**
* Template Name: Congreso - Patrocinadores
 */

get_header(); ?>

<?php get_template_part( 'template-parts/header', 'congreso' ); ?>

 <?php 

    $partners = get_posts( array(
        'fields' => 'ids',
        "post_type"=>"patrocinadores_new",
        "posts_per_page"=>-1,
    ));

    $partners_cumbre=array();
    foreach ($partners as $partner) {
        $rel_cumbre=get_post_meta($partner, 'rel_cumbre', true);
        if($rel_cumbre!=NULL){
            $id_cumbre=(string)$num_cumbre;
            $partners_cumbre[]=(in_array($id_cumbre, $rel_cumbre))?$partner:null;
        }
    }
    $partners_cumbre=array_filter($partners_cumbre);
    sort($partners_cumbre);

    // $partners_type = get_terms( "tipos_de_patrocinadores", array( "hide_empty" => false ) );
    // echo 'categorias: '.print_r($partners_type);

    if(ICL_LANGUAGE_CODE=='es'){ $partners_nombre = array('Organismos patrocinadores','Patrocinadores principales','Colaboradores / Patrocinadores','Ciudad Invitada Saborea España'); }
    else{ $partners_nombre = array('Sponsors Organizations','Main Sponsors','Sponsors Collaborators','Other Sponsors'); }
    
    ?>
 
<div class="container clear congresoPlantilla">
    	<div class="row">
    		<section id="primary" class="content-area col-sm-12">
        
                <main id="main" class="site-main page-template-page_home-cocineros" role="main">
        			
                        <?php if($año==$año_actual): ?>
							<div class="row patrocinadores contenedor-blanco">

                    			<h1 class="entry-title">
									<span><?php the_title(); echo (ICL_LANGUAGE_CODE=='es')?' de':' of'; ?> </span><?php echo get_the_title($num_cumbre) ?>
								</h1>

								<?php foreach ($partners_nombre as $key => $nombre) : 

								   $args = Array(
										"post_nombre"=>"patrocinadores_new",
										'post__in'=>$partners_cumbre,
										'tax_query' => array(
															array(
																'taxonomy'  => 'tipos_de_patrocinadores',
																'field'     => 'name',
																'terms'     => $nombre,
																'operator'  => 'IN'
															),
														),
										'order' => 'ASC',
										'meta_key' => 'orden',
										'orderby' => 'meta_value',
										'posts_per_page' => -1

									);
									$query = new WP_Query( $args ); ?> 


									<?php if($query->have_posts()&&$num_cumbre!=NULL&&count($partners_cumbre)>=1): ?>

										<div class="titPatrocinadores"><?php echo $nombre ?></div>

										<?php  while ( $query->have_posts() ) :$query->the_post();?>
                                        
											 <!--CÓDIGO PATROCINADORES-->                            
												<div class="itemListadoCont col-md-3 patrocinadores">
													<div class="itemListado center">
														<a href="<?php echo  get_field('url',$post->ID ,true) ?>" target="_blank">
															<?php 
																if( has_post_thumbnail() ) { the_post_thumbnail();
																} else { echo the_title(); }
															 ?>
														</a>
													</div>
												</div>
												<!-- / CÓDIGO PATROCINADORES--> 

										<?php  endwhile; wp_reset_postdata();?>


									<?php endif; ?>

								<?php endforeach; ?>

								<div style="margin-top:30px;clear:both;width:100%;padding:20px;">
									<?php echo wp_specialchars_decode( get_field( 'patrocinadores', $num_cumbre, true));  ?>
								</div>

							</div>

                        <?php else: ?> 
							<div class="patrocinadores contenedor-blanco">
								<h1 class="entry-title">
									<span><?php the_title(); echo (ICL_LANGUAGE_CODE=='es')?' de':' of'; ?> <span> <?php echo get_the_title($num_cumbre) ?>
								</h1>

								<!-- contenido --> 
								<?php 
									if(ICL_LANGUAGE_CODE=='es'){
										echo wp_specialchars_decode( get_field(get_query_var('pagename'), $num_cumbre, true)); 
									}else{
										switch(get_query_var('pagename')) {
											case 'workshops': $pagina='talleres'; break;
											case 'conferences-and-tastings': $pagina='conferencias-y-catas'; break;
											case 'contests-and-awards': $pagina='concursos-y-premios'; break;
											case 'exhibitors': $pagina='expositores'; break;
											case 'sponsors': $pagina='patrocinadores'; break;
										}
										echo wp_specialchars_decode( get_field($pagina, $num_cumbre, true)); 
									}
                                
								?>
								<!-- contenido -->
							</div>
                        <?php endif; ?>
                        
                    

        		</main><!-- #main -->
        	</section><!-- #primary -->

<?php
get_footer();
