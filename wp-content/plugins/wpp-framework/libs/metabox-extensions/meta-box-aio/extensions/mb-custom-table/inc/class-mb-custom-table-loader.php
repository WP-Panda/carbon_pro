<?php
/**
 * Loader class
 *
 * @package    Meta Box
 * @subpackage MB Custom Table
 */

/**
 * Class MB_Custom_Table_Loader
 */
class MB_Custom_Table_Loader {

	/**
	 * Class initialize.
	 */
	public function init() {
		add_filter( 'rwmb_get_storage', array( $this, 'filter_storage' ), 10, 3 );
		add_action( 'rwmb_after_save_post', array( $this, 'update_from_cache' ) );
		add_action( 'delete_post', array( $this, 'delete_object_data' ) );
		add_action( 'delete_term', array( $this, 'delete_object_data' ) );
		add_action( 'deleted_user', array( $this, 'delete_object_data' ) );
	}

	/**
	 * Filter storage object.
	 *
	 * @param RWMB_Table_Storage $storage     Custom table storage object.
	 * @param string             $object_type Object type.
	 * @param RW_Meta_Box        $meta_box    Meta box object.
	 *
	 * @return mixed
	 */
	public function filter_storage( $storage, $object_type, $meta_box ) {
		if ( $meta_box && $this->is_custom_table( $meta_box ) ) {
			$storage = new RWMB_Table_Storage();
			$storage->set_table( $meta_box->table );
		}

		return $storage;
	}

	/**
	 * Update data from cache.
	 *
	 * @param int $object_id Object ID.
	 */
	public function update_from_cache( $object_id ) {
		$object_type = $this->get_current_object_type();
		$meta_boxes  = rwmb_get_registry( 'meta_box' )->get_by( array(
			'storage_type' => 'custom_table',
			'object_type'  => $object_type,
		) );

		$type = '';
		if ( 'post' === $object_type ) {
			$type = get_post_type( $object_id );
		}
		if ( 'term' === $object_type ) {
			$term = get_term( $object_id );
			$type = is_array( $term ) ? $term->taxonomy : '';
		}
		foreach ( $meta_boxes as $meta_box ) {
			if ( 'post' === $object_type && ! in_array( $type, $meta_box->post_types, true ) ) {
				continue;
			}
			if ( 'term' === $object_type && ! in_array( $type, $meta_box->taxonomies, true ) ) {
				continue;
			}
			$storage = $meta_box->get_storage();
			$row     = MB_Custom_Table_Cache::get( $object_id, $meta_box->table );
			$row     = array_map( 'maybe_serialize', $row );
			if ( $storage->row_exists( $object_id ) ) {
				$storage->update_row( $object_id, $row );
			} else {
				$storage->insert_row( $object_id, $row );
			}
		}
	}

	/**
	 * Delete object data in cache and in the database.
	 *
	 * @param int $object_id Object ID.
	 */
	public function delete_object_data( $object_id ) {
		$object_type = str_replace( array( 'delete_', 'deleted_' ), '', current_filter() );
		if ( 'post' === $object_type && 'revision' === get_post_type( $object_id ) ) {
			return;
		}
		$meta_boxes  = rwmb_get_registry( 'meta_box' )->get_by( array(
			'object_type'  => $object_type,
			'storage_type' => 'custom_table',
		) );
		foreach ( $meta_boxes as $meta_box ) {
			$storage = $meta_box->get_storage();
			$storage->delete( $object_id ); // Delete from cache.
			$storage->delete_row( $object_id ); // Delete from DB.
		}
	}

	/**
	 * Check if meta box uses custom table.
	 *
	 * @param RW_Meta_Box $meta_box Meta box object.
	 *
	 * @return bool
	 */
	protected function is_custom_table( $meta_box ) {
		return 'custom_table' === $meta_box->storage_type && ! empty( $meta_box->meta_box['table'] );
	}

	/**
	 * Get current object type for the being saved object.
	 *
	 * @return string
	 */
	protected function get_current_object_type() {
		global $wp_current_filter;

		foreach ( $wp_current_filter as $hook ) {
			if ( 'edit_comment' === $hook ) {
				return 'comment';
			}
			if ( 'profile_update' === $hook || 'user_register' === $hook ) {
				return 'user';
			}
			if ( 0 === strpos( $hook, 'edited_' ) || 0 === strpos( $hook, 'created_' ) ) {
				return 'term';
			}
		}
		return 'post';
	}
}
