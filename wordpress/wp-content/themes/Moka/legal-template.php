<?php
/*
Template Name: Legal
*/
?>

<?php get_header("home")?>
<body>
   <section class="contacto contacto-template legaltemplate">

    <?php get_template_part('partials/header','menu');?>

     <div class="father-contenedor">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; endif; ?>
    </div>
    </section>
<?php get_footer()?>