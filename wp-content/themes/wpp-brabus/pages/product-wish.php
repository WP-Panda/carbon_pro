<?php
/*
	Template Name: Product Wish
	Template Post Type: page
	*/
defined( 'ABSPATH' ) || exit;

get_header();
global $wpp_wl;
$result      = $wpp_wl->get_items();
$posts_array = [];
foreach ( $result as $one_item ) :
	$posts_array[ $one_item->item_cat ][] = $one_item->item_id;
endforeach;

$view          = ( ! empty( $_COOKIE['wpp_view'] ) && $_COOKIE['wpp_view'] === 'list' ) ? 'list' : 'grid';
$paged         = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$post_per_page = 18;
$offset        = ( $paged - 1 ) * $post_per_page;

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
                <section class="parts-category">
					<?php

					$count  = count( $posts_array );
					$offset = ( $paged - 1 ) * $post_per_page;
					$pages  = array_chunk( $posts_array, 18 );

					if ( ! empty( $posts_array ) ) :

						foreach ( $posts_array as $term_key => $posts ) :


							?>
                            <section
                                    class="container-fluid responsive-gutter section-padding-small col-min-height wl-container">
								<?php

								$terms  = get_term_parents_list( $term_key, 'product_cat' );
								$string = ! is_wp_error( $terms ) ? trim( $terms, '/' ) : __( 'All', 'wpp-br' );
								printf( '<h3 class="font-size-small text-uppercase headline-padding-small wpp-wl-title">%s</h3>', $string );

								$query = new WP_Query(
									[
										'post_type'      => 'product',
										'taxonomy'       => 'product_cat',
										'post__in'       => $posts,
										'posts_per_page' => - 1,
									]
								);

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
									wpp_get_template_part( 'templates/product-cat/product-' . $view, [ 'post'    => $post_array,
									                                                                   'term_id' => $term_key
									] );

								endforeach;
								wp_reset_query();

								if ( $view === 'grid' ) {
									echo '</div>';
								} ?>
                            </section>
						<?php
						endforeach;

						/**/
					endif;

					/*$big = 999999999; // уникальное число

					$pag = paginate_links( array(
						'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'  => '?paged=%#%',
						'current' => max( 1, get_query_var( 'paged' ) ),
						'total'   => ceil( $count / $post_per_page )
					) );
					printf( '<div class="row pag-row">%s</div>', $pag );*/
					?>
                </section>
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