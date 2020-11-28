<?php

/**
 *Heading field class.
 */
class RWMB_WPP_Heading_Field extends RWMB_Field {
	/**
	 * Enqueue scripts and styles.
	 */
	public static function admin_enqueue_scripts() {
		list( , $url ) = RWMB_Loader::get_path( dirname( __FILE__ ) );
		wp_enqueue_style( 'wpp-headin', $url . 'css/heading.css', array(), RWMB_VER );
	}

	/**
	 * Show begin HTML markup for fields.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function begin_html( $meta, $field ) {
		$id  = empty( $field['id'] ) ? '' : " id='{$field['id']}'";
		$tag = empty( $field['tag'] ) ? 'h4' : $field['tag'];

		return sprintf( '<%3$s%1$s>%2$s</%3$s>', $id, $field['name'],$tag );
	}

	/**
	 * Show end HTML markup for fields.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function end_html( $meta, $field ) {
		return self::input_description( $field );
	}
}