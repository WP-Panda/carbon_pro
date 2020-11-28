<?php
/*
	Template Name: Product NEWS
	Template Post Type: page
	*/
defined( 'ABSPATH' ) || exit;

get_header();

$paged         = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$post_per_page = 24;


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

                <div class="container">
                    <div class="row">
                        <form id="wpp-form-filter"
                              action="<?php echo get_the_permalink( wpp_fr_get_page_id( 'pf' ) ); ?>"
                              method="get">
                            <div class="col-12 col-lg-2" style="display: inline-block;color: #fff">.</div>
							<?php echo wpp_navs_fllter(); ?>
                            <a href="<?php echo get_the_permalink( wpp_fr_get_page_id( 'pf' ) ); ?>"
                               class="form--button-dark form--button--cta col-12 col-sm-3 col-lg-3"><?php e_wpp_br_lng( 'clear_filter' ); ?></a>
                        </form>
                    </div>
                </div>

                <section class="container-fluid responsive-gutter section-padding-small col-min-height wl-container">
					<?php

					/*	$terms  = get_term_parents_list( $term_key, 'product_cat' );
						$string = ! is_wp_error( $terms ) ? trim( $terms, '/' ) : __( 'All', 'wpp-br' );
						printf( '<h3 class="font-size-small text-uppercase headline-padding-small wpp-wl-title">%s</h3>', $string );*/

					$query_args = [
						'post_type'      => 'product',
						'posts_per_page' => $post_per_page,
						'paged'          => $paged,
						'tax_query'      => [
							[
								'taxonomy' => 'product_type',
								'field'    => 'name',
								'terms'    => [ 'simple' ]
							]
						],
						'meta_query'     => [
							[
								'key'   => 'create_time',
								'value' => 1
							]
						]
					];

					$query_args['orderby'] = 'modified';
					$query_args['order']   = 'DESC';

					if ( ! empty( $_REQUEST['wpp-filter-brend'] ) || ! empty( $_REQUEST['wpp-filter-model'] ) ) {

						$ids = ! empty( $_REQUEST['wpp-filter-model'] ) ? $_REQUEST['wpp-filter-model'] : $_REQUEST['wpp-filter-brend'];

						$query_args['tax_query']['hh'] = [
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => [ (int) $ids ]
						];

						//$query_args['nopaging'] = true;
					}

					$query_n = new WP_Query( $query_args );


					echo '<div class="row">';

					foreach ( $query_n->posts as $post ) :
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
						wpp_get_template_part( 'templates/product-cat/product-grid', [
							'post' => $post_array,
							/*'term_id' => $term_key*/
						] );

					endforeach;


					echo '</div>';
					?>
                </section>
				<?php
				$big = 999999999;
				$pag =  paginate_links( array(
					'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'current' => max( 1, get_query_var( 'paged' ) ),
					'total'   => $query_n->max_num_pages
				) );

				printf( '<div class="row pag-row">%s</div>', $pag );
				?>
            </section>
        </div>
    </div>

<?php wp_reset_query();
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
get_footer();