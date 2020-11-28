<?php
/**
 * Сохранение корзины
 * тут все для сохраненных корзин вроде как даже рефакторинга уже не надо
 *
 * @since 1.0.6
 */

// Тут проверяет таблицу каждый раз, ниже при активации темы
// Это круто, но каждый раз оно не нужно
// Но если будешь вносить изменеия то поменяй хуки потом вернуть взад назад
#add_action( 'init', 'wpp_create_save_cart_table' );
add_action( 'after_switch_theme', 'wpp_create_save_cart_table' );

/**
 * Создание таблицы для корзины
 */
function wpp_create_save_cart_table() {

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$db_table_name   = $wpdb->prefix . 'wpp_woo_saved_carts';

	$wpdb->hide_errors();

	if ( $wpdb->get_var( sprintf( "show tables like '%s'", $db_table_name ) ) !== $db_table_name ) {

		$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `key` varchar(255) NOT NULL,
                `cart_count`  int(3),
            	`cart` longtext,
                UNIQUE KEY id (id)
        ) %s;", $db_table_name, $charset_collate );

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

add_action( "wp_ajax_wpp_save_cart_details", "wpp_save_cart_details_callback" );
add_action( "wp_ajax_nopriv_wpp_save_cart_details", "wpp_save_cart_details_callback" );


/**
 * Сохранение корзины
 */
function wpp_save_cart_details_callback() {

	global $wpdb;

	$db_table_name = $wpdb->prefix . 'wpp_woo_saved_carts';

	$current_cart = WC()->cart->get_cart();

	//Проверка наличия товаров в корзине
	if ( empty( $current_cart ) ) {
		wp_send_json_error( [
			'msg' => 'Cart Is Empty'
		] );
	}

	/**
	 * Пересчет корзины для бандлов
	 */
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( ! empty( $cart_item['wpp_add_bundle'] ) ) {
			$sale                     = (int) get_post_meta( (int) $cart_item['wpp_add_bundle'], 'bundle_sale', true );
			$price                    = $cart_item['line_total'] / $cart_item['quantity'];
			$sale_coeff               = ! empty( $sale ) ? 1 - ( $sale / 100 ) : 1;
			$cart_item['data']->price = $price * $sale_coeff;
		}
	}

	WC()->cart->calculate_totals();
	$cart_contents = WC()->cart->get_cart();
	$count         = count( $cart_contents );
	$key           = WC()->cart->get_cart_hash();

	/**
	 * Проверка наличия такогого же хэша в таблице
	 */
	$data_item = $wpdb->get_row( $wpdb->prepare( "SELECT `id` FROM $db_table_name WHERE `key` = %s AND `cart_count` = %d", $key, $count ) );
	if ( ! empty( $data_item ) ) {
		wp_send_json_success( [
			'url' => sprintf( '%s/cart/%s', esc_url( $_POST['actual_url'] ), $data_item->id )
		] );
	}

	$insert_data = [
		'key'        => $key,
		'cart_count' => $count,
		'cart'       => serialize( $cart_contents )
	];

	$inser_format = [
		'%s',
		'%d',
		'%s'
	];

	$insert = $wpdb->insert(
		$db_table_name,
		$insert_data,
		$inser_format
	);

	if ( empty( $insert ) ) {
		wp_send_json_error( [ 'status' => 4 ] );
	}

	$this_insert = $wpdb->insert_id;

	wp_send_json_success( [
		'url' => sprintf( '%s/cart/%s', esc_url( $_POST['actual_url'] ), $this_insert )
	] );

}

/**
 * Правила перезаписи для сохраненных корзин
 */

function wpp_saved_cart_do_rewrite() {

	add_rewrite_rule( '^(cart)/([^/]*)/?', 'index.php?pagename=$matches[1]&saved-cart=$matches[2]', 'top' );

	add_filter( 'query_vars', function ( $vars ) {
		$vars[] = 'saved-cart';

		return $vars;
	} );
}

add_action( 'init', 'wpp_saved_cart_do_rewrite' );


/**
 * Проверка наличия корзины
 * @return bool
 */
function is_saved_cart() {
	global $wpdb;

	$db_table_name = $wpdb->prefix . 'wpp_woo_saved_carts';
	$key           = WC()->cart->get_cart_hash();

	$data_item = $wpdb->get_row( $wpdb->prepare( "SELECT `id` FROM $db_table_name WHERE `key` = %s ", $key ) );

	return ! empty( $data_item->id ) ? $data_item->id : false;
}