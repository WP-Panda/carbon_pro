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
	
	class WPP_PF_Filter_DB {
		
		public static $table = 'wpp_prdf_filter';
		
		public static function init() {
			add_action( 'init', array ( __CLASS__, 'install' ) );
			add_action( 'woocommerce_attribute_added', array ( __CLASS__, 'created' ), 10, 2 );
			add_action( 'woocommerce_attribute_updated', array ( __CLASS__, 'rename' ), 10, 3 );
			add_action( 'woocommerce_attribute_deleted', array ( __CLASS__, 'remove_column' ), 10, 3 );
			add_action( 'save_post_product', array(
				__CLASS__,
				'update_post'
			), 150 );
			
			add_action( 'transition_post_status', array(
				__CLASS__,
				'wpp_run_on_publish_only'
			), 10, 3 );
		}
		
		/**
		 * @param $atts      - атрибут
		 * @param $delimiter - разделитель
		 *
		 * @return string
		 */
		public static function add_one_rov_sql( $atts, $delimiter, $coma ) {
			return 'pa_' . wpp_bt_convert_atts( $atts ) . ' VARCHAR(200) NOT NULL DEFAULT \'\'' . $coma . $delimiter;
		}
		
		/**
		 * Добавление атрибута запрос
		 *
		 * @param $atts
		 */
		public static function add_column( $atts ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			$str = self::add_one_rov_sql( $atts, '', '' );
			$wpdb->query( "ALTER TABLE $table_name ADD $str" );
		}
		
		/**
		 * Удаление
		 *
		 * @param $atts
		 */
		public static function remove_column( $id, $name, $taxonomy ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			$str = wpp_bt_convert_atts( $taxonomy );
			$wpdb->query( "ALTER TABLE $table_name DROP $str" );
		}
		
		/**
		 * Добавление атрибута
		 *
		 * @param $id
		 * @param $data
		 */
		public static function created( $id, $data ) {
			self::add_column( $data[ 'attribute_name' ] );
		}
		
		/**
		 * Переименование атрибута
		 *
		 * @param $id
		 * @param $data
		 */
		public static function rename( $id, $data, $old_slug ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			
			$old_str = wpp_bt_convert_atts( 'pa_' . $old_slug );
			$new_str = wpp_bt_convert_atts( 'pa_' . $data[ 'attribute_name' ] );
			$collate = '';
			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}
			$collate = str_replace( 'DEFAULT', '', $collate );
			$wpdb->query(
				"ALTER TABLE $table_name CHANGE $old_str $new_str VARCHAR(200) $collate NOT NULL DEFAULT ''"
			);
		}
		
		/**
		 * Действия при создании плагина
		 */
		public static function install() {
			
			if ( ! is_blog_installed() ) {
				return;
			}
			
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name )  :
				
				$collate = '';
				$command_add = '';
				if ( $wpdb->has_cap( 'collation' ) ) {
					$collate = $wpdb->get_charset_collate();
				}
				
				$attribute_taxonomies = wc_get_attribute_taxonomies();
				$count = count( $attribute_taxonomies );
				if ( 0 !== $count ) {
					$n = 0;
					foreach ( $attribute_taxonomies as $one_tax ) {
						$n ++;
						$delimiter = $n === $count ? '' : "\n";
						$coma = ',';
						$command_add .= self::add_one_rov_sql( $one_tax->attribute_name, $delimiter, $coma );
					}
				}
				
				$command = <<<COMMAND
					CREATE TABLE %s (
					id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
					post_id int(11) UNSIGNED NOT NULL,
					price int(11) UNSIGNED NOT NULL,
					stock_status VARCHAR(200) NOT NULL DEFAULT '',
					sku  VARCHAR(200) NOT NULL DEFAULT '',
					title  VARCHAR(800) NOT NULL DEFAULT '',
					product_cat VARCHAR(200) NOT NULL DEFAULT '',
					product_tag VARCHAR(200) NOT NULL DEFAULT '',
					%s
					PRIMARY KEY (id),
					KEY post_id (post_id),
					KEY product_cat (product_cat)
					) %s;
COMMAND;
				/**
				 * For Developer
				 */
				if ( function_exists( 'wpp_d_log' ) && defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
					#wpp_d_log( $command );
				}
				
				$table = sprintf( $command, $table_name, $command_add, $collate );
				$wpdb->hide_errors();
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $table );
			
			endif;
			
		}
		
		/**
		 * Изменение / добаление строки
		 *
		 * @param $post_id
		 */
		public static function update_row( $post_id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . self::$table;
			
			$array = wpp_bt_prd_atts( $post_id, true );
			
			$flag = $wpdb->get_row( "SELECT * FROM $table_name WHERE post_id = " . $post_id );
			
			if ( ! empty( $flag ) ) {
				$wpdb->update( $table_name, $array, array ( 'post_id' => $post_id ) );
			} else {
				$array[ 'post_id' ] = $post_id;
				$wpdb->insert( $table_name, $array );
			}
		}
		
		public static function update_post($post_id){
			if ( isset( $_POST[ 'post_status' ] ) && 'publish' === $_POST[ 'post_status' ] ) {
				self::update_row( $post_id );
			}
		}
		
		
		/**
		 * Действия при обновлении
		 *
		 * @param $new_status
		 * @param $old_status
		 * @param $post
		 */
		public static function wpp_run_on_publish_only( $new_status, $old_status, $post ) {
			
			remove_action( 'save_post_product', array(
				__CLASS__,
				'update_post'
			), 150 );
			
			if ( ( 'publish' == $new_status && 'publish' !== $old_status ) && 'product' == $post->post_type ) {
				self::update_row( $post->ID );
			} elseif ( ( 'publish' !== $new_status && 'publish' == $old_status ) && 'product' == $post->post_type ) {
				global $wpdb;
				$table_name = $wpdb->prefix . self::$table;
				$wpdb->delete( $table_name, array( 'post_id' => $post->ID ), array( '%d' ) );
			}
		}
		
	}
	
	WPP_PF_Filter_DB::init();