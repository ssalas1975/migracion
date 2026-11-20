
	<?php 

		if($post->post_name=='won-inspiring'){
			$tit='<p class="claim"><span class="byellow">Won</span>/ins<br>piring</p>'; $color='byellow'; $summit='inspiring';
		}elseif($post->post_name=='won-talks'){
			$tit='<p class="claim"><span class="bblue">Won</span>/talks</p>'; $color='bblue'; $summit='talks';
		}elseif($post->post_name=='won-innospace'){
			$tit='<p class="claim"><span class="bpink">Won</span>/inno<br>space</p>'; $color='bpink'; $summit='startups';
		}else{
			$tit='<p class="claim onlywon"><span class="white">Won</span></p>'; $color='';
		}

		$args_viernes = array(
            'posts_per_page'=> -1,
            'post_type'     => 'evento',
            'tax_query' => array(
            	'relation' => 'AND',
		        array( 'taxonomy' => 'dia','field' => 'slug','terms' => 'viernes' ),
		        array( 'taxonomy' => 'summit','field' => 'slug', 'terms' => $summit ),
		    ), 'order'         => 'ASC',
        );
        $args_sabado = array(
            'posts_per_page'=> -1,
            'post_type'     => 'evento',
            'tax_query' => array(
            	'relation' => 'AND',
		        array( 'taxonomy' => 'dia','field' => 'slug','terms' => 'sabado' ),
		        array( 'taxonomy' => 'summit','field' => 'slug', 'terms' => $summit ),
		    ), 'order'         => 'ASC',
        );

        $events_viernes = new WP_Query($args_viernes);
        $events_sabado = new WP_Query($args_sabado);

		function getEvents($events,$color){ ?>
			<?php while ($events->have_posts()): $events->the_post() ;?>
				<article>
					<div class="horario <?php echo $color ?>">
						<?php if( have_rows('grupo') ):while ( have_rows('grupo')): the_row(); ?>
		    				<?php echo get_sub_field('inicio') ?><span>-</span>
		    				<?php echo get_sub_field('fin') ?>
		    			<?php endwhile; endif; ?>
					</div>
					<div class="contenido <?php echo $color ?>">
						<h1 class="<?php echo $color ?>"><?php the_title() ?></h1>
						<?php echo get_field('texto') ?>
					</div>
				</article>
			<?php endwhile; }?>

	<!----------------------- cabecera ----------------------------------->
	<section class="cabecera no_marge_b container">

        <span class="color_title color <?php echo $color; ?>"></span>
        <span class="title">WON</span>

		<span class="cont-img">
			<?php echo $tit; ?>
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
        
        <nav>
        	<p>
        		<?php  if($post->post_parent == 0): ?>
        			<span><?php echo get_field('txt_imagen');?></span><img src="<?php echo get_template_directory_uri();?>/images/arrow_summit.png">
        		<?php else: ?>
        			<?php echo get_field('txt_imagen');?>
        		<?php endif; ?>
        	</p>
        	<?php wp_nav_menu(array( 'theme_location' => 'menu-summit','container' => 'ul', 'menu_class' => 'menu_summit '.$color)); ?>
        </nav>
        
    </section>
	<!----------------------- cabecera ----------------------------------->


    <!----------------------- inspiring ----------------------------------->
	<?php if($post->post_name=='won-inspiring'): ; ?>

	    <section class="summit container">

	        <h1 class="title">
	        	<span class="color byellow"></span>
	        	<?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
	        </h1>
	        <div class="dias inspiring">
	        	<span class="color byellow"></span>
	        	<ul>
		        	
		        	<li class="fecha">
		        		<h1 class="date"><?php _e( 'Viernes', 'won2019' ); ?><span>29</span><?php _e( 'Marzo', 'won2019' ); ?> 2019</h1>
		        		<?php if( $events_viernes->have_posts() ) : ?>
							<?php getEvents($events_viernes,'byellow') ?>
			        	<?php endif; ?>
		        	</li>

		        	<?php  if( !$events_viernes->have_posts() ) : ?>
						<div class="prox inspiring center">
							<span><?php _e( '<strong>Programa/Agenda</strong>completa próximamente', 'won2019' ); ?></span>
						</div>
		        	<?php else: ?>
						<div class="lugar inspiring center">
							<h1><?php _e('Edificio Nouvel.<span>Auditorio 400.</span>' , 'won2019') ?></h1>
							<p> <?php echo _e('Programa provisional. <span>Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones</span>' , 'won2019') ?></p>
						</div>
		        	<?php endif; ?>

		        </ul>
	        </div>
	        <span class="clear"></span>
	    </section>
	    <section class="programa"><?php dynamic_sidebar('Link Programa'); ?></section>
	<!----------------------- inspiring ----------------------------------->


	<!----------------------- talks ----------------------------------->
	<?php elseif($post->post_name=='won-talks'): ; ?>

	    <section class="summit container">

	        <h1 class="title">
	        	<span class="color bblue"></span>
	        	<?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
	        </h1>
	        <div class="dias">
	        	<span class="color bblue"></span>
	        	<ul>

		        	<li class="fecha">
		        		<h1 class="date"><?php _e( 'Viernes', 'won2019' ); ?><span>29</span><?php _e( 'Marzo', 'won2019' ); ?> 2019</h1>
						<?php if( $events_viernes->have_posts() ) : ?>
			        		<?php getEvents($events_viernes,'bblue') ?>
			        	<?php endif; ?>	
		        	</li>
		        	
		        	<li class="fecha">
		        		<h1 class="date"><?php _e( 'Sábado', 'won2019' ); ?><span>30</span><?php _e( 'Marzo', 'won2019' ); ?> 2019</h1>
		        		<?php if( $events_sabado->have_posts() ) : ?>
							<?php getEvents($events_sabado,'bblue') ?>
			        	<?php endif; ?>
		        	</li>

		        	<?php  if( !$events_viernes->have_posts()&&!$events_sabado->have_posts() ) : ?>
						<div class="prox center">
							<span><?php _e( '<strong>Programa/Agenda</strong>completa próximamente', 'won2019' ); ?></span>
						</div>
					<?php else: ?>
						<div class="lugar center">
							<h1><?php _e('Edificio Nouvel.<span>Auditorio 200.</span>' , 'won2019') ?></h1>
							<p> <?php echo _e('Programa provisional. <span>Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones</span>' , 'won2019') ?></p>
						</div>
		        	<?php endif; ?>

		        </ul>
	        </div>
	        <span class="clear"></span>
	    </section>
	   <section class="programa"><?php dynamic_sidebar('Link Programa'); ?></section>

	    
	<!----------------------- talks ----------------------------------->



	<!----------------------- innoSpace ----------------------------------->
	<?php elseif($post->post_name=='won-innospace'): ; ?>

	    <section class="summit container">

	        <h1 class="title">
	        	<span class="color bpink"></span>
	        	<?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
	        </h1>
	        <div class="dias">
	        	<span class="color bpink"></span>
	        	<ul>

		        	<li class="fecha">
		        		<h1 class="date"><?php _e( 'Viernes', 'won2019' ); ?><span>29</span><?php _e( 'Marzo', 'won2019' ); ?> 2019</h1>
						<?php if( $events_viernes->have_posts() ) : ?>
			        		<?php getEvents($events_viernes,'bpink') ?>
			        	<?php endif; ?>	
		        	</li>
		        	
		        	<li class="fecha">
		        		<h1 class="date"><?php _e( 'Sábado', 'won2019' ); ?><span>30</span><?php _e( 'Marzo', 'won2019' ); ?> 2019</h1>
		        		<?php if( $events_sabado->have_posts() ) : ?>
							<?php getEvents($events_sabado,'bpink') ?>
			        	<?php endif; ?>
		        	</li>

		        	<?php  if( !$events_viernes->have_posts()&&!$events_sabado->have_posts() ) : ?>
						<div class="prox center">
							<span><?php _e( '<strong>Programa/Agenda</strong>completa próximamente', 'won2019' ); ?></span>
						</div>
		        	<?php else: ?>
						<div class="lugar center">
							<h1><?php _e('Edificio Nouvel.<span>Sala Protocolo, 6ªplanta.</span>' , 'won2019') ?></h1>
							<p> <?php echo _e('Programa provisional. <span>Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones</span>' , 'won2019') ?></p>
						</div>
		        	<?php endif; ?>

		        </ul>
	        </div>
	        <span class="clear"></span>
	    </section>
	    <section class="programa"><?php dynamic_sidebar('Link Programa'); ?></section>

	<!----------------------- innoSpace ----------------------------------->



	<!------------------- the summit - raiz ----------------------------->
	<?php else: ; ?>

		<!----------------------- newsletter ----------------------------------->

			<section class="popup popsummit">
				<span class="center">
					<div class="cierreX">X</div>
					<article class="aside">
						<span class="color"></span>
						<img class="logo" src="<?php echo get_template_directory_uri();?>/images/logo.png">
						<img class="claim" src="<?php echo get_template_directory_uri();?>/images/claim_b.png">
					</article>
					<?php if(ICL_LANGUAGE_CODE=='es'): ?>

						<article class="form init" style="display:block">
							<h1>¡Suscríbete a nuestra newsletter!</h1>
							<p>Recibe en tu email toda la información de <strong>#SantanderWON19:</strong> noticias de interés, novedades de agenda y speakers, información actualizada...</p>
							<input type="text" name="email" placeholder="Introduce tu email">
							<div class="mensaje"></div>
							<div class="envio">Enviar</div>
							<div class="cierre">No gracias!, prefiero seguir navegando</div>
						</article>
						<article class="form close" style="display:none">
							<h2>Ya estás registrado/a en la newsletter de <strong>#SantanderWON19</strong></h2>
							<p> Te hemos enviado un email de confirmación<br>¡Gracias!</p>
							<p><b>Equipo WON Summit</b></p>
						</article>

					<?php else: ?>

						<article class="form init" style="display:block">
							<h1>Subscribe to our newsletter!</h1>
							<p>Get all the information on <strong>#SantanderWON19:</strong> straight to your inbox: interesting news stories, the latest news on the agenda and speakers, updated information, etc.</p>
							<input type="text" name="email" placeholder="Enter your email">
							<div class="mensaje"></div>
							<div class="envio">Send</div>
							<div class="cierre">No thanks, I prefer to keep browsing</div>
						</article>
						<article class="form close" style="display:none">
							<h2>You have already registered with <strong>#SantanderWON19</strong></h2>
							<p> We’ve sent you a confirmation email <br>Thanks!</p>
							<p><b>The WON Summit Team</b></p>
						</article>

					<?php endif; ?>
				</span>
			</section>

		<!----------------------- newsletter ----------------------------------->

		<!----------------------- popup video ----------------------------------->

            <script src="<?php echo get_template_directory_uri(); ?>/js/froogaloop.min.js"></script>
            <section class="popup video on">
                <span class="center" style="background-color: #000">
                    <div class="cierreX video">X</div>
                    <div style="padding:56.25% 0 0 0;position:relative;">
                        <iframe id="iframe" src="https://player.vimeo.com/video/329792354?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                    <script src="https://player.vimeo.com/api/player.js"></script>
                </span>
            </section>

        <!----------------------- popup video ----------------------------------->


	    <section class="summit container">

	        <h1 class="title">
	        	<?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
	        </h1>
	        <article class="contenido"><?php echo get_field('contenido_pagina') ?></article>
	    	<article class="hastag"><?php echo get_field('hastag') ?></article>
	        
	    </section>
	<!------------------- the summit - raiz ----------------------------->
	<?php endif; ?>