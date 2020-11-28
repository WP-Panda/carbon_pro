<?php
	
	/**
	 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
	 *
	 * Create HTML list of nav menu input items.
	 *
	 * @package WordPress
	 * @since   3.0.0
	 * @uses    Walker_Nav_Menu
	 */
	
	/*function tr_post_types_list() {
		$all_post_types = get_post_types();
		$post_types     = array();

		foreach ( $all_post_types as $post_type ) {
			if ( ! in_array( $post_type, array( 'attachment', 'revision', 'nav_menu_item', 'product_variation', 'shop_order', 'shop_coupon', 'parallax' ) ) ) {
				$post_types[] = $post_type;
			}
		}

		return $post_types;
	}*/
	
	
	if ( ! class_exists( 'Walker_Nav_Menu_Edit' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );
	}
	
	
	class kleo_custom_menu {
		
		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/
		
		function __construct() {
			
			// add custom menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array ( $this, 'kleo_add_custom_nav_fields' ) );
			
			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array ( $this, 'kleo_update_custom_nav_fields' ), 10, 3 );
			
			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array ( $this, 'kleo_edit_walker' ), 10, 2 );
			
			//load extra scripts
			add_action( 'admin_print_scripts', array ( $this, 'sq_load_scripts' ) );
			
			
		} // end constructor
		
		/**
		 * Load necessary JavaScript and CSS files
		 */
		public function sq_load_scripts() {
			
			$screen = get_current_screen();
			
			
		}
		
		
		/**
		 * Add custom fields to $item nav object
		 * in order to be used in custom Walker
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function kleo_add_custom_nav_fields( $menu_item ) {
			
			$menu_item->top_menu_view = get_post_meta( $menu_item->ID, '_top_menu_view', true );
			$menu_item->img_item_tumb = get_post_meta( $menu_item->ID, '_img_item_url', true );
			$menu_item->img_item_tumb2 = get_post_meta( $menu_item->ID, '_img_item_url2', true );
			
			return $menu_item;
			
		}
		
		/**
		 * Save menu custom fields
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function kleo_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
			
			// Check if  icons element is properly sent
			if ( isset( $_REQUEST[ 'top_menu_view' ] ) && is_array( $_REQUEST[ 'top_menu_view' ] ) && isset( $_REQUEST[ 'top_menu_view' ][ $menu_item_db_id ] ) ) {
				$icon_value = $_REQUEST[ 'top_menu_view' ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_top_menu_view', $icon_value );
			} else {
				update_post_meta( $menu_item_db_id, '_top_menu_view', null );
			}
			
			
			if ( isset( $_REQUEST[ 'img_item_tumb' ] ) && is_array( $_REQUEST[ 'img_item_tumb' ] ) && isset( $_REQUEST[ 'img_item_tumb' ][ $menu_item_db_id ] ) ) {
				$icon_value = $_REQUEST[ 'img_item_tumb' ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_img_item_url', esc_url($icon_value) );
			} else {
				update_post_meta( $menu_item_db_id, '_img_item_url', null );
			}
			
			if ( isset( $_REQUEST[ 'img_item_tumb2' ] ) && is_array( $_REQUEST[ 'img_item_tumb2' ] ) && isset( $_REQUEST[ 'img_item_tumb2' ][ $menu_item_db_id ] ) ) {
				$icon_value2 = $_REQUEST[ 'img_item_tumb2' ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_img_item_url2', esc_url($icon_value2) );
			} else {
				update_post_meta( $menu_item_db_id, '_img_item_url2', null );
			}
			
		}
		
		/**
		 * Define new Walker edit
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		function kleo_edit_walker( $walker, $menu_id = null ) {
			
			return 'Walker_Nav_Menu_Edit_Custom';
			
		}
		
	}
	
	if ( ! class_exists( 'Kleo_Walker_Nav_Menu_Edit' ) ) {
		/**
		 *
		 * Create HTML list of nav menu input items.
		 *
		 * @package WordPress
		 * @since   3.0.0
		 * @uses    Walker_Nav_Menu
		 */
		class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu_Edit {
			/**
			 * @see   Walker_Nav_Menu::start_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 */
			function start_lvl( &$output, $depth = 0, $args = array () ) {
			}
			
			/**
			 * @see   Walker_Nav_Menu::end_lvl()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference.
			 */
			function end_lvl( &$output, $depth = 0, $args = array () ) {
			}
			
			/**
			 * @see   Walker::start_el()
			 * @since 3.0.0
			 *
			 * @param string $output Passed by reference. Used to append additional content.
			 * @param object $item   Menu item data object.
			 * @param int    $depth  Depth of menu item. Used for padding.
			 * @param object $args
			 */
			function start_el( &$output, $item, $depth = 0, $args = array (), $id = 0 ) {
				
				parent::start_el( $output, $item, $depth, $args, $id );
				
				$item_id = esc_attr( $item->ID );
				$to_add = '';
				
				$types_options = null;
				$types = array (
					'default'  => 'Default',
					'columns'  => 'Column',
					'thumb'    => 'Thumbinail',
					'item_img' => 'Item With Image'
				);
			
				foreach ( $types as $type_key => $type_val ) {
					$selected_type = $item->top_menu_view == $type_key ? ' selected="selected"' : '';
					$types_options .= '<option value="' . $type_key . '"' . $selected_type . '>' . $type_val . '</option> ';
				}
				
				
				$to_add .= '<p class="menu-item-icons description description-thin">' . '<label for="top_menu_view-' . $item_id . '">' . esc_html__(
						'Select element Type', 'buddyapp'
					) . ' <br><select id="top_menu_view-' . $item_id . '" name="top_menu_view[' . $item_id . ']" style="width:100%;">' . $types_options . '</select>' . '</label>' . '</p>';
				
				
				$to_add .= '<p class="menu-item-url description description-thin">' . '<label for="img_item_tumb-' . $item_id . '">' . esc_html__(
						'Insert Image Url', 'buddyapp'
					) . ' <br><input type="text" id="img_item_tumb-' . $item_id . '" name="img_item_tumb[' . $item_id . ']" style="width:100%;" value="' . $item->img_item_tumb . '"></label></p>';
				
				$to_add .= '<p class="menu-item-url description description-thin">' . '<label for="img_item_tumb2-' . $item_id . '">' . esc_html__(
						'Insert Image 2 Url', 'buddyapp'
					) . ' <br><input type="text" id="img_item_tumb2-' . $item_id . '" name="img_item_tumb2[' . $item_id . ']" style="width:100%;" value="' . $item->img_item_tumb2 . '"></label></p>';
				
				ob_start();
				
				// action for other plugins
				do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args );
				
				$to_add .= ob_get_clean();
				
				$output = str_replace(
					'<label for="edit-menu-item-target-' . $item_id . '">',
					'</p>' . $to_add . '<div class="clear"></div><p class="field-link-target description"><label for="edit-menu-item-target-' . $item_id . '">', $output
				);
				
			}
		}
	}
	// instantiate the custom menu class
	$GLOBALS[ 'kleo_custom_menu' ] = new kleo_custom_menu();