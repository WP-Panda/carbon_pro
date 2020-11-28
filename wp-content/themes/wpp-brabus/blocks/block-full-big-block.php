<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$loc = !empty($flag) ? strtolower( get_locale() ) : 'ru_ru';

$imgs      = ! empty( block_value( 'img' ) ) ? wp_get_attachment_image_url( block_value( 'img' ), 'full' ) : '';
$title     = ! empty( block_value( 'title_' . $loc ) ) ? block_value( 'title_' . $loc ) : '';
$btn_link  = ! empty( block_value( 'btn_link_' . $loc ) ) ? block_value( 'btn_link_' . $loc ) : '';
$btn_title = ! empty( block_value( 'btn_title_' . $loc ) ) ? block_value( 'btn_title_' . $loc ) : '';


$params = array(
	'retina'     => true,
	'water_mark' => true,
	'sizes' => [
		[ 'width' => 1920, 'height' => 1080, 'media' => '(min-width: 1200px)' ],
		[ 'width' => 1200, 'height' => 680, 'media' => '(min-width: 992px)' ],
		[ 'width' => 992, 'height' => 546, 'media' => '(min-width: 576px)' ],
	]

);

?>
<section class="type-2 picture-teaser-container-xl slick-initialized slick-slider" data-autoplay="false"
         data-slick="{'accessibility':true,'arrows':false,'dots':true,'infinite':true,'speed':600,'slidesToShow':1,'autoplaySpeed':2400,'fade':true,'autoplay':false,'pauseOnHover':true}">
    <div aria-live="polite" class="slick-list draggable">
        <div class="slick-track" role="listbox">
            <figure class="picture-teaser-xl slick-slide slick-current slick-active" data-slick-index="0"
                    aria-hidden="false"
                    tabindex="-1" role="option">
	            <?php e_wpp_fr_image_html( $imgs, $params ); ?>
                <figcaption class="container-fluid responsive-gutter">
                    <div class="row picture-teaser-content align-items-center align-items-sm-end justify-content-center justify-content-sm-center">
                        <div class="col-12 col-md-10 col-lg-8 picture-teaser-caption light text-center text-sm-center">
                            <h2 class="font-size-medium"><?php echo $title; ?></h2>

                            <p class="form--row">
                                <a href="<?php echo $btn_link ?>'" target="_self"
                                   class="form--button-light picture-teaser-button" tabindex="0">
                                    <span><?php echo $btn_title; ?></span>
                                </a>
                            </p>
                        </div>
                    </div>
                </figcaption>
            </figure>
        </div>
    </div>
</section>