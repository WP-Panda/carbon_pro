<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$array = [
	'lang-new/init',
	'get-templates',
	'setting-helpers',
	'info-data',
	'admin_tools',
	'product-bundle/register-bundle',
	'product-bundle/bundle-price',
	'saved-cart/init',
	'variants/helpers',
	'user-show-site-params',
	'for-navs'
];

foreach ( $array as $one ) {
	require_once $one . '.php';
}