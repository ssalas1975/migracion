<?php if ('on' == et_get_option('divi_back_to_top', 'false')) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if (! is_page_template('page-template-blank.php')) : ?>

			<footer id="main-footer">
				<?php get_sidebar('footer'); ?>


		<?php
            if (has_nav_menu('footer-menu')) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'depth'          => '1',
                                'menu_class'     => 'bottom-nav',
                                'container'      => '',
                                'fallback_cb'    => '',
                            ));
                        ?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
                    if (false !== et_get_option('show_footer_social_icons', true)) {
                        get_template_part('includes/social_icons', 'footer');
                    }

                    echo et_get_footer_credits();
                ?>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' )?>

	</div> <!-- #page-container -->
	<?php wp_footer(); ?>
	<script>
		setTimeout(function () {
			const menu = document.querySelector('#menu-item-9984 a');
			if(menu !== null){
			    menu.href = 'javascript:void();';
			    menu.setAttribute("onclick", "Voonto.cmp.show();");
			}
			const menuEng = document.querySelector('#menu-item-15939 a');
			if(menuEng != null){
				menuEng.href = 'javascript:void();';
			    menuEng.setAttribute("onclick", "Voonto.cmp.show();");
			}
		}, 3000);
	</script>
</body>
</html>