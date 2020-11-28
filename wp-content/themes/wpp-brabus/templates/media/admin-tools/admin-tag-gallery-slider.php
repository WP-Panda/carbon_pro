<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1.2.00 - изменен урл картинок
 */

echo '</section><form  data-cat="' . $args['term'] . '" class="wpp_sl_bundle wpp_wrapper row wpp-sortable-ajax">';

foreach ( $args['imgs'] as $img_ID ) {
	$url = wp_get_attachment_image_src( $img_ID, 'full' );
	$img   = n_wpp_fr_image_bundle( $url[0], wpp_br_thumb_array() );
	$input = sprintf( '<input  type="hidden" value="%s" name="%s[]">', $img_ID, $args['key'] );
	printf( '<div class="wpp-tag-img col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2" data-id="%s" data-term="%s" data-key="%s"><div class="wpp-ad-tools-remove wpp_remove_term_img wpp-tooltips" data-title="Удалить Изображение"><img src="%s"></div><img class="wpp-tooltips-follow" src="%s" data-title="Перетянуть для сортировки">%s</div>', $img_ID, $args['term'], $args['key'], get_template_directory_uri() . '/assets/img/icons/cross.svg', $img[implode('x', BR_THUMB_SIZE)]['thumb']['norm'], $input );

}
echo '</form>';
echo '<section>';