<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! is_admin() ) {
	?>
    <figcaption>
		<?php if ( wpp_fr_user_is_admin() && ! is_home() && ! is_front_page() && ! is_singular() ) : ?>
            <div class="images-links">
                <ul>
					<?php
					$n = 1;
					if ( ! empty( $args['imgs'] ) ) :
						foreach ( $args['imgs'] as $img_one ) {
							printf( '<li><a target="_blank" href="%s">%d</a></li>', $img_one, $n );
							$n ++;
						}
					endif; ?>
                </ul>
            </div>

            <ul class="admin-actions">
                <li class="wpp-del-post-li">
							<?php wpp_delete_post_link( $args['post']['id'], '' ); ?>
                        </li>
                <li class="wpp-ed-post-li">
							<?php wpp_edit_post_link( $args['post']['id'] ); ?>
                        </li>
            </ul>

		<?php endif; ?>
		<?php if ( $args['type'] === 'bundle' ) :
			printf( '<span class="grid-teaser-badge">%s</span>', wpp_br_lng( 'package' ) );
		endif; ?>
		<?php #wpp_get_template_part( 'templates/product-cat/partitions/round-action-group', [] ); ?>

        <h4 class="grid-headline-icon"><?php echo $args['post']['title']; ?></h4>

		<?php if ( 'bundle' === $args['type'] ) { ?>
            <p style="margin-bottom: 5px;">
            <span>
                <?php echo wc_price( wpp_br_bundle_price( $args['meta'], $args['post']['id'] ) ); ?>
                <a class="wpp-add-cart-bundle"
                   href="<?php echo wpp_br_bundle_cart_link( $args['meta'], $args['post']['id'] ); ?>">
                    <span style="margin-top: -3px;display: inline-block;float: right;">
                        <img class="wpp-plus-icon"
                             src="/wp-content/themes/wpp-brabus/assets/img/icons/plus.svg"
                             alt="">
                    </span>
                </a>
            </span>
            </p>
			<?php
			wpp_br_bundle_max_time( $args['meta'], $args['post']['id'] );
		} ?>
    </figcaption>
	<?php
}