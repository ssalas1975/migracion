	<section class="cabecera no_marge_b container">

        <span class="color_title color "></span>
        <span class="title">WON</span>

		<span class="cont-img">
			<img class="fondo" src="<?php echo get_the_post_thumbnail_url();?>">
		</span>
        
    </section>

    <section class="expertos container">

         <h1 class="title">
            <?php echo get_field('titulo') ?>
            <span class="high"><?php echo get_field('subtitulo_destacado') ?></span>
            <span class="low"><?php echo get_field('subtitulo') ?></span>
        </h1>
        <article><?php echo get_field('contenido_pagina') ?></article>
        
    </section>