<?php
	/**
	 * Checkout Form
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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

?>
<?php wpp_brabus_final_bread( 2 ); ?>
<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
    <section class="container-fluid responsive-gutter section-padding-medium" id="tracking">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="text-uppercase headline-padding-medium">
					<?php _e( 'Your data', 'wpp-brabus' ); ?>
                </h3>
                <p>
					<?php _e( 'Please provide us with your contact details. To give you the best possible advice, our experts will
                    contact you personally after receiving your inquiry. Thus we can guarantee excellent service and
                    best suitable delivery.', 'wpp-brabus' ); ?>
                </p>
                <div class="section-padding-medium">
					<?php if ( $checkout->get_checkout_fields() ) : ?>
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					<?php endif; ?>
                    <div class="form--row ">
                        <div class="form--checkbox form--border-none">
                            <input id="terms" type="checkbox"
                                   name="terms" value="1">
                            <label for="terms" class="form--label form--label__multiLine">
								<?php wc_checkout_privacy_policy_text(); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5 col-xl-3 column-margin-top-lg">
				<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
				<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="form--button-dark form--button--cta" name="woocommerce_checkout_place_order" id="place_order" value="' .  __('Send request','wpp-brabus') .'" data-value="' .  __('Send request','wpp-brabus') .'">' .  __('Send request','wpp-brabus') .'</button>' ); ?>
				<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
            </div>
        </div>
    </section>
	<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</form>








