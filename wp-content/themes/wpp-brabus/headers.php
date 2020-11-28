<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

add_filter('allowed_http_origins', 'add_allowed_origins');

function add_allowed_origins($origins) {
	$origins[] = 'http://brabus.coms';
	$origins[] = 'http://ru.brabus.coms';
	$origins[] = 'http://carbon.pro';
	$origins[] = 'http://ru.carbon.pro';
	return $origins;
}