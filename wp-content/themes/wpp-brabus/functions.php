<?php
/**
 * Created by PhpStorm.
 * User: WP_Panda
 * Date: 21.05.2019
 * Time: 9:28
 */

define( 'BRABUS_PREF', 'brabus_' );
define( 'BRABUS_VER', '0.1.085' );
define( 'BRABUS_URL', get_template_directory_uri() );
define( 'BRABUS_DIR', get_template_directory() );
define( 'WPP_DOMAIN', 'carbon.pro' );
define( 'WPP_PROTOCOL', 'https://' );

define( 'BR_DOMAIN', $_SERVER['SERVER_NAME'] );

$files = [
	BRABUS_DIR . '/core/init.php',
	BRABUS_DIR . '/functions/theme-setup.php',
	BRABUS_DIR . '/functions/assets.php',
	BRABUS_DIR . '/functions/custom-gallery.php',
	BRABUS_DIR . '/functions/content-mod.php',
	BRABUS_DIR . '/functions/settings/meta-boxes.php',
	BRABUS_DIR . '/functions/settings/media_setting.php',
	BRABUS_DIR . '/functions/settings/woo-config.php',
	BRABUS_DIR . '/functions/settings/wpp-framework-settings.php',
	BRABUS_DIR . '/functions/shortcodes.php',
	BRABUS_DIR . '/functions/settings/taxonomies.php',
	BRABUS_DIR . '/functions/settings/term-boxes.php',
	BRABUS_DIR . '/functions/woo-ajax.php',
	BRABUS_DIR . '/functions/bulk-actions.php',
	BRABUS_DIR . '/functions/create-variants.php',
	BRABUS_DIR . '/functions/currency.php',
	BRABUS_DIR . '/functions/lang.php',
	BRABUS_DIR . '/functions/actions.php',
	BRABUS_DIR . '/functions/add-to-cart.php',
	BRABUS_DIR . '/functions/markup.php'
];


require_once 'headers.php';

wpp_fr_require_files( $files );

class R34SVG {

	var $mimes = array(
		'svg'  => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
	);


	var $svg_strings = array(
		'<svg',
		'"http://www.w3.org/2000/svg"',
	);


	function __construct() {
		add_filter( 'upload_mimes', array( @$this, 'upload_mimes' ), 10, 1 );
		add_filter( 'wp_check_filetype_and_ext', array( @$this, 'wp_check_filetype_and_ext' ), 10, 3 );
		add_filter( 'wp_get_attachment_metadata', array( @$this, 'wp_get_attachment_metadata' ), 10, 2 );
	}


	// Add SVG MIME types to allowed uploads
	function upload_mimes( $mimes ) {
		return $mimes + $this->mimes;
	}


	// Modify WP 4.7 upload validation to handle SVG
	function wp_check_filetype_and_ext( $wp_check_filetype_and_ext, $file, $filename ) {
		extract( $wp_check_filetype_and_ext );

		// Get passed file's extension
		$file_ext = pathinfo( $filename, PATHINFO_EXTENSION );

		// Check if the default WP validator returned false but the file appears to be an SVG
		if (
			$type == false && $ext == false &&
			in_array( $file_ext, array_keys( $this->mimes ) ) &&
			file_exists( $file )
		) {

			// Read file contents and confirm valid XML
			$file_contents = file_get_contents( $file );
			$valid_xml     = simplexml_load_string( $file_contents );

			// Check file contents for required SVG strings
			$valid_svg = ( $valid_xml != false );
			if ( $valid_svg ) {
				foreach ( $this->svg_strings as $svg_string ) {
					if ( strpos( $file_contents, $svg_string ) === false ) {
						$valid_svg = false;
						break;
					}
				}
			}

			// Valid SVG
			if ( $valid_svg ) {

				// Set type and extension for SVG
				$ext  = $file_ext;
				$type = $this->mimes[ $ext ];

				// Set proper filename
				// Modified from wp-includes/functions.php lines 2307-2314
				$filename_parts = explode( '.', $filename );
				array_pop( $filename_parts );
				$filename_parts[] = $ext;
				$proper_filename  = implode( '.', $filename_parts );
			} // Invalid SVG
			else {
				$ext = $type = $proper_filename = false;
			}

			// Update return values
			$wp_check_filetype_and_ext = compact( 'ext', 'type', 'proper_filename' );
		}

		return $wp_check_filetype_and_ext;
	}


	// Add minimal amount of file meta data to get Media Library to display SVGs in grid
	function wp_get_attachment_metadata( $data, $post_id ) {
		if ( ! $data ) {
			$data = array(
				'sizes' => array(
					'large' => array(
						'file' => pathinfo( wp_get_attachment_url( $post_id ), PATHINFO_BASENAME )
					)
				)
			);
		}

		return $data;
	}

}


// Initialize plugin
add_action( 'admin_init', function () {
	$R34SVG = new R34SVG;
} );


Class IBenic_Walker extends Walker_Nav_Menu {


	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output
	 * @param object $item Объект элемента меню, подробнее ниже.
	 * @param int $depth Уровень вложенности элемента меню.
	 * @param object $args Параметры функции wp_nav_menu
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		/*
		 * Некоторые из параметров объекта $item
		 * ID - ID самого элемента меню, а не объекта на который он ссылается
		 * menu_item_parent - ID родительского элемента меню
		 * classes - массив классов элемента меню
		 * post_date - дата добавления
		 * post_modified - дата последнего изменения
		 * post_author - ID пользователя, добавившего этот элемент меню
		 * title - заголовок элемента меню
		 * url - ссылка
		 * attr_title - HTML-атрибут title ссылки
		 * xfn - атрибут rel
		 * target - атрибут target
		 * current - равен 1, если является текущим элементом
		 * current_item_ancestor - равен 1, если текущим (открытым на сайте) является вложенный элемент данного
		 * current_item_parent - равен 1, если текущим (открытым на сайте) является родительский элемент данного
		 * menu_order - порядок в меню
		 * object_id - ID объекта меню
		 * type - тип объекта меню (таксономия, пост, произвольно)
		 * object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
		 * type_label - название данного типа с локализацией (Рубрика, Страница)
		 * post_parent - ID родительского поста / категории
		 * post_title - заголовок, который был у поста, когда он был добавлен в меню
		 * post_name - ярлык, который был у поста при его добавлении в меню
		 */

		if ( $item->object === 'product_cat' ) {
			$thumb = get_term_meta( $item->object_id, 'logo', true );
		}
		/*if ( ! empty( $thumb ) ) {
			wpp_dump( $thumb );
		}*/
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';
		if ( ! empty( $thumb ) ) {
			$src    = wp_get_attachment_image_src( $thumb );
			$output .= sprintf( '<img class="wpp-menu-logo-img" src="%s">', $src[0] );
		}

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string $title Title attribute.
		 * @type string $target Target attribute.
		 * @type string $rel The rel attribute.
		 * @type string $href The href attribute.
		 * @type string $aria_current The aria-current attribute.
		 * }
		 *
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


/**
 * Замена галлереи  гутенберга
 */


add_filter( 'render_block', 'wrap_my_image_block', 1, 2 );


function wrap_my_image_block( $block_content, $block ) {


	$args = [
		'core/gallery',
		'core/table',
		#'block-lab/separator'
	];

	#wpp_console($block_content);

	if ( in_array( $block['blockName'], $args ) ) {


		if ( 'core/table' === $block['blockName'] ) {
			if ( is_singular( [ 'sale_car', 'project' ] ) ) {
				$args     = [ 'html' => $block['innerHTML'] ];
				$template = 'templates/gutenberg-castom/featueres-accordion';
			} else {
				$template = $args = false;
			}
		} elseif ( 'core/gallery' === $block['blockName'] ) {
			$args     = [ 'imgs' => $block['attrs']['ids'] ];
			$template = 'templates/media/gutenberg-gallery-slider';
		}


	}

	if ( ! empty( $template ) ) :
		ob_start();
		wpp_get_template_part( $template, $args );
		$block_content = ob_get_clean();
	endif;

	return $block_content;
}

function ghfg() {
	wp_add_dashboard_widget( 'wpp_dashboard_status', __( 'Wpp Status', 'woocommerce' ), 'wpp_status_widget' );
}

add_action( 'dashboard_widget', 'ghfg' );

function wpp_status_widget() {
	echo 'fffffff';
}