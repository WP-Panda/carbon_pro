<?php
$post = $args['post']; ?>

<?php

$edit = sprintf( '<img class="wpp-plus-icon" src="%s" alt="">', get_template_directory_uri() . '/assets/img/icons/edit.svg' );


$trash = sprintf( '<img class="wpp-trash-icon" src="%s" alt="">', get_template_directory_uri() . '/assets/img/icons/trash.svg' );
 ?>

<?php
$attachment_ids = wc_get_product( $post['id'] )->get_gallery_image_ids();
$img            = <<<IMG
                      <img src="%s" alt="%s" class="wpp-mu-im img-fluid %s %s" data-i="%s">
IMG;

$imgs = [];
?>
<div class="row wpp-list-cat">
    <div class="wpp-list-img col-xs-12 col-sm-6 col-md-3">
		<?php
		do_action('wpp_wish_action_icon');
        edit_post_link( $edit, '<div class="wpp-ed-post">', '</div>', $post['id'] );
        wpp_delete_post_link( $post['id'], $trash );
		$i      = 1;
		$imgs[] = [
			'src'   => bfi_thumb( $post['img'], [
				'width'  => 540,
				'height' => 360,
				'crop'   => true
			] ),
			'alt'   => $post['title'],
			'class' => 'wpp-im-' . $i
		];

		if ( ! empty( $attachment_ids ) ) {
			$i ++;
			foreach ( $attachment_ids as $attachment_id ) {
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
			printf( $img, $img_one['src'], $img_one['alt'], $img_one['class'], 'wpp-ims-w-' . $i, $aa );
			printf( '<span class="imgg-wp-bull img-bull-w-%s img-wpp-%s" data-i="%s"></span>', count( $imgs ), $aa, $aa );
			$aa ++;
		}
		?>

    </div>
    <div class="col-xs-12 col-sm-6 col-md-9">
        <a href="<?php echo get_the_permalink( $post['id'] ); ?>" title=""><h4
                    class="grid-headline-icon"><?php echo $post['title']; ?></h4></a>
        <p><?php echo get_the_excerpt( $post['id'] ); ?></p>
        <div class="row">
            <div class="col-md-5">
				<?php def_costil( $post['id'] ); ?></div>
        </div>
    </div>
</div>