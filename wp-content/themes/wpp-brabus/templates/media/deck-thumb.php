<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$images = [];
$args   = (array) $args;


if ( ! empty( $args->post ) ) {
	$args['post'] = $args->post;
}

if ( is_singular() || is_front_page() || is_home() || is_admin() ) {
	$post_ID                = ! empty( $args['post']->ID ) ? $args['post']->ID : $args['post']['id'];
	$post_link              = get_the_permalink( $post_ID );
	$images[]               = get_the_post_thumbnail_url( $post_ID, 'full' );
	$args['attachment_ids'] = $product->get_gallery_image_ids();
	$title                  = get_the_title( $post_ID );
} else {
	$post_ID   = $args['post']['id'];
	$post_link = $args['post']['link'];
	$images[]  = $args['post']['img'];
	$title     = $args['post']['title'];
}

$i = 1;

if ( ! empty( $args['attachment_ids'] ) ) {
	$i ++;
	foreach ( $args['attachment_ids'] as $attachment_id ) {
		$full_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		if ( $i <= 5 ) {
			$images[] = $full_src[0];
		}
		if ( $i === 5 ) {
			break;
		}
		$i ++;
	}
}


?>
    <figure class="grid-teaser-image <?php e_wpp_fr_file_class( __FILE__, 'wpp_' ); ?>" data-img="<?php echo $post_ID; ?>">

		<?php wpp_get_template_part( 'templates/admin-tools/cars4sale', [
			'cars_array' => $args['cars_array'],
			'id'         => $post_ID
		] );

		wpp_get_template_part( 'templates/admin-tools/projects', [
			'project_array' => $args['project_array'],
			'id'            => $post_ID
		] );

		if ( is_singular( [ 'sale_car', 'project' ] ) && wpp_fr_user_is_admin() ) {

			$class = is_singular( 'sale_car' ) ? 'wpp_remove_sale_car' : 'wpp_remove_project';
			printf( '<div class="wpp-ad-tools-remove %s wpp-tooltips" data-detail="%s" data-parent="%s" data-title="Удалить Деталь %s"><img src="%s"></div>', $class, $post_ID, $args['parent'], $post_ID, get_template_directory_uri() . '/assets/img/icons/cross.svg' );
		} ?>

        <a href="<?php echo $post_link ?>" class="grid-teaser-shadow">
			<?php


			$aa = 1;

			foreach ( $images as $img_one ) {
				$params['class'] = sprintf( 'wpp-mu-im img-fluid wpp-im-%s wpp-ims-w-%s', $aa, $aa );
				if ( isset( $args['lazy'] ) ) {
				    if($aa>1) {
					    $params['lazy'] = false;
                    } else {
					    $params['lazy'] = $args['lazy'];
				    }

				}
				e_wpp_fr_image_html( $img_one, wpp_br_thumb_array( $params ) );
				printf( '<span class="imgg-wp-bull img-bull-w-%s img-wpp-%s" data-i="%s"></span>', count( $images ), $aa, $aa );
				$aa ++;
			}

			?>
        </a>

    </figure>

<?php
wpp_get_template_part( 'templates/thumb/figcaption', [
	'post'           => [ 'id' => $post_ID, 'title' => $title ],
	'attachment_ids' => $args['attachment_ids'],
	'type'           => $args['type'],
	'meta'           => $args['meta'],
	'imgs'           => $images
] );