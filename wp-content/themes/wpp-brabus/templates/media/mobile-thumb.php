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
?>

    <figure class="swiper-container <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>" data-img="<?php echo $args['post']['id']; ?>">
        <a href="<?php echo $args['post']['link']; ?>" class="swiper-wrapper grid-teaser-shadow">
			<?php
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
				echo '<div class="swiper-slide">';
				printf( $img, $img_one['src'], $img_one['alt'], $img_one['class'], 'wpp-ims-w-' . $i, $aa );
				echo '</div>';
				#printf( '<span class="imgg-wp-bull img-bull-w-%s img-wpp-%s" data-i="%s"></span>', count( $imgs ), $aa, $aa );
				$aa ++;
			}
			?>
        </a>
        <div class="swiper-pagination"></div>
    </figure>

<?php
wpp_get_template_part( 'templates/thumb/figcaption', [
	'post'           => $args['post'],
	'attachment_ids' => $args['attachment_ids'],
	'type'           => $args['type'],
	'meta'           => $args['meta']
] );
