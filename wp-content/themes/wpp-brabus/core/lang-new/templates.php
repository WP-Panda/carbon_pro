<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Переключатель языка
 */
function wpp_lng_swither() {
	$cur = wpp_get_url_lng();

	$lng_args = WPP_LNG_ARGS;

	$out = sprintf( '<a href="javascript:void(0);">%s</a>', $lng_args[ $cur ] );

	unset( $lng_args[ $cur ] );

	foreach ( $lng_args as $lng_key => $lng_title ) :
		$out .= sprintf( '<a href="%s">%s</a>', wpp_convert_lng_url( $lng_key ), $lng_title );
	endforeach;

	printf( '<ul class="flyout-navigation language-navigation"><li class="flyout-item">%s</li></ul>', $out );
}

/**
 * Переключатель валюты
 */
function wpp_currency_swither() {
	$currency = wpp_get_url_currency();

	$currency_args = WPP_CUR_ARGS;

	$out = sprintf( '<a href="javascript:void(0);">%s</a>', $currency_args[ strtolower( $currency ) ] );

	unset( $currency_args[ strtolower( $currency ) ] );

	foreach ( $currency_args as $cur_key => $cur_title ) :
		$out .= sprintf( '<a href="%s">%s</a>', wpp_convert_cur_url( $cur_key ), $cur_title );
	endforeach;

	printf( '<ul class="flyout-navigation language-navigation wpp-cur-swith-list"><li class="flyout-item wpp-cur-swith-items">%s</li></ul>', $out );
}