<?php

/**
 * Настройка хлебных крошек
 *
 * @param $classes
 *
 * @return array
 */

function wpp_br_breadcrumbs_classes( $classes ) {
	$classes = [
		'wrap_class' => 'breadcrumb breadcrumb--overflow responsive-gutter section-padding-small',
		'item_class' => 'wpp_br_item',
		'link_class' => 'breadcrumb-item'
	];

	return $classes;
}

add_filter( 'wpp_fr_breadcrumbs_classes', 'wpp_br_breadcrumbs_classes' );

/**
 * @param $args
 *
 * @return mixed
 */
function wpp_br_bread_array( $args ) {
	$args['home_text'] = __( 'Home', 'wpp-brabus' );

	return $args;
}

add_filter( 'wpp_fr_breadcrumb_setting', 'wpp_br_bread_array' );


/**
 * Отображение страниц в админке
 *
 * @param $states
 *
 * @return mixed
 */
function wpp_br_custom_pages( $states ) {

	$search = wpp_fr_get_page_id( 'search' );
	$wl     = wpp_fr_get_page_id( 'wl' );
	$pn     = wpp_fr_get_page_id( 'pn' );

	if ( ! empty( $search ) ) :
		$states[ $search ] = __( 'Product Search', 'wpp-brabus' );
	endif;

	if ( ! empty( $wl ) ) :
		$states[ $wl ] = __( 'Product Wishlist', 'wpp-brabus' );
	endif;

	if ( ! empty( $pn ) ) :
		$states[ $pn ] = __( 'Product News', 'wpp-brabus' );
	endif;

	return $states;
}

add_filter( 'wpp_fr_post_states', 'wpp_br_custom_pages' );


function load_core_assets() {

	//if ( /*is_archive() || is_home() || is_front_page() */) {
		wp_enqueue_script( 'lightgallery' );
		wp_enqueue_style( 'lightgallery' );

	//}

	if ( wp_is_mobile() /*&& ( is_archive() || is_page_template( 'pages/product-wish.php' ) || is_page_template( 'page/product-search.php' )*/ ) {
		wp_enqueue_script( 'swiper' );
		wp_enqueue_style( 'swiper' );
	}
	#wp_enqueue_style( 'slick-theme' );


}

add_action( 'wp_enqueue_scripts', 'load_core_assets' );


/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}

remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
