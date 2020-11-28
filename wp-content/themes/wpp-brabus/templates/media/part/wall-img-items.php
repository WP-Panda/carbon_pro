<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

extract( $args );

$html = '';

if ( ! empty( $img ) ) {
	foreach ( $img as $one_img ) :

		$url = wp_get_attachment_image_src( $one_img, 'full' );

		$original_w_wm = bfi_thumb( $url[0], [ 'water_mark' => true, 'crop' => true ] );

		if ( $device_count === $a ) {
			$html .= sprintf( '<div class="col-6 col-xs-6 col-sm-3 col-md-2 more-slider-after wpp-grid-slide" aria-selected="true"><span class="learn-more-wrap"><span class="slider-more-text">%s</span></span>%s</div>', wpp_br_lng( 'learn_more' ), e_wpp_fr_image_html( $url[0], wpp_br_thumb_array( [ 'class' => 'wpp-fancy-gallery-thumb more-slider' ] ), true ) );
		}

		$hide = $a >= $device_count ? 'wpp-hide' : '';
		$html .= sprintf( '<div class="col-6 col-xs-6 col-sm-3 col-md-2 wpp-grid-slide %s" %s>', $hide, ! empty( $hide ) ? sprintf( 'data-hide="%s"', $hide ) : '' );
		$html .= sprintf( '<a href="%s" class="wpp-gallery">%s</a>', esc_url( $original_w_wm ), e_wpp_fr_image_html( $url[0], wpp_br_thumb_array( [ 'class' => 'wpp-fancy-gallery-thumb' ] ), true ) );

		$html .= '</div>';

		$a ++;

	endforeach;
}

echo $html;