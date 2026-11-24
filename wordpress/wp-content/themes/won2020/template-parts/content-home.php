

    <!-- START: o-section -->
    <section class="o-section u-mb40">
    <h1 class='h1_home'>WomenNOW. Mujeres extraordinarias que escriben el futuro de la igualdad</h1>
        <?php if(!is_null(get_field('video_congreso')) && get_field('video_congreso')!=''){?>
            <div class="c-detail__media__video video_portada">
                <div class="video-player" data-voc-video-player data-voc-video-player-source="brightcove" data-voc-video-player-accountname="womennow"  data-voc-video-player-autoplay="false" data-voc-video-player-muted="false" data-voc-video-player-videoid="<?php echo get_field('video_congreso') ?>"></div>
            </div>
        <?php } ?>
        <div class="o-slider" data-type="slide" data-slide-autoplay="true">
            <div class="o-slider__wrap degree">
                <?php if( have_rows('slide') ): while ( have_rows('slide') ) : the_row();?>
                    <a href="<?php  echo get_sub_field('grupo2')['enlace']; ?>" title="Leer <?php echo (str_replace('"', '\'', strip_tags(get_sub_field('grupo')['titulo']))); ?>" aria-label="Leer <?php echo (str_replace('"', '\'', strip_tags(get_sub_field('grupo')['titulo']))); ?>" class="c-claim">
                        <figure class="c-claim__thumb">
                            <img src="<?php echo get_site_url().get_sub_field('grupo2')['imagen']['url']; ?>" alt="<?php echo (str_replace('"', '\'', strip_tags(get_sub_field('grupo')['titulo']))); ?>">
                        </figure>
                        <div class="c-claim__txt">
                            <h2 class="c-claim__title"><?php  echo get_sub_field('grupo')['titulo']; ?></h2>
                            <p class="c-claim__subtitle"><?php  echo get_sub_field('grupo')['texto']; ?></p>
                        </div>
                    </a>
                <?php  endwhile; endif; ?>
            </div>
        </div>
    </section>
    <!-- END: o-section -->

    <?php get_template_part( 'template-parts/content', 'modulos' ); ?>

</div>

    <style>
        .mejs-controls {
          display: none !important;
          visibility: hidden !important;
        }
    </style>

