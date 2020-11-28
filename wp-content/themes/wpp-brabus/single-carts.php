<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

//получение сохраненной корзины
$cart_content = get_post_meta( get_queried_object_id(), '_cart', 'true' );

// наплолнение корзины данными
foreach ( unserialize( $cart_content ) as $hash => $val ) {

	$data = [];

	#'product_id'    => $val['product_id'],
	#'wpp_add_variants' => $val['wpp_add_variants'],
	#'wpp_add_bundle'        => $val['wpp_add_bundle'],
	#'quantity'      => $val['quantity'],
	#'line_subtotal'    => $val['line_subtotal'],
	#'sku'              => $val['sku'],
	#'puck_id'          => $val['puck_id'],
	#'name'             => get_the_title( $val['product_id'] ),
	#'assembly'         => $val['assembly'],
	#'paint'            => $val['paint']


	# если в составе бандла
	if ( ! empty( $val['wpp_add_bundle'] ) ) :
		$data['wpp_add_bundle'] = $val['wpp_add_bundle'];
	endif;

	# вариант / ключь варианта
	if ( ! empty( $val['wpp_add_variants'] ) ) :
		$data['wpp_add_variants'] = $val['wpp_add_variants'];
	endif;

	# ID пакета к которому относится( Для покраски/ установки )
	if ( ! empty( $val['puck_id'] ) ) :
		$data['puck_id'] = $val['puck_id'];
	endif;

	# нудна ли установка
	if ( ! empty( $val['assembly'] ) ) :
		$data['assembly'] = $val['assembly'];
	endif;

	# адо ли покраску
	if ( ! empty( $val['paint'] ) ) :
		$data['paint'] = $val['paint'];
	endif;


	$product_id = (int) $val['product_id'];
	$quantity   = empty( $val['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $val['quantity'] ) );

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
	$product_status    = get_post_status( $product_id );

	#Добавление в корзину
	if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $data ) && 'publish' === $product_status ) {

		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( [ $product_id => $quantity ], true );
		}

		//WC_AJAX::get_refreshed_fragments();

	} else {

		// If there was an error adding to the cart, redirect to the product page to show any errors.
		$data = [
			'error'       => true,
			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
		];

	}


}