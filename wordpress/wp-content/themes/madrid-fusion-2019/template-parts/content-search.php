<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<?php /*?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php wp_bootstrap_starter_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php wp_bootstrap_starter_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## --><?php */?>

				<article class="resultadoBusqueda">
                    <div class="itemListado cocineros-ficha-recetas">
                        <div class="imagen">
                            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail() ?></a>
                        </div>
                        <div class="texto">
                        	<div class="taxonomia">
                             <a href="<?php echo link_cpt( get_post_type( $post->ID )); ?>"><?php echo get_post_type( $post->ID ) ?></a>  
                            </div>
                            <div class="titular">
                                <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
                            </div>
                            <div class="autor">
                                <p><?php the_author_posts_link() ?></p>
                            </div>
                            <div class="fecha">
                                <p><?php echo get_the_date()  ?></p>
                            </div>
                            <div class="extracto">
                                <p><?php the_excerpt() ?></p>
                            </div>
                        </div>
                    </div>
                </article>
