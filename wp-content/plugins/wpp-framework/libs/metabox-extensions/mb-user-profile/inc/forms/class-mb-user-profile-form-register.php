<?php
/**
 * The main class that handles frontend register forms.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * Frontend register form class.
 */
class MB_User_Profile_Form_Register extends MB_User_Profile_Form {
	/**
	 * Display submit button.
	 */
	protected function submit_button() {
		?>
		<div class="rwmb-field rwmb-button-wrapper rwmb-form-submit">
			<button class="rwmb-button login100-form-btn" name="rwmb_profile_submit_register" value="1"><?php echo esc_html( $this->config['submit_button'] ); ?></button>
		</div>
		<?php
	}
}
