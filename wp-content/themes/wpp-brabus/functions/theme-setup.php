<?php
/**
 * Created by PhpStorm.
 * User: WP_Panda
 * Date: 21.05.2019
 * Time: 17:44
 */

require_once 'settings/theme-setting.php';
if ( ! function_exists( 'wpp_brabus_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * Create your own wpp_brabus_setup() function to override in a child theme.
	 *
	 * @since Twenty Sixteen 1.0
	 */
	function wpp_brabus_setup() {

		load_theme_textdomain( 'wpp-brabus' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );

		add_theme_support( 'custom-logo', [
			/*'height'      => 240,
			'width'       => 240,*//*'height'      => 240,
			'width'       => 240,*/
			'flex-height' => true,
		] );

		// This theme uses wp_nav_menu() in two locations.


		$langs = wpp_set_locate_args();
		/*$def_lng = get_option( 'WPLANG' );*/

		$args_navs = [];

		if ( ! empty( $langs ) && is_array( $langs ) ) {

			foreach ( $langs as $lang => $val ) {
				$args_navs[ 'main_' . $lang ]   = $val . ' ' .  __( ' Side Menu ', 'wpp-brabus' );
				$args_navs[ 'top_' . $lang ]    = $val . ' ' .   __( ' Top Menu ', 'wpp-brabus' );
				$args_navs[ 'footer_' . $lang ] = $val . ' ' .   __( ' Footer Menu ', 'wpp-brabus' );
			}
		}

		register_nav_menus( $args_navs );

		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		] );

		add_theme_support( 'woocommerce', [
			'product_grid' => [
				'default_rows'    => 3,
				'min_rows'        => 2,
				'max_rows'        => 8,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 5
			]
		] );

		add_editor_style( [
			'css/editor-style.css',
			#wpp_brabus_fonts_url()
		] );

		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom color scheme.
		add_theme_support( 'editor-color-palette', [
			[
				'name'  => __( 'Dark Gray', 'wpp-brabus' ),
				'slug'  => 'dark-gray',
				'color' => '#1a1a1a',
			],
			[
				'name'  => __( 'Medium Gray', 'wpp-brabus' ),
				'slug'  => 'medium-gray',
				'color' => '#686868',
			],
			[
				'name'  => __( 'Light Gray', 'wpp-brabus' ),
				'slug'  => 'light-gray',
				'color' => '#e5e5e5',
			],
			[
				'name'  => __( 'White', 'wpp-brabus' ),
				'slug'  => 'white',
				'color' => '#fff',
			],
			[
				'name'  => __( 'Blue Gray', 'wpp-brabus' ),
				'slug'  => 'blue-gray',
				'color' => '#4d545c',
			],
			[
				'name'  => __( 'Bright Blue', 'wpp-brabus' ),
				'slug'  => 'bright-blue',
				'color' => '#007acc',
			],
			[
				'name'  => __( 'Light Blue', 'wpp-brabus' ),
				'slug'  => 'light-blue',
				'color' => '#9adffd',
			],
			[
				'name'  => __( 'Dark Brown', 'wpp-brabus' ),
				'slug'  => 'dark-brown',
				'color' => '#402b30',
			],
			[
				'name'  => __( 'Medium Brown', 'wpp-brabus' ),
				'slug'  => 'medium-brown',
				'color' => '#774e24',
			],
			[
				'name'  => __( 'Dark Red', 'wpp-brabus' ),
				'slug'  => 'dark-red',
				'color' => '#640c1f',
			],
			[
				'name'  => __( 'Bright Red', 'wpp-brabus' ),
				'slug'  => 'bright-red',
				'color' => '#ff675f',
			],
			[
				'name'  => __( 'Yellow', 'wpp-brabus' ),
				'slug'  => 'yellow',
				'color' => '#ffef8e',
			],
		] );

		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}

endif;

add_action( 'after_setup_theme', 'wpp_brabus_setup' );


# Replace Posts label as Articles in Admin Panel

function change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0]                 = 'Новости';
	$submenu['edit.php'][5][0]  = 'Новости';
	$submenu['edit.php'][10][0] = 'Добавить новость';
	echo '';
}

add_action( 'admin_menu', 'change_post_menu_label' );