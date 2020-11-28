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
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

	var $list_show = 0;

	/**
	 * @see   Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see   Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
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

	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		global $_wp_nav_menu_max_depth;

		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();

		if ( ! $this->list_show ++ ) {
			$icons_array = cr_get_fa_list();
			echo( '<div id="cr-font-modal-box" class="cr-font-modal-box"><ul>' );
			foreach ( $icons_array as $icon ) {
				echo( '<li><span class="fa ' . $icon . '"></span></li>' );
			}
			echo( '</ul></div>' );
		}

		$item_id      = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) ) {
				$original_title = false;
			}
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title  = $original_object->post_title;
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET[ 'edit-menu-item' ] ) && $item_id == $_GET[ 'edit-menu-item' ] ) ? 'active' : 'inactive' ),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)', 'alfus' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __( '%s (Pending)', 'alfus' ), $item->title );
		}

		$title = empty( $item->label ) ? $title : $item->label;

		?>
	<li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<dl class="menu-item-bar">
			<dt class="menu-item-handle">
				<span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                        echo wp_nonce_url(
		                        add_query_arg(
			                        array(
				                        'action'    => 'move-up-menu-item',
				                        'menu-item' => $item_id,
			                        ),
			                        remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
		                        ),
		                        'move-menu_item'
	                        );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e( 'Move up' ); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                        echo wp_nonce_url(
		                        add_query_arg(
			                        array(
				                        'action'    => 'move-down-menu-item',
				                        'menu-item' => $item_id,
			                        ),
			                        remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
		                        ),
		                        'move-menu_item'
	                        );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e( 'Move down' ); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr( $item_id ); ?>" title="<?php esc_attr_e( 'Edit Menu Item' ); ?>" href="<?php
	                    echo ( isset( $_GET[ 'edit-menu-item' ] ) && $item_id == $_GET[ 'edit-menu-item' ] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item', 'alfus' ); ?></a>
	                </span>
			</dt>
		</dl>

		<div class="menu-item-settings cr-menu-setting-item" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
			<?php if ( 'custom' == $item->type ) : ?>
				<p class="field-url description description-wide">
					<label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
						<?php _e( 'URL', 'alfus' ); ?><br/>
						<input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>"/>
					</label>
				</p>
			<?php endif; ?>
			<p class="description description-thin">
				<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
					<?php _e( 'Title', 'alfus' ); ?><br/>
					<input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>"/>
				</label>
			</p>

			<p class="description description-thin">
				<label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
					<?php _e( 'Title', 'alfus' ); ?><br/>
					<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]"
					       value="<?php echo esc_attr( $item->post_excerpt ); ?>"/>
				</label>
			</p>

			<p class="field-link-target description">
				<label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
					<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->target, '_blank' ); ?> />
					<?php _e( 'Open Link in new TAB', 'alfus' ); ?>
				</label>
			</p>

			<p class="field-css-classes description description-thin">
				<label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
					<?php _e( 'CSS Class (Optional)', 'alfus' ); ?><br/>
					<input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]"
					       value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>"/>
				</label>
			</p>

			<p class="field-xfn description description-thin">
				<label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
					<?php _e( 'Link (XFN)', 'alfus' ); ?><br/>
					<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>"/>
				</label>
			</p>

			<p class="field-description description description-wide">
				<label for="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>">
					<?php _e( 'Description', 'alfus' ); ?><br/>
					<textarea id="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20"
					          name="menu-item-description[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
					<span class="description"><?php _e( 'The description will be displayed in the menu if the current theme supports it.', 'alfus' ); ?></span>
				</label>
			</p>

			<p class="submenu_view description">
				<label for="top_menu_view[<?php echo esc_attr( $item_id ); ?>]"><?php echo __( 'Select element Type', 'alfus' ); ?><br>
					<select name="top_menu_view[<?php echo esc_attr( $item_id ); ?>]" id="top_menu_view[<?php echo esc_attr( $item_id ); ?>]">
						<option value="default" <?php selected( $item->top_menu_view, 'default' ) ?>><?php _e( 'Default', 'alfus' ); ?></option>
						<option value="columns" <?php selected( $item->top_menu_view, 'columns' ) ?>><?php _e( 'Column', 'alfus' ); ?></option>
						<option value="title" <?php selected( $item->top_menu_view, 'title' ) ?>><?php _e( 'Title', 'alfus' ); ?></option>
						<option value="divider" <?php selected( $item->top_menu_view, 'divider' ) ?>><?php _e( 'Divider', 'alfus' ); ?></option>
						<option value="thumb" <?php selected( $item->top_menu_view, 'thumb' ) ?>><?php _e( 'Thumbinail', 'alfus' ); ?></option>
						<option value="thumb_title" <?php selected( $item->top_menu_view, 'thumb_title' ) ?>><?php echo __( 'Thumbinail with title', 'alfus' ); ?></option>
					</select>
				</label>
			</p>

			<p class="column_width description">
				<label for="column_width[<?php echo esc_attr( $item_id ); ?>]"><?php echo __( 'Column Width', 'alfus' ); ?><br>
					<select name="column_width[<?php echo esc_attr( $item_id ); ?>]" id="column_width[<?php echo esc_attr( $item_id ); ?>]">
						<option value="default" <?php selected( $item->column_width, 'default' ) ?>><?php _e( '1/3', 'alfus' ); ?></option>
						<option value="col-sm-12" <?php selected( $item->column_width, 'col-sm-12' ) ?>><?php _e( '1/1', 'alfus' ); ?></option>
						<option value="col-sm-6" <?php selected( $item->column_width, 'col-sm-6' ) ?>><?php _e( '1/2', '' ); ?></option>
						<option value="col-sm-4" <?php selected( $item->column_width, 'col-sm-4' ) ?>><?php _e( '1/3', 'alfus' ); ?></option>
						<option value="col-sm-3" <?php selected( $item->column_width, 'col-sm-3' ) ?>><?php _e( '1/4', 'alfus' ); ?></option>
						<option value="col-sm-8" <?php selected( $item->column_width, 'col-sm-8' ) ?>><?php echo __( '2/3', 'alfus' ); ?></option>
						<option value="col-sm-9" <?php selected( $item->column_width, 'col-sm-9' ) ?>><?php echo __( '3/4', 'alfus' ); ?></option>
					</select>
				</label>
			</p>

			<?php // } ?>


			<!--<p class="item_icon description">
				<label for="item_icon_class"><?php /*_e( 'Select Icon', 'alfus' ); */?><br>
					<input type="text"
					       placeholder="<?php /*echo __( 'Выберите иконку для пункта', 'alfus' ); */?>"
					       name="item_icon_class[<?php /*echo esc_attr( $item_id ); */?>]"
					       id="item_icon_class<?php /*echo esc_attr( $item_id ); */?>"
					       value="<?php /*echo esc_attr( $item->item_icon_class ); */?>"
					       class="item_icon_select"
					/>
				</label>
			</p>-->

			<p class="image_add_row description">
				<label><?php _e( 'Select menu Image', 'alfus' ); ?></label><br>
				<?php
				wp_enqueue_media();
				echo '<a class="button mediamanager" href="#"
							data-choose="' . __( 'Choose an image for the menu item', 'alfus' ) . '"
							data-linked-field="item_thumb_holder' . $item_id . '"
							onclick="showMediaManager(this);"
							style="margin-bottom: 10px;">' . __( 'Select Image', 'alfus' ) . '</a>';
				?>
				<a class="button mediamanager_reset" href="#"><?php _e( 'Delete Image', 'alfus' ); ?></a>
				<input class="item_thumb" id="item_thumb_holder<?php echo esc_attr( $item_id ); ?>" name="item_thumb_holder[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->item_thumb ) ?>" type="hidden"/>

				<div class="item_img"><?php echo ! empty( $item->item_thumb ) ? '<img src="' . $item->item_thumb . '" alt="">' : ''; ?></div>
			</p>

			<?php //} ?>


			<div class="menu-item-actions description-wide submitbox">
				<?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
					<p class="link-to-original">
						<?php printf( __( 'Original: %s', 'alfus' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
					</p>
				<?php endif; ?>
				<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr( $item_id ); ?>" href="<?php
				echo wp_nonce_url(
					add_query_arg(
						array(
							'action'    => 'delete-menu-item',
							'menu-item' => $item_id,
						),
						remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
					),
					'delete-menu_item_' . $item_id
				); ?>"><?php _e( 'Delete', 'alfus' ); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr( $item_id ); ?>" href="<?php echo esc_url( add_query_arg( array(
					'edit-menu-item' => $item_id,
					'cancel'         => time()
				), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
				?>#menu-item-settings-<?php echo esc_attr( $item_id ); ?>"><?php _e( 'Remove', 'alfus' ); ?></a>
			</div>

			<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>"/>
			<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>"/>
			<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>"/>
			<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>"/>
			<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>"/>
			<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>"/>
		</div><!-- .menu-item-settings-->
		<ul class="menu-item-transport"></ul>
		<?php

		$output .= ob_get_clean();

	}

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

}