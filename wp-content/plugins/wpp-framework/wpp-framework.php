<?php
/**
 * @package WPP Framework
 * @version 1.0.3
 */
/*
 * Plugin Name: WPP Framework
 * Plugin URI: mailto:yoowordpress@yandex.ru?subject=WPP Framework
 * Description: Плагин фреймворк для сборки всякого от WP Panda
 * Author: WP Panda
 * Version: 1.0.6
 * Author URI: mailto:yoowordpress@yandex.ru?subject=WPP Framework
 * Text Domain: wpp-fr
 */
/**
 * Plugin Name: AWC Framework
 * Plugin URI: https://advancedwebcare.com
 * Description: Framework  Plugin by Advanced Web Care
 * Author: AWC
 * Version: 1.0.5
 * Author URI: https://advancedwebcare.com
 * Text Domain: wpp-fr
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! defined( 'WPP_PLUGIN_FILE' ) ) {
	define( 'WPP_PLUGIN_FILE', __FILE__ );
}

define( 'WPP_FRAMEWORK', '1.0.9' );

class WPP_Framework {

	/**
	 * WppFramework version.
	 *
	 * @var string
	 */
	public $version = '1.0.9';

	/**
	 * The single instance of the class.
	 *
	 * @var WPP_Framework
	 * @since 1.0.2
	 */
	protected static $_instance = null;

	/**
	 * Main WPP_Framework Instance.
	 *
	 * Ensures only one instance of WPP_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.2
	 * @static
	 * @see   WPP_FR()
	 * @return WPP_Framework - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * WPP_Framework Constructor.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'wpp_framework_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.2
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}


	/**
	 * Init WooCommerce when WordPress Initialises.
	 *
	 * @since 1.0.2
	 */
	public function init() {
		// Before init action.
		do_action( 'before_wpp_fr_init' );

		// Set up localisation.
		#$this->load_plugin_textdomain();

		// Init action.
		do_action( 'wpp_fr_init' );
	}

	/**
	 * Define WPP Constants.
	 *
	 * @since 1.0.2
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir( null, false );

		$this->define( 'WPP_ABSPATH', dirname( WPP_PLUGIN_FILE ) . '/' );
		$this->define( 'WPP_PLUGIN_BASENAME', plugin_basename( WPP_PLUGIN_FILE ) );
		$this->define( 'WPP_FR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'WPP_FR_PLUGIN_ICONS_URL', WPP_FR_PLUGIN_URL . 'assets/icons/' );

		#когда надо подключать PDF
		$this->define( 'WPP_FR_PDF', true );

		/**
		 * Для GEO API
		 */
		#огда надо подключать гео
		$this->define( 'WPP_FR_GEO', true );
		#когда надо отдекбажить гео
		$this->define( 'WPP_FR_GEO_DEV', false );
		#когла надо подключить mailchimp
		$this->define( 'WPP_FR_MAILCHIMP', true );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since 1.0.2
	 *
	 * @param string $name Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {

		include_once WPP_ABSPATH . 'libs/init.php';
		include_once WPP_ABSPATH . 'wpp-must-have/classes/init.php';
		include_once WPP_ABSPATH . 'wpp-must-have/init.php';
		#include_once WPP_ABSPATH . 'wpp-extention/wpp-cookie-notis/init.php';
		include_once WPP_ABSPATH . 'settings/init.php';
		#include_once WPP_ABSPATH . 'navs-walkers/font-awesome-walker.php';
		#include_once WPP_ABSPATH . 'navs-walkers/custom_menu/custom_menu.php';
		#include_once WPP_ABSPATH . 'wpp-extention/wpp-page-builder/wpp-page-builder.php';
		include_once WPP_ABSPATH . 'wpp-extention/wpp-account/init.php';
		#include_once WPP_ABSPATH . 'wpp-extention/wpp-notification/wpp-notification.php';
		#include_once WPP_ABSPATH . 'wpp-extention/top-bottom/top-bottom.php';

		if ( is_admin() ) :
			require_once WPP_ABSPATH . 'wpp-extention/wpp-notification/wpp-notification.php';
		endif;

		if ( function_exists( 'icl_object_id' ) ) {
			include_once WPP_ABSPATH . 'wpp-wpml/init.php';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			include_once WPP_ABSPATH . 'wpp-woocommerce/init.php';
		}


	#	include_once WPP_ABSPATH . 'wpp-extention/wpp-mailchimp/init.php';
		#include_once WPP_ABSPATH . 'wpp-extention/wpp-bt-modal-login/init.php';
		#include_once WPP_ABSPATH . 'wpp-extention/wpp-bt-prd-filter/init.php';

	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', WPP_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin url.
	 *
	 * @since 1.0.2
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( WPP_PLUGIN_FILE ) );
	}

}

/**
 * Main instance of WPP_Framework.
 *
 * Returns the main instance of WPP to prevent the need to use globals.
 *
 * @since  1.0.2
 * @return WPP_Framework
 */
function wpp_fr() {
	return WPP_Framework::instance();
}

// Global for backwards compatibility.
$GLOBALS['wpp_framework'] = wpp_fr();