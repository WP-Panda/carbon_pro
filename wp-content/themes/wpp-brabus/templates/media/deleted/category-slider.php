<?php
if ( ! empty( $args['img'] ) ) :
	$html = '';
	?>
    <section class="carousel flickity-enabled slider-old-new" tabindex="0">
		<?php


		$params = [
			'retina'     => true,
			'water_mark' => true,
			'sizes'      => [
				[ 'height' => 780 ],
				[ 'height' => 1560 ]
			]
		];


		$slide = <<<SLIDE
            <div class="carousel-cell carousel-color-dark">
                <picture>
                    <source type="image/webp" srcset="%1\$s 1x, %5\$s 2x">
                    <source type="image/%2\$s" srcset="%3\$s 1x, %6\$s 2x">
                    <img class="wpp-cat-img-%4\$d" src="%3\$s"/>
                </picture>
            </div>
SLIDE;

		$n = 0;
		foreach ( $args['img'] as $one_img ) :
			$url  = wp_get_attachment_image_src( $one_img, 'full' );
			$img  = wpp_fr_image_bundle( $url[0], $params );
			$html .= sprintf( $slide, $img['webp']['780'], $img['ext'], $img['thumb']['780'], $n, $img['webp']['1560'], $img['thumb']['1560'] );
			$n ++;
		endforeach;
		echo $html;
		?>
    </section>
<?php endif;