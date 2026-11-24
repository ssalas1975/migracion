<?php
/**
* Template Name: Congreso - Generica
 */

get_header(); ?>

<?php get_template_part( 'template-parts/header', 'congreso' ); ?>


<div class="container clear congresoPlantilla">    
	<div class="row">
		<section id="primary" class="content-area col-sm-12">
			<main id="main" class="site-main" role="main">
				<div class="contenido-blanco">
					<?php $name_talleres=(ICL_LANGUAGE_CODE=='es')?'talleres':'workshops'; ?>


					<?php if(get_query_var('pagename')==$name_talleres&&$año==$año_actual): ?>

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

					<?php else: ?> 

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

					<?php endif; ?>
            
			</div>
        </main>
	</section>
<?php
get_footer();
