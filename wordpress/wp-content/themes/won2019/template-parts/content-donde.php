	<?php 

		$args = array(
            'posts_per_page' => -1,
            'post_type'     => 'plan',
            'order' => 'DESC' 
        );
        $planes = new WP_Query($args); 


	?>

	<!----------------------- google maps ----------------------------------->

		<section class="popup map">
			<span class="center">
				<div class="cierreX">X</div>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3038.04236404993!2d-3.696745584604347!3d40.4079122793661!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422628db3a07bd%3A0x309814d159babf71!2sMuseo+Nacional+Centro+de+Arte+Reina+Sof%C3%ADa!5e0!3m2!1ses!2ses!4v1551048389184" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
			</span>
		</section>

	<!----------------------- google maps ----------------------------------->


	<section class="cabecera no_marge_b container">

	    <span class="color_title color"></span>
	    <span class="title">WON</span>

		<span class="cont-img">
			<p class="claim"><span class="white"><?php _e( 'Madrid</span>/women<br>capital', 'won2019' ); ?></p>
			<?php if(count(get_field('imagenes'))>1):  ?>
				<div class="swiper-container">
				    <div class="swiper-wrapper">
				    	<?php if( have_rows('imagenes') ): while ( have_rows('imagenes') ) : the_row(); if(get_sub_field('imagen')):?>
							<div class="swiper-slide"><img class="fondo" src=" <?php  echo get_sub_field('imagen')['url']; ?> "></div>
						<?php  endif; endwhile; endif; ?>
				    </div>
				    <div class="bullets"></div>
				    <div class="arrow arrowLeft"></div>
					<div class="arrow arrowRight"></div>
				</div>
			<?php else: ?>
				<?php if( have_rows('imagenes') ): while ( have_rows('imagenes') ) : the_row(); if(get_sub_field('imagen')):?>
					<div class="swiper-slide"><img class="fondo" src=" <?php  echo get_sub_field('imagen')['url']; ?> "></div>
				<?php  endif; endwhile; endif; ?>
			<?php endif; ?>
		</span>
	    
	</section>

	<section class="donde container">

        <h1 class="title">
        	<?php echo get_field('titulo') ?>
        	<span class="high"><?php echo get_field('subtitulo_destacado') ?></span>
        	<span class="low"><?php echo get_field('subtitulo') ?></span>
        </h1>

		<?php if( have_rows('map') ):?>

			<ul class="where">
				<?php while ( have_rows('map') ) : the_row(); ?>
					<span class="cont-li">
						<li class="foto" style="background-image: url('<?php  echo get_sub_field('imagen')['url']; ?>')"></li>
						<li class="datos">
							<span class="center">
								<?php  echo get_sub_field('texto'); ?>
								<span class="line"></span>
								<a class="link map"><i class="icon-map"></i></a>
							</span>
						</li>
					</span>
				<?php endwhile; ?>
			</ul>

		<?php endif; ?>

		<ul class="lineas">

			<?php if( have_rows('emt') ):while ( have_rows('emt')): the_row(); ?>
				<span class="cont-li">
					<li class="foto" style="background-image: url('<?php  echo get_sub_field('imagen')['url']; ?>')"></li>
					<li class="datos">
						<span class="center">
							<?php  echo get_sub_field('texto'); ?>
							<span class="line"></span>
							<i class="icon-icon3"></i>
						</span>
					</li>
				</span>
			<?php endwhile; endif; ?>

			<?php if( have_rows('metro') ):while ( have_rows('metro')): the_row(); ?>
				<span class="cont-li">
					<li class="foto" style="background-image: url('<?php  echo get_sub_field('imagen')['url']; ?>')"></li>
					<li class="datos">
						<span class="center">
							<?php  echo get_sub_field('texto'); ?>
							<span class="line"></span>
							<i class="icon-icon3"></i>
						</span>
					</li>
				</span>
			<?php endwhile; endif; ?>

			<?php if( have_rows('renfe') ):while ( have_rows('renfe')): the_row(); ?>
				<span class="cont-li">
					<li class="foto" style="background-image: url('<?php  echo get_sub_field('imagen')['url']; ?>')"></li>
					<li class="datos">
						<span class="center">
							<?php  echo get_sub_field('texto'); ?>
							<span class="line"></span>
							<i class="icon-icon3"></i>
						</span>
					</li>
				</span>
			<?php endwhile; endif; ?>

		</ul>
	        
	</section>


	<?php if($planes->have_posts()): ?>
		<section class="planes container">	
			<h1 class="title">
	        	<?php echo get_field('titulo_planes') ?>
	        	<span class="low"><?php echo get_field('subtitulo_planes') ?></span>
	        </h1>

	        <ul>
	        	<?php while($planes->have_posts()): $planes->the_post() ?>
	        		<a href="<?php echo get_field('url'); ?>">
						<li class="imagen" style="background-image: url('<?php  echo get_the_post_thumbnail_url() ?>')"></li>
						<li class="texto">
							<div class="tit"><?php the_title() ?></div>
							<div class="more"><?php _e('Leer más...') ?></div>
						</li>
					</a>
	        	<?php endwhile; ?>
	        </ul>
		</section>
	<?php endif; ?>