<?php
if ( ! empty( $args['video'] ) || ! empty( $args['img'] ) ) :

	$n = $page = 1;

	$count_video = ! empty( $args['video'] ) ? count( $args['video'] ) : 0;
	$count_image = ! empty( $args['img'] ) ? count( $args['img'] ) : 0;
	$count       = $count_image + $count_video;
	$html        = '';
	?>
    <section class="wpp-slick wpp-fancy-box-gallery" tabindex="0">
		<?php if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) : ?>
        <div class="container-fluid wpp-wall-page" aria-selected="true" data-page="<?php echo $page; ?>">
            <section class="row ">
				<?php endif;

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

						$size_thumb = ! wp_is_mobile() && $args['type'] !== 'slider' ? '' : 'maxresdefault';

						$img = wpp_fr_get_youtube_video_thumb( $video_id, $size_thumb );

						if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) :
							$class="col-md-3";
						else:
							$class="wpp-slider-item";
						endif;

						$html .= ' <div class="' . $class . '" aria-selected="true">';
						$html .= sprintf( '<a href="%s" data-fancybox="gallery" ><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $one_group['video'] ), esc_url( $img ) );
						$html .= '</div>';
						$n ++;

						if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) :
							if ( $n % 16 === 1 && $n !== 1 && $n > $count ) {
								$page ++;
								$html .= sprintf( '</section></div><div class="wpp-wall-page data-page="%d"><section class="row wpp-fancy-box-gallery">', $page );
							}
						endif;
					endforeach;
				}

				if ( ! empty( $args['img'] ) ) {
					foreach ( $args['img'] as $one_img ) :

						if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) :
							$class="col-md-3";
						else:
							$class="wpp-slider-item";
						endif;

						$html .= ' <div class="' . $class . '" aria-selected="true">';

						$url = wp_get_attachment_image_src( $one_img, 'full' );
						if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) :
							$cropped = bfi_thumb( $url[0], [ 'width' => 640, 'height' => 480, 'crop' => true ] );
						else:
							$cropped = $url[0];
						endif;
						$html .= sprintf( '<a href="%s" data-fancybox="gallery"><img class="wpp-fancy-gallery-thumb" src="%s"/></a></picture>', esc_url( $url[0] ), esc_url( $cropped ) );

						$html .= '</div>';
						$n ++;
						if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) :
							if ( $n % 16 === 1 && $n !== 1 && $n < $count ) {
								$page ++;
								$html .= sprintf( '</section></div><div class="wpp-wall-page" data-page="%d"><section class="row wpp-fancy-box-gallery">', $page );
							}
						endif;
					endforeach;
				}

				echo $html;
				if ( ! wp_is_mobile() && $args['type'] !== 'slider' ) : ?>

            </section>
        </div>
	<?php
	endif; ?>
    </section>
<?php endif;