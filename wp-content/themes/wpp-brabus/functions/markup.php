<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


add_action( 'init', 'my_remove_json_ld_frontend' );
function my_remove_json_ld_frontend() {
	remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );
}