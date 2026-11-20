

    

<div class="section section-head">
   <p class="title">
    <?php global $post; if($post->post_name=='noticias'): ?>
      NOTICIAS
    <?php else: ?>
      MUJERES EXTRAORDINARIAS
    <?php endif; ?>
  </p>
   <span class="navi"><?php wp_pagenavi(); ?></span>
   </div>

	<!-- START: section -->
	<section class="section section-list">
	    <div class="wrap">
	    	<?php $type=$post->post_name; if (have_posts()):?>

				<?php while(have_posts()): the_post(); ?>

					<!--  <article class="item"> -->

                <?php 

                  if($type!='actividades'&&$type!='articulos'){ $link=get_the_permalink();
                  }else{
                    if( get_field('link')!='') $link=get_field('link');
                    if( get_field('pdf')!='') $link=get_field('pdf');
                  } 

                ?>
                
                <style> span.hover:hover{ border-bottom: 0px solid rgba(0,0,0,0) !important; }</style>

                <a href="<?php echo $link ?>" class="item">
                  <figure class="thumb">
                  	<?php if(get_the_post_thumbnail_url()!=''): ?>
                     		<img src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="thumb">
                  	<?php endif; ?>
                  </figure>
                  <div class="txt">
				             <?php $total=185; $contenido=$total-strlen(get_the_title()); ?>
                      <h3 class="title"><?php the_title() ?></h3>

                      <?php if($type!='actividades'&&$type!='articulos'): ?>

                        	<p class="subtitle"><?php echo excerpt(get_field('resumen',false,false),$contenido) ?></p>
                       		<span class="btn hover">Leer más</span>
                     	  	</div>	

                     	<?php else: ?>

                     			<p class="subtitle"><?php echo get_field('contenido',false,false) ?></p>

                     		</div>
                     		<?php if( get_field('link')!=''): ?>
                     			<span class="btn-suscribe hover" title="">Suscribe</span>
                     		<?php elseif( get_field('pdf')!=''): ?>
                     			<span class="btn-pdf hover" title="">PDF</span>
                     		<?php endif; ?>
                     		
                     	<?php endif; ?>
                </a>

              <!-- </article> -->

	        	<?php endwhile; ?>

			<?php endif; ?>

<div class="section section-head bottom">
      <span class="navi"><?php wp_pagenavi(); ?></span>
</div>
	    </div>
	</section>
	<!-- END: section -->
