    
    <!-- CONTACTO -->
    <!-- CONTACTO -->
    <?php  $page=get_page_by_path( 'contacto' ); $page = get_page(icl_object_id($page->ID, 'page', true)); ?>
    <aside class="modal contact">
        <div class="modal-content w1032">
            <span class="modal-content__close">Cerrar</span>

            <div class="modal-content-body-two-cols">
                <h1 class="modal-content__title center fs40 full"><?php echo get_field('titulo',$page->ID); ?></h1>
                <article class="card card-contact">
                    <figure class="thumb"><img src="<?php echo get_field('contacto-field',$page->ID)['imagen']['url']; ?>" alt="icono contacto"/></figure>
                    <div class="txt">
                        <h1 class="title"><?php echo get_field('contacto-field',$page->ID)['titulo']; ?></h1>
                        <?php echo get_field('contacto-field',$page->ID)['texto']; ?>
                    </div>
                </article>
                <article class="card card-contact">
                    <figure class="thumb"><img src="<?php echo get_field('prensa',$page->ID)['imagen']['url']; ?>" alt="icono contacto"/></figure>
                    <div class="txt">
                        <h1 class="title"><?php echo get_field('prensa',$page->ID)['titulo']; ?></h1>
                        <?php echo get_field('prensa',$page->ID)['texto']; ?>
                    </div>
                </article>
            </div>
        </div>
    </aside>

     <?php global $post;  if($post->post_name!='speakers'): ?>

        <!-- AGENDA -->
        <!-- AGENDA -->
        <?php $terms = get_terms([ 'taxonomy' => 'tipo_agenda', 'hide_empty' => false ]); //print_r($terms); ?>
        <?php foreach ($terms as $key => $term): ?>

            <aside class="modal agenda-<?php echo $term->slug; ?>">
                <div class="modal-content w1032">
                    <span class="modal-content__close">Cerrar</span>
                    <h1 class="modal-content__title center">
                        <?php 
                            $page=get_page_by_path( 'agenda' ); $page = get_page(icl_object_id($page->ID, 'page', true));
                            if($term->slug=='won-innospace') echo get_field('titulo-popup-innospace',$page->ID); 
                            if($term->slug=='won-inspiring') echo get_field('titulo-popup-inspiring',$page->ID); 
                            if($term->slug=='won-talks') echo get_field('titulo-popup-talks',$page->ID); 
                            if($term->slug=='acto-inaugural') echo get_field('titulo-popup-acto',$page->ID); 
                        ?> 
                    </h1>
                    <p class="modal-content__subtitle center">
                        <?php 
                            if($term->slug=='won-innospace') echo get_field('subtitulo-popup-innospace',$page->ID); 
                            if($term->slug=='won-inspiring') echo get_field('subtitulo-popup-inspiring',$page->ID); 
                            if($term->slug=='won-talks') echo get_field('subtitulo-popup-talks',$page->ID); 
                            if($term->slug=='acto-inaugural') echo get_field('subtitulo-popup-acto',$page->ID); 
                        ?>   
                    </p>
                    <div class="modal-content-body-scroll">

                        <?php 
						
						 $day25=new WP_Query(array('post_type' => 'agenda_cpt', 
                                                      'posts_per_page' => -1,
                                                      'tax_query' => array(
                                                            'relation' => 'AND',
                                                                array(
                                                                   'taxonomy' => 'tipo_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array($term->name),
                                                                    ),
                                                                array(
                                                                    'taxonomy' => 'dia_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array('25-marzo'),
                                                                    ),
                                                                ),
                                                      'orderby'=>'menu_order', 'order' => 'ASC' 
                                                  )); 
						
                            $day26=new WP_Query(array('post_type' => 'agenda_cpt', 
                                                      'posts_per_page' => -1,
                                                      'tax_query' => array(
                                                            'relation' => 'AND',
                                                                array(
                                                                   'taxonomy' => 'tipo_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array($term->name),
                                                                    ),
                                                                array(
                                                                    'taxonomy' => 'dia_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array('26-marzo'),
                                                                    ),
                                                                ),
                                                      'orderby'=>'menu_order', 'order' => 'ASC' 
                                                  )); 

                            $day27=new WP_Query(array('post_type' => 'agenda_cpt', 
                                                      'posts_per_page' => -1,
                                                      'tax_query' => array(
                                                            'relation' => 'AND',
                                                                array(
                                                                   'taxonomy' => 'tipo_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array($term->name),
                                                                    ),
                                                                array(
                                                                    'taxonomy' => 'dia_agenda',
                                                                    'field'    => 'slug',
                                                                    'terms'    => array('27-marzo'),
                                                                    ),
                                                                ),
                                                        'orderby'=>'menu_order', 'order' => 'ASC'
                                                  ));

                            // echo 'results: <br>';print_r($day26);

                         ?>
						 
						 
						 <!-- START: card card-shedule -->
						  <?php if($day25->have_posts()): ?>
                        <article class="card card-schedule">

                            <header class="card-schedule__day">
                                <div class="col day">
                                    <h1><?php _e('Día','summit2020_wpml'); ?> <span class="number">25</span></h1>
                                </div>
                                <div class="col description">
                                    <p><?php _e('Programa provisional. Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones.','summit2020_wpml'); ?></p>
                                </div>
                            </header>

                            <?php while($day25->have_posts()): $day25->the_post();  ?>
                                <div class="card-schedule__event">
                                    <div class="col hour">
                                        <?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')!=''): ?>
                                            <p><?php echo get_field('hora-inicio').'-'.get_field('hora-fin') ?></p>
                                        <?php endif; ?>	
										<?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')==''): ?>
                                            <p><?php echo get_field('hora-inicio') ?></p>
                                        <?php endif; ?>											
                                    </div>
                                    <div class="col description">
                                        <h2><?php the_title() ?></h2>
                                        <?php the_content() ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                        </article>
						 <?php endif; ?>	
                        <!-- END: card card-shedule -->
						 
						 
						 
                        <!-- START: card card-shedule -->
						 <?php if($day26->have_posts()): ?>
                        <article class="card card-schedule">

                            <header class="card-schedule__day">
                                <div class="col day">
                                    <h1><?php _e('Día','summit2020_wpml'); ?> <span class="number">26</span></h1>
                                </div>
                                <div class="col description">								
                                    <p><?php _e('Programa provisional. Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones.','summit2020_wpml'); ?></p>
                                </div>
                            </header>

                            <?php while($day26->have_posts()): $day26->the_post();  ?>
                                <div class="card-schedule__event">
                                    <div class="col hour">
                                        <?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')!=''): ?>
                                            <p><?php echo get_field('hora-inicio').'-'.get_field('hora-fin') ?></p>
                                        <?php endif; ?>	
										<?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')==''): ?>
                                            <p><?php echo get_field('hora-inicio') ?></p>
                                        <?php endif; ?>											
                                    </div>
                                    <div class="col description">
                                        <h2><?php the_title() ?></h2>
                                        <?php the_content() ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                        </article>
						<?php endif; ?>
                        <!-- END: card card-shedule -->

                        <!-- START: card card-shedule -->
						 <?php if($day27->have_posts()): ?>
                        <article class="card card-schedule">

                            <header class="card-schedule__day">
                                <div class="col day">
                                    <h1><?php _e('Día','summit2020_wpml'); ?> <span class="number">27</span></h1>
                                </div>
                                <div class="col description">
                                    <p><?php _e('Programa provisional. Horarios y/o speakers sujetos a posibles actualizaciones o modificaciones.','summit2020_wpml'); ?></p>
                                </div>
                            </header>

                            <?php while($day27->have_posts()): $day27->the_post();  ?>
                                <div class="card-schedule__event">
                                    <div class="col hour">
                                        <?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')!=''): ?>
                                            <p><?php echo get_field('hora-inicio').'-'.get_field('hora-fin') ?></p>
                                        <?php endif; ?>	
										<?php if(get_field('hora-inicio')!=''&&get_field('hora-fin')==''): ?>
                                            <p><?php echo get_field('hora-inicio') ?></p>
                                        <?php endif; ?>											
                                    </div>
                                    <div class="col description">
                                        <h2><?php the_title() ?></h2>
                                        <?php the_content() ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            
                        </article>
						<?php endif; ?>
                        <!-- END: card card-shedule -->
                    </div>
                </div>
            </aside>

        <?php endforeach; ?>
        <!-- AGENDA -->
        <!-- AGENDA -->

        <!-- SPEAKERS -->
        <!-- SPEAKERS -->

        <?php if($GLOBALS['speaker_featured1']): while($GLOBALS['speaker_featured1']->have_posts()): $GLOBALS['speaker_featured1']->the_post();  ?>

            <aside class="modal speaker-<?php global $post;  echo $post->post_name.'-'.get_field('apellidos'); ?>">
                <div class="modal-content w845">
                    <span class="modal-content__close"><?php _e('Cerrar','summit2020_wpml'); ?></span>
                    <h1 class="modal-content__title fs40"><?php echo get_the_title().' '.get_field('apellidos');  ?></h1>
                    <p class="modal-content__subtitle"><?php echo get_field('cargo'); ?></p>
                    <div class="modal-content-body-scroll">
                        <!-- START: card card-speaker-modal -->
                        <article class="card card-speaker-modal">
                            <div class="card-speaker-modal__txt">
                               <?php the_content(); ?>
                            </div>
                            <nav class="card-speaker-modal__options">
                                <?php if(get_field('facebook')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook'); ?>" title="Ir a Facebook de WN">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('twitter')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter'); ?>" title="Ir a Twitter de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                    </svg>
                                    <span>Twitter</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('linkedin')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin'); ?>" title="Ir a Linkedin de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('instagram')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('instagram'); ?>">
                                     <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                    </svg>
                                    <span>Instagram</span>
                                </a>
                                <?php endif; ?>
                            </nav>

                        </article>
                        <!-- END: card card-speaker-modal -->
                    </div>
                </div>
            </aside>

        <?php endwhile; endif;?>

        <?php if($GLOBALS['speaker_featured2']): while($GLOBALS['speaker_featured2']->have_posts()): $GLOBALS['speaker_featured2']->the_post();  ?>

            <aside class="modal speaker-<?php global $post;  echo $post->post_name.'-'.get_field('apellidos'); ?>">
                <div class="modal-content w845">
                    <span class="modal-content__close"><?php _e('Cerrar','summit2020_wpml'); ?></span>
                    <h1 class="modal-content__title fs40"><?php echo get_the_title().' '.get_field('apellidos');  ?></h1>
                    <p class="modal-content__subtitle"><?php echo get_field('cargo'); ?></p>
                    <div class="modal-content-body-scroll">
                        <!-- START: card card-speaker-modal -->
                        <article class="card card-speaker-modal">
                            <div class="card-speaker-modal__txt">
                               <?php the_content(); ?>
                            </div>
                            <nav class="card-speaker-modal__options">
                                <?php if(get_field('facebook')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook'); ?>" title="Ir a Facebook de WN">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('twitter')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter'); ?>" title="Ir a Twitter de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                    </svg>
                                    <span>Twitter</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('linkedin')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin'); ?>" title="Ir a Linkedin de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('instagram')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('instagram'); ?>">
                                     <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                    </svg>
                                    <span>Instagram</span>
                                </a>
                                <?php endif; ?>
                            </nav>

                        </article>
                        <!-- END: card card-speaker-modal -->
                    </div>
                </div>
            </aside>

        <?php endwhile; endif;?>

        <?php if($GLOBALS['speaker_featured3']): while($GLOBALS['speaker_featured3']->have_posts()): $GLOBALS['speaker_featured3']->the_post();  ?>

            <aside class="modal speaker-<?php global $post;  echo $post->post_name.'-'.get_field('apellidos'); ?>">
                <div class="modal-content w845">
                    <span class="modal-content__close"><?php _e('Cerrar','summit2020_wpml'); ?></span>
                    <h1 class="modal-content__title fs40"><?php echo get_the_title().' '.get_field('apellidos');  ?></h1>
                    <p class="modal-content__subtitle"><?php echo get_field('cargo'); ?></p>
                    <div class="modal-content-body-scroll">
                        <!-- START: card card-speaker-modal -->
                        <article class="card card-speaker-modal">
                            <div class="card-speaker-modal__txt">
                               <?php the_content(); ?>
                            </div>
                            <nav class="card-speaker-modal__options">
                                <?php if(get_field('facebook')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('facebook'); ?>" title="Ir a Facebook de WN">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('twitter')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('twitter'); ?>" title="Ir a Twitter de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                    </svg>
                                    <span>Twitter</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('linkedin')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('linkedin'); ?>" title="Ir a Linkedin de WN">
                                    <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path>
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
                                <?php endif; ?>
                                <?php if(get_field('instagram')!=''): ?>
                                <a class="btn btn-icon" target="_blank" rel="nofollow noopener" href="<?php echo get_field('instagram'); ?>">
                                     <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                                    </svg>
                                    <span>Instagram</span>
                                </a>
                                <?php endif; ?>
                            </nav>

                        </article>
                        <!-- END: card card-speaker-modal -->
                    </div>
                </div>
            </aside>

        <?php endwhile; endif;?>
        <!-- SPEAKERS -->
        <!-- SPEAKERS -->

        <!-- PARTNERS -->
        <!-- PARTNERS -->
        <aside class="modal partners">
            <div class="modal-content w1450">
                <span class="modal-content__close"><?php _e('Cerrar','summit2020_wpml'); ?></span>
                <h1 class="modal-content__title center fs40"><?php _e('Our Partners','summit2020_wpml'); ?></h1>
                <p class="modal-content__subtitle center"><?php _e('Coming together is a beginning','summit2020_wpml'); ?></p>
                <div class="modal-content-body-scroll">
                    

                        <?php 

                            $terms = get_terms([
                                'taxonomy' => 'tipo_partner',
                                'hide_empty' => false,
                                'meta_key'          => 'orden',
                                'orderby'           => 'meta_value',
                                'order'             => 'ASC',
                            ]); 
                            foreach ($terms as $key => $term) {
                                $columnas = get_field('columnas',$term->taxonomy.'_'.$term->term_id);
                                $Partner=new WP_Query(array('post_type' => 'partner_cpt', 
                                                              'posts_per_page' => -1,
                                                              'orderby'=> 'title', 'order' => 'ASC',
                                                              'tax_query' => array(array('taxonomy'=>'tipo_partner','field'=>'slug','terms'=> array($term->slug))))); 

                        ?>

                            <!-- Partner -->
                            <?php  if($Partner->found_posts>0): ?>
                                <?php  if($term->slug=='global-partner'): ?>

                                    <div class="modal-content-body-five-cols card card-partner">
                                        <div class="full">
                                            <h2 class="title"><?php echo $term->name; ?></h2>
                                            <?php while( $Partner->have_posts()):  $Partner->the_post();  ?>
                                                <figure class="thumb lateral">
                                                    <a href="<?php echo get_field('link'); ?>" target="_blank">
                                                        <img src="<?php echo get_the_post_thumbnail_url() ?>">
                                                    </a>
                                                    <figcaption class="caption">  <?php the_content(); ?> </figcaption>
                                                </figure>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>

                                <?php  else: ?>
                                    <div class="modal-content-body-five-cols card card-partner">
                                        <div class="full">
                                            <h2 class="title"><?php echo $term->name; ?></h2>
                                        </div>
                                    </div>
                                    <?php 
										$class='modal-content-body';
                                        //$class='modal-content-body-five-cols';
                                        //if($columnas==4)  $class='modal-content-body-four-cols'; 
                                        //if($columnas==3)  $class='modal-content-body-three-cols'; 
                                        //if($columnas==2)  $class='modal-content-body-two-cols';  
                                    ?>
                                    <div class="card card-partner <?php echo $class; ?>">
                                        <?php while( $Partner->have_posts()):  $Partner->the_post();  ?>
                                            <figure class="thumb">
                                                <a href="<?php echo get_field('link'); ?>" target="_blank">
                                                    <img src="<?php echo get_the_post_thumbnail_url() ?>">
                                                </a>
                                            </figure>
                                        <?php endwhile; ?>
                                    </div>

                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- Partner -->

                        <?php }; ?>

                    </div>

                </div>
            </div>
        </aside>
        <!-- PARTNERS -->
        <!-- PARTNERS -->



        <!-- WHERE -->
        <!-- WHERE -->
        <?php  $page=get_page_by_path( 'donde' ); $page = get_page(icl_object_id($page->ID, 'page', true));?>
        <aside class="modal where">
            <div class="modal-content w1032">
                <span class="modal-content__close">Cerrar</span>
                <h1 class="modal-content__title center fs40">WON Summit 2020</h1>
                <p class="modal-content__subtitle center">Madrid 26-27 <?php _e('marzo','summit2020_wpml'); ?> 2020</p>
                <div class="modal-content-body-scroll">
                    <div class="modal-content-body-two-cols ">
                    <article class="card card-contact full">
                        <div class="txt">
                            <h1 class="title fs24"><?php echo get_field('museo',$page->ID); ?></h1>
                            <p class="subtitle"><?php echo get_field('auditorio',$page->ID); ?></p>
                        </div>
                        <div class="embed"><?php echo get_field('google_maps',$page->ID); ?> </div>
                    </article>

                    <?php if(get_field('autobus',$page->ID)): ?>
                        <article class="card card-contact">
                            <figure class="thumb"><img src="<?php echo get_field('autobus',$page->ID)['imagen']['url']; ?>" alt="icono bus"/></figure>
                            <div class="txt">
                                <h1 class="title"> <?php echo get_field('autobus',$page->ID)['titulo']; ?></h1>
                                <?php echo get_field('autobus',$page->ID)['texto']; ?>
                            </div>
                        </article>
                    <?php endif; ?>

                    <?php if(get_field('metro',$page->ID)): ?>
                        <article class="card card-contact">
                            <figure class="thumb"><img src="<?php echo get_field('metro',$page->ID)['imagen']['url']; ?>" alt="icono bus"/></figure>
                            <div class="txt">
                                <h1 class="title"> <?php echo get_field('metro',$page->ID)['titulo']; ?></h1>
                                 <?php echo get_field('metro',$page->ID)['texto']; ?>
                            </div>
                        </article>
                    <?php endif; ?>


                    <?php if(get_field('tren',$page->ID)): ?>
                        <article class="card card-contact">
                            <figure class="thumb"><img src="<?php echo get_field('tren',$page->ID)['imagen']['url']; ?>" alt="icono bus"/></figure>
                            <div class="txt">
                                <h1 class="title"> <?php echo get_field('tren',$page->ID)['titulo']; ?></h1>
                                <?php echo get_field('tren',$page->ID)['texto']; ?>
                            </div>
                        </article>
                    <?php endif; ?>


                    <?php if(get_field('bicimad',$page->ID)): ?>
                        <article class="card card-contact">
                            <figure class="thumb"><img src="<?php echo get_field('bicimad',$page->ID)['imagen']['url']; ?>" alt="icono bus"/></figure>
                            <div class="txt">
                                <h1 class="title"> <?php echo get_field('bicimad',$page->ID)['titulo']; ?></h1>
                                 <?php echo get_field('bicimad',$page->ID)['texto']; ?>
                            </div>
                        </article>
                    <?php endif; ?>

                </div>
                </div>
            </div>
        </aside>
        <!-- WHERE -->
        <!-- WHERE -->

    <?php endif; ?>

    <!-- END: MODALS -->