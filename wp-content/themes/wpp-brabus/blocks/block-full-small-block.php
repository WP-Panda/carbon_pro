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
	'retina'       => true,
	'water_mark'   => true,
	'picture_wrap' => 'picture-teaser__media',
	'sizes'        => [
		[ 'width' => 1920, 'height' => 270, 'media' => '(min-width: 1200px)' ],
		[ 'width' => 1200, 'height' => 270, 'media' => '(min-width: 992px)' ],
		[ 'width' => 992, 'height' => 270, 'media' => '(min-width: 768px)' ],
		[ 'width' => 768, 'height' => 360, 'media' => '(min-width: 576px)' ],
		[ 'width' => 576, 'height' => 360, 'media' => '(min-width: 576px)' ],
	]

);
$img    = wpp_fr_image_bundle( $imgs, $params );
?>

<section class="teaser-container-newsletter">
    <figure class="teaser-newsletter">
	    <?php e_wpp_fr_image_html( $imgs, $params ); ?>
        <figcaption class="container-fluid responsive-gutter">
            <div class="row align-items-center justify-content-center picture-teaser-content">
                <div class="col-12 picture-teaser-caption"><p><span
                                style="text-align:center;">Stay up to date !</span><a
                                href="https://clients.cetrez.com/brabus/signup/en/"
                                class="form--button-light picture-teaser-button">BRABUS News</a></p></div>
            </div>
        </figcaption>
    </figure>
</section>