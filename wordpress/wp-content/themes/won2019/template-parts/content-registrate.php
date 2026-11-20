	<section class="cabecera no_marge_b container">

        <span class="color_title color "></span>
        <span class="title">WON</span>

		<span class="cont-img">
			<p class="hello"><span class="white">&nbsp;</span></p>
			<img class="fondo" src="<?php echo get_the_post_thumbnail_url();?>">
		</span>
        
    </section>


	<!----------------------- newsletter ----------------------------------->

			<section class="popup popregister">
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

    <section class="who container">


        <article><?php echo get_field('contenido_pagina') ?></article>

       
        
    </section>