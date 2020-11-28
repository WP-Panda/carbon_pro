<?php

/**
 * @package WPP Framework\wpp-woocommerce
 *
 * @version 1.0.3
 */
class WPP_Additional_Sales {

	/**
	 * Prefix
	 *
	 * @var string
	 */
	public static $pref = 'wpp_as_';

	public static function init() {
		add_action( 'woocommerce_before_calculate_totals', [
			__CLASS__,
			'cart_item_recalculate_price'
		] );
		add_filter( 'woocommerce_add_cart_item_data', [
			__CLASS__,
			'cr_woo_additional_services_selection'
		], 1000, 3 );
		add_filter( 'wpp_checkout_additional', [
			__CLASS__,
			'cr_woo_additional_services_cart_item_name'
		], 10000, 3 );
		add_filter( 'wpp_checkout_additional_pdf', [
			__CLASS__,
			'cr_woo_additional_services_cart_item_name_pdf'
		], 10000, 3 );
		add_filter( 'woocommerce_cart_item_name', [
			__CLASS__,
			'cr_woo_additional_services_cart_item_name_mame'
		], 10, 3 );

		add_filter( 'woocommerce_cart_item_name_pdf', [
			__CLASS__,
			'cr_woo_additional_services_cart_item_name_mame_pdf'
		], 10, 3 );

		add_filter( 'woocommerce_cart_item_name_mini', [
			__CLASS__,
			'cr_woo_additional_services_cart_item_name_mini'
		], 10, 3 );
		add_action( 'woocommerce_cart_calculate_fees', [
			__CLASS__,
			'cr_woo_additional_services_surcharge'
		] );
		add_action( 'wpp_cat_additional_fields', [
			__CLASS__,
			'render_fields_additional'
		] );
		add_filter( 'woocommerce_get_cart_item_from_session', [
			__CLASS__,
			'add_additional_too_order'
		], 1, 3 );
		add_action( 'woocommerce_add_order_item_meta', [
			__CLASS__,
			'wdm_add_values_to_order_item_meta'
		], 1, 2 );
		add_action( 'woocommerce_admin_order_data_after_order_details', [
			__CLASS__,
			'field_display_admin_order_meta'
		], 10, 1 );
		add_filter( 'woocommerce_calculate_totals', [
			__CLASS__,
			'cart_subtotal'
		], 10, 1 );
		add_filter( 'wpp_fr_post_meta_boxes_args', [
			__CLASS__,
			'add_dep_fields_setting'
		] );

		add_action( 'wpp_render_dept_post_fields', [
			__CLASS__,
			'render_dept_field_in_product'
		] );

		add_filter( 'woocommerce_cart_item_subtotal', [
			__CLASS__,
			'cart_item_subtotal'
		], 99, 3 );
		add_action( 'init', [
			__CLASS__,
			'require_files'
		], 5 );
	}

	public static function require_files() {
		$files = [
			#__DIR__ . DIRECTORY_SEPARATOR . 'wpp-additional-sales-options.php',
			__DIR__ . DIRECTORY_SEPARATOR . 'wpp-additional-sales-tax.php',
			__DIR__ . DIRECTORY_SEPARATOR . 'wpp-additional-sales-options.php'
		];

		foreach ( $files as $file ) {
			require_once( $file );
		}
	}

	/**
	 * Поля дополнительных продаж
	 */
	public static function wpp_additional_fields_array() {

		$dependent = self::dependent_fields();

		/**
		 * [] - массив с данными полей
		 * Параметры массива
		 *
		 * @param string        field               - Дополнительный ID         (необязательно)
		 * @param string        name                - Название поля
		 * @param integer       min                 - Минимальное значение      (необязательно)
		 * @param integer       max                 - Максимальное значение     (необязательно)
		 * @param string        short_name          - ключ для массива
		 * @param bool          sales               - распространяется ли скидка (необязательно)
		 * @param integer|float price               - стоимость
		 * @param string        field_type          - тип поля
		 * @param array|bool    dependent_fields    - зависимости                 (необязательно)
		 *
		 * $dependent - массив с данными полей зависимостей
		 *
		 * @see  dependent_fields()
		 *
		 */

		#пример
		/*
		$array[ $pref . 'gala_dinner' ]     = [
			'field'            => $pref . 'gala_dinner'
			'name'             => __( 'Industrial day ticket (with Gala dinner)' ),
			'min'              => 0,
			'max'              => 1,
			'short_name'       => 'gala_dinner',
			'sales'            => false,
			'price'            => 19000,
			'field_type'       => 'checkbox',
			'dependent_fields' => false
		];
		*/


		$fields = apply_filters( 'wpp_ad_sale_fields_array', [], $dependent );

		#wpp_dump($fields);
		return $fields;
	}

	/**
	 * Поля зависимостей для доппродаж
	 */
	public static function dependent_fields() {

		/**
		 * [] - массив с данными полей
		 * Параметры массива
		 *
		 * @param string        label               - Заголовок
		 * @param string        type                - тип поля
		 * @param string        required            - обязательное или нет
		 * @param bool          sales               - распространяется ли скидка (необязательно)
		 *
		 */

		#пример
		/*
		$array[ $pref . 'star' ] = [
			'name'         => [
				'label'    => __( 'Surname, name, patronymic (in accordance with an identity document)' ),
				'required' => true
			]
		]
		*/

		return apply_filters( 'wpp_ad_sale_dependent_fields', [] );

	}


	/**
	 * Добавление в корзину
	 *
	 * @param $cart_item_data
	 * @param $product_id
	 * @param $variation_id
	 *
	 * @return mixed
	 */

	public static function cr_woo_additional_services_selection( $cart_item_data, $product_id, $variation_id ) {

		## тут херня для того, что-бы каждый продукт добалялся отдельно
		## если только он не принадлежит к дополнительным товарам кидаемым в корзину из корзины
		#$str = get_theme_mod( 'cart_prod' );

		#if ( ! empty( $str ) ):
		#	$array = explode( ',', $str );
		#	if ( empty( $array ) || ( ! empty( $array ) && ! in_array( $product_id, $array ) ) ) :
		#		$cart_item_data['wpp_timestamp'] = time();
		#	endif;
		#endif;

		$array   = self::wpp_additional_fields_array();
		$array[] = [ 'field' => 'wpp_add_fields' ];
		$array[] = [ 'field' => 'wpp_add_variants' ];
		foreach ( $array as $key => $one_field ) :
			if ( ! empty( $_REQUEST[ $one_field['field'] ] ) ) {
				$cart_item_data[ $one_field['field'] ] = $_REQUEST[ $one_field['field'] ];
			}
		endforeach;

		return $cart_item_data;
	}


	/**
	 * Изменение тайтла в корзине
	 */
	public static function cr_woo_additional_services_cart_item_name_mame( $string, $order_item, $key ) {

		if ( ! empty( $order_item['wpp_add_variants'] ) ) {

			$meta      = wpp_get_opt_data( $order_item['product_id'] );
			$v         = array_search( $order_item['wpp_add_variants'], array_column( $meta, 'key' ) );
			$meta_term = explode( '-', $meta[ $v ]['key'] );
			$url       = null;
			foreach ( $meta_term as $one ) {
				$url .= get_the_post_thumbnail( $one, [
					60,
					45,
					'bfi_thumb' => true
				], [ 'class' => 'wpp-cart-att-img' ] );
			}
			$string = sprintf( '<span class="wpp-cart-title">%s</span>%s', $string, $url );

			#$string = sprintf( '%s%s', $string, $url );

			return $string;
		}


	}

	/**
	 * Изменение тайтла в корзине
	 */
	public static function cr_woo_additional_services_cart_item_name_mame_pdf( $string, $order_item, $key ) {

		if ( ! empty( $order_item['wpp_add_variants'] ) ) {

			$meta      = wpp_get_opt_data( $order_item['product_id'] );
			$v         = array_search( $order_item['wpp_add_variants'], array_column( $meta, 'key' ) );
			$meta_term = explode( '-', $meta[ $v ]['key'] );
			$url       = null;
			foreach ( $meta_term as $one ) {
				foreach ( $meta_term as $one ) {
					$url .= '<div>' . get_the_post_thumbnail( $one, [
							60,
							45,
							'bfi_thumb' => true
						], [ 'class' => 'wpp-cart-att-img' ] ) . '</div>';
				}
			}
			$string = sprintf( '<span class="wpp-cart-title">%s</span>%s', $string, $url );

			return $string;
		}


	}

	public static function cr_woo_additional_services_cart_item_name( $string, $order_item, $key ) {
		#wpp_dump($order_item['product_id']);

		$array = self::wpp_additional_fields_array();
		#wpp_dump($array);
		$string = '';
		foreach ( $array as $key => $val ) :
			if ( ! empty( $order_item[ $key ] ) ) {
				$price   = get_post_meta( $order_item['product_id'], $key, true );
				$price_n = ! empty( $price ) ? $price : $val['price'];

				$price_n = wpp_return_custom_price_cart( (int) $price_n, $order_item['product_id'] );

				$string .= '<div class="cart-item cart_item">';
				if ( $val['name'] == 'Painting' ) {
					$src     = get_template_directory_uri() . '/assets/img/icons/pa.svg';
					$classer = ' class="svg"';
				} elseif ( $val['field'] === 'assembly' ) {
					$src     = get_template_directory_uri() . '/assets/img/icons/as.svg';
					$classer = ' class="svg"';
				} else {
					$src     = bfi_thumb( wc_placeholder_img_src( [
						160,
						90
					] ), [
						160,
						90,
						'crop' => true
					] );
					$classer = '';
				}

				$string .= '<img' . $classer . ' src="' . $src . '">';

				$string .= '<div class="cart-item--title-description-amount-container">';
				$string .= '<div class="cart-item-amount">';
				$string .= '<div class="quantity">';
				$string .= '<a href="javaScript:void(0)"><i class="icon icon-minus small"></i></a>';
				$string .= '<input data-type="' . $key . '" class="form--text form--border input-small qty" name="cart[' . $order_item['key'] . '][qty]" value="' . $order_item[ $key ] . '">';
				$string .= '<a href="javaScript:void(0)"><i class="icon icon-plus small"></i></a>';
				$string .= '</div>';
				$string .= '</div>';
				$string .= '<div class="cart-item-title">';
				$string .= str_replace( ' -', '', $val['name'] );
				$string .= '</div>';
				$string .= '</div>';
				$string .= '<div class="cart-item--number-price-container">';
				$string .= '<div class="cart-item-price">';
				$string .= wpp_wc_price( (int) $order_item[ $key ] * $price_n );
				$string .= '</div>';
				$string .= '</div>';
				$string .= '</div>';
			}
		endforeach;

		return $string;
	}


	public static function cr_woo_additional_services_cart_item_name_pdf( $string, $order_item, $key ) {
		#wpp_dump($order_item['product_id']);

		$array = self::wpp_additional_fields_array();
		#wpp_dump($array);
		$string = '';
		foreach ( $array as $key => $val ) :
			if ( ! empty( $order_item[ $key ] ) ) {
				$price   = get_post_meta( $order_item['product_id'], $key, true );
				$price_n = ! empty( $price ) ? $price : $val['price'];

				$price_n = wpp_return_custom_price_cart( (int) $price_n, $order_item['product_id'] );

				$string .= '<div class="cart-item cart_item">';
				$string .= '<div class="catr-item-img-bloscs">';
				if ( $val['name'] == 'Painting' ) {
					$src     = get_template_directory_uri() . '/assets/img/icons/as.png';
					$classer = ' class="svg"';
				} elseif ( $val['field'] === 'assembly' ) {
					$src     = get_template_directory_uri() . '/assets/img/icons/pa.png';
					$classer = ' class="svg"';
				} else {
					$src     = bfi_thumb( wc_placeholder_img_src( [
						160,
						90
					] ), [
						160,
						90,
						'crop' => true
					] );
					$classer = '';
				}

				$string .= '<img' . $classer . ' src="' . $src . '">';
				$string .= '</div>';
				$string .= '<div class="cart-item--title-description-amount-container">';
				$string .= '<div class="cart-item-amount">';

				$string .= '</div>';
				$string .= '<div class="cart-item-title">';
				$string .= str_replace( ' -', '', $val['name'] ) . ' - ' . $order_item[ $key ];;
				$string .= '</div>';
				$string .= '</div>';
				$string .= '<div class="cart-item--number-price-container">';
				$string .= '<div class="cart-item-number">';
				$string .= '-' /*(int) $order_item[ $key ]*/
				;
				$string .= '</div>';
				$string .= '<div class="cart-item-price">';
				$string .= wpp_wc_price( (int) $order_item[ $key ] * $price_n, array( 'pdf' => true ) );
				$string .= '</div>';
				$string .= '</div>';
				$string .= '</div>';
			}
		endforeach;

		return $string;
	}


	public static function cr_woo_additional_services_cart_item_name_mini( $string, $order_item, $key ) {
		#wpp_dump('fffffffffffffffffffffffffffffffffff');
		#wpp_dump($order_item['product_id']);

		$array = self::wpp_additional_fields_array();
		#wpp_dump($array);
		$string = '';
		foreach ( $array as $key => $val ) :
			#wpp_dump( $val );
			if ( ! empty( $order_item[ $key ] ) ) {
				$price   = get_post_meta( $order_item['product_id'], $key, true );
				$price_n = ! empty( $price ) ? $price : $val['price'];
				$string  .= '<div class="row woooo row__section-padding border-bottom">';
				$string  .= '<div class="col-12 col-sm-8">';
				$string  .= '<strong>';
				$string  .= str_replace( '-', '', $val['name'] );
				$string  .= '</strong>';
				$string  .= '</div>';
				$string  .= '<div class="col-8 col-sm-2 cart-layer__number-items">';
				$string  .= $order_item[ $key ];
				$string  .= '</div>';
				$string  .= '<div class="col-4 col-sm-2 text-right" style="font-size: 18px>';
				#wpp_console(__FILE__ . ':::' . __LINE__);
				$string .= wpp_wc_price( apply_filters( 'wpp_dep_field_pice', $price_n, $order_item['product_id'] ) );
				$string .= '</div>';
				$string .= '</div>';
			}
		endforeach;

		return $string;
	}

	/**
	 * Изменение цены товара
	 *
	 * @param $cart_object
	 */
	public static function cart_item_recalculate_price( $cart_object ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		foreach ( $cart_object->get_cart() as $hash => $value ) {

			if ( ! empty( $value['wpp_add_variants'] ) ) {

				$v = wpp_fr_get_variant_by( $value['product_id'], $value['wpp_add_variants'], 'key', 'price' );
				#wpp_console( (float) $v );
				$value['data']->set_price( (float) $v );
			}


		}

	}


	/**
	 * Изменение подитога товара
	 *
	 * @param $subtotal
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return string
	 */
	public static function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {


		$add_price = 0;
		$array     = self::wpp_additional_fields_array();

		foreach ( $array as $key => $one_field ) :

			//wpp_d_log( $one_field );

			if ( empty( $cart_item[ $one_field['field'] ] ) ) {
				continue;
			}

			if ( ! empty( $cart_item[ $one_field['field'] ] && ! empty( $one_field['price'] ) ) ) {
				$add_price = $add_price + ( (int) $cart_item[ $one_field['field'] ] * (int) $one_field['price'] );
				wpp_dump( $cart_item );
				wpp_dump( $one_field );
			}
		endforeach;

		$sub = wpp_return_ads_price_opt( $cart_item['line_subtotal'] + $add_price, $cart_item['product_id'] );

		#wpp_console( __FILE__ . ":::" . __LINE__ );

		return wpp_wc_price( $sub );
	}


	/**
	 * Подшив
	 */
	public static function cr_woo_additional_services_surcharge() {
		global $woocommerce;
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$fields = self::wpp_additional_fields_array();
		$count  = count( $fields );

		$n = 1;
		while ( $n <= $count ) :
			$data_{$n} = [];
			$n ++;
		endwhile;

		foreach ( $woocommerce->cart->cart_contents as $cart_item ) {

			$n = 1;
			foreach ( $fields as $one_field ) :
				$price   = get_post_meta( $cart_item['product_id'], $one_field['field'], true );
				$price_n = ! empty( $price ) ? $price : ( ! empty( $one_field['price'] ) ? $one_field['price'] : 0 );
				if ( ! empty( $cart_item[ $one_field['field'] ] ) ) {
					$data_{$n}[] = (int) $cart_item[ $one_field['field'] ] * (int) $price_n;
				}
				$n ++;
			endforeach;

		}

		$n = 1;
		foreach ( $fields as $one_field ) :

			if ( ! empty( $data_{$n} ) ) :
				$surcharge = array_sum( $data_{$n} );

				if ( ! empty( $surcharge ) ) {
					$woocommerce->cart->add_fee( $one_field['name'], $surcharge, true, '' );
				}

			endif;

			$n ++;
		endforeach;

	}

	/**
	 * Подшив
	 */
	public static function cart_subtotal( $cart_object ) {
		global $woocommerce;
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$fields = self::wpp_additional_fields_array();
		$count  = count( $fields );

		$n = 1;
		while ( $n <= $count ) :
			$data_{$n} = [];
			$n ++;
		endwhile;
		$surcharge = 0;
		foreach ( $woocommerce->cart->cart_contents as $cart_item ) {

			$n = 1;
			foreach ( $fields as $one_field ) :
				$price   = get_post_meta( $cart_item['product_id'], $one_field['field'], true );
				$price_n = ! empty( $price ) ? $price : ( ! empty( $one_field['price'] ) ? $one_field['price'] : 0 );
				if ( ! empty( $cart_item[ $one_field['field'] ] ) ) {
					$surcharge = $surcharge + wpp_return_ads_price_opt( (int) $cart_item[ $one_field['field'] ] * (int) $price_n, $cart_item['product_id'] );
					#wpp_console( __FILE__ . ":::" . __LINE__ );
				}
				$n ++;
			endforeach;

		}

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( ! WC()->cart->is_empty() ):
			## Displayed subtotal (+10%)
			// $cart_object->subtotal *= 1.1;

			## Displayed TOTAL (+10%)
			// $cart_object->total *= 1.1;

			## Displayed TOTAL CART CONTENT (+10%)
			$cart_object->subtotal = $cart_object->subtotal + $surcharge;

		endif;
	}

	/**
	 * Поля зависимости
	 *
	 * @param $fields - массив полей
	 *
	 * @return string
	 */
	public static function dept_fields( $fields ) {

		$html = <<<HTML
            <div class="form-group">
                <label for="%1\$s">%2\$s</label>
                <input type="text" class="form-control" name='wpp_add_fields[%1\$s]' id="%1\$s"%3\$s>
            </div>
HTML;

		$out = '';
		foreach ( $fields as $field => $one ) {
			$out .= sprintf( $html, $field, $one['label'], ! empty( $one['required'] ) ? ' data-required="true"' : '' );
		}

		return $out;

	}

	/**
	 * Отрисовка полей
	 */
	public static function render_fields_additional() {
		$array = self::wpp_additional_fields_array();

		$dept_fields = '';
		/*$html        = <<<HTML
			<div class="checkbox">
				<label class="wpp-label-ads" for="%1\$s" data-dept="%4\$s">
					<input type="checkbox" class="wpp-check-ads" name="%1\$s" id="%1\$s" value="1" data-price="%5\$s">
					<span class="wpp-add-sales-title">%2\$s</span>
					<span class="wpp-add-sales-price">%3\$s</span>
				</label>
			</div>
HTML;*/

		$html     = <<<HTML

		<table class="products">
		    <tbody>
                <tr>
                    <th>
                        <span class="form--checkbox form--border-none">
                            <input type="checkbox" class="wpp-check-ads" name="%1\$s" id="%1\$s" value="1" data-price="%5\$s"%6\$s>
                            <label class="extra-large" for="%1\$s" data-dept="%4\$s"></label>
                            <h5>%2\$s</h5>
                        </span>
                    </th>
                    <td class="product-price">%3\$s</td>
                </tr>
            </tbody>
        </table>       
HTML;
		$geo_flag = wpp_fr_is_russia();
		$user_lng = get_locale();
		if ( ! empty( $array ) ) : ?>
            <!-- <ul class="wpp-additional-sales-list">-->
            <div class="product-group">
                <!--<h5 class="product-group__caption">Please choose your optional articles:</h5>-->
				<?php

				global $post;
				foreach ( $array as $key => $one_field ) :
					$price = get_post_meta( $post->ID, $key, true );
					if ( empty( $price ) ) {
						continue;
					}
					if ( $key === 'assembly' && ( $geo_flag === false || $user_lng !== 'ru_RU' ) ) {
						$checked = '';
					} else {
						$checked = ' checked="checked"';
					}

					$price_n   = ! empty( $price ) ? $price : $one_field['price'];
					$data_dept = 'false';
					if ( ! empty( $one_field['dependent_fields'] ) && is_array( $one_field['dependent_fields'] ) ) {
						$dept        = self::dept_fields( $one_field['dependent_fields'] );
						$dept_fields .= sprintf( '<div class="wpp_dept_fields">%s</div>', $dept );
						$data_dept   = 'true';
					}
					#wpp_console(__FILE__ . ':::' . __LINE__);
					printf( $html, $one_field['field'], $one_field['name'], wc_price( apply_filters( 'wpp_dep_field_pice', $price_n, $post->ID ) ), $data_dept, ! empty( $one_field['price'] ) ? $one_field['price'] : 0, $checked );
				endforeach;
				echo $dept_fields;
				?>
            </div>
		<?php endif;
	}

	/**
	 * Передача подшива из корзины в ордер сессию
	 *
	 * @param $item
	 * @param $values
	 * @param $key
	 *
	 * @return mixed
	 */
	public static function add_additional_too_order( $item, $values, $key ) {

		if ( ! empty( $values['wpp_add_fields'] ) ) {
			$item['wpp_add_fields'] = $values['wpp_add_fields'];
		}

		return $item;
	}


	/**
	 * @param $item_id
	 * @param $values
	 *
	 * @throws Exception
	 */
	public static function wdm_add_values_to_order_item_meta( $item_id, $values ) {
		global $woocommerce, $wpdb;
		$user_custom_values = $values['wpp_add_fields'];
		if ( ! empty( $user_custom_values ) ) {
			wc_add_order_item_meta( $item_id, 'wpp_add_fields', $user_custom_values );
		}
	}


	/**
	 * Display field value on the order edit page
	 *
	 * @param $order
	 *
	 * @throws Exception
	 */
	public static function field_display_admin_order_meta( $order ) {

		foreach ( $order->get_items() as $item_id => $item ) {

			$custom_field = wc_get_order_item_meta( $item_id, 'wpp_add_fields', true );

			if ( is_array( $custom_field ) ) {
				$args = self::dependent_fields();

				foreach ( $custom_field as $key => $val ) {
					printf( '<p><b class="add-title">%s</b>:   %s</p>', $args[ self::$pref . 'star' ][ $key ]['label'], $val );
				}
			} else {
				echo $custom_field;
			}
		}

	}


	/**
	 *  Вывод настрое полей которые нобходимо заполнить при продаже товара
	 * в админку товра
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public static function add_dep_fields_setting( $meta_boxes ) {

		/*$fields_array = apply_filters( 'wpp_fr_ad_sales_dept_product_fields', [
			'id'          => self::$pref . 'add_product_data',
			'type'        => 'group',
			'collapsible' => true,
			'save_state'  => true,
			'clone'       => true,
			'sort_clone'  => true,
			'group_title' => [ 'field' => 'title' ],
			'fields'      => [
				[
					'name' => __( 'Group', 'wpp-fr' ),
					'id'   => 'key',
					'type' => 'text'
				],
				[
					'name' => __( 'Option', 'wpp-fr' ),
					'id'   => 'key',
					'type' => 'text'
				],
				[
					'name' => __( 'Name', 'wpp-fr' ),
					'id'   => 'title',
					'type' => 'text'
				],
				[
					'name' => __( 'Price', 'wpp-fr' ),
					'id'   => 'title',
					'type' => 'text'
				],
				[
					'name' => __( 'Availability', 'wpp-fr' ),
					'id'   => 'title',
					'type' => 'text'
				],
			]
		] );

		$meta_boxes[] = [
			'title'      => __( 'Options', 'wpp-fr' ),
			'post_types' => 'product',
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => [
				$fields_array
			]
		];*/

		$meta_boxes[] = [
			'title'      => __( 'Additional data fields setting', 'wpp-fr' ),
			'post_types' => 'product',
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => [
				[
					'name' => __( 'Paint Price', 'wpp-fr' ),
					'id'   => 'paint',
					'type' => 'text'
				],
				[
					'name' => __( 'Installation Price', 'wpp-fr' ),
					'id'   => 'assembly',
					'type' => 'text'
				]
			]
		];

		return $meta_boxes;
	}

	/**
	 * Формирование массива полей которые нобходимо запорлнить при продаже товара
	 *
	 * @return bool|mixed
	 */
	public static function get_product_dep_fields_array() {
		global $post;
		$fields_array = get_post_meta( $post->ID, self::$pref . 'add_product_data', true );
		#wpp_dump( $fields_array );
		if ( ! empty( $fields_array ) ) :

			$depend_array = [];

			foreach ( $fields_array as $field ) {
				if ( empty( $field['key'] ) ) {
					break;
				}
				$depend_array[ $field['key'] ] = [
					'label'    => $field['title'],
					'required' => ! empty( $field['required'] ) ? true : false
				];
			}

		endif;

		return ! empty( $depend_array ) ? $depend_array : false;
	}

	/**
	 * Отрисовка полей заввисимостей которые необходимо заполнить при продаже товара
	 */
	public static function render_dept_field_in_product() {
		global $post;
		$dept_fields = '';
		$fields      = self::get_product_dep_fields_array();
		if ( ! empty( $fields ) ) :
			$dept        = self::dept_fields( $fields );
			$dept_fields = sprintf( '<div class="wpp_dept_product_fields" data-depth="%d">%s</div>', $post->ID, $dept );
		endif;

		echo $dept_fields;
	}
}


WPP_Additional_Sales::init();