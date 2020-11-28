<?php
/*
	Template Name: Product Search
	Template Post Type: page
	*/
defined( 'ABSPATH' ) || exit;

get_header();
$view          = ( ! empty( $_COOKIE['wpp_view'] ) && $_COOKIE['wpp_view'] === 'list' ) ? 'list' : 'grid';
$paged         = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$post_per_page = 18;
$offset        = ( $paged - 1 ) * $post_per_page;

if ( ! empty( $_GET['product_search'] ) ) {

	$search = '%' . esc_sql( $_GET['product_search'] ) . '%';

	global $wpdb;
	$count = $wpdb->query( sprintf( "
     SELECT DISTINCT a.ID
	 FROM $wpdb->posts a
	 INNER JOIN $wpdb->postmeta b
	 WHERE a.post_type = 'product'
	 AND a.ID = b.post_id
	 AND ( a.post_title LIKE '%s'
	 OR a.post_content LIKE '%s'
	  OR b.meta_value LIKE '%s')
	 ", $search, $search, $search ), ARRAY_A );

	#записи
	$posts = $wpdb->get_results( sprintf( "
     SELECT DISTINCT a.ID
	 FROM $wpdb->posts a
	 INNER JOIN $wpdb->postmeta b
	 WHERE a.post_type = 'product'
	 AND a.ID = b.post_id
	 AND ( a.post_title LIKE '%s'
	 OR a.post_content LIKE '%s'
	  OR b.meta_value LIKE '%s')
	 LIMIT %s,%s", $search, $search, $search, $offset, $post_per_page ), ARRAY_A );


	$posts_array = wp_list_pluck( $posts, 'ID' );
	if ( $paged === 1 ) {
		#категории
		$cats        = $wpdb->get_results( sprintf( "
    SELECT DISTINCT a.term_id
	 FROM $wpdb->terms a
	 INNER JOIN $wpdb->term_taxonomy b
	 WHERE b.taxonomy = 'product_cat'
	 AND a.term_id = b.term_id
	 AND a.name LIKE '%s'
	 ", $search ), ARRAY_A );
		$terms_array = wp_list_pluck( $cats, 'term_id' );
	} else {
		$terms_array = [];
	}
}


/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>


	    <?php wpp_get_template_part( 'templates/globals/page-title', [] ); ?>

        <div class="content">

            <div class="neos-contentcollection">
				<?php if ( empty( $terms_array ) && empty( $posts_array ) ) { ?>
                    <div class="container-fluid responsive-gutter section-padding-small col-min-height">
                        <section class="container-fluid responsive-gutter section-padding-small col-min-height">
                            <h3 class="font-size-small text-uppercase headline-padding-small"><?php _e( 'Not results Found', 'wpp-br' ); ?></h3>
                        </section>
                    </div>
				<?php } ?>
				<?php $args = wp_parse_args(
					[],
					array(
						'before'    => apply_filters( 'woocommerce_before_output_product_categories', '' ),
						'after'     => apply_filters( 'woocommerce_after_output_product_categories', '' ),
						'parent_id' => 0,
					)
				);


				if ( ! empty( $terms_array ) ) :
					?>
                    <div class="container-fluid responsive-gutter section-padding-small col-min-height">
                        <div class="row">
                            <section class="container-fluid responsive-gutter section-padding-small col-min-height">
                                <h3 class="font-size-small text-uppercase headline-padding-small"><?php _e( 'Categories', 'wpp-br' ); ?></h3>
                            </section>

							<?php

							echo $args['before']; // WPCS: XSS ok.*/
							foreach ( $terms_array as $term ) {
								$category = get_term( $term );
								wc_get_template(
									'content-product_cat.php',
									array(
										'category' => $category,
									)
								);
							}

							echo $args['after']; // WPCS: XSS ok.*/
							?>
                        </div>
                    </div>
				<?php endif;


				if ( ! empty( $posts_array ) ) :
					#wpp_dump( $posts_array );
					?>
                    <section class="container-fluid responsive-gutter section-padding-small col-min-height">
                        <h3 class="font-size-small text-uppercase headline-padding-small"><?php _e( 'Products', 'wpp-br' ); ?></h3>


						<?php


						$query = new WP_Query(
							[
								'post_type'      => 'product',
								'taxonomy'       => 'product_cat',
								'post__in'       => $posts_array,
								'posts_per_page' => $post_per_page,
							]
						);

						#wpp_dump( $query->posts);

						if ( $view === 'grid' ) {
							echo '<div class="row">';
						}
						foreach ( $query->posts as $post ) :
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

							#wpp_dump( $post );
							wpp_get_template_part( 'templates/product-cat/product-' . $view, [ 'post'    => $post_array,
							                                                                   'term_id' => 0
							] );
						endforeach;
						wp_reset_query();


						if ( $view === 'grid' ) {
							echo '</div>';
						} ?>
                    </section>
				<?php
					/**/
				endif;

				$big = 999999999; // уникальное число

				$pag = paginate_links( array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'  => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) ),
					'total'   => ceil( $count / $post_per_page )
				) );
				printf( '<div class="row pag-row">%s</div>', $pag );
				?>
            </div>
        </div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
get_footer();