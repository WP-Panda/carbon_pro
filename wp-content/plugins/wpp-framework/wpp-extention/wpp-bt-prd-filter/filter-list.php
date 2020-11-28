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
	
	function wpp_bt_prd_filter_list() {
		if ( empty( $_GET ) ) {
			return;
		}
		
		$str = '';
		foreach ( $_GET as $key => $val ) {
			
			if ( taxonomy_exists( wc_attribute_taxonomy_name( $key ) ) ) :
				$atts = explode( ',', $val );
				foreach ( $atts as $att ) {
					$term = get_term( $att );
					$str .= sprintf(
						'<li><a href="javascript:vod(0);" title="">%s</a><i data-remove="%s-%s" class="fa fa-times-circle unchecked-cross"></i>', /*get_term_link( $term, 'pa_' . $key ),*/ $term->name,
						$key, $att
					);
				}
			
			else:
			endif;
		}
		printf( '<ul class="selected-filter-list">%s</ul>', $str );
	}
	
	add_action('wpp_wc_bt_shop_filters_index','wpp_bt_prd_filter_list');