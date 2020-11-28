<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$loc = !empty($flag) ? strtolower( get_locale() ) : 'ru_ru';

$params = array(
	'retina'     => true,
	'water_mark' => true,
	'picture_wrap' => 'picture-teaser__media',
	'sizes' => [
		[ 'width' => 960, 'height' => 600, 'media' => '(min-width: 1200px)' ],
		[ 'width' => 600, 'height' => 500, 'media' => '(min-width: 992px)' ],
		[ 'width' => 500, 'height' => 480, 'media' => '(min-width: 576px)' ]
	]
);

$imgs      = ! empty( block_value( 'img_l' ) ) ? wp_get_attachment_image_url( block_value( 'img_l' ), 'full' ) : '';
$title     = ! empty( block_value( 'title_l_' . $loc ) ) ? block_value( 'title_l_' . $loc ) : '';
$btn_link  = ! empty( block_value( 'btn_link_l_' . $loc ) ) ? block_value( 'btn_link_l_' . $loc ) : '';
$btn_title = ! empty( block_value( 'btn_title_l_' . $loc ) ) ? block_value( 'btn_title_l_' . $loc ) : '';



$imgs_r      = ! empty( block_value( 'img_r' ) ) ? wp_get_attachment_image_url( block_value( 'img_r' ), 'full' ) : '';
$title_r     = ! empty( block_value( 'title_r_' . $loc ) ) ? block_value( 'title_r_' . $loc ) : '';
$btn_link_r  = ! empty( block_value( 'btn_link_r_' . $loc ) ) ? block_value( 'btn_link_r_' . $loc ) : '';
$btn_title_r = ! empty( block_value( 'btn_title_r_' . $loc ) ) ? block_value( 'btn_title_r_' . $loc ) : '';


?>
<section class="container-fluid picture-teaser-container-m">
    <div class="row">
        <figure class="col-12 col-sm-12 col-md-6 teaser-newsletter">
	        <?php e_wpp_fr_image_html( $imgs, $params ); ?>
            <figcaption class="container-fluid responsive-gutter">
                <div class="row align-items-center justify-content-center picture-teaser-content">
                    <div class="col-12 picture-teaser-caption">
                        <p>
                            <span style="text-align:center;">
                                <?php echo $title; ?>
                            </span>
                            <a href="<?php echo wpp_sanitize_link( $btn_link ); ?>"
                               class="form--button-light picture-teaser-button">
								<?php echo $btn_title; ?>
                            </a>
                        </p>
                    </div>
                </div>
            </figcaption>
        </figure>
        <figure class="col-12 col-sm-12 col-md-6 teaser-newsletter">
	        <?php e_wpp_fr_image_html( $imgs_r, $params ); ?>
            <figcaption class="container-fluid responsive-gutter">
                <div class="row align-items-center justify-content-center picture-teaser-content">
                    <div class="col-12 picture-teaser-caption">
                        <p>
                            <span style="text-align:center;">
                                <?php echo $title_r; ?>
                            </span>
                            <a href="<?php echo wpp_sanitize_link( $btn_link_r ); ?>"
                               class="form--button-light picture-teaser-button">
			                    <?php echo $btn_title_r; ?>
                            </a>
                        </p>
                    </div>
                </div>
            </figcaption>
        </figure>
    </div>
</section>