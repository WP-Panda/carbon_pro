<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$flag = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;

$count  = ! empty( block_value( 'count' ) ) ? block_value( 'count' ) : 24;
$width  = ! empty( block_value( 'with' ) ) && ! wp_is_mobile() ? block_value( 'with' ) : ( wp_is_mobile() ? 2 : 6 );
$height = ! empty( block_value( 'height' ) ) && ! wp_is_mobile() ? block_value( 'height' ) : ( wp_is_mobile() ? 1 : 2 );

$module = $width * $height;

if ( 2 === (int) $width ) {
	$class = 'col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 wpp-news-block-front';
} elseif ( 3 === (int) $width ) {
	$class = 'col-6 col-sm-6 col-mdg-4 col-lg-4 col-xl-4 wpp-news-block-front';
} elseif ( 4 === (int) $width ) {
	$class = 'col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 wpp-news-block-front';
} else {
	$class = 'col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2 wpp-news-block-front';
}

$query_args = [
	'post_type'      => 'product',
	'posts_per_page' => (int) $count,
	'page'           => 1,
	'tax_query'      => [
		[
			'taxonomy' => 'product_type',
			'field'    => 'name',
			'terms'    => [ 'simple' ]
		]
	],
	// выборка по дате изменения записи
	'orderby'        => 'modified',
	'order'          => 'DESC',
	//выборка по дате производства
	'meta_query'     => [
		[
			'key'   => 'create_time',
			'value' => 1
		]
	]
];


$query_n = new WP_Query( $query_args );
$count   = $query_n->post_count;

if ( $query_n->have_posts() ) : $n = 1; ?>

    <script>
        /* <![CDATA[ */
        var WppHomeNews = {"height": "<?php echo $height; ?>", "width": "<?php echo $width; ?>"};
        /* ]]> */
    </script>

	<?php wpp_get_template_part( 'templates/globals/head-line', [
		'title' => wpp_br_lng( 'new_products' )
	] );
	?>

    <section class="container-fluid responsive-gutter section-padding-large homepage-grid-teaser hone-news-productcts">
        <div class="row wpp-news-row">
			<?php while ( $query_n->have_posts() ) : $query_n->the_post();

				wpp_get_template_part( 'templates/media/media-more/carousel-item', [
					'class'  => $class,
					'flag'   => $flag,
					'n'      => $n,
					'module' => $module,
					'count'  => $count,
					'lazy'   => false
				] );

				$n ++;
			endwhile; ?>
        </div>
    </section>

<?php endif;
wp_reset_query();