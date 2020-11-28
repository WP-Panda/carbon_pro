<?php
/**
 * 1.2.00 - Вывод картнки новым алгоритмом
 * 1.3.00 - Переезд на LG gallery
 */
if ( ! empty( $args['video'] ) || ! empty( $args['img'] ) ) :

	$html = '';
	$a = 1;
	$device_count = wp_is_mobile() ? 6 : 24;
	?>
    <section class="row wpp-fancy-box-gallery <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
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

				ob_start();
				wpp_get_template_part( 'templates/media/part/wall-video-items', [
					'video_id'          => $video_id,
					'a'            => $a,
					'device_count' => $device_count,
                    'one_group' => $one_group
				] );
				$a ++;
				$html .= ob_get_clean();


			endforeach;
		}

		ob_start();
		wpp_get_template_part( 'templates/media/part/wall-img-items', [
			'img'          => ! empty( $args['img'] ) ? $args['img'] : [],
			'a'            => $a,
			'device_count' => $device_count
		] );

		$html .= ob_get_clean();

		echo $html;
		?>
    </section>

<?php endif;