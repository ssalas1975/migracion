<?php
if ( et_theme_builder_overrides_layout( ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ) || et_theme_builder_overrides_layout( ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ) ) {
    // Skip rendering anything as this partial is being buffered anyway.
    // In addition, avoids get_sidebar() issues since that uses
    // locate_template() with require_once.
    return;
}

/**
 * Fires after the main content, before the footer is output.
 *
 * @since 3.10
 */
do_action( 'et_after_main_content' );

if ( 'on' === et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div>

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
					if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );
					}

					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					echo et_core_fix_unclosed_html_tags( et_core_esc_previously( et_get_footer_credits() ) );
					// phpcs:enable
				?>
					</div>
				</div>
			</footer>
		</div>

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div>

	<?php wp_footer(); ?>

	<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/popper.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/bootstrap.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/modernizr.custom.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/jquery.gridrotator.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/mobile-detect.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/owl.carousel.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/aos.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/parsley.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/parsley.es.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/infobox.js?ver=1.0'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.2/TweenMax.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.2/utils/Draggable.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/plax.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/isotope.pkgd.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/cells-by-row.js?ver=1.0'></script>
<script type='text/javascript' src='https://static.laverdad.es/includes/manuales/laverdad/eventos/js/main.js?ver=1.4'></script>


</body>
</html>
