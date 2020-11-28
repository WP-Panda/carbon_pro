<?php
/**
 * Created by PhpStorm.
 * User: WP_Panda
 * Date: 29.07.2019
 * Time: 20:13
 */

/**
 * Старт сессии
 */
function register_wpp_lang_session() {
	if ( ! session_id() ) {
		session_start();
	}
	if ( ! isset( $_SESSION['wpp_lng'] ) ) {
		$need                = wpp_fr_generate_default_url();
		$_SESSION['wpp_lng'] = 'Set-wpp';
		$actual              = wpp_fr_actual_link();
		if ( $need !== $actual ) {
			wp_redirect( $need, 301 );
		}
	}
}

add_action( 'init', 'register_wpp_lang_session', 0 );
add_filter( 'locale', 'wpp_lng_set', 100 );


/**
 * Установка языка
 */
function wpp_lng_set( $locate ) {


	$lng = wpp_get_url_lng();

	$lang = [
		'en' => 'en_US',
		'ru' => 'ru_RU'
	];

	return $lang[ $lng ];


}


/**
 * Массив язфков старый
 *
 * @return array
 */
function wpp_set_locate_args() {
	$array = [
		'en_US' => 'English',
		'ru_RU' => 'Русский'
	];

	return $array;

}


add_filter( 'wpp_fr_post_meta_boxes_args', 'wpp_lng_metaboxes' );


/**
 * Добавление полей для языков перевода
 *
 * @param $meta_boxes
 *
 * @return mixed
 */
function wpp_lng_metaboxes( $meta_boxes ) {

	$meta_boxes['lng'] = [
		'title'      => 'Перевод',
		'post_types' => [
			'product',
			'post',
			'page',
			'as_option'
		],
		'context'    => 'normal',
		'priority'   => 'high',
		'fields'     => []
	];

	$langs = wpp_set_locate_args();


	if ( ! empty( $langs ) && is_array( $langs ) ) {

		foreach ( $langs as $lang => $val ) {
			if ( $lang === 'en_US' ) {
				continue;
			}

			$meta_boxes['lng']['fields'][] = [
				'id'   => 'title_' . $lang,
				'name' => 'Заголовок для языка - ' . $val,
				'type' => 'text',
				'size' => 100
			];

			$meta_boxes['lng']['fields'][] = [
				'id'      => 'text_' . $lang,
				'name'    => 'Текст для языка - ' . $val,
				'type'    => 'wysiwyg',
				'raw'     => true,
				'options' => [
					'textarea_rows' => 4,
					'teeny'         => true,
				],
			];

		}
	}

	return $meta_boxes;

}

add_filter( 'wpp_fr_term_meta_boxes_args', 'wpp_term_metaboxes' );
function wpp_term_metaboxes( $meta_boxes ) {

	$meta_boxes['lngt'] = [
		'title'      => 'Дополнительно',
		'taxonomies' => 'product_tag',
		'fields'     => []
	];

	$langs = wpp_set_locate_args();

	if ( ! empty( $langs ) && is_array( $langs ) ) {

		foreach ( $langs as $lang => $val ) {
			if ( $lang === 'en_US' ) {
				continue;
			}

			$meta_boxes['lngt']['fields'][] = [
				'id'   => 'title_' . $lang,
				'name' => 'Заголовок для языка - ' . $val,
				'type' => 'text'
			];

		}
	}

	return $meta_boxes;

}

function get_user_titile( $title, $id ) {

	if ( is_admin() && ! is_ajax() ) :
		return $title;
	endif;
	$locale = get_locale();

	$title_lng = get_post_meta( $id, 'title_' . $locale, true );

	if ( ! empty( $title_lng ) ) {
		return $title_lng;
	} else {
		return $title;
	}
}

add_filter( 'the_title', 'get_user_titile', 10, 2 );


function get_user_content( $content, $post_id = null ) {
	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}
	if ( is_admin() && ! is_ajax() ) :
		return $content;
	endif;

	$locale      = get_locale();
	$content_lng = get_post_meta( $post_id, 'text_' . $locale, true );
	if ( ! empty( $content_lng ) ) {
		return $content_lng;
	} else {
		return $content;
	}
}

add_filter( 'the_content', 'get_user_content' );


function get_user_term_title( $term ) {
	$locale      = get_locale();
	$content_lng = get_term_meta( $term->term_id, 'title_' . $locale, true );
	if ( ! empty( $content_lng ) ) {
		return $content_lng;
	} else {
		return $term->name;
	}
}