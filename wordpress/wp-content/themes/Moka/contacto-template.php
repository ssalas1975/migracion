<?php
/*
Template Name: Contacto
*/
?>

<?php get_header("home")?>
<body>
   <section class="contacto contacto-template">

    <?php get_template_part('partials/header','menu');?> 

     <div class="father-contenedor">   
         <div class="texto-encabezado text-center">
		 
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; endif; ?>
	
       
    
            </div>
        </div>
    </div>

        <div class="semicirculo-contact">
            <img src="<?php echo get_stylesheet_directory_uri()?>/img/semicirculo-contact.svg" alt="">
        </div>
    </section>
<?php get_footer()?>