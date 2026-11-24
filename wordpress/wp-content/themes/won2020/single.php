<?php get_header(); ?>

	 <!-- START: content-->
    <main class="content">
        <div class="o-container">
            <aside  class="o-section">
                <div class="c-add c-add-d980x250 u-flex">
                    <div class="c-add__creatividad">
                        <div class="sticky-footer voc-advertising voc-adver-header hidden-lg hidden-md hidden-sm fixed-position" data-voc-advertising-close="" style="display: block;">
                            <div class="voc-advertising__close">
                                <div class="voc-advertising__close-btn" data-voc-adver-close-btn="">
                                    <i class="fa cb-times cierremegabanner"><b>x</b></i>
                                </div>
                            </div>
                        </div>
                        <!-- START: c-add -->
                        <div id="megabanner-id" class="megabanner">
                            <script>
                                googletag.cmd.push(function() { googletag.display('megabanner-id'); });
                            </script>
                        </div>
                        <!-- END: c-add -->
                    </div>
                </div>
            </aside>
            <!-- noticia -->
            <?php if($post->post_type=='noticia'): get_template_part( 'template-parts/single', 'noticia' ); ?>
            <!-- video -->
            <?php elseif($post->post_type=='videocpt'): get_template_part( 'template-parts/single', 'video' ); ?>
            <!-- audio -->
            <?php elseif($post->post_type=='audio'): get_template_part( 'template-parts/single', 'audio' ); ?>

            <?php endif; ?>

            <!-- modulos -->
            <?php get_template_part( 'template-parts/content', 'modulos' ); ?>

        </div>
    </main>
    <!-- END: content-->

<?php get_footer(); ?>
