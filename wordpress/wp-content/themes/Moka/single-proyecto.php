<?php get_header();
setup_postdata($post);
?>

<section class="proyecto-detalle-01">
<!--   <div class="father-contenedor"> -->

        <div class="titulos-proyectos">
          <h1><?php the_title()?></h1>
          <h2><?php echo get_field('subtitulo')?></h2>
        </div>

		<?php 
		$videoid=get_field("video_detalle");
		$image_detalle=moka_get_image_detalle(get_the_ID());
		if($image_detalle || $videoid){
			
			$style='style="background-image:url('.$image_detalle.')"';
			if($videoid)
				$style='';
			
		?>
        <div class="content-detalle-01" <?php echo $style ?>>
          <?php
			
			if($videoid && moka_get_video_url($videoid)){
				?>
				<iframe src="<?php echo moka_get_video_url($videoid)?>" width="100%" height="650px" frameborder="0" allowfullscreen></iframe>
			<?php }
			
		  ?>
        </div>
		<?php } ?>

        <div class="content-detalle-02">
		<?php the_content()?>
        </div>

    

        <div class="botones-navegacion-projects">
          <a href="javascript:history.back()">
            <div class="back-proyectos">Volver</div>
          </a>

          
          <div class="navegacion">
		  <?php 
			$pp=get_adjacent_post(false,'',true);
			$np=get_adjacent_post(false,'',false);
			
			
			if($pp && get_the_ID()!=$pp->ID){
			?>
             <div class="arrow-izq"></div>
			<?php }?>
			<?php 
			
			if($np && get_the_ID()!=$np->ID){
			?>
           <div class="arrow-drc"></div> 
			<?php }?>
			<?php 
		
			if($pp && get_the_ID()!=$pp->ID){
			?>
			<a class="next" href="<?php echo get_permalink($pp)?>">SIGUIENTE</a>
			<?php }?>
			<?php 
			
			if($np && get_the_ID()!=$np->ID){
			?>
			<a class="previous" href="<?php echo get_permalink($np)?>">ANTERIOR</a>
			<?php }?>
           
          </div>


        </div>

<!--    </div>   -->
</section>

<?php get_footer()?>