<?php
	/**
	 * Single variation cart button
	 *
	 * @see     https://docs.woocommerce.com/document/template-structure/
	 * @package WooCommerce/Templates
	 * @version 3.4.0
	 */

	defined( 'ABSPATH' ) || exit;

	global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( [
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST[ 'quantity' ] ) ? wc_stock_amount( wp_unslash( $_POST[ 'quantity' ] ) ) : $product->get_min_purchase_quantity(),
			// WPCS: CSRF ok, input var ok.
		] );

		do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>
    <div class="form--row d-flex justify-content-end">
        <button type="submit"
                class="form--button-gray single_add_to_cart_button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
    </div>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>"/>
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>"/>
    <?php
    $default_attributes = wpp_fr_get_default_attributes( $product );
    $variation_id = wpp_fr_find_matching_product_variation( $product, $default_attributes );
    $id = !empty($variation_id) ? $variation_id : 0;
    ?>
    <input type="hidden" name="variation_id" class="variation_id" value="<?php echo  $id; ?>"/>
</div>
