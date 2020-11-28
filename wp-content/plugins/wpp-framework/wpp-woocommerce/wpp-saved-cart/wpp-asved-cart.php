<?php
/**
 * Сохранение корзины
 *
 * @since 1.0.6
 */

/**
 * @todo ужно переделать с сохранения в постах в сохренениее в отдельной таблице
 */
add_action( 'init', 'wpp_sc_register_post_types', 10 );
function wpp_sc_register_post_types() {
	if ( ! is_blog_installed() || post_type_exists( 'carts' ) ) {
		return;
	}

	do_action( 'wpp_fr_carts_register_posts' );

	$supports = [
		'title',
	];

	$carts = [
		'labels'              => [
			'name'          => __( 'Carts', 'wpp-fr' ),
			'singular_name' => __( 'Cart', 'wpp-fr' ),
			'all_items'     => __( 'All Carts', 'wpp-fr' ),
			'menu_name'     => _x( 'Carts', 'Admin menu name', 'wpp-fr' ),
		],
		'public'              => false,
		'show_ui'             => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => true,
		'hierarchical'        => false,
		// Hierarchical causes memory issues - WP loads all records!
		'rewrite'             => false,
		'query_var'           => true,
		'supports'            => $supports,
		'has_archive'         => false,
		'show_in_nav_menus'   => false,
	];

	if ( is_wpp_panda() ) {
		$carts['public']  = true;
		$carts['show_ui'] = true;
	}

	register_post_type( 'carts', apply_filters( 'wpp_fr_ads_register_post_type_as_option', $carts ) );
	do_action( 'wpp_fr_carts_after_register_posts' );
}

add_action( 'wp_ajax_wpp_save_cart_details', 'wpp_save_cart_details_callback' );
add_action( 'wp_ajax_nopriv_wpp_save_cart_details', 'wpp_save_cart_details_callback' );


function wpp_save_cart_details_callback() {

	$key = md5( uniqid( date( 'l dS F' ), true ) );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		if ( ! empty( $cart_item['wpp_add_bundle'] ) ) {


			$sale = (int) get_post_meta( (int) $cart_item['wpp_add_bundle'], 'bundle_sale', true );

			$price = $cart_item['line_total'] / $cart_item['quantity'];

			$sale_coeff               = ! empty( $sale ) ? 1 - ( $sale / 100 ) : 1;
			$cart_item['data']->price = $price * $sale_coeff;

		}

	}

	WC()->cart->calculate_totals();
	$cart_contents = WC()->cart->get_cart();

	$count   = count( $cart_contents );
	$post_id = wp_insert_post( [
		'post_type'      => 'carts',
		'post_name'      => $key,
		'post_title'     => $key,
		'post_status'    => 'publish',
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	] );

	if ( ! empty( $post_id ) ) :
		update_post_meta( $post_id, '_cart', serialize( $cart_contents ) );
		update_post_meta( $post_id, '_cart_count', $count );
		wp_send_json_success( [
			'url' => sprintf( '%s?saved-cart=%s&s-lng=%s&s-cur=%s',/*__('Saved Cart link:  ', 'wpp-fr')*/
				wc_get_cart_url(), $key, $_COOKIE['wpp_lng'], $_COOKIE['wpp_currency'] )
		] );
	else:
		wp_send_json_error();
	endif;


}