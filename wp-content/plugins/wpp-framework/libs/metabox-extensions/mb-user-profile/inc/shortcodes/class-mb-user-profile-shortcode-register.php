<?php
/**
 * Register form shortcode.
 *
 * @package    Meta Box
 * @subpackage MB User Form Register
 */

/**
 * Shortcode class.
 */
class MB_User_Profile_Shortcode_Register extends MB_User_Profile_Shortcode {
	/**
	 * Shortcode type.
	 *
	 * @var string
	 */
	protected $type = 'register';

	/**
	 * Handle the form submit.
	 */
	public function process() {
		// @codingStandardsIgnoreLine
		$config = isset( $_POST['rwmb_form_config'] ) ? $_POST['rwmb_form_config'] : '';
		if ( empty( $config ) ) {
			return;
		}
		$form = $this->get_form( $config );
		if ( false === $form ) {
			return;
		}

		// Make sure to include the WordPress media uploader functions to process uploaded files.
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		unset( $_COOKIE['mb_user_profile_error'] );
		$config_submit = 'error';
		$result        = $form->process();

		if ( $result['error'] ) {
			setcookie( 'mb_user_profile_error', $result['error'], strtotime( '+1 days' ), '/' );
		} else {
			$array_config  = array_filter( explode( ',', $config['id'] . ',' ) );
			$config_submit = implode( ',', $array_config );
		}

		$redirect = add_query_arg( 'rwmb-form-submitted', $config_submit );
		$redirect = apply_filters( 'rwmb_profile_redirect', $redirect, $config );
		wp_safe_redirect( $redirect );
		die;
	}

	/**
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Register Form object or false.
	 */
	protected function get_form( $args ) {
		$args = shortcode_atts( array(
			// Meta Box ID.
			'id'            => '',

			// User fields.
			'user_id'       => 0,

			// Appearance options.
			'submit_button' => __( 'Submit', 'mb-user-profile' ),
			'confirmation'  => __( 'Your account has been created successfully.', 'mb-user-profile' ),
		), $args );

		$meta_boxes   = array();
		$meta_box_ids = array_filter( array_map( 'trim', explode( ',', $args['id'] . ',' ) ) );
		array_unshift( $meta_box_ids, 'rwmb-user-register' );

		foreach ( $meta_box_ids as $meta_box_id ) {
			if ( 'rwmb-user-login' === $meta_box_id || 'rwmb-user-info' === $meta_box_id ) {
				continue;
			}
			$meta_boxes[] = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
		}
		$meta_boxes = array_filter( $meta_boxes );
		if ( ! $meta_boxes ) {
			return false;
		}

		$meta_box_ids = array();
		foreach ( $meta_boxes as $meta_box ) {
			$meta_box_ids[] = $meta_box->id;
		}

		$args['id'] = implode( ',', $meta_box_ids );

		$user = new MB_User_Profile_User( $args['user_id'], $args );

		return new MB_User_Profile_Form_Register( $meta_boxes, $user, $args );
	}
}
