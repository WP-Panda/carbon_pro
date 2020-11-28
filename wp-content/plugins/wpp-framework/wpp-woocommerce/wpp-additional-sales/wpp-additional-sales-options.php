<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'woocommerce_product_options_pricing', 'my_woo_custom_price_field' );
/**
 * Add a Christmas Price field
 **/
function my_woo_custom_price_field() {

	$field = [
		'id'        => 'cff_price',
		'label'     => __( 'Price Coefficient', 'textdomain' ),
		'data_type' => 'price'
	];

	woocommerce_wp_text_input( $field );

}


add_action( 'woocommerce_product_options_pricing', 'currency_woo_custom_price_field' );
/**
 * Add a Christmas Price field
 **/
function currency_woo_custom_price_field() {

	$field = [
		'id'    => '_currency',
		'label' => __( 'Currency', 'textdomain' ),
	];

	woocommerce_wp_text_input( $field );

}


/**
 * Save the custom field
 *
 * @since 1.0.0
 */
function currency_save_custom_field( $post_id ) {
	$product = wc_get_product( $post_id );
	$title   = isset( $_POST['_currency'] ) ? $_POST['_currency'] : '';
	$product->update_meta_data( '_currency', sanitize_text_field( $title ) );
	$product->save();
}

add_action( 'woocommerce_process_product_meta', 'currency_save_custom_field' );

/**
 * Save the custom field
 *
 * @since 1.0.0
 */
function cfwc_save_custom_field( $post_id ) {
	$product = wc_get_product( $post_id );
	$title   = isset( $_POST['cff_price'] ) ? $_POST['cff_price'] : '';
	$product->update_meta_data( 'cff_price', sanitize_text_field( $title ) );
	$product->save();
}

add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );


add_action( 'admin_footer', 'my_action_javascript' );
function my_action_javascript() {
	$ajax_nonce = wp_create_nonce( "wpp-co-special-string" );
	global $post;
	?>
    <script>
        jQuery(document).ready(function ($) {
            $('.wpp-create-variants').click(function (e) {
                e.preventDefault();
                var data = {
                    action: 'create_post_opt',
                    security: '<?php echo $ajax_nonce; ?>',
                    post_id: '<?php echo $post->ID; ?>'
                };

                $.post(ajaxurl, data, function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                });
            });
        });

        jQuery(document).ready(function ($) {
            $('.wpp-create-variants-cat').click(function (e) {
                e.preventDefault();
                var data = {
                    action: 'create_post_opt',
                    security: '<?php echo $ajax_nonce; ?>',
                    post_id: $(this).data('id')
                }

                $.post(ajaxurl, data, function (response) {
                    if (response.success) {
                      //  console.log('ok')
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                });
            });
        });


        jQuery(document).ready(function ($) {
            $('#wpp_var_parent').change(function (e) {
                e.preventDefault();
                var data = {
                    action: 'change_post_var_parent',
                    security: '<?php echo $ajax_nonce; ?>',
                    post_id: '<?php echo $post->ID; ?>',
                    val: $(this).val()
                }

                $.post(ajaxurl, data, function (response) {
                    if (response.success) {
                       // console.log('ok')
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                });
            });
        });
    </script>
	<?php
}


/**
 * Контрольнвй массив вариантов
 *
 * @param $id
 *
 * @return array
 */
function wpp_fr_control_keys( $id ) {

	$variants = wpp_create_variants_array( $id );

	$keys        = array_keys( $variants );
	$control_key = [];
	foreach ( $keys as $key ) :
		$control_key = array_merge( $control_key, explode( '-', $key ) );
	endforeach;

	return array_unique( $control_key );
}

/**
 * Вывод опций товара в карточке
 *
 * @param $post_id
 *
 * @return string
 */
function wpp_as_options( $post_id ) {

	$radio = [];
	$html  = '';

	$var = wpp_get_opt_data( $post_id );

	#выходной массив + массив данных ля варицйий
	$out      = $keyer = $keyer_flag = [];
	$flag_def = false;
	// формирование массива всех опций товара
	foreach ( $var as $key => $val ) :
		$out[ $val['key'] ]                   = $val;
		$out[ $val['key'] ]['opt_price_html'] = wc_price( $val['opt_price'] );
		$out[ $val['key'] ]['sku']            = $val['sku'];

		$keyers = explode( '-', $val['key'] );

		$keyer[] = $keyers;

		//получение това а по умолчанию
		$flag_default = 0;
		$key_flag     = count( $keyers );
		foreach ( $keyers as $one_key ) :
			$post = get_post( $one_key );
			if ( ! empty( $post ) ) :
				$keyer_flag[ $one_key ] = get_post_meta( $one_key, 'def_options', true );
			else :
				$keyer_flag[ $one_key ] = get_term_meta( $one_key, 'def_options', true );
			endif;
			$flag_default = $flag_default + (int) $keyer_flag[ $one_key ];
		endforeach;

		if ( $flag_default === $key_flag && $flag_def === false ) {
			$out[ $val['key'] ]['default'] = true;
			$flag_def                      = true;
		} else {
			$out[ $val['key'] ]['default'] = false;
		}

	endforeach;
#	wpp_console( $out );
	$html .= sprintf( '<div id="wpp-as-options-flag" data-options=\'%s\'>', json_encode( $out ) );
	if ( is_wpp_panda() ) {
		#wpp_dump( $out );
	}


	$url = get_the_permalink($post_id);

	$array_opt_3 = wpp_get_as_options_array(); ?>
    <!--<div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">-->
		<?php foreach ( $out as $one ) { ?>
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <meta itemprop="availability" content="http://schema.org/InStock">
                <meta itemprop="sku" content="<?php echo $one['sku']; ?>">
                <meta itemprop="price" content="<?php echo $one['opt_price']; ?>">
                <meta itemprop="inventoryLevel" content="10">
                <meta itemprop="itemCondition" content="NewCondition">
                <meta itemprop="url" content="<?php echo $url; ?>#<?php echo $one['sku']; ?>">
                <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>">
            </div>
		<?php }

		?>
   <!-- </div>-->
	<?php
	$radio['checkbox'] = <<<HTML
            <div class="col-md-3">
                <table class="products">
                    <tbody>
                        <tr class="">
                            <th>
                                <span class="form--checkbox form--border-none">
                                    <input %3\$s id="opt-%1\$s" class="wpp-variation wpp-check test" type="checkbox" value="%1\$s" data-valls='%5\$s' data-deff="%6\$s">
                                        <label for="opt-%1\$s" class="extra-large"></label>
                                    </span>
                                <h5>%2\$s</h5>
                            </th>
                        <td class="product-price"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
HTML;

	$radio['image'] = <<<HTML
            <div class="col-xl-2 col-lg-3 col-md-4 coll-hider">
   
                <label class="product-attribute__value" for="opt-%1\$s">
                    <span class="product-attribute__image">
                        <img src="%4\$s"
                             class="img-fluid" alt="%1\$s">
                    </span>
                    <span class="product-attribute__label">
                        <input type="radio" class="wpp-variation wpp-img-var"   id="opt-%1\$s" value="%1\$s" data-valls='%5\$s' data-deff="%6\$s">
                        %2\$s
                        <i class="icon small icon-check-mark"></i>
                    </span>
                </label>
            </div>
HTML;


	$array_opt_final = ( ! empty( $array_opt_3['top'] ) ? $array_opt_3['top'] : [] ) + ( ! empty( $array_opt_3['bottom'] ) ? $array_opt_3['bottom'] : [] );

	if ( empty( $array_opt_final ) ) {
		return '';
	}


	foreach ( $array_opt_final as $key => $vals ) {
		if ( ! empty( $vals["id"] ) ) :
			$label_text = wpp_br_options_name( $vals["id"] );
			if ( ! empty( $label_text ) ) {
				$html .= sprintf( '<h4 class="product-list__caption">%s:</h4>', $label_text );
			}
		endif;
		$html .= '<div class="row product-attribute product-attribute--gutter">';
		foreach ( $vals['fields'] as $key_id => $id ) {

			$img = get_the_post_thumbnail_url( $id['id'], 'full' );
			if ( ! empty( $img ) ) {
				$link = $img;
			} else {
				$link = '';
			}

			$opt_keys = [];
			foreach ( $keyer as $one_opt ) {

				if ( in_array( $id['id'], $one_opt ) ) :
					$opt_keys = array_merge( $opt_keys, $one_opt );
				endif;
			}

			$data_valls = json_encode( array_unique( $opt_keys ) );

			$html .= sprintf( $radio[ $id['type'] ], esc_attr( $id['id'] ), esc_attr( $id['name'] ), checked( $id['id'], $key_id, false ), $link, $data_valls, ( ! empty( $keyer_flag[ $id['id'] ] ) && (int) $keyer_flag[ $id['id'] ] === 1 ? true : false ) );

		}
		$html .= '</div>';
	}
	$html .= '</div>';

	return $html;
}

add_filter( 'manage_edit-product_columns', 'wpp_as_related_product_col' );

/**
 * Колонка вариаций
 *
 * @param $columns
 *
 * @return array
 */
function wpp_as_related_product_col( $columns ) {
	$new_columns        = ( is_array( $columns ) ) ? $columns : [];
	$new_columns['VAR'] = 'Create Variation';

	return $new_columns;
}

add_action( 'manage_product_posts_custom_column', 'wpp_as_related_product_col_data', 2 );

/**
 * Содержимое колонки вариаций
 *
 * @param $column
 */
function wpp_as_related_product_col_data( $column ) {
	global $post;
	if ( $column == 'VAR' ) {
		printf( '<a class="wpp-create-variants-cat" data-id="%s" href="javascript:void(0);">Create variants</a>', $post->ID );
	}
}


function wpp_get_opt_data( $id ) {
	$meta = get_post_meta( $id, 'wpp_as_products', true );

	return apply_filters( 'wpp_as_data_product', $meta, $id );
}


/**
 * Формирование рпций по варианту 3
 *
 * @param null $current_ID
 *
 * @return array
 */
function wpp_create_variants_array( $current_ID = null ) {

	if ( empty( $current_ID ) ) {
		global $post;
		$current_ID = $post->ID;
	}

	# Все записи опций
	$terms = get_the_terms( $current_ID, 'as_options' );

	# Выходные Массивы
	$ids_array = $title_array = $final_ids = $final_title = [];

	$a = 0;
	foreach ( $terms as $term ) {

		$posts = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'as_option',
			'tax_query'      => [
				'tx' => [
					'taxonomy'         => 'as_options',
					'field'            => 'id',
					'terms'            => $term->term_id,
					'include_children' => true
				]
			]
		] );

		#Верхний уровень
		$ids_array[ $a ][ $term->term_id ][ $term->term_id ][]   = $term->term_id;
		$title_array[ $a ][ $term->term_id ][ $term->term_id ][] = $term->name;

		if ( ! empty( $posts ) ) {

			foreach ( $posts as $one_post ) :
				$term_list = wp_get_post_terms( $one_post->ID, 'as_group', [ 'fields' => 'all' ] );

				#Записи разбитве по группам
				$ids_array[ $a ][ $term->term_id ][ $term_list[0]->term_id ][]   = $one_post->ID;
				$title_array[ $a ][ $term->term_id ][ $term_list[0]->term_id ][] = $one_post->post_title;

			endforeach;

		}

		$a ++;
	}

	foreach ( $title_array as $one_titles ) {
		$comb_titles = wpp_fr_array_combinate( array_shift( $one_titles ) );
		$final_title = array_merge( $final_title, $comb_titles );
	}

	foreach ( $ids_array as $one_ids ) {
		$comb_ids  = wpp_fr_array_combinate( array_shift( $one_ids ) );
		$final_ids = array_merge( $final_ids, $comb_ids );
	}

	$variants_array = array_combine( array_unique( $final_ids ), array_unique( $final_title ) );

	return $variants_array;
}


/**
 * Получение рпций по варианту 3
 *
 * @param null $current_ID
 *
 * @return array
 */
function wpp_get_as_options_array( $current_ID = null ) {
	if ( empty( $current_ID ) ) {
		global $post;
		$current_ID = $post->ID;
	}

	# Все записи опций
	$terms = get_the_terms( $current_ID, 'as_options' );

	# Выходные Массивы
	$title_array = [];

	$a = 0;
	if ( empty( $terms ) ) {
		return null;
	}
	foreach ( $terms as $term ) {

		$posts = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'as_option',
			'tax_query'      => [
				'tx' => [
					'taxonomy'         => 'as_options',
					'field'            => 'id',
					'terms'            => $term->term_id,
					'include_children' => true
				]
			]
		] );

		#Верхний уровень
		$title_array['top']['top']['fields'][ $a ]['id']   = $term->term_id;
		$title_array['top']['top']['fields'][ $a ]['name'] = $term->name;
		$title_array['top']['top']['fields'][ $a ]['type'] = get_term_meta( $term->term_id, 'option_type', true );

		if ( ! empty( $posts ) ) :

			$i = 0;
			foreach ( $posts as $one_post ) :
				$term_list = wp_get_post_terms( $one_post->ID, 'as_group', [ 'fields' => 'all' ] );
				$type_opt  = get_post_meta( $one_post->ID, 'option_type', true );

				#Записи разбитве по группам
				$title_array['bottom'][ $term_list[0]->term_id ]['id']                              = $term_list[0]->term_id;
				$title_array['bottom'][ $term_list[0]->term_id ]['name']                            = $term_list[0]->name;
				$title_array['bottom'][ $term_list[0]->term_id ]['fields'][ $one_post->ID ]['id']   = $one_post->ID;
				$title_array['bottom'][ $term_list[0]->term_id ]['fields'][ $one_post->ID ]['name'] = $one_post->post_title;
				$title_array['bottom'][ $term_list[0]->term_id ]['fields'][ $one_post->ID ]['type'] = empty( $type_opt ) ? 'image' : $type_opt;
				$i ++;
			endforeach;

		endif;

		$a ++;
	}

	return $title_array;
}