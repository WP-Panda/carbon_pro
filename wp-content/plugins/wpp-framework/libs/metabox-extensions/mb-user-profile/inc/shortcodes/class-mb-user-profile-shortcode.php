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
class MB_User_Profile_Shortcode {
	/**
	 * Shortcode type. Defined in subclass.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Initialization.
	 */
	public function init() {
		add_shortcode( "mb_user_profile_{$this->type}", array( $this, 'shortcode' ) );
		if ( filter_input( INPUT_POST, "rwmb_profile_submit_{$this->type}", FILTER_SANITIZE_STRING ) ) {
			add_action( 'template_redirect', array( $this, 'process' ) );
		}
	}

	/**
	 * Output the user form in the frontend.
	 *
	 * @param array $atts Form parameters.
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {
		$form = $this->get_form( $atts );
		if ( false === $form ) {
			return 'No forms';
		}
		ob_start();
		$form->render();

		return ob_get_clean();
	}

	/**
	 * Handle the form submit.
	 */
	public function process() {
	}

	/**
	 * Get the form.
	 *
	 * @param array $args Form configuration.
	 *
	 * @return bool|MB_User_Profile_Form_Info Form object or false.
	 */
	protected function get_form( $args ) {
	}
}
