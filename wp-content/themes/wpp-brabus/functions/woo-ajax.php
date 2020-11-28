<?php
/**
 * Кнопка очистки корзины
 */
function wpp_br_clear_cart() {

	check_ajax_referer( 'wpp-cart-string', 'security' );

	global $woocommerce;
	$woocommerce->cart->empty_cart();

	wp_send_json_success();

}

add_action( 'wp_ajax_wpp_br_clear_cart', 'wpp_br_clear_cart' );
add_action( 'wp_ajax_nopriv_wpp_br_clear_cart', 'wpp_br_clear_cart' );


/**
 * Добавление одного продукта в корзину
 * @throws Exception
 */
function wpp_ajax_cart_cat_callback() {

	/*	header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Credentials: true' );*/

	#check_ajax_referer( 'wpp-cart-string', 'security' );

	if ( ! empty( $_POST['single'] ) ) {
		parse_str( $_POST['data_product'], $data );
	} else {
		parse_str( parse_url( $_POST['data_product'] )['query'], $data );
	}

	$product_id = ! empty( (int) $data['add-to-cart'] ) ? (int) $data['add-to-cart'] : null;

	if ( empty( $product_id ) ) :
		wp_send_json_error( [ 'message' => __( 'Product ID is Empty', 'wpp-fr' ) ] );
	endif;

	$quantity     = empty( $data['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $data['quantity'] ) );
	$variation_id = 0;
	$variation    = [];

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
	$product_status    = get_post_status( $product_id );

	unset( $data['quantity'] );
	unset( $data['add-to-cart'] );

	wpp_d_log( '$data' );
	wpp_d_log( $data );


	$sdder = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $data );
	wpp_d_log( '$sdder' );
	wpp_d_log( $sdder );
	if ( $passed_validation && false !== $sdder && 'publish' === $product_status ) {

		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( [ $product_id => $quantity ], true );
		}

		wpp_d_log( '$data2' );
		wpp_d_log( $data );

		WC_AJAX::get_refreshed_fragments();

	} else {

		// If there was an error adding to the cart, redirect to the product page to show any errors.
		$data = [
			'error'       => true,
			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
		];

		wp_send_json( $data );
	}

	wp_die();
}

add_action( 'wp_ajax_wpp_ajax_cart_cat', 'wpp_ajax_cart_cat_callback' );
add_action( 'wp_ajax_nopriv_wpp_ajax_cart_cat', 'wpp_ajax_cart_cat_callback' );

/**
 * Добавление пакета в корзину
 * @throws Exception
 */
function wpp_ajax_cart_bundle_callback() {

	#check_ajax_referer( 'wpp-cart-string', 'security' );

	if ( ! empty( $_POST['single'] ) ) {
		parse_str( $_POST['data_product'], $data );
	} else {
		parse_str( parse_url( $_POST['data_product'] )['query'], $data );
	}

	#wp_send_json_success( $data );

	$product_ids = ! empty( $data['add-to-cart'] ) ? explode( ',', $data['add-to-cart'] ) : null;
	$config      = ! empty( $data['wpp_add_variants'] ) ? explode( ',', $data['wpp_add_variants'] ) : null;
	$bundle      = ! empty( $data['bundle'] ) ? $data['bundle'] : null;

	$assembly = ! empty( $data['b_assembly'] ) ? true : false;
	$paint    = ! empty( $data['b_paint'] ) ? true : false;

	if ( empty( $product_ids ) ) :
		wp_send_json_error( [ 'message' => __( 'Product ID is Empty', 'wpp-fr' ) ] );
	endif;


	/*if ( ! empty( $paint ) ) {
		WC()->cart->add_to_cart( 10543, 1, 0, [], [ 'puck_id' => $bundle ] );
	}*/

	foreach ( $product_ids as $key => $product_id ) {

		$quantity     = empty( $data['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $data['quantity'] ) );
		$variation_id = 0;
		$variation    = [];

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );

		/*unset( $data['quantity'] );
		unset( $data['add-to-cart'] );*/

		$addit = [
			'wpp_add_variants' => $config[ $key ],
			'wpp_add_bundle'   => $bundle
		];


		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $addit ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( [ $product_id => $quantity ], true );
			}

			//

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$out = [
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			];

			wp_send_json( $out );
		}
	}

	if ( ! empty( $assembly ) ) {
		WC()->cart->add_to_cart( ASSEM, 1, 0, [], [ 'puck_id' => $bundle ] );
		#wpp_d_log(ASSEM);
	}

	if ( ! empty( $paint ) ) {
		WC()->cart->add_to_cart( PAINT, 1, 0, [], [ 'puck_id' => $bundle ] );
	}
	#wpp_d_log( WC()->cart->cart_contents);

	WC_AJAX::get_refreshed_fragments();
	wp_send_json_success( [ $paint, $data['b_paint'] ] );
}

add_action( 'wp_ajax_wpp_ajax_cart_bundle', 'wpp_ajax_cart_bundle_callback' );
add_action( 'wp_ajax_nopriv_wpp_ajax_cart_bundle', 'wpp_ajax_cart_bundle_callback' );

/**
 * Изменение количества в корзине
 */
function ajax_qty_cart() {

	// Set item key as the hash found in input.qty's name
	$cart_item_key = $_POST['hash'];

	wpp_d_log( $cart_item_key );

	// Get the array of values owned by the product we're updating
	$threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );

	if ( ! empty( WC()->cart->cart_contents[ $cart_item_key ]['wpp_add_bundle'] ) ) :
		$current_bundle = WC()->cart->cart_contents[ $cart_item_key ]['wpp_add_bundle'];
	endif;


	if ( ! empty( $_POST['type'] ) && $_POST['type'] === 'product' ) {
		// Get the quantity of the item in the cart
		$threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var( $_POST['quantity'], FILTER_SANITIZE_NUMBER_INT ) ) ), $cart_item_key );

		// Update cart validation
		$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );

		// Update the quantity of the item in the cart
		if ( $passed_validation ) {
			WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
		}
	} else {
		$type                                                = esc_attr( $_POST['type'] );
		WC()->cart->cart_contents[ $cart_item_key ][ $type ] = preg_replace( "/[^0-9\.]/", '', filter_var( $_POST['quantity'], FILTER_SANITIZE_NUMBER_INT ) );
	}


	if ( ! empty( $current_bundle ) ) {

		$bundles       = wp_list_pluck( WC()->cart->cart_contents, 'wpp_add_bundle' );
		$pack_items    = get_post_meta( (int) $current_bundle, 'bundle_package', false );
		$count         = (int) count( $pack_items );
		$count_in_cart = count( array_keys( $bundles, $current_bundle ) );


		if ( (int) $count > (int) $count_in_cart ) {
			foreach ( WC()->cart->cart_contents as $hash => $value ) {


				if ( ! empty( $value['wpp_add_bundle'] ) && (int) $value['wpp_add_bundle'] === (int) $current_bundle ) {

					unset( WC()->cart->cart_contents[ $hash ]['wpp_add_bundle'] );
				}

				if ( ! empty( $value['puck_id'] ) && (int) $value['puck_id'] === (int) $current_bundle ) {

					unset( WC()->cart->cart_contents[ $hash ] );
				}

			}
		}

	}

	WC()->cart->calculate_totals();
	// Refresh the page
	echo do_shortcode( '[woocommerce_cart]' );

	die();

}

add_action( 'wp_ajax_qty_cart', 'ajax_qty_cart' );
add_action( 'wp_ajax_nopriv_qty_cart', 'ajax_qty_cart' );


/**
 * Удаление установки покраски из товара
 */
function ajax_remove_additional() {
	/*$type = ! empty( $_POST['type'] ) ? esc_attr( $_POST['type'] ) : false;
	if ( empty( $type ) ) {
		wp_send_json_error( [ 'message' => 'Type is empty' ] );
	}*/

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
		WC()->cart->cart_contents[ $cart_item_key ]['assembly'] = 0;
		WC()->cart->cart_contents[ $cart_item_key ]['paint']    = 0;
	endforeach;

	echo do_shortcode( '[woocommerce_cart]' );

	die();
}

add_action( 'wp_ajax_ajax_remove_additional', 'ajax_remove_additional' );
add_action( 'wp_ajax_nopriv_ajax_remove_additional', 'ajax_remove_additional' );


add_action( 'woocommerce_before_calculate_totals', 'before_calculate_totals', 1000, 1 );
function before_calculate_totals( $cart_obj ) {
	// loop through the cart_contents
	foreach ( $cart_obj->get_cart() as $key => $value ) {

		if ( (int) $value['product_id'] === PAINT || (int) $value['product_id'] === ASSEM ) {

			$puck = (int) $value['puck_id'];

			if ( (int) $value['product_id'] === ASSEM ) {
				$assembly     = get_post_meta( (int) $puck, 'assembly', true );
				$custom_price = $assembly;
			} else {
				$paint        = get_post_meta( (int) $puck, 'paint', true );
				$custom_price = $paint;
			}

			$value['data']->set_price( wpp_return_ads_price_opt( $custom_price, 0 ) );
		}


		#wpp_console( $value );

		if ( ! empty( $value['wpp_add_bundle'] ) ) {

			$sale  = (int) get_post_meta( (int) $value['wpp_add_bundle'], 'bundle_sale', true );
			$price = $value['data']->get_price();

			$sale_coeff = ! empty( $sale ) ? 1 - ( $sale / 100 ) : 1;


			$value['data']->set_price( $price * $sale_coeff );

		}


	}

}


/**
 * Добавление пачки уст ановленных продуктов
 * @throws Exception
 */
function wpp_ajax_cart_package_callback() {

	#check_ajax_referer( 'wpp-cart-string', 'security' );


	$product_counts = count( $_POST['products'] );

	$n = 1;
	foreach ( $_POST['products'] as $one_product ) {
		parse_str( parse_url( $one_product )['query'], $data );


		$product_id = ! empty( (int) $data['add-to-cart'] ) ? (int) $data['add-to-cart'] : null;

		if ( empty( $product_id ) ) :
			wp_send_json_error( [ 'message' => __( 'Product ID is Empty', 'wpp-fr' ) ] );
		endif;

		$quantity     = empty( $data['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $data['quantity'] ) );
		$variation_id = 0;
		$variation    = [];

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );

		unset( $data['quantity'] );
		unset( $data['add-to-cart'] );

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $data ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( [ $product_id => $quantity ], true );
			}


			if ( $n === $product_counts ) {
				WC_AJAX::get_refreshed_fragments();
			}
			$n ++;

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = [
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			];

			wp_send_json( $data );
		}
	}

	wp_die();
}

add_action( 'wp_ajax_wpp_ajax_cart_package', 'wpp_ajax_cart_package_callback' );
add_action( 'wp_ajax_nopriv_wpp_ajax_cart_package', 'wpp_ajax_cart_package_callback' );