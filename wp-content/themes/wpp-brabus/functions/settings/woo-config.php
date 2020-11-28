<?php

function reregister_taxonomy_pro_tags( $array ) {
	$array['hierarchical'] = true;

	return $array;
}

/**
 * Set WooCommerce image dimensions upon theme activation
 */
// Remove each style one by one
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	if ( ! is_cart() ) :
		unset( $enqueue_styles['woocommerce-general'] );    // Remove the gloss
		unset( $enqueue_styles['woocommerce-layout'] );        // Remove the layout
		unset( $enqueue_styles['woocommerce-smallscreen'] );    // Remove the smallscreen optimisation

		return $enqueue_styles;
	endif;
}

if ( ! function_exists( 'wpp_woocommerce_template_loop_category_link_open' ) ) {
	/**
	 * Insert the opening anchor tag for categories in the loop.
	 *
	 * @param int|object|string $category Category ID, Object or String.
	 */
	function wpp_woocommerce_template_loop_category_link_open( $category ) {
		echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '" class="grid-teaser__link-detail">';
	}
}

if ( ! function_exists( 'wpp_woocommerce_subcategory_thumbnail' ) ) {

	/**
	 * Show subcategory thumbnails.
	 *
	 * @param mixed $category Category.
	 */
	function wpp_woocommerce_subcategory_thumbnail( $category ) {

		$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
			$image = $image[0];
		} else {
			$image = wc_placeholder_img_src();
		}

		if ( $image ) {
			$image = str_replace( ' ', '%20', $image );
		}

		wpp_get_template_part( 'templates/media/sub-cat-thumb', [ 'img' => $image, 'cat' => $category ] );

	}
}

/**
 * Get HTML for a gallery image.
 *
 * Woocommerce_gallery_thumbnail_size, woocommerce_gallery_image_size and woocommerce_gallery_full_size accept name
 * based image sizes, or an array of width/height values.
 *
 * @since 3.3.2
 *
 * @param int $attachment_id Attachment ID.
 * @param bool $main_image Is this the main image or a thumbnail?.
 *
 * @return string
 */
function wpp_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {

	$params = [
		'water_mark' => true,
		'retina'     => true,
		'lazy'       => false,
		'sizes'      => [
			[ 'height' => 780 ]
		],
		'wrap'       => '<div class="carousel-cell carousel-color-dark">%s</div>'
	];

	$url = wp_get_attachment_image_src( $attachment_id, 'full' );

	return e_wpp_fr_image_html( $url[0], $params, true );
}


/**
 * Show the subcategory title in the product loop.
 *
 * @param object $category Category object.
 */
function wpp_woocommerce_template_loop_category_title( $category ) {

	?>
    <h4 class="grid-headline-icon">
		<?php
		echo esc_html( $category->name );
		?>
    </h4>
	<?php
}


function wpp_woo_sub_cat_list( $category ) {
	$children = get_term_children( $category->term_id, 'product_cat' ); ?>
    <div class="grid-teaser-details structured-content">
        <table class="structured-table">
            <tbody>
			<?php if ( ! empty( $children ) ) :

				$body_types = wpp_woo_car_body_tipes();
				?>

				<?php foreach ( $children as $child ) :
				$term = get_term_by( 'id', $child, 'product_cat' );
				if ( $term->parent !== $category->term_id ) {
					continue;
				}
				$body_type = get_term_meta( $term->term_id, 'body_type', true );
				$type      = ! empty( $body_type ) && ! empty( $body_types[ $body_type ] ) ? $body_types[ $body_type ] : null;
				?>
                <tr>
                    <td class="value" colspan="2">
                        <a class="grid-teaser__link-detail"
                           href="<?php echo get_term_link( $term->term_id, $term->taxonomy ); ?>">
                            <strong><?php echo $type; ?></strong>&nbsp;<?php echo $term->name; ?>
                        </a>
                    </td>
                </tr>
			<?php endforeach;

			endif; ?>
            </tbody>
        </table>
    </div>
	<?php
}

/**
 * массив продуктов
 *
 * @return array
 */
function wpp_br_get_cat_products( $id_term ) {
	$args     = [
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => - 1,
		'tax_query'           => [
			[
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $id_term,
				'operator' => 'IN'
			],
			[
				'taxonomy' => 'product_visibility',
				'field'    => 'slug',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN'
			]
		]
	];
	$output   = [];
	$products = new WP_Query( $args );
	if ( $products->have_posts() ) : while ( $products->have_posts() ) : $products->the_post();
		$id      = get_the_id();
		$terms   = wp_get_post_terms( $id, 'product_tag' );
		$product = wc_get_product( $id );

		$price = $product->get_price();


		//$img = $product->get_image_id();
		$img = get_the_post_thumbnail_url( $id, 'full' );
		if ( empty( $img ) ) {
			$img = wc_placeholder_img_src( 'woocommerce_single' );
		}

		if ( count( $terms ) > 0 ) {
			foreach ( $terms as $term ) {

				$tag = get_term( $term->term_id, 'product_tag' );
				if ( empty( $tag ) ) {
					continue;
				}

				$tag_parent = get_term( $tag->parent, 'product_tag' );
				if ( empty( $tag_parent->name ) ) {
					continue;
				}


				$output[ $tag_parent->term_id ]['name']                         = get_user_term_title( $tag_parent );
				$output[ $tag_parent->term_id ]['div']                          = $tag_parent->slug;
				$output[ $tag_parent->term_id ]['id']                           = $tag_parent->term_id;
				$output[ $tag_parent->term_id ]['term_order']                   = ! empty( $tag_parent->term_order ) ? (int) $tag_parent->term_order : 0;
				$output[ $tag_parent->term_id ][ $term->term_id ]['name']       = get_user_term_title( $tag );
				$output[ $tag_parent->term_id ][ $term->term_id ]['div']        = $tag->slug;
				$output[ $tag_parent->term_id ][ $term->term_id ]['term_order'] = ! empty( $term->term_order ) ? ( int) $term->term_order : 0;
				$output[ $tag_parent->term_id ][ $term->term_id ]['posts'][]    = [
					'link'  => get_the_permalink(),
					'id'    => $id,
					'title' => get_the_title(),
					'img'   => $img,
					'price' => $price,
					'type'  => $product->get_type(),
					'order' => get_post_field( 'menu_order', $id )
				];
			}
		}

	endwhile;
	endif;
	$out = [];

	#wpp_dump( $output );
	wp_reset_query();

	#return array_reverse( $out );
	return $output;
}

function wpp_custom_column_header( $columns ) {
	$columns['custom_id'] = 'ORDER';

	return $columns;
}

add_filter( "manage_edit-product_tag_columns", 'wpp_custom_column_header', 10 );


function wpp_custom_column_content( $value, $column_name, $term_id ) {
	if ( $column_name === 'custom_id' ) {
		$term    = get_term( $term_id );
		$columns = $term->term_order;
	}

	return $columns;
}

add_action( "manage_product_tag_custom_column", 'wpp_custom_column_content', 10, 3 );


/**
 * Липкое меню
 *
 * @param $output
 *
 * @return string
 */
function wpp_br_st_nav( $output, $current ) {

	# wpp_dump($output);

	$list = sprintf( '<img class="wpp-temp-icon" src="%s" alt="">', get_template_directory_uri() . '/assets/img/icons/list.svg' );
	$grid = sprintf( '<img class="wpp-temp-icon" src="%s" alt="">', get_template_directory_uri() . '/assets/img/icons/grid.svg' );

	$html = <<<NAV
            <a class="nav-item%s" href="#%s">
                <span class="nav-icon">
                    <i class="icon %s"></i>
                </span>
                <span class="nav-text">%s</span>
            </a>
NAV;

	$wrap = <<<WPAP
            <header class="container-fluid responsive-gutter parts-category__header">
                <div class="row">
                    <div class="col-12">
                        <nav class="nav nav-bordered nav-category">
                        %s          
                        </nav>
                    </div>
                </div>
            </header>
WPAP;

	#icon icon-exterior
#icon icon-sound
	#   icon icon-wheel
	#       icon icon-interior


	$nav = '';
	foreach ( $output as $one_tag ) :
		$active = $one_tag['div'] === $current ? ' active' : '';
		$nav    .= sprintf( $html, $active, $one_tag['div'], tags_icon( $one_tag['id'] ), $one_tag['name'] );
	endforeach;

	$html_l = <<<NAV
            <a class="swith-item" data-swith="list" href="javascript:void(0);">$list</a>
NAV;

	$html_g = <<<NAV
            <a class="swith-item" data-swith="grid" href="javascript:void(0);">$grid</a>
NAV;


	return sprintf( $wrap, $nav /*. '<span class="nav-item wpp-swith-wrap">' . $html_l . $html_g . '</span>'*/ );
}


function custom_call_for_price() {
	return __( 'Price on request', 'wpp-brabus' );
}


function woo_custom_cart_button_text() {
	return __( 'Add to cart', 'wpp-brabus' );
}


function wpp_br_html( $radio ) {
	$radio['image'] = <<<HTML
            <div class="col-xl-2 col-lg-3 col-md-4">
                <label class="product-attribute__value %10\$s" for="%1\$s-%6\$s">
                    <span class="product-attribute__image">
                        <img src="%9\$s"
                             class="img-fluid">
                    </span>
                    <span class="product-attribute__label">
                        <input type="radio" class="wpp-variation"  id="%1\$s-%6\$s" value="%6\$s"
                               data-attribute_name="attribute_%4\$s" data-show_option_none="%5\$s">
                        %8\$s
                        <i class="icon small icon-check-mark"></i>
                    </span>
                </label>
            </div>
HTML;

	$radio['radio'] = <<<HTML
            <div class="col-md-3">
                <table class="products">
                    <tbody>
                        <tr class="">
                            <th>
                                <span class="form--checkbox form--border-none">
                                    <input %7\$s id="%1\$s-%6\$s"  class="wpp-variation wpp-check" data-object-label="%8\$s" type="checkbox" value="%6\$s"  data-attribute_name="attribute_%4\$s" data-show_option_none="%5\$s">
                                        <label for="%1\$s-%6\$s" class="extra-large"></label>
                                    </span>
                                <h5>%8\$s</h5>
                            </th>
                        <td class="product-price"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
HTML;

	return $radio;
}


if ( ! function_exists( 'wpp_br_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array            Fragments to refresh via AJAX
	 */
	function wpp_br_cart_link_fragment( $fragments ) {
		ob_start();
		wpp_br_cart_link();
		$fragments['wpp_cart_frag'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wpp_br_cart_link_fragment' );

if ( ! function_exists( 'wpp_br_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function wpp_br_cart_link() {

		$class       = (int) ( WC()->cart->get_cart_contents_count() ) > 0 ? ' icon-cart--active' : '';
		$session_cur = ! empty( $_SESSION['wpp_currency_new'] ) ? '/' . strtolower( $_SESSION['wpp_currency_new'] ) : '';
		?>
        <a href="<?php echo $session_cur ?>/cart" id="wpp-cart-frag" class="navbar-toggler cart-contents"
           data-cart-url="<?php echo $session_cur ?>/cart">
            <b class="cart_total"><?php echo wpp_get_cart_subtotal(); ?></b>
            <i class="icon icon-cart<?php echo $class; ?>">
                    <span class="icon-cart__counter count"
                          data-counter="<?php echo WC()->cart->get_cart_contents_count(); ?>"></span>
            </i>
        </a>
		<?php
	}
}

function wpp_brabus_final_bread( $active ) { ?>
    <nav class="breadcrumb breadcrumb-cart responsive-gutter section-padding-small">
        <a href="<?php echo wc_get_cart_url(); ?>"
           class="breadcrumb-item<?php echo $active === 1 ? ' active' : ''; ?>">
            1 <?php _e( 'Products', 'wpp-brabus' ); ?>
        </a>
        <a href="<?php echo wc_get_checkout_url(); ?>"
           class="breadcrumb-item<?php echo $active === 2 ? ' active' : ''; ?>">
            2 <?php _e( 'Details', 'wpp-brabus' ); ?>
        </a>
        <a href="javascript:void(0);" class="breadcrumb-item<?php echo $active === 3 ? ' active' : ''; ?>">
            3 <?php _e( 'Despatch', 'wpp-brabus' ); ?>
        </a>
    </nav>
<?php }


add_action( 'template_redirect', 'woo_custom_redirect_after_purchase' );
function woo_custom_redirect_after_purchase() {
	global $wp;
	if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
		wp_redirect( get_home_url() . '/finish' );
		exit;
	}
}

remove_action( 'woocommerce_before_main_conten', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_filter( 'wpp_rf_variable_input_html', 'wpp_br_html' );

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );

add_filter( 'woocommerce_empty_price_html', 'custom_call_for_price' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'reregister_taxonomy_pro_tags', 11 );

add_filter( 'woocommerce_product_subcategories_hide_empty', '__return_false' );

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'wpp_woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'wpp_woo_sub_cat_list', 20 );


remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'woocommerce_before_subcategory_title', 'wpp_woocommerce_subcategory_thumbnail', 10 );

remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
add_action( 'woocommerce_before_subcategory', 'wpp_woocommerce_template_loop_category_link_open', 10 );


remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
#add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 40 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
#remove_action( 'woocommerce_single_product_summary', WC_Structured_Data::generate_product_data(), 60 );


add_action( 'wpp_woocommerce_single_add_cart', 'woocommerce_template_single_add_to_cart', 10 );

#add_action( 'wpp_woocommerce_single_product_summary', WC_Structured_Data::generate_product_data(), 10 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


add_action( 'woocommerce_widget_shopping_cart_buttons', 'wpp_woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );


add_action( 'wp_ajax_wpp_ajax_cart_cat', 'wpp_ajax_cart_cat_callback' );
add_action( 'wp_ajax_nopriv_wpp_ajax_cart_cat', 'wpp_ajax_cart_cat_callback' );

add_filter( 'woocommerce_cart_needs_payment', '__return_false' );


if ( ! function_exists( 'wpp_woocommerce_widget_shopping_cart_button_view_cart' ) ) {

	/**
	 * Output the view cart button.
	 */
	function wpp_woocommerce_widget_shopping_cart_button_view_cart() {
		echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button wc-forward form--button cart-layer__add-to-cart">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>';
	}
}


if ( ! function_exists( 'wpp_woocommerce_form_field' ) ) {
	/**
	 * Outputs a checkout/address form field.
	 *
	 * @param string $key Key.
	 * @param mixed $args Arguments.
	 * @param string $value (default: null).
	 *
	 * @return string
	 */
	function wpp_woocommerce_form_field( $key, $args, $value = null ) {
		$defaults = [
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'autocomplete'      => false,
			'id'                => $key,
			'class'             => [],
			'label_class'       => [],
			'input_class'       => [],
			'return'            => false,
			'options'           => [],
			'custom_attributes' => [],
			'validate'          => [],
			'default'           => '',
			'autofocus'         => '',
			'priority'          => '',
		];
		$args     = wp_parse_args( $args, $defaults );
		$args     = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );
		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
		} else {
			$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
		}
		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = [ $args['label_class'] ];
		}
		if ( is_null( $value ) ) {
			$value = $args['default'];
		}
		// Custom attribute handling.
		$custom_attributes         = [];
		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );
		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}
		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}
		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}
		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}
		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}
		if ( ! empty( $args['validate'] ) ) {
			foreach ( $args['validate'] as $validate ) {
				$args['class'][] = 'validate-' . $validate;
			}
		}
		$field           = '';
		$label_id        = $args['id'];
		$sort            = $args['priority'] ? $args['priority'] : '';
		$field_container = '<div class="form--row" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</div>';
		switch ( $args['type'] ) {
			case 'country':
				$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
				if ( 1 === count( $countries ) ) {
					$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';
					$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';
				} else {
					$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '><option value="">' . esc_html__( 'Select a country&hellip;', 'woocommerce' ) . '</option>';
					foreach ( $countries as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
					}
					$field .= '</select>';
					$field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country', 'woocommerce' ) . '">' . esc_html__( 'Update country', 'woocommerce' ) . '</button></noscript>';
				}
				break;
			case 'state':
				/* Get country this state field is representing */
				$state_val   = 'billing_state' === $key ? 'billing_country' : 'shipping_country';
				$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( $state_val );
				$states      = WC()->countries->get_states( $for_country );
				if ( is_array( $states ) && empty( $states ) ) {
					$field_container = '<div class="form--row" id="%2$s" style="display: none">%3$s</div>';
					$field           .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" />';
				} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {
					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'woocommerce' ) ) . '">
						<option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';
					foreach ( $states as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . $cvalue . '</option>';
					}
					$field .= '</select>';
				} else {
					$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				}
				break;
			case 'textarea':
				$field .= '<textarea name="' . esc_attr( $key ) . '" class="form--text form--border" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'checkbox':
				$field = '<label class="checkbox form--label" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';
				break;
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="form--text form--border-bottom" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
				break;
			case 'select':
				$field   = '';
				$options = '';
				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						if ( '' === $option_key ) {
							// If we have a blank option, select2 needs a placeholder.
							if ( empty( $args['placeholder'] ) ) {
								$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
							}
							$custom_attributes[] = 'data-allow_clear="true"';
						}
						$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
					}
					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="form--text form--border-bottom" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
				}
				break;
			case 'radio':
				$label_id .= '_' . current( array_keys( $args['options'] ) );
				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
						$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio form--label">' . $option_text . '</label>';
					}
				}
				break;
		}
		if ( ! empty( $field ) ) {
			$field_html = '';
			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="form--label">' . $args['label'] . $required . '</label>';
			}
			$field_html .= '' . $field;
			if ( $args['description'] ) {
				$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
			}
			$field_html      .= '';
			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}
		/**
		 * Filter by type.
		 */
		$field = apply_filters( 'woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );
		/**
		 * General filter on form fields.
		 *
		 * @since 3.4.0
		 */
		$field = apply_filters( 'woocommerce_form_field', $field, $key, $args, $value );
		if ( $args['return'] ) {
			return $field;
		} else {
			echo $field; // WPCS: XSS ok.
		}
	}
}

add_filter( 'woocommerce_checkout_fields', 'wpp_br_checkout_setting' );

function wpp_br_checkout_setting( $fields ) {
	/**
	 * $fields_array = wpp_fr_checkout_fields();
	 * foreach ( $fields_array as $key => $val ) :
	 * unset( $fields[ 'key' ] );
	 * endforeach;
	 */
	$fields = [
		'billing' => [
			'billing_salutation' => [
				'label'    => __( 'Salutation', 'wpp-brabus' ),
				'required' => false,
				'type'     => 'text',
				'priority' => 10
			],
			'billing_first_name' => [
				'label'    => __( 'Name', 'wpp-brabus' ),
				'required' => true,
				'type'     => 'text',
				'priority' => 20
			],
			'billing_email'      => [
				'label'    => __( 'Email', 'wpp-brabus' ),
				'required' => true,
				'type'     => 'text',
				'priority' => 30,
			],

			'billing_phone'            => [
				'label'    => __( 'Phone', 'wpp-brabus' ),
				'required' => true,
				'type'     => 'text',
				'priority' => 40,
			],
			'billing_country'          => [
				'type'     => 'select',
				'label'    => __( 'Country', 'wpp-brabus' ),
				'options'  => wpp_fr_county_array(),
				'required' => false,
				'priority' => 50,
			],
			'billing_number_and_model' => [
				'label'    => __( 'Chassis Number and Model (and Year Built):', 'wpp-brabus' ),
				'type'     => 'text',
				'required' => false,
				'priority' => 60,
			]
		],
		'order'   => [
			'order_comments' => [
				'label'    => __( 'Message', 'wpp-brabus' ),
				'type'     => 'textarea',
				'required' => false,
				'priority' => 70,
			],
			'order_code'     => [
				'label'    => __( 'Promo Code', 'wpp-brabus' ),
				'required' => false,
				'type'     => 'text',
				'priority' => 80
			]
		]
	];

	return $fields;
}

function wpp_ads_prd( $array ) {

	$array['paint']    = [
		'field'            => 'paint',
		'name'             => wpp_br_lng( 'paint' ),
		'min'              => 0,
		'max'              => 1,
		'sales'            => false,
		#'price'            => apply_filters( 'paint', 19000, $id=null ),
		'field_type'       => 'checkbox',
		'dependent_fields' => false
	];
	$array['assembly'] = [
		'field'            => 'assembly',
		'name'             => wpp_br_lng( 'assem' ),
		'min'              => 0,
		'max'              => 1,
		'sales'            => false,
		#'price'            => apply_filters( 'paint', 19000,$id=null  ),
		'field_type'       => 'checkbox',
		'dependent_fields' => false
	];


	return $array;

}

add_filter( 'wpp_ad_sale_fields_array', 'wpp_ads_prd', 0 );


remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );

function wpp_get_cart_subtotal( $compound = false ) {
	/**
	 * If the cart has compound tax, we want to show the subtotal as cart + shipping + non-compound taxes (after discount).
	 */
	WC()->cart->calculate_totals();
	if ( $compound ) {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_cart_contents_total() + WC()->cart->get_shipping_total() + WC()->cart->get_taxes_total( false, false ) );

	} elseif ( WC()->cart->display_prices_including_tax() ) {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax() );

		if ( WC()->cart->get_subtotal_tax() > 0 && ! wc_prices_include_tax() ) {
			$cart_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
		}
	} else {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_subtotal() );

		if ( WC()->cart->get_subtotal_tax() > 0 && wc_prices_include_tax() ) {
			$cart_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}
	}

	return apply_filters( 'woocommerce_cart_subtotal', $cart_subtotal, $compound, WC()->cart );
}

function wpp_get_cart_subtotal_pdf( $compound = false ) {
	/**
	 * If the cart has compound tax, we want to show the subtotal as cart + shipping + non-compound taxes (after discount).
	 */
	if ( $compound ) {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_cart_contents_total() + WC()->cart->get_shipping_total() + WC()->cart->get_taxes_total( false, false ), array( 'pdf' => true ) );

	} elseif ( WC()->cart->display_prices_including_tax() ) {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax(), array( 'pdf' => true ) );

		if ( WC()->cart->get_subtotal_tax() > 0 && ! wc_prices_include_tax() ) {
			$cart_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
		}
	} else {
		$cart_subtotal = wpp_wc_price( WC()->cart->get_subtotal(), array( 'pdf' => true ) );

		if ( WC()->cart->get_subtotal_tax() > 0 && wc_prices_include_tax() ) {
			$cart_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}
	}

	return apply_filters( 'woocommerce_cart_subtotal', $cart_subtotal, $compound, WC()->cart );
}


/*
This script will allow you to send a custom email from anywhere within wordpress
but using the woocommerce template so that your emails look the same.
Created by craig@123marbella.com on 27th of July 2017
Put the script below into a function  or anywhere you want to send a custom email
*/
function get_custom_email_html( $data, $heading = false, $mailer ) {
	$template = 'emails/contacts.php';

	return wc_get_template_html( $template, array(
		'data'          => $data,
		'email_heading' => $heading,
		'sent_to_admin' => false,
		'plain_text'    => false,
		'email'         => $mailer
	) );
}

// load the mailer class


/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_settings_pages', 'wpp_br_add_page_wc_settings', 10 );
function wpp_br_add_page_wc_settings( $settings ) {


	$settings[5] = array(
		'title'    => __( 'Product Search Page', 'wpp-br' ),
		/* Translators: %s Page contents. */
		'id'       => 'wpp_fr_search_page_id',
		'type'     => 'single_select_page',
		'default'  => '',
		'class'    => 'wc-enhanced-select-nostd',
		'css'      => 'min-width:300px;',
		'desc_tip' => true,
	);

	$settings[6] = array(
		'title'    => __( 'News Products', 'wpp-br' ),
		/* Translators: %s Page contents. */
		'id'       => 'wpp_fr_pn_page_id',
		'type'     => 'single_select_page',
		'default'  => '',
		'class'    => 'wc-enhanced-select-nostd',
		'css'      => 'min-width:300px;',
		'desc_tip' => true,
	);

	$settings[7] = array(
		'title'    => __( 'Wishlist Page', 'wpp-br' ),
		/* Translators: %s Page contents. */
		'id'       => 'wpp_fr_wl_page_id',
		'type'     => 'single_select_page',
		'default'  => '',
		'class'    => 'wc-enhanced-select-nostd',
		'css'      => 'min-width:300px;',
		'desc_tip' => true,
	);


	return $settings;

}