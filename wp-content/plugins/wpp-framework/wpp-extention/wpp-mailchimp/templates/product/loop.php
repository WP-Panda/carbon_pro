<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

$query = new WP_Query( [
	'post_type' => 'product',
	'tax_query' => [
		[
			'taxonomy' => 'product_cat',
			'field'    => 'id',
			'terms'    => [ 23 ],
		]
	]
] );
if ( $query->have_posts() ) {
	$n = 1;
	while ( $query->have_posts() ) {
		$query->the_post();
		$img   = get_the_post_thumbnail_url( $post->ID, 'full' );
		$src   = bfi_thumb( $img, [ 'width' => 540, 'height' => 360, 'crop' => true ] );
		$align = $n % 2 === 1 ? 'left' : 'right';
		if ( $n % 2 === 1 ) :
			?>
            <tr>
            <td valign="top" style="padding:9px" class="mcnImageGroupBlockInner">
		<?php endif; ?>
        <table
                align="<?php echo $align; ?>"
                width="273"
                border="0"
                cellpadding="0"
                cellspacing="0"
                class="mcnImageGroupContentContainer"
        >
            <tbody>
            <tr>
                <td class="mcnImageGroupContent" valign="top"
                    style="padding-left: 9px; padding-top: 0; padding-bottom: 0;">

                    <table style="width:264px;"
                           class="mcpreview-image-uploader"
                           data-mc-id="0">
                        <tr>
                            <td>
                                <a href="<?php echo get_the_permalink(); ?>">
                                    <img style="width:264px;"
                                         src="<?php echo $src; ?>"
                                         alt=""
                                    >
                                    <h3><?php the_title();
										echo $n % 2; ?></h3>
                                </a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            </tbody>

        </table>

		<?php if ( $n % 2 === 0 ) : ?>
            </td>
            </tr>
		<?php
		endif;
		$n ++;
	}
} else {
}
wp_reset_postdata();