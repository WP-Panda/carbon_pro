<?php
/**
 * Plugin Name: Meta Box AIO
 * Plugin URI: https://metabox.io/plugins/developer-bundle/
 * Description: All Meta Box extensions in one package.
 * Version: 1.6.8
 * Author: MetaBox.io
 * Author URI: https://metabox.io
 * License: GPL2+
 * Text Domain: meta-box
 * Domain Path: /languages/
 *
 * @package    Meta Box
 * @subpackage Meta Box AIO
 */

defined( 'ABSPATH' ) || die;

require_once dirname( __FILE__ ) . '/class-mb-aio-loader.php';
new MB_AIO_Loader();

require_once dirname( __FILE__ ) . '/extensions/mb-settings-page/mb-settings-page.php';
require_once dirname( __FILE__ ) . '/class-mb-aio-settings.php';
new MB_AIO_Settings();

if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/class-mb-aio-dashboard-widget.php';
	new MB_AIO_Dashboard_Widget();
}
