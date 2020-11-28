<?php
/**
 * The user object model.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * User class.
 */
class MB_User_Profile_User {

	/**
	 * User ID.
	 *
	 * @var int
	 */
	public $user_id;

	/**
	 * Configuration for the user model.
	 *
	 * @var array
	 */
	public $config;

	/**
	 * Error message.
	 *
	 * @var string
	 */
	public $error;

	/**
	 * Constructor.
	 *
	 * @param int   $user_id Post ID.
	 * @param array $config  Custom parameters.
	 */
	public function __construct( $user_id = 0, $config = array() ) {
		$this->user_id = (int) $user_id;
		$this->config  = $config;
	}

	/**
	 * Save user data.
	 *
	 * @return array Array( error, user_id )
	 */
	public function save() {
		do_action( 'rwmb_profile_before_save_user', $this );

		if ( $this->user_id ) {
			$this->update();
		} else {
			$this->create();
		}

		do_action( 'rwmb_profile_after_save_post', $this );

		return array(
			'error'   => $this->error,
			'user_id' => $this->user_id,
		);
	}

	/**
	 * Update user info.
	 */
	private function update() {
		$data = $this->get_data();
		$data = apply_filters( 'rwmb_profile_update_user_data', $data, $this->config );

		// Do not update user data, only trigger an action for Meta Box to update custom fields.
		if ( empty( $data ) ) {
			$old_user_data = get_userdata( $this->user_id );
			if ( ! $old_user_data ) {
				$this->error = esc_html__( 'Invalid user ID.', 'mb-user-profile' );
				return;
			}
			do_action( 'profile_update', $this->user_id, $old_user_data );
			return;
		}

		// Update user data.
		$data['ID'] = $this->user_id;
		if ( isset( $data['user_pass'] ) && isset( $data['user_repeat_password'] ) && $data['user_pass'] !== $data['user_repeat_password'] ) {
			$this->error = esc_html__( 'Passwords do not coincide.', 'mb-user-profile' );
			return;
		}
		unset( $data['user_repeat_password'] );

		$result = wp_update_user( $data );
		if ( is_wp_error( $result ) ) {
			$this->error = $result->get_error_message();
			return;
		}
	}

	/**
	 * Create a new user.
	 */
	private function create() {
		$data = $this->get_data();

		$data = apply_filters( 'rwmb_profile_insert_user_data', $data, $this->config );
		if ( isset( $data['user_login'] ) && username_exists( $data['user_login'] ) ) {
			$this->error = esc_html__( 'Your username already exists.', 'mb-user-profile' );
			return;
		}
		if ( isset( $data['user_email'] ) && email_exists( $data['user_email'] ) ) {
			$this->error = esc_html__( 'Your email already exists.', 'mb-user-profile' );
			return;
		}
		if ( isset( $data['user_pass'] ) && isset( $data['user_repeat_password'] ) && $data['user_pass'] !== $data['user_repeat_password'] ) {
			$this->error = esc_html__( 'Passwords do not coincide.', 'mb-user-profile' );
			return;
		}
		unset( $data['user_repeat_password'] );

		$result = wp_insert_user( $data );
		if ( is_wp_error( $result ) ) {
			$this->error = $result->get_error_message();
		} else {
			$this->user_id = $result;
		}
	}

	/**
	 * Get submitted data to save into the database.
	 *
	 * @return array
	 */
	private function get_data() {
		$data = array(
			'user_login'           => '',
			'user_email'           => '',
			'user_pass'            => '',
			'user_repeat_password' => '',
		);

		foreach ( $data as $field => $value ) {
			$data[ $field ] = (string) filter_input( INPUT_POST, $field );
		}
		return array_filter( $data );
	}
}
