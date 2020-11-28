<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$img  = <<<IMG
                      <img src="%s" alt="%s" class="wpp-mu-im img-fluid %s %s" data-i="%s">
IMG;
$imgs = [];

$img_main = get_the_post_thumbnail_url( $args['post']['id'] );

?>
    <figure class="grid-teaser-image" data-img="<?php echo $args['post']['id']; ?>">
        <a href="<?php echo $args['post']['link']; ?>" class="grid-teaser-shadow">
			<?php
			if ( ! empty( $img_main ) ) :
				$i      = 1;
				$imgs[] = [
					'src'   => bfi_thumb( $args['post']['img'], [
						'width'  => 540,
						'height' => 360,
						'crop'   => true
					] ),
					'alt'   => $args['post']['title'],
					'class' => 'wpp-im-' . $i
				];


				if ( ! empty( $args['attachment_ids'] ) ) {
					$i ++;
					foreach ( $args['attachment_ids'] as $attachment_id ) {
						$full_src = wp_get_attachment_image_src( $attachment_id, 'full' );
						if ( $i <= 5 ) {
							$imgs[] = [
								'src'   => bfi_thumb( $full_src[0], [
									'width'  => 540,
									'height' => 360,
									'crop'   => true
								] ),
								'alt'   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
								'class' => 'wpp-im-' . $i
							];
						}
						if ( $i === 5 ) {
							break;
						}
						$i ++;
					}
				}

				$aa = 1;
				foreach ( $imgs as $img_one ) {
					#echo '<div class="swiper-slide">';
					printf( $img, $img_one['src'], $img_one['alt'], $img_one['class'], 'wpp-ims-w-' . $i, $aa );
					#echo '</div>';
					printf( '<span class="imgg-wp-bull img-bull-w-%s img-wpp-%s" data-i="%s"></span>', count( $imgs ), $aa, $aa );
					$aa ++;
				}
			else:

				$meta_count = count( $args['meta'] );

				if ( $meta_count >= 4 ) {
					$meta_class = 4;
				} elseif ( $meta_count === 1 ) {
					$meta_class = 1;
				} elseif ( $meta_count === 2 ) {
					$meta_class = 2;
				} elseif ( $meta_count === 3 ) {
					$meta_class = 3;
				}

				$m_c = 1;
				foreach ( $args['meta'] as $one ) {
					$url = get_the_post_thumbnail_url( $one );

					if ( $meta_count === 2 ) {
						$w = 200;
						$h = 286;
					} elseif ( $meta_count === 3 ) {
						if ( $m_c !== 3 ) {
							$w = 200;
							$h = 138;
						} else {
							$w = 420;
							$h = 138;
						}
					} else {
						$w = 200;
						$h = 138;
					}

					$src = bfi_thumb( $url, [
						'width'  => $w,
						'height' => $h,
						'crop'   => true
					] );

					$more_class = $meta_count > 4 && $m_c === 4 ? ' wpp_b_more' : '';
					printf( '<img src="%s" class="wpp-bundle-img wpp-b-img-%s%s" alt="">', $src, $meta_class, $more_class );

					$m_c ++;
					if ( $m_c === 5 ) {
						#break;
					}
				}
			endif;
			?>
        </a>
    </figure>


<?php
wpp_get_template_part( 'templates/thumb/figcaption', [
	'post'           => $args['post'],
	'attachment_ids' => $args['attachment_ids'],
	'type'           => $args['type'],
	'meta'           => $args['meta'],
	'imgs'           => $imgs
] );
