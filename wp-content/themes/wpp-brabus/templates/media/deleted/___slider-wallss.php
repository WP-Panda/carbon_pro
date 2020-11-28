<?php if ( ! empty( $args['video'] ) ) :

	$html = '';
	?>
    <section class="row wpp-fancy-box-gallery">
		<?php
		$n = 1;
		foreach ( $args['video'] as $one_group ) :

			if ( empty( $one_group['type'] ) ) {
				continue;
			}

			$caption = ! empty( $one_group['caption'] ) ? esc_html( $one_group['caption'] ) : '';

			$html .= ' <div class="col-md-2" aria-selected="true">';
			if ( $one_group['type'] === 'img' ) :
				if ( empty( $one_group['img'][0] ) ) {
					continue;
				}
				$url     = wp_get_attachment_image_src( $one_group['img'][0], 'full' );
				$cropped = bfi_thumb( $url[0], [ 'width' => 640, 'height' => 480 ] );
				$html    .= sprintf( '<a href="%s" data-fancybox="gallery" data-caption="%s"><img class="wpp-fancy-gallery-thumb" src="%s" alt="%s"/></a></picture>', esc_url( $url[0] ), $caption, esc_url( $cropped ), $caption );
            elseif ( $one_group['type'] === 'video' ) :

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


				$html .= sprintf( '<a href="%s" data-fancybox="gallery" data-caption="%s"><img class="wpp-fancy-gallery-thumb" src="%s" alt="%s"/></a></picture>', esc_url( $one_group['video'] ), $caption, esc_url( $img ), $caption );
			endif;
			$html .= '</div>';

		endforeach;
		$html .= ' <div class="col-md-2" aria-selected="true">';
		$html .= '</div>';
		echo $html;
		?>
    </section>
<?php endif;