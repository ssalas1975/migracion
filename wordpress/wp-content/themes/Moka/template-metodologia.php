<?php
/*
Template Name: Metodologia
*/
?>
<?php get_header()?>

<body>
 <script src="<?php echo get_stylesheet_directory_uri()?>/js/movimientos3.js"></script>

<section class="bienvenido-method metodologia-template">




    <div class="father-contenedor">

	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php endwhile; endif; ?>
	
	
    </div>      
 </section>        

<?php get_footer()?>