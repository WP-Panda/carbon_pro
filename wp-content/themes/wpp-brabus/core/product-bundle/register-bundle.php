<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @snippet       Create a New Product Type @ WooCommerce Admin
 * @how-to        Get CustomizeWoo.com FREE
 * @source        https://businessbloomer.com/?p=73644
 * @author        Rodolfo Melogli
 * @compatible    Woo 3.3.3
 */

// --------------------------
// #1 Add New Product Type to Select Dropdown

add_filter( 'product_type_selector', 'wpp_fr_add_custom_product_type' );

function wpp_fr_add_custom_product_type( $types ) {
	$types['bundle'] = 'Набор';

	return $types;
}

// --------------------------
// #2 Add New Product Type Class

add_action( 'init', 'wpp_fr_create_custom_product_type' );

function wpp_fr_create_custom_product_type() {
	class WPP_Fr_Product_Bundle extends WC_Product {
		public function get_type() {
			return 'bundle';
		}
	}
}

// --------------------------
// #3 Load New Product Type Class

add_filter( 'woocommerce_product_class', 'wpp_fr_woocommerce_product_class', 10, 2 );

function wpp_fr_woocommerce_product_class( $class_name, $product_type ) {
	if ( $product_type === 'bundle' ) {
		$class_name = 'WPP_Fr_Product_Bundle';
	}

	return $class_name;
}

if ( ! function_exists( 'wpp_fr_bundle_add_to_cart' ) ) {

	/**
	 * Output the simple product add to cart area.
	 */
	function wpp_fr_bundle_add_to_cart() {
		wpp_get_template_part( 'woocommerce/single-product/add-to-cart/bunde' );
	}
}

add_action( 'woocommerce_bundle_add_to_cart', 'wpp_fr_bundle_add_to_cart' );