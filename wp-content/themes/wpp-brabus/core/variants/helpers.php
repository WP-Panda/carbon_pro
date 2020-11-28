<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Полученеие / параметра опции по другому параметру
 *
 * @param $post_id - id родукта
 * @param $need - параметр который искать
 * @param string $field - параметр по которому искать по умолчанию 'key'
 * @param string $return - что вернуть, по умолчанию все
 *
 * @return bool
 */
function wpp_fr_get_variant_by( $post_id, $need, $field = null, $return = null ) {

	$out = false;

	$meta = wpp_get_opt_data( $post_id );
	if ( ! empty( $meta ) ) {

		$field = empty( $field ) ? 'key' : $field;

		$key = array_search( $need, array_column( $meta, $field ) );

		if ( isset( $key ) ) {

			if ( empty( $return ) ) {
				$out = $meta[ $key ];
			} else {
				$return_val = $return === 'price' ? 'opt_price' : $return;

				if ( !empty( $meta[ $key ][ $return_val ] ) ) {
					$out = $meta[ $key ][ $return_val ];
				}
			}
		}
	}

	return $out;
}