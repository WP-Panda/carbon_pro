<?php
add_filter( 'rwmb_meta_boxes', 'mb_user_profile_meta_fields' );

/**
 * Add special meta boxes for user forms.
 *
 * @param array $meta_boxes Meta boxes.
 *
 * @return array
 */
function mb_user_profile_meta_fields( $meta_boxes ) {
	$meta_boxes[] = array(
		'id'         => 'rwmb-user-register',
		'title'      => 'register',
		'post_types' => array( 'rwmb-user-profile' ),
		'fields'     => apply_filters( 'rwmb_profile_register_fields', array(
			'username'  => array(
				'name'     => __( 'Username', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_login',
				'type'     => 'text',
				'required' => true,
			),
			'email'     => array(
				'name'     => __( 'Email', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_email',
				'type'     => 'email',
				'required' => true,
			),
			'password'  => array(
				'name'     => __( 'Password', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_pass',
				'type'     => 'password',
				'required' => true,
			),
			'password2' => array(
				'name'     => __( 'Confirm Password', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_pass2',
				'type'     => 'password',
				'required' => true,
			),
		) ),
	);

	$meta_boxes[] = array(
		'id'         => 'rwmb-user-login',
		'title'      => 'login',
		'post_types' => array( 'rwmb-user-profile' ),
		'fields'     => apply_filters( 'rwmb_profile_login_fields', array(
			'username' => array(
				'name'     => __( 'Username or Email Address', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_login',
				'type'     => 'text',
				'required' => true,
			),
			'password' => array(
				'name'     => __( 'Password', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_pass',
				'type'     => 'password',
				'required' => true,
			),
		) ),
	);

	$current_user = wp_get_current_user();
	$meta_boxes[] = array(
		'id'         => 'rwmb-user-info',
		'title'      => 'info',
		'post_types' => array( 'rwmb-user-profile' ),
		'fields'     => apply_filters( 'rwmb_profile_info_fields', array(
			'password'  => array(
				'name'     => __( 'New Password', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_pass',
				'type'     => 'password',
				'required' => true,
			),
			'password2' => array(
				'name'     => __( 'Confirm Password', 'mb-user-profile' ) . ' <span class="rwmb-required">*</span>',
				'id'       => 'user_pass2',
				'type'     => 'password',
				'required' => true,
			),
		) ),
	);
	return $meta_boxes;
}
