<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Получение языка браузера
 *
 * @return bool|string
 */
function wpp_fr_get_browser_lang() {

	$lng = WPP_LNG_DEF;

	if ( ! empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		preg_match_all( '~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), $matches, PREG_SET_ORDER );
	}

	if ( ! empty( $matches[0][0] ) ) {
		$lng = $matches[0][0];
	}

	$lngs = explode( '-', $lng );

	if ( ! empty( $lngs[0] ) ) {
		$lng = $lngs[0];
	}

	return $lng;
}

/**
 * Получение языка из куков
 *
 * @return bool|string
 */
function wpp_fr_get_cookie_lang() {
	return $_SESSION['wpp_lng'] ?? false;
}

/**
 * Получение языка пользователя
 *
 * @return bool|string
 */
function wpp_fr_get_user_lang() {

	$lng = ! empty( wpp_fr_get_cookie_lang() ) ? wpp_fr_get_cookie_lang() : wpp_fr_get_browser_lang();

	return $lng;

}

/**
 * Получение языка из текущего текущего урла
 *
 * @return string
 */
function wpp_get_url_lng() {

	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return 'ru';
	} else {

		$url_info = wpp_fr_parse_url( wpp_fr_actual_link() );
		$lang = ! empty( $url_info['lang_url'] ) ? $url_info['lang_url'] : WPP_LNG_DEF;

		if ( empty( $_SESSION['wpp_lang_new'] ) ) {
			$lang = wpp_fr_get_browser_lang();
		}

		$_SESSION['wpp_lang_new'] = $lang;

		return $lang;
	}
}

/**
 * Получение валюты браузера
 */
function wpp_fr_get_browser_currency() {

	$lang     = wpp_fr_get_browser_lang();
	$currency = 'usd';

	if ( 'ru' === $lang ) {
		$currency = 'rub';
	}

	return $currency;

}

/**
 * Получение валюты пользователя
 *
 * @return bool|string
 */
function wpp_fr_get_user_currency() {

	return wpp_get_url_currency();

}

/**
 * Получение валюты из текущего текущего урла
 *
 * @return string
 */
function wpp_get_url_currency() {

	$url_info = wpp_fr_parse_url( wpp_fr_actual_link() );

	$currency = ! empty( $url_info['currency'] ) ? strtoupper( $url_info['currency'] ) : ( ! empty( $_SESSION['wpp_currency_new'] ) ? strtoupper( $_SESSION['wpp_currency_new'] ) : strtoupper( WPP_CUR_DEF ) );

	$_SESSION['wpp_currency_new'] = $currency;

	return $currency;

}