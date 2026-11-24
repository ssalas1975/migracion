<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */
?>
<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">
		<div class="container-fluid p-3 p-md-5">
			<div class="site-info">
                <?php /*?>&copy; <?php echo date('Y'); ?> <?php echo '<a href="'.home_url().'">'.get_bloginfo('name').'</a>'; ?>
                <span class="sep"> | </span>
                <a class="credits" href="https://afterimagedesigns.com/wp-bootstrap-starter/" target="_blank" title="Wordpress Technical Support" alt="Bootstrap Wordpress Theme"><?php echo esc_html__('Bootstrap Wordpress Theme','wp-bootstrap-starter'); ?></a><?php */?>
                <!--.:-:.-->
                <div class="redes">
                	<div class="contenedor-centrado">
                    	<div class="contenido-centrado">
                        	<?php if (!dynamic_sidebar('pie-redes')) {} ?>
                        </div>
                    </div>
                </div>
                <!--.:-:.-->
                <div class="organiza">
                	<?php if (!dynamic_sidebar('pie-organiza')) {} ?>
                </div>
                <!--.:-:.-->
                <div class="copy">
                	<div class="contenedor-centrado">
                    	<div class="contenido-centrado">
                        	<?php if (!dynamic_sidebar('pie-copy')) {} ?>
                        </div>
                    </div>
                </div>
                <!--.:-:.-->
                <div class="legal">
                	<div class="contenedor-centrado">
                    	<div class="contenido-centrado">
                        	<?php if (!dynamic_sidebar('pie-legal')) {} ?>
                        </div>
                    </div>
                </div>
                <!--.:-:.-->
                <div class="version" style="display:none;">versión 2018/11/08 14:41</div>
            </div>
			<!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>