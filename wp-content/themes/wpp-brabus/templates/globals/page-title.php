<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( empty( $_GET['product_search'] ) ) {

	if ( is_front_page() ) :
		if ( has_nav_menu( 'top_' . get_locale() ) ) {
			$nav = wp_nav_menu( [
				'theme_location' => 'top_' . get_locale(),
				'container'      => '',
				'add_a_class'    => 'main-navigation-link',
				'echo'           => false,

			] );
			preg_match_all( '#<a.*>.*</a>#USi', $nav, $text );
		}


		?>
        <h1 id="mainNavigation" class="main-navigation">

			<?php if ( ! empty( $text[0] ) ) {
				echo implode( $text[0] );
			} ?>

        </h1>
	<?php else:

		if ( is_shop() ) :
			$shop_page_id = wc_get_page_id( 'shop' );
			$title        = get_the_title( $shop_page_id );
        elseif ( is_singular() ) :
			$title = get_the_title();
        elseif ( is_archive() || is_category() ) :
			$title = single_term_title( '', false );
        elseif ( is_home() ) :
			$title = get_the_title( get_option( 'page_for_posts', false ) );
		else:
			$title = '';
		endif;


		if ( is_product() ) {
			$name = ' itemprop="name"';
		} else {
			$name = '';
		}

		printf( '<section class="main-title"><h1 class="main-title__headline"%s>%s</h1></section>', $name, $title );
	endif;
} else {

	$title = sprintf( '%s: %s', __( 'Search results for', 'wpp-br' ), $_GET['product_search'] );
	printf( '<section class="main-title"><h1 class="main-title__headline">%s</h1></section>', $title );
}