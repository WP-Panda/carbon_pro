<?php
	/**
	 * Created by PhpStorm.
	 * User: WP_Panda
	 * Date: 28.07.2019
	 * Time: 9:25
	 */



	/**
	 * Adds a new item into the Bulk Actions dropdown.
	 */
	function wpp_variants_bulk_actions( $bulk_actions ) {

		$bulk_actions['create_variants'] = __( 'Create Variants', 'domain' );

		return $bulk_actions;


	}
	add_filter( 'bulk_actions-edit-product', 'wpp_variants_bulk_actions' );

	/**
	 * Handles the bulk action.
	 */
	function wpp_variants_bulk_action_handler( $redirect_to, $action, $post_ids ) {

		if ( $action !== 'create_variants' ) {
			return $redirect_to;
		}

		foreach ( $post_ids as $post_id ) {
			update_post_opt($post_id);
		}

		$redirect_to = add_query_arg( 'wpp_cr_variants', count( $post_ids ), $redirect_to );

		return $redirect_to;

	}
	add_filter( 'handle_bulk_actions-edit-product', 'wpp_variants_bulk_action_handler', 10, 3 );

	/**
	 * Shows a notice in the admin once the bulk action is completed.
	 */
	function wpp_variants_bulk_action_admin_notice() {
		if ( ! empty( $_REQUEST['wpp_cr_variants'] ) ) {
			$drafts_count = intval( $_REQUEST['wpp_cr_variants'] );

			printf(
				'<div id="message" class="updated fade">' .
				_n( 'Creates variants for %s post.', 'Creates variants for %s posts.', $drafts_count, 'domain' )
				. '</div>',
				$drafts_count
			);
		}
	}
	add_action( 'admin_notices', 'wpp_variants_bulk_action_admin_notice' );
