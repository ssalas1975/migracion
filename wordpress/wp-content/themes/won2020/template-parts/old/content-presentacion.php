
    
	
     <!-- START: section -->
     <section class="section section-intro">
          <h1 class="title"><?php echo get_field('intro_pagina') ?></h1>
     </section>
     <!-- END: section -->
     <!-- START: section -->
     <section class="section section-values">
         <article class="wrap">
             <p class="subtitle">Presentación</p>
             <div class="txt">
                 <?php the_content();//apply_filters( 'the_content', get_the_content() ); ?>
             </div>
         </article>
     </section>
     <!-- END: section -->
      <!-- START: section -->
     <section class="section">
         <div class="slide" data-type="slide" data-slide-autoplay="true">
             <div class="slide__wrap ">
                <?php if( have_rows('slide') ): while ( have_rows('slide') ) : the_row(); if(get_sub_field('imagen')):?>
                    <a href="#" title=""><figure class="thumb">
                          <img src="<?php  echo get_site_url().get_sub_field('imagen')['url']; ?>" alt="summit 2019 - 1">
                     </figure></a>
                <?php  endif; endwhile; endif; ?>
             </div>
         </div>
     </section>
     <!-- END: section -->
