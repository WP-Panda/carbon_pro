<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
global $product;

$meta       = get_post_meta( get_queried_object_id(), 'bundle_package', false );
$price      = wpp_br_bundle_price( $meta, get_queried_object_id() );
$sale       = (int) get_post_meta( get_queried_object_id(), 'bundle_sale', true );
$sale_coeff = ! empty( $sale ) ? 1 - ( $sale / 100 ) : 1;

$html = '';
if ( ! empty( $meta ) ) {
	$array_for_options = $all_options = [];
	foreach ( $meta as $post_id ) {

		$radio = [];


		$var = wpp_get_opt_data( $post_id );

		$out      = $keyer = $keyer_flag = [];
		$flag_def = false;

		foreach ( $var as $key => $val ) :


			$out[ $val['key'] ]                   = $val;
			$out[ $val['key'] ]['opt_price_html'] = wc_price( $sale_coeff * $val['opt_price'] );
			$out[ $val['key'] ]['opt_price']      = $sale_coeff * $val['opt_price'];
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

		$html        .= sprintf( '<div class="wpp-as-options-flag" data-product="' . $post_id . '" data-options=\'%s\'>', json_encode( $out ) );
		$array_opt_3 = wpp_get_as_options_array( (int) $post_id );

		$radio['checkbox'] = <<<HTML
            <div class="col-md-3">
                <table class="products">
                    <tbody>
                        <tr class="">
                            <th>
                                <span class="form--checkbox form--border-none">
                                    <input %3\$s id="opt-%1\$s-%7\$s" class="wpp-variation wpp-check" type="checkbox" value="%1\$s" data-valls='%5\$s' data-deff="%6\$s">
                                        <label for="opt-%1\$s-%7\$s" class="extra-large"></label>
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
   
                <label class="product-attribute__value" for="opt-%1\$s-%7\$s">
                    <span class="product-attribute__image">
                        <img src="%4\$s"
                             class="img-fluid" alt="%1\$s">
                    </span>
                    <span class="product-attribute__label">
                        <input type="radio" class="wpp-variation wpp-img-var"   id="opt-%1\$s-%7\$s" value="%1\$s" data-valls='%5\$s' data-deff="%6\$s">
                        %2\$s
                        <i class="icon small icon-check-mark"></i>
                    </span>
                </label>
            </div>
HTML;


		$array_opt_final                   = ( ! empty( $array_opt_3['top'] ) ? $array_opt_3['top'] : [] ) + ( ! empty( $array_opt_3['bottom'] ) ? $array_opt_3['bottom'] : [] );
		$all_options['top'][ $post_id ]    = ( ! empty( $array_opt_3['top'] ) ? $array_opt_3['top'] : [] );
		$all_options['bottom'][ $post_id ] = ( ! empty( $array_opt_3['bottom'] ) ? $array_opt_3['bottom'] : [] );

		$html .= '</div>';


	}
}

echo $html;


$_final_final_top = $_final_final_bottom = $final_final_unique_t = $final_final_unique_b = [];
foreach ( $all_options['top'] as $optiong ) {
	foreach ( $optiong as $one ) {

		foreach ( $one['fields'] as $field ) {
			if ( ! in_array( $field['id'], $final_final_unique_t ) ) {
				$_final_final_top['top']['fields'][$field['id']] = $field;
				$final_final_unique_t[]              = $field['id'];

			}
		}
	}
}


foreach ( $all_options['bottom'] as $optiong ) {
	foreach ( $optiong as $one ) {

		if ( empty( $final_final_unique_b[ $one['id'] ] ) ) {
			$final_final_unique_b[ $one['id'] ]        = [];
			$_final_final_bottom[ $one['id'] ]['id']   = $one['id'];
			$_final_final_bottom[ $one['id'] ]['name'] = $one['name'];
		}

		foreach ( $one['fields'] as $field ) {

			if ( ! in_array( $field['id'], $final_final_unique_b[ $one['id'] ] ) ) {
				$_final_final_bottom[ $one['id'] ]['fields'][] = $field;
				$final_final_unique_b[ $one['id'] ][]          = $field['id'];

			}
		}
	}
}


$all_options_array = $_final_final_top + $_final_final_bottom;

$html   = '<div id="wpp-bundle-vars">';
$hidden = 1;
foreach ( $all_options_array as $key => $vals ) {

	$class = $hidden === 1 ? ' wpp_hidden' : '';
	#$class = '';
	if ( ! empty( $vals["id"] ) ) :
		$label_text = wpp_br_options_name( $vals["id"] );
		if ( ! empty( $label_text ) ) {
			$html .= sprintf( '<h4 class="product-list__caption">%s:</h4>', $label_text );
		}
	endif;
	$html  .= sprintf( '<div class="row product-attribute product-attribute--gutter%s">', $class );
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


		if ( (int)$id === CARB ) {
			$ch = checked( 1, 1, false );
		} else {
			$ch = checked( $id['id'], $key_id, false );
		}

		$html .= sprintf( $radio[ $id['type'] ], esc_attr( $id['id'] ), esc_attr( $id['name'] ), $ch, $link, $data_valls, ( ! empty( $keyer_flag[ $id['id'] ] ) && (int) $keyer_flag[ $id['id'] ] === 1 ? true : false ), mt_rand() );

	}
	$html .= '</div>';
	$hidden ++;
}
$html .= '</div>';


echo $html;
?>

<section>

    <div class="text-right product-price" style="font-size: 21px">
        <p class="cart-item-number wpp_scu"></p>
		<?php //wpp_br_create_time_info( $product->get_id() ); ?>
        <p class="wpp-bundle-price <?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><span class="wpp-b-total"><?php echo wpp_br_lng( 'package_total' ); ?></span><?php echo wc_price( $price ); ?></p>
    </div>

</section>
<?php #do_action( 'wpp_cat_additional_fields' ); ?>
<div class="product-group">
    <!--<h5 class="product-group__caption">Please choose your optional articles:</h5>-->
	<?php
	$assembly = get_post_meta( get_queried_object_id(), 'assembly', true );
	$paint    = get_post_meta( get_queried_object_id(), 'paint', true );
	?>
	<?php if ( !empty( $paint ) ) { ?>
        <table class="products">
            <tbody>
            <tr>
                <th>
                <span class="form--checkbox form--border-none">
                    <input type="checkbox" class="wpp-check-ads" name="b_paint" id="paint" value="1" data-price="0"
                           checked="checked">
                            <label class="extra-large" for="paint" data-dept="false"></label>
                            <h5><?php echo wpp_br_lng( 'paint' );?></h5>
                 </span>
                </th>
                <td class="product-price">
					<?php echo wc_price( wpp_return_ads_price_opt( $paint, 0 ) );#wpp_console( __FILE__ . ":::" . __LINE__ ); ?>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } ?>
	<?php if ( !empty( $assembly ) ) {
	    $loc = get_locale();
	    ?>
        <table class="products">
            <tbody>
            <tr>
                <th>
                <span class="form--checkbox form--border-none">
                    <input type="checkbox" class="wpp-check-ads" name="b_assembly" id="assembly" value="1"
                           data-price="0" <?php checked($loc, 'ru_RU'); ?>>
                    <label class="extra-large" for="assembly" data-dept="false"></label>
                    <h5><?php echo wpp_br_lng( 'assem' );?></h5>
                </span>
                </th>
                <td class="product-price">
					<?php echo wc_price( wpp_return_ads_price_opt( $assembly, 0 ) ); #wpp_console( __FILE__ . ":::" . __LINE__ );?>
                </td>
            </tr>
            </tbody>
        </table>
	<?php } ?>
</div>

<div class="form--row d-flex justify-content-end">
    <!--<button type="submit" name="add-to-cart" value="<?php /*echo esc_attr( $product->get_id() ); */ ?>"
                        class="single_add_to_cart_button button alt"><?php /*echo esc_html( $product->single_add_to_cart_text() ); */ ?></button>-->

    <a href="<?php echo wpp_br_bundle_cart_link( $meta, $product->get_id() ); ?>"
       class="wpp-add-cart-bundle form--button-gray single_add_to_cart_button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
</div>
<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>