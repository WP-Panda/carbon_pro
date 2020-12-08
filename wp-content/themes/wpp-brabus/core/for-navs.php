<?php
	/**
	 * @package brabus.coms
	 * @author  WP_Panda
	 * @version 1.0.0
	 */

	defined( 'ABSPATH' ) || exit;


	function wpp_get_the_category_list( $post_id = false ) {

		$the_list   = [];
		$categories = apply_filters( 'the_product_cat_list', get_the_terms( $post_id, 'product_cat' ), $post_id );

		if ( ! empty( $categories ) ) {


			foreach ( $categories as $category ) {

				if ( $category->parent ) {
					$term = get_term( $category->parent, 'product_cat' );

					if ( $term ) {

						$parents = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );

						foreach ( array_reverse( $parents ) as $term_id ) {
							$parent                        = get_term( $term_id, 'product_cat' );
							$the_list [ $parent->term_id ] = $parent->name;

						}

					}
				}
				$the_list[ $category->term_id ] = $category->name;
			}
		}


		return $the_list;
	}


	function cmp_nav( $a, $b ) {
		return strcmp( $a[ "name" ], $b[ "name" ] );
	}

	function cmp_nav_2( $a, $b ) {
		return strcmp( $a->slug, $b->slug );
	}

	function wpp_br_nav_models() {

		$html = '';

		$terms = get_terms( [
			'taxonomy' => [ 'product_cat' ],
			'parent'   => 0,
		] );


		if ( ! empty( $terms ) ) {

			$html .= '<li class="menu-item menu-maker-items">';
			$html .= sprintf( '<a href="javascript:void(0);" class="menu-maker-trigger" title="" data-text="%1$s">%1$s</a>', wpp_br_lng( 'select_maker' ) );
			$html .= '<ul class="menu-maker-items-wrap">';


			//wpp_console( $terms );
			usort( $terms, "cmp_nav_2" );
			//wpp_console( $terms );

			foreach ( $terms as $term ) {

				/*	$parents[] = [
						'name' => $term->name,
						'link' => get_term_link( $term->term_id, 'product_cat' )
					];*/


				$terms_c = get_terms( [
					'taxonomy' => [ 'product_cat' ],
					'parent'   => $term->term_id
				] );

				if ( ! empty( $terms_c ) ) {
					$out = [];

					/*$out[ $term->term_id ][] = [
						'name' => wpp_br_lng('all_models'),
						'link' => get_term_link( $term->term_id, 'product_cat' )
					];*/

					foreach ( $terms_c as $term_c ) {
						$out[ $term->term_id ][] = [
							'name' => $term_c->name,
							'link' => get_term_link( $term_c->term_id, 'product_cat' )
						];
					}

					usort( $out[ $term->term_id ], "cmp_nav" );

					array_unshift( $out[ $term->term_id ], [
						'name' => wpp_br_lng( 'all_models' ),
						'link' => get_term_link( $term->term_id, 'product_cat' )
					] );


					$serialize = json_encode( $out );

				}

				$html .= sprintf( '<li class="menu-item"><a href="javascript:void(0);" class="menu-maker-link" title="" data-child=\'[%s]\'>%s</a></li>', ! empty( $serialize ) ? $serialize : '', $term->name );
			}
			$html .= '</ul>';
			$html .= '</li>';
			$html .= sprintf( '<li class="menu-item menu-model-items"><a href="javascript:void(0);" class="menu-model-trigger wpp-disabled" title="">%s</a><ul class="menu-model-items-wrap"></ul></li>', wpp_br_lng( 'select_model' ) );
		}

		return $html;
	}

	add_filter( 'wp_nav_menu_items', 'add_search_box_to_menu', 10, 2 );
	function add_search_box_to_menu( $items, $args ) {

		$lng       = get_locale();
		$item_prev = $args->theme_location === 'main_' . $lng ? wpp_br_nav_models() : '';

		return $item_prev . $items;
	}


	function wpp_navs_fllter() {

		$html = '';

		$terms = get_terms( [
			'taxonomy' => [ 'product_cat' ],
			'parent'   => 0,
		] );


		$data = [];

		if ( ! empty( $terms ) ) {

			$html_s = '';
			$html_s .= sprintf( '<option value="">%1$s</option>', wpp_br_lng( 'select_maker' ) );
			$out    = [];

			usort( $terms, "cmp_nav_2" );


			foreach ( $terms as $term ) {


				$terms_c = get_terms( [
					'taxonomy' => [ 'product_cat' ],
					'parent'   => $term->term_id
				] );

				if ( ! empty( $terms_c ) ) {


					foreach ( $terms_c as $term_c ) {
						$out[ $term->term_id ][] = [
							'name' => $term_c->name,
							'id'   => $term_c->term_id
						];
					}

					usort( $out[ $term->term_id ], "cmp_nav" );

					array_unshift( $out[ $term->term_id ], [
						'name' => wpp_br_lng( 'all_models' ),
						'id'   => $term->term_id
					] );


				}


				$selected = ! empty( $_REQUEST[ 'wpp-filter-brend' ] ) && $term->term_id === (int) $_REQUEST[ 'wpp-filter-brend' ] ? ' selected="selected"' : '';

				$html_s .= sprintf( '<option value=\'%s\' data-options=\'[%s]\'%s>%s</option>', $term->term_id, json_encode( $out ), $selected, $term->name );
			}

			$html .= sprintf( '<select name="wpp-filter-brend" class="form--text form--border-bottom dirty filter-news-cats col-12 col-sm-4 col-lg-3">%s</select>', $html_s );

			$options = '';

			if ( ! empty( $_REQUEST[ 'wpp-filter-brend' ] ) ) {

				foreach ( $out[ $_REQUEST[ 'wpp-filter-brend' ] ] as $one ) {
					$selected = ! empty( $_REQUEST[ 'wpp-filter-model' ] ) && $one[ 'id' ] === (int) $_REQUEST[ 'wpp-filter-model' ] ? ' selected="selected"' : '';

					$options .= sprintf( '<option value=\'%s\'%s>%s</option>', $one[ 'id' ], $selected, $one[ 'name' ] );
				}

			}

			$html .= sprintf( '<select  name="wpp-filter-model"  class="form--text form--border-bottom dirty filter-news-model col-12 col-sm-4 col-lg-3"><option value="">%s</option>%s</select>', wpp_br_lng( 'select_model' ), $options );
		}

		return $html;
	}


	function wpp_br_sort_filter_args() {

		$car_args = [
			'post_type' => [ 'project' ],
			'nopaging'  => true,
			'order'     => 'ASC',
		];


		$posts = get_posts( $car_args );

		$first = $next = $maker = [];


		foreach ( $posts as $post ) {

			$post_makers = wp_get_post_terms( $post->ID, 'attach_makers', [ 'fields' => 'all' ] );
			$post_terms  = wp_get_post_terms( $post->ID, 'product_cat', [ 'fields' => 'all' ] );
			$firt_term   = wpp_get_term_parents_list( $post_terms[ 0 ]->term_id, 'product_cat', true );


			if ( ! empty( $post_makers ) ) {
				$maker[ $post_makers[ 0 ]->term_id ] = $post_makers[ 0 ]->name;
			}

			if ( ! empty( $firt_term ) && is_array( $firt_term ) ) {
				$first[ $firt_term[ 0 ][ 'term_id' ] ] = $firt_term[ 0 ][ 'name' ];


				if ( $firt_term[ 0 ][ 'term_id' ] !== $post_terms[ 0 ]->term_id ) {
					$next[ $post_terms[ 0 ]->term_id ] = $post_terms[ 0 ]->name;
				}
			}

		}


		$first = wpp_fr_array_keys_to_string( $first );
		$next  = wpp_fr_array_keys_to_string( $next );
		$maker = wpp_fr_array_keys_to_string( $maker );
		asort( $first );
		asort( $next );
		asort( $maker );


		$out = [
			'car_brand'    => $first,
			'car_model'    => $next,
			'attach_brand' => $maker
		];


		return $out;

	}

	/**
	 * Цепочка всех терминов до родительского
	 *
	 * @param      $term_id
	 * @param      $taxonomy
	 * @param bool $parent
	 *
	 * @return array|null|string|WP_Error|WP_Term
	 */
	function wpp_get_term_parents_list( $term_id, $taxonomy, $only_top = false ) {
		$list   = '';
		$term   = get_term( $term_id, $taxonomy );
		$arrgss = [];
		if ( is_wp_error( $term ) ) {
			return $term;
		}

		if ( ! $term ) {
			return $list;
		}

		$term_id = $term->term_id;

		$parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );
		array_unshift( $parents, $term_id );


		$out = [];


		$a = 0;
		foreach ( array_reverse( $parents ) as $term_id ) {
			$parent = get_term( $term_id, $taxonomy );
			$out[]  = [ 'term_id' => $parent->term_id, 'name' => $parent->name ];
			if ( ! empty( $only_top ) && wp_validate_boolean( $only_top ) ) {
				break;
			}
			$a ++;
		}


		return $out;
	}


	/**
	 * Дерево категорий
	 *
	 * @return array|mixed
	 */
	function wpp_get_term_product_tree( $post_type = null, $taxonomy = null, $id ) {

		if ( empty( $post_type ) ) {
			$post_type = [ 'project' ];
		}

		if ( empty( $taxonomy ) ) {
			$taxonomy = 'product_cat';
		}

		$arrgss = [];

		$car_args = [
			'post_type' => $post_type,
			'nopaging'  => true,
			'order'     => 'ASC',
		];

		$posts = get_posts( $car_args );
		foreach ( $posts as $post ) {

			$post_terms = wp_get_post_terms( $post->ID, $taxonomy, [ 'fields' => 'all' ] );
			$term_id    = $post_terms[ 0 ]->term_id;

			$term = get_term( $term_id, $taxonomy );

			if ( is_wp_error( $term ) ) {
				continue;
			}

			if ( ! $term ) {
				continue;
			}

			$term_id = $term->term_id;

			$parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );
			array_unshift( $parents, $term_id );


			$arrgs = [];
			$curr  = &$arrgs;
			foreach ( array_flip( array_reverse( $parents ) ) as $idx => $val ) {
				if ( ! array_key_exists( $idx, $curr ) ) {
					$curr[ $idx ] = [];
				}

				$curr = &$curr[ $idx ];

			}
			$curr   = $term->name;
			$arrgss = array_replace_recursive( $arrgss, $arrgs );


		}


		if ( ! empty( $id ) ) {
			return $arrgss[ $id ];
		}

		return $arrgss;
	}


	function tax_tree_with_labels( $array = null, $out = '', $drop = false, $n = 1, $cars_vas_maker = [] ) {
		if ( empty( $array ) ) {
			$array = wpp_get_term_product_tree( '', '', 503 );
		}

		$out .= '<ul>';

		if ( empty( $cars_vas_maker ) ) :
			$cars_vs_maker = wpp_get_post_tems_maker();
		endif;

		foreach ( $array as $key => $val ) {

			$class   = is_array( $val ) ? ' filter-sublavel' : '';
			$class_t = is_array( $val ) ? '<span class="trigger"></span>' : '';
			//if ( $n !== 1 ) {
			$out .= sprintf( '<li class="wpp-filter-item %s"><span class="filter-item" data-tun="%s" data-val=".t_%d">%s</span>%s', $class, '.t_' . implode( ',.t_', $cars_vs_maker[ $key ] ), $key, get_term( $key )->name, $class_t );
			//}
			if ( is_array( $val ) ) {
				$n ++;
				$out .= tax_tree_with_labels( $val, '', true, $n, $cars_vs_maker );
			}
			$out .= '</li>';
		}
		$out .= '</ul>';

		return $out;

	}

	function wpp_f_s() {

		$wrap = <<<WREAP
		<div class="col-12 col-sm-3 col-lg-3 wpp-form-row wpp-filers-wrap">
                <div class="filter-select">
                        <button class="wpp-f-active-button" data-text="Все модели">Все модели</button>
                        <input type="hidden" id="filter-models-input" class="filter-display">
                        <input type="hidden" id="filter-models-data" class="filter-values wpp-mix-filter" name="filter_models">
                </div>
                        <div class="filter-drop">
                        %s   
                        </div>
                        </div>
               
WREAP;

		return sprintf( $wrap, tax_tree_with_labels() );

	}


	/**
	 *  Выпадающий список фильтр
	 * обертка
	 *
	 * @return string
	 */
	function wpp_filter_data_wrap() {

		$wrap = <<<WREAP
		<div class="col-12 col-sm-4 col-lg-4 wpp-form-row wpp-filers-wrap model_class">
               <div class="wpp-f-trigger-group">
                    <button class="wpp-f-active-button" data-text="Все модели">Все модели</button>
                    <input type="hidden" class="wpp-f-display-input" name="">
                    <input type="hidden" class="wpp-f-search-input" name="">
                    <input type="hidden" class="wpp-f-keys-input wpp-mix-filter" value="%s" name="filter_models">
                </div>
                <div class="model-drop"></div>
                
         </div>
               
WREAP;

		return sprintf($wrap,!empty($_GET['filter_models']) ? $_GET['filter_models'] : '');
	}


	/**
	 * Список выпадающих полей
	 *
	 * @param null   $array
	 * @param string $out
	 * @param bool   $drop
	 * @param int    $n
	 *
	 * @return string
	 */
	function wpp_tax_tree_with_labels( $array = null, $out = '', $drop = false, $n = 1, $id, $cars_vs_maker = null, $a = 1 ) {

		if ( empty( $array ) ) {
			$array = wpp_get_term_product_tree( '', '', $id );
		}

		$class = $n === 1 ? ' class="wpp-f-drop-list"' : '';

		$out .= sprintf( '<ul%s>', $class );
		if($n === 1 ) {
			$out .= sprintf('<li class="wpp-f-all">Любая</li>');
		}

		if ( empty( $cars_vas_maker ) ) :
			$cars_vs_maker = wpp_get_post_tems_maker();
		endif;

		foreach ( $array as $key => $val ) {

			$class   = is_array( $val ) ? ' filter-sublavel' : '';
			$class_t = is_array( $val ) ? '<span class="trigger"></span>' : '';

			$out .= sprintf( '<li class="wpp-filter-item %s" data-tun="%s" data-value=".t_%d" data-level="%d"><span class="f-item-text">%s</span>%s', $class, '.t_' . implode( ',.t_', $cars_vs_maker[ $key ] ), $key, $a, get_term( $key )->name, $class_t );

			if ( is_array( $val ) ) {
				$n ++;
				$out .= wpp_tax_tree_with_labels( $val, '', true, $n, $cars_vs_maker, $a );
			}


			$out .= '</li>';

		}


		$out .= '</ul>';

		return $out;

	}


	/**
	 * Ключ - модели : значение - массив тюнинга
	 *
	 * @return array
	 */

	function wpp_get_post_tems_maker() {

		$car_args = [
			'post_type' => [ 'project' ],
			'nopaging'  => true,
			'order'     => 'ASC',
		];


		$posts = get_posts( $car_args );

		$maker = [];


		foreach ( $posts as $post ) {

			$post_makers = wp_get_post_terms( $post->ID, 'attach_makers', [ 'fields' => 'ids' ] );
			$post_terms  = wp_get_post_terms( $post->ID, 'product_cat', [ 'fields' => 'ids' ] );

			if ( ! empty( $post_makers ) ) {
				$maker[ $post_terms[ 0 ] ][] = $post_makers[ 0 ];

				$parents = get_ancestors( $post_terms[ 0 ], 'product_cat', 'taxonomy' );

				if ( ! empty( $parents ) ) {
					foreach ( $parents as $parent ) {
						$maker[ $parent ][] = $post_makers[ 0 ];
					}
				}
			}

		}

		return $maker;

	}