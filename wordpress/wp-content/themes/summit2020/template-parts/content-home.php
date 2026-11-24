   
    <!-- START: content-->
    <main class="content">

         <!-- START: section intro -->
          <?php  $page=get_page_by_path( 'home' ); $page = get_page(icl_object_id($page->ID, 'page', true)); ?>
         <section class="section section-intro" data-type="scene" data-scene-name="scene-intro" data-url="" >
             <div class="section__container">
                 <div class="section__claim">
                     <?php echo get_field('titulo',$page->ID); ?>
                 </div>
                 <div class="date">
                    <?php echo get_field('date',$page->ID); ?>
                 </div>
                 <p class="hashtag">#SantanderWON20</p>
                 <div class="shape">
                     <div class="rotate-180">
                     <div class="shape-1 ellipse ellipse-cyan">
                         <div class="ellipse ellipse-red"></div>
                     </div>
                     </div>
                     <div class="shape-2 ellipse ellipse-cyan">
                         <div class="ellipse ellipse-yellow"></div>
                     </div>
                 </div>
                 <button class="btn-summit" data-type="nav" data-nav-scroll-to="section-the-summit"><span>SCROLL DOWN</span><svg aria-hidden="true" focusable="false" data-prefix="fal" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M252.485 343.03l-7.07-7.071c-4.686-4.686-12.284-4.686-16.971 0L145 419.887V44c0-6.627-5.373-12-12-12h-10c-6.627 0-12 5.373-12 12v375.887l-83.444-83.928c-4.686-4.686-12.284-4.686-16.971 0l-7.07 7.071c-4.686 4.686-4.686 12.284 0 16.97l116 116.485c4.686 4.686 12.284 4.686 16.971 0l116-116.485c4.686-4.686 4.686-12.284-.001-16.97z" class=""></path></svg></button>
             </div>
         </section>
         <!-- END: section -->


        <!-- START: section the-summit -->
        <?php  $page=get_page_by_path( 'the-summit' ); $page = get_page(icl_object_id($page->ID, 'page', true));  ?>
         <section class="section section-the-summit" data-type="scene" data-scene-name="scene-summit" data-url="the-summit">
             <div class="section__container">
                 <div class="section__claim">
                    <?php echo get_field('titulo',$page->ID); ?>
					<p class="hashtag">#SantanderWON20</p>
                 </div>
                 <div class="shape">
                     <div class="shape-1 ellipse ellipse-cyan rotate-180">
                         <div class="ellipse ellipse-red"></div>
                     </div>
                     <div class="shape-2 ellipse ellipse-cyan">
                         <div class="ellipse ellipse-yellow"></div>
                     </div>
                 </div>
                 <article class="txt">
                    <?php echo wpautop( $page->post_content ); ?>
                 </article>
             </div>
         </section>
         <!-- END: section -->
         

         <!-- START: section agenda-->
          <?php  $page=get_page_by_path( 'agenda' ); $page = get_page(icl_object_id($page->ID, 'page', true)); ?>
         <section class="section section-agenda" data-type="scene" data-scene-name="scene-agenda" data-url="agenda">
             <div class="section__container">
                 <div class="section__claim">
                     <span><?php echo get_field('titulo',$page->ID); ?></span>
					 <p class="hashtag">#SantanderWON20</p>
                 </div>
                 <div class="shape shape-1"></div>
                 <div class="shape shape-2"></div>
                 <nav class="txt">
                     <p class="txt__title"><?php echo get_field('titulo-pagina',$page->ID); ?></p>
                     <div class="flip-card flip-card-1" data-type="modal" data-modal-content="agenda-won-inspiring">
                       <div class="flip-card-inner">
                         <div class="flip-card-front">
                           <p class="btn btn-cyan"><span>Won</span><span>inspiring</span></p>
                         </div>
                         <div class="flip-card-back"> <p class="btn btn-grey"><span>AGENDA</span></p> </div>
                       </div>
                     </div>
                     <div class="flip-card flip-card-2" data-type="modal"  data-modal-content="agenda-won-talks">
                       <div class="flip-card-inner">
                         <div class="flip-card-front">
                           <p class="btn btn-yellow"><span>Won</span><span>talks</span></p>
                         </div>
                         <div class="flip-card-back"> <p class="btn btn-grey"><span>AGENDA</span></p> </div>
                       </div>
                     </div>
                     <div class="flip-card flip-card-3" data-type="modal"  data-modal-content="agenda-won-innospace">
                       <div class="flip-card-inner">
                         <div class="flip-card-front">
                           <p class="btn btn-red"><span>Won</span><span>innospace</span></p>
                         </div>
                          <div class="flip-card-back"> <p class="btn btn-grey"><span>AGENDA</span></p> </div>
                       </div>
                     </div>
                     <div class="flip-card flip-card-4" data-type="modal"  data-modal-content="agenda-acto-inaugural">
                       <div class="flip-card-inner">
                         <div class="flip-card-front">
                           <p class="btn btn-black" ><span>Acto</span><span>inaugural</span></p>
                         </div>
                         <div class="flip-card-back"> <p class="btn btn-grey"><span>AGENDA</span></p> </div>
                       </div>
                     </div>
                     <article class="tip tip-1">
                         <h1 class="title" style="display:none;">WON INSPIRING</h1>
                         <p class="subtitle"><?php echo get_field('inspiring',$page->ID); ?></p>
                     </article>
                     <article class="tip tip-2">
                         <h1 class="title" style="display:none;">WON  TALKS</h1>
                         <p class="subtitle"><?php echo get_field('talks',$page->ID); ?></p>
                     </article>
                     <article class="tip tip-3">
                         <h1 class="title" style="display:none;">WON  INNOSPACE</h1>
                         <p class="subtitle"><?php echo get_field('innospace',$page->ID); ?></p>
                     </article>
                     <article class="tip tip-4">
                         <h1 class="title" style="display:none;">ACTO INAUGURAL</h1>                         
                     </article>
                     <!-- <a class="txt__modal" href="#" title="">Ver toda la agenda</a> -->
                 </nav>
             </div>
         </section>
         <!-- END: section -->

         <!-- START: section speakers -->
          <?php 
            $page=get_page_by_path( 'speakers' ); $page = get_page(icl_object_id($page->ID, 'page', true)); 
            $GLOBALS['speaker_featured1']=new WP_Query(array('post_type' => 'speaker_cpt', 
                                                'meta_query' => array( array( 'key' => 'speaker_destacado1','value' => 1 )),
                                                'posts_per_page' => 1));
            $GLOBALS['speaker_featured2']=new WP_Query(array('post_type' => 'speaker_cpt', 
                                                'meta_query' => array( array( 'key' => 'speaker_destacado2','value' => 1 )),
                                                'posts_per_page' => 1)); 
            $GLOBALS['speaker_featured3']=new WP_Query(array('post_type' => 'speaker_cpt', 
                                                'meta_query' => array( array( 'key' => 'speaker_destacado3','value' => 1 )),
                                                'posts_per_page' => 1)); 

          ?>
         <section class="section section-speakers" data-type="scene" data-scene-name="scene-speakers" data-url="speakers">
             <div class="section__container">
                 <div class="section__claim">
                     <?php echo get_field('titulo',$page->ID); ?>
					 <p class="hashtag">#SantanderWON20</p>
                 </div>

                <?php while($GLOBALS['speaker_featured1']->have_posts()): $GLOBALS['speaker_featured1']->the_post(); ?>

                     <article class="card card-speaker speaker-1">
                         <figure class="card-speaker__top" data-type="modal" data-modal-content="speaker-<?php global $post; echo $post->post_name.'-'.get_field('apellidos'); ?>">
                              <img src="<?php echo get_the_post_thumbnail_url() ?>">
                         </figure>
                         <div class="card-speaker__middle">
                             <h1 class="card-speaker__middle-name"><?php echo get_the_title().' '.get_field('apellidos'); ?></h1>
                             <p class="card-speaker__middle-position"><?php echo get_field('cargo'); ?></p>
                             <p class="card-speaker__middle-description"><?php echo strip_tags(get_field('entradilla')); ?></p>
                         </div>
                     </article>

                <?php endwhile; ?>


                <?php while($GLOBALS['speaker_featured2']->have_posts()): $GLOBALS['speaker_featured2']->the_post(); ?>

                     <article class="card card-speaker speaker-3">
                         <figure class="card-speaker__top" data-type="modal" data-modal-content="speaker-<?php global $post; echo $post->post_name.'-'.get_field('apellidos'); ?>">
                              <img src="<?php echo get_the_post_thumbnail_url() ?>">
                         </figure>
                         <div class="card-speaker__middle">
                             <h1 class="card-speaker__middle-name"><?php echo get_the_title().' '.get_field('apellidos'); ?></h1>
                             <p class="card-speaker__middle-position"><?php echo get_field('cargo'); ?></p>
                             <p class="card-speaker__middle-description"><?php echo strip_tags(get_field('entradilla'));  ?></p>
                         </div>
                     </article>

                <?php endwhile; ?>


                <?php while($GLOBALS['speaker_featured3']->have_posts()): $GLOBALS['speaker_featured3']->the_post(); ?>

                     <article class="card card-speaker speaker-2">
                         <figure class="card-speaker__top" data-type="modal" data-modal-content="speaker-<?php global $post; echo $post->post_name.'-'.get_field('apellidos'); ?>">
                              <img src="<?php echo get_the_post_thumbnail_url() ?>">
                         </figure>
                         <div class="card-speaker__middle">
                             <h1 class="card-speaker__middle-name"><?php echo get_the_title().' '.get_field('apellidos'); ?></h1>
                             <p class="card-speaker__middle-position"><?php echo get_field('cargo'); ?></p>
                             <p class="card-speaker__middle-description"><?php echo strip_tags(get_field('entradilla'));  ?></p>
                         </div>
                     </article>

                <?php endwhile; ?>

                 <a href="<?php echo esc_url(home_url('/speakers/')); ?>" class="btn-partners"><span>See</span> <span>More</span> <span>Speakers</span></a>
             </div>
         </section>
         <!-- END: section -->

         <!-- START: section partners -->
         <section class="section section-partners" data-type="scene" data-scene-name="scene-partners"  data-url="partners">
             <div class="section__container">
                 <div class="section__claim">
                     <span>building</span>
                     <span>a new </span>
                     <span>world</span>
                     <span>together</span>
					 <p class="hashtag">#SantanderWON20</p>
                 </div>                 
				 <div class="btn-partners" data-type="modal" style="z-index:1500" data-modal-content="partners"><span>See Our</span><span>Partners</span></div>
                 <figure class="logo-partners">
                     <img src="/wp-content/themes/summit2020/assets/img/partner/partners.png">
                     <figcaption data-type="modal"  data-modal-content="partners"><span>See Our</span><span>Partners</span></figcaption>
                 </figure>
             </div>
              <div class="shape-1 ellipse ellipse-cyan rotate-90">
                 <div class="ellipse ellipse-yellow"></div>
             </div>
              <div class="shape-2 ellipse ellipse-cyan rotate-270">
                 <div class="ellipse ellipse-red"></div>
             </div>
         </section>
         <!-- END: section -->

        

         <!-- START: section donde -->
         <?php  $page=get_page_by_path( 'donde' ); $page = get_page(icl_object_id($page->ID, 'page', true)); ?>
         <section class="section section-donde" data-type="scene" data-scene-name="scene-where" data-url="donde">
             <div class="section__container">
                 <div class="section__claim">
                     <?php echo get_field('titulo',$page->ID); ?>
					 <p class="hashtag">#SantanderWON20</p>
                 </div>
                 <p class="literal"><?php _e('Dónde...','summit2020_wpml'); ?></p>
                 <div class="txt">
                     <!-- <p class="txt__title">Donde</p> -->
                     <article class="card card-where">
                         <figure class="card-where__thumb">
                               <img src="<?php echo get_template_directory_uri(); ?>/assets/img/donde/museo.jpg" alt="">
                         </figure>
                     </article>
                     <article class="card card-where">
                         <div class="card-where__txt">
                             <h1 class="title"><?php _e('<span>Museo</span> <span>Reina</span> <span>Sofia</span>','summit2020_wpml'); ?></h1>
                             <p class="claim-1"><?php _e('Auditorios <br>Edificio Nouvel','summit2020_wpml'); ?></p>
                             <p class="claim-2"><?php _e('Entrada por ronda de atocha s/n','summit2020_wpml'); ?></p>
                             <a class="link" data-type="modal" data-modal-content="where"><span><?php _e('Cómo llegar','summit2020_wpml'); ?></span></a>
                         </div>
                     </article>
                 </div>
                 <div class="shape shape-1"></div>
                 <div class="shape shape-2 rotate-270"></div>
				 <a href="<?php _e('https://registro.womennow.es/wm2020/es/register/Register/Summit','summit2020_wpml'); ?>" class="btn-registro"><?php _e('Regístrate','summit2020_wpml'); ?></a>
             </div>
         </section>
         <!-- END: section -->

		<a href="<?php _e('https://registro.womennow.es/wm2020/es/register/Register/Summit','summit2020_wpml'); ?>" class="btn-registro"><?php _e('Regístrate','summit2020_wpml'); ?></a>
    </main>
    <!-- END: content -->