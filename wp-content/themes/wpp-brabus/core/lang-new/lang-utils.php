<?php
/**
 * @package taxo.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Проверка домашней страницы
 *
 * @return bool
 */
function is_login_page() {
	return in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ] );
}

/**
 * разбор урда
 *
 * @param $url
 *
 * @return array
 */
function wpp_fr_parse_url( $url ) {

	preg_match( '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:?#]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!', $url, $out );

	$result = [];
	if ( ! empty( $out[1] ) ) {
		$result['scheme'] = $out[1];
	}
	if ( ! empty( $out[2] ) ) {
		$result['user'] = $out[2];
	}
	if ( ! empty( $out[3] ) ) {
		$result['pass'] = $out[3];
	}
	if ( ! empty( $out[4] ) ) {
		$result['host'] = $out[4];
	}
	if ( ! empty( $out[5] ) ) {
		$result['host'] .= ':' . $out[5];
	}
	if ( ! empty( $out[6] ) ) {
		$result['path'] = $out[6];
	}
	if ( ! empty( $result['path'] ) ) {
		$cur_temp = explode( '/', trim( $result['path'], "/" ) )[0];

		if ( wpp_fr_is_enabled_cur( $cur_temp ) ) {
			$result['currency'] = $cur_temp;
		}

	}
	if ( ! empty( $out[7] ) ) {
		$result['query'] = $out[7];
	}
	if ( ! empty( $out[8] ) ) {
		$result['fragment'] = $out[8];
	}

	if ( ! empty( $result['host'] ) ) {

		if ( preg_match( '#^([a-z]{2})\.#i', $result['host'], $match ) ) {
			$lang = $match[1];
			if ( $lang ) {
				$result['lang_url']        = $lang;
				$result['host']            = substr( $result['host'], 3 );
				$result['doing_front_end'] = true;
			}
		}

	}

	return $result;
}

/**
 * Получение текущего урла
 */
function wpp_fr_actual_link() {
	return ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Проверка доступности языка
 *
 * @param $lang
 *
 * @return bool
 */
function wpp_fr_is_enabled_lang( $lang ) {

	$args = WPP_LNG_ARGS;
	$lang = strtolower( $lang );

	return $args[ $lang ] ?? WPP_LNG_DEF;
}

/**
 * Проверка доступности валюты
 *
 * @param $cur
 *
 * @return bool
 */
function wpp_fr_is_enabled_cur( $cur ) {

	$args = WPP_CUR_ARGS;
	$cur  = strtolower( $cur );

	return isset( $args[ $cur ] );
}

/**
 * Замена языка в переданном урле
 */
function wpp_convert_lng_url( $need_lng, $url = null ) {
	$url = $url ?? wpp_fr_actual_link();

	$url_data = wpp_fr_parse_url( $url );
	$need_lng = WPP_LNG_DEF !== $need_lng ? $need_lng . '.' : '';

	if ( ! empty( $url_data['lang_url'] ) ) {
		$search = '//' . $url_data['lang_url'] . '.' . $url_data['host'];
	} else {
		$search = '//' . $url_data['host'];
	}
	$replace = '//' . $need_lng . $url_data ['host'];

	return str_replace( $search, $replace, $url );
}

/**
 * Замена валюты в переданном урле
 */
function wpp_convert_cur_url( $need_cur, $url = null ) {
	$url = $url ?? wpp_fr_actual_link();

	$url_data = wpp_fr_parse_url( $url );
	$need_cur = '/' . $need_cur . '/';

	if ( ! empty( $url_data['currency'] ) ) {
		$search = $url_data['host'] . '/' . $url_data['currency'] . '/';
	} else {
		$search = $url_data['host'] . '/';
	}
	$replace = $url_data ['host'] . $need_cur;


	return str_replace( $search, $replace, $url );
}


function wpp_fr_generate_default_url() {
	$need_cur = wpp_fr_get_browser_currency();
	$need_lng = wpp_fr_get_browser_lang();

	$url = wpp_convert_lng_url( $need_lng );

	return wpp_convert_cur_url( $need_cur, $url );

}