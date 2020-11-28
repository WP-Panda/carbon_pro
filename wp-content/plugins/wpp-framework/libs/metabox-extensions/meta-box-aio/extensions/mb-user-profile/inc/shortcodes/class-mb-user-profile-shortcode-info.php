<?php
/**
 * Profile form shortcode.
 *
 * @package    Meta Box
 * @subpackage MB Frontend Form Profile
 */

/**
 * Shortcode class.
 */
class MB_User_Profile_Shortcode_Info extends MB_User_Profile_Shortcode {
	/**
	 * Shortcode type.
	 *
	 * @var string
	 */
	protected $type = 'info';

	/**
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Info Form object or false.
	 */
	protected function get_form( $args ) {
		$args = shortcode_atts( array(
			// Meta Box ID.
			'id'              => '',

			// User fields.
			'user_id'         => get_current_user_id(),

			'redirect'        => '',
			'form_id'         => 'profile-form',

			// Appearance options.
			'label_password'  => __( 'New Password', 'mb-user-profile' ),
			'label_password2' => __( 'Confirm Password', 'mb-user-profile' ),
			'label_submit'    => __( 'Submit', 'mb-user-profile' ),

			'id_password'     => 'user_pass',
			'id_password2'    => 'user_pass2',
			'id_submit'       => 'submit',

			'confirmation'    => __( 'Your information has been successfully submitted. Thank you.', 'mb-user-profile' ),
		), $args );

		// Compatible with old shortcode attributes.
		$this->change_array_key( $args, 'submit_button', 'label_submit' );

		// Apply changes to appearance.
		$base_meta_box = rwmb_get_registry( 'meta_box' )->get( 'rwmb-user-info' );
		if ( $base_meta_box ) {
			if ( isset( $base_meta_box->meta_box['fields']['password'] ) ) {
				$base_meta_box->meta_box['fields']['password']['name'] = $args['label_password'];
				$base_meta_box->meta_box['fields']['password']['id'] = $args['id_password'];
			}
			if ( isset( $base_meta_box->meta_box['fields']['password2'] ) ) {
				$base_meta_box->meta_box['fields']['password2']['name'] = $args['label_password2'];
				$base_meta_box->meta_box['fields']['password2']['id'] = $args['id_password2'];
			}
		}

		$meta_boxes = [$base_meta_box];
		$meta_box_ids = array_filter( array_map( 'trim', explode( ',', $args['id'] . ',' ) ) );

		foreach ( $meta_box_ids as $k => $meta_box_id ) {
			if ( 'rwmb-user-login' === $meta_box_id || 'rwmb-user-register' === $meta_box_id ) {
				unset( $meta_box_ids[$k] );
				continue;
			}
			$meta_box = rwmb_get_registry( 'meta_box' )->get( $meta_box_id );
			if ( ! $meta_box ) {
				unset( $meta_box_ids[$k] );
				continue;
			}
			$meta_box->set_object_id( $args['user_id'] );
			$meta_boxes[] = $meta_box;
		}
		if ( ! $meta_boxes ) {
			return false;
		}

		$args['id'] = implode( ',', $meta_box_ids );

		$user = new MB_User_Profile_User( $args['user_id'], $args );

		return new MB_User_Profile_Form_Info( $meta_boxes, $user, $args );
	}
}
