<?php
/**
 * Created by PhpStorm.
 * User: WP_Panda
 * Date: 18.02.2020
 * Time: 15:37
 */

class WPP_Extra_Product_FIelds{

	public static function init() {

		add_filter( 'woocommerce_product_data_tabs', [ __CLASS__, 'add_my_custom_product_data_tab' ], 99, 1 );

		add_action( 'woocommerce_product_data_panels', [ __CLASS__, 'add_my_custom_product_data_fields' ] );

		add_action( 'woocommerce_process_product_meta', [
			__CLASS__,
			'woocommerce_process_product_meta_fields_save'
		] );
	}


	function add_my_custom_product_data_tab( $product_data_tabs ) {
		$product_data_tabs[ 'wpp-params' ] = [
			'label'  => __( 'Характеристики', 'wpp-fr' ),
			'target' => 'wpp_params_product_data',
		];

		return $product_data_tabs;
	}


	public static function get_fields() {
		$args = [];


		$args = apply_filters( 'wpp_fr_woo_extra_fields', $args );


		return $args;
	}


	function add_my_custom_product_data_fields() {

		global $post;

		?>
		<div id="wpp_params_product_data" class="panel woocommerce_options_panel">
			<?php
			$array = self::get_fields();
			foreach ( $array as $key => $data ) {
				$data[ 'id' ] = $key;
				$cats         = get_the_terms( $post->ID, 'product_cat' );
				$cats_ids     = wp_list_pluck( $cats, 'term_id' );


				if ( empty( $data[ 'term' ] ) ) {
					continue;
				}

				$type = ! empty( $data[ 'type' ] ) ? $data[ 'type' ] : 'text_input';

				if ( is_array( $data[ 'term' ] ) ) {


					foreach ( $data[ 'term' ] as $one_term ) {


						if ( ! in_array( $one_term, $cats_ids ) ) {
							continue;
						}


						call_user_func( 'woocommerce_wp_' . $type, $data );
					}

				} else {

					if ( ! in_array( $data[ 'term' ], $cats_ids ) ) {
						continue;
					}


					call_user_func( 'woocommerce_wp_' . $type, $data );
				}


			}
			?>
		</div>
		<?php
	}


	function woocommerce_process_product_meta_fields_save( $post_id ) {
		$array = self::get_fields();
		foreach ( $array as $key => $data ) {

			if ( $data[ 'type' ] === 'checkbox' ) {
				$update = isset( $_POST[ $key ] ) ? 'yes' : 'no';
			} else {
				$update = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
			}

			update_post_meta( $post_id, $key, $update );
		}
	}

}

$_GLOBAL[ 'wpp_extra_product_fields' ] = WPP_Extra_Product_FIelds::init();