<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

extract( $args );

$img = wpp_fr_get_youtube_video_thumb( $video_id );

if ( $device_count === $a ) {
	$html .= sprintf( '<div class="col-6 col-xs-6 col-sm-3 col-md-2 more-slider-after wpp-grid-slide" aria-selected="true"><span class="learn-more-wrap"><span class="slider-more-text">%s</span></span>%s</div>', wpp_br_lng( 'learn_more' ), e_wpp_fr_image_html( esc_url( $img ), wpp_br_thumb_array( [ 'class' => 'wpp-fancy-gallery-thumb more-slider' ] ), true ) );
}
$hide = $a >= $device_count ? 'wpp-hide' : '';

$html .= sprintf( '<div class="col-6 col-xs-6 col-sm-3 col-md-2 wpp-grid-slide video-play %s" %s> ', $hide, ! empty( $hide ) ? sprintf( 'data-hide="%s"', $hide ) : '' );
$html .= sprintf( '<a href="%s" class="wpp-gallery" ><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $one_group['video'] ), esc_url( $img ) );
$html .= '</div>';

echo $html;