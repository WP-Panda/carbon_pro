<?php
	/**
	 * Получение id c fnhb,enfvb gj evjkxfyb.
	 *
	 * @param $product
	 * @param $attributes
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function wpp_fr_find_matching_product_variation( $product, $attributes ) {

		foreach ( $attributes as $key => $value ) {
			if ( strpos( $key, 'attribute_' ) === 0 ) {
				continue;
			}

			unset( $attributes[ $key ] );
			$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
		}

		if ( class_exists( 'WC_Data_Store' ) ) {

			$data_store = WC_Data_Store::load( 'product' );
			return $data_store->find_matching_product_variation( $product, $attributes );

		} else {

			return $product->get_matching_variation( $attributes );

		}

	}

	/**
	 * Получение атрибутов по умолчанию
	 *
	 * @param WC_Product $product
	 *
	 * @return array
	 */
	function wpp_fr_get_default_attributes( $product ) {
		return $product->get_default_attributes();

	}