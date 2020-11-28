<?php
	/**
	 * File Description
	 *
	 * @author  WP Panda
	 *
	 * @package Time, it needs time
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	/**
	 * Перегон атрибутов в нижний регистр
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	function wpp_bt_convert_atts( $atts ) {
		return str_replace( '-', '_', $atts );
	}
	
	/**
	 *  Атрибуты продукта ы массив
	 *
	 * @param $in_base - по умолчанию просто атрибуты,  если true то для sql
	 * @param $prd_id
	 *
	 * @return array
	 */
	function wpp_bt_prd_atts( $prd_id, $in_base = null ) {
		$out = array ();
		$product = new WC_Product( $prd_id );
		
		$out[ 'price' ] = $product->get_price();
		$out[ 'stock_status' ] = $product->get_stock_status();;
		
		$cats = get_the_terms( $prd_id, 'product_cat' );
		$cats_args = array ();
		if ( ! empty( $cats ) ) :
			foreach ( $cats as $cat ) {
				$cats_args[] = $cat->term_id;
			}
			$out[ 'product_cat' ] = implode( ',', $cats_args );
		endif;
		
		$tags = get_the_terms( $prd_id, 'product_cat' );
		if ( ! empty( $tags ) ) :
			$tags_args = array ();
			foreach ( $tags as $tag ) {
				$tags_args[] = $tag->term_id;
			}
			$out[ 'product_tag' ] = implode( ',', $tags_args );
		endif;
		
		$attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );
		
		foreach ( $attributes as $attribute ) :
			
			if ( $attribute->is_taxonomy() ) {
				
				$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array ( 'fields' => 'all' ) );
				$flag = array ();
				foreach ( $attribute_values as $attribute_value ) {
					$flag[] = $attribute_value->term_id;
				}
				$name = empty( $in_base ) || $in_base !== true ? $attribute->get_name() : wpp_bt_convert_atts( $attribute->get_name() );
				$out[ $name ] = implode( ',', $flag );
			}
		
		endforeach;
		
		return $out;
		
	}