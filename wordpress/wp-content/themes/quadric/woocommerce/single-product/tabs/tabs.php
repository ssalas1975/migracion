<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() ); 

if ( ! empty( $tabs ) ) : ?>

	<div class="edgtf-accordion-holder edgtf-toggle edgtf-boxed woocommerce-tabs wc-tabs-wrapper">		
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<h6 class="clearfix edgtf-title-holder">
				<span class="edgtf-tab-title">
					<span class="edgtf-tab-title-inner">
						<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
					</span>
				</span>
			</h6>
			<div class="edgtf-accordion-content panel entry-content wc-tab">
				<div class="edgtf-accordion-content-inner">
					<?php call_user_func( $tab['callback'], $key, $tab ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>
