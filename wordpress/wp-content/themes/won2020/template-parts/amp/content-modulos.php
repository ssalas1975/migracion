
<?php
    $current_ID = $GLOBALS['current_id'];
    $current_type = $GLOBALS['current_type'];
    $show_roba2 = check_paragraphs(get_field('contenido'),$current_ID);
    $home = get_page_by_title('Home');
    if (have_rows('modulos', $home->ID)) : while (have_rows('modulos', $home->ID)) : the_row();
    $pagina = (get_sub_field('mostrar')) ? get_sub_field('mostrar') : array();
    $tipo = (get_sub_field('mostrar_int')) ? get_sub_field('mostrar_int') : array();
    if (in_array($current_ID, $pagina) || in_array($current_type, $tipo)) :

?>
<!-- START: o-section carousel-->
<section class="o-section">
    <?php $posts = get_sub_field('contenido');
    $cabeceras = (strpos(get_sub_field('titulo'), ',') !== false) ? explode(',', get_sub_field('titulo')) : ''; ?>
    <?php if ($cabeceras == '') : ?>
        <p class="c-title"><?php echo get_sub_field('titulo'); ?></p>
    <?php endif; ?>
    <?php if ( get_sub_field('titulo')=='TE INTERESA') : ?>
    <!-- START: Te interesa -->
        <?php $posts = get_sub_field('contenido'); if ($posts): ?>
        <div class="u-grid u-grid-columns-3 u-mb40">
            <?php foreach ($posts as $key => $post): setup_postdata($post); ?>
            <article class="c-article">
            <figure class="c-article__thumb <?php if (get_field('video')||$post->post_type=='videocpt') {
                        echo 'video ';
                    } if (get_field('galeria')) {
                        echo 'gallery';
                    } ?>">
                        <amp-img src="<?php echo get_the_post_thumbnail_url() ?>" alt="thumb article" width="410" height="290" layout="responsive"></amp-img>
                    </figure>
                    <div class="c-article__txt">
                        <p class="c-article__tag"><?php echo get_field('pretitulo') ?></p>
                        <h3 class="c-article__title">
                            <a href="<?php the_permalink(); ?>" title="Te interesa ver <?php echo (stripQuotes(get_the_title())); ?>" aria-label="Te interesa ver <?php echo (stripQuotes(get_the_title())); ?>" ><?php the_title(); ?></a>
                        </h3>
                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); endif; ?>
        </div>

    <!-- END: Te interesa -->
    <?php endif; ?>
    <?php if ( get_sub_field('titulo')=='NO TE PIERDAS') : ?>
       <!-- START: carousel -->
        <amp-carousel height="460" layout="fixed-height" type="carousel" role="region" class="carousel">

            <?php $posts = get_sub_field('contenido'); if ($posts): ?>
            <?php foreach ($posts as $key => $post): setup_postdata($post); ?>
                <article class="c-article">
                    <figure class="c-article__thumb <?php if (get_field('video')||$post->post_type=='videocpt') {
                        echo 'video ';
                    } if (get_field('galeria')) {
                        echo 'gallery';
                    } ?>">
                        <amp-img src="<?php echo get_the_post_thumbnail_url();?>" width="410" height="290" alt="thumb article"></amp-img>
                    </figure>
                    <div class="c-article__txt">
                        <?php $total=110; $contenido=$total-strlen(get_the_title());?>
                        <p class="c-article__tag"><?php echo get_field('pretitulo') ?></p>
                        <h3 class="c-article__title">
                            <?php if (count($posts)<=3||get_sub_field('carrusel')==1): ?>
                                <a href="<?php the_permalink() ?>" title="Leer <?php echo stripQuotes(get_the_title()); ?>" aria-label="Leer <?php echo stripQuotes(get_the_title()); ?>" >
                            <?php else: ?>
                                <a href="<?php the_permalink() ?>" title="Leer <?php echo (strlen(stripQuotes(get_the_title()))>=$total)?title_excerpt(stripQuotes(get_the_title()), $total):stripQuotes(get_the_title()); ?>" aria-label="Leer <?php echo (strlen(stripQuotes(get_the_title()))>=$total)?title_excerpt(stripQuotes(get_the_title()), $total):stripQuotes(get_the_title()); ?>" >
                            <?php endif; ?>
                                <?php if (count($posts)<=3||get_sub_field('carrusel')==1): ?>
                                    <?php echo get_the_title(); ?>
                                <?php else: ?>
                                    <?php echo (strlen(get_the_title())>=$total)?title_excerpt(get_the_title(), $total):get_the_title(); ?>
                                <?php endif; ?>
                            </a>
                        </h3>
                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); endif; ?>
    </amp-carousel>
    <!-- END: carousel-->
        <?php if ( $current_type != 'noticia' || $show_roba2) : ?>
            <amp-ad data-loading-strategy="0.5" data-npa-on-unknown-consent="true" data-block-on-consent width="300" height="600" style="display:block; margin: 15px auto;" type="doubleclick" data-slot="/4900/webm.WOMENNOW/AMP/<?php echo sanitize_title(get_the_category()[0]->cat_name); ?>" json='{"targeting":{"pos":"robapaginas_dos"}}' data-multi-size="300x250" data-multi-size-validation="false"></amp-ad>
        <?php endif; ?>
    <?php endif; ?>

</section>

    <?php endif; ?>

    <?php  endwhile; wp_reset_postdata(); endif;  ?>
<!-- END: o-section carousel-->

<amp-ad data-loading-strategy="0.5" data-npa-on-unknown-consent="true" data-block-on-consent width="320" height="100" type="doubleclick" style="display:block; margin:auto;" data-slot="/4900/webm.WOMENNOW/AMP/<?php echo sanitize_title(get_the_category()[0]->cat_name); ?>" json='{"targeting":{"pos":"megabanner_dos"}}' data-multi-size="320x50" data-multi-size-validation="false"></amp-ad>

<!-- START: o-section interests -->
<section class="o-section">
	<div class="c-interest">
		<p class="c-interest__title u-flex"><?php echo _e('Enlaces de Interés', 'won2020_v2') ?></p>
		<nav class="c-interest__list u-flex">
			<?php $terms = get_terms('category');
			foreach ($terms as $term) : ?>
				<a class="c-interest__list-item" href="<?php echo site_url() . '/tags/' . $term->slug; ?>/" title=""><?php echo $term->name; ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>
<!-- END: o-section interests-->
