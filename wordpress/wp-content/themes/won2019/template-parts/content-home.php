
    <?php 



        //// speakers
        $args = array(
            'posts_per_page'=> -1,
            'post_type'     => 'speaker',
            'meta_query' => array(
                array(
                    'key' => 'categoria',
                    'value' => 1,
                    'compare'  => '==',
                )
            ),
            'meta_key'      => 'orden',
            'orderby'       => 'meta_value',
            'order'         => 'ASC',
        );
        $keynote = new WP_Query($args);


        $args = array(
            'posts_per_page'=> 7-$keynote->found_posts,
            'post_type'     => 'speaker',
            'meta_query' => array(
                array(
                    'key' => 'categoria',
                    'value' => 0,
                    'compare'  => '==',
                )
            ),
            'meta_key'      => 'orden',
            'orderby'       => 'meta_value',
            'order'         => 'ASC'
        );
        $inspiring = new WP_Query($args);
        //// speakers


        //// noticias
        $args = array(
            'posts_per_page' => -1,
            'post_type'     => 'noticias',
            'meta_query' => array(
                array( 'key' => 'destacada','value' => 1 )
            ),
            'order' => 'DESC' 
        );
        $noticias = new WP_Query($args);

    ?>

    <!----------------------- popup video ----------------------------------->

            <script src="<?php echo get_template_directory_uri(); ?>/js/froogaloop.min.js"></script>
           <section class="popup video">
                <span class="center" style="background-color: #000">
                    <div class="cierreX video">X</div>
                    <div style="padding:56.25% 0 0 0;position:relative;">
                        <iframe id="iframe" src="https://player.vimeo.com/video/329791985?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                    <script src="https://player.vimeo.com/api/player.js"></script>
                </span>
            </section>

        <!----------------------- popup video ----------------------------------->

    <section class="cabecera home container">

        <span class="color_title"></span>
        <span class="title">WON</span>

        <img class="claim" src="<?php echo get_template_directory_uri();?>/images/claim.png">
        
        <?php if(count(get_field('imagenes'))>1):  ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if( have_rows('imagenes') ): while ( have_rows('imagenes') ) : the_row(); if(get_sub_field('imagen')):?>
                        <div class="swiper-slide"><img class="fondo" src=" <?php  echo get_sub_field('imagen')['url']; ?> "></div>
                    <?php  endif; endwhile; endif; ?>
                </div>
                <div class="bullets"></div>
                <div class="arrow arrowLeft"></div>
                <div class="arrow arrowRight"></div>
                <p class="save"><?php _e( 'Save<br> the<br> date', 'won2019' ); ?></p>
            </div>
        <?php else: ?>
            <?php if( have_rows('imagenes') ): while ( have_rows('imagenes') ) : the_row(); if(get_sub_field('imagen')):?>
                <div class="swiper-slide"><img class="fondo" src=" <?php  echo get_sub_field('imagen')['url']; ?> "></div>
            <?php  endif; endwhile; endif; ?>
        <?php endif; ?>

        <article>
            <h1><?php echo get_field('titular') ?></h1>
            <p><?php echo get_field('texto') ?></p>
        </article>

    </section>

    <section class="time container">

        <span class="title">Time is <strong>NOW</strong></span>
        <p class="fecha"><span>29-30/03/<br>2019</span></p> 
        <p class="hastag">#MakeHERstory</p>
        <article>
            <span class="center">
                <h1><?php echo get_field('titular-time') ?></h1>
                <p><?php echo get_field('texto-time') ?></p>
            </span>
        </article>
        <img src="<?php  echo get_field('imagen-time')['url']; ?>">
        <span class="footer">
            <?php if(get_field('num_speak1')!=''&&get_field('num_speak2')!=''&&get_field('num_speak3')!=''): ?>
                <ul class="datos vcenter">
                    <li><?php echo get_field('num_speak1') ?><span>International<br>Speakers</span></li>
                    <li><?php echo get_field('num_speak2') ?><span>European<br>Places</span></li>
                    <li><?php echo get_field('num_speak3') ?><span>International<br>Speakers</span></li>
                </ul>
            <?php else: ?>
                <p class="hastag vcenter">#TimeisNow</p>
            <?php endif; ?>
        </span>

    </section>


    <?php if( $keynote->have_posts() || $inspiring->have_posts()) : ?>
        <section class="speakers home container">

            <span class="title_speak">Speakers</span>
            <ul>

                <?php while ( $keynote->have_posts() ) : $keynote->the_post(); ?>

                    <li>
                        <div class="image open" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')"></div>
                        <div class="info open">
                            <span class="vcenter">
                                <h1><?php the_title() ?></h1>
                                <p><?php echo get_field('cargo') ?></p>
                            </span>
                            <nav class="rrss">
                                <?php if(get_field('fb')!='' ): ?> 
                                    <a href="<?php echo get_field('fb') ?>"><i class="icon-fb"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('twt')!='' ): ?> 
                                    <a href="<?php echo get_field('twt') ?>"><i class="icon-twt"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('link')!='' ): ?> 
                                    <a href="<?php echo get_field('link') ?>"><i class="icon-link"></i></a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <div class="content">
                            <span class="vcenter">
                                <?php echo get_field('texto') ; ?>
                            </span>
                        </div>
                        <span class="x">X</span>
                    </li>

                <?php  endwhile; ?>

                <?php while ( $inspiring->have_posts() ) : $inspiring->the_post(); ?>

                    <li>
                        <div class="image open" style="background-image: url('<?php echo get_the_post_thumbnail_url();?>')"></div>
                        <div class="info open">
                            <span class="vcenter">
                                <h1><?php the_title() ?></h1>
                                <p><?php echo get_field('cargo') ?></p>
                            </span>
                            <nav class="rrss">
                                <?php if(get_field('fb')!='' ): ?> 
                                    <a href="<?php echo get_field('fb') ?>"><i class="icon-fb"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('twt')!='' ): ?> 
                                    <a href="<?php echo get_field('twt') ?>"><i class="icon-twt"></i></a>
                                <?php endif; ?>
                                <?php if(get_field('link')!='' ): ?> 
                                    <a href="<?php echo get_field('link') ?>"><i class="icon-link"></i></a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <div class="content">
                            <span class="vcenter">
                                <?php echo get_field('texto') ; ?>
                            </span>
                        </div>
                        <span class="x">X</span>
                    </li>

                <?php  endwhile; ?>

                    <!-- link a resto de speakers -->
                    <?php $page = get_page_by_path( 'speakers' ); ?>
                        <li>
                            <a href="<?php echo get_the_permalink($page) ?>"><span>speakers</span></a>
                        </li>
                    <?php wp_reset_postdata(); ?>

            </ul>
        </section>
    <?php endif; ?>


    <?php if ( $noticias->have_posts() ) : ?>
        <section class="news home container">
            
                <span class="title">News</span>
                <ul>

                    <?php 

                        for($i=0;$i<=4;$i++): 
                             $args = array(
                                'posts_per_page' => 1,
                                'post_type'     => 'noticias',
                                'meta_query' => array(
                                    'relation' => 'AND',
                                    array(
                                        'relation' => 'AND',
                                        array( 'key' => 'destacada','value' => 1 ),
                                        array('key' => 'noticias_destacadas', 'value' => $i )
                                    ),
                                ),
                                'order' => 'DESC' 
                        );
                        $noticias = new WP_Query($args);
                    ?>

                    <?php if ( $noticias->have_posts() ) : $noticias->the_post();  ?>
                        <li>
                            <a href="<?php the_permalink() ?>">
                                <?php while(have_rows('imagenes-noticia')){ the_row(); 
                                    if(get_sub_field('imagen')!=''){ $img=get_sub_field('imagen')['url']; break; } 
                                } ?>
                                <span class="imagen" style="background-image: url('<?php echo $img ?>')">
                                    <span class="hover"><i class="icon-lupa center"></i></span>
                                </span>
                                <h1><?php the_title() ?></h1>
                                <p><?php echo get_the_excerpt()?></p>
                            </a>
                        </li>
                    <?php  endif; wp_reset_postdata(); endfor; ?>
                    
                </ul>

                <?php $enlace=(ICL_LANGUAGE_CODE == 'es')?icl_get_home_url().'/noticias/':icl_get_home_url().'/news/'; ?>
                <a class="listado" href="<?php echo $enlace ?>"><?php _e( 'Leer más noticias...', 'won2019' ); ?></a>

        </section>
    <?php endif; ?>
