<?php
/*
	Plugin Name: WPP DEV
	Plugin URI: https://wppanda.com?target=wpp_dev
	Description: Функции для разработки
	Author: WP Panda
	Author URI: https://wppanda.com
	Text Domain: wpp-dev
	Domain Path: /languages/
	Version: 0.0.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'WPP_LOCAL', true );

if ( ! function_exists( 'wpp_admin_notice' ) ) :
	/**
	 * Message in admin
	 * @since 0.0.1
	 */
	function wpp_admin_notice() {
		printf(
			'<div class="notice noticewarning"><p>%s</p></div>', __( 'ATTENTION! DO NOT DEACTIVATE WPP DEV. WE WILL TURN IT OFF AFTER WORK IS COMPLETE !', 'wpp-dev' )
		);
	}

endif;

add_action( 'admin_notices', 'wpp_admin_notice' );

require_once 'functions/init.php';
require_once 'libs/init.php';

if ( ! function_exists( 'is_rest' ) ) {

	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: It can happen that WP_Rewrite is not yet initialized, so do this (wp-settings.php)
	 * Case #4: URL Path begins with wp-json/ (your REST prefix) Also supports WP installations in subfolders
	 *
	 * @returns boolean
	 * @author matzeeable
	 */
	function is_rest() {
		$prefix = rest_get_url_prefix();
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST // (#1)
		     || isset( $_GET['rest_route'] ) // (#2)
		        && strpos( trim( $_GET['rest_route'], '\\/' ), $prefix, 0 ) === 0 ) {
			return true;
		}
		// (#3)
		global $wp_rewrite;
		if ( $wp_rewrite === null ) {
			$wp_rewrite = new WP_Rewrite();
		}

		// (#4)
		$rest_url    = wp_parse_url( trailingslashit( rest_url() ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );

		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}

}