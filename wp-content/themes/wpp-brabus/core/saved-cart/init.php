<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$array = [
	'saved-cart',
	'save-cart'
];

foreach ( $array as $one ) {
	require_once $one . '.php';
}