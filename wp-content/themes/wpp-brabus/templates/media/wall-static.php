<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

extract( $args );
$pages = ! empty( $posts_per_page ) && ! wp_is_mobile() ? $posts_per_page : ( wp_is_mobile() ? 4 : 8 );

if ( ! is_archive( 'product_cat' ) ) {

	$car_args = [
		'post_type'      => $post_type,
		'posts_per_page' => $pages,
		'order'          => 'ASC'
	];

	if ( 'project' !== $post_type ) {
		$car_args['orderby']  = 'meta_value';
		$car_args['meta_key'] = '_sold';
	}

} else {

	$car_args = $args;

}

$posts = get_posts( $car_args );

if ( ! is_archive( 'product_cat' ) ) {
	wpp_get_template_part( 'templates/globals/head-line', [
		'title' => wpp_br_lng( $title_key )
	] );
}

$html = '<section class="row wpp-fancy-box-gallery">';
$a    = 1;
foreach ( $posts as $post ) {
	setup_postdata( $post );

	ob_start();
	if ( 'sale_car' === $post_type ) :
		$_price = get_post_meta( $post->ID, '_price', true );

		if ( ! empty( $_price ) ) {
			$price = wpp_return_price( $_price );
		} else {
			$price = '-';
		}

		$_mileage = get_post_meta( $post->ID, '_mileage', true );
		$_power   = get_post_meta( $post->ID, '_power', true );
		?>
        <div class="grid-teaser-details structured-content">
            <h4 class="grid-headline-icon"><?php echo get_the_title( $post->ID ); ?></h4>
            <p><?php echo get_post_meta( $post->ID, '_subtitle', true ); ?></p>
            <table class="structured-table">
                <tbody>
                <tr>
                    <td class="key"><?php e_wpp_br_lng( 'gross' ); ?></td>
                    <td class="value"><span
                                class=""><?php echo wc_price( $price ); ?></span><br><span
                                class=""><?php e_wpp_br_lng( 'in_vat' ); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="key"><?php e_wpp_br_lng( 'neus' ); ?></td>
                    <td class="value"></td>
                </tr>
                <tr>
                    <td class="key"><?php e_wpp_br_lng( '_mileage' ); ?></td>
                    <td class="value"><?php echo ! empty( $_mileage ) ? $_mileage : '-'; ?></td>
                </tr>
                <tr>
                    <td class="key"><?php e_wpp_br_lng( '_power' ); ?></td>
                    <td class="value"><?php echo ! empty( $_power ) ? $_power : '-'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
	<?php
	else: ?>
        <div class="grid-teaser-details structured-content">
            <h4 class="grid-headline-icon"><?php echo get_the_title( $post->ID ); ?></h4>
            <p><?php echo get_post_meta( $post->ID, '_subtitle', true ); ?></p>
        </div>
	<?php endif;

	$htmls = ob_get_clean();

	$url = get_the_post_thumbnail_url( $post->ID, 'full' );

	if ( (int) $pages === $a ) {

		$params = [
			'class' => 'wpp-fancy-gallery-thumb more-slider',
		];

		$cropped = e_wpp_fr_image_html( $url, wpp_br_thumb_array( $params ), true );
		$html    .= sprintf( '<a href="%s" class="col-6 col-xs-6 col-sm-4 col-md-3 more-slider-after wpp-grid-slide" aria-selected="true"><span class="learn-more-wrap"><span class="slider-more-text">%s</span></span>%s</a>', get_home_url() . '/' . $title_key . '/', wpp_br_lng( 'learn_more' ), $cropped );

	} else {

		$over   = 'project' === $post_type ? ' overlayed' : '';
		$params = [
			'wrap'  => '<div class="col-6 col-xs-6 col-sm-4 col-md-3 wpp-static-grid-item' . $over . '" aria-selected="true"><a href="' . get_the_permalink( $post->ID ) . '">%s' . $htmls . '</a></div>',
			'class' => 'wpp-fancy-gallery-thumb',
		];
		$html   .= e_wpp_fr_image_html( $url, wpp_br_thumb_array( $params ), true );
	}

	if ( 8 === $a ) {
		break;
	}

	$a ++;
	wp_reset_postdata();

}

$html .= '</section>';
echo $html;