<?php
add_action( 'pmxi_saved_post', 'update_post_opt', 10, 1 );

/**
 * Создание вариаций при импорте товароы
 *
 * @param $post_id
 */
function update_post_opt( $post_id ) {

	$post_type = get_post_type( $post_id );

	if ( 'product' == $post_type ) {


		$arg = [];

		$cff        = get_post_meta( $post_id, 'cff_price', true );
		$main_price = get_post_meta( $post_id, '_price', true );

		if ( empty( $main_price ) ) {
			wp_send_json_error( [ 'message' => __( 'Price is empty!!!' ) ] );
		}

		$cff_res  = ! empty( $cff ) ? (float) $cff : 1;
		$variants = wpp_create_variants_array( $post_id );

		$i = 0;
		foreach ( $variants as $key => $title ) {
			$paint         = false;
			$assembly      = false;
			$variants_args = explode( '-', $key );

			$a      = 0;
			$prices = $before_scu = $after_scu = [];
			foreach ( $variants_args as $one_variant ) :

				$post = get_post( $one_variant );

				if ( ! empty( $post ) && $post->post_type === 'as_option' ) :

					$price        = get_post_meta( $one_variant, 'def_price', true );
					$prices[ $a ] = ! empty( $price ) ? (float) str_replace( ',', '.', $price ) : 1;
					$preff_scu    = get_post_meta( $one_variant, '_scu_before', true );
					$postf_scu    = get_post_meta( $one_variant, '_scu_after', true );

				else :

					$price        = get_term_meta( $one_variant, 'def_price', true );
					$prices[ $a ] = ! empty( $price ) ? (float) str_replace( ',', '.', $price ) : 1;
					$preff_scu    = get_term_meta( $one_variant, '_scu_before', true );
					$postf_scu    = get_term_meta( $one_variant, '_scu_after', true );
					$paint        = get_term_meta( $one_variant, 'paint', true );
					$assembly     = get_term_meta( $one_variant, 'assembly', true );

				endif;

				if ( ! empty( $preff_scu ) ) :
					$before_scu[] = $preff_scu;
				endif;

				if ( ! empty( $postf_scu ) ) :
					$after_scu[] = $postf_scu;
				endif;

				$a ++;
			endforeach;

			$before_scu = ! empty( $before_scu ) ? implode( '-', $before_scu ) . '-' : null;
			$after_scu  = ! empty( $after_scu ) ? '-' . implode( '-', $after_scu ) : null;
			$scu_main   = get_post_meta( $post_id, '_sku', true );

			$arg[ $i ] = [
				'count'     => 10,
				'key'       => $key,
				'title'     => $title,
				'opt_price' => array_product( $prices ) * (float) $cff_res * (float) str_replace( ',', '.', $main_price ),
				'sku'       => $before_scu . $scu_main . $after_scu,
				'assembly'  => ! empty( $assembly ) ? 1 : null,
				'paint'     => ! empty( $paint ) ? 1 : null
			];

			$i ++;
		}

		update_post_meta( $post_id, 'wpp_as_products', $arg );


	}
}


add_action( 'wp_ajax_create_post_opt', 'create_post_opt' );

/**
 * Создание вариаций по кнопке
 *
 * @param $post_id
 */
function create_post_opt( $post_id ) {

	check_ajax_referer( 'wpp-co-special-string', 'security' );
	$post_id = intval( $_POST['post_id'] );

	$arg = [];

	$cff        = get_post_meta( $post_id, 'cff_price', true );
	$main_price = get_post_meta( $post_id, '_price', true );

	if ( empty( $main_price ) ) {
		wp_send_json_error( [ 'message' => __( 'Price is empty!!!', 'wpp-fr' ) ] );
	}

	$cff_res = ! empty( $cff ) ? (float) $cff : 1;

	$variants = wpp_create_variants_array( $post_id );


	$i = 0;
	foreach ( $variants as $key => $title ) {
		$paint         = false;
		$assembly      = false;
		$variants_args = explode( '-', $key );

		$a      = 0;
		$prices = $before_scu = $after_scu = [];
		foreach ( $variants_args as $one_variant ) :

			$post = get_post( $one_variant );

			if ( ! empty( $post ) && $post->post_type === 'as_option' ) :

				$price        = get_post_meta( $one_variant, 'def_price', true );
				$prices[ $a ] = ! empty( $price ) ? (float) str_replace( ',', '.', $price ) : 1;
				$preff_scu    = get_post_meta( $one_variant, '_scu_before', true );
				$postf_scu    = get_post_meta( $one_variant, '_scu_after', true );

			else :

				$price        = get_term_meta( $one_variant, 'def_price', true );
				$prices[ $a ] = ! empty( $price ) ? (float) str_replace( ',', '.', $price ) : 1;
				$preff_scu    = get_term_meta( $one_variant, '_scu_before', true );
				$postf_scu    = get_term_meta( $one_variant, '_scu_after', true );
				$paint        = get_term_meta( $one_variant, 'paint', true );
				$assembly     = get_term_meta( $one_variant, 'assembly', true );

			endif;

			if ( ! empty( $preff_scu ) ) :
				$before_scu[] = $preff_scu;
			endif;

			if ( ! empty( $postf_scu ) ) :
				$after_scu[] = $postf_scu;
			endif;

			$a ++;
		endforeach;

		$before_scu = ! empty( $before_scu ) ? implode( '-', $before_scu ) . '-' : null;
		$after_scu  = ! empty( $after_scu ) ? '-' . implode( '-', $after_scu ) : null;
		$scu_main   = get_post_meta( $post_id, '_sku', true );

		$arg[ $i ] = [
			'count'     => 10,
			'key'       => $key,
			'title'     => $title,
			'opt_price' => array_product( $prices ) * (float) $cff_res * (float) str_replace( ',', '.', $main_price ),
			'sku'       => $before_scu . $scu_main . $after_scu,
			'assembly'  => ! empty( $assembly ) ? 1 : null,
			'paint'     => ! empty( $paint ) ? 1 : null
		];

		$i ++;
	}

	$res = update_post_meta( $post_id, 'wpp_as_products', $arg );

	wp_send_json_success( [
		'message' => __( 'ok', 'wpp-fr' ),
		'res'     => $res,
		'arg'     => $arg,
		'id'      => $post_id
	] );

}
