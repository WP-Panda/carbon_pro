<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */
/**
 * Тут находятся функции ответственные
 * за параметры отображения сайта
 * для конкретного пользователя
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpp_set_user_currency' ) ) :
	/**
	 * Установка валюты и языка
	 */
	function wpp_set_user_currency() {

		$lng = $_GET['s-lng'] ?? ( empty( $_COOKIE['wpp_lng'] ) ? 'ru_RU' : false );
		if ( ! empty( $cur ) ) :
			setcookie( 'wpp_lng', $lng, time() + ( 3600 * 24 * 30 * 12 ), '/' );
		endif;
		switch_to_locale( $lng );


		$cur = $_GET['s-cur'] ?? ( empty( $_COOKIE['wpp_currency'] ) ? 'EUR' : false );
		if ( ! empty( $cur ) ) :
			setcookie( 'wpp_currency', $cur, time() + ( 3600 * 24 * 30 * 12 ), '/' );
		endif;

	}

	//add_action( 'init', 'wpp_set_user_currency' );

endif;


add_filter( 'wpp_fr_user_ip', function ( $dsata ) {
	if ( BR_DOMAIN === 'brabus.coms' ) {
		return '91.198.36.14';
	} else {
		return $dsata;
	}
}
);