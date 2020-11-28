<?php
/**
 * The main class that handles frontend user profile forms.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * Frontend user profile form class.
 */
class MB_User_Profile_Form_Info extends MB_User_Profile_Form {
	/**
	 * Check if current user have privilege to access the form.
	 *
	 * @return bool
	 */
	protected function has_privilege() {
		if ( ! is_user_logged_in() ) {
			esc_html_e( 'Please login to continue.', 'mb-user-profile' );
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
			<button class="rwmb-button" name="rwmb_profile_submit_info" value="1"><?php echo esc_html( $this->config['submit_button'] ); ?></button>
		</div>
		<?php
	}
}
