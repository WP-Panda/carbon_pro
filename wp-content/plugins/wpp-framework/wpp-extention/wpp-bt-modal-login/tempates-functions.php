<?php
/**
 * File Description
 *
 * @author  WP Panda
 *
 * @package Time, it needs time
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Login form template
 */
if ( ! function_exists( 'wpp_al_login_form' ) ):
	function wpp_al_login_form() {
		require_once 'templates/forms/login.php';
	}
endif;

/**
 * Register form template
 */
if ( ! function_exists( 'wpp_al_register_form' ) ):
	function wpp_al_register_form() {
		require_once 'templates/forms/register.php';
	}
endif;

/**
 * Lost Password
 */
if ( ! function_exists( 'wpp_al_lost_form' ) ):
	function wpp_al_lost_form() {
		require_once 'templates/forms/lost.php';
	}
endif;

/**
 * Reset
 */
if ( ! function_exists( 'wpp_al_set_form' ) ):
	function wpp_al_set_form() {
		require_once 'templates/forms/set.php';
	}
endif;


/**
 * term
 */
if ( ! function_exists( 'wpp_al_terms_field' ) ):
	function wpp_al_terms_field() {
		require_once 'templates/patr/terms.php';
	}
endif;

/**
 * type
 */
if ( ! function_exists( 'wpp_al_type_field' ) ):
	function wpp_al_type_field() {
		require_once 'templates/patr/type.php';
	}
endif;