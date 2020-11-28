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
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Login Form object or false.
	 */
	protected function get_form( $args ) {
		// Compatible with old shortcode attributes.
		$this->change_array_key( $args, 'remember', 'label_remember' );
		$this->change_array_key( $args, 'lost_pass', 'label_lost_password' );
		$this->change_array_key( $args, 'submit_button', 'label_submit' );

		$args = shortcode_atts( array(
			// User fields.
			'user_id'             => 0,

			'redirect'            => '',
			'form_id'             => 'loginform',

			// Appearance options.
			'label_username'      => __( 'Username or Email Address', 'mb-user-profile' ),
			'label_password'      => __( 'Password', 'mb-user-profile' ),
			'label_remember'      => __( 'Remember Me', 'mb-user-profile' ),
			'label_lost_password' => __( 'Lost Password?', 'mb-user-profile' ),
			'label_submit'        => __( 'Log In', 'mb-user-profile' ),

			'id_username'         => 'user_login',
			'id_password'         => 'user_pass',
			'id_remember'         => 'rememberme',
			'id_submit'           => 'submit',

			'remember'            => true,

			'value_username'      => '',
			'value_remember'      => false,

			'confirmation'        => __( 'You are now logged in.', 'mb-user-profile' ),
		), $args );

		// Apply changes to appearance.
		$base_meta_box = rwmb_get_registry( 'meta_box' )->get( 'rwmb-user-login' );
		if ( isset( $base_meta_box->meta_box['fields']['username'] ) ) {
			$base_meta_box->meta_box['fields']['username']['name'] = $args['label_username'];
			$base_meta_box->meta_box['fields']['username']['id'] = $args['id_username'];
		}
		if ( isset( $base_meta_box->meta_box['fields']['password'] ) ) {
			$base_meta_box->meta_box['fields']['password']['name'] = $args['label_password'];
			$base_meta_box->meta_box['fields']['password']['id'] = $args['id_password'];
		}

		$meta_boxes = [$base_meta_box];

		$user = new MB_User_Profile_User( $args['user_id'], $args );

		return new MB_User_Profile_Form_Login( $meta_boxes, $user, $args );
	}
}
