<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$flag  = defined( 'REST_REQUEST' ) && REST_REQUEST ? 0 : 1;
$query = new WP_Query( [
	'post_type' => 'post',
	'showposts' => (int) block_value( 'news_count' ) + $flag
] );

if ( $query->have_posts() ) : ?>
    <section class="container-fluid responsive-gutter section-padding-large homepage-grid-teaser">
        <div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post();

				$image = get_the_post_thumbnail_url( '', 'full' );

				?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">

                    <a href="<?php echo get_the_permalink(); ?>">

                        <div class="grid-teaser">
                            <div class="grid-teaser-image">
								<?php e_wpp_fr_image_html( $image, wpp_br_thumb_array() ); ?>
                            </div>
                            <h4>
								<?php the_title(); ?>
                            </h4>
                            <p>
                                <strong>
									<?php echo get_the_date( 'd.m.Y' ); ?>
                                </strong>
								<?php the_excerpt(); ?>
                            </p>
                        </div>

                    </a>

                </div>
			<?php endwhile; ?>

        </div>
    </section>

<?php endif;
wp_reset_query();