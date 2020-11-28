<?php
if ( ! empty( $args['video'] ) || ! empty( $args['img'] ) ) :
	$html = '';
	?>
    <section class="carousel flickity-enabled is-draggable" tabindex="0">
		<?php
		if ( ! empty( $args['img'] ) ) {
			foreach ( $args['img'] as $one_img ) :
				$url  = wp_get_attachment_image_src( $one_img, 'full' );
				$html .= ' <div class="carousel-cell carousel-color-dark is-selected" aria-selected="true">';
				$html .= sprintf( '<picture><a href="%s" data-fancybox="gallery"><img src="%s" alt=""/></a></picture>', esc_url( $url[0] ), esc_url( $url[0] ) );
				$html .= '</div>';
			endforeach;
		}


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

				$img = wpp_fr_get_youtube_video_thumb( $video_id,'maxresdefault' );
				$html .= '<div class="carousel-cell carousel-color-dark is-selected" aria-selected="true">';
				$html .= sprintf( '<picture><a href="%s" data-fancybox="gallery" ><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $one_group['video'] ), esc_url( $img ) );
				$html .= '</div>';

			endforeach;
		}
		echo $html;
		?>
    </section>
<?php endif;