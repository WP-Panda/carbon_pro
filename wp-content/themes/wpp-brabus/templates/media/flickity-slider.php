<?php if ( ! empty( $args['img'] ) ) :
	$html = '';
	?>
    <section class="carousel flickity-enabled slider-fickity-slider <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>" tabindex="0">
		<?php
        $n=1;
		foreach ( $args['img'] as $url ) :
			$html .= ' <div class="carousel-cell carousel-color-dark is-selected" aria-selected="true">';
			$html .= sprintf( '<picture><img itemprop="image" src="%s" alt="%s"/></picture>', esc_url( $url[0] ), esc_html__( 'Awaiting product image', 'woocommerce' ) . $n );
			$html .= '</div>';
			$n++;
		endforeach;
		echo $html;
		?>
    </section>
<?php endif;