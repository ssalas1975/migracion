	<section class="cabecera no_marge_b container">

        <span class="color_title color "></span>
        <span class="title">WON</span>

		<span class="cont-img">
			<p class="claim hello"><span class="white">Hello</span></p>
			<img class="fondo" src="<?php echo get_the_post_thumbnail_url();?>">
		</span>
        
    </section>

    <section class="who container">

        <h1 class="title">Who’s Who </h1>
        <article><?php echo get_field('contenido_pagina') ?></article>

        <h2><?php _e( 'Dinos en qué podemos ayudarte', 'won2019' ); ?></h2>
        <ul>
        	<li>
                <i class="icon-icon1"></i><h1><?php _e( 'Contacto WON Summit', 'won2019' ); ?></h1>
                <article><?php  echo get_field('contacto'); ?></article>
        	</li>
        	<li>
                <i class="icon-icon2"></i><h1><?php _e( 'Prensa WON Summit', 'won2019' ); ?></h1>
                <article><?php  echo get_field('prensa'); ?></article>
        	</li>
        </ul>
        
    </section>