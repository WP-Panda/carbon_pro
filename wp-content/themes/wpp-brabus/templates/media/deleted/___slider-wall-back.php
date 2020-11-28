<?php
if ( ! empty( $args['video'] ) || ! empty( $args['img'] ) ) :

	$html = '';
	?>
    <section class="row wpp-fancy-box-gallery">
		<?php


		if ( ! empty( $args['video'] ) ) {
			foreach ( $args['video'] as $one_group ) :




				if ( empty( $one_group['video'] ) ) {
					continue;
				}
				$type = wpp_fr_get_ext_video_host( esc_url( $one_group['video'] ) );

				if ( $type !== 'youtube' ) {
					continue;
				}

				$data = wpp_fr_get_youtube_video_data( esc_url( $one_group['video'] ) );

				if ( empty( $data ) ) {
					continue;
				}

				$video_id = wpp_fr_get_youtube_video_id( $one_group['video'] );

				if ( empty( $video_id ) ) {
					continue;
				}

				$img = wpp_fr_get_youtube_video_thumb( $video_id );

				$html .= ' <div class="col-md-3" aria-selected="true">';
				$html .= sprintf( '<a href="%s" data-fancybox="gallery" ><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $one_group['video'] ), esc_url( $img ) );
				$html .= '</div>';

			endforeach;
		}

		if ( ! empty( $args['img'] ) ) {
			foreach ( $args['img'] as $one_img ) :

				$html .= ' <div class="col-md-3" aria-selected="true">';

				$url     = wp_get_attachment_image_src( $one_img, 'full' );
				$cropped = bfi_thumb( $url[0], [ 'width' => 640, 'height' => 480 ] );
				$html    .= sprintf( '<a href="%s" data-fancybox="gallery"><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $url[0] ), esc_url( $cropped ) );

				$html .= '</div>';

			endforeach;
		}

		echo $html;
		?>
    </section>
<?php endif;