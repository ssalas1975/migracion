<section class="listed-podcast-container">
	<h1 class="c-title-small <?php echo $classtitle; ?>">
		<?php echo _e($post->post_title, 'won2020_v2'); ?>
	</h1>
	<div class="block-container">
	<?php
	$podcasts = get_field('listado_podcasts');
    foreach ($podcasts as $podcast) { ?>
		<div class="listed-podcast">
			<img data-src="<?php echo $podcast['imagen_podcasts']["url"]; ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($podcast['titulo_podcast']))); ?>" loading="lazy">
			<h2><?php echo $podcast['titulo_podcast']; ?></h2>
			<p><?php echo $podcast['descripcion_podcast']; ?></p>
			<a href="<?php echo $podcast['url_podcast']; ?>" title="Escuchar <?php echo stripQuotes($podcast['titulo_podcast']); ?>" aria-label="Escuchar <?php echo stripQuotes($podcast['titulo_podcast']); ?>">Escuchar</a>
		</div>
    <?php } ?>
</div>
</section>

