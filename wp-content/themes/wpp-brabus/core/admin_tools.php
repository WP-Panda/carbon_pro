<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Обновление Данных установки
 */
function wpp_create_date_set() {

	$time    = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;
	$post_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : false;

	if ( ! empty( $post_id ) ) {
		update_post_meta( $post_id, 'create_time', $time );

		$post_data = [
			'ID' => $post_id,
		];

		// Update the post
		wp_update_post( $post_data );
		wp_send_json_success( [ 'upd' ] );
	} else {
		wp_send_json_error( [ 'no' ] );
	}
}

add_action( 'wp_ajax_wpp_create_date_set', 'wpp_create_date_set' );

/**
 * Обновление набора продуктов
 */
function wpp_change_bundle() {
	$bundle  = ! empty( $_POST['bundle'] ) ? (int) $_POST['bundle'] : 0;
	$post_id = ! empty( $_POST['val'] ) ? (int) $_POST['val'] : 0;

	if ( ! empty( $bundle ) ) {
		$bundle_args = get_post_meta( (int) $bundle, 'bundle_package', false );
	}


	if ( empty( $bundle_args ) ) {
		$bundle_args = [];
	}

	if ( ! empty( $post_id ) ) {

		$key = array_search( (string) $post_id, $bundle_args );


		if ( isset( $key ) && $key !== false ) {
			unset( $bundle_args[ $key ] );
		} else {
			$bundle_args[] = (string) $post_id;
		}

		delete_post_meta( (int) $bundle, 'bundle_package' );

		if ( ! empty( $bundle_args ) ) {
			foreach ( $bundle_args as $one ) {
				add_post_meta( (int) $bundle, 'bundle_package', $one );
			}
		}


		$bundler = get_post_meta( (int) $bundle, 'bundle_package', false );

		if ( ! empty( $bundler ) ) {
			$assembly_s = $paint_s = 0;
			foreach ( $bundler as $one ) {

				$assembly = get_post_meta( (int) $one, 'assembly', true );
				$paint    = get_post_meta( (int) $one, 'paint', true );


				if ( ! empty( $assembly ) ) {
					$assembly_s = $assembly_s + (float) $assembly;
				}

				if ( ! empty( $paint ) ) {
					$paint_s = $paint_s + (float) $paint;
				}
				#wpp_d_log( $assembly_s );
				#wpp_d_log( $paint_s );

				if ( ! empty( $assembly_s ) ) {
					update_post_meta( (int) $bundle, 'assembly', $assembly_s );
				} else {
					delete_post_meta( (int) $bundle, 'assembly' );
				}

				if ( ! empty( $paint_s ) ) {
					update_post_meta( (int) $bundle, 'paint', $paint_s );
				} else {
					delete_post_meta( (int) $bundle, 'paint' );
				}

			}
		}


		wp_send_json_success( [ $key, $bundle_args ] );
	} else {
		wp_send_json_error( [ 'error' ] );
	}


}

add_action( 'wp_ajax_wpp_change_bundle', 'wpp_change_bundle' );

/**
 * Обновление набора продуктов
 */
function wpp_sort_bundle() {
	$bundler = ! empty( $_POST['bundle'] ) ? $_POST['bundle'] : 0;
	$post_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	parse_str( $bundler, $bundle );

	if ( ! empty( $bundler ) && ! empty( $bundle ) ) {

		delete_post_meta( (int) $post_id, 'bundle_package' );

		foreach ( $bundle['wpp_bundle_sort'] as $one ) {
			add_post_meta( (int) $post_id, 'bundle_package', $one );
		}


		wp_send_json_success( [ $bundle['wpp_bundle_sort'] ] );
	} else {
		wp_send_json_error( [ $bundler ] );
	}


}

add_action( 'wp_ajax_wpp_sort_bundle', 'wpp_sort_bundle' );

function aditional_variants_button() {
	printf( '<div class="misc-pub-section" style="text-align: right;"><div></div><button type="button" class="wpp-create-variants button">Генерировать варианты</button></div>' );
}

add_action( 'post_submitbox_misc_actions', 'aditional_variants_button', 20 );

/**
 * Обновление слайдкера
 */
add_action( 'wp_ajax_wpp_sort_terms_img', 'wpp_sort_terms_img' );
function wpp_sort_terms_img() {
	$images = ! empty( $_POST['images'] ) ? $_POST['images'] : 0;
	$term   = ! empty( $_POST['term'] ) ? (int) $_POST['term'] : 0;

	parse_str( $images, $images_f );

	foreach ( $images_f as $key => $val ) {

		update_term_meta( $term, $key, $val );
	}

	wp_send_json_success( [ 'images' => $images_f ] );

}


add_action( 'wp_ajax_wpp_sort_posts', 'wpp_sort_posts' );
/**
 * ортировка товаров
 */

function wpp_sort_posts() {

	$posts  = ! empty( $_POST['posts'] ) ? $_POST['posts'] : 0;
	$target = ! empty( $_POST['parent'] ) ? (int) $_POST['parent'] : 0;

	if ( empty( $target ) ) {
		wp_send_json_error( [ 'not_target' ] );
	}

	$post = get_post( $target );

	$object_type = !empty( $post ) ? 'post' : 'term';

	if ( 'post' === $object_type ) {
		update_post_meta( $target, 'posts_order', $posts );
	} else {
		update_term_meta( $target, 'posts_order', $posts );
	}

	wp_send_json_success( [ $object_type, $posts,$post ] );

}

/**
 * Обновление машин на продажу
 */
add_action( 'wp_ajax_wpp_save_car_bundle', 'wpp_save_car_bundle' );
function wpp_save_car_bundle() {
	$post = ! empty( $_POST['post'] ) ? $_POST['post'] : false; // запчасть
	$cars = ! empty( $_POST['cars'] ) ? $_POST['cars'] : false; //машины

	if ( empty( $post ) || empty( $cars ) ) {
		wp_send_json_error( [ 'error' ] );
	}

	foreach ( $cars as $car ) {

		$cars_package = get_post_meta( (int) $car, '_in_sale_package', true );
		if ( empty( $cars_package ) ) {
			$cars_package = [];
		}
		$cars_package[] = (int) $post;
		update_post_meta( (int) $car, '_in_sale_package', array_unique( $cars_package ) );
		//update_post_meta( (int) $car, '_in_sale_package', '' );

	}

	$curent_cars = get_post_meta( (int) $post, '_in_sale_cars', true );
	if ( ! empty( $curent_cars ) ) {
		$cars = array_merge( $curent_cars, $cars );
	}
	update_post_meta( (int) $post, '_in_sale_cars', array_unique( $cars ) );
	//update_post_meta( (int) $post, '_in_sale_cars', '' );

	wp_send_json_success( [ 'images' ] );

}

/**
 * Ужаление запчасти из машины на продажу
 */
add_action( 'wp_ajax_wpp_at_remove_sale_car_detail', 'wpp_at_remove_sale_car_detail' );
function wpp_at_remove_sale_car_detail() {
	$post_id   = ! empty( (int) $_POST['id'] ) ? (int) $_POST['id'] : false;
	$parent_id = ! empty( (int) $_POST['parent'] ) ? (int) $_POST['parent'] : false;

	if ( empty( $post_id ) || empty( $parent_id ) ) {
		wp_send_json_error( [ 'error' ] );
	}


	$cars_package = get_post_meta( (int) $parent_id, '_in_sale_package', true ); //тут лежат детали
	$curent_cars  = get_post_meta( (int) $post_id, '_in_sale_cars', true ); // тут лежат машины

	$key = array_search( $post_id, $cars_package );

	if ( ! empty( (int) $key + 1 ) ) {
		unset( $cars_package[ $key ] );
		update_post_meta( (int) $parent_id, '_in_sale_package', array_unique( $cars_package ) );
	}

	$key_2 = array_search( $parent_id, $curent_cars );

	if ( ! empty( (int) $key_2 + 1 ) ) {
		unset( $curent_cars[ $key_2 ] );
		update_post_meta( (int) $post_id, '_in_sale_package', array_unique( $curent_cars ) );
	}

	wp_send_json_success( [ 'key_1' => $key, 'key_2' => $key_2 ] );
}

/**
 * Обновление проектов на продажу
 */
add_action( 'wp_ajax_wpp_project_car_bundle', 'wpp_project_car_bundle' );
function wpp_project_car_bundle() {
	$post = ! empty( $_POST['post'] ) ? $_POST['post'] : false; // запчасть
	$cars = ! empty( $_POST['cars'] ) ? $_POST['cars'] : false; //машины

	if ( empty( $post ) || empty( $cars ) ) {
		wp_send_json_error( [ 'error' ] );
	}

	foreach ( $cars as $car ) {

		$cars_package = get_post_meta( (int) $car, '_in_project_package', true );
		if ( empty( $cars_package ) ) {
			$cars_package = [];
		}
		$cars_package[] = (int) $post;
		update_post_meta( (int) $car, '_in_project_package', array_unique( $cars_package ) );
		//update_post_meta( (int) $car, '_in_sale_package', '' );

	}

	$curent_cars = get_post_meta( (int) $post, '_in_project_cars', true );
	if ( ! empty( $curent_cars ) ) {
		$cars = array_merge( $curent_cars, $cars );
	}
	update_post_meta( (int) $post, '_in_project_cars', array_unique( $cars ) );
	//update_post_meta( (int) $post, '_in_sale_cars', '' );

	wp_send_json_success( [ 'images' ] );

}

/**
 * Ужаление запчасти из проекта
 */
add_action( 'wp_ajax_wpp_at_remove_project_detail', 'wpp_at_remove_project_detail' );
function wpp_at_remove_project_detail() {
	$post_id   = ! empty( (int) $_POST['id'] ) ? (int) $_POST['id'] : false;
	$parent_id = ! empty( (int) $_POST['parent'] ) ? (int) $_POST['parent'] : false;

	if ( empty( $post_id ) || empty( $parent_id ) ) {
		wp_send_json_error( [ 'error' ] );
	}


	$cars_package = get_post_meta( (int) $parent_id, '_in_project_package', true ); //тут лежат детали
	$curent_cars  = get_post_meta( (int) $post_id, '_in_project_cars', true ); // тут лежат машины

	$key = array_search( $post_id, $cars_package );

	if ( ! empty( (int) $key + 1 ) ) {
		unset( $cars_package[ $key ] );
		update_post_meta( (int) $parent_id, '_in_project_package', array_unique( $cars_package ) );
	}

	$key_2 = array_search( $parent_id, $curent_cars );

	if ( ! empty( (int) $key_2 + 1 ) ) {
		unset( $curent_cars[ $key_2 ] );
		update_post_meta( (int) $post_id, '_in_project_package', array_unique( $curent_cars ) );
	}

	wp_send_json_success( [ 'key_1' => $key, 'key_2' => $key_2 ] );
}

/**
 * Список наборов в категории массив данных
 * @return array|bool
 */
function wpp_get_bundle_package() {

	$term_id = get_queried_object_id();

	$query_b = new WP_Query( [
		'post_type' => 'product',
		'nopaging'  => true,
		'tax_query' => [
			[
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => [ 'bundle' ]
			],
			[
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => [ $term_id ]
			],
			'relation' => 'AND'
		]
	] );

	wp_reset_query();
	if ( ! empty( $query_b->posts ) ) {
		$bundle = [];
		foreach ( $query_b->posts as $one ) {

			$bundle[ $one->ID ] = [
				'title'          => $one->post_title,
				'bundle_package' => get_post_meta( $one->ID, 'bundle_package', false )
			];

		}

	} else {
		$bundle = false;
	}

	return $bundle;
}

function wpp_br_bundle_editor( $post_id, $bundle ) {

	if ( ! wpp_fr_user_is_admin() ) {
		return false;
	}
	if ( ! empty( $bundle ) ) {

		$out = '';
		foreach ( $bundle as $key => $val ) {
			$out .= sprintf( '<li><input type="checkbox" class="wpp-bundle-edit" name="b-%1$s-%2$s" id="b-%1$s-%2$s" data-bundle="%1$s" value="%2$s"%3$s><label for="b-%1$s-%2$s">%4$s</label></li>', $key, $post_id, in_array( $post_id, $val['bundle_package'] ) ? ' checked="checked"' : '', $val['title'] );
		}
		printf( '<ul class="wpp_admin_bundles">%s</ul>', $out );
	}
}

function enqueue_media_uploader() {
	wp_enqueue_media();
}

add_action( "wp_enqueue_scripts", "enqueue_media_uploader" );

function wpp_save_term_slider() {

	$preff  = ! empty( $_POST['category'] ) ? '_' . $_POST['category'] : '';
	$afrer  = ! empty( $_POST['tag'] ) ? '_' . $_POST['tag'] : '';
	$images = ! empty( $_POST['images'] ) ? $_POST['images'] : false;

	$name = $preff . $afrer;

	if ( empty( $preff ) ) {
		wp_send_json_error( 'preff' );
	}

	if ( empty( $name ) ) {
		wp_send_json_error( 'name' );
	}

	if ( empty( $images ) ) {
		wp_send_json_error( 'images' );
	}

	$meta = get_term_meta( (int) $_POST['category'], $name, true );

	if ( ! empty( $meta ) ) {
		$images = array_merge( $images, $meta );
	}

	update_term_meta( (int) $_POST['category'], $name, $images );

	wp_send_json_success( [ 'name' => $name, 'images' => $images ] );

}

add_action( 'wp_ajax_wpp_save_term_slider', 'wpp_save_term_slider' );

function wpp_save_term_slider_remoove_img() {

	$category = $_POST['category'];
	$key      = $_POST['key'];
	$img      = $_POST['imag'];


	$images = get_term_meta( $category, $key, true );

	$search = array_search( (string) $img, $images );


	if ( isset( $search ) ) {
		unset( $images[ $search ] );
	}

	update_term_meta( (int) $category, $key, $images );

	wp_send_json_success( [ 'name' => $key, 'imag' => $img ] );

}

add_action( 'wp_ajax_wpp_save_term_slider_remoove_img', 'wpp_save_term_slider_remoove_img' );

function wpp_terms_slider_format() {
	$term   = $_POST['term'];
	$key    = $_POST['key'];
	$format = $_POST['format'];

	update_term_meta( (int) $term, $key, $format );

	wp_send_json_success( [ 'name' => $key, 'format' => $format ] );
}

add_action( 'wp_ajax_wpp_terms_slider_format', 'wpp_terms_slider_format' );

function wpp_single_slider_format() {
	$post_id = $_POST['post_id'];
	$format  = $_POST['format'];

	update_post_meta( (int) $post_id, '_slider_format', $format );

	wp_send_json_success( [ 'post_id' => $post_id, 'format' => $format ] );
}

add_action( 'wp_ajax_wpp_single_slider_format', 'wpp_single_slider_format' );