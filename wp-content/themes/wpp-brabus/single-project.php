<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$tehnical_data = [
	'_engine',
	'_power',
	'_mileage',
	'_speed_100',
	'_speed_max'
];
$prices        = [
	'_price',
	'_price_nds'
];

$flag_tehc = $flag_price = 0;

$tech_data = array_merge( $tehnical_data, $prices );

foreach ( $tech_data as $one ) :

	if ( in_array( $one, $prices ) ) :
		$flag_price ++;
	endif;

	if ( in_array( $one, $tehnical_data ) ) :
		$flag_tehc ++;
	endif;

	${$one} = get_post_meta( $post->ID, $one, true );

endforeach;


get_header();
?>
    <div class="content">
        <div id="object-data" class="neos-contentcollection" data-id="<?php echo get_queried_object_id(); ?>">
			<?php

			if ( wpp_fr_user_is_admin() ) {

				echo '<div class="admin-tools-panel">';
				echo sprintf( '<a class="wpp-cat-slider-format wpp-single-format-trigger format-slider wpp-tooltips" href="javasctipt:void(0);" data-post="%s" data-format="slider" data-title="Формат Слайдер"><img src="%s"></a>', get_queried_object_id(), get_template_directory_uri() . '/assets/img/icons/slider.svg' );
				echo sprintf( '<a class="wpp-cat-slider-format wpp-single-format-trigger format-greed wpp-tooltips" href="javasctipt:void(0);" data-post="%s" data-format="wall" data-title="Формат Галерея"><img src="%s"></a>', get_queried_object_id(),  get_template_directory_uri() . '/assets/img/icons/greed.svg' );
				echo '</div>';
			}
			$format   = get_post_meta( get_queried_object_id(), '_slider_format', true );

			$template = ! empty( $format ) && 'slider' === $format ? 'fullscreen-slider' : 'wall-slider';
			wpp_get_template_part( 'templates/media/' . $template, [
				'img'   => explode( ',', get_post_meta( get_queried_object_id(), '_wpp_post_gallery', true ) ),
				'video' => $video,
				'type'  => 'wall'
			] );

			if ( have_posts() ) : while ( have_posts() ) : the_post();
				the_title( '<section class="main-title"><h1 class="main-title__headline">', '</h1></section>' );
				?>
                <section>
					<?php the_content(); ?>
                </section>
            <?php /**
                <section class="container-fluid responsive-gutter section-padding-large structured-content">
                    <div class="row justify-content-center">

						<?php if ( ! empty( $flag_tehc ) ) : ?>
                            <div class="col-lg-6 col-xl-5">
                                <h4><?php e_wpp_br_lng( 'technical_data' ); ?></h4>
                                <table class="structured-table ">
                                    <tbody>
									<?php foreach ( $tehnical_data as $one ) {

										if ( ! empty( ${$one} ) ) {
											printf( '<tr><td class="key">%s</td><td class="value">%s</td></tr>', wpp_br_lng( $one ), ${$one} );
										}
									}

									?>
                                    </tbody>
                                </table>
                            </div>
						<?php endif; ?>
						<?php if ( ! empty( $flag_tehc ) ) : ?>
                        <div class="col-lg-6 col-xl-5">
                            <h4><?php e_wpp_br_lng( 'gross' ); ?></h4>
                            <table class="structured-table ">
                                <tbody>
								<?php
								$n = 1;
								foreach ( $prices as $one ) {
									if ( ! empty( ${$one} ) ) {

										$pricerr = wpp_return_price( ${$one} );
										printf( '<tr><td class="key">%s</td><td class="value">%s</td></tr>', wpp_br_lng( $one ), 1 === $n ? sprintf( '<span class="car-price">%s</span>', str_replace( 'woocommerce-Price-amount', '', wc_price( $pricerr ) ) ) : wc_price( $pricerr ) );
									}
									$n ++;
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
					<?php endif; ?>
                </section>
                 */ ?>
			<?php endwhile;
			else :
			endif;

			wpp_get_template_part('blocks/block-table-desc',['return_content'=>true]);

			?>


           <!-- <section class="container-fluid section-padding-small responsive-gutter" id="modalTriggerContactForm">
                <div class="form--row section-padding-large text-center">
                    <button type="button" class="form--button-dark form--button--cta auto" data-toggle="modal"
                            data-target=".bd-example-modal-lg"><?php /*e_wpp_br_lng( 'request' ); */?></button>
                </div>
            </section>-->


            <section class="container-fluid section-padding-small responsive-gutter share">
                <div class="row">
                    <div class="col-12 col-sm-6 right">
						<?php

						/* * <a href="#" onclick="window.print();" target="_self">
						 * <i class="icon social icon-flyer"></i>
						 * </a>
						 * <h4 class="text-uppercase light">Print exposé</h4> */

						?></div>
                    <div class="col-12 col-sm-6 left">
                        <a href="javascript:void(0)">
                            <i class="icon social icon-share"></i></a>
                        <div class="social-icons" style="display: none">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink( $post->ID ); ?>"
                               target="_blank">
                                <i class="icon social icon-facebook"></i>
                            </a>
                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo get_the_permalink( $post->ID ); ?>"
                               target="_blank">
                                <i class="icon social icon-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo get_the_permalink( $post->ID ); ?>&amp;text=Hello,%0D%0A%0D%0AI just found a car that might interest you:&amp;via=Carbon Pro"
                               target="_blank">
                                <i class="icon social icon-twitter"></i>
                            </a>
                            <a href="mailto:?subject=Carbon Pro / Interesting car for you!&amp;body=Hello,%0D%0A%0D%0AI just found a car that might interest you:%0D%0A<?php echo get_the_permalink( $post->ID ); ?>">
                                <i class="icon social icon-mail"></i>
                            </a>
                        </div>
                        <h4 class="text-uppercase light hide"><?php e_wpp_br_lng( 'share' ); ?></h4>
                    </div>

                </div>
            </section>
        </div>
    </div>
<?php
wpp_get_template_part( 'templates/globals/modal-contacts' );
get_footer();