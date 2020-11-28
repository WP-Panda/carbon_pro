<?php
/**
 * The main class that handles frontend forms.
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

/**
 * A collection of helper functions
 */
class MB_User_Profile_Helpers {
	/**
	 * Load template file name with priorities:
	 * - Child theme (inside folder /mb-frontend-submission/
	 * - Parent theme (inside folder /mb-frontend-submission/
	 * - Plugin /templates/ folder
	 *
	 * @param string $slug Template file slug.
	 * @param string $name Template variation.
	 * @param array  $data Data passed to template file.
	 */
	public static function load_template( $slug, $name = '', $data = array() ) {
		static $template_loader = null;
		if ( null === $template_loader ) {
			$template_loader = new MB_User_Profile_Template_Loader();
		}
		$template_loader->set_template_data( $data )->get_template_part( $slug, $name );
	}
}
