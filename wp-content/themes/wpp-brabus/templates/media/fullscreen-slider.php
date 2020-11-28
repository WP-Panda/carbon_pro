<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1.2.00 Изменено формирование слайдера
 */

global $post;


$attachments = wpp_empty_array_clear( explode( ',', get_post_meta( $post->ID, '_wpp_post_gallery', true ) ) );

if ( ! empty( $attachments ) ) {
	?>
    <section class="picture-teaser-container-xl fullscreen-slider <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>" data-autoplay="true"
             data-slick='{"accessibility":true,"arrows":false,"dots":true,"infinite":true,"speed":600,"slidesToShow":1,"autoplaySpeed":2400,"fade":true,"autoplay":false,"pauseOnHover":true}'>
		<?php
		$i       = 0;

		foreach ( $attachments as $img_ID ) :
			$params['class'] = 'wpp-hero-img wpp-hero-img-' . $i . ' img-fluid';
			$url = wp_get_attachment_image_src( $img_ID, 'full' );
			?>
            <div>
                <figure class="picture-teaser-xl slick-slide">
					<?php e_wpp_fr_image_html( $url[0], wpp_br_fullscreen_array() ); ?>
                </figure>
            </div>
			<?php
			$i ++;
		endforeach; ?>
    </section>
<?php }