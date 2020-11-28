<?php
/**
 * Установка сохраненной корзины пользователю при переходе по ссылке
 *
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'wpp_br_get_set_saved_cart' ) ) :

	/**
	 * Установка сохраненной корзины для пользователя
	 *
	 * @throws Exception
	 */

	function wpp_br_get_set_saved_cart() {

		global $wpdb;

		$cart_key      = get_query_var( 'saved-cart' );
		$db_table_name = $wpdb->prefix . 'wpp_woo_saved_carts';

		if ( ! empty( $cart_key ) ) {

			$data_item = $wpdb->get_row( $wpdb->prepare( "SELECT `cart` FROM $db_table_name WHERE `key` = %s OR `id` = %d", $cart_key, (int)$cart_key ) );

			if ( empty( $data_item->cart) ) {
				return false;
			}

			WC()->cart->empty_cart();

			// наплолнение корзины данными
			foreach ( unserialize( $data_item->cart ) as $hash => $val ) {

				$data = [];

				$fields = [
					'wpp_add_bundle', # если в составе бандла
					'wpp_add_variants',    # вариант / ключь варианта
					'puck_id',# ID пакета к которому относится( Для покраски/ установки )
					'assembly',# нудна ли установка
					'paint'# адо ли покраску
				];

				foreach ( $fields as $field ) {
					if ( ! empty( $val[ $field ] ) ) :
						$data[ $field ] = $val[ $field ];
					endif;

				}

				$product_id = (int) $val['product_id'];
				$quantity   = empty( $val['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $val['quantity'] ) );

				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				$product_status    = get_post_status( $product_id );

				#Добавление в корзину
				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, 0, [], $data ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				}

			}


		}


	}

	add_action( 'wp', 'wpp_br_get_set_saved_cart', 100 );

endif;