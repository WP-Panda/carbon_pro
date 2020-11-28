<?php

if ( ! is_shop() ) :
	$terma                = get_queried_object();
	$id_term              = $terma->term_id;
	$output               = $navs = wpp_br_get_cat_products( $id_term );
	$video                = get_term_meta( $id_term, 'standard', true );
	$thumbnail_ids        = get_term_meta( $id_term, 'image_cat_gallery', false );
	#$slider_type          = get_term_meta( $id_term, 'type', true );
	#$slider_sanitize_type = empty( $slider_type ) || $slider_type !== 'slider' ? 'wall' : 'slider';

	if ( ! empty( $thumbnail_ids ) ):
		$images = [];
		foreach ( $thumbnail_ids as $thumbnail_id ) :
			$images[] = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		endforeach;
		wpp_get_template_part( 'templates/media/wall-slider', [
			'img'   => $thumbnail_ids,
			'video' => $video,
			'type'  => 'wall'
		] );

	endif;
endif;
?>
<div class="container-fluid responsive-gutter section-padding-small col-min-height">
    <div class="row">
		<?php
		ob_start();
		woocommerce_output_product_categories( [
			'parent_id' => is_product_category() ? get_queried_object_id() : 0,
		] );
		$loop_html = ob_get_clean();
		echo $loop_html;
		?>
    </div>
</div>
