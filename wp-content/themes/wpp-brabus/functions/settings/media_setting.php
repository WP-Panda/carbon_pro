<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


define( 'BR_THUMB_SIZE', [ 'width' => 475, 'height' => 317 ] );

/**
 * Миниатюра
 *
 * @param $array array - спец данные
 *
 * @return array
 */
function wpp_br_thumb_array( $array = null ) {

	$args = [
		'crop'   => true,
		'retina' => true,
		'lazy'       => false,
		'sizes'  => [ BR_THUMB_SIZE ]
	];

	if ( ! empty( $array ) ) {
		$args = array_merge( $args, $array );
	}


	return apply_filters( 'wpp_br_thumb_array', $args );

}

/**
 * В слайдкры
 *
 * @param $array array - спец данные
 *
 * @return array
 */
function wpp_br_slider_array( $array = null ) {

	$args = [
		'water_mark' => true,
		'retina'     => true,
		'lazy'       => false,
		'sizes'      => [
			[ 'height' => 780, 'media' => '(min-width: 992px)' ],
			[ 'height' => 680, 'media' => '(min-width: 768px)' ],
			[ 'height' => 400, 'media' => '(max-width: 767px)' ],
		]
	];

	if ( ! empty( $array ) ) {
		$args = array_merge( $args, $array );
	}


	return apply_filters( 'wpp_br_thumb_array', $args );

}


/**
 * Полноэкранная
 *
 * @param $array array - спец данные
 *
 * @return array
 */
function wpp_br_fullscreen_array( $array = null ) {

	$args = [
		'crop'       => true,
		'retina'     => true,
		'water_mark' => true,
		'crop'       => true,
		'lazy'       => false,
		'sizes'      => [
			[ 'width' => 1920, 'height' => 1280, 'media' => '(min-width: 1200px)' ]
		]
	];

	if ( ! empty( $array ) ) {
		$args = array_merge( $args, $array );
	}


	return apply_filters( 'wpp_br_thumb_array', $args );

}

function paulund_remove_default_image_sizes( $sizes ) {
	unset( $sizes['medium_large'] );

	return $sizes;
}

add_filter( 'intermediate_image_sizes_advanced', 'paulund_remove_default_image_sizes' );


/**
 * Hero слайдер
 *
 * @param $array array - спец данные
 *
 * @return array
 */
function wpp_br_hero_main_array( $array = null, $one ) {

	$args = [
		'picture_class' => 'picture-teaser__media',
		'retina'        => true,
		'water_mark'    => true,
		'lazy'          => false,
		'class'         => 'wpp-hero-img wpp-hero-img-' . $one,
		'sizes'         => [
			[
				'width'  => 1920,
				'height' => 1280,
				'media'  => '(min-width: 1200px)'
			],
			[
				'width'  => 1200,
				'height' => 800,
				'media'  => '(min-width: 992px)'
			],
			[
				'width'  => 990,
				'height' => 660,
				'media'  => '(min-width: 768px)'
			],
		]
	];

	if ( ! empty( $array ) ) {
		$args = array_merge( $args, $array );
	}


	return apply_filters( 'wpp_br_hero_main_array', $args );

}


/**
 * Hero слайдер миниатюры
 *
 * @param $array array - спец данные
 *
 * @return array
 */
function wpp_br_hero_thumb_array( $array = null ) {

	$args = [
		'crop'   => true,
		'retina' => true,
		'lazy'   => false,
		'sizes'  => [
			[
				'width'  => 120,
				'height' => 80,
			],
		]
	];

	if ( ! empty( $array ) ) {
		$args = array_merge( $args, $array );
	}


	return apply_filters( 'wpp_br_hero_thumb_array', $args );

}