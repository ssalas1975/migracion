
	<?php get_header(); ?>

	<main>
		
		<!----------------------- Noticia ----------------------------------->
		<?php  if($post->post_type=='noticias'): ?>

		<section class="cabecera noticias container">
	        <span class="color_title"></span>
	        <span class="title">News</span>
	    </section>

		<section class="noticia news container ">
			<article class="contenido col">
				<div class="cont-img c2 col">
					<?php while(have_rows('imagenes-noticia')): the_row(); if(get_sub_field('imagen')!=''): ?>
                		<img src="<?php echo get_sub_field('imagen')['url'] ?>">
                	<?php endif; endwhile; ?>
                    <span class="imagen" style="background-image: url('<?php echo $img ?>')">
				</div>
				<div class="cont-texto c2">

					<?php next_post_link( '%link','<div class="arrow b arrowRight"></div>',FALSE ); ?>
					<h1><?php the_title() ?></h1>
					<div class="cont"><?php echo get_the_content() ?></div>
					<?php previous_post_link( '%link','<div class="arrow b arrowLeft"></div>',FALSE ); ?>

					<?php $enlace=(ICL_LANGUAGE_CODE == 'es')?icl_get_home_url().'/noticias/':icl_get_home_url().'/news/'; ?>
                	<a class="listado" href="<?php echo $enlace ?>"><?php _e( 'Leer más noticias...', 'won2019' ); ?></a>

				</div>
			</article>
			
		</section>

		<!----------------------- Noticia ----------------------------------->
		
		
		<!----------------------- Speaker ----------------------------------->
		<?php  elseif($post->post_type=='speaker'): ?>

			

	    <?php  endif;  ?>
		<!----------------------- Speaker ----------------------------------->
		
		
	</main>


<?php get_footer(); ?>



