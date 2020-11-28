<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
#do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

?>
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
        <meta itemprop="productID" content="isbn:<?php echo $product->get_sku(); ?>">
        <meta itemprop="mpn" content="<?php echo $product->get_sku(); ?>">
		<?php

		$options = get_the_terms( $product->get_id(), 'as_options' );

		if ( ! empty( $options ) ) :
			foreach ( $options as $option ) {
				printf( '<meta itemprop="brand" content="%s">', $option->name );
			}
		endif;

		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>


		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked #woocommerce_template_single_title - 5
		 * @hooked #woocommerce_template_single_rating - 10
		 * @hooked #woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked #woocommerce_template_single_add_to_cart - 30
		 * @hooked #woocommerce_template_single_meta - 40
		 * @hooked #woocommerce_template_single_sharing - 50
		 * @hooked #WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );

		?>
        <section class="container-fluid responsive-gutter">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="product-list">
                        <div class="product-list-footer">
							<?php
							/**
							 * Hook: wpp_woocommerce_single_add_cart.
							 *
							 *
							 * @hooked woocommerce_template_single_add_to_cart - 10
							 */

							do_action( 'wpp_woocommerce_single_add_cart' );
							?>
                        </div>
                    </div>
					<?php
					/**
					 * Hook: wpp_woocommerce_single_product_summary.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'wpp_woocommerce_single_product_summary' );
					?>
                </div>
            </div>
        </section>

		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
		<?php
		$type = $product->get_type();
		if ( $type === 'bundle' ) {
			$meta = get_post_meta( get_queried_object_id(), 'bundle_package', false );
			?>
            <section class="container-fluid responsive-gutter" id="productdetail" data-tracking="">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="product-teaser">
                            <h4 class="product-teaser__caption"><?php echo wpp_br_lng( 'package_inc' ); ?></h4>
                            <form class="wpp-b-sort-form">
                                <div class="row wpp-sortable" data-options="[]" data-attributes-selection-target="">
									<?php if ( ! empty( $meta ) ) {
										foreach ( $meta as $one ) : ?>
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 wpp-drug"
                                                 data-min-height="">
												<?php if ( wpp_fr_user_is_admin() ) {
													printf( '<input type="hidden" class="wpp-bundle-sort" name="wpp_bundle_sort[]" value="%s">', $one );
												} ?>
                                                <a href="<?php echo get_the_permalink( (int) $one ); ?>"
                                                   class="grid-teaser-shadow product-teaser">
                                                    <div class="grid-teaser">
                                                        <div class="grid-teaser-image">
                                                            <picture>
																<?php $src = bfi_thumb( get_the_post_thumbnail_url( (int) $one, 'full' ), [
																	'width'  => 540,
																	'height' => 360,
																	'crop'   => true
																] ) ?>
                                                                <img src="<?php echo $src ?>"
                                                                     alt="<?php echo get_the_title( (int) $one ); ?>"
                                                                     class="img-fluid">
                                                            </picture>
                                                        </div>
                                                        <h4 class="grid-headline-icon"><?php echo get_the_title( (int) $one ); ?></h4>
														<?php def_costil( (int) $one, true ); ?>
                                                    </div>
                                                </a>
                                            </div>
										<?php
										endforeach;
									} ?>
                                </div>
                            </form>
							<?php if ( wpp_fr_user_is_admin() ) {
								printf( '<a href="javascript:void(0);"  data-id="%s" class="wpp-bundle-sort-send form--button-dark form--button--cta">%s</a>', get_queried_object_id(), 'Обновить' );
							} ?>
                        </div>
                    </div>
                </div>
            </section>
			<?php
		} ?>
    </div>

<?php do_action( 'woocommerce_after_single_product' );
/*
$query_args = [
	'post_type'    => 'product',
	'post__not_in' => [$product->get_id()],
	'show_post'    => 4,
	'tax_query'    => [
		[
			'taxonomy' => 'product_cat',
			'fields'   => 'term_id',
			'terms'    => get_the_terms( $product->get_id(), 'product_cat' )[0]->term_id
		],
		[
			'taxonomy' => 'product_tag',
			'fields'   => 'term_id',
			'terms'    => get_the_terms( $product->get_id(), 'product_tag' )[0]->term_id
		],
		'relation' => 'AND'
	]
];
$query      = new WP_Query( $query_args );


if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
	$producta = wc_get_product( get_the_ID() );

	$attachment_ids = $producta->get_gallery_image_ids();
	$type           = $producta->get_type();

	?>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 wpp-grid-item" data-prd_id="<?php echo get_the_ID(); ?>">

        <div class="grid-teaser<?php echo $type === 'bundle' ? ' grid-teaser-package' : ''; ?>">
            <div class="wpp-grid_imgs">
				<?php
				$pref = ! wp_is_mobile() ? 'deck' : 'mobile';

				if ( 'bundle' !== $product->get_type() ) :
					wpp_get_template_part( 'templates/thumb/' . $pref . '-thumb',
						[
							'post'           => $post,
							'attachment_ids' => $attachment_ids,
							'type'           => $type,
							'meta'           => get_post_meta( get_the_ID(), 'bundle_package', false )
						]
					);
				else:
					wpp_get_template_part( 'templates/thumb/bundle-thumb', [
						'post'           => $post,
						'attachment_ids' => $attachment_ids,
						'type'           => $type,
						'meta'           => get_post_meta( get_the_ID(), 'bundle_package', false )
					] );
				endif;
				?>
            </div>
        </div>

		<?php def_costil( get_the_ID() ); ?>
    </div>
<?php
endwhile;
else :
endif;
wp_reset_query();
*/