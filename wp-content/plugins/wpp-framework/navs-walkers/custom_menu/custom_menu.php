<?php

/**
 * Create additional attributes in WordPress menus
 */
class сr_custom_menu {

	public static $preff = 'yamm-wpp';
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// Добавление произвольных полей к меню
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

		if ( is_admin() ) {
			// кастомизация сборщика
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ), 10, 2 );
			// сохранение полей меню
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields' ), 10, 3 );
			// Подключение стилей
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		} else {
			// load frontend scripts and styles
			//add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}

	}

	function admin_scripts() {
		wp_enqueue_style( self::$preff . 'font-awesome', WPP_BT_NEW_ASSETS_URI . 'css/font-awesome.min.css', null, '4.7.0', 'all' );
		wp_enqueue_style( self::$preff . 'custom-menu-admin-style', BT_TMP_DIR_URL . '/wpp_bt_framework/navs-walkers/custom_menu/custom_menu_admin.css', array(), null );
		wp_enqueue_script( self::$preff . 'custom-menu-admin-script', BT_TMP_DIR_URL . '/wpp_bt_framework/navs-walkers/custom_menu/custom_menu_admin.js', array( 'jquery-core' ), null, true );
		wp_enqueue_script( self::$preff . 'theme-admin-script', BT_TMP_DIR_URL . '/wpp_bt_framework/navs-walkers/custom_menu/_admin.js', array( 'jquery-core' ), null, true );
	}

//	function frontend_scripts() {
//		wp_enqueue_style( 'custom-menu-style', ALFUS_URI . 'framework/custom_menu/custom_menu.css', array(), null );
//		wp_enqueue_script( 'custom-menu-script', ALFUS_URI . 'framework/custom_menu/custom_menu.js', array( 'jquery' ), null, true );
//	}


	/**
	 * Добавляет произвольные поля
	 */
	function add_custom_nav_fields( $menu_item ) {

		$item_custom_data = get_post_meta( $menu_item->ID, '_item_custom_data', true );

		$menu_item->item_thumb       = isset( $item_custom_data[ 'item_thumb' ] ) ? $item_custom_data[ 'item_thumb' ] : ''; #миниатюра
		$menu_item->top_level_type   = isset( $item_custom_data[ 'top_level_type' ] ) ? $item_custom_data[ 'top_level_type' ] : '';
		$menu_item->item_icon_class  = isset( $item_custom_data[ 'item_icon_class' ] ) ? $item_custom_data[ 'item_icon_class' ] : ''; #иконка
		$menu_item->auto_items_count = isset( $item_custom_data[ 'auto_items_count' ] ) ? $item_custom_data[ 'auto_items_count' ] : '';
		$menu_item->top_menu_view    = isset( $item_custom_data[ 'top_menu_view' ] ) ? $item_custom_data[ 'top_menu_view' ] : '';
		$menu_item->item_sorting_by  = isset( $item_custom_data[ 'item_sorting_by' ] ) ? $item_custom_data[ 'item_sorting_by' ] : '';
		$menu_item->post_types_list  = isset( $item_custom_data[ 'post_types_list' ] ) ? $item_custom_data[ 'post_types_list' ] : '';
		$menu_item->categories       = isset( $item_custom_data[ 'cat_list' ] ) ? $item_custom_data[ 'cat_list' ] : '';
		$menu_item->column_width     = isset( $item_custom_data[ 'column_width' ] ) ? $item_custom_data[ 'column_width' ] : ''; #ширина колонки

		return $menu_item;
	}

	/**
	 * Обновление произвольных полей
	 *
	 * @param string $menu_id
	 * @param string $menu_item_db_id
	 * @param array  $args
	 */
	function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		$item_custom_data = array();

		// Check if element is properly sent
		if ( isset( $_REQUEST[ 'item_thumb_holder' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'item_thumb' ] = $_REQUEST[ 'item_thumb_holder' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'top_level_type' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'top_level_type' ] = $_REQUEST[ 'top_level_type' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'item_icon_class' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'item_icon_class' ] = $_REQUEST[ 'item_icon_class' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'auto_items_count' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'auto_items_count' ] = $_REQUEST[ 'auto_items_count' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'top_menu_view' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'top_menu_view' ] = $_REQUEST[ 'top_menu_view' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'item_sorting_by' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'item_sorting_by' ] = $_REQUEST[ 'item_sorting_by' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'post_types_list' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'post_types_list' ] = $_REQUEST[ 'post_types_list' ][ $menu_item_db_id ];
		}
		if ( isset( $_REQUEST[ 'cat_list' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'cat_list' ] = $_REQUEST[ 'cat_list' ][ $menu_item_db_id ];
		}

		if ( isset( $_REQUEST[ 'column_width' ][ $menu_item_db_id ] ) ) {
			$item_custom_data[ 'column_width' ] = $_REQUEST[ 'column_width' ][ $menu_item_db_id ];
		}

		update_post_meta( $menu_item_db_id, '_item_custom_data', $item_custom_data );

	}

	/**
	 * Подключение сборки Меню
	 *
	 * @param $walker
	 * @param $menu_id
	 *
	 * @return string
	 */
	function edit_walker( $walker, $menu_id ) {

		return 'Walker_Nav_Menu_Edit_Custom';

	}
}

// instantiate plugin's class
global $сr_custom_menu;
$сr_custom_menu = new сr_custom_menu();

if ( is_admin() ) {
	require_once( 'custom_walker_admin.php' );
} else {
	require_once( 'yamm-menu-class.php' );
}


/**
 * Получение классов иконок
 */
if ( ! function_exists( 'cr_get_fa_list' ) ) {
	function cr_get_fa_list() {

		$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
		$subject = file_get_contents( __DIR__ . '/font-awesome.css' );
		preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );
		$icons = array();
		foreach ( $matches as $match ) {
			$icons[] = $match[ 1 ];
		}

		return $icons;
	}
}