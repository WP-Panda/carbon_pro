<?php
/**
 * The main class that handles frontend login forms.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * Frontend login form class.
 */
class MB_User_Profile_Form_Login extends MB_User_Profile_Form {
	/**
	 * Check if current user have privilege to access the form.
	 *
	 * @return bool
	 */
	protected function has_privilege() {
		if ( is_user_logged_in() && ! $this->is_processed() ) {
			esc_html_e( 'You are already logged in.', 'mb-user-profile' );
			return false;
		}
		return true;
	}

	/**
	 * Display submit button.
	 */
	protected function submit_button() {
		?>
		<div class="rwmb-field rwmb-button-wrapper rwmb-form-submit">
			<?php if ( $this->config['remember'] ) : ?>
				<p class="rwmb-form-submit-remember">
					<label>
						<input name="rememberme" type="checkbox" id="<?php echo esc_attr( $this->config['id_remember'] ); ?>" value="forever" <?php checked( $this->config['value_remember'], true ); ?>>
						<?php echo esc_html( $this->config['label_remember'] ); ?>
					</label>
				</p>
			<?php endif; ?>
			<div class="rwmb-form-submit-login">
				<p class="rwmb-form-submit-button">
					<button class="rwmb-button" id="<?php echo esc_attr( $this->config['id_submit'] ); ?>" name="rwmb_profile_submit_login" value="1"><?php echo esc_html( $this->config['label_submit'] ); ?></button>
				</p>
				<p class="rwmb-form-submit-lost-password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html( $this->config['label_lost_password'] ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Process the form.
	 *
	 * @return string Error message if any.
	 */
	public function process() {
		$username    = filter_input( INPUT_POST, 'user_login' );
		$password    = filter_input( INPUT_POST, 'user_pass' );
		$remember    = filter_input( INPUT_POST, 'rememberme' );
		$credentials = array(
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => (bool) $remember,
		);

		$user = wp_signon( $credentials, true );
		return is_wp_error( $user ) ? $user->get_error_message() : '';
	}
}
