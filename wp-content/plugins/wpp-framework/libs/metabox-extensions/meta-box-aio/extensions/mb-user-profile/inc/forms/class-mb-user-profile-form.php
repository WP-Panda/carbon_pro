<?php
/**
 * The main class that handles frontend user profile forms.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * Frontend form class.
 */
class MB_User_Profile_Form {
	/**
	 * Meta boxes object.
	 *
	 * @var array
	 */
	public $meta_boxes;

	/**
	 * The object model that meta box is for.
	 *
	 * @var MB_User_Profile_User
	 */
	public $object;

	/**
	 * Form configuration.
	 *
	 * @var array
	 */
	public $config;

	/**
	 * Constructor.
	 *
	 * @param array                $meta_boxes Meta box array.
	 * @param MB_User_Profile_User $object     Object model where the custom fields belong to.
	 * @param array                $config     Form configuration.
	 */
	public function __construct( $meta_boxes, $object, $config ) {
		$this->meta_boxes = array_filter( $meta_boxes );
		$this->object     = $object;
		$this->config     = $config;
	}

	/**
	 * Output the form.
	 */
	public function render() {
		if ( ! $this->has_privilege() ) {
			return;
		}

		$this->enqueue();

		if ( $this->is_processed() ) {
			do_action( 'rwmb_profile_before_display_confirmation', $this->config );
			$this->display_confirmation();
			do_action( 'rwmb_profile_after_display_confirmation', $this->config );
			// return;
		}

		$this->display_errors();

		do_action( 'rwmb_profile_before_form', $this->config );

		echo '<form class="rwmb-form" method="post" enctype="multipart/form-data" encoding="multipart/form-data" id="' . esc_html( $this->config['form_id'] ) . '">';
		$this->render_hidden_fields();

		// Register wp color picker scripts for frontend.
		$this->register_scripts();
		wp_localize_jquery_ui_datepicker();

		foreach ( $this->meta_boxes as $meta_box ) {
			$meta_box->enqueue();
			$meta_box->show();
		}

		do_action( 'rwmb_profile_before_submit_button', $this->config );
		$this->submit_button();
		do_action( 'rwmb_profile_after_submit_button', $this->config );

		echo '</form>';

		do_action( 'rwmb_profile_after_form', $this->config );
	}

	/**
	 * Process the form.
	 * Meta box auto hooks to 'save_post' action to save its data, so we only need to save the post.
	 *
	 * @return string Error message if any.
	 */
	public function process() {
		$is_valid = true;
		foreach ( $this->meta_boxes as $meta_box ) {
			$is_valid = $is_valid && $meta_box->validate();
		}

		$is_valid = apply_filters( 'rwmb_profile_validate', $is_valid, $this->config );

		if ( ! $is_valid ) {
			return __( 'Invalid form submit.', 'mb-user-profile' );
		}
		do_action( 'rwmb_profile_before_process', $this->config );
		$result = $this->object->save();
		do_action( 'rwmb_profile_after_process', $this->config, $result['user_id'] );
		return $result;
	}

	/**
	 * Check if current user have privilege to access the form.
	 *
	 * @return bool
	 */
	protected function has_privilege() {
		return true;
	}

	/**
	 * Display errors.
	 */
	protected function display_errors() {
		if ( isset( $_COOKIE['mb_user_profile_error'] ) ) {
			echo '<div class="rwmb-profile-error">', wp_kses_post( $_COOKIE['mb_user_profile_error'] ), '</div>';
		}
	}

	/**
	 * Display submit button.
	 */
	protected function submit_button() {
	}

	/**
	 * Register scripts.
	 */
	protected function register_scripts() {
		if ( wp_script_is( 'wp-color-picker', 'registered' ) ) {
			return;
		}
		wp_register_script( 'iris', admin_url( 'js/iris.min.js' ), array(
			'jquery-ui-draggable',
			'jquery-ui-slider',
			'jquery-touch-punch',
		), '1.0.7', true );
		wp_register_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), '', true );

		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
			'clear'            => __( 'Clear', 'mb-frontend-submission' ),
			'clearAriaLabel'   => __( 'Clear color', 'mb-frontend-submission' ),
			'defaultString'    => __( 'Default', 'mb-frontend-submission' ),
			'defaultAriaLabel' => __( 'Select default color', 'mb-frontend-submission' ),
			'pick'             => __( 'Select Color', 'mb-frontend-submission' ),
			'defaultLabel'     => __( 'Color value', 'mb-frontend-submission' ),
		) );
	}

	/**
	 * Enqueue scripts and styles for the forms.
	 */
	protected function enqueue() {
		wp_enqueue_style( 'mb-user-profile-form', MB_USER_PROFILE_URL . 'css/user-profile.css', '', '1.0' );
	}

	/**
	 * Render hidden fields for form configuration.
	 */
	protected function render_hidden_fields() {
		foreach ( $this->config as $key => $value ) {
			echo '<input type="hidden" name="rwmb_form_config[', esc_attr( $key ), ']" value="', esc_attr( $value ), '">';
		}
	}

	/**
	 * Check if the form is processed and process it if necessary.
	 *
	 * @return bool True if the form has been processed, false otherwise.
	 */
	protected function is_processed() {
		return 'success' === filter_input( INPUT_GET, 'rwmb-form-submitted' );
	}

	/**
	 * Display confirmation message.
	 */
	protected function display_confirmation() {
		MB_User_Profile_Helpers::load_template( 'confirmation', '', $this->config );
	}
}
