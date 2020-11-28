<?php
	/**
	 * Custom nav walker
	 *
	 * Custom nav walker to assign icons to menu items.
	 */
	class Font_Awesome_Icon_Walker extends Walker_Nav_Menu
	{
		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			/** Remove icon class from list item */
			if ( $icon_class = preg_grep( '/fa-/', $classes ) )
				$classes = array_diff( $classes, $icon_class );
			
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$output .= $indent . '<li' . $id . $value . $class_names .'>';
			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			/** Add an icon if assigned. See check above */
			if ( $icon_class )
				$item_output .= '<i class="fa ' . current( $icon_class ) . '"></i>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	
	
	
	/**
	 * Nav menu walker
	 */
	class Bridge_Walker_Nav_Menu extends Walker_Nav_Menu {
		
		/**
		 * Prepare item
		 *
		 * @param  object $item  Menu Item.
		 * @param  array  $args  Arguments passed to walk().
		 * @param  int    $depth Item's depth.
		 *
		 * @return array
		 */
		protected function prepare_item( $item, $args, $depth ) {
			$title = apply_filters( 'the_title', $item->title, $item->ID );
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
			$classes = apply_filters( 'nav_menu_css_class', array_filter( $item->classes ), $item, $args, $depth );
			$thumb = get_post_meta( $item->ID, '_img_item_url', true );
			$thumb2 = get_post_meta( $item->ID, '_img_item_url2', true );
			
			return array (
				'id'          => absint( $item->ID ),
				'order'       => (int) $item->menu_order,
				'parent'      => absint( $item->menu_item_parent ),
				'title'       => $title,
				'url'         => $item->url,
				'attr'        => $item->attr_title,
				'target'      => $item->target,
				'classes'     => $classes,
				'xfn'         => $item->xfn,
				'description' => $item->description,
				'object_id'   => absint( $item->object_id ),
				'object'      => $item->object,
				'type'        => $item->type,
				'type_label'  => $item->type_label,
				'img'         => $thumb,
				'img2'         => $thumb2,
				'el_type'     => $item->top_menu_view,
				'children'    => array (),
			);
			
		}
		
		
		/**
		 * Traverse elements to create list from elements.
		 *
		 * This method should not be called directly, use the walk() method instead.
		 *
		 * @param object $element           Data object.
		 * @param array  $children_elements List of elements to continue traversing.
		 * @param int    $max_depth         Max depth to traverse.
		 * @param int    $depth             Depth of current element.
		 * @param array  $args              An array of arguments.
		 * @param array  $output            Passed by reference. Used to append additional content.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}
			
			if ( ! is_array( $output ) ) {
				$output = array ();
			}
			
			$id_field = $this->db_fields[ 'id' ];
			$id = $element->$id_field;
			$item = $this->prepare_item( $element, $args, $depth );
			
			if ( ! empty( $children_elements[ $id ] ) ) {
				foreach ( $children_elements[ $id ] as $child ) {
					$this->display_element(
						$child, $children_elements, 1, ( $depth + 1 ), $args, $item[ 'children' ]
					);
				}
				
				unset( $children_elements[ $id ] );
			}
			
			$output[] = $item;
		}
	}