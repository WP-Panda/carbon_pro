<?php
	/*
Template Name: Finish
Template Post Type: page
*/

	if ( !defined( 'ABSPATH' ) ) {
		exit;
	}
	global $woocommerce;
	$woocommerce->cart->empty_cart();
	get_header();
?>

        <?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>
        <div class="content">
            <div class="neos-contentcollection">
				<?php wpp_brabus_final_bread( 3 ); ?>

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <section class="container-fluid responsive-gutter section-padding-medium" id="tracking">
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <section class="text-box-container text-box-white">
                                    <div class="text-box-row">
                                        <div class="text-box">
                                            <h3 class="text-center">
												<?php _e( 'Thank you for your request', 'wpp-brabus' ) ?>
                                            </h3>
                                            <p class="text-center">
												<?php _e( 'Your BRABUS dealer will contact you as soon as possible.', 'wpp-brabus' ) ?>
                                            </p>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="form--row">
                                    <a class="form--button-dark form--button--cta" href="<?php echo get_home_url(); ?>">
										<?php _e( 'Return to homepage', 'wpp-brabus' ) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
					<?php the_content(); ?>
				<?php
				endwhile;
				else :
				endif; ?>
            </div>
        </div>

<?php
	get_footer();