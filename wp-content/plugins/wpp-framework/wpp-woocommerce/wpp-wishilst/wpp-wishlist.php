<?php

class Wpp_Fr_Wish {

	private static $table = 'wpp_woo_wishlist';

	function __construct() {
		add_action( 'init', [ $this, 'set_wl_cookie' ], 5 );
		add_action( 'init', [ $this, 'create_table' ] );
		add_action( 'wpp_wish_action_icon', [ $this, 'wish_html' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'wish_assets' ] );

		add_action( 'wp_ajax_add_wish_item', [ $this, 'add_wish_item' ] );
		add_action( 'wp_ajax_nopriv_add_wish_item', [ $this, 'add_wish_item' ] );
		add_action( 'wp_ajax_delete_wish_item', [ $this, 'delete_wish_item' ] );
		add_action( 'wp_ajax_nopriv_delete_wish_item', [ $this, 'delete_wish_item' ] );

	}

	/**
	 * Ключб пользователя
	 * @return array
	 */
	private static function get_user_wish_key() {
		$array            = [];
		$array['user_id'] = get_current_user_id();
		$array['key']     = ! empty( $_COOKIE['wpp_wl_new'] ) ? $_COOKIE['wpp_wl_new'] : false;

		return $array;
	}

	/**
	 * Формирование строки и или или
	 * @return string
	 */
	private static function get_user_sql_str() {
		$str       = false;
		$key_array = self::get_user_wish_key();

		if ( ! empty( $key_array['user_id'] ) ) {
			$str .= sprintf( 'WHERE `user_id`=\'%s\'', esc_sql( $key_array['user_id'] ) );
		}

		if ( ! empty( $key_array['key'] ) ) {
			$preff = empty( $key_array['user_id'] ) ? 'WHERE' : ' OR';
			$str   .= sprintf( '%s `key`=\'%s\'', $preff, esc_sql( $key_array['key'] ) );
		}

		return $str;
	}

	/**
	 * Установка куки для вишлиста
	 * @return bool
	 */
	public static function set_wl_cookie() {

		if ( isset( $_COOKIE['wpp_wl_new'] ) ) {
			return false;
		}

		$cookie_key = md5( time() * rand( '100', '10000' ) );

		setcookie( 'wpp_wl_new', $cookie_key, time() + 1209600, COOKIEPATH, COOKIE_DOMAIN );

	}

	/**
	 * Количество позиций в вищлисте
	 */
	public function wl_count() {
		global $wpdb;
		$db_table_name = $wpdb->prefix . self::$table;

		$str = self::get_user_sql_str();
		if ( ! empty( $str ) ) {
			$count = $wpdb->get_var( sprintf( "SELECT COUNT(*) FROM $db_table_name %s", $str ) );

			$out = ! empty( $count ) ? $count : '';
		} else {
			$out = '';
		}

		return $out;
	}


	/**
	 * Количество позиций в вищлисте
	 */
	public function get_items() {
		global $wpdb;
		$db_table_name = $wpdb->prefix . self::$table;
		$str = self::get_user_sql_str();
		if ( ! empty( $str ) ) {
			$result = $wpdb->get_results(
				sprintf(
					"SELECT `item_cat`,`item_id` FROM $db_table_name %s ORDER BY `item_cat`",
					$str
				)
			);

			$out = ! empty( $result ) ? $result : '';
		} else {
			$out = '';
		}

		return $out;
	}

	/**
	 * Создание таблицы для вишлтиста
	 */
	public static function create_table() {

		global $wpdb;

		$wpdb->hide_errors();

		$db_table_name   = $wpdb->prefix . self::$table;  // table name
		$charset_collate = $wpdb->get_charset_collate();
		if ( $wpdb->get_var( sprintf( "show tables like '%s'", $db_table_name ) ) !== $db_table_name ) {
			$sql = sprintf( "CREATE TABLE `%s` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `key` varchar(255) NOT NULL,
                `user_id` bigint(20) NOT NULL,
                `item_cat` bigint(20) NOT NULL,
                `item_id` bigint(20) NOT NULL,
                UNIQUE KEY id (id)
        ) %s;", $db_table_name, $charset_collate );

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

	}

	/**
	 * Добавление пункта в таблицу
	 */
	public function add_wish_item() {


		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( [ 'status' => 1 ] );
		}
		parse_str( $_POST['data'], $wish_item );

		$item_cat = ! empty( $wish_item['item_cat'] ) ? (int) $wish_item['item_cat'] : 0;
		$item_id  = ! empty( $wish_item['item_id'] ) ? (int) $wish_item['item_id'] : false;
		if ( empty( $item_id ) ) {
			wp_send_json_error( [ 'status' => 2 ] );
		}


		$key = ! empty( $_COOKIE['wpp_wl_new'] ) ? $_COOKIE['wpp_wl_new'] : false;
		if ( empty( $key ) ) {
			wp_send_json_error( [ 'status' => 3 ] );
		}

		$user_id = get_current_user_id();

		global $wpdb;
		$db_table_name = $wpdb->prefix . self::$table;


		$insert = $wpdb->insert(
			$db_table_name,
			[ 'key' => $key, 'user_id' => $user_id, 'item_cat' => $item_cat, 'item_id' => $item_id ],
			[ '%s', '%d', '%d', '%d' ]
		);

		if ( empty( $insert ) ) {
			wp_send_json_error( [ 'status' => 4 ] );
		}

		wp_send_json_success( [ 'status' => 200, 'count' => $this->wl_count() ] );

	}

	/**
	 * Удаление пункта из таблицы
	 */
	public function delete_wish_item() {

		global $wpdb;
		$data_array = $type_array = [];

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( [ 'status' => 1 ] );
		}

		parse_str( $_POST['data'], $wish_item );

		$item_cat               = ! empty( $wish_item['item_cat'] ) ? (int) $wish_item['item_cat'] : 0;
		$data_array['item_cat'] = $item_cat;
		$type_array[]           = '%d';

		$item_id = ! empty( $wish_item['item_id'] ) ? (int) $wish_item['item_id'] : false;
		if ( empty( $item_id ) ) {
			wp_send_json_error( [ 'status' => 2 ] );
		}
		$data_array['item_id'] = $item_id;
		$type_array[]          = '%d';

		$key = ! empty( $_COOKIE['wpp_wl_new'] ) ? $_COOKIE['wpp_wl_new'] : false;
		if ( empty( $key ) ) {
			wp_send_json_error( [ 'status' => 3 ] );
		}
		$data_array['key'] = $key;
		$type_array[]      = '%s';


		$db_table_name = $wpdb->prefix . self::$table;
		$delete        = $wpdb->delete(
			$db_table_name,
			$data_array,
			$type_array
		);

		if ( empty( $delete ) ) {
			wp_send_json_error( [ 'status' => 4 ] );
		}

		wp_send_json_success( [ 'status' => 200, 'count' => $this->wl_count() ] );

	}

	/**
	 * Проверка нахожденитя в вишлисте
	 * @return array
	 */
	function get_wish_item_data( $id, $term_id ) {

		global $wpdb;
		$db_table_name = $wpdb->prefix . 'wpp_woo_wishlist';
		$key           = ! empty( $_COOKIE['wpp_wl_new'] ) ? $_COOKIE['wpp_wl_new'] : false;

		$data_item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $db_table_name WHERE `key` = %s AND `item_id` = %d", $key, $id ) );
		$check     = ! empty( $data_item ) ? true : false;

		$out = [];
		/**
		 * Вывод нужной иконки
		 */

		$icons       = apply_filters( 'wpp_fr_wishlist_icons', [
			'yes_icon' => get_template_directory_uri() . '/assets/img/icons/lists-icons.svg',
			'no_icon'  => get_template_directory_uri() . '/assets/img/icons/lists-icons.svg'
		] );
		$out['icon'] = ! empty( $check ) ? $icons['yes_icon'] : $icons['no_icon'];

		/**
		 * Вывод класса для обертки
		 */
		$classes      = apply_filters( 'wpp_fr_wishlist_classes', [
			'yes_class' => 'in-wpp-wish',
			'no_class'  => 'out-wpp-wish'
		] );
		$out['class'] = ! empty( $check ) ? $classes['yes_class'] : $classes['no_class'];

		/**
		 * Мелкий текст
		 */
		$out['small'] = empty( $check ) ? __( 'Save this to your wishlist', 'wpp-br' ) : __( 'Click to unsave', 'wpp-br' );

		/**
		 * Крупный текст
		 */
		$out['text'] = empty( $check ) ? __( 'Save to wishlist', 'wpp-br' ) : __( 'Saved to wishlist', 'wpp-br' );

		return $out;

	}

	/**
	 * Получение HTML иконки
	 * @return string
	 */
	function get_wish_html( $id, $term_id ) {
		$data = $this->get_wish_item_data( $id, $term_id );

		$wrap = <<<WPRAP
		<div class="wpp-wish-wrap %s" data-id="%s" data-term="%s">
		<span class="wpp-action-icon wpp-wish-icon" style="background-image: url('%s')"></span><span class="main-text">%s</span><span class="copy-text">%s</span>
		</div>
WPRAP;

		return sprintf( $wrap, $data['class'], $id, $term_id, $data['icon'], $data['text'], $data['small'] );
	}

	/**
	 * вывод HTML иконки
	 */
	function wish_html( $id, $term_id ) {
		echo $this->get_wish_html( $id, $term_id );
	}

	public static function wish_assets() {

		$wl = get_queried_object_id() === wpp_fr_get_page_id( 'wl' ) ? 'true' : 'false';
		wp_enqueue_script( 'wpp-wl', WPP_FR_PLUGIN_URL . 'wpp-woocommerce/wpp-wishilst/assets/wpp-wl.js', [ 'jquery' ], '2.0.0', true );
		wp_localize_script( 'wpp-wl', 'WppWlAjax', array(
			'ajaxurl'     => admin_url( 'admin-ajax.php' ),
			'security'    => wp_create_nonce( 'wpp-wl-string' ),
			'save_title'  => __( 'Save to wishlist', 'wpp-br' ),
			'save_desc'   => __( 'Save this to your wishlist', 'wpp-br' ),
			'saved_title' => __( 'Saved to wishlist', 'wpp-br' ),
			'saved_desc'  => __( 'Click to unsave', 'wpp-br' ),
			'wl'          => $wl
		) );
	}

}

$GLOBALS['wpp_wl'] = new Wpp_Fr_Wish();