<?php
/**
 * The plugin loader: load enabled extensions.
 *
 * @package    Meta Box
 * @subpackage Meta Box AIO
 */

class MB_AIO_Loader {
	public function __construct() {
		$this->load_premium_extensions();
		$this->load_free_extensions();
	}

	public function load_premium_extensions() {
		$option = get_option( 'meta_box_aio' );
		if ( empty( $option['extensions'] ) ) {
			return;
		}

		// Allows developers to filter the list of premium extensions.
		$extensions = apply_filters( 'mb_aio_extensions', $option['extensions'] );
		if ( ! is_array( $option['extensions'] ) ) {
			return;
		}
		foreach ( $extensions as $extension ) {
			require_once dirname( __FILE__ ) . "/extensions/$extension/$extension.php";
		}
	}

	public function load_free_extensions() {
		require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', array( $this, 'register_free_extensions' ) );
	}

	public function register_free_extensions() {
		if ( false === apply_filters( 'mb_aio_load_free_extensions', true ) ) {
			return;
		}
		$plugins = array(
			array(
				'name'     => 'Meta Box',
				'slug'     => 'meta-box',
				'required' => true,
			),
			array(
				'name' => 'MB Comment Meta',
				'slug' => 'mb-comment-meta',
			),
			array(
				'name' => 'MB Custom Post Type',
				'slug' => 'mb-custom-post-type',
			),
			array(
				'name' => 'MB Relationships',
				'slug' => 'mb-relationships',
			),
			array(
				'name' => 'MB Rest API',
				'slug' => 'mb-rest-api',
			),
			array(
				'name' => 'Meta Box - Beaver Themer Integrator',
				'slug' => 'meta-box-beaver-themer-integrator',
			),
			array(
				'name' => 'Meta Box - FacetWP Integrator',
				'slug' => 'meta-box-facetwp-integrator',
			),
			array(
				'name' => 'Meta Box for Yoast SEO',
				'slug' => 'meta-box-yoast-seo',
			),
			array(
				'name' => 'Meta Box Text Limiter',
				'slug' => 'meta-box-text-limiter',
			),
		);
		$config  = array(
			'id'          => 'meta-box-aio',
			'parent_slug' => 'meta-box',
			'menu'        => 'meta-box-install-plugins',
			'strings'     => array(
				'page_title' => __( 'Free Extensions', 'meta-box-aio' ),
				'menu_title' => __( 'Free Extensions', 'meta-box-aio' ),
				'notice_can_install_required'     => _n_noop(
					// translators: 1: plugin name(s).
					'The Meta Box AIO plugin requires the following plugin: %1$s.',
					'The Meta Box AIO plugin requires the following plugins: %1$s.',
					'meta-box-aio'
				),
				'notice_can_install_recommended'  => _n_noop(
					// translators: 1: plugin name(s).
					'The Meta Box AIO plugin recommends the following plugin: %1$s.',
					'The Meta Box AIO plugin recommends the following plugins: %1$s.',
					'meta-box-aio'
				),
			),
		);

		tgmpa( $plugins, $config );
	}
}
