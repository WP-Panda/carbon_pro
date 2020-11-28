<?php
/**
 * Installation related functions and actions.
 *
 * @package WooCommerce/Classes
 * @version 1.0.9
 */
defined( 'ABSPATH' ) || exit;
/**
 * WPP_Fr_Install Class.
 */
class WPP_Fr_Install {

	/**
	 * Hook in tabs.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'install' ), 5 );
	}

	/**
	 * Install Wpp_Fr.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}
		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'wpp_fr_installing' ) ) {
			return;
		}
		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'wpp_fr_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		wpp_fr_maybe_define_constant( 'WPP_FR_INSTALLING', true );


		self::create_tables();

		delete_transient( 'wpp_fr_installing' );
		do_action( 'wpp_fr_flush_rewrite_rules' );
		do_action( 'wpp_fr_installed' );
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 *
	 * Tables:
	 *      woocommerce_attribute_taxonomies - Table for storing attribute taxonomies - these are user defined
	 *      woocommerce_downloadable_product_permissions - Table for storing user and guest download permissions.
	 *          KEY(order_id, product_id, download_id) used for organizing downloads on the My Account page
	 *      woocommerce_order_items - Order line items are stored in a table to make them easily queryable for reports
	 *      woocommerce_order_itemmeta - Order line item meta is stored in a table for storing extra data.
	 *      woocommerce_tax_rates - Tax Rates are stored inside 2 tables making tax queries simple and efficient.
	 *      woocommerce_tax_rate_locations - Each rate can be applied to more than one postcode/city hence the second table.
	 */
	private static function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		/**
		 * Before updating with DBDELTA, remove any primary keys which could be
		 * modified due to schema updates.
		 */
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}woocommerce_downloadable_product_permissions';" ) ) {
			if ( ! $wpdb->get_var( "SHOW COLUMNS FROM `{$wpdb->prefix}woocommerce_downloadable_product_permissions` LIKE 'permission_id';" ) ) {
				$wpdb->query( "ALTER TABLE {$wpdb->prefix}woocommerce_downloadable_product_permissions DROP PRIMARY KEY, ADD `permission_id` BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;" );
			}
		}
		/**
		 * Change wp_woocommerce_sessions schema to use a bigint auto increment field instead of char(32) field as
		 * the primary key as it is not a good practice to use a char(32) field as the primary key of a table and as
		 * there were reports of issues with this table (see https://github.com/woocommerce/woocommerce/issues/20912).
		 *
		 * This query needs to run before dbDelta() as this WP function is not able to handle primary key changes
		 * (see https://github.com/woocommerce/woocommerce/issues/21534 and https://core.trac.wordpress.org/ticket/40357).
		 */
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}woocommerce_sessions'" ) ) {
			if ( ! $wpdb->get_var( "SHOW KEYS FROM {$wpdb->prefix}woocommerce_sessions WHERE Key_name = 'PRIMARY' AND Column_name = 'session_id'" ) ) {
				$wpdb->query(
					"ALTER TABLE `{$wpdb->prefix}woocommerce_sessions` DROP PRIMARY KEY, DROP KEY `session_id`, ADD PRIMARY KEY(`session_id`), ADD UNIQUE KEY(`session_key`)"
				);
			}
		}
		dbDelta( self::get_schema() );
		$index_exists = $wpdb->get_row( "SHOW INDEX FROM {$wpdb->comments} WHERE column_name = 'comment_type' and key_name = 'woo_idx_comment_type'" );
		if ( is_null( $index_exists ) ) {
			// Add an index to the field comment_type to improve the response time of the query
			// used by WC_Comments::wp_count_comments() to get the number of comments by type.
			$wpdb->query( "ALTER TABLE {$wpdb->comments} ADD INDEX woo_idx_comment_type (comment_type)" );
		}
		// Get tables data types and check it matches before adding constraint.
		$download_log_columns     = $wpdb->get_results( "SHOW COLUMNS FROM {$wpdb->prefix}wc_download_log WHERE Field = 'permission_id'", ARRAY_A );
		$download_log_column_type = '';
		if ( isset( $download_log_columns[0]['Type'] ) ) {
			$download_log_column_type = $download_log_columns[0]['Type'];
		}
		$download_permissions_columns     = $wpdb->get_results( "SHOW COLUMNS FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions WHERE Field = 'permission_id'", ARRAY_A );
		$download_permissions_column_type = '';
		if ( isset( $download_permissions_columns[0]['Type'] ) ) {
			$download_permissions_column_type = $download_permissions_columns[0]['Type'];
		}
		// Add constraint to download logs if the columns matches.
		if ( ! empty( $download_permissions_column_type ) && ! empty( $download_log_column_type ) && $download_permissions_column_type === $download_log_column_type ) {
			$fk_result = $wpdb->get_row(
				"SELECT COUNT(*) AS fk_count
				FROM information_schema.TABLE_CONSTRAINTS
				WHERE CONSTRAINT_SCHEMA = '{$wpdb->dbname}'
				AND CONSTRAINT_NAME = 'fk_{$wpdb->prefix}wc_download_log_permission_id'
				AND CONSTRAINT_TYPE = 'FOREIGN KEY'
				AND TABLE_NAME = '{$wpdb->prefix}wc_download_log'"
			); // WPCS: unprepared SQL ok.
			if ( 0 === (int) $fk_result->fk_count ) {
				$wpdb->query(
					"ALTER TABLE `{$wpdb->prefix}wc_download_log`
					ADD CONSTRAINT `fk_{$wpdb->prefix}wc_download_log_permission_id`
					FOREIGN KEY (`permission_id`)
					REFERENCES `{$wpdb->prefix}woocommerce_downloadable_product_permissions` (`permission_id`) ON DELETE CASCADE;"
				); // WPCS: unprepared SQL ok.
			}
		}
		// Clear table caches.
		delete_transient( 'wc_attribute_taxonomies' );
	}

}