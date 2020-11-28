<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
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

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

wpp_brabus_final_bread( 1 );
wpp_get_template_part( 'templates/cart/actions-row' ); ?>
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>
        <section class="container-fluid responsive-gutter section-padding-medium" id="tracking">
            <div class="row justify-content-center">
                <div class="col-lg-10">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key ); ?>
                            <div class="cart-item  wpp-c-i <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                <div class="catr-item-img-bloscs">
									<?php
									$params = [ 'wrap' => '<a href="' . $product_permalink . '">%s</a>' ];
									e_wpp_fr_image_html( wp_get_attachment_image_url( $_product->get_image_id(), 'full' ), wpp_br_thumb_array( $params ) );
									?>

                                </div>
                                <div class="cart-item--title-description-amount-container">
                                    <div class="cart-item-amount">
										<?php if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
										} else { ?>
                                            <a href="javaScript:void(0)"><i class="icon icon-minus small"></i></a>
                                            <input data-type="product" class="form--text form--border input-small qty"
                                                   name="cart[<?php echo $cart_item_key; ?>][qty]"
                                                   value="<?php echo $cart_item['quantity'] ?>"
                                                   data-text="Price on request">
                                            <a href="javaScript:void(0)"><i class="icon icon-plus small"></i></a>
										<?php } ?>
                                    </div>
                                    <div class="cart-item-title">
										<?php
										if ( ! empty( $_COOKIE['wpp_lng'] ) ):
											$content_lng = get_post_meta( $_product->get_id(), 'title_' . get_locale(), true );
										endif;

										if ( ! empty( $content_lng ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $content_lng, $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', apply_filters( 'the_title', $_product->get_name(), $_product->get_id() ), $cart_item, $cart_item_key ) . '&nbsp;' );
										}

										?>
                                    </div>
									<?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>
                                    <div class="cart-item-description"></div>
                                </div>
                                <div class="cart-item--number-price-container">
                                    <div class="cart-item-number">
										<?php

										$meta = wpp_get_opt_data( $_product->get_id() );
										if ( ! empty( $cart_item['wpp_add_variants'] ) ) :
											$v = array_search( $cart_item['wpp_add_variants'], array_column( $meta, 'key' ) );
											echo $meta[ $v ]['sku'];
										endif; ?>
                                    </div>
                                    <div class="cart_item_time"><?php echo wpp_br_create_time_info( $_product->get_id() ); ?></div>
									<?php

									$price = $_product->get_price();

									$norm_price = wpp_fr_get_variant_by( $cart_item['product_id'], $cart_item['wpp_add_variants'], '', 'price' );
									if ( ( round( $norm_price ) !== round( $price ) ) && ! empty( $norm_price ) ) {
										printf( '<div class="cart-item-price dell-price"><span><del>%s</del></span></div>', wpp_wc_price( $cart_item['quantity'] * $norm_price ) );
									}
									printf( '<div class="cart-item-price"><span>%s</del></span></div>', wpp_wc_price( $cart_item['quantity'] * $price ) ); ?>


                                </div>
                            </div>
							<?php echo apply_filters( 'wpp_checkout_additional', $_product->get_name(), $cart_item, $cart_item_key ); ?>
						<?php endif;
					}
					?>

                    <div class="cart-item-end-sum" data-sum="">
                        <div class="cart-item--net-tax-end-price-container">
                            <div class="cart-item-end-price">
                                <span data-item-totalsum="1"><?php echo wpp_get_cart_subtotal(); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="form--row">
                        <a class="form--button-dark form--button--cta" href="<?php echo wc_get_checkout_url(); ?>">
							<?php _e( 'Next', 'wpp-brabus' ) ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <button type="submit" class="button" style="display: none;opacity: 0;visibility: hidden;" name="update_cart"
                value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
		<?php do_action( 'woocommerce_cart_actions' ); ?>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    </form>
<?php
wpp_get_template_part( 'templates/cart/actions-row' );
do_action( 'woocommerce_after_cart' );
