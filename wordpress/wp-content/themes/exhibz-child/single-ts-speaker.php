<?php
/**
 * The template for displaying a single speaker.
 */

get_header();
get_template_part( 'template-parts/banner/content', 'banner-page' );

?>
<div id="main-content" class="main-container blog-single" role="main">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="content-single">
                <div class="entry-content">
                    <?php get_template_part( 'template-parts/blog/contents/content', 'single-speaker' ); ?>
                </div>

                <footer class="entry-footer clearfix">
                    <?php get_template_part( 'template-parts/blog/post-parts/part', 'tags' ); ?>

                    <?php if ( is_user_logged_in() ) { ?>
                        <p>
                            <?php
                            edit_post_link(
                                esc_html__( 'Edit', 'exhibz' ),
                                '<span class="meta-edit">',
                                '</span>'
                            );
                            ?>
                        </p>
                    <?php } ?>
                </footer>
            </div>

            <?php comments_template(); ?>
        <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>
