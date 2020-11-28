<?php
/**
 * Snippets for WooCommerce
 */


/**
 * Cart Snippets
 */
if ( ! function_exists( '' ) ) :
	/**
	 * Only one product in cart
	 * @since         1.0.3
	 * @snippet       WooCommerce Max 1 Product @ Cart
	 * @compatible    WC 3.5.4
	 */

	function wpp_only_one_in_cart( $passed, $added_product_id ) {

// empty cart first: new item will replace previous
		wc_empty_cart();

		return $passed;
	}

endif;
#add_filter( 'woocommerce_add_to_cart_validation', 'wpp_only_one_in_cart', 99, 2 );


if ( ! function_exists( 'wpp_only_one_product_per_row' ) ) :
	/**
	 * Split Product quantities in cart
	 *
	 * @param $cart_item_data
	 * @param $product_id
	 *
	 * @return mixed
	 */
	function wpp_only_one_product_per_row( $cart_item_data, $product_id ) {

		$cart_item_data['unique_key'] = uniqid();

		return $cart_item_data;

	}
endif;

#add_filter( 'woocommerce_add_cart_item_data', 'wpp_only_one_product_per_row', 10, 2 );

/**
 * Продавть продукты индивидуально
 */
#add_filter( 'woocommerce_is_sold_individually', '__return_true' );