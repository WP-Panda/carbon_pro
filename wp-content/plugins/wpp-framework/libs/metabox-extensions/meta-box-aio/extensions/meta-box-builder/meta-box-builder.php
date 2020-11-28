<?php
/**
 * Plugin Name: Meta Box Builder
 * Plugin URI: https://metabox.io/plugins/meta-box-builder/
 * Description: Drag and drop UI for creating custom meta boxes and custom fields.
 * Version: 2.9.3
 * Author: MetaBox.io
 * Author URI: https://metabox.io
 * License: GPL2+
 *
 * @package    Meta Box
 * @subpackage Meta Box Builder
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'mb_builder_load' ) ) {
	/**
	 * Hook to 'init' with priority 5 to make sure all actions are registered before Meta Box 4.9.0 runs
	 */
	add_action( 'init', 'mb_builder_load', 5 );

	/**
	 * Load plugin files after Meta Box is loaded
	 */
	function mb_builder_load() {

		if ( ! defined( 'RWMB_VER' ) || class_exists( 'Meta_Box_Builder' ) ) {
			return;
		}

		if ( ! defined( 'MBB_DIR' ) ) {
			define( 'MBB_DIR', dirname( __FILE__ ) );
		}

		define( 'MBB_INC_DIR', trailingslashit( MBB_DIR . '/inc/' ) );
		define( 'MBB_FIELDS_DIR', trailingslashit( MBB_INC_DIR . '/fields/' ) );

		// Show Meta Box admin menu.
		add_filter( 'rwmb_admin_menu', '__return_true' );

		require_once MBB_INC_DIR . 'helpers.php';
		require_once MBB_INC_DIR . 'class-meta-box-attribute.php';
		require_once MBB_FIELDS_DIR . 'field.php';
		require_once MBB_INC_DIR . 'class-meta-box-show-hide-template.php';
		require_once MBB_INC_DIR . 'class-meta-box-include-exclude-template.php';
		require_once MBB_INC_DIR . 'class-meta-box-processor.php';
		require_once MBB_INC_DIR . 'class-meta-box-import.php';
		require_once MBB_INC_DIR . 'class-meta-box-builder.php';

		new Meta_Box_Builder();
	}
}
