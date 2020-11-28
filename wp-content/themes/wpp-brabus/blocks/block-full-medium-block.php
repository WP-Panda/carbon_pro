<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$loc =! empty($flag) ? strtolower( get_locale() ) : 'ru_ru';

$imgs      = ! empty( block_value( 'img' ) ) ? wp_get_attachment_image_url( block_value( 'img' ), 'full' ) : '';
$title     = ! empty( block_value( 'title_' . $loc ) ) ? block_value( 'title_' . $loc ) : '';
$btn_link  = ! empty( block_value( 'btn_link_' . $loc ) ) ? block_value( 'btn_link_' . $loc ) : '';
$btn_title = ! empty( block_value( 'btn_title_' . $loc ) ) ? block_value( 'btn_title_' . $loc ) : '';



$params = array(
	'retina'     => true,
	'water_mark' => true,
	'sizes' => [
		[ 'width' => 1920, 'height' => 720, 'media' => '(min-width: 1200px)' ],
		[ 'width' => 1200, 'height' => 600, 'media' => '(min-width: 992px)' ],
		[ 'width' => 992, 'height' => 480, 'media' => '(min-width: 576px)' ],
	]

);

?>
<section class="picture-teaser-container-m slick-initialized slick-slider" data-autoplay="false"
         data-slick="{'accessibility': true, 'arrows': false, 'dots': true, 'infinite': true, 'speed': 600, 'slidesToShow': 1, 'autoplaySpeed': 2400, 'fade': true, 'autoplay': false}">
	<figure class="picture-teaser-m" data-slick-index="0" aria-hidden="false" style="" role="option"
	        aria-describedby="slick-slide50">
		<?php e_wpp_fr_image_html( $imgs, $params ); ?>

		<figcaption class="container-fluid responsive-gutter">
			<div class="row picture-teaser-content align-items-center justify-content-center">
				<div class="col-10 col-md-8 col-lg-6 picture-teaser-caption light text-center">
					<h2 class="font-size-medium"><?php echo $title; ?></h2>
					<p></p>
					<p class="form--row">
						<a href="<?php echo wpp_sanitize_link( $btn_link ); ?>" target="_blank"
						   class="form--button-light picture-teaser-button" tabindex="0">
							<span><?php echo $btn_title; ?></span>
						</a>

					</p>

				</div>
			</div>
		</figcaption>
	</figure>

</section>