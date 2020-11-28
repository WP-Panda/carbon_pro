<?php
	/**
	 * Created by PhpStorm.
	 * User: WP_PANDA
	 * Date: 09.03.2019
	 * Time: 13:09
	 */

	if ( ! function_exists( 'wpp_dump' ) ) :

		/**
		 * var_dump for wp-panda
		 *
		 * @since 0.0.1
		 *
		 * @param $data
		 */
		function wpp_dump( $data ) {
			if ( is_wpp_panda() ) {
				echo '<pre>';
				var_dump( $data );
				echo '</pre>';
			}
		}
	endif;

	if ( ! function_exists( 'wpp_d_log' ) ) {
		/**
		 * echo log in file
		 *
		 * @since 0.0.1
		 *
		 * @param $log
		 */
		function wpp_d_log( $log ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}


	function wpp_console( $data ) {
		return new Wpp_Console_Log( $data );
	}