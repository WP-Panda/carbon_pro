<?php

/**
 * Post types Class.
 */
class Wpp_Add_Sales_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', [
			__CLASS__,
			'register_taxonomies'
		], 10 );
		add_action( 'init', [
			__CLASS__,
			'register_post_types'
		], 10 );
		#add_action( 'init', [__CLASS__,'support_jetpack_omnisearch'] );
		#add_filter( 'rest_api_allowed_post_types', ['rest_api_allowed_post_types'] );
		#add_action( 'wpp_fr_after_register_post_type', array( __CLASS__, 'maybe_flush_rewrite_rules' ) );
		#add_action( 'wpp_fr_flush_rewrite_rules', array( __CLASS__, 'flush_rewrite_rules' ) );
		#add_filter( 'gutenberg_can_edit_post_type', array( __CLASS__, 'gutenberg_can_edit_post_type' ), 10, 2 );
		#add_filter( 'use_block_editor_for_post_type', array( __CLASS__, 'gutenberg_can_edit_post_type' ), 10, 2 );

		add_action( 'wpp_fr_post_meta_boxes_args', [
			__CLASS__,
			'add_dep_fields_setting'
		] );

		add_action( 'wpp_fr_term_meta_boxes_args', [
			__CLASS__,
			'taxonomy_meta_boxes'
		] );
	}

	/**
	 * Register core taxonomies.
	 *
	 * @since 1.0.6
	 *
	 */
	public static function register_taxonomies() {
		if ( ! is_blog_installed() ) {
			#return;
		}
		if ( taxonomy_exists( 'as_options' ) ) {
			#return;
		}

		do_action( 'wpp_fr_ads_sales_register_tax' );


		register_taxonomy( 'as_options', apply_filters( 'wpp_fr_taxonomy_objects_as_options', [
			'as_option',
			'product'
		] ), apply_filters( 'wpp_fr_taxonomy_args_as_options', [
			'hierarchical'      => false,
			'label'             => __( 'AS Maker', 'wpp-fr' ),
			'labels'            => [
				'name'              => __( 'AS Makers', 'wpp-fr' ),
				'singular_name'     => __( 'AS Maker', 'wpp-fr' ),
				'menu_name'         => _x( 'AS Makers', 'Admin menu name', 'wpp-fr' ),
				'search_items'      => __( 'Search AS Makers', 'wpp-fr' ),
				'all_items'         => __( 'All AS Makers', 'wpp-fr' ),
				'parent_item'       => __( 'Parent AS Maker', 'wpp-fr' ),
				'parent_item_colon' => __( 'Parent AS Maker:', 'wpp-fr' ),
				'edit_item'         => __( 'Edit AS Maker', 'wpp-fr' ),
				'update_item'       => __( 'Update AS Maker', 'wpp-fr' ),
				'add_new_item'      => __( 'Add new AS Maker', 'wpp-fr' ),
				'new_item_name'     => __( 'New AS Maker name', 'wpp-fr' ),
				'not_found'         => __( 'No AS Makers found', 'wpp-fr' ),
			],
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
		] ) );


		register_taxonomy( 'as_group', apply_filters( 'wpp_fr_taxonomy_objects_as_options', [ 'as_option' ] ), apply_filters( 'wpp_fr_taxonomy_args_as_options', [
			'hierarchical'      => false,
			'label'             => __( 'AS Group', 'wpp-fr' ),
			'labels'            => [
				'name'              => __( 'AS Groups', 'wpp-fr' ),
				'singular_name'     => __( 'AS Group', 'wpp-fr' ),
				'menu_name'         => _x( 'AS Groups', 'Admin menu name', 'wpp-fr' ),
				'search_items'      => __( 'Search AS Groups', 'wpp-fr' ),
				'all_items'         => __( 'All AS Groups', 'wpp-fr' ),
				'parent_item'       => __( 'Parent AS Group', 'wpp-fr' ),
				'parent_item_colon' => __( 'Parent AS Group:', 'wpp-fr' ),
				'edit_item'         => __( 'Edit AS Group', 'wpp-fr' ),
				'update_item'       => __( 'Update AS Group', 'wpp-fr' ),
				'add_new_item'      => __( 'Add new AS Group', 'wpp-fr' ),
				'new_item_name'     => __( 'New AS Group name', 'wpp-fr' ),
				'not_found'         => __( 'No AS Groups found', 'wpp-fr' ),
			],
			'show_ui'           => true,
			'query_var'         => true,
			'show_admin_column' => true,
		] ) );

		do_action( 'wpp_fr_ads_sales_after_register_tax' );
	}

	/**
	 * Register core post types.
	 *
	 * @since 1.0.6
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() || post_type_exists( 'as_option' ) ) {
			return;
		}
		do_action( 'wpp_fr_ads_sales_register_posts' );

		#$permalinks = wc_get_permalink_structure();

		$supports = [
			'title',
			'thumbnail',
		];

		register_post_type( 'as_option', apply_filters( 'wpp_fr_ads_register_post_type_as_option', [
			'labels'              => [
				'name'                  => __( 'AS options', 'wpp-fr' ),
				'singular_name'         => __( 'AS option', 'wpp-fr' ),
				'all_items'             => __( 'All AS options', 'wpp-fr' ),
				'menu_name'             => _x( 'AS options', 'Admin menu name', 'wpp-fr' ),
				'add_new'               => __( 'Add New', 'wpp-fr' ),
				'add_new_item'          => __( 'Add new AS option', 'wpp-fr' ),
				'edit'                  => __( 'Edit', 'wpp-fr' ),
				'edit_item'             => __( 'Edit AS option', 'wpp-fr' ),
				'new_item'              => __( 'New AS option', 'wpp-fr' ),
				'view_item'             => __( 'View AS option', 'wpp-fr' ),
				'view_items'            => __( 'View AS options', 'wpp-fr' ),
				'search_items'          => __( 'Search AS options', 'wpp-fr' ),
				'not_found'             => __( 'No AS option found', 'wpp-fr' ),
				'not_found_in_trash'    => __( 'No AS option found in trash', 'wpp-fr' ),
				'parent'                => __( 'Parent AS option', 'wpp-fr' ),
				'featured_image'        => __( 'AS option image', 'wpp-fr' ),
				'set_featured_image'    => __( 'Set AS option image', 'wpp-fr' ),
				'remove_featured_image' => __( 'Remove AS option image', 'wpp-fr' ),
				'use_featured_image'    => __( 'Use as AS option image', 'wpp-fr' ),
				'insert_into_item'      => __( 'Insert into AS option', 'wpp-fr' ),
				'uploaded_to_this_item' => __( 'Uploaded to this AS option', 'wpp-fr' ),
				'filter_items_list'     => __( 'Filter AS option', 'wpp-fr' ),
				'items_list_navigation' => __( 'AS option navigation', 'wpp-fr' ),
				'items_list'            => __( 'AS option list', 'wpp-fr' ),
			],
			'description'         => __( 'This is where you can add new AS option to your store.', 'wpp-fr' ),
			'public'              => true,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			// Hierarchical causes memory issues - WP loads all records!
			'rewrite'             => false,
			'query_var'           => true,
			'supports'            => $supports,
			'has_archive'         => false,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
		] ) );
		do_action( 'wpp_fr_ads_sales_after_register_posts' );
	}


	public static function get_current_post_id() {

		#wpp_d_log( '$_GET' );
		#wpp_d_log( $_GET );
		#wpp_d_log( '$_POST[' );
		#wpp_d_log( $_POST );

		$post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );

		/**
		 * @todo  Фикс не понятный, уже забыллел и почему тут нет сейчас иногда post_id, но буду тащить если нет из GET параметра, если когда-нибудь дойдут руки - разобраться с этим
		 */
		if ( empty( $post_id ) ) {
			if ( ! empty( $_POST['data_product'] ) ) {
				$data = parse_url( $_POST['data_product'] );
				if ( ! empty( $data['query'] ) ) {
					parse_str( $data['query'], $query );

					if ( ! empty( $query['add-to-cart'] ) ) {
						$post_id = $query['add-to-cart'];
					}
				}
			}
		}

		if ( empty( $post_id ) ) {
			if ( ! empty( $_POST['data']['wp-refresh-post-lock']['post_id'] ) ) {

				$post_id = $_POST['data']['wp-refresh-post-lock']['post_id'];


			}
		}

		return is_numeric( $post_id ) ? absint( $post_id ) : false;
	}


	/**
	 * Options for options :))
	 *
	 * @since 1.0.6
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public static function add_dep_fields_setting( $meta_boxes ) {
		global $post;

		//wpp_d_log(debug_backtrace());

		$id = self::get_current_post_id();


		if ( ! empty( $id ) ) :

			$product = wc_get_product( $id );
			if ( ! empty( $product ) ) :
				$type = $product->get_type();
			endif;


			if ( empty( $type ) || $type !== 'bundle' ) {


				#wpp_d_log( get_post_meta( 88 ) );
				$fields_array = apply_filters( 'wpp_fr_as_options_fields', [
					'id'          => 'wpp_as_products',
					'type'        => 'group',
					'collapsible' => true,
					'save_state'  => true,
					'clone'       => true,
					'sort_clone'  => true,
					'group_title' => [ 'field' => 'title' ],
					'fields'      => [
						[
							'name' => __( 'Count', 'wpp-fr' ),
							'id'   => 'count',
							'type' => 'text'
						],
						[
							'name' => __( 'Price', 'wpp-fr' ),
							'id'   => 'opt_price',
							'type' => 'text'
						],
						[
							'name' => __( 'Key', 'wpp-fr' ),
							'id'   => 'key',
							'type' => 'text'
						],
						[
							'name' => __( 'Name', 'wpp-fr' ),
							'id'   => 'title',
							'type' => 'text'
						],
						[
							'name' => __( 'SKU', 'wpp-fr' ),
							'id'   => 'sku',
							'type' => 'text'
						],
						[
							'name'      => __( 'Paint', 'wpp-fr' ),
							'id'        => 'paint',
							'type'      => 'switch',
							'style'     => 'rounded',
							'on_label'  => 'On',
							'off_label' => 'Off',
							'std'       => 1
						],
						[
							'name'      => __( 'Installation', 'wpp-fr' ),
							'id'        => 'assembly',
							'type'      => 'switch',
							'style'     => 'rounded',
							'on_label'  => 'On',
							'off_label' => 'Off',
							'std'       => 1
						],

					]
				] );
				if ( empty( $fields_array ) ) {
					$fields_array = null;
				}
			}
		endif;

		$terms = get_terms( [
			'taxonomy'   => 'as_options',
			'hide_empty' => false,
		] );

		$options = [];

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		/*$screen = get_current_screen();
		wpp_dump( $screen );*/
		if ( empty( $type ) || $type !== 'bundle' ) {
			$meta_boxes[] = [
				'id'         => 'def_ns',
				'title'      => __( 'Options', 'wpp-fr' ),
				'post_types' => 'product',
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => [
					/*	[
							'name'     => __( 'Options for Create Variants', 'wpp-fr' ),
							'id'       => 'wpp_var_parent',
							'type'     => 'select',
							'options'  => $options,
							'multiple' => false,
						],*/
					[
						'type'       => 'button',
						'name'       => __( 'Create Variants', 'wpp-fr' ),
						'std'        => __( 'Start', 'wpp-fr' ),
						'attributes' => [
							'class' => 'wpp-create-variants',
						],
					],

					[
						'type'    => 'select',
						'name'    => 'Срок производства',
						'id'      => 'create_time',
						'options' => wpp_br_create_time_array(),
						'std'     => '6'
					],

					$fields_array
				]
			];
		}

		$meta_boxes['ff'] = [
			'title'      => __( 'AS Option Setting', 'wpp-fr' ),
			'post_types' => 'as_option',
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => [
				[
					'id'   => 'def_price',
					'name' => __( 'Price Coefficient', 'wpp-fr' ),
					'type' => 'text',
					'std'  => 100
				],
				[
					'id'            => 'def_options',
					'name'          => __( 'Enable Default?', 'wpp-fr' ),
					'type'          => 'switch',
					'style'         => 'rounded',
					'on_label'      => 'Yes',
					'off_label'     => 'No',
					'admin_columns' => true,
					'std'           => 0
				],

				[
					'id'            => 'def_cat',
					'name'          => __( 'Show in Cat variants', 'wpp-fr' ),
					'type'          => 'switch',
					'style'         => 'rounded',
					'on_label'      => 'Yes',
					'off_label'     => 'No',
					'admin_columns' => true,
					'std'           => 0
				],

				/*[
					'id'            => 'on_var',
					'name'          => __( 'Enable variations?', 'wpp-fr' ),
					'type'          => 'switch',
					'style'         => 'rounded',
					'on_label'      => 'Yes',
					'off_label'     => 'No',
					'admin_columns' => 'before title',
				],*/
				[
					'name' => __( 'SCU Before', 'wpp-fr' ),
					'id'   => '_scu_before',
					'type' => 'text',
				],
				[
					'name' => __( 'SCU After', 'wpp-fr' ),
					'id'   => '_scu_after',
					'type' => 'text',
				]

			]


		];

		$terms = get_terms( [
			'taxonomy'   => 'as_options',
			'hide_empty' => false
		] );


		/*foreach ( $terms as $term ) :
			$meta_boxes[ 'ff' ][ 'fields' ][ $term->term_id ] = [
				'name'              => __( 'Exclude options in variations?', 'wpp-fr' ),
				'id'                => 'ex_page_' . $term->term_id,
				'type'              => 'post',
				'label_description' => $term->name,
				'post_type'         => 'as_option',
				'field_type'        => 'checkbox_tree',
				'multiple'          => true,
				'query_args'        => [
					'posts_per_page' => -1,
					'tax_query'      => [
						[
							'taxonomy' => 'as_options',
							'field'    => 'id',
							'terms'    => [ $term->term_id ]
						]
					]
				]
			];
		endforeach;*/

		return $meta_boxes;
	}


	/**
	 * Optioins_tax_fields
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public static function taxonomy_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = [
			'title'      => 'AS Maker Options',
			'taxonomies' => 'as_options',
			// List of taxonomies. Array or string

			'fields' => [
				[
					'id'               => 'image',
					'name'             => 'Cover',
					'type'             => 'image_advanced',
					'force_delete'     => false,
					'max_file_uploads' => 1,
					'max_status'       => 'false',
					'image_size'       => 'thumbnail',
					'admin_columns'    => 'before name',
					// Show this column before 'Title' column
				],
				[
					'name'          => __( 'Type', 'wpp-fr' ),
					'id'            => 'option_type',
					'type'          => 'select_advanced',
					'options'       => [
						'checkbox' => __( 'Checkbox', 'wpp-fr' ),
						'image'    => __( 'Image', 'wpp-fr' )
					],
					'admin_columns' => 'after name',
					'multiple'      => false,
					'std'           => 'image'
				],

				[
					'id'   => 'def_price',
					'name' => __( 'Price Coefficient', 'wpp-fr' ),
					'type' => 'text',
					'std'  => 1
				],
				[
					'id'            => 'def_options',
					'name'          => __( 'Enable Default?', 'wpp-fr' ),
					'type'          => 'switch',
					'style'         => 'rounded',
					'on_label'      => 'Yes',
					'off_label'     => 'No',
					'admin_columns' => true,
					'std'           => 0
				],

				[
					'id'            => 'def_cat',
					'name'          => __( 'Show in Cat variants', 'wpp-fr' ),
					'type'          => 'switch',
					'style'         => 'rounded',
					'on_label'      => 'Yes',
					'off_label'     => 'No',
					'admin_columns' => true,
					'std'           => 0
				],

				[
					'name'      => __( 'Paint', 'wpp-fr' ),
					'id'        => 'paint',
					'type'      => 'switch',
					'style'     => 'rounded',
					'on_label'  => 'On',
					'off_label' => 'Off',
					'std'       => 1
				],
				[
					'name'      => __( 'Installation', 'wpp-fr' ),
					'id'        => 'assembly',
					'type'      => 'switch',
					'style'     => 'rounded',
					'on_label'  => 'On',
					'off_label' => 'Off',
					'std'       => 1
				],

				[
					'name' => __( 'SCU Before', 'wpp-fr' ),
					'id'   => '_scu_before',
					'type' => 'text',
				],
				[
					'name' => __( 'SCU After', 'wpp-fr' ),
					'id'   => '_scu_after',
					'type' => 'text',
				]
			]
		];

		return $meta_boxes;
	}

	/**
	 * Flush rules if the event is queued.
	 *
	 * @since 1.0.6
	 */
	public static function maybe_flush_rewrite_rules() {
		if ( 'yes' === get_option( 'wpp_fr_as_queue_flush_rewrite_rules' ) ) {
			update_option( 'wpp_fr_as_queue_flush_rewrite_rules', 'no' );
			self::flush_rewrite_rules();
		}
	}

	/**
	 * Flush rewrite rules.
	 *
	 * @since 1.0.6
	 */
	public static function flush_rewrite_rules() {
		flush_rewrite_rules();
	}

	/**
	 * Disable Gutenberg for products.
	 *
	 * @since 1.0.6
	 *
	 * @param bool $can_edit Whether the post type can be edited or not.
	 * @param string $post_type The post type being checked.
	 *
	 * @return bool
	 */
	public static function gutenberg_can_edit_post_type( $can_edit, $post_type ) {
		return 'product' === $post_type ? false : $can_edit;
	}

	/**
	 * Add Product Support to Jetpack Omnisearch.
	 */
	public static function support_jetpack_omnisearch() {
		if ( class_exists( 'Jetpack_Omnisearch_Posts' ) ) {
			new Jetpack_Omnisearch_Posts( 'as_option' );
		}
	}

	/**
	 * Added product for Jetpack related posts.
	 *
	 * @since 1.0.6
	 *
	 * @param  array $post_types Post types.
	 *
	 * @return array
	 */
	public static function rest_api_allowed_post_types( $post_types ) {
		$post_types[] = 'as_option';

		return $post_types;
	}

}

Wpp_Add_Sales_Post_Types::init();