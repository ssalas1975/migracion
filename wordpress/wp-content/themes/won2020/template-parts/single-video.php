
<!-- START: o-section -->
   <section class="o-section post-type-video">
        <p class="c-title-small u-color-cyan">Vídeos</p>
        <article class="c-detail">
        <div class="c-detail__top u-mb40 grey-degree">
                <div class="c-detail__media__video">
                    <!-- <img src="http://placehold.it/1250x400" alt="thumb video"> -->
                    <div class="video-player" data-voc-video-player data-voc-video-player-source="brightcove" data-voc-video-player-accountname="womennow"  data-voc-video-player-autoplay="false" data-voc-video-player-muted="false" data-voc-video-player-videoid="<?php echo get_field('idvideo') ?>">
                        <div class="video-player-play-button">
                            <span class="boton-video">
                                <svg viewBox="0 0 24 24"><path d="M8,5.14V19.14L19,12.14L8,5.14Z"></path></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="u-grid u-grid-columns-2-xs u-grid-columns-2">
                    <div class="c-detail__signature">
                        <p class="c-detail__signature__author">By WomenNow</p>
                        <p class="c-detail__signature__date"><?php echo ucwords(get_the_time('j, F, Y', $post->ID)); ?></p>
                    </div>
                    <div>
                        <nav class="c-detail__share u-flex u-flex-justify-end" data-share-url="<?php global $wp; echo home_url( $wp->request ) ?>" data-share-text="<?php the_title() ?>">
                            <a data-share-button="twitter" class="btn btn-primary btn-icon" title="Comparte en Twitter" tabindex="0">
                                <span>Twitter</span>
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg>
                            </a>
                            <a data-share-button="facebook" class="btn btn-primary btn-icon" title="Comparte en Facebook" tabindex="0">
                                <span>Facebook</span>
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" class=""></path></svg>
                            </a>
                            <a data-share-button="linkedin" class="btn btn-primary btn-icon" title="Comparte en LinkedIn" tabindex="0">
                                <span>LinkeInd</span>
                                <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M100.3 480H7.4V180.9h92.9V480zM53.8 140.1C24.1 140.1 0 115.5 0 85.8 0 56.1 24.1 32 53.8 32c29.7 0 53.8 24.1 53.8 53.8 0 29.7-24.1 54.3-53.8 54.3zM448 480h-92.7V334.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V480h-92.8V180.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V480z"></path></svg>
                            </a>
                            <a data-share-button="whatsapp" class="btn btn-primary btn-icon" title="Comparte en Whatsapp" tabindex="0">
                                <span>Whatsapp</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="417.488" height="419.495" viewBox="0 0 417.488 419.495">
                                    <g fill="currentColor" id="prefix__WhatsApp_Logo" data-name="WhatsApp Logo" transform="translate(-430.394 -325.331)">
                                        <path id="prefix__WA_Logo" d="M459.885 637.109a207.5 207.5 0 0 1-27.748-103.922c.046-114.611 93.3-207.856 207.873-207.856a207.908 207.908 0 0 1 207.873 208.022c-.047 114.614-93.307 207.869-207.87 207.869h-.086a207.688 207.688 0 0 1-99.337-25.3l-110.2 28.906zM552 682.032a172.509 172.509 0 0 0 87.937 24.083h.07c95.229 0 172.735-77.508 172.774-172.775a172.8 172.8 0 0 0-172.7-172.9c-95.3 0-172.809 77.5-172.847 172.761a172.354 172.354 0 0 0 26.416 91.947l4.108 6.538-17.458 63.755 65.4-17.153zm121.934-51.912a298.414 298.414 0 0 1-29.609-10.94c-52.1-22.5-86.132-74.959-88.729-78.427s-21.208-28.164-21.208-53.731 13.418-38.13 18.179-43.331a19.081 19.081 0 0 1 13.85-6.5c3.463 0 6.929.032 9.955.183.374.019.765.016 1.168.014 3.027-.017 6.8-.034 10.518 8.9 4.328 10.4 14.716 35.967 16.014 38.567s2.164 5.631.433 9.1-2.6 5.632-5.193 8.665-5.455 6.772-7.791 9.1c-2.6 2.59-5.307 5.4-2.277 10.6s13.456 22.21 28.893 35.98c19.839 17.7 36.574 23.18 41.768 25.78s8.224 2.165 11.253-1.3 12.984-15.167 16.447-20.366 6.926-4.332 11.687-2.6 30.3 14.3 35.491 16.9 8.657 3.9 9.955 6.067 1.3 12.566-3.029 24.7-25.079 23.206-35.059 24.7a84.914 84.914 0 0 1-12.606 1 65.9 65.9 0 0 1-20.11-3.061z" data-name="WA Logo"/>
                                    </g>
                                </svg>
                            </a>
                        </nav>
                      </div>
                </div>
                <h1 class="c-detail__title"><?php the_title(); ?></h1>
                <div class="c-detail__subtitle"><?php echo the_content(); ?></div>
                 <!-- START: Tags -->
                 <?php $terms = get_the_terms( $post->ID , 'category' ); if ( $terms != null ):  ?>
                      <nav class="c-detail__tags u-flex u-flex-justify-start">
                          <span>TAGS</span>
                          <?php foreach( $terms as $term ): ?>
                              <a href="<?php echo site_url().'/tags/'.$term->slug ?>/" title="Ir a <?php echo $term->name; ?>" aria-label="Ir a <?php echo $term->name; ?>" ><?php echo $term->name; ?></a>
                          <?php endforeach; ?>
                      </nav>
                  <?php endif; ?>
                  <!-- END: Tags -->
            </div>
            <div class="robapaginas-mobile"></div>
            <!-- START: Vídeos relacionados -->
            <?php $class=(get_field('interes'))?'u-mb40 u-border-bottom':''; ?>
            <?php $posts = get_field('videos_relacionados'); if( $posts ): ?>
                <p class="c-title"><?php echo _e('Vídeos relacionados','won2020_v2') ?></p>
                <div class="u-grid u-grid-columns-3 <?php echo $class; ?>">
                    <?php foreach( $posts as $post): setup_postdata($post); ?>
                        <article class="c-article">
                            <figure class="c-article__thumb video">
                                <img data-src="<?php echo get_the_post_thumbnail_url($post->post_ID,'video-imgs') ?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" src="" loading="lazy">
                            </figure>
                            <div class="c-article__txt">
                                <p class="c-article__tag"><?php get_field('pretitulo') ?></p>
                                <h3 class="c-article__title">
                                    <a href="<?php the_permalink(); ?>" title="Ver vídeo relacionado <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Ver vídeo relacionado <?php echo (stripQuotes(get_the_title())); ?>" ><?php the_title(); ?></a>
                                </h3>
                            </div>
                        </article>
                    <?php endforeach; wp_reset_postdata();?>
                </div>
            <?php endif; ?>
            <!-- END: Vídeos relacionados -->

             <!-- START: Te puede interesar -->
             <?php $posts = get_field('interes'); if( $posts ): ?>
                <div class="c-detail__interest">
                    <h3 class="c-detail__interest__title u-mb40"><?php echo _e('Te puede interesar','won2020_v2') ?></h3>
                    <ul class="c-detail__interest__list u-mb40">
                        <?php foreach( $posts as $post): setup_postdata($post); ?>
                            <li class="c-detail__interest__list-item u-flex u-flex-justify-start">
                                <a href="<?php the_permalink(); ?>" title="Te puede interesar <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Te puede interesar <?php echo (stripQuotes(get_the_title())); ?>" ><?php the_title(); ?></a>
                            </li>
                        <?php endforeach; wp_reset_postdata();?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- END: Te puede interesar -->

        </article>
    </section>
    <!-- END: o-section -->

    <style>
        /*video*/
        .video-player {
            position: relative;
            display: block;
            /*min-height:250px;*/
            background-color: black;
            /*padding-bottom: 56.25%;*/
            overflow: hidden;
        }
        .featured .video-player {
            min-height:150px;
        }
            .boton-video {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                width: 20%;
                z-index: 1;
                pointer-events:none !important;
            }
            .boton-video svg {
                width: 100%;
                fill: #fff;
            }
    </style>

