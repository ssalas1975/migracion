<?php

get_header();

$args = array(
    'posts_per_page' => 5,
    'post_type' => 'any',
    'meta_key' => 'views_last_week',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
);

$lomasvisto = new WP_Query($args);

?>

<!-- START: content-->
<main class="content">
    <div class="o-container">

        <!-- START: o-section -->
        <section class="o-section">
            <div class="u-grid u-grid-columns-3-add">
                <div class=" u-grid-start-1 u-grid-col-span-2 u-mb40 u-border-top">

                    <?php //echo get_search_query() 
                    ?>

                    <div class="c-search u-border-bottom u-mb40">
                        <h1 class="c-title u-border-none u-mb-0"><?php echo _e('Buscador', 'won2020_v2') ?></h1>
                        <form role="search" method="get" class="search-form">
                            <input name="s" id="spage" type="text" placeholder="<?php echo _e('Buscar', 'won2020_v2') ?>..." value="<?php echo get_search_query() ?>">
                            <button class="btn btn__icon btn__icon-search" onclick="submitSearchForm()" type="button">
                                <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path>
                                </svg>
                                <span><?php echo _e('Buscar', 'won2020_v2') ?></span>
                            </button>
                            <script type="vocento/script">
                                function submitSearchForm(){						
                                    var urlParam = document.getElementById("spage").value;
                                    var URL = "<?php echo home_url('/search/');?>";
                                    var encordedUrl = URL+encodeURIComponent(urlParam.toLowerCase().replace("+","").normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
                                    console.log(encordedUrl);
                                    document.location.href = encordedUrl;
                                }
                                $(document).ready(function() {
                                    $(window).keydown(function(event){
                                        if(event.keyCode == 13) {
                                            event.preventDefault();
                                            submitSearchForm();
                                            return false;
                                        }
                                    });
                                });
                            </script>
                        </form>
                    </div>

                    <div class="u-grid u-grid-columns-2 u-mb40 u-border-bottom">
                        <div></div>
                        <!-- START: pagenavi -->
                        <nav class="wp-pagenavi" role="navigation">
                            <?php wp_pagenavi(); ?>
                        </nav>
                        <!-- END: pagenavi -->
                    </div>

                    <div class="c-list">
                        <?php if (have_posts()):?>
                            <?php while (have_posts()) : the_post();
                                $total = 185;
                                $contenido = $total - strlen(get_the_title()); ?>
                                <!-- START: c-list__item-->
                                <article class="c-list__item">
                                    <figure class="c-list__item__thumb">
                                        <img src="<?php echo get_site_url().get_the_post_thumbnail_url() ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($post->post_title))); ?>">
                                    </figure>
                                    <div class="c-list__item__txt">
                                        <h3 class="c-list__item__title"><?php the_title(); ?></h3>
                                        <p class="c-list__item__subtitle"><?php echo excerpt(get_field('resumen', false, false), $contenido) ?>
                                            <a class="c-list__item__link" href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>">Leer más</a>
                                        </p>
                                    </div>
                                </article>
                                <!-- END: c-list__item-->
                            <?php endwhile; ?>
                        <?php else:?>
                            <article class="c-list__item__txt">
                                <div class="u-mb40">
                                    <h3 class="c-list__item__title">TU BUSQUEDA NO TIENE RESULTADOS</h3>
                                    <p class="c-list__item__subtitle">Lo sentimos, pero no se ha encontrado nada por el término de tu búsqueda.</p>
                                    <p class="c-list__item__subtitle">Por favor, prueba a buscar con diferentes términos.</p>
                                </div>
                            </article>
                        <?php endif;?>

                    </div>
                    <div class="u-grid u-grid-columns-2 u-mb40 u-border-top">
                        <div>
                            <!-- c  -->
                        </div>
                        <!-- START: pagenavi -->
                        <nav class="wp-pagenavi u-mt40" role="navigation">
                            <?php wp_pagenavi(); ?>
                        </nav>
                        <!-- END: pagenavi -->
                    </div>
                </div>
                <aside class="u-grid-start-3 u-grid-row-start-1 u-grid-row-span-2">
                    <!-- START: c-lomas -->
                    <div class="c-lomas u-mb40 u-border-top">
                        <h3 class="c-lomas__title"><?php echo _e('Lo más visto', 'won2020_v2') ?></h3>
                        <figure class="c-lomas__thumb">
                            <img src="<?php echo get_site_url().get_the_post_thumbnail_url($lomasvisto->posts[0]->ID) ?>" alt="<?php echo (str_replace('"', '\'', strip_tags($lomasvisto->posts[0]->post_title))); ?>">
                        </figure>

                        <?php if ($lomasvisto->have_posts()) : ?>
                            <ol class="c-lomas__list">
                                <?php while ($lomasvisto->have_posts()) : $lomasvisto->the_post(); ?>
                                    <li class="c-lomas__list-item"><a href="<?php the_permalink(); ?>" title="Leer <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Leer <?php echo (stripQuotes(get_the_title())); ?>" ><?php the_title(); ?></a></li>
                                <?php endwhile; ?>
                            </ol>
                        <?php endif; ?>
                    </div>
                    <!-- END: c-lomas -->

                    <!-- START: c-add -->
                    <!-- <div class="c-add c-add-d300x600 u-flex">
                        <div class="c-add__creatividad">
                            <img src="assets/img/add/add-300x600.jpg" alt="advertisement">
                        </div>
                    </div> -->
                    <!-- END: c-add -->

                </aside>
            </div>
        </section>
        <!-- END: o-section -->

    </div>
</main>
<!-- END: content-->


<?php get_footer(); ?>