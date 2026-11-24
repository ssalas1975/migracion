<?php
/*
Template Name: Nosotros
*/
?>
<?php get_header("home")?>

<body>
<script src="<?php echo get_stylesheet_directory_uri()?>/js/movimientos5.js"></script>

  <section>
   <div class="father-contenedor nosotros-template">

		<?php get_template_part('partials/header','menu');?> 
	</div> 
</section>


<section class="blog">
  <div class="father-contenedor2">
    <div class="nosotros-fondo">
      <div class="nosotros-fondo2">
        <img src="<?php echo get_stylesheet_directory_uri()?>/img/nosotros-background.png" alt="" class="about-image">
      </div>
    </div>

    <div class="row marginbottom0 about-parte1" id="contenedor-grip-blog2">
      <div class="col-md-6" id="columm-A">
        <article class="grip article-nosotros titulo-about">
          <h1>
            Equipo
          </h1>
        </article>
      </div>
    </div>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php endwhile; endif; ?>

  </div> 
</section>

<?php get_footer()?>