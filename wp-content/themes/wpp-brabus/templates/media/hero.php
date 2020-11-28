<?php

$ids      = get_queried_object_id();
$on       = is_singular() ? get_post_meta( $ids, 'hero_on', true ) : true;
$locale   = get_locale();
$url_info = wpp_fr_parse_url( wpp_fr_actual_link() );


if ( empty( $on ) ) :

	$slides = get_post_meta( $ids, 'hero', true );

	if ( ! empty( $slides ) ) :
		?>

        <section
                class="picture-teaser-container-xl fullscreen-slider slider-main <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>">
			<?php
			$one               = 0;
			foreach ( $slides as $slide ) :
				$id = ! empty( $slide['video'] ) ? (int) $slide['video'][0] : (int) $slide['img'][0];
				$link          = wp_get_attachment_url( $id );

				if ( ! empty( $link ) ) :
					$title = ! empty( $slide[ 'title' . $locale ] ) ? $slide[ 'title' . $locale ] : false;
					$btn_link  = ! empty( $slide[ 'btn_link' . $locale ] ) ? $slide[ 'btn_link' . $locale ] : false;
					$btn_title = ! empty( $slide[ 'btn_title' . $locale ] ) ? $slide[ 'btn_title' . $locale ] : false;

					if ( ! empty( $btn_link ) ) :
						$lang          = wpp_get_url_lng();
						$language      = ( ! empty( $lang ) && $lang === 'ru' ) ? 'ru.' : '';
						$currency_need = ! empty( $url_info['currency'] ) ? strtoupper( $url_info['currency'] ) : ( ! empty( $_SESSION['wpp_currency_new'] ) ? strtoupper( $_SESSION['wpp_currency_new'] ) : strtoupper( WPP_CUR_DEF ) );
						$redirect_url_change = $url_info['scheme'] . '://' . $language . $url_info['host'] . '/' . strtolower( $currency_need );
						$btn_link            = str_replace( '//' . WPP_DOMAIN, $url_info['scheme'] . '://' . $language . $url_info['host'] . '/' . strtolower( $currency_need ), $btn_link );
					endif;

					?>

                    <figure class="dark picture-teaser--opaque">

						<?php if ( ! empty( $slide['video'] ) ) :
							wpp_get_template_part( 'templates/media/video/video-file', [ 'video' => $link ] );
						else:
							e_wpp_fr_image_html( $link, wpp_br_hero_main_array( [], $one ) );
						endif; ?>

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
			endforeach; ?>
        </section>

        <div class="cur-text"></div>

        <section class="slider-nav <?php e_wpp_fr_file_class( __FILE__, 'wpp_nav_' ); ?>" aria-hidden="true">
			<?php
			$a        = 0;
			foreach ( $slides as $slide ) :
				$id = ! empty( $slide['video'] ) ? (int) $slide['video'][0] : (int) $slide['img'][0];
				$link = wp_get_attachment_url( $id );
				if ( ! empty( $link ) ) : ?>
                    <figure class="dark picture-teaser--opaque">
                        <span data-slick-index="<?php echo $a; ?>" class="progress"></span>
						<?php if ( ! empty( $slide['video'] ) ) :
							wpp_get_template_part( 'templates/media/video/video-file', [ 'video' => $link ] );
						else:
							e_wpp_fr_image_html( $link, wpp_br_hero_thumb_array() );
						endif; ?>
                    </figure>
				<?php endif;
				$a ++;
			endforeach; ?>
        </section>

	<?php endif;
endif;