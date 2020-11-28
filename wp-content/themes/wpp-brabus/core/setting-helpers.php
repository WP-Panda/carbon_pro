<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


if ( defined( 'BR_DOMAIN' ) ) {
	if ( BR_DOMAIN === 'carbon.pro' ) {
		define( 'PAINT', 34709 );
		define( 'ASSEM', 34708 );
		define( 'CARB', 486 );
	} else {
		define( 'PAINT', 10544 );
		define( 'ASSEM', 10543 );
		define( 'CARB', 486 );
	}
}

/**
 * Лаюел для опций
 * @param $key
 *
 * @return mixed
 */
function wpp_br_options_name( $key ) {
	if ( BR_DOMAIN === 'carbon.pro' ) {
		$array = [
			494 => wpp_br_lng( 'varnish_select' ),
			495 => wpp_br_lng( 'wave_select' ),
		];
	} else {
		$array = [
			494 => wpp_br_lng( 'varnish_select' ),
			495 => wpp_br_lng( 'wave_select' ),
		];
	}

	if ( ! empty( $key ) ) {
		return $array[ $key ];
	}
}

/**
 * Иконки
 *
 * @param $key
 *
 * @return mixed
 */
function tags_icon( $key ) {
	if ( BR_DOMAIN === 'carbon.pro' ) {
		$array = [
			37 => 'icon-exterior', #Design & Exterior
			39 => 'icon-wheel', #DWheels & Chassis
			38 => 'icon-sound', #Power & Sound
			40 => 'icon-interior' #Interior
		];
	} else {
		$array = [
			37 => 'icon-exterior', #Design & Exterior
			39 => 'icon-wheel', #DWheels & Chassis
			38 => 'icon-sound', #Power & Sound
			40 => 'icon-interior' #Interior
		];
	}

	return ! empty( $array[ $key ] ) ? $array[ $key ] : '';
}

/**
 * Массив настроек для сроков производства
 * @return array
 */
function wpp_br_create_time_array() {

	$locale = get_locale();

	$array = [
		'ru_RU' => [
			'0' => 'Выберите срок производства',
			'1' => '0-14 дней',
			'2' => '14-30 дней',
			'6' => '21-35  дней',
			'3' => '30-60 дней',
			'5' => '30-45 дней',
			'4' => '60-90 дней'
		],
		'en_US' => [
			'0' => 'Select the production period',
			'1' => '0-14 days',
			'2' => '14-30 days',
			'6' => '21-35 days',
			'3' => '30-60 days',
			'5' => '30-45 days',
			'4' => '60-90 days'
		]
	];

	return apply_filters( 'wpp_br_create_time_' . $locale, $array[ $locale ] );

}

/**
 * Массив данных про кузова
 * @return array
 */
function wpp_woo_car_body_tipes() {
	$array = [
		'cabriolet' => __( 'Cabriolet', 'wpp-brsbus' ),
		'coupe'     => __( 'Coupe', 'wpp-brsbus' ),
		't_model'   => __( 'T-Model', 'wpp-brsbus' ),
		'limousine' => __( 'Limousine', 'wpp-brsbus' ),
		'suv'       => __( 'SUV', 'wpp-brsbus' ),
	];

	return apply_filters( 'wpp_br_body_types', $array );

}