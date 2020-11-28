<?php

class Wpp_Currency {

	/**
	 * Получение ыалюты аользователя
	 * @return string
	 */
	public static function get_user_currency() {

		return wpp_get_url_currency();
	}

	/**
	 * Получение данных о курсе валют и запись в файл
	 *
	 * @return array|mixed|object
	 */
	public static function get_cbr_daly_data() {

		$dir     = wp_get_upload_dir();
		$basedir = $dir['basedir'] . DIRECTORY_SEPARATOR . 'wpp-currency';

		if ( ! is_dir( $basedir ) ) {
			mkdir( $basedir );
		}

		$json_daily_file = $basedir . '/daily.json';

		if ( ! is_file( $json_daily_file ) || filemtime( $json_daily_file ) < time() - 15 ) {
			if ( $json_daily = file_get_contents( 'https://www.cbr-xml-daily.ru/daily_json.js' ) ) {
				file_put_contents( $json_daily_file, $json_daily );
			}
		}

		return json_decode( file_get_contents( $json_daily_file ) );
	}


	/**
	 * Получение курса одной валюты
	 *
	 * @param $currency - код валюты
	 *
	 * @return mixed
	 */
	public static function get_currency( $currency ) {
		$data = self::get_cbr_daly_data();

		if ( ! empty( $data->Valute->{$currency}->Value ) ) {
			return $data->Valute->{$currency}->Value;
		} else {
			return false;
		}
	}

	/**
	 * @param $cur_1 - валюта 1
	 * @param $cur_2 - валюта 2
	 *
	 * @return bool|float|int
	 */
	public static function cross_exchange( $cur_1, $cur_2 ) {
		$data_1 = self::get_currency( $cur_2 );
		$data_2 = self::get_currency( $cur_1 );

		if ( empty( $data_1 ) || empty( $data_2 ) ) {
			return false;
		}

		$cross = (float) $data_1 * ( 1 / (float) $data_2 );

		return round( $cross, 3 );

	}


}

$GLOBALS['wpp_currency'] = new Wpp_Currency();


/**
 * Format the price with a currency symbol.
 *
 * @param  float $price Raw price.
 * @param  array $args Arguments to format a price {
 *     Array of arguments.
 *     Defaults to empty array.
 *
 * @type bool $ex_tax_label Adds exclude tax label.
 *                                      Defaults to false.
 * @type string $currency Currency code.
 *                                      Defaults to empty string (Use the result from get_woocommerce_currency()).
 * @type string $decimal_separator Decimal separator.
 *                                      Defaults the result of wc_get_price_decimal_separator().
 * @type string $thousand_separator Thousand separator.
 *                                      Defaults the result of wc_get_price_thousand_separator().
 * @type string $decimals Number of decimals.
 *                                      Defaults the result of wc_get_price_decimals().
 * @type string $price_format Price format depending on the currency position.
 *                                      Defaults the result of get_woocommerce_price_format().
 * }
 * @return string
 */
function wpp_wc_price( $price, $args = array() ) {

	$currency = wpp_get_url_currency();
	$args     = apply_filters(
		'wc_price_args',
		wp_parse_args(
			$args,
			array(
				'ex_tax_label'       => false,
				'currency'           => $currency,
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		)
	);

	$unformatted_price = $price;
	$negative          = $price < 0;
	$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
	$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = wc_trim_zeros( $price );
	}

	$simb = $args['currency'] === 'RUB' && ! empty( $args['pdf'] ) ? 'P.' : get_woocommerce_currency_symbol( $currency );

	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="woocommerce-Price-currencySymbol">' . $simb . '</span>', $price );
	$return          = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

	if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
		$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
	}

	/**
	 * Filters the string of price markup.
	 *
	 * @param string $return Price HTML markup.
	 * @param string $price Formatted price.
	 * @param array $args Pass on the args.
	 * @param float $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
	 */
	return apply_filters( 'wc_price', $return, $price, $args, $unformatted_price );
}