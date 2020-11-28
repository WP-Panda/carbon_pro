<?php

if ( ! function_exists( 'wpp_fr_find_product_in_cart' ) ) :

	/**
	 * Проверка наличия продукта в корзине
	 *
	 * @param null|string|integer $product_id - id продукта
	 * @param null|array $data - допполя продукта
	 * @param null|array $flag_data - произвольный массив с данными
	 *
	 * @return bool
	 */

	function wpp_fr_find_product_in_cart( $product_id = null, $data = null, $flag_data = null ) {

		if ( is_admin() ) {
			return false;
		}

		if ( empty( $product_id ) ) {
			global $product;
			$product_id = $product->get_id();
		}

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product_in_cart = $cart_item['product_id'];
			if ( $product_in_cart === $product_id && ! empty( $data['wpp_add_variants'] ) && ! empty( $cart_item['wpp_add_variants'] && $data['wpp_add_variants'] === $cart_item['wpp_add_variants'] ) ) {
				return true;
			}
		}

		return false;

	}

endif;