<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<p class="cart-empty"><?php _e( 'Your cart is currently empty.', 'quadric' ) ?></p>

<?php do_action( 'woocommerce_cart_is_empty' ); ?>

<p class="return-to-shop">

<?php
	$button = edgtf_quadric_get_button_html(
		array(
			'custom_class'	=> 'button wc-backward',
			'text'			=> esc_html__( 'Return To Shop', 'quadric' ),
			'html_type'		=> 'anchor',
			'type'			=> 'solid',
			'link'			=> apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ),	
			'fe_icon'		=> 'icon_bag_alt',
			'icon_pack'		=> 'font_elegant'
		)
	);

	print $button;
?>

</p>
