<?php

$terma                = get_queried_object();
$id_term              = $terma->term_id;
$output               = $navs = wpp_br_get_cat_products( $id_term );
$video                = get_term_meta( $id_term, 'standard', true );
$thumbnail_ids        = get_term_meta( $id_term, 'image_cat_gallery', false );
$slider_type          = get_term_meta( $id_term, 'type', true );
$slider_sanitize_type = empty( $slider_type ) || $slider_type !== 'wall' ? 'category' : 'wall';

$order_class = wpp_fr_user_is_admin() ? ' wpp-posts-ordering' : null;

$bundle = wpp_get_bundle_package();


$ordered = get_term_meta( $id_term, 'posts_order', true );
if ( ! empty( $ordered ) ) {
	$ordered = array_flip( $ordered );
}


if ( wpp_fr_user_is_admin() ) {
	$args       = [ 'post_type' => 'sale_car', 'nopaging' => true ];
	$query      = get_posts( $args );
	$cars_array = wp_list_pluck( $query, 'post_title', 'ID' );


	$args_2        = [ 'post_type' => 'project', 'nopaging' => true ];
	$query_2       = get_posts( $args_2 );
	$project_array = wp_list_pluck( $query_2, 'post_title', 'ID' );

	wp_enqueue_script( 'select2' );
	wp_enqueue_style( 'select2' );
}

if ( wpp_fr_user_is_admin() ) {
	printf( '<a class="wpp-sort-prod" href="%s"><img src="%s"></a>', get_admin_url() . 'edit.php?product_cat=' . $terma->slug . '&post_type=product', get_template_directory_uri() . '/assets/img/icons/edit.svg' );
}

usort( $output, function ( $a, $b ) {
	return ( $a['term_order'] - $b['term_order'] );
} );

usort( $navs, function ( $a, $b ) {
	return ( $a['term_order'] - $b['term_order'] );
} );

wpp_get_template_part( 'templates/media/wall-slider', [
	'img'   => $thumbnail_ids,
	'video' => $video,
	'type'  => $slider_sanitize_type
] );

if ( ! empty( $output ) ) :


	foreach ( $output as $one_tag ) :

		uasort( $one_tag, function ( $a, $b ) {
			if ( empty( $a['term_order'] ) ) {
				return;
			}

			return ( $a['term_order'] - $b['term_order'] );
		} ); ?>
        <a name="<?php echo $one_tag['div'] ?>"></a>
        <section class="parts-category" data-sticky-navigation="1">

			<?php
			echo wpp_br_st_nav( $navs, $one_tag['div'] );
			unset( $one_tag['name'] );
			unset( $one_tag['div'] );
			unset( $one_tag['term_order'] );

			foreach ( $one_tag as $child_tag ) :
				$key = '_' . $id_term . '_' . $child_tag['div'];
				$slider = get_term_meta( $id_term, '_' . $id_term . '_' . $child_tag['div'], true );

				?>
                <section class="container-fluid responsive-gutter section-padding-small col-min-height">

					<?php if ( ! empty( $child_tag['name'] ) ) : ?>
                        <h3 class="font-size-small text-uppercase headline-padding-small">
							<?php echo $child_tag['name']; ?>
							<?php if ( wpp_fr_user_is_admin() ) {

								echo sprintf( '<a class="wpp-cat-tag-edit" target="_blank" href="%sedit.php?post_type=product&product_cat=%s&product_tag=%s"><img src="%s"></a>', get_admin_url(), $terma->slug, $child_tag['div'], get_template_directory_uri() . '/assets/img/icons/edit.svg' );
								echo sprintf( '<a class="wpp-cat-img-edit wpp-tooltips" href="javasctipt:void(0);" data-name="%s" data-term="%s" data-title="Добавить Изображения Tега"><img src="%s"></a>', $child_tag['div'], $id_term, get_template_directory_uri() . '/assets/img/icons/add-img.png' );


								echo sprintf( '<a class="wpp-cat-slider-format wpp-tag-format-trigger format-slider wpp-tooltips" href="javasctipt:void(0);" data-key="%s_format" data-term="%s" data-format="slider" data-title="Формат Слайдер"><img src="%s"></a>', $key, $id_term, get_template_directory_uri() . '/assets/img/icons/slider.svg' );
								echo sprintf( '<a class="wpp-cat-slider-format wpp-tag-format-trigger format-greed wpp-tooltips" href="javasctipt:void(0);" data-key="%s_format" data-term="%s" data-format="wall" data-title="Формат Галерея"><img src="%s"></a>', $key, $id_term, get_template_directory_uri() . '/assets/img/icons/greed.svg' );
							} ?>
                        </h3>
					<?php endif; ?>

					<?php

					if ( ! empty( $slider ) ) {

						if ( wpp_fr_user_is_admin() ) {
							$template = 'admin-tools/admin-tag-gallery-slider';
						} else {

							$format = get_term_meta( $id_term, '_' . $id_term . '_' . $child_tag['div'] . '_format', true );

							$template = ! empty( $format ) && 'wall' === $format ? 'tag-wall-slider' : 'tag-gallery-slider';

						}

						wpp_get_template_part( 'templates/media/' . $template, [
							'imgs' => $slider,
							'key'  => $key,
							'term' => $id_term
						] );
					}
					?>

                </section>
				<?php if ( ! empty( $child_tag['posts'] ) ) { ?>
                <section class="container-fluid responsive-gutter section-padding-small col-min-height">
                    <div class="row<?php echo $order_class; ?>">
						<?php

						#wpp_dump( $child_tag['posts'] );
						if ( ! empty( $ordered ) ) {
							foreach ( $child_tag['posts'] as $key_or => $post_or ) {

								if ( isset( $ordered[ $post_or['id'] ] ) ) {
									$child_tag['posts'][ $key_or ]['order'] = $ordered[ $post_or['id'] ];
								}

							}
						}
						#wpp_dump( $child_tag['posts']);


						usort( $child_tag['posts'], function ( $a, $b ) {

							if ( $a['type'] == $b['type'] ) {
								return ( $a['order'] - $b['order'] );
							}

							if ( $a['type'] == 'bundle' ) {
								return - 1;
							} else if ( $b['type'] == 'bundle' ) {
								return 1;
							} else {
								return ( $a['order'] - $b['order'] );
							}

						} );

						foreach ( $child_tag['posts'] as $post ) :


							wpp_get_template_part( 'templates/product-cat/product-grid', [
								'post'          => $post,
								'term_id'       => $id_term,
								'bundle'        => $bundle,
								'cars_array'    => $cars_array,
								'project_array' => $project_array,
							] );
						endforeach;
						?>
                    </div>
                </section>
			<?php }
			endforeach;
			?>
        </section>
	<?php
	endforeach;
endif;
$terms = wpp_get_term_parents_list( $id_term, 'product_cat' );
//wpp_dump($id_term);

$car_models_line = $terms[1] ?? false;

if ( ! empty( $car_models_line ) ) {

	$car_args = [
		'post_type' => 'project',
		'nopaging'  => true,
		'order'     => 'ASC',
		'tax_query' => [
			[
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => [ $id_term ]
				//'terms'    => [ $car_models_line['term_id'] ]
			]
		]
	];

	ob_start();
	wpp_get_template_part( 'templates/media/wall-static', $car_args );
	$echo = ob_get_clean();

	if ( ! empty( strip_tags( $echo ) ) ) { ?>
        <section class="container-fluid responsive-gutter section-padding-small col-min-height">
            <h3 class="font-size-small text-uppercase headline-padding-small">
				<?php printf( wpp_br_lng( 'project_for' ), $terms[0]['name'], $car_models_line['name'] ); ?>
            </h3>
        </section>
		<?php
		echo $echo;
	}
}
