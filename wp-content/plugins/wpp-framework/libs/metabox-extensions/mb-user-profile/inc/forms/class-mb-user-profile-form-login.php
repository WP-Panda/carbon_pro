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
		if ( is_user_logged_in() ) {
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
			<p class="rwmb-form-submit_forgetmenot">
				<label for="rwmb-rememberme">
					<input name="rwmb_rememberme" type="checkbox" id="rwmb-rememberme" value="forever">
					<?php echo esc_html( $this->config['remember'] ); ?>
				</label>
			</p>
			<p class="rwmb-form-submit_lostpass">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html( $this->config['lost_pass'] ); ?></a>
			</p>
			<p>
				<button class="rwmb-button" name="rwmb_profile_submit_login" value="1"><?php echo esc_html( $this->config['submit_button'] ); ?></button>
			</p>
		</div>
		<?php
	}
}
