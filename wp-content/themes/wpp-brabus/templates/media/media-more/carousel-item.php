<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
extract( $args );
global $post;

$template = $n === (int) $count ? 'templates/media/media-more/carousel-more' : 'templates/product-cat/product-grid';

if ( $flag === 1 ) {
	ob_start();
	wpp_get_template_part( $template, [
		'post'    => $post,
		'term_id' => 0,
		'bundle'  => false,
		'lazy' => false,
		'sizes_key' => 'carousel_sizes'
	] );
	$content = ob_get_clean();

	printf( ' <div class="%s">%s</div>', $class, $content );

	if ( 0 === $n % $module && $n !== $count && $n !== 1 ) {
		echo '</div><div class="row wpp-news-row">';
	}


} else {

	if ( $n === 1 ) {
		echo '<ul class="wpp-thumb-img">';
	}

	printf( '<li class="wpp-block-thumb"><img src="%s"><span>%s</span></li>', get_the_post_thumbnail_url( $post->ID, 'thumbnail' ), $post->post_title );


	if ( $n + 1 === $count ) {
		echo '</ul>';
	}

}