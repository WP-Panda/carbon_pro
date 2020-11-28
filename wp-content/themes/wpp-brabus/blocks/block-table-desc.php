<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
global $post;
$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$loc = !empty($flag) ? strtolower( get_locale() ) : 'ru_ru';

$field_key = is_singular( 'project' ) ? 'project' : 'sale';

$in = get_post_meta( $post->ID, '_in_' . $field_key . '_package', true );

$main_ID = $post->ID;

$flag = ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || is_admin() ? '-admin' : '';

$args_cat = [
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => 0,
	'taxonomy'   => 'product_tag',
	'parent'     => 0
];

$categories = get_terms( $args_cat );

ob_start();
foreach ( $categories as $category ) :

	$post_args = [
		'include'   => $in,
		'post_type' => 'product',
		'tax_query' => [
			[
				'taxonomy' => 'product_tag',
				'field'    => 'id',
				'terms'    => [ $category->term_id ]
			]
		]
	];

	$car_posts = get_posts( $post_args );

	if ( ! empty( $car_posts ) ) :

		printf( '<h3 class="font-size-small text-uppercase col-sm-12 wpp-tun-title">%s</h3>', $category->name );

		if ( ! empty( $flag ) ) {
			printf( '<ul>' );
		}

		if ( is_singular( 'sale_car' ) && wpp_fr_user_is_admin() && empty( $flag ) ) :
			printf( '<div class="row wpp-sortable">' );
		endif;

		foreach ( $car_posts as $post ) {
			setup_postdata( $post );

			$product = wc_get_product( $post->ID );
			$img     = get_the_post_thumbnail_url( $post->ID, 'full' );
			$price   = $product->get_price();
			if ( empty( $img ) ) {
				$img = wc_placeholder_img_src( 'woocommerce_single' );
			}


			$post_array = [
				'id'    => $post->ID,
				'link'  => get_the_permalink( $post->ID ),
				'title' => $post->post_title,
				'img'   => $img,
				'price' => $price,
			];


			wpp_get_template_part( 'templates/product-cat/product-grid' . $flag, [
				'post'   => $post_array,
				'parent' => $main_ID
			] );


		}
		if ( is_singular( 'sale_car' ) && wpp_fr_user_is_admin() && empty( $flag ) ) :
			printf( '</div>' );
		endif;

		if ( ! empty( $flag ) ) {
			printf( '</ul>' );
		}

	endif;
	wp_reset_postdata();


endforeach;
$content = ob_get_clean();

if ( ! empty( $content ) ) {
	$content = sprintf( '<div class="row wpp-cart-need">%s</div>', $content );
}

if ( empty( $args['return_content'] ) ) {
	wpp_get_template_part( 'templates/part/accordion', [
		'head'    => wpp_br_lng( 'tuning_features' ),
		'content' => $content
	] );
} else { ?>
    <section class="container-fluid responsive-gutter section-padding-small col-min-height">
        <div class="container">
			<?php
			echo $content;
			?>
        </div>
    </section>
	<?php
}
?>
</section>
<section class="form--row section-padding-large text-center">
    <a class="add_all_products_to_cart form--button-dark form--button--cta auto"
       href="javascript:void(0);"><?php e_wpp_br_lng( 'add_all_project' ); ?></a>
</section>