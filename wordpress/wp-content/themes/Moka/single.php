<?php get_header();
setup_postdata($post);
?>


<section class="blog blog-template">
  <div class="father-contenedor">
    <div class="blog">
      <h1>
        <img src="<?php echo get_stylesheet_directory_uri()?>/img/blog.svg" alt="moka" class="logo-blog2">
      </h1>
    </div>

    <div class="row margintop0 marginbottom0" id="contenedor-grip-blog">
			<?php 
				$videoid=get_field("video_detalle");
				if($videoid && moka_get_video_url($videoid)){?>
				
					<iframe width="100%" height="650px" src="<?php echo moka_get_video_url($videoid) ?>" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
		
					
				<?php } else{ ?>
					<img src="<?php echo moka_get_image_detalle(get_the_ID()) ?>" alt="" class="blog-imgs" style="margin-top: 0px; width: 100%; height: 100%">
				<?php } ?>
				
			
            
    </div>

    <div class="row margintop0 marginbottom0" id="contenedor-grip-blog2">
      <div class="col-md-6 anchotablet" id="columm-A">
        <article class="grip">
          <h3>
           <?php the_title()?>
          </h3>
        </article>
      </div>
    </div>
  <div class="row margintop0 marginbottom0" id="contenedor-grip-blog">
    <div class="col-md-6 anchotablet" id="columm-A">
        <article class="grip">
          <p>
            Por  <?php echo ((get_field("autor"))?get_field("autor"): the_author())?>
          </p>
          
        </article>
      </div>
      <div class="col-md-6 anchotablet" id="columm-B">
        <article class="grip">
          <div class="fecha marginbottom1">
            <time >
            <?php the_date()?>
          </time>
          </div>
        </article>

        
      </div>
      
    </div>
    <div class="row margintop0" id="contenedor-grip-blog">
    <div class="col-md-6 anchotablet" id="columm-A">
		
		 <?php
			set_query_var( 'postsingle', $post );
            get_template_part('partials/post','gallery');		  
		?>
        
    </div>
    <div class="col-md-6 anchotablet" id="columm-B">
        <article class="grip">
          <?php the_content()?>
        </article>

        
      </div>
      
    </div>

    <div class="row margintop0" id="contenedor-grip-blog">
    <div class="col-md-6 anchotablet" id="columm-A">
        
    </div>
    <div class="col-md-6 anchotablet" id="columm-B">
        <article class="grip">
          <p class="negro">
  Compartir
          </p>
          <div>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo "http://".$_SERVER['HTTP_HOST'].get_the_permalink()?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri()?>/img/facebook.png" class="blog-iconos"></a>
             <a href="https://twitter.com/home?status=<?php echo "http://".$_SERVER['HTTP_HOST'].get_the_permalink()?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri()?>/img/twitter.png" class="blog-iconos"></a>
             <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo "http://".$_SERVER['HTTP_HOST'].get_the_permalink()?>&title=<?php the_title()?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri()?>/img/linkedin.png" class="blog-iconos"></a>

          </div>
          
        </article>

        
      </div>
      
    </div>

  <div class="line2"></div>

    <div class="boton-blog">
      <a class="btn btn-primary btn-lg" href="/blog/" id="btn-blog">VOLVER</a>

    </div>



  </div> 
</section>


<?php get_footer()?>
