<?php
$ids = get_queried_object_id();

if ( is_singular() ) {
	$on     = get_post_meta( $ids, 'hero_on', true );
} else {
	$on = true;
}

if ( empty( $on ) ) :


	$slides = get_post_meta( $ids, 'hero', true );

	if ( ! empty( $slides ) ) :
		?>
        <section class="picture-teaser-container-xl fullscreen-slider <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>"
                 aria-hidden="true"
                 data-slick='{"accessibility": true, "arrows": false, "dots": true, "infinite": true, "speed": 1000, "slidesToShow": 1, "autoplaySpeed": 3500, "fade": true, "autoplay": true}'>
			<?php
			$one = 0;
			foreach ( $slides as $slide ) {

				$id = ! empty( $slide['video'] ) ? (int) $slide['video'][0] : (int) $slide['img'][0];

				$link   = wp_get_attachment_url( $id );
				$params = [
					'picture_class' => 'picture-teaser__media',
					'retina'        => true,
					'water_mark'    => true,
					'class'         => 'wpp-hero-img wpp-hero-img-' . $one,
					'sizes'         => [
						[
							'width'  => 1920,
							'height' => 1280,
							'media'  => '(min-width: 1200px)'
						],
						[
							'width'  => 1200,
							'height' => 800,
							'media'  => '(min-width: 992px)'
						],
						[
							'width'  => 990,
							'height' => 660,
							'media'  => '(min-width: 768px)'
						],
					]
				];
				?>
				<?php

				$locale        = get_locale();
				if ( ! empty( $link ) ) :
					$title = ! empty( $slide[ 'title' . $locale ] ) ? $slide[ 'title' . $locale ] : false;
					$btn_link  = ! empty( $slide[ 'btn_link' . $locale ] ) ? $slide[ 'btn_link' . $locale ] : false;
					$btn_title = ! empty( $slide[ 'btn_title' . $locale ] ) ? $slide[ 'btn_title' . $locale ] : false;
					?>

                    <figure class="dark picture-teaser--opaque">
						<?php if ( ! empty( $slide['video'] ) ) : ?>
                            <video class="picture-teaser__media" loop autoplay muted playsinline>
                                <source src="<?php echo $link; ?>"
                                        type="video/mp4"/>
                                Your browser does not support the video tag.
                            </video>
						<?php else: ?>
							<?php e_wpp_fr_image_html( $link, $params ); ?>
						<?php endif; ?>

                        <!--<button type="button" data-muted="true" class="picture-teaser__mute picture-teaser__mute--right"></button>-->
                        <figcaption class="container-fluid responsive-gutter">
                            <div class="row picture-teaser-content align-items-end align-items-sm-end justify-content-end justify-content-sm-end">
                                <div class="col-12 col-md-10 col-lg-8 picture-teaser-caption light text-right text-sm-right">
									<?php
									if ( ! empty( $title ) ) :
										printf( '<h2 class="font-size-small">%s</h2>', $title );
									endif;
									if ( ! empty( $title ) ) :
										printf( '<p class="form--row"><a href="%s" class="form--button-dark picture-teaser-button"><span>%s</span></a></p>', wpp_sanitize_link( $btn_link ), $btn_title );
									endif;
									?>
                                </div>
                            </div>
                        </figcaption>
                    </figure>

					<?php
					$one ++;
				endif;
			} ?>
        </section>
	<?php endif;

endif;