<?php
	/* HEADER 1 */

	$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  $re = '/http(.+?)\\/\\/(.+?)\\/(.+?)\\//m';
  preg_match_all($re, $actual_link, $matches, PREG_SET_ORDER, 0);
  $menu_slug = 'Menu 1';
  if( $matches[0][3] && $matches[0][3] == '100empresasfamiliares') {
	  $menu_slug = '100empresasfamiliares';
  }

  echo '<!-- _______________ '.$menu_slug.' -->';

	if ( ! function_exists( 'jevelin_nav_wrap' ) ) :
		function jevelin_nav_wrap() {
		    $wrap  = '<ul id="%1$s" class="%2$s">%3$s';
		    $wrap .= jevelin_nav_wrap_lang();
		    $wrap .= jevelin_nav_wrap_cart();
		    $wrap .= jevelin_nav_wrap_search();
		    $wrap .= jevelin_nav_wrap_social();
		    $wrap .= jevelin_nav_wrap_login();
		    $wrap .= '</ul>';

		    return $wrap;
		}
	endif;
?>

<?php /* Header */ ?>
<div class="<?php jevelin_header_classes( 5 ); ?>">
	<div class="container">
		<div class="sh-table">
			<div class="sh-table-cell sh-group">

				<?php /* Header logo */ ?>
				<?php jevelin_header_logo(); ?>

			</div>
			<div class="sh-table-cell">

				<?php /* Header navigation */ ?>
				<nav id="header-navigation" class="header-standard-position">
					<?php if ( has_nav_menu( 'header' ) ) : ?>
						<?php
							wp_nav_menu( array(
							  'menu' => $menu_slug,
								'theme_location' => 'header',
								'depth' => 4,
								'container_class' => 'sh-nav-container',
								'menu_class' => 'sh-nav',
								'items_wrap' => jevelin_nav_wrap()
							));
						?>
					<?php else :
						jevelin_asign_menu();
					endif; ?>
				</nav>

			</div>
		</div>
	</div>

	<?php
		/* Header popup search */
		get_template_part('inc/headers/header-search');
	?>
</div>
