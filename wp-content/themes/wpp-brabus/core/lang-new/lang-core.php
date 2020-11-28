<?php
/**
 * @package taxo.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Это добавить в wp-config;
 */

#define('COOKIE_DOMAIN', '.taxo.coms'); // your main domain
#define('COOKIEPATH', '/');
#define('COOKIEHASH', md5('taxo.coms')); // notice absence of a '.' in fron


# Настротройки Языков
define( 'WPP_LNG_DEF', 'en' );
define( 'WPP_LNG_ARGS', [
	'en' => 'English',
	'ru' => 'Русский'
] );

# Настротройки Валют
define( 'WPP_CUR_DEF', 'eur' );
define( 'WPP_CUR_ARGS', [
	'usd' => 'USD',
	'rub' => 'РУБ',
	'eur' => 'EUR'
] );


/**
 * Подмена хоста, для языка не по умолчанию
 *
 * @param $url
 * @param $path
 * @param $orig_scheme
 * @param $blog_id
 *
 * @return string
 */
function wpp_fr_change_lang_url( $url, $path, $orig_scheme, $blog_id ) {


	if ( ! is_admin() && ! is_login_page() /* && ! is_rest() */ ) {

		$url_info = wpp_fr_parse_url( wpp_fr_actual_link() );
		$lang     = wpp_get_url_lng();

		$currency = ( ! empty( $url_info['currency'] ) && wpp_fr_is_enabled_cur( $url_info['currency'] ) ) ? '/' . $url_info['currency'] : '';
		$language = ( ! empty( $lang ) && $lang === 'ru' ) ? 'ru.' : '';

		if ( empty( $currency ) ) { // если в урл нет валюты, отправить на страницу с валютой


			if ( empty( $url_info['path'] ) ) {
				$url_info['path'] = '';
			}

			if ( $url_info['path'] === '/wp-json' ) {
				return $url;
			}


			$currency_need = ! empty( $url_info['currency'] ) ? strtoupper( $url_info['currency'] ) : ( ! empty( $_SESSION['wpp_currency_new'] ) ? strtoupper( $_SESSION['wpp_currency_new'] ) : strtoupper( WPP_CUR_DEF ) );
			$redirect_url  = $url_info['scheme'] . '://' . $language . $url_info['host'] . '/' . strtolower( $currency_need ) . str_replace( '//', '/', '/' . $url_info['path'] );
			wp_redirect( $redirect_url, '301' );
			exit;
		} else {
			return $url_info['scheme'] . '://' . $language . $url_info['host'] . $currency . str_replace( '//', '/', '/' . $path );
		}
	} else {
		return $url;
	}

}

add_filter( 'home_url', 'wpp_fr_change_lang_url', 10, 4 );


/*function wpp_redirect() {
	$url_info = wpp_fr_parse_url( wpp_fr_actual_link() );


	$currency = ( ! empty( $url_info['currency'] ) && wpp_fr_is_enabled_cur( $url_info['currency'] ) ) ? '/' . $url_info['currency'] : '';
	$language = ( ! empty( $url_info['lang_url'] ) && wpp_fr_is_enabled_lang( $url_info['lang_url'] ) && $url_info['lang_url'] !== WPP_LNG_DEF ) ? $url_info['lang_url'] . '.' : '';

	if ( empty( $currency ) ) { // если в урл нет валюты, отправить на страницу с валютой



		if ( empty( $url_info['path'] ) ) {
			$url_info['path'] = '';
		}


		$currency_need = ! empty( $url_info['currency'] ) ? strtoupper( $url_info['currency'] ) : ( ! empty( $_SESSION['wpp_currency_new'] ) ? strtoupper( $_SESSION['wpp_currency_new'] ) : strtoupper( WPP_CUR_DEF ) );
		$redirect_url  = $url_info['scheme'] . '://' . $language . $url_info['host'] . '/' . strtolower( $currency_need ) . str_replace( '//', '/', '/' . $url_info['path'] );
		wp_redirect( $redirect_url, '301' );
		exit;
	}
}*/

if ( !function_exists( 'is_rest' ) ) {
	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: It can happen that WP_Rewrite is not yet initialized,
	 *          so do this (wp-settings.php)
	 * Case #4: URL Path begins with wp-json/ (your REST prefix)
	 *          Also supports WP installations in subfolders
	 *
	 * @returns boolean
	 * @author matzeeable
	 */
	function is_rest() {
		$prefix = rest_get_url_prefix( );
		if (defined('REST_REQUEST') && REST_REQUEST // (#1)
		    || isset($_GET['rest_route']) // (#2)
		       && strpos( trim( $_GET['rest_route'], '\\/' ), $prefix , 0 ) === 0)
			return true;
		// (#3)
		global $wp_rewrite;
		if ($wp_rewrite === null) $wp_rewrite = new WP_Rewrite();

		// (#4)
		$rest_url = wp_parse_url( trailingslashit( rest_url( ) ) );
		$current_url = wp_parse_url( add_query_arg( array( ) ) );
		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}
}


