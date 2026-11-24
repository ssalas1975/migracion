
<?php get_header(); ?>

	<?php //echo 'secc: '.$post->post_name; ?>

	<?php 
		if($post->post_name=='speakers') { get_template_part( 'template-parts/content', 'speakers' ); }
		else if($post->post_name=='whos-who') { get_template_part( 'template-parts/content', 'whoswho' ); }
		else if($post->post_name=='aviso-legal'||$post->post_name=='politica-de-privacidad'||$post->post_name=='politica-de-cookies') { get_template_part( 'template-parts/content', 'legal' ); }
		else{ get_template_part( 'template-parts/content', 'home' ); }
	?>

	<script>
		var secc="<?php if($post->post_name!='home'&&$post->post_name!='whos-who'&&$post->post_name!='aviso-legal'&&$post->post_name!='politica-de-privacidad'&&$post->post_name!='politica-de-cookies') { 
			wp_reset_query(); echo $post->post_name; 
		} ?>";
		// var secc='';
	</script>

	<?php dynamic_sidebar( 'registrate' ); ?>

<?php get_footer(); ?>

