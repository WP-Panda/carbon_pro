<?php

$post = $args['post'];
if ( is_home() || is_front_page() || is_admin() ) {
	$post_id = is_array( $post ) ? $post_id : $post->ID;
} else {
	$post_id = ! empty( $post['id'] ) ? $post['id'] : $post->ID;
}

$product = wc_get_product( $post_id );

$attachment_ids = $product->get_gallery_image_ids();
$type           = $product->get_type();

?>
<?php if ( ! is_home() && ! is_front_page() && ! is_admin() ) : ?>
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 wpp-grid-item" data-prd_id="<?php echo $post_id; ?>">
<?php endif; ?>

    <div class="grid-teaser<?php echo $type === 'bundle' ? ' grid-teaser-package' : ''; ?>">
        <div class="wpp-grid_imgs">
			<?php

			$pref = ! wp_is_mobile() || is_home() || is_front_page() ? 'deck' : 'mobile';

			if ( 'bundle' !== $product->get_type() ) :

				wpp_get_template_part( 'templates/media/' . $pref . '-thumb',
					[
						'post'           => $post,
						'attachment_ids' => $attachment_ids,
						'type'           => $type,
						'meta'           => get_post_meta( $post_id, 'bundle_package', false ),
						'cars_array'     => ! empty( $args['cars_array'] ) ? $args['cars_array'] : [],
						'project_array'  => ! empty( $args['project_array'] ) ? $args['project_array'] : [],
						'parent'         => ! empty( $args['parent'] ) ? $args['parent'] : false,
                        'lazy' => isset($args['lazy']) && $args['lazy'] === false ? false : ''
					]
				);
			else:
				wpp_get_template_part( 'templates/media/bundle-thumb', [
					'post'           => $post,
					'attachment_ids' => $attachment_ids,
					'type'           => $type,
					'meta'           => get_post_meta( $post_id, 'bundle_package', false ),
					'lazy' => isset($args['lazy']) && $args['lazy'] === false ? false : ''
				] );
			endif;
			?>
        </div>
    </div>

<?php
def_costil( $post_id );
if ( $type !== 'bundle' ) {
	if ( ! is_admin() ) :
		wpp_br_create_time_info( $post_id );

	endif;

	if ( ! empty( $args['bundle'] ) ) :
		wpp_br_bundle_editor( $post_id, $args['bundle'] );
	endif;
}
?>

<?php if ( ! is_home() && ! is_front_page() && ! is_admin() ) : ?>
    </div>
<?php endif;