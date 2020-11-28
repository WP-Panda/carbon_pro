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
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Register Form object or false.
	 */
	protected function get_form( $args ) {
		$args = shortcode_atts( array(
			// Meta Box ID.
			'id'              => '',

			// User fields.
			'user_id'         => 0,

			'redirect'        => '',
			'form_id'         => 'register-form',

			// Appearance options.
			'label_username'  => __( 'Username', 'mb-user-profile' ),
			'label_email'     => __( 'Email', 'mb-user-profile' ),
			'label_password'  => __( 'Password', 'mb-user-profile' ),
			'label_password2' => __( 'Confirm Password', 'mb-user-profile' ),
			'label_submit'    => __( 'Register', 'mb-user-profile' ),

			'id_username'     => 'user_login',
			'id_email'        => 'user_email',
			'id_password'     => 'user_pass',
			'id_password2'    => 'user_pass2',
			'id_submit'       => 'submit',

			'confirmation'    => __( 'Your account has been created successfully.', 'mb-user-profile' ),
		), $args );

		// Compatible with old shortcode attributes.
		$this->change_array_key( $args, 'submit_button', 'label_submit' );

		// Apply changes to appearance.
		$base_meta_box = rwmb_get_registry( 'meta_box' )->get( 'rwmb-user-register' );
		if ( isset( $base_meta_box->meta_box['fields']['username'] ) ) {
			$base_meta_box->meta_box['fields']['username']['name'] = $args['label_username'];
			$base_meta_box->meta_box['fields']['username']['id'] = $args['id_username'];
		}
		if ( isset( $base_meta_box->meta_box['fields']['email'] ) ) {
			$base_meta_box->meta_box['fields']['email']['name'] = $args['label_email'];
			$base_meta_box->meta_box['fields']['email']['id'] = $args['id_email'];
		}
		if ( isset( $base_meta_box->meta_box['fields']['password'] ) ) {
			$base_meta_box->meta_box['fields']['password']['name'] = $args['label_password'];
			$base_meta_box->meta_box['fields']['password']['id'] = $args['id_password'];
		}
		if ( isset( $base_meta_box->meta_box['fields']['password2'] ) ) {
			$base_meta_box->meta_box['fields']['password2']['name'] = $args['label_password2'];
			$base_meta_box->meta_box['fields']['password2']['id'] = $args['id_password2'];
		}

		$meta_boxes   = array();
		$meta_box_ids = array_filter( array_map( 'trim', explode( ',', $args['id'] . ',' ) ) );

		foreach ( $meta_box_ids as $k => $meta_box_id ) {
			$meta_box = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
			if ( ! $meta_box ) {
				unset( $meta_box_ids[$k] );
				continue;
			}
			$meta_boxes[] = $meta_box;
		}
		array_unshift( $meta_boxes, $base_meta_box );

		$args['id'] = implode( ',', $meta_box_ids );

		$user = new MB_User_Profile_User( $args['user_id'], $args );

		return new MB_User_Profile_Form_Register( $meta_boxes, $user, $args );
	}
}
