
   <?php
	$args = array(
        'posts_per_page'=>5,
        'post_type'=> 'any',
        'meta_key'=>'views_last_week',
        'orderby'=>'meta_value_num',
        'order'=>'DESC'
	);
    $lomasvisto = new WP_Query($args);

    $terms = get_the_terms($post->ID, 'category');
    $terms_string = join(', ', wp_list_pluck($terms, 'slug'));
    $title_section = (strpos($terms_string, 'money-career') !== false) ? 'Money & Career' : 'Te interesa';
?>

   <!-- START: o-section -->
   <section class="o-section post-type-noticia">

       <p class="c-title-small u-color-cyan"><?php echo $title_section; ?></p>
       <div class="u-grid u-grid-columns-3-add">
          <!-- START: c-article -->
          <article class="c-detail u-grid-start-1 u-grid-col-span-2">

                  <div class="u-grid u-grid-columns-2-xs u-grid-columns-2 u-sticky">
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

                  <!-- START: IMAGEN -->
                  <?php if(get_the_post_thumbnail_url()!=''&&get_field('foto-interior')!=1): ?>
                      <figure class="c-detail__media">
                          <div class="c-detail__media__thumb">
                            <?php 
                                $imageId = get_post_thumbnail_id($article->ID);
                                $srcImgsAbsolutaUrl = get_srcset($imageId);
                            ?>
                            <img <?php echo $srcImgsAbsolutaUrl; ?> loading="lazy" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>">
                          </div>
                          <figcaption class="c-detail__media__txt">
                              <p class="c-detail__media__title"><?php echo get_field('pie_foto'); ?></p>
                          </figcaption>
                      </figure>
                  <?php endif; ?>
                  <!-- END: IMAGEN -->

                    <!-- START: VIDEO -->
                    <?php $piedefoto=get_field('pie_foto'); ?>
                    <?php $post_object = get_field('video'); ?>
                    <?php if( $post_object ): $post = $post_object; setup_postdata( $post ); ?>
                        <a href="<?php echo the_permalink() ?>" title="Ver <?php echo stripQuotes(get_the_title()); ?>" aria-label="Ver <?php echo stripQuotes(get_the_title()); ?>">
                            <div class="c-detail__media">
                                <figure class="c-detail__media__video">
                                    <?php 
                                        $imageId = get_post_thumbnail_id();
                                        $srcImgsAbsolutaUrl = get_srcset($imageId);
                                    ?>
                                    <img <?php echo $srcImgsAbsolutaUrl; ?> loading="lazy" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>">
                                    <figcaption class="c-detail__media__video__txt">
                                        <p class="c-detail__media__video__title"><?php echo the_title(); ?></p>
                                    </figcaption>
                                </figure>
                                <div class="c-detail__media__txt">
                                    <p class="c-detail__media__title"><?php echo $piedefoto ?></p>
                                </div>
                            </div>
                         </a>
                      <?php wp_reset_postdata();?>
                  <?php endif; ?>
                  <!-- END: VIDEO -->

                    <!-- START: galeria extra -->
                    <?php $post_object = get_field('galeria_extra'); ?>
                    <?php if( $post_object ): $post = $post_object; setup_postdata( $post ); ?>
                        <a href="<?php echo the_permalink() ?>" title="Leer <?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" aria-label="Leer <?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>" >
                            <figure class="c-detail__media">
                                <div class="c-detail__media__gallery">
                                    <?php 
                                        $imageId = get_post_thumbnail_id($article->ID);
                                        $srcImgsAbsolutaUrl = get_srcset($imageId);
                                    ?>
                                    <img <?php echo $srcImgsAbsolutaUrl; ?> loading="lazy" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>">
                                </div>
                                <figcaption class="c-detail__media__txt">
                                    <p class="c-detail__media__title"><?php echo get_field('pie_foto'); ?></p>
                                </figcaption>
                            </figure>
                        </a>
                        <?php wp_reset_postdata();?>
                    <?php endif; ?>
                    <!-- END: galeria extra -->

                  <h1 class="c-detail__title"><?php the_title() ?></h1>
                  <h2 class="c-detail__subtitle"><?php echo  get_field('resumen'); ?></h2>

                  <!-- START: podcast -->
                  <?php $post_object = get_field('audio'); ?>
                    <?php if( $post_object ): $post = $post_object; setup_postdata( $post ); ?>
                      <div class="c-detail__media__widget">
                          <?php $audio=get_field('audio'); echo do_shortcode("$audio") ?>
                          <?php $page_podcast=get_page_by_title('Podcasts'); ?>
                          <p><a href="<?php echo the_permalink($page_podcast->ID) ?>" title="Escuchar más podcasts..." aria-label="Escuchar más podcasts..." >Escuchar más podcasts...</a></p>
                      </div>
                    <?php wp_reset_postdata();?>
                  <?php endif; ?>
                  <!-- END: podcast-->

                  <!-- START: content -->
                  <div class="c-detail__body">
                    <?php  
                    if(''!=get_field('autor')){
                        echo '<p>'.get_field('autor').'</p>';
                    }

                    $urlpost = get_permalink();
                    $titlepost =  str_replace('"', '\'', strip_tags(get_the_title()));

                    if($post->post_content==''){
                        echo acf_replace_tags($urlpost,$titlepost,add_lazyload(get_field('contenido')));
                    }else{
                        echo acf_replace_tags($urlpost,$titlepost,add_lazyload(the_content()),'noticia',true);
                    }?>
                  </div>
                   <!-- END: content -->

                  <!-- START: gallery -->
                    <?php
                    if (have_rows('galeria')) : 
                        $nPhotos = 0;
                        while (have_rows('galeria')) : the_row(); 
                            $nPhotos++; 
                        endwhile;
                    ?>
                        <div class="c-detail__gallery">

                            <?php
                            $key=0;
                            while (have_rows('galeria')) : the_row(); 
                                $rowIndex = get_row_index();
                                $key++;
                            ?>
                                <section class="c-detail__gallery__item"  id="imagen-<?php echo ($key) ?>">
                                    <?php $grupo_imagen = get_sub_field('grupo_imagen'); ?>
                                    <figure class="c-detail__gallery__item__thumb">
                                        <?php 
                                            $imageId = $grupo_imagen['imagen']['ID'];
                                            $srcImgsAbsolutaUrl = get_srcset($imageId);
                                        ?>
                                        <img <?php echo $srcImgsAbsolutaUrl; ?> loading="lazy" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>">
                                        <figcaption class="u-flex"><?php echo $grupo_imagen['pie'] ?></figcaption>
                                    </figure>
                                    <div class="c-detail__body">
                                        <?php echo get_sub_field('texto_imagen');  ?>
                                    </div>
                                    <?php if ($nPhotos == $rowIndex): ?>
                                        <div class="medicion-fotogaleria" data-voc-vtm-id="completed-gallery" data-voc-vtm-info="vista completa"></div>
                                    <?php endif; ?>
                                </section>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                  <!-- END: gallery -->

                  <!-- START: Tags -->
                  <?php if ( $terms != null ):  ?>
                      <nav class="c-detail__tags u-flex u-flex-justify-start u-mb40">
                          <span class="u-color-cyan">TAGS</span>
                          <?php foreach( $terms as $term ): ?>
                              <a href="<?php echo site_url().'/tags/'.$term->slug ?>/" title="Ir a <?php echo $term->name; ?>" aria-label="Ir a <?php echo $term->name; ?>" ><?php echo $term->name; ?></a>
                          <?php endforeach; ?>
                      </nav>
                  <?php endif; ?>
                  <!-- END: Tags -->

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
          <!-- END: c-article -->

          <aside class="u-grid-start-3 u-grid-row-start-1 u-grid-row-span-2">
               <!-- START: c-add -->
              <div id="robapaginas-id" class="u-flex" style="padding-bottom:30px;">
               <script type="vocento/script">
                  googletag.cmd.push(function() { googletag.display('robapaginas-id'); });
              </script>
                </div>
               <!-- END: c-add -->
               <!-- START: c-add -->
              <div id="boton_native-id">
               <script type="vocento/script">
                  googletag.cmd.push(function() { googletag.display('boton_native-id'); });
              </script>
                </div>
               <!-- END: c-add -->
               <!-- START: c-newsletter -->
               <div class="c-lomas u-sticky u-mb40">
                 <h3 class="c-lomas__title"><?php echo _e('Lo más visto','won2020_v2');?></h3>
                  <figure class="c-lomas__thumb">
                    <?php 
                        $imageId = get_post_thumbnail_id($lomasvisto->posts[0]->ID);//($lomasvisto->posts[0]->ID,'lomas-imgs')
                        $srcImgsAbsolutaUrl = get_srcset($imageId);
                    ?>
                    <img <?php echo $srcImgsAbsolutaUrl; ?> loading="lazy" alt="<?php echo (str_replace('"', '\'', strip_tags(get_the_title()))); ?>">
                  </figure>

                  <?php if ($lomasvisto->have_posts()):?>
                      <ol class="c-lomas__list">
                              <?php  while ($lomasvisto->have_posts()): $lomasvisto->the_post(); ?>
                                  <li class="c-lomas__list-item"><a href="<?php the_permalink(); ?>" title="Leer lo más visto <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer lo más visto: <?php echo (stripQuotes(get_the_title())); ?>" ><?php the_title(); ?></a></li>
                              <?php endwhile; ?>
                      </ol>
                  <?php endif; ?>
               <!-- END: c-newsletter -->
          </aside>


       </div>
  </section>
  <!-- END: o-section -->
