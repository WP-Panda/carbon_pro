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
	
	function filer_pre_get_posts( $query ) {
		if ( empty( $_GET ) ) {
			return $query;
		}
		
		if ( ! is_shop() || ! is_post_type_archive('product')) {
			return $query;
		}
		
		
		if ( $query->is_main_query() ) {
			$tax_query = $query->get( 'tax_query' );
			$meta_query = $query->get( 'meta_query' );
			if ( empty( $tax_query ) ) {
				$tax_query = [];
			}
			if ( empty( $meta_query ) ) {
				$meta_query = [];
			}
			foreach ( $_GET as $key => $val ) {
				
				if ( $key === 'min' || $key === 'max' ) {
					if ( $key === 'min' ) {
						$min_price = (int) $val;
					}
					if ( $key === 'max' ) {
						$max_price = (int) $val;
					}
					continue;
				}
				
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $key ) ) ) :
					$keys = explode( ',', $val );
					$tax_query[ 'pa_' . $key ] = array (
						'taxonomy' => 'pa_' . $key,
						'field'    => 'id',
						'terms'    => $keys,
						'operator' => 'AND'
					);
				
				else:
				
				endif;
				
				
			}
			
			$min_price = ! empty( $min_price ) ? $min_price : 0;
			$max_price = ! empty( $max_price ) ? $max_price : 1000000000000;
			
			$meta_query[] = array (
				'key'     => '_price',
				'value'   => array ( $min_price, $max_price ),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
			$meta_query[ 'relation' ] = 'AND';
			
			
			$query->set( 'meta_query', $meta_query );
			
			if ( ! empty( $tax_query ) ) {
				$tax_query[ 'relation' ] = 'AND';
				$query->set( 'tax_query', $tax_query );
			}
		}
		
		
	}
	
	add_action( 'pre_get_posts', 'filer_pre_get_posts' );
	
	function wpp_dump_query() {
		
		$WP_Query = new WP_Query();
		$var = $WP_Query->get( 'tax_query' );
		#wpp_dump( $_GET );
		#wpp_dump( $var );
		
		
	}
	
	#add_action( 'wp_footer', 'wpp_dump_query' );