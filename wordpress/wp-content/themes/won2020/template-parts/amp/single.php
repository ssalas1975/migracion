<!DOCTYPE html>
<html amp>
<?php get_template_part('template-parts/amp/header')?>


<!-- START: content-->
<main class="content">
    <div class="o-container">
        <!-- noticia -->
        <?php if($post->post_type=='noticia'): get_template_part( 'template-parts/amp/single', 'noticia' ); ?>
        <!-- video -->
        <?php elseif($post->post_type=='videocpt'): get_template_part( 'template-parts/amp/single', 'video' ); ?>
        <!-- audio -->
        <?php elseif($post->post_type=='audio'): get_template_part( 'template-parts/amp/single', 'audio' ); ?>

        <?php endif; ?>

        <!-- modulos -->
        <?php get_template_part( 'template-parts/amp/content', 'modulos' ); ?>
    </div>
    <!-- megabanner sticky -->
    <amp-sticky-ad layout="nodisplay">
            <amp-ad data-npa-on-unknown-consent="true" data-block-on-consent width="320" height="100" type="doubleclick" data-slot="/4900/webm.WOMENNOW/AMP/<?php echo sanitize_title(get_the_category()[0]->cat_name); ?>" json='{"targeting":{"pos":"megabanner"}}' data-multi-size="320x50" data-multi-size-validation="false"></amp-ad>
        </amp-sticky-ad>
</main>
<!-- END: content-->


<?php get_template_part('template-parts/amp/footer')?>
</body>
</html>
