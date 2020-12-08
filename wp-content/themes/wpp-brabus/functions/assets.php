<?php
function wpp_brabus_assets() {

	$ajax_url = '/wp-admin/admin-ajax.php';
	$actual_url = wpp_fr_parse_url( wpp_fr_actual_link() );

	$currency   = ! empty( $actual_url['currency'] ) ? '/' . $actual_url['currency'] : '';
	$lang       = ! empty( $actual_url['lang_url'] ) ? $actual_url['lang_url'] . '.' : '';

	wp_enqueue_style( BRABUS_PREF . 'project', BRABUS_URL . '/assets/css/project.css', [], BRABUS_VER, 'all' );
	wp_enqueue_style( BRABUS_PREF . 'csel', BRABUS_URL . '/assets/css/justselect.min.css', [], BRABUS_VER, 'all' );

	if ( is_front_page() || is_home() ) :
		wp_enqueue_style( BRABUS_PREF . 'custom-homepage', BRABUS_URL . '/assets/css/custom-homepage.css', [], BRABUS_VER, 'all' );
	endif;

	wp_enqueue_script( BRABUS_PREF . 'bootstrap', BRABUS_URL . '/assets/js/bootstrap.js', [], BRABUS_VER, true );
	wp_enqueue_script( BRABUS_PREF . 'specs', BRABUS_URL . '/assets/js/lib.js', [
		'jquery',
		'flickity'
	], BRABUS_VER, true );

	wp_enqueue_script( BRABUS_PREF . 'bra-bra', BRABUS_URL . '/assets/js/brabra.js', [], BRABUS_VER, true );
	wp_enqueue_script( BRABUS_PREF . 'csel', BRABUS_URL . '/assets/js/justselect.min.js', [], BRABUS_VER, true );
	wp_enqueue_script( BRABUS_PREF . 'cart', BRABUS_URL . '/assets/js/cart.js', [], BRABUS_VER, true );
	wp_localize_script( BRABUS_PREF . 'cart', 'WppAjaxCart', [
		'ajax_url'   => $ajax_url,
		'security'   => wp_create_nonce( 'wpp-cart-string' ),
		'actual_url' => $actual_url['scheme'] . '://' . $lang . $actual_url['host'] . $currency,
		'icons'      => [
			'plus'           => get_template_directory_uri() . '/assets/img/icons/plus.svg',
			'loader'         => get_template_directory_uri() . '/assets/img/icons/loader.svg',
			'check'          => get_template_directory_uri() . '/assets/img/icons/check.svg',
			'wall_show_text' => wpp_br_lng( 'learn_more' ),
			'wall_hide_text' => wpp_br_lng( 'learn_hide' )
		]
	] );


	wp_enqueue_script( BRABUS_PREF . 'actions', BRABUS_URL . '/assets/js/actions.js', [
		'jquery-ui-core',
		'jquery-effects-core',
		'jquery-effects-scale',
		'clipboard'
	], BRABUS_VER, true );

	wp_localize_script( BRABUS_PREF . 'actions', 'WppAjaxAct', [
		'ajax_url' => $ajax_url,
		'security' => wp_create_nonce( 'wpp-act-string' ),
		'copy'     => __( 'Item Link', 'wpp-br' ),
		'copied'   => __( 'Copied to Clipboard', 'wpp-br' )
	] );


	if ( wpp_fr_user_is_admin() ) {
		wp_enqueue_script( BRABUS_PREF . 'tools', BRABUS_URL . '/assets/js/admin-tools.js', [ 'jquery-ui-sortable' ], BRABUS_VER, true );
		wp_localize_script( BRABUS_PREF . 'tools', 'WppTools', [
			'ajax_url' => $ajax_url,
			'security' => wp_create_nonce( 'wpp-tools-string' ),
			'icons'    => [
				'loader' => get_template_directory_uri() . '/assets/img/icons/loader.svg',
			]
		] );
	}
}

add_action( 'wp_enqueue_scripts', 'wpp_brabus_assets' );


function afs() {
	?>
    <style>
        .wp-block {
            float: none;
            overflow: hidden;
        }

        li.wpp-block-thumb span {
            white-space: nowrap;
            overflow: hidden;
        }

        li.wpp-block-thumb {
            overflow: hidden;
        }

        ul.wpp_post_images img,
        .rwmb-media-view img {
            width: 100%;
            height: auto;
        }
    </style>
	<?php
}

add_action( 'admin_footer', 'afs' );