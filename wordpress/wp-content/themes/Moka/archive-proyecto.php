<?php get_header()?>
<script src="<?php echo get_stylesheet_directory_uri()?>/js/movimientos4.js"></script>


<section class="proyectos proyectos-template">
  <!-- <div class="father-contenedor"> -->

    <div class="proyectos-page-container">
      <div class="proyectos-title">
        <h1>Proyectos</h1>
      </div>
	
	<?php get_template_part('partials/projects','container');?>
                
            

    </div>
  <!-- </div>  -->
</section>


<?php get_footer()?>