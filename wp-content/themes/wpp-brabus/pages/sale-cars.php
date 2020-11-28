<?php
/*
	Template Name: Car4Sale
	Template Post Type: page
	*/
defined( 'ABSPATH' ) || exit;
global $post;
get_header();

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>


<?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>

    <div class="content">

        <div class="neos-contentcollection">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <section class="text-box-container text-box-white">
                    <div class="text-box-row">
                        <div class="text-box text-center">
							<?php
							ob_start();
							the_content();
							echo wpautop( ob_get_clean() );
							?>
                        </div>
                    </div>
                </section>
			<?php endwhile;
			else :
			endif; ?>

			<?php $car_args = [ 'post_type' => 'sale_car', 'nopaging' => true,'order' => 'ASC','orderby' => 'meta_value', 'meta_key' => '_sold' ];
			$car_query      = new WP_Query( $car_args );

			if ( $car_query->have_posts() ) : ?>

                <section class="container-fluid responsive-gutter section-padding-small col-min-height">
                    <div class="row">
						<?php while ( $car_query->have_posts() ) : $car_query->the_post();
							$sold      = get_post_meta( $post->ID, '_sold', true );
							$sold_class = ! empty( $sold ) ? 'grid-teaser-sold ' : '';
							?>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12" data-min-height="">
                                <div class="grid-teaser-shadow grid-teaser-overlay" tabindex="1">
                                    <a href="<?php the_permalink(); ?>" alt="">
                                        <div class="<?php echo $sold_class; ?>grid-teaser">
                                            <div class="grid-teaser-image">
												<?php
												$_price = get_post_meta( $post->ID, '_price', true );

												if ( ! empty( $_price ) ) {
													$price = wpp_return_price( $_price );
												} else {
													$price = '-';
												}

												$_mileage = get_post_meta( $post->ID, '_mileage', true );
												$_power   = get_post_meta( $post->ID, '_power', true );
												$url      = get_the_post_thumbnail_url( '', 'full' );
												if ( empty( $url ) ) {
													$url = wpp_fr_image_placeholder();
												}
												e_wpp_fr_image_html( $url, wpp_br_thumb_array() ); ?>
                                            </div>
											<?php if ( ! empty( $sold ) ) :?>
                                                <span class="grid-teaser-badge"><?php e_wpp_br_lng( 'sold' ); ?></span>
											<?php endif; ?>
                                            <h4 class="grid-headline-icon"><?php the_title(); ?></h4>
                                            <p><?php echo get_post_meta($post->ID,'_subtitle',true); ?></p>
                                            <div class="grid-teaser-details structured-content">
                                                <table class="structured-table">
                                                    <tbody>
                                                    <tr>
                                                        <td class="key"><?php e_wpp_br_lng( 'gross' ); ?></td>
                                                        <td class="value"><span
                                                                    class=""><?php echo wc_price( $price ); ?></span><br><span
                                                                    class=""><?php e_wpp_br_lng( 'in_vat' ); ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key"><?php e_wpp_br_lng( 'neus' ); ?></td>
                                                        <td class="value"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key"><?php e_wpp_br_lng( '_mileage' ); ?></td>
                                                        <td class="value"><?php echo ! empty( $_mileage ) ? $_mileage : '-'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key"><?php e_wpp_br_lng( '_power' ); ?></td>
                                                        <td class="value"><?php echo ! empty( $_power ) ? $_power : '-'; ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <div class="form--row">
                                                    <button class="form--button-small form--button-center form--button--cta form--button-dark">
														<?php e_wpp_br_lng( 'details' ); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
						<?php endwhile; ?>
                    </div>
                </section>
			<?php endif; ?>

        </div>
    </div>

<?php wp_reset_query();
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
get_footer();