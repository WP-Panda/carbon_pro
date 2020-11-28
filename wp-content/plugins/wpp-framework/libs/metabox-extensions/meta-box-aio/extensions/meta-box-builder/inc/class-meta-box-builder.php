<?php
/**
 * Builder UI.
 *
 * @package    Meta Box
 * @subpackage Meta Box Builder
 */

/**
 * Main plugin class
 *
 * @author  Tan Nguyen <tan@giga.ai>
 * @package Meta Box
 */
class Meta_Box_Builder {
	public $meta = array();

	/**
	 * Initial scripts
	 */
	public function __construct() {
		$this->load_textdomain();

		// Register 'meta-box-builder' post type to store all meta boxes.
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Use all 'meta-box' post type to register meta box.
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_box' ) );

		// Load 'meta-box' builder scripts on add and edit post page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Setup the script when WP admin is fully loaded.
		add_action( 'init', array( $this, 'setup' ) );

		// Change the output of updated messages.
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ), 10, 1 );

		add_filter( 'wp_insert_post_data', array( $this, 'update_meta_box' ), 10, 2 );

		add_action( 'admin_init', array( $this, 'remove_meta_box' ) );

		add_action( 'admin_menu', array( 'Meta_Box_Import', 'submenu' ) );

		add_action( 'admin_footer-edit.php', array( 'Meta_Box_Import', 'bulk_action' ) );

		add_action( 'admin_init', array( 'Meta_Box_Import', 'handle_post' ) );

		add_filter( 'redirect_post_location', array( $this, 'redirect_after_update' ) );
	}

	/**
	 * Load plugin text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'meta-box-builder', false, plugin_basename( RWMB_DIR ) . '/languages/' );
	}

	/**
	 * We don't need any meta box on meta box post type
	 */
	public function remove_meta_box() {
		remove_meta_box( 'submitdiv', 'meta-box', 'side' );
	}

	/**
	 * Setup when plugin fully loaded
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'edit_form_after_title', array( $this, 'setup_gui' ) );
	}

	/**
	 * Action when user save post. We have to manual store post_content field.
	 * Which takes value from post_excerpt and serialize
	 *
	 * @param  mixed    $data raw posted data
	 * @param  \WP_Post $post current post to save
	 *
	 * @return mixed $data after formatted
	 */
	public function update_meta_box( $data, $post ) {
		if ( ! isset( $post['post_type'] ) || 'meta-box' !== $post['post_type'] || empty( $data['post_excerpt'] ) ) {
			return $data;
		}
		$parser   = new Meta_Box_Processor( $data['post_excerpt'] );
		$meta_box = $parser->get_meta_box();
		$status   = ! empty( $meta_box['status'] ) ? $meta_box['status'] : 'publish';
		unset( $meta_box['for'], $meta_box['is_id_modified'], $meta_box['status'] );

		if ( ! empty( $meta_box['checktable'] ) ) {
			unset( $meta_box['checktable'] );
			$meta_box['storage_type'] = 'custom_table';
		} else {
			unset( $meta_box['table'] );
		}

		$fields = array();
		if ( ! empty( $meta_box['fields'] ) ) {
			$fields = &$meta_box['fields'];
		}

		foreach ( $fields as &$field ) {
			unset( $field['groupfield'] );

			if ( ! in_array( $field['type'], array( 'taxonomy', 'taxonomy_advanced' ) ) ) {
				continue;
			}

			$std = ! empty( $field['std'] ) ? $field['std'] : '';
			if ( isset( $field['multiple'] ) && true == $field['multiple'] ) {
				$field['std'] = '@@@array(' . implode( ',', $std ) . ')@@@';
			}
			unset( $field['terms'] );
		}

		array_walk( $fields, array( $this, 'process_field_options' ) );

		// Only allow Trash or Publish status.
		$data['post_status']  = 'trash' === $data['post_status'] ? $data['post_status'] : $status;
		$data['post_content'] = @serialize( $meta_box );

		return $data;
	}

	public function process_field_options( &$field ) {
		if ( ! in_array( $field['type'], array( 'select', 'radio', 'checkbox_list', 'select_advanced' ) ) ) {
			return;
		}
		if ( empty( $field['options'] ) || is_array( $field['options'] ) ) {
			return;
		}
		$options = array();

		$field['options'] = wp_unslash( $field['options'] );
		$field['options'] = explode( "\n", $field['options'] );

		foreach ( $field['options'] as $choice ) {
			if ( false !== strpos( $choice, ':' ) ) {
				list( $value, $label )     = explode( ':', $choice, 2 );
				$options[ trim( $value ) ] = trim( $label );
			} else {
				$options[ trim( $choice ) ] = trim( $choice );
			}
		}

		$field['options'] = array_filter( $options );
	}
	/**
	 * Redirect to current tab after update
	 *
	 * @param string $location Redirect URL.
	 *
	 * @return string URL to be redirected
	 */
	public function redirect_after_update( $location ) {
		if ( ! $this->is_meta_box_post_type() ) {
			return $location;
		}

		$tab = ! empty( mbb_get_current_tab() ) ? mbb_get_current_tab() : 'fields';
		return add_query_arg(
			array(
				'tab'    => $tab,
				'active' => $tab,
			),
			$location
		);
	}

	/**
	 * Modify the output message of meta-box post type
	 *
	 * @param  mixed $messages Message array
	 *
	 * @return mixed $messages Message after modified
	 */
	public function updated_messages( $messages ) {
		$messages['meta-box'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Field group updated.', 'meta-box-builder' ),
			2 => __( 'Custom field updated.', 'meta-box-builder' ),
			3 => __( 'Custom field deleted.', 'meta-box-builder' ),
			4 => __( 'Field group updated.', 'meta-box-builder' ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Field group restored to revision from %s', 'meta-box-builder' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Field group updated.', 'meta-box-builder' ),
			7 => __( 'Field group updated.', 'meta-box-builder' ),
			8 => __( 'Field group submitted.', 'meta-box-builder' ),
		);

		return $messages;
	}

	/**
	 * Load the GUI for Builder only when in meta-box post type
	 *
	 * @return void
	 */
	public function setup_gui() {
		if ( ! $this->is_meta_box_post_type() ) {
			return;
		}

		$tab = mbb_get_current_tab();
		if ( ( 'code' === $tab && ! empty( $this->meta ) ) || 'code' !== $tab ) {
			require MBB_INC_DIR . $tab . '-gui.php';
		}
	}

	/**
	 * Check if current link is meta box post type or not
	 *
	 * @return boolean Meta box post type or not
	 */
	public function is_meta_box_post_type() {
		$screen = get_current_screen();

		return 'post' === $screen->base && 'meta-box' === $screen->post_type;
	}

	/**
	 * Only enqueue media on edit post page
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( ! $this->is_meta_box_post_type() ) {
			return;
		}

		$attrs = require MBB_INC_DIR . 'define.php';

		list( , $url ) = RWMB_Loader::get_path( dirname( dirname( __FILE__ ) ) );

		wp_enqueue_style( 'rwmb-select2', RWMB_CSS_URL . 'select2/select2.css', array(), '4.0.1' );
		wp_enqueue_style( 'rwmb-select-advanced', RWMB_CSS_URL . 'select-advanced.css', array(), RWMB_VER );
		wp_enqueue_style( 'highlightjs', $url . 'assets/css/atom-one-dark.css' );
		wp_enqueue_style( 'meta-box-builder', $url . 'assets/css/meta-box-builder.css', array(), '1.0.0' );

		wp_register_script( 'highlightjs', $url . 'assets/js/highlight.pack.js', array(), '9.11.0', true );
		wp_register_script( 'angularjs', $url . 'assets/js/angular.min.js', array(), '1.6.9', true );
		wp_register_script( 'angularjs-animate', $url . 'assets/js/angular-animate.min.js', array( 'angularjs' ), '1.6.9', true );
		wp_register_script( 'angular-ui-sortable', $url . 'assets/js/sortable.min.js', array(), '0.19.0', true );
		wp_register_script( 'tg-dynamic-directive', $url . 'assets/js/tg.dynamic.directive.js', array(), '0.3.0', true );
		wp_register_script( 'meta-box-builder-directives', $url . 'assets/js/directives.js', array( 'angularjs' ), '2.0', true );
		wp_register_script( 'rwmb-select2', RWMB_JS_URL . 'select2/select2.min.js', array( 'jquery' ), '4.0.2', true );
		wp_register_script( 'clipboard', $url . 'assets/js/clipboard.min.js', array(), '1.3.2', true );

		wp_localize_script( 'meta-box-builder-directives', 'attrs', $attrs );

		// If we're updating metabox, load old data.
		if ( isset( $_GET['post'] ) ) {
			$post = get_post( $_GET['post'] );
			$meta = $post->post_excerpt;

			// Should convert to array to enqueue properly.
			$meta = json_decode( $meta, true );

			$this->meta = unserialize( $post->post_content );
			wp_localize_script( 'meta-box-builder-directives', 'meta', $meta );
		}

		$post_types    = mbb_get_post_types();
		$taxonomies    = mbb_get_taxonomies();
		$setting_pages = mbb_get_setting_pages();
		$terms         = mbb_get_all_taxonomy_term();

		wp_localize_script( 'meta-box-builder-directives', 'post_types', $post_types );
		wp_localize_script( 'meta-box-builder-directives', 'taxonomies', $taxonomies );
		wp_localize_script( 'meta-box-builder-directives', 'settings_pages', $setting_pages );
		wp_localize_script( 'meta-box-builder-directives', 'terms', $terms );

		wp_enqueue_script(
			'meta-box-builder',
			$url . 'assets/js/builder.js',
			array(
				'highlightjs',
				'angularjs-animate',
				'meta-box-builder-directives',
				'rwmb-select2',
				'accordion',
				'angular-ui-sortable',
				'tg-dynamic-directive',
				'clipboard',
			),
			'2.0.0',
			true
		);
	}

	/**
	 * Register post type named 'meta-box'
	 *
	 * Meta box name - Post Title
	 * Meta box id - Post Name
	 * Meta box array - Post Content
	 * Meta box json - Post Excerpt
	 *
	 * @return void
	 */
	public function register_post_type() {
		$post_type = 'meta-box';

		$labels = array(
			'name'               => _x( 'Field Groups', 'post type general name', 'meta-box-builder' ),
			'singular_name'      => _x( 'Field Group', 'post type singular name', 'meta-box-builder' ),
			'menu_name'          => _x( 'Custom Fields', 'admin menu', 'meta-box-builder' ),
			'name_admin_bar'     => _x( 'Custom Fields', 'add new on admin bar', 'meta-box-builder' ),
			'add_new'            => _x( 'Add New', 'meta-box-builder', 'meta-box-builder' ),
			'add_new_item'       => __( 'Add New Field Group', 'meta-box-builder' ),
			'new_item'           => __( 'New Field Group', 'meta-box-builder' ),
			'edit_item'          => __( 'Edit Field Group', 'meta-box-builder' ),
			'view_item'          => __( 'View Field Group', 'meta-box-builder' ),
			'all_items'          => __( 'Custom Fields', 'meta-box-builder' ),
			'search_items'       => __( 'Search Field Groups', 'meta-box-builder' ),
			'parent_item_colon'  => __( 'Parent Field Groups:', 'meta-box-builder' ),
			'not_found'          => __( 'No field groups found.', 'meta-box-builder' ),
			'not_found_in_trash' => __( 'No field groups found in Trash.', 'meta-box-builder' ),
		);

		$args = array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'meta-box',
			'query_var'       => true,
			'rewrite'         => array( 'slug' => 'metabox' ),
			'capability_type' => 'post',
			'hierarchical'    => false,
			'menu_position'   => null,
			'supports'        => false,
			'capabilities'    => array(
				'edit_post'          => 'edit_theme_options',
				'read_post'          => 'edit_theme_options',
				'delete_post'        => 'edit_theme_options',
				'edit_posts'         => 'edit_theme_options',
				'edit_others_posts'  => 'edit_theme_options',
				'delete_posts'       => 'edit_theme_options',
				'publish_posts'      => 'edit_theme_options',
				'read_private_posts' => 'edit_theme_options',
			),
		);

		register_post_type( $post_type, $args );
	}

	/**
	 * Get all registered meta boxes.
	 *
	 * @param array $meta_boxes Meta boxes.
	 *
	 * @return array
	 */
	public function register_meta_box( $meta_boxes ) {
		$posts = get_posts(
			array(
				'post_type'      => 'meta-box',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
			)
		);

		if ( empty( $posts ) ) {
			return $meta_boxes;
		}

		foreach ( $posts as $post ) {
			$meta_box = @unserialize( $post->post_content );

			if ( ! isset( $meta_box['fields'] ) ) {
				continue;
			}

			$meta_boxes[] = $meta_box;
		}

		return $meta_boxes;
	}
}
