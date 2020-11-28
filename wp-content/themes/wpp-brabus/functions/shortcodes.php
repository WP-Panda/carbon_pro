<?php

function full_big_img( $atts ) {
	$atts = shortcode_atts( [
		'img'      => '',
		'align'    => 'right',
		'title_en' => '',
		'link_en'  => 'javascript:void(0);',
		'btn_en'   => 'text',
		'title_ru' => '',
		'link_ru'  => 'javascript:void(0);',
		'btn_ru'   => 'text',
		'xl'       => ''
	], $atts );

	$locale     = get_locale();
	$pref_array = explode( '_', $locale );
	$preff      = '_' . $pref_array[0];
	$ww         = ! empty( $atts['xl'] ) ? 'xl' : 'm';
	$img_fluid  = ! empty( $atts['xl'] ) && $atts['xl'] === 'xl' ? 'img-fluid' : '';

	$html = <<<HTML
					<figure class="picture-teaser-%7\$s" data-slick-index="0">
						<picture>
							<img class="%8\$s" src="%1\$s">
						</picture>
	
						<figcaption class="container-fluid responsive-gutter">
							<div class="row picture-teaser-content align-items-center justify-content-%2\$s">
								<div class="col-10 col-md-8 col-lg-6 picture-teaser-caption light text-%3\$s">
									<h2 class="font-size-medium">%4\$s</h2>
							
									<p class="form--row">
										<a href="%5\$s"
										   class="form--button-light picture-teaser-button" target="_blank" tabindex="0">
											<span>%6\$s</span>
										</a>
									</p>
								</div>
							</div>
						</figcaption>
					</figure>
HTML;
	if ( ! empty( $atts['img'] ) ) {
		$end   = $atts['align'] === 'right' ? 'end' : 'start';
		$right = $atts['align'] === 'right' ? 'right' : 'left';

		#return sprintf( $html, $atts['img'], $end, $right, $atts[ 'title' . $preff ], $atts[ 'link' . $preff ], $atts[ 'btn' . $preff ], $atts['xl']);
		return sprintf( $html, $atts['img'], 'center', 'center', $atts[ 'title' . $preff ], $atts[ 'link' . $preff ], $atts[ 'btn' . $preff ], $atts['xl'], $img_fluid );
	} else {
		return null;
	}
}


function full_small_img( $atts ) {
	$atts = shortcode_atts( [
		'img'      => '',
		'align'    => 'right',
		'title_en' => '',
		'link_en'  => 'javascript:void(0);',
		'btn_en'   => 'text',
		'title_ru' => '',
		'link_ru'  => 'javascript:void(0);',
		'btn_ru'   => 'text',
	], $atts );

	$locale     = get_locale();
	$pref_array = explode( '_', $locale );
	$preff      = '_' . $pref_array[0];

	$html = <<<HTML
			
				
					<figure class="teaser-newsletter">
						<picture class="picture-teaser__media">
							<img src="%1\$s">
						</picture>
	
						<figcaption class="container-fluid responsive-gutter">
							<div class="row align-items-center justify-content-center picture-teaser-content">
								<div class="col-12 picture-teaser-caption">
								<p>
									<span class="text-align:center;">%4\$s</span>
									
										<a href="%5\$s"
										   class="form--button-light picture-teaser-button" tabindex="0">
											%6\$s
										</a>
									</p>
								</div>
							</div>
						</figcaption>
					</figure>
				
			
HTML;
	if ( ! empty( $atts['img'] ) ) {
		$end   = $atts['align'] === 'right' ? 'end' : 'start';
		$right = $atts['align'] === 'right' ? 'right' : 'left';

		return sprintf( $html, $atts['img'], $end, $right, $atts[ 'title' . $preff ], $atts[ 'link' . $preff ], $atts[ 'btn' . $preff ], '100%' );
	} else {
		return null;
	}
}


function small_img( $atts ) {
	$atts = shortcode_atts( [
		'img'   => '',
		'align' => 'right',
		'title' => '',
		'link'  => 'javascript:void(0);',
		'btn'   => 'text',
	], $atts );


	$html = <<<HTML
			<section class="picture-teaser-container-m slick-initialized slick-slider col-md-6 col-sm-12"
                                 data-autoplay="false" style="padding: 0;">
				<figure class="picture-teaser-m slick-slide slick-current slick-active" data-slick-index="0"
						aria-hidden="false" style="width: %7\$s">
					<picture>
						<img src="%1\$s">
					</picture>

					<figcaption class="container-fluid responsive-gutter">
						<div class="row picture-teaser-content align-items-start justify-content-%2\$s">
							<div class="col-10 col-md-8 col-lg-6 picture-teaser-caption dark text-%3\$s">
								<h2 class="font-size-x-large">%4\$s</h2>
								<p></p>

								<p class="form--row">
									<a href="%5\$s"
									   class="form--button-dark picture-teaser-button" tabindex="0">
										<span>%6\$s</span>
									</a>
								</p>
							</div>
						</div>
					</figcaption>
				</figure>
			</section>
HTML;
	if ( ! empty( $atts['img'] ) ) {
		$end   = $atts['align'] === 'right' ? 'end' : 'start';
		$right = $atts['align'] === 'right' ? 'right' : 'left';

		return sprintf( $html, $atts['img'], $end, $right, $atts['title'], $atts['link'], $atts['btn'], '100%' );
	} else {
		return null;
	}
}

add_shortcode( 'full_big_img', 'full_big_img' );
add_shortcode( 'full_small_img', 'full_small_img' );