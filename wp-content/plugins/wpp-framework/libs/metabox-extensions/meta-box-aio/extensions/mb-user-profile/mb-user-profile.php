<?php
/**
 * Plugin Name: MB User Profile
 * Plugin URI: https://metabox.io/plugins/mb-frontend-profile
 * Description: Submit posts and custom fields in the frontend.
 * Version: 1.1.1
 * Author: MetaBox.io
 * Author URI: https://metabox.io
 * License: GPL2+
 * Text Domain: mb-user-profile
 * Domain Path: /languages/
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;

require dirname( __FILE__ ) . '/vendor/GaryJones/Gamajo-Template-Loader/class-gamajo-template-loader.php';
require dirname( __FILE__ ) . '/vendor/meta-box/mb-user-meta/mb-user-meta.php';

if ( ! function_exists( 'mb_user_profile_load' ) ) {
	/**
	 * Hook to 'init' with priority 10 to make sure all meta boxes are registered.
	 */
	add_action( 'init', 'mb_user_profile_load', 20 );

	/**
	 * Load plugin files after Meta Box is loaded
	 */
	function mb_user_profile_load() {
		if ( ! defined( 'RWMB_VER' ) ) {
			return;
		}

		define( 'MB_USER_PROFILE_DIR', dirname( __FILE__ ) );
		list( , $url ) = RWMB_Loader::get_path( MB_USER_PROFILE_DIR );
		define( 'MB_USER_PROFILE_URL', $url );

		load_plugin_textdomain( 'mb-user-profile', false, basename( MB_USER_PROFILE_DIR ) . '/languages' );
		require MB_USER_PROFILE_DIR . '/mb-user-profile-fields.php';
		require MB_USER_PROFILE_DIR . '/inc/class-mb-user-profile-template-loader.php';
		require MB_USER_PROFILE_DIR . '/inc/class-mb-user-profile-helpers.php';
		require MB_USER_PROFILE_DIR . '/inc/class-mb-user-profile-user.php';

		require MB_USER_PROFILE_DIR . '/inc/forms/class-mb-user-profile-form.php';
		require MB_USER_PROFILE_DIR . '/inc/shortcodes/class-mb-user-profile-shortcode.php';

		require MB_USER_PROFILE_DIR . '/inc/forms/class-mb-user-profile-form-register.php';
		require MB_USER_PROFILE_DIR . '/inc/shortcodes/class-mb-user-profile-shortcode-register.php';

		require MB_USER_PROFILE_DIR . '/inc/forms/class-mb-user-profile-form-info.php';
		require MB_USER_PROFILE_DIR . '/inc/shortcodes/class-mb-user-profile-shortcode-info.php';

		require MB_USER_PROFILE_DIR . '/inc/forms/class-mb-user-profile-form-login.php';
		require MB_USER_PROFILE_DIR . '/inc/shortcodes/class-mb-user-profile-shortcode-login.php';

		$info = new MB_User_Profile_Shortcode_Info();
		$info->init();

		$register = new MB_User_Profile_Shortcode_Register();
		$register->init();

		$login = new MB_User_Profile_Shortcode_Login();
		$login->init();
	}
}
