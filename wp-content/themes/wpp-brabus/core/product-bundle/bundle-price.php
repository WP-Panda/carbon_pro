<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Получение данных по всему бандлу
 *
 * @param array $meta
 *
 * @return array
 */
function wpp_br_bundle( $meta = [], $post_id = '' ) {

	if ( empty( $meta ) ) {
		global $post;
		$meta = get_post_meta( $post->ID, 'bundle_package', false );
	}

	if ( ! empty( $post_id ) ) {
		$sale = (int) get_post_meta( $post_id, 'bundle_sale', true );
	}

	$sale_coeff = ! empty( $sale ) ? 1 - ( $sale / 100 ) : 1;

	$full = $flags = [];
	if ( ! empty( $meta ) ) {
		foreach ( $meta as $post_id ) {
			$options = wpp_get_opt_data( $post_id );

			$out = $keyer_flag = $array = [];

			foreach ( $options as $key => $val ) :

				$out[ $val['key'] ]                   = $val;
				$out[ $val['key'] ]['opt_price_html'] = wc_price( $sale_coeff * $val['opt_price'] );
				$out[ $val['key'] ]['opt_price']      = $sale_coeff * $val['opt_price'];
				$out[ $val['key'] ]['sku']            = $val['sku'];
				$out[ $val['key'] ]['post_id']        = $post_id;
				$out[ $val['key'] ]['time']           = (int) get_post_meta( $post_id, 'create_time', true );
				$keyers                               = explode( '-', $val['key'] );


				$flag_default = 0;
				$key_flag     = count( $keyers );
				foreach ( $keyers as $one_key ) :
					$post = get_post( $one_key );
					if ( ! empty( $post ) && $post->post_type === 'as_option' ) {
						$keyer_flag[ $one_key ] = get_post_meta( $one_key, 'def_cat', true );
					} else {
						$keyer_flag[ $one_key ] = get_term_meta( $one_key, 'def_cat', true );
					}

					$flag_default = $flag_default + (int) $keyer_flag[ $one_key ];
				endforeach;


				if ( $flag_default === $key_flag ) {
					#$id      = explode( '-', $val['key'] );
					#$img_ID  = get_term_meta( $id[0], 'image', true );
					#$img_src = wp_get_attachment_image_src( $img_ID, 'full' );

					#тут только для первого элемента набора
					if ( empty( $array ) ) {
						$array[ $val['key'] ] = $val['key'];
					}

				}

			endforeach;
			$rr      = $array;
			$full[]  = $out[ array_shift( $rr ) ];
			$flags[] = array_shift( $array );

		}


	}


	return $full;
}


/**
 * Получение цены набора по умолчанию
 *
 * @param array $meta
 *
 * @return int
 */
function wpp_br_bundle_price( $meta = [], $post_id = '' ) {

	$full  = wpp_br_bundle( $meta, $post_id );
	$price = 0;
	foreach ( $full as $item ) {
		$price = $price + $item['opt_price'];
	}

	return $price;
}


/**
 * Строка добавления набора в корзину
 *
 * @param array $meta
 *
 * @return string
 */
function wpp_br_bundle_cart_link( $meta = [], $post_id = '' ) {
	$full = wpp_br_bundle( $meta, $post_id );

	$assembly = get_post_meta( (int) $post_id, 'assembly', true );
	$paint    = get_post_meta( (int) $post_id, 'paint', true );

	$user_lng = get_locale();
	$geo_flag = wpp_fr_is_russia();

	$pa_icon = '<svg style="height: 2vh;" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="paint-roller" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-paint-roller fa-w-16 fa-2x"><path fill="currentColor" d="M464 64h-48V48c0-26.51-21.49-48-48-48H48C21.49 0 0 21.49 0 48v64c0 26.51 21.49 48 48 48h320c26.51 0 48-21.49 48-48V96h48c8.81 0 16 7.17 16 16v96c0 8.83-7.19 16-16 16H256c-26.47 0-48 21.53-48 48v48h-16c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h64c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32h-16v-48c0-8.83 7.19-16 16-16h208c26.47 0 48-21.53 48-48v-96c0-26.47-21.53-48-48-48zm-80 0v48c0 8.82-7.18 16-16 16H48c-8.82 0-16-7.18-16-16V48c0-8.82 7.18-16 16-16h320c8.82 0 16 7.18 16 16v16zM256 480h-64V352h64v128z" class=""></path></svg>';
	$as_icon = '<svg style="height: 2vh;" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="wrench" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-wrench fa-w-16 fa-2x"><path fill="currentColor" d="M507.42 114.49c-2.34-9.47-9.66-16.98-19.06-19.61-9.47-2.61-19.65 0-26.65 6.98l-63.87 63.87-44.25-7.36-7.38-44.24 63.87-63.87c6.94-6.92 9.62-17.09 7-26.54-2.62-9.47-10.19-16.83-19.75-19.2C345.6-8.31 291.95 6.54 254.14 44.3c-37.84 37.87-52.21 92.52-38.62 144.7L22.19 382.29c-29.59 29.63-29.59 77.83 0 107.45C36.54 504.09 55.63 512 75.94 512s39.37-7.91 53.71-22.26l193.14-193.11c52.03 13.73 106.8-.72 144.89-38.82 37.81-37.81 52.68-91.39 39.74-143.32zm-62.36 120.7c-31.87 31.81-78.43 42.63-121.77 28.23l-9.38-3.14-206.88 206.84c-16.62 16.62-45.59 16.62-62.21 0-17.12-17.14-17.12-45.06 0-62.21l207.01-206.98-3.09-9.34c-14.31-43.45-3.56-90.06 28.03-121.67C299.48 44.2 329.44 32 360.56 32c6.87 0 13.81.59 20.72 1.81l-69.31 69.35 13.81 83.02L408.84 200l69.3-69.35c6.72 38.25-5.34 76.79-33.08 104.54zM80 416c-8.84 0-16 7.16-16 16s7.16 16 16 16 16-7.16 16-16-7.16-16-16-16z" class=""></path></svg>';


	$opt_html = <<<OPT_HTML
        <p style="margin-bottom: 5px;">
            <span class="serv-icon">%s</span>
            <span class="serv-price">%s</span>
        </p>
OPT_HTML;

	$as_str = $pa_str = '';
	if ( ! is_singular() ) {
		if ( ! empty( $assembly ) && ( $geo_flag !== false || $user_lng === 'ru_RU' ) ) {
			$as_str  = '&b_assembly=1';
			#wpp_console(__FILE__ . ':::' . __LINE__);
			$as_html = sprintf( $opt_html, $as_icon, wc_price( apply_filters( 'wpp_dep_field_pice', $assembly, $post_id ) ) );
		}

		if ( ! empty( $paint ) && $geo_flag !== false ) {
			$pa_str  = '&b_paint=1';
			#wpp_console(__FILE__ . ':::' . __LINE__);
			$pa_html = sprintf( $opt_html, $pa_icon, wc_price( apply_filters( 'wpp_dep_field_pice', $paint, $post_id ) ) );
		}
	} else {
		if ( ! empty( $paint ) ) {
			$pa_str = '&b_paint=1';
		}
		if ( ! empty( $paint ) && $user_lng === 'ru_RU' ) {
			$as_str = '&b_assembly=1';
		}
	}

	$posts = $key = '';
	$n     = 1;

	foreach ( $full as $item ) {

		$sep   = $n !== 1 ? ',' : '';
		$posts .= $sep . $item['post_id'];
		$key   .= $sep . $item['key'];
		$n ++;
	}

	return sprintf( '%s/?bundle=%s&add-to-cart=%s&wpp_add_variants=%s%s%s', get_home_url(), $post_id, $posts, $key, $as_str, $pa_str );
}


/**
 * Максимальная дата производства
 *
 * @param array $meta
 *
 * @return string
 */
function wpp_br_bundle_max_time( $meta = [], $post_id = '' ) {
	$full  = wpp_br_bundle( $meta, $post_id );
	$times = wp_list_pluck( $full, 'time' );
	$array = wpp_br_create_time_array();
	$date  = max( $times );

	if ( ! empty( $date ) ) {


		$text = sprintf( '<span class="wpp_br_time_text">%s</span>', $array[ $date ] );
		$img  = sprintf( '<img class="wpp-br-box-image" src="%s/assets/img/icons/box-%s.svg" alt="">', get_template_directory_uri(), $date );

		printf( '<p class="wpp-br-time-p"><span>%s%s</span></p>', $img, $text );


	}

}



add_filter( 'woocommerce_cart_item_name', 'cart_product_title', 20, 3 );

function cart_product_title( $title, $value, $cart_item_key ) {

	$str = '';

	if ( ! empty( $value['wpp_add_bundle'] ) ) {
		$str = '<small class="wpp-cart-bundle-title">' . wpp_br_lng( 'package_in' ) . get_the_title( (int) $value['wpp_add_bundle'] ) . '</small>';
	}

	if ( $value['product_id'] === PAINT ) {
		$title = sprintf( '%s %s', wpp_br_lng( 'package_pa' ), get_the_title( $value['puck_id'] ) );
	}

	if ( $value['product_id'] === ASSEM ) {
		$title = sprintf( '%s %s', wpp_br_lng( 'package_as' ), get_the_title( $value['puck_id'] ) );
	}

	return $str . $title;
}