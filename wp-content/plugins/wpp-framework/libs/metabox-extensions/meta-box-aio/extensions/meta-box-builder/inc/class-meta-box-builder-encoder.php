<?php
/**
 * Builder encoder class
 *
 * @package    Meta Box
 * @subpackage Meta Box Builder
 */

class Meta_Box_Builder_Encoder {
	/**
	 * Function name.
	 *
	 * @var string
	 */
	protected $function_name = 'your_prefix_register_meta_boxes';

	/**
	 * Text domain.
	 *
	 * @var string
	 */
	protected $text_domain = 'text-domain';

	/**
	 * Encode.
	 *
	 * @param  array $data Data to encode.
	 * @return string      Encoded string.
	 */
	public function encode( $data ) {
		$string_data = var_export( $data, true );
		$string_data = $this->replace_get_text_function( $string_data );
		$string_data = $this->replace_get_array_function( $string_data );
		$string_data = $this->fix_code_standard( $string_data );
		$string_data = $this->wrap_function_call( $string_data );

		return $string_data;
	}

	/**
	 * Replace translatable string with gettext function.
	 *
	 * @param  string $string_data Encoded string.
	 * @return string
	 */
	protected function replace_get_text_function( $string_data ) {
		$find    = "/'###(.*)###'/";
		$replace = "esc_html__( '$1', '" . $this->text_domain . "' )";

		return preg_replace( $find, $replace, $string_data );
	}

	/**
	 * Replace translatable string with gettext function.
	 *
	 * @param  string $string_data Encoded string.
	 * @return string
	 */
	protected function replace_get_array_function( $string_data ) {
		$find    = "/'@@@(.*)@@@'/";
		$replace = '$1';

		return preg_replace( $find, $replace, $string_data );
	}

	/**
	 * Make encoded string compatible with WordPress coding standard.
	 *
	 * @param  string $string_data Encoded string.
	 * @return string
	 */
	protected function fix_code_standard( $string_data ) {
		$search = array(
			'/  /',
			"/\n\t/",
			"/\n\)/",
			"/=> \n\t\tarray \(/",
			"/\n\t\t\t\\d => /",
		);

		$replace = array(
			"\t",
			"\n\t\t",
			"\n\t)",
			'=> array(',
			"\n\t\t\t",
		);

		$string_data = preg_replace( $search, $replace, $string_data );

		return $string_data;
	}

	/**
	 * Wrap encoded string with function name and hook.
	 *
	 * @param  string $string_data Encoded string.
	 * @return string
	 */
	protected function wrap_function_call( $string_data ) {
		$string_data = sprintf(
			"add_filter( 'rwmb_meta_boxes', '%1\$s' );\nfunction %1\$s( \$meta_boxes ) {\n\t\$meta_boxes[] = %2\$s;\n\treturn \$meta_boxes;\n}",
			$this->function_name,
			$string_data
		);

		return $string_data;
	}
}
