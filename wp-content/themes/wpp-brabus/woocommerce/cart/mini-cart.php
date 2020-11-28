<?php
	/**
	 * Mini-cart
	 *
	 * Contains the markup for the mini-cart, used by the cart widget.
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see     https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce/Templates
	 * @version 3.5.0
	 */
	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}

	do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( !WC()->cart->is_empty() ) : ?>
    <div class="cart-layer__specs">
        <div class="container-fluid">

			<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item[ 'data' ], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item[ 'product_id' ], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item[ 'quantity' ] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						#$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_price = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item[ 'quantity' ] ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
                        <div class="row row__section-padding border-bottom <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <div class="col-12 col-sm-8">
                                <strong><?php echo $product_name; ?></strong>
                                <div class="cart-item-number">
									<?php

										$meta = wpp_get_opt_data( $_product->get_id());
										$v = array_search( $cart_item[ 'wpp_add_variants' ], array_column( $meta, 'key' ) );
										echo $meta[ $v ][ 'sku' ]; ?>
                                </div>
                            </div>
                            <div class="col-8 col-sm-2 cart-layer__number-items"><?php echo $cart_item[ 'quantity' ]; ?></div>
                            <div class="col-4 col-sm-2 text-right"
                                 style="font-size: 18px"><?php echo $product_price; ?></div>
                        </div>
						<?php
                        echo apply_filters( 'woocommerce_cart_item_name_mini', $_product->get_name(), $cart_item, $cart_item_key );
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
			?>
            <div class="row row__section-padding border-bottom">
                <div class="col-12 col-sm-8"></div>
                <div class="col-8 col-sm-2 cart-layer__number-items"><?php _e( 'total', 'wpp-brabus' ); ?></div>
                <div class="col-4 col-sm-2 text-right"
                     style="font-size: 18px"><?php wc_cart_totals_subtotal_html(); ?></div>
            </div>
        </div>
    </div>
    <div class="cart-layer__controls">
		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
		<?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
        <button type="button" class="cart-layer__close close" aria-label="Close" data-cart-layer-hide="1"><i class="icon icon-close-large"></i></button>
    </div>
<?php else : ?>

    <p class="woocommerce-mini-cart__empty-message"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>
    <button type="button" class="cart-layer__close close" aria-label="Close" data-cart-layer-hide="1"><i class="icon icon-close-large"></i></button>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
