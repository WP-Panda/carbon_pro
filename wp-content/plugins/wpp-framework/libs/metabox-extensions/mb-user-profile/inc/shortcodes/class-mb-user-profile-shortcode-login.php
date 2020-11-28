<?php
/**
 * Login form shortcode.
 *
 * @package    Meta Box
 * @subpackage MB User Form Login
 */

/**
 * Shortcode class.
 */
class MB_User_Profile_Shortcode_Login extends MB_User_Profile_Shortcode {
	/**
	 * Shortcode type.
	 *
	 * @var string
	 */
	protected $type = 'login';

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
		$result = $this->check_login( $_POST );

		if ( is_wp_error( $result ) ) {
			setcookie( 'mb_user_profile_error', esc_attr( $result->get_error_message() ), strtotime( '+1 days' ), '/' );
		}

		$username = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
		$redirect = add_query_arg( 'rwmb-form-submitted', $username );
		$redirect = apply_filters( 'rwmb_profile_redirect', $redirect, $config );
		wp_safe_redirect( $redirect );
		die;
	}

	/**
	 * Handle the form login.
	 *
	 * @param array $data Custom parameters.
	 *
	 * @return string|int Error message or user ID.
	 */
	public function check_login( $data ) {
		$username    = isset( $data['user_login'] ) ? $data['user_login'] : '';
		$password    = isset( $data['user_pass'] ) ? $data['user_pass'] : '';
		$remember    = ! empty( $data['rwmb_rememberme'] );
		$credentials = array(
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => $remember,
		);

		return wp_signon( $credentials, true );
	}

	/**
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Login Form object or false.
	 */
	protected function get_form( $args ) {
		$args = shortcode_atts( array(
			// User fields.
			'user_id'       => 0,

			// Appearance options.
			'submit_button' => __( 'Submit', 'mb-user-profile' ),
			'remember'      => __( 'Remember', 'mb-user-profile' ),
			'lost_pass'     => __( 'Lost Password ?', 'mb-user-profile' ),
			'confirmation'  => __( 'You are now logged in.', 'mb-user-profile' ),
		), $args );

		$meta_boxes   = array();
		$meta_box_id  = 'wpp-user-login';
		$meta_boxes[] = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
		$meta_boxes   = array_filter( $meta_boxes );
		if ( ! $meta_boxes ) {
			return false;
		}

		$user = new MB_User_Profile_User( $args['user_id'], $args );

		return new MB_User_Profile_Form_Login( $meta_boxes, $user, $args );
	}
}
