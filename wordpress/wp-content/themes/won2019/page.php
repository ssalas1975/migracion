
<?php get_header(); ?>

<?php 
	$parent = get_post( $post->post_parent ); 
	$name_parent=$parent->post_name;
?>

	<main>

		<?php 
			if($name_parent=='home'): get_template_part( 'template-parts/content', 'home' );
			elseif($name_parent=='the-summit'): get_template_part( 'template-parts/content', 'thesummit' );
			elseif($name_parent=='speakers'): get_template_part( 'template-parts/content', 'speakers' );
			elseif($name_parent=='partners'): get_template_part( 'template-parts/content', 'partners' );
			elseif($name_parent=='expertos'||$name_parent=='expert-committee'): get_template_part( 'template-parts/content', 'expertos' );	
			elseif($name_parent=='donde'||$name_parent=='where'): get_template_part( 'template-parts/content', 'donde' );
			elseif($name_parent=='whos-who'): get_template_part( 'template-parts/content', 'whoiswho' ); 
			elseif($name_parent=='registrate'||$name_parent=='register'): get_template_part( 'template-parts/content', 'registrate' );
?>
		<?php elseif($name_parent=='pruebas'): ?>
			


		<?php else: ?>

			<h1 class="title">
	        	<?php echo get_field('titulo') ?><span class="low"><?php echo get_field('subtitulo') ?></span>
	        </h1>
	        <article class="contenido"><?php echo get_field('contenido_pagina') ?></article>
			
		<?php endif; ?>

	</main>

<?php get_footer(); ?>
