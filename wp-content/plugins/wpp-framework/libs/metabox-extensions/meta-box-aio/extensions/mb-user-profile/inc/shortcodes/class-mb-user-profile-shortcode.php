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
abstract class MB_User_Profile_Shortcode {
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
		$config = isset( $_POST['rwmb_form_config'] ) ? $_POST['rwmb_form_config'] : '';
		if ( empty( $config ) ) {
			return;
		}
		$form = $this->get_form( $config );
		if ( false === $form ) {
			return;
		}

		// Make sure to include the WordPress media uploader functions to process uploaded files.
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		$is_error  = false;
		setcookie( 'mb_user_profile_error', '', strtotime( '-1 day' ), '/' );

		$error = $form->process();
		if ( $error ) {
			$is_error  = true;
			setcookie( 'mb_user_profile_error', $error, strtotime( '+1 day' ), '/' );
		}

		$redirect = add_query_arg( 'rwmb-form-submitted', $is_error ? 'error' : 'success' );

		if ( ! $is_error && $config['redirect'] ) {
			$redirect = $config['redirect'];
		}

		$redirect = apply_filters( 'rwmb_profile_redirect', $redirect, $config );
		wp_safe_redirect( $redirect );
		die;
	}

	/**
	 * Change array key.
	 * TODO: Remove when `rwmb_change_array_key()` is available.
	 *
	 * @param  array  $array Input array.
	 * @param  string $from  From key.
	 * @param  string $to    To key.
	 */
	protected function change_array_key( &$array, $from, $to ) {
		if ( ! $array ) {
			return;
		}
		if ( isset( $array[ $from ] ) ) {
			$array[ $to ] = $array[ $from ];
		}
		unset( $array[ $from ] );
	}
}
