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
	
	class Wpp_PF_Option_page {
		
		public static function init() {
			add_action( 'admin_menu', array ( __CLASS__, 'cr_create_menu' ) );
			add_action( 'admin_enqueue_scripts', array ( __CLASS__, 'cr_csv_assets' ) );
			add_action(
				'wp_ajax_cr_action', array (
					                   __CLASS__,
					                   'cr_action_callback'
				                   )
			);
			
			add_action(
				'wp_ajax_cr_load_import', array (
					                        __CLASS__,
					                        'cr_load_import_callback'
				                        )
			);
		}
		
		/**
		 * Подключение ресурсов
		 */
		public static function cr_csv_assets() {
			$replace = str_replace( ABSPATH, get_home_url() . '/', __DIR__ );
			wp_enqueue_script( 'wpp-pf', $replace . '/assets/wpp-pf.js', array ( 'jquery' ), '0.1.2', true );
			wp_localize_script(
				'wpp-pf', 'WppPf', array (
					        'security' => wp_create_nonce( 'wpp-pf-string' )
				        )
			);
			wp_enqueue_style( 'wpp-pf', $replace . '/assets/wpp-pf.css', array (), '0.0.1' );
		}
		
		/**
		 * Страницы меню
		 */
		public static function cr_create_menu() {
			
			add_menu_page(
				'WPP Product Filter', 'WPP Product Filter', 'manage_options', 'wpp_filter_page', '', 'dashicons-carrot'
			);
			
			add_submenu_page(
				'wpp_filter_page', 'WPP Product Filter', 'WPP Product Filter', 'manage_options', 'wpp_filter_options', array ( __CLASS__, 'cr_csv_import_page' )
			);
			
			remove_submenu_page( 'wpp_filter_page', 'wpp_filter_page' );
		}
		
		/**
		 * Шаблон страницы
		 *
		 * @param $title
		 * @param $left_block
		 * @param $right_block
		 */
		public static function cr_csv_page( $title, $left_block, $right_block ) { ?>
            <div class="wrap">
                <h1><?php echo $title; ?></h1>

                <div class="cr-left-wrapper">
					<?php echo $left_block; ?>
                    <div class="appender"></div>
                </div>
                <div class="cr-right-wrapper">
					<?php echo $right_block; ?>
                </div>


            </div>
		<?php }
		
		/**
		 * Страница импорта
		 */
		public static function cr_csv_import_page() {
			$title = 'WPP Product Filter Options';
			$left = '<div class="cr-admin-block">
					<h2 class="cr-admin-block-title">Reindexing data</h2>
					<div class="loader">
						<div class="indnicator" style="width:0%;"></div>
						<span></span>
					</div>
					<p class="submit cr-csv-check-file">
						<span id="cr-submit-csv" type="submit" class="button-primary">' . __( 'Reindexing data' ) . '</span>
					</p>
				</div>';
			$right = '';
			self::cr_csv_page( $title, $left, $right );
		}
		
		/**
		 * Проверка файла ajax
		 */
		public static function cr_action_callback() {
			
			ini_set( 'memory_limit', - 1 );
			self::myStartSession();
			
			$_SESSION[ 'index_tabler' ] = array ();
			check_ajax_referer( 'wpp-pf-string', 'security' );
			
			$count_posts = wp_count_posts( 'product' );
			$product_counts = (int) $count_posts->publish;
			
			wp_send_json_success( array ( 'count' => $product_counts ) );
			
		}
		
		
		public static function myStartSession() {
			if ( ! session_id() ) {
				session_start();
			}
		}
		
		
		public static function get_my_session_id() {
			return session_id();
		}
		/**
		 * Сам Импорт
		 */
		public static function cr_load_import_callback() {
			
			check_ajax_referer( 'wpp-pf-string', 'security' );
			
			$count = isset( $_POST[ 'offset' ] ) ? (int) $_POST[ 'offset' ] : null;
			
			$product = get_posts(
				
				array (
					'posts_per_page' => 1,
					'offset'         => $count - 1,
					'post_type'      => 'product',
					'post_status'    => 'publish',
				)
			
			);
			
			
			$post_id = $product[ 0 ]->ID;
			unset( $product );
			WPP_PF_Filter_DB::update_row( $post_id );
			$data = array ( 'count' => $count - 1 );
			
			wp_send_json_success( $data );
		}
		
	}
	
	
	
	Wpp_PF_Option_page::init();