<?php
/**
 * Register meta boxes
 *
 * @author  WP Panda
 *
 * @package WPP_FR
 * @since   1.0.0
 * @version 1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WPP_FR_Meta_Boxes {

	/**
	 * Class Init;
	 * @since   1.0.0
	 * @version 1.0.3
	 *
	 * @return null
	 */
	public static function init() {
		add_action( 'admin_init', [ __CLASS__, 'post_boxes' ]);
		add_filter( 'rwmb_meta_boxes', [ __CLASS__, 'term_boxes' ] );

	}

	/**
	 * Post boxes
	 * Метабоксы для записей
	 *
	 * @since   1.0.0
	 * @version 1.0.3
	 *
	 * @return array
	 */
	public static function post_boxes() {
		$final_boxes = apply_filters( 'wpp_fr_post_meta_boxes_args', [] );

		foreach ( $final_boxes as $meta_box ) {
			new RW_Meta_Box( $meta_box );
		}

	}

	/**
	 * Term boxes
	 * Метабоксы для таксономий
	 *
	 * @since   1.0.0
	 * @version 1.0.3
	 *
	 * @return array
	 */
	public static function term_boxes() {

		$out = apply_filters( 'wpp_fr_term_meta_boxes_args', [] );

		//wpp_d_log($out);
		if ( ! empty( $out ) ) {
			return $out;
		}
	}

}

WPP_FR_Meta_Boxes::init();