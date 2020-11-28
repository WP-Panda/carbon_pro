<?php
/**
 * Get field edit content
 *
 * @param  string $type Input Type
 *
 * @return string html
 */
function mbb_get_field_edit_content( $type ) {
	$type = str_replace( '_', '-', $type );

	if ( file_exists( MBB_FIELDS_DIR . "{$type}.php" ) ) {
		require_once MBB_FIELDS_DIR . "{$type}.php";
	}
}

/**
 * Get content from 'attribute/$attribute.html'
 *
 * @param  string $attribute Attribute name
 *
 * @return string html
 */
function mbb_get_attribute_content( $attribute, $replace = '', $label = '' ) {
	$attribute = str_replace( '_', '-', $attribute );

	$path = MBB_INC_DIR . "attributes/{$attribute}.html";

	$handle = fopen( $path, 'r' );

	$content = fread( $handle, filesize( $path ) );

	$content = str_replace( '##key_value##', $replace, $content );

	$labels = Meta_Box_Attribute::$labels;

	$label_type = array_key_exists( $replace, $labels ) ? $labels[ $replace ] : str_title( $replace );

	$label = ! empty( $label ) ? $label : $label_type;

	$content = str_replace( '##key_value_label##', $label_type, $content );

	$content = str_replace( '##key_label##', $label, $content );

	fclose( $handle );

	return $content;
}

/**
 * Get post type for displaying on Meta Box Settings
 *
 * @return array Post types
 */
function mbb_get_post_types() {
	$unsupported = array(
		// WordPress built-in post types.
		'revision',
		'nav_menu_item',
		'customize_changeset',
		'oembed_cache',
		'custom_css',
		'attachment',
		'user_request',

		// Meta Box post types.
		'meta-box',
		'mb-post-type',
		'mb-taxonomy',
	);
	$post_types  = get_post_types( '', 'name' );
	$post_types  = array_diff_key( $post_types, array_flip( $unsupported ) );
	foreach ( $post_types as &$post_type ) {
		$post_type = array(
			'slug' => $post_type->name,
			'name' => $post_type->labels->singular_name,
		);
	}
	return $post_types;
}

/**
 * Get taxonomies for displaying dropdown for taxonomy and taxonomy_advanced fields
 *
 * @return array Taxonomies
 */
function mbb_get_taxonomies() {
	$taxonomies      = get_taxonomies( '', 'name' );
	$taxonomy_object = array();
	$unsupported     = array( 'link_category', 'nav_menu', 'post_format' );

	foreach ( $unsupported as $taxonomy ) {
		unset( $taxonomies[ $taxonomy ] );
	}

	$x = 0;
	foreach ( $taxonomies as $taxonomy ) {
		$x ++;
		$taxonomy_object[ $x ]['slug'] = $taxonomy->name;
		$taxonomy_object[ $x ]['name'] = $taxonomy->labels->singular_name;
	}

	return $taxonomy_object;
}

function mbb_get_page_templates() {
	$templates = get_page_templates();

	return $templates;
}

function mbb_get_post_formats() {
	if ( current_theme_supports( 'post-formats' ) ) {
		$post_formats = get_theme_support( 'post-formats' );

		if ( is_array( $post_formats[0] ) ) {
			return $post_formats[0];
		}

		return $post_formats;
	}
}

function mbb_get_setting_pages() {
	$setting_pages = apply_filters( 'mb_settings_pages', array() );
	$setting_pages = wp_list_pluck( $setting_pages, 'id' );
	return array_unique( $setting_pages );
}


function mbb_get_categories() {
	$categories = get_categories();

	$cats = array();

	foreach ( $categories as $cat ) {
		$cats[ $cat->term_id ] = $cat->name;
	}

	return $cats;
}

function mbb_get_terms() {
	$taxonomies = wp_list_pluck( mbb_get_taxonomies(), 'slug' );
	$taxes      = array();

	foreach ( $taxonomies as $tax ) {
		if ( $tax === 'post_format' ) {
			continue;
		}

		$taxes[] = $tax;
	}

	$items = get_terms( $taxes );

	$hierachy = array();

	foreach ( $items as $item ) {
		$hierachy[ $item->taxonomy ][ $item->term_id ] = $item->name;
	}

	return $hierachy;
}

function mbb_get_all_taxonomy_term() {
	$term          = mbb_get_taxonomies();
	$list_category = array();
	foreach ( mbb_get_taxonomies() as $tax => $value ) :
		$categories = get_categories(
			array(
				'taxonomy'   => $value['slug'],
				'hide_empty' => 0,
			)
		);
		foreach ( $categories as $key2 => $value2 ) {
			// code...
			$list_category[ $value['slug'] ][ $value2->term_id ]['id']   = $value2->term_id;
			$list_category[ $value['slug'] ][ $value2->term_id ]['name'] = $value2->name;
		}
	endforeach;

	return $list_category;
}

/**
 * Array of Menu item on builder GUI
 *
 * Todo: Remove it and use default field type and field value
 *
 * @return array Menu structure
 */
function mbb_get_builder_menu() {
	$menu = array(

		'Input Fields'       => array(
			'text'      => 'Text',
			'number'    => 'Number',
			'url'       => 'URL',
			'email'     => 'Email',
			'range'     => 'Range',
			'text_list' => 'Text List',
		),

		'Basic Fields'       => array(
			'checkbox'        => 'Checkbox',
			'checkbox_list'   => 'Checkbox List',
			'button'          => 'Button',
			'button_group'    => 'Button Group',
			'password'        => 'Password',
			'radio'           => 'Radio',
			'select'          => 'Select',
			'select_advanced' => 'Select Adv.',
			'textarea'        => 'Textarea',
			'hidden'          => 'Hidden',
			'image_select'    => 'Image Select',
		),

		'Advanced Fields'    => array(
			'color'         => 'Color Picker',
			'oembed'        => 'OEmbed',
			'slider'        => 'Slider',
			'wysiwyg'       => 'WYSIWYG',
			'autocomplete'  => 'Autocomplete',
			'fieldset_text' => 'Fieldset Text',
			'background'    => 'Background',
			'switch'        => 'Switch',
			'map'           => 'Map',
			'heading'       => 'Heading',
			'divider'       => 'Divider',
			'custom_html'   => 'Custom HTML',
			'osm'           => 'Osm',
		),

		'Date & Time Fields' => array(
			'date'     => 'Date',
			'datetime' => 'Date Time',
			'time'     => 'Time',
		),

		'WordPress Fields'   => array(
			'post'              => 'Post',
			'taxonomy'          => 'Taxonomy',
			'taxonomy_advanced' => 'Taxonomy Adv.',
			'user'              => 'User',
			'sidebar'           => 'Sidebar',
		),

		'Media Fields'       => array(
			'file'           => 'HTML File',
			'file_input'     => 'File Input',
			'file_advanced'  => 'File Advanced',
			'image_advanced' => 'Image Adv.',
			'single_image'   => 'Single Image',
			'image'          => 'HTML Image',
			'video'          => 'Video',
			'file_upload'    => 'File Upload',
			'image_upload'   => 'Image Upload',
		),

		'Specials'           => array(
			'tab'   => 'Tab',
			'group' => 'Group',
		),
	);

	return $menu;
}

if ( ! function_exists( 'str_title' ) ) {
	/**
	 * Convert snake case or normal case to title case
	 *
	 * @param  String $str String to be convert
	 *
	 * @return String As Title
	 */
	function str_title( $str ) {
		$str = str_replace( '_', ' ', $str );

		return ucwords( $str );
	}
}

if ( ! function_exists( 'array_unflatten' ) ) {
	/**
	 * Convert flatten collection (with dot notation) to multiple dimmensionals array
	 *
	 * @param  Collection $collection Collection to be flatten
	 *
	 * @return Array
	 */
	function array_unflatten( $collection ) {
		$collection = (array) $collection;

		$output = array();

		foreach ( $collection as $key => $value ) {
			array_set( $output, $key, $value );

			if ( is_array( $value ) && ! strpos( $key, '.' ) ) {
				$nested = array_unflatten( $value );

				$output[ $key ] = $nested;
			}
		}

		return $output;
	}
}


if ( ! function_exists( 'array_set' ) ) {
	function array_set( &$array, $key, $value ) {
		if ( is_null( $key ) ) {
			return $array = $value;
		}

		$keys = explode( '.', $key );

		while ( count( $keys ) > 1 ) {
			$key = array_shift( $keys );

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
				$array[ $key ] = array();
			}

			$array =& $array[ $key ];
		}

		$array[ array_shift( $keys ) ] = $value;

		return $array;
	}
}

if ( ! function_exists( 'ends_with' ) ) {
	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param  string       $haystack
	 * @param  string|array $needles
	 *
	 * @return bool
	 */
	function ends_with( $haystack, $needles ) {
		foreach ( (array) $needles as $needle ) {
			if ( (string) $needle === substr( $haystack, - strlen( $needle ) ) ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Get All WP Dashicon for displaying in Tab or Tooltip
 *
 * @return Array List of WP Dashicon
 */
function mbb_get_dashicons() {
	return array(
		'dashicons-admin-appearance',
		'dashicons-admin-collapse',
		'dashicons-admin-comments',
		'dashicons-admin-generic',
		'dashicons-admin-home',
		'dashicons-admin-links',
		'dashicons-admin-media',
		'dashicons-admin-network',
		'dashicons-admin-page',
		'dashicons-admin-plugins',
		'dashicons-admin-post',
		'dashicons-admin-settings',
		'dashicons-admin-site',
		'dashicons-admin-tools',
		'dashicons-admin-users',
		'dashicons-album',
		'dashicons-align-center',
		'dashicons-align-left',
		'dashicons-align-none',
		'dashicons-align-right',
		'dashicons-analytics',
		'dashicons-archive',
		'dashicons-arrow-down-alt2',
		'dashicons-arrow-down-alt',
		'dashicons-arrow-down',
		'dashicons-arrow-left-alt2',
		'dashicons-arrow-left-alt',
		'dashicons-arrow-left',
		'dashicons-arrow-right-alt2',
		'dashicons-arrow-right-alt',
		'dashicons-arrow-right',
		'dashicons-arrow-up-alt2',
		'dashicons-arrow-up-alt',
		'dashicons-arrow-up',
		'dashicons-art',
		'dashicons-awards',
		'dashicons-backup',
		'dashicons-book-alt',
		'dashicons-book',
		'dashicons-building',
		'dashicons-businessman',
		'dashicons-calendar-alt',
		'dashicons-calendar',
		'dashicons-camera',
		'dashicons-carrot',
		'dashicons-cart',
		'dashicons-category',
		'dashicons-chart-area',
		'dashicons-chart-bar',
		'dashicons-chart-line',
		'dashicons-chart-pie',
		'dashicons-clipboard',
		'dashicons-clock',
		'dashicons-cloud',
		'dashicons-controls-back',
		'dashicons-controls-forward',
		'dashicons-controls-pause',
		'dashicons-controls-play',
		'dashicons-controls-repeat',
		'dashicons-controls-skipback',
		'dashicons-controls-skipforward',
		'dashicons-controls-volumeoff',
		'dashicons-controls-volumeon',
		'dashicons-dashboard',
		'dashicons-desktop',
		'dashicons-dismiss',
		'dashicons-download',
		'dashicons-editor-aligncenter',
		'dashicons-editor-alignleft',
		'dashicons-editor-alignright',
		'dashicons-editor-bold',
		'dashicons-editor-break',
		'dashicons-editor-code',
		'dashicons-editor-contract',
		'dashicons-editor-customchar',
		'dashicons-editor-distractionfree',
		'dashicons-editor-expand',
		'dashicons-editor-help',
		'dashicons-editor-indent',
		'dashicons-editor-insertmore',
		'dashicons-editor-italic',
		'dashicons-editor-justify',
		'dashicons-editor-kitchensink',
		'dashicons-editor-ol',
		'dashicons-editor-outdent',
		'dashicons-editor-paragraph',
		'dashicons-editor-paste-text',
		'dashicons-editor-paste-word',
		'dashicons-editor-quote',
		'dashicons-editor-removeformatting',
		'dashicons-editor-rtl',
		'dashicons-editor-spellcheck',
		'dashicons-editor-strikethrough',
		'dashicons-editor-textcolor',
		'dashicons-editor-ul',
		'dashicons-editor-underline',
		'dashicons-editor-unlink',
		'dashicons-editor-video',
		'dashicons-edit',
		'dashicons-email-alt',
		'dashicons-email',
		'dashicons-excerpt-view',
		'dashicons-exerpt-view',
		'dashicons-external',
		'dashicons-facebook-alt',
		'dashicons-facebook',
		'dashicons-feedback',
		'dashicons-flag',
		'dashicons-format-aside',
		'dashicons-format-audio',
		'dashicons-format-chat',
		'dashicons-format-gallery',
		'dashicons-format-image',
		'dashicons-format-links',
		'dashicons-format-quote',
		'dashicons-format-standard',
		'dashicons-format-status',
		'dashicons-format-video',
		'dashicons-forms',
		'dashicons-googleplus',
		'dashicons-grid-view',
		'dashicons-groups',
		'dashicons-hammer',
		'dashicons-heart',
		'dashicons-id-alt',
		'dashicons-id',
		'dashicons-images-alt2',
		'dashicons-images-alt',
		'dashicons-image-crop',
		'dashicons-image-flip-horizontal',
		'dashicons-image-flip-vertical',
		'dashicons-image-rotate-left',
		'dashicons-image-rotate-right',
		'dashicons-index-card',
		'dashicons-info',
		'dashicons-leftright',
		'dashicons-lightbulb',
		'dashicons-list-view',
		'dashicons-location-alt',
		'dashicons-location',
		'dashicons-lock',
		'dashicons-marker',
		'dashicons-media-archive',
		'dashicons-media-audio',
		'dashicons-media-code',
		'dashicons-media-default',
		'dashicons-media-document',
		'dashicons-media-interactive',
		'dashicons-media-spreadsheet',
		'dashicons-media-text',
		'dashicons-media-video',
		'dashicons-megaphone',
		'dashicons-menu',
		'dashicons-microphone',
		'dashicons-migrate',
		'dashicons-minus',
		'dashicons-money',
		'dashicons-nametag',
		'dashicons-networking',
		'dashicons-no-alt',
		'dashicons-no',
		'dashicons-palmtree',
		'dashicons-performance',
		'dashicons-phone',
		'dashicons-playlist-audio',
		'dashicons-playlist-video',
		'dashicons-plus-alt',
		'dashicons-plus',
		'dashicons-portfolio',
		'dashicons-post-status',
		'dashicons-post-trash',
		'dashicons-pressthis',
		'dashicons-products',
		'dashicons-randomize',
		'dashicons-redo',
		'dashicons-rss',
		'dashicons-schedule',
		'dashicons-screenoptions',
		'dashicons-search',
		'dashicons-share1',
		'dashicons-share-alt2',
		'dashicons-share-alt',
		'dashicons-share',
		'dashicons-shield-alt',
		'dashicons-shield',
		'dashicons-slides',
		'dashicons-smartphone',
		'dashicons-smiley',
		'dashicons-sort',
		'dashicons-sos',
		'dashicons-star-empty',
		'dashicons-star-filled',
		'dashicons-star-half',
		'dashicons-store',
		'dashicons-tablet',
		'dashicons-tagcloud',
		'dashicons-tag',
		'dashicons-testimonial',
		'dashicons-text',
		'dashicons-tickets-alt',
		'dashicons-tickets',
		'dashicons-translation',
		'dashicons-trash',
		'dashicons-twitter',
		'dashicons-undo',
		'dashicons-universal-access-alt',
		'dashicons-universal-access',
		'dashicons-update',
		'dashicons-upload',
		'dashicons-vault',
		'dashicons-video-alt2',
		'dashicons-video-alt3',
		'dashicons-video-alt',
		'dashicons-visibility',
		'dashicons-welcome-add-page',
		'dashicons-welcome-comments',
		'dashicons-welcome-edit-page',
		'dashicons-welcome-learn-more',
		'dashicons-welcome-view-site',
		'dashicons-welcome-widgets-menus',
		'dashicons-welcome-write-blog',
		'dashicons-wordpress-alt',
		'dashicons-wordpress',
	);
}

function mbb_get_current_tab() {
	return filter_input( INPUT_GET, 'tab' ) ?: 'fields';
}

function mbb_is_extension_active( $extension ) {
	$functions = array(
		'mb-term-meta'               => 'mb_term_meta_load',
		'mb-settings-page'           => 'mb_settings_page_load',
		'mb-user-meta'               => 'mb_user_meta_load',
		'mb-comment-meta'            => 'mb_comment_meta_load',
		'mb-custom-table'            => 'mb_custom_table_load',
		'meta-box-conditional-logic' => 'mb_conditional_logic_load',
	);

	return isset( $functions[ $extension ] ) && function_exists( $functions[ $extension ] );
}
