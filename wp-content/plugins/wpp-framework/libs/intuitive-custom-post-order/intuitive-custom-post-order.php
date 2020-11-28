<?php
	/*
	 * Plugin Name: Intuitive Custom Post Order
	 * Plugin URI: http://hijiriworld.com/web/plugins/intuitive-custom-post-order/
	 * Description: Intuitively, Order Items( Posts, Pages, ,Custom Post Types, Custom Taxonomies, Sites ) using a Drag and Drop Sortable JavaScript.
	 * Version: 3.1.2
	 * Author: hijiri
	 * Author URI: http://hijiriworld.com/web/
	 * Text Domain: intuitive-custom-post-order
	 * Domain Path: /languages
	 * License: GPLv2 or later
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
	*/

	/**
	 * Удалено всякое для мультисайтов
	 * Добалено подключение из фреймворка
	 * Добалены функции для констант
	 * Убраны страницы насторек
	 * Теперь работает всегда
	 *
	 */
	/**
	 * Class & Method
	 */
	if ( !class_exists( 'Hicpo' ) ) :


		class Hicpo {

			protected function constants() {

				list( $path, $url ) = self::get_path( dirname( __FILE__ )  );

				define( 'HICPO_URL', $url );
				define( 'HICPO_DIR', $path );

			}

			/**
			 * Get plugin base path and URL.
			 * The method is static and can be used in extensions.
			 *
			 * @link http://www.deluxeblogtips.com/2013/07/get-url-of-php-file-in-wordpress.html
			 *
			 * @param string $path Base folder path.
			 *
			 * @return array Path and URL.
			 */
			public static function get_path( $path = '' ) {
				// Plugin base path.
				$path = wp_normalize_path( untrailingslashit( $path ) );
				$themes_dir = wp_normalize_path( untrailingslashit( dirname( get_stylesheet_directory() ) ) );

				// Default URL.
				$url = plugins_url( '', $path . '/' . basename( $path ) . '.php' );
				//wpp_dump($url);

				// Included into themes.
				if ( 0 !== strpos( $path, wp_normalize_path( WP_PLUGIN_DIR ) ) && 0 !== strpos( $path, wp_normalize_path( WPMU_PLUGIN_DIR ) ) && 0 === strpos( $path, $themes_dir ) ) {
					$themes_url = untrailingslashit( dirname( get_stylesheet_directory_uri() ) );
					$url = str_replace( $themes_dir, $themes_url, $path );
				}

				$path = trailingslashit( $path );
				$url = trailingslashit( $url );

				return [
					$path,
					$url
				];
			}

			/**
			 * Construct
			 */
			function __construct() {
				self::constants();

				// admin init
				if ( empty( $_GET ) ) {
					add_action( 'admin_init', [
						$this,
						'refresh'
					] );
				}

				add_action( 'admin_init', [
					$this,
					'load_script_css'
				] );

				// sortable ajax action
				add_action( 'wp_ajax_update-menu-order', [
					$this,
					'update_menu_order'
				] );
				add_action( 'wp_ajax_update-menu-order-tags', [
					$this,
					'update_menu_order_tags'
				] );

				// reorder post types
				add_action( 'pre_get_posts', [
					$this,
					'hicpo_pre_get_posts'
				] );

				add_filter( 'get_previous_post_where', [
					$this,
					'hicpo_previous_post_where'
				] );
				add_filter( 'get_previous_post_sort', [
					$this,
					'hicpo_previous_post_sort'
				] );
				add_filter( 'get_next_post_where', [
					$this,
					'hocpo_next_post_where'
				] );
				add_filter( 'get_next_post_sort', [
					$this,
					'hicpo_next_post_sort'
				] );

				// reorder taxonomies
				add_filter( 'get_terms_orderby', [
					$this,
					'hicpo_get_terms_orderby'
				], 10, 3 );
				add_filter( 'wp_get_object_terms', [
					$this,
					'hicpo_get_object_terms'
				], 10, 3 );
				add_filter( 'get_terms', [
					$this,
					'hicpo_get_object_terms'
				], 10, 3 );

			}

			function load_script_css() {

				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'hicpojs', HICPO_URL . '/js/hicpo.js', [ 'jquery' ], null, true );

				wp_enqueue_style( 'hicpo', HICPO_URL . '/css/hicpo.css', [], null );

			}

			function refresh() {
				global $wpdb;
				$objects = $this->get_hicpo_options_objects();
				$tags = $this->get_hicpo_options_tags();

				if ( !empty( $objects ) ) {
					foreach ( $objects as $object ) {
						$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min 
					FROM $wpdb->posts 
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				" );
						if ( $result[ 0 ]->cnt == 0 || $result[ 0 ]->cnt == $result[ 0 ]->max )
							continue;

						$results = $wpdb->get_results( "
					SELECT ID 
					FROM $wpdb->posts 
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') 
					ORDER BY menu_order ASC
				" );
						foreach ( $results as $key => $result ) {
							$wpdb->update( $wpdb->posts, [ 'menu_order' => $key + 1 ], [ 'ID' => $result->ID ] );
						}
					}
				}

				if ( !empty( $tags ) ) {
					foreach ( $tags as $taxonomy ) {
						$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(term_order) as max, min(term_order) as min 
					FROM $wpdb->terms AS terms 
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id ) 
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
				" );
						if ( $result[ 0 ]->cnt == 0 || $result[ 0 ]->cnt == $result[ 0 ]->max )
							continue;

						$results = $wpdb->get_results( "
					SELECT terms.term_id 
					FROM $wpdb->terms AS terms 
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id ) 
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "' 
					ORDER BY term_order ASC
				" );
						foreach ( $results as $key => $result ) {
							$wpdb->update( $wpdb->terms, [ 'term_order' => $key + 1 ], [ 'term_id' => $result->term_id ] );
						}
					}
				}
			}


			function update_menu_order() {
				global $wpdb;

				parse_str( $_POST[ 'order' ], $data );

				if ( !is_array( $data ) )
					return false;

				// get objects per now page
				$id_arr = [];
				foreach ( $data as $key => $values ) {
					foreach ( $values as $position => $id ) {
						$id_arr[] = $id;
					}
				}

				// get menu_order of objects per now page
				$menu_order_arr = [];
				foreach ( $id_arr as $key => $id ) {
					$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );
					foreach ( $results as $result ) {
						$menu_order_arr[] = $result->menu_order;
					}
				}

				// maintains key association = no
				sort( $menu_order_arr );

				foreach ( $data as $key => $values ) {
					foreach ( $values as $position => $id ) {
						$wpdb->update( $wpdb->posts, [ 'menu_order' => $menu_order_arr[ $position ] ], [ 'ID' => intval( $id ) ] );
					}
				}
				wp_send_json_success( [ 'yes' ] );
			}

			function update_menu_order_tags() {
				global $wpdb;

				parse_str( $_POST[ 'order' ], $data );

				if ( !is_array( $data ) )
					return false;

				$id_arr = [];
				foreach ( $data as $key => $values ) {
					foreach ( $values as $position => $id ) {
						$id_arr[] = $id;
					}
				}

				$menu_order_arr = [];
				foreach ( $id_arr as $key => $id ) {
					$results = $wpdb->get_results( "SELECT term_order FROM $wpdb->terms WHERE term_id = " . intval( $id ) );
					foreach ( $results as $result ) {
						$menu_order_arr[] = $result->term_order;
					}
				}
				sort( $menu_order_arr );

				foreach ( $data as $key => $values ) {
					foreach ( $values as $position => $id ) {
						$wpdb->update( $wpdb->terms, [ 'term_order' => $menu_order_arr[ $position ] ], [ 'term_id' => intval( $id ) ] );
					}
				}
			}

			function hicpo_previous_post_where( $where ) {
				global $post;

				$objects = $this->get_hicpo_options_objects();
				if ( empty( $objects ) )
					return $where;

				if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
					$current_menu_order = $post->menu_order;
					$where = str_replace( "p.post_date < '" . $post->post_date . "'", "p.menu_order > '" . $current_menu_order . "'", $where );
				}
				return $where;
			}

			function hicpo_previous_post_sort( $orderby ) {
				global $post;

				$objects = $this->get_hicpo_options_objects();
				if ( empty( $objects ) )
					return $orderby;

				if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
					$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
				}
				return $orderby;
			}

			function hocpo_next_post_where( $where ) {
				global $post;

				$objects = $this->get_hicpo_options_objects();
				if ( empty( $objects ) )
					return $where;

				if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
					$current_menu_order = $post->menu_order;
					$where = str_replace( "p.post_date > '" . $post->post_date . "'", "p.menu_order < '" . $current_menu_order . "'", $where );
				}
				return $where;
			}

			function hicpo_next_post_sort( $orderby ) {
				global $post;

				$objects = $this->get_hicpo_options_objects();
				if ( empty( $objects ) )
					return $orderby;

				if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
					$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
				}
				return $orderby;
			}

			function hicpo_pre_get_posts( $wp_query ) {
				$objects = $this->get_hicpo_options_objects();
				if ( empty( $objects ) )
					return false;

				/**
				 * for Admin
				 *
				 * @default
				 * post cpt: [order] => null(desc) [orderby] => null(date)
				 * page: [order] => asc [orderby] => menu_order title
				 *
				 */

				if ( is_admin() ) {

					// adminの場合 $wp_query->query['post_type']=post も渡される
					if ( isset( $wp_query->query[ 'post_type' ] ) && !isset( $_GET[ 'orderby' ] ) ) {
						if ( in_array( $wp_query->query[ 'post_type' ], $objects ) ) {
							$wp_query->set( 'orderby', 'menu_order' );
							$wp_query->set( 'order', 'ASC' );
						}
					}

					/**
					 * for Front End
					 */

				} else {

					$active = false;

					// page or custom post types
					if ( isset( $wp_query->query[ 'post_type' ] ) ) {
						// exclude array()
						if ( !is_array( $wp_query->query[ 'post_type' ] ) ) {
							if ( in_array( $wp_query->query[ 'post_type' ], $objects ) ) {
								$active = true;
							}
						}
						// post
					} else {
						if ( in_array( 'post', $objects ) ) {
							$active = true;
						}
					}

					if ( !$active )
						return false;

					// get_posts()
					if ( isset( $wp_query->query[ 'suppress_filters' ] ) ) {
						if ( $wp_query->get( 'orderby' ) == 'date' || $wp_query->get( 'orderby' ) == 'menu_order' ) {
							$wp_query->set( 'orderby', 'menu_order' );
							$wp_query->set( 'order', 'ASC' );
						} elseif ( $wp_query->get( 'orderby' ) == 'default_date' ) {
							$wp_query->set( 'orderby', 'date' );
						}
						// WP_Query( contain main_query )
					} else {
						if ( !$wp_query->get( 'orderby' ) )
							$wp_query->set( 'orderby', 'menu_order' );
						if ( !$wp_query->get( 'order' ) )
							$wp_query->set( 'order', 'ASC' );
					}
				}
			}

			function hicpo_get_terms_orderby( $orderby, $args ) {
				if ( is_admin() )
					return $orderby;

				$tags = $this->get_hicpo_options_tags();

				if ( !isset( $args[ 'taxonomy' ] ) )
					return $orderby;

				$taxonomy = $args[ 'taxonomy' ];
				if ( !in_array( $taxonomy, $tags ) )
					return $orderby;

				$orderby = 't.term_order';
				return $orderby;
			}

			function hicpo_get_object_terms( $terms ) {
				$tags = $this->get_hicpo_options_tags();

				if ( is_admin() && isset( $_GET[ 'orderby' ] ) )
					return $terms;

				foreach ( $terms as $key => $term ) {
					if ( is_object( $term ) && isset( $term->taxonomy ) ) {
						$taxonomy = $term->taxonomy;
						if ( !in_array( $taxonomy, $tags ) )
							return $terms;
					} else {
						return $terms;
					}
				}

				usort( $terms, [
					$this,
					'taxcmp'
				] );
				return $terms;
			}

			function taxcmp( $a, $b ) {
				if ( $a->term_order == $b->term_order )
					return 0;
				return ( $a->term_order < $b->term_order ) ? -1 : 1;
			}

			function get_hicpo_options_objects() {
				$post_types = get_post_types( [
					'show_ui' => true,
					'show_in_menu' => true,
				], 'objects' );

				$objects = [];
				foreach ( $post_types as $post_type ) {
					if ( $post_type->name == 'attachment' )
						continue;
					$objects[] = $post_type->name;
				}
				return $objects;
			}

			function get_hicpo_options_tags() {

				$taxonomies = get_taxonomies( [
					'show_ui' => true,
				], 'objects' );

				$tags = [];

				foreach ( $taxonomies as $taxonomy ) {
					if ( $taxonomy->name == 'post_format' )
						continue;
					$tags[] = $taxonomy->name;
				}

				return $tags;
			}

		}

		$hicpo = new Hicpo();

	endif;