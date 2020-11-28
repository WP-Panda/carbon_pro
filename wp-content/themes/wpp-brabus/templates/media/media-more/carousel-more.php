<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="grid-teaser">
    <div class="wpp-grid_imgs">
        <figure class="grid-teaser-image">
            <a href="<?php echo get_home_url() . '/new-products/'; ?>" class="grid-teaser-shadow more-slider-after">
                <span class="learn-more-wrap">
                    <span class="slider-more-text">
                        <?php e_wpp_br_lng( 'learn_more' ); ?>
                    </span>
                </span>
				<?php
				$img = get_the_post_thumbnail_url( $args['post']->ID, 'full' );
				e_wpp_fr_image_html( $img, wpp_br_thumb_array() );
				?>
            </a>
        </figure>
    </div>
</div>