<?php

/**
 *Heading field class.
 */
class RWMB_WPP_Image_Heading_Field extends RWMB_Field {
	/**
	 * Enqueue scripts and styles.
	 */
	public static function admin_enqueue_scripts() {
		list( , $url ) = RWMB_Loader::get_path( dirname( __FILE__ ) );
		wp_enqueue_style( 'wpp-image-head', $url . 'css/wpp-image.css', array(), RWMB_VER );
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
		$id    = empty( $field['id'] ) ? '' : " id='{$field['id']}'";
		$image = ! empty( $field['img'] ) ? esc_url( $field['img'] ) : null;

		return sprintf( '<div%s class="wpp_meta_img_wrap"><img src="%s" alt=""></div>', $id, $image );
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