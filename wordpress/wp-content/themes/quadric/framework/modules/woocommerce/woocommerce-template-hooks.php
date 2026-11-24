<?php

if (!function_exists('edgtf_quadric_woocommerce_products_per_page')) {
	/**
	 * Function that sets number of products per page. Default is 12
	 * @return int number of products to be shown per page
	 */
	function edgtf_quadric_woocommerce_products_per_page() {

		$products_per_page = 12;

		if (edgtf_quadric_options()->getOptionValue('edgtf_woo_products_per_page')) {
			$products_per_page = edgtf_quadric_options()->getOptionValue('edgtf_woo_products_per_page');
		}

		return $products_per_page;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_related_products_args')) {
	/**
	 * Function that sets number of displayed related products. Hooks to woocommerce_output_related_products_args filter
	 * @param $args array array of args for the query
	 * @return mixed array of changed args
	 */
	function edgtf_quadric_woocommerce_related_products_args($args) {

		if (edgtf_quadric_options()->getOptionValue('edgtf_woo_product_list_columns')) {

			$related = edgtf_quadric_options()->getOptionValue('edgtf_woo_product_list_columns');
			switch ($related) {
				case 'edgtf-woocommerce-columns-4':
					$args['posts_per_page'] = 4;
					break;
				case 'edgtf-woocommerce-columns-3':
					$args['posts_per_page'] = 3;
					break;
				default:
					$args['posts_per_page'] = 3;
			}

		} else {
			$args['posts_per_page'] = 3;
		}

		return $args;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_template_loop_product_title')) {
	/**
	 * Function for overriding product title template in Product List Loop
	 */
	function edgtf_quadric_woocommerce_template_loop_product_title() {

		$tag = edgtf_quadric_options()->getOptionValue('edgtf_products_list_title_tag');
		the_title('<' . $tag . ' class="edgtf-product-list-product-title">', '</' . $tag . '>');

	}

}

if (!function_exists('edgtf_quadric_woocommerce_template_single_title')) {
	/**
	 * Function for overriding product title template in Single Product template
	 */
	function edgtf_quadric_woocommerce_template_single_title() {

		$tag = edgtf_quadric_options()->getOptionValue('edgtf_single_product_title_tag');
		print do_shortcode('[edgtf_zig_zag_separator position="left" animated="no" size="medium"]');
		the_title('<' . $tag . '  itemprop="name" class="edgtf-single-product-title">', '</' . $tag . '>');

	}

}

if (!function_exists('edgtf_quadric_woocommerce_sale_flash')) {
	/**
	 * Function for overriding Sale Flash Template
	 *
	 * @return string
	 */
	function edgtf_quadric_woocommerce_sale_flash() {

		return '<span class="edgtf-onsale"><span class="edgtf-onsale-inner">' . esc_html__('Sale!', 'quadric') . '</span></span>';

	}

}

if (!function_exists('edgtf_quadric_custom_override_checkout_fields')) {
	/**
	 * Overrides placeholder values for checkout fields
	 * @param array all checkout fields
	 * @return array checkout fields with overriden values
	 */
	function edgtf_quadric_custom_override_checkout_fields($fields) {
		//billing fields
		$args_billing = array(
			'first_name'	=> esc_html__('First name','quadric'),
			'last_name'		=> esc_html__('Last name','quadric'),
			'company'		=> esc_html__('Company name','quadric'),
			'address_1'		=> esc_html__('Address','quadric'),
			'email'			=> esc_html__('Email','quadric'),
			'phone'			=> esc_html__('Phone','quadric'),
			'postcode'		=> esc_html__('Postcode / ZIP','quadric'),
			'state'			=> esc_html__('State / County', 'quadric')
		);

		//shipping fields
		$args_shipping = array(
			'first_name' => esc_html__('First name','quadric'),
			'last_name'  => esc_html__('Last name','quadric'),
			'company'    => esc_html__('Company name','quadric'),
			'address_1'  => esc_html__('Address','quadric'),
			'postcode'   => esc_html__('Postcode / ZIP','quadric')
		);

		//override billing placeholder values
		foreach ($args_billing as $key => $value) {
			$fields["billing"]["billing_{$key}"]["placeholder"] = $value;
		}

		//override shipping placeholder values
		foreach ($args_shipping as $key => $value) {
			$fields["shipping"]["shipping_{$key}"]["placeholder"] = $value;
		}

		return $fields;
	}

}

if (!function_exists('edgtf_quadric_woocommerce_loop_add_to_cart_link')) {
	/**
	 * Function that overrides default woocommerce add to cart button on product list
	 * Uses HTML from edgtf_button
	 *
	 * @return mixed|string
	 */
	function edgtf_quadric_woocommerce_loop_add_to_cart_link() {

		global $product;

		$classes = '';
		$classes .= $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button ' : ' ';
		$classes .= $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart ' : ' ';

		$button_url = $product->add_to_cart_url();
		$button_classes = sprintf('%s product_type_%s',
			$classes,
			esc_attr( $product->product_type )
		);
		$button_text = $product->add_to_cart_text();
		$button_attrs = array(
			'rel' => 'nofollow',
			'data-product_id' => esc_attr( $product->id ),
			'data-product_sku' => esc_attr( $product->get_sku() ),
			'data-quantity' => esc_attr( isset( $quantity ) ? $quantity : 1 )
		);

		$icon = 'icon_bag_alt';
		$icon_pack = 'font_elegant';

		$add_to_cart_button = edgtf_quadric_get_button_html(
			array(
				'link'			=> $button_url,
				'custom_class'	=> $button_classes,
				'text'			=> $button_text,
				'custom_attrs'	=> $button_attrs,
				'fe_icon'		=> $icon,
				'icon_pack'		=> $icon_pack
			)
		);

		return $add_to_cart_button;

	}

}

if (!function_exists('edgtf_quadric_get_woocommerce_add_to_cart_button')) {
	/**
	 * Function that overrides default woocommerce add to cart button on simple and grouped product single template
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_get_woocommerce_add_to_cart_button() {

		global $product;

		$icon = 'icon_bag_alt';
		$icon_pack = 'font_elegant';

		$add_to_cart_button = edgtf_quadric_get_button_html(
			array(
				'custom_class'	=> 'single_add_to_cart_button alt',
				'text'			=> $product->single_add_to_cart_text(),
				'html_type'		=> 'button',
				'fe_icon'		=> $icon,
				'icon_pack'		=> $icon_pack
			)
		);

		print $add_to_cart_button;

	}

}

if (!function_exists('edgtf_quadric_get_woocommerce_add_to_cart_button_external')) {
	/**
	 * Function that overrides default woocommerce add to cart button on external product single template
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_get_woocommerce_add_to_cart_button_external() {

		global $product;

		$icon = 'icon_bag_alt';
		$icon_pack = 'font_elegant';

		$add_to_cart_button = edgtf_quadric_get_button_html(
			array(
				'link'			=> $product->add_to_cart_url(),
				'custom_class'	=> 'single_add_to_cart_button alt',
				'text'			=> $product->single_add_to_cart_text(),
				'custom_attrs'	=> array(
					'rel' 		=> 'nofollow'
				),
				'fe_icon'		=> $icon,
				'icon_pack'		=> $icon_pack
			)
		);

		print $add_to_cart_button;

	}

}

if ( ! function_exists('edgtf_quadric_woocommerce_single_variation_add_to_cart_button') ) {
	/**
	 * Function that overrides default woocommerce add to cart button on variable product single template
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_woocommerce_single_variation_add_to_cart_button() {
		global $product;

		$icon = 'icon_bag_alt';
		$icon_pack = 'font_elegant';

		$html = '<div class="variations_button">';
		woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) );

		$button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'button',
			'custom_class'	=> 'single_add_to_cart_button alt',
			'text'			=> $product->single_add_to_cart_text(),
			'fe_icon'		=> $icon,
			'icon_pack'		=> $icon_pack
		));

		$html .= $button;

		$html .= '<input type="hidden" name="add-to-cart" value="' . absint( $product->id ) . '" />';
		$html .= '<input type="hidden" name="product_id" value="' . absint( $product->id ) . '" />';
		$html .= '<input type="hidden" name="variation_id" class="variation_id" value="" />';
		$html .= '</div>';

		print $html;

	}

}

if (!function_exists('edgtf_quadric_get_woocommerce_apply_coupon_button')) {
	/**
	 * Function that overrides default woocommerce apply coupon button
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_get_woocommerce_apply_coupon_button() {

		$coupon_button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'input',
			'input_name'	=> 'apply_coupon',
			'type'			=> 'solid',
			'text'			=> esc_html__( 'Apply Coupon', 'quadric' )
		));

		print $coupon_button;

	}

}

if (!function_exists('edgtf_quadric_get_woocommerce_update_cart_button')) {
	/**
	 * Function that overrides default woocommerce update cart button
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_get_woocommerce_update_cart_button() {

		$update_cart_button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'input',
			'input_name'	=> 'update_cart',
			'type'			=> 'solid',
			'text'			=> esc_html__( 'Update Cart', 'quadric' )
		));

		print $update_cart_button;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_button_proceed_to_checkout')) {
	/**
	 * Function that overrides default woocommerce proceed to checkout button
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_woocommerce_button_proceed_to_checkout() {

		$proceed_to_checkout_button = edgtf_quadric_get_button_html(array(
			'link'			=> WC()->cart->get_checkout_url(),
			'type'			=> 'solid',
			'custom_class'	=> 'checkout-button alt wc-forward',
			'text'			=> esc_html__( 'Proceed to Checkout', 'quadric' )
		));

		print $proceed_to_checkout_button;

	}

}

if (!function_exists('edgtf_quadric_get_woocommerce_update_totals_button')) {
	/**
	 * Function that overrides default woocommerce update totals button
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_get_woocommerce_update_totals_button() {

		$update_totals_button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'button',
			'text'			=> esc_html__( 'Update Totals', 'quadric' ),
			'custom_attrs'	=> array(
				'value'		=> 1,
				'type'		=> 'solid',
				'name'		=> 'calc_shipping'
			)
		));

		print $update_totals_button;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_pay_order_button_html')) {
	/**
	 * Function that overrides default woocommerce pay order button on checkout page
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_woocommerce_pay_order_button_html() {

		$pay_order_button_text = esc_html__('Pay for order', 'quadric');

		$place_order_button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'input',
			'custom_class'	=> 'alt',
			'custom_attrs'	=> array(
				'id'			=> 'place_order',
				'data-value'	=> $pay_order_button_text
			),
			'text'			=> $pay_order_button_text,
		));

		return $place_order_button;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_order_button_html')) {
	/**
	 * Function that overrides default woocommerce place order button on checkout page
	 * Uses HTML from edgtf_button
	 */
	function edgtf_quadric_woocommerce_order_button_html() {

		$pay_order_button_text = esc_html__('Place Order', 'quadric');

		$place_order_button = edgtf_quadric_get_button_html(array(
			'html_type'		=> 'input',
			'custom_class'	=> 'alt',
			'custom_attrs'	=> array(
				'id'			=> 'place_order',
				'data-value'	=> $pay_order_button_text,
				'name'			=> 'woocommerce_checkout_place_order'
			),
			'text'			=> $pay_order_button_text,
		));

		return $place_order_button;

	}

}

if (!function_exists('edgtf_quadric_woocommerce_thumbnails_col')) {
	/**
	 * Function that overrides default woocommerce number of columns 
	 */
	function edgtf_quadric_woocommerce_thumbnails_col() {
		$number  = 4;
     	return $number; 
 	}

}