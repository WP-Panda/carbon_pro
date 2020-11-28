<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Подключение шаблона
 *
 * @param $array
 */
function wpp_br_require_template( $array ) {
	if ( ! empty( $array ) ) {
		foreach ( $array as $one ) {
			wpp_get_template_part( $one, [] );
		}
	}
}

/**
 * Вызов хэдера
 */
function wpp_br_get_header() {
	$preff = 'templates/globals/header/';
	$array = apply_filters( 'wpp_br_header_templates', [
		10 => $preff . 'logo',
		20 => $preff . 'nav',
		30 => $preff . 'meta-data'
	] );

	wpp_br_require_template( $array );
}

/**
 * Контент основной страницы
 */
function wpp_br_home_content() {

	$array = apply_filters( 'wpp_br_home_content', [
		10 => 'templates/media/hero',
		20 => 'templates/globals/navs/top',
		30 => 'templates/globals/loop',
		#40 => 'templates/home/promo',
		#50 => 'templates/globals/loop'
	] );

	wpp_br_require_template( $array );
}