<?php
/**
 * The settings page for Meta Box AIO.
 *
 * @package    Meta Box
 * @subpackage Meta Box AIO
 */

class MB_AIO_Settings {
	public $option_name = 'meta_box_aio';
	public $extension_dir;
	public $extensions;

	public function __construct() {
		$this->extension_dir = dirname( __FILE__ ) . '/extensions';

		$this->get_extensions();
		$this->migrate_settings();

		add_action( 'init', array( $this, 'init' ), 0 );
	}

	public function init() {
		// Allows developers to bypass the settings page by filter.
		if ( false === apply_filters( 'mb_aio_show_settings', true ) ) {
			return;
		}

		// Show Meta Box admin menu.
		add_filter( 'rwmb_admin_menu', '__return_true' );
		add_filter( 'mb_settings_pages', array( $this, 'add_settings_page' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_settings' ) );

		add_action( 'admin_print_styles-meta-box_page_meta-box-aio', array( $this, 'enqueue' ) );
		add_filter( 'rwmb_extensions_begin_html', array( $this, 'output_filters' ) );
	}

	/**
	 * Add plugin settings page.
	 *
	 * @param array $settings_pages List of settings pages.
	 * @return array
	 */
	public function add_settings_page( $settings_pages ) {
		$settings_pages[] = array(
			'id'          => 'meta-box-aio',
			'option_name' => $this->option_name,
			'menu_title'  => esc_html__( 'All-In-One', 'meta-box-aio' ),
			'page_title'  => esc_html__( 'Meta Box All-In-One', 'meta-box-aio' ),
			'parent'      => 'meta-box',
			'columns'     => 1,
			'style'       => 'no-boxes',
		);
		return $settings_pages;
	}

	/**
	 * Register settings.
	 *
	 * @param array $meta_boxes Meta boxes.
	 *
	 * @return array
	 */
	public function register_settings( $meta_boxes ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';

		$options = array();
		foreach ( $this->extensions as $extension ) {
			$data = get_plugin_data( "{$this->extension_dir}/$extension/$extension.php" );
			$options[$extension] = $data['Name'];
		}
		$meta_boxes[] = array(
			'id'             => 'extensions',
			'title'          => ' ',
			'settings_pages' => 'meta-box-aio',
			'fields'         => array(
				array(
					'type' => 'checkbox',
					'id'   => 'dashboard_widget',
					'name' => esc_html__( 'Enable The Dashboard Widget?', 'meta-box-aio' ),
					'std'  => 1,
				),
				array(
					'type'              => 'checkbox_list',
					'id'                => 'extensions',
					'name'              => esc_html__( 'Enabled Extensions', 'meta-box-aio' ),
					'label_description' => esc_html__( 'Check or uncheck extensions below to enable or disable them.', 'meta-box-aio' ),
					'select_all_none'   => true,
					'class'             => 'extension-list',
					'options'           => $options,
				),
			),
		);
		return $meta_boxes;
	}

	/**
	 * Migrate the settings from the previous options to the new one.
	 * Do not save settings in the form of 'extension' => true.
	 * Instead save an array of active extensions.
	 * @return [type] [description]
	 */
	public function migrate_settings() {
		$option = get_option( $this->option_name );
		if ( empty( $option ) || isset( $option['extensions'] ) ) {
			return;
		}
		$option = array_filter( $option );
		$extensions = array_intersect( $this->extensions, array_keys( $option ) );
		$option = array(
			'extensions'       => $extensions,
			'dashboard_widget' => 1,
		);
		update_option( $this->option_name, $option );
	}

	/**
	 * Get list of available extensions.
	 */
	public function get_extensions() {
		$this->extensions = glob( "{$this->extension_dir}/*", GLOB_ONLYDIR );
		$this->extensions = array_map( 'basename', $this->extensions );
	}

	public function enqueue() {
		wp_enqueue_style( 'meta-box-aio', plugin_dir_url( __FILE__ ) . 'css/aio.css' );
		wp_enqueue_script( 'meta-box-aio', plugin_dir_url( __FILE__ ) . 'js/aio.js', array(), '', true );
	}

	public function output_filters( $begin ) {
		ob_start();
		?>
		<div class="filters" id="filters">
			<p><?php esc_html_e( 'Filter the extensions by:', 'meta-box-aio' ); ?></p>
			<p>
				<a href="#" data-filter=""><?php esc_html_e( 'All', 'meta-box-aio' ); ?></a>
				<!-- <a href="#" data-filter="premium"><?php esc_html_e( 'Premium', 'meta-box-aio' ); ?></a> -->
				<!-- <a href="#" data-filter="free"><?php esc_html_e( 'Free', 'meta-box-aio' ); ?></a> -->
			</p>
			<p>
				<a href="#" data-filter="popular"><?php esc_html_e( 'Popular', 'meta-box-aio' ); ?></a>
				<a href="#" data-filter="data"><?php esc_html_e( 'Data', 'meta-box-aio' ); ?></a>
				<a href="#" data-filter="ui"><?php esc_html_e( 'UI', 'meta-box-aio' ); ?></a>
				<!-- <a href="#" data-filter="integration"><?php esc_html_e( 'Integration', 'meta-box-aio' ); ?></a> -->
				<a href="#" data-filter="admin"><?php esc_html_e( 'Admin', 'meta-box-aio' ); ?></a>
				<a href="#" data-filter="frontend"><?php esc_html_e( 'Frontend', 'meta-box-aio' ); ?></a>
			</p>
		</div>
		<?php
		$filters = ob_get_clean();
		$begin = str_replace(
			'</div><div class="rwmb-input">',
			"$filters</div><div class='rwmb-input'>",
			$begin
		);
		return $begin;
	}
}
