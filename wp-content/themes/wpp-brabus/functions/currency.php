<?php

/**
 * Символ валюты
 *
 * @param $currency_symbol
 * @param $currency
 *
 * @return string
 */
function wpp_change_existing_currency_symbol( $currency_symbol, $currency ) {
	global $wpp_currency;
	if ( is_admin() && ! is_ajax() ) {
		return $currency_symbol;
	}
	$currency_def = get_woocommerce_currency();
	$flag         = $wpp_currency::get_user_currency();
	$currency     = empty( $flag ) || $flag === $currency_def ? false : $flag;

	switch ( $currency ) {
		case 'RUB':
			$cur = '&#8381;';
			break;
		case 'USD':
			$cur = '&#36;';
			break;
		case 'EUR':
			$cur = '&euro;';
			break;
		default:
			$cur = $currency_symbol;

	}

	return $cur;
}

add_filter( 'wc_price_args', 'wpp_change_existing_currency_symbol_for_cart', 10 );
function wpp_change_existing_currency_symbol_for_cart( $args ) {
	global $wpp_currency;
	if ( is_admin() && ! is_ajax() ) {
		return $args;
	}
	$currency_def = get_woocommerce_currency();
	$flag         = $wpp_currency::get_user_currency();
	$currency     = empty( $flag ) || $flag === $currency_def ? 'EUR' : $flag;

	if ( ! empty( $currency ) ) {
		$args['currency'] = $currency;
	}

	return $args;
}

function wpp_return_custom_price_opt( $meta, $id ) {


	global $wpp_currency;
	$currency_def = get_woocommerce_currency();

	$currency_type = get_post_meta( $id, '_currency', true );

	if ( empty( $currency_type ) ) {
		$currency_type = 'EUR';
	}

	$flag = $wpp_currency::get_user_currency();

	$currency = empty( $flag ) || $flag === $currency_def ? 'EUR' : $flag;

	if ( empty( $currency ) ) {
		return $meta;
	}


	$data = [];
	if ( ! empty( $meta ) ) {
		foreach ( $meta as $one => $val ) {
			$data[ $one ]              = $val;
			$data[ $one ]['opt_price'] = wpp_currency_exchange( $currency_type, $currency, $wpp_currency, $val['opt_price'] );

		}
	}

	/*	wpp_console( __FILE__ . ":" . __LINE__ );
		wpp_console( $data );*/

	return $data;

}

add_filter( 'wpp_as_data_product', 'wpp_return_custom_price_opt', 2, 10 );

function wpp_return_ads_price_opt( $price, $id ) {

	global $wpp_currency;
	$currency_def = get_woocommerce_currency();

	$currency_type = get_post_meta( $id, '_currency', true );

	if ( empty( $currency_type ) ) {
		$currency_type = 'EUR';
	}

	$flag = $wpp_currency::get_user_currency();

	$currency = empty( $flag ) || $flag === $currency_def ? 'EUR' : $flag;

	if ( empty( $currency ) ) {
		return $price;
	}

	$out = wpp_currency_exchange( $currency_type, $currency, $wpp_currency, $price );

	return round( $out );

}


add_filter( 'wpp_dep_field_pice', 'wpp_return_ads_price_opt', 2, 10 );


function wpp_return_custom_price( $price, $product ) {

	$out = wpp_return_ads_price_opt( $price, $product->get_id() );

	return $out;
}

#add_filter( 'woocommerce_product_get_price', 'wpp_return_custom_price', 10, 2 );

/**
 * @deprecated
 *
 * @param $price
 * @param $id
 *
 * @return float
 */
function wpp_return_custom_price_cart( $price, $id ) {

	return wpp_return_ads_price_opt( $price, $id );
}

/**
 *  Конвертация цены между валютами
 *
 * @param $currency_type
 * @param $currency
 * @param $wpp_currency
 * @param $price
 *
 * @return float|int
 */
function wpp_currency_exchange( $currency_type, $currency, $wpp_currency, $price ) {

	if ( $currency_type === 'EUR' ) {

		switch ( $currency ) {
			case 'RUB':
				$exchange = $wpp_currency::get_currency( 'EUR' );
				$out      = (float) $price * (float) $exchange;
				break;
			case 'USD':
				$cross = $wpp_currency::cross_exchange( 'USD', 'EUR' );
				$out   = (float) $price * (float) $cross;
				break;
			default:
				$out = (float) $price;

		}

	} elseif ( $currency_type === 'USD' ) {

		switch ( $currency ) {
			case 'RUB':
				$exchange = $wpp_currency::get_currency( 'USD' );
				$out      = (float) $price * (float) $exchange;
				break;
			case 'EUR':
				$cross = $wpp_currency::cross_exchange( 'EUR', 'USD' );
				$out   = (float) $price * (float) $cross;
				break;
			default:
				$out = (float) $price;

		}

	} else {
		switch ( $currency ) {

			case 'USD':
				$exchange = $wpp_currency::get_currency( 'USD' );
				$out      = $price / (float) $exchange;
				break;
			case 'EUR':
				$exchange = $wpp_currency::get_currency( 'EUR' );
				$out      = $price / (float) $exchange;
				break;
			default:
				$out = $price;

		}

	}

	#wpp_console( $out );

	return $out;
}


function wpp_return_price( $price ) {

	global $wpp_currency;
	$currency_def  = get_woocommerce_currency();
	$currency_type = 'EUR';
	$flag          = $wpp_currency::get_user_currency();
	$currency      = empty( $flag ) || $flag === $currency_def ? 'EUR' : $flag;

	if ( empty( $currency ) ) {
		return $price;
	}

	$out = wpp_currency_exchange( $currency_type, $currency, $wpp_currency, $price );

	return round( $out );

}