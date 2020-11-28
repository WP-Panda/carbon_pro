<?php

/**
 * Post types Class.
 */
class WPP_BR_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', [
			__CLASS__,
			'register_taxonomies'
		], 5 );
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {

		if ( ! is_blog_installed() ) {
			return;
		}

		$taxonomy_name = 'brands';
		$for_post_type = 'product';

		if ( taxonomy_exists( $taxonomy_name ) ) {
			return;
		}

		do_action( 'wpp_tf_register_taxonomy' );

		$permalinks = wc_get_permalink_structure();

		register_taxonomy( $taxonomy_name, apply_filters( 'wpp_tf_taxonomy_objects_' . $taxonomy_name, [ $for_post_type ] ), apply_filters( 'wpp_tf_taxonomy_args_' . $taxonomy_name, [
			'label'        => __( 'Brands', 'woocommerce' ),
			'labels'       => [
				'name'          => __( 'Brands', 'woocommerce' ),
				'singular_name' => __( 'Brand', 'woocommerce' ),
				'menu_name'     => _x( 'Brands', 'Admin menu name', 'woocommerce' ),
			],
			'hierarchical' => false,

			#'update_count_callback' => '_wc_term_recount',

			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		] ) );


		do_action( 'wpp_tf_after_register_taxonomy' );
	}

}

WPP_BR_Post_types::init();


function wpp_sad_register_post_types( $args ) {

	/**
	 * @param $nominative     string - именительный падеж кто? что?
	 * @param $genitive       string - родительный кого? чего?
	 * @param $dative         string - дательный кого? что?
	 * @param $instrumental   string - творительный кем? чем?
	 * @param $plural         string - множественное
	 * @param $plurals        string - множественное 2
	 */

	$args['sale_car'] = [
		'cir'          => true,
		'single'       => 'Машина на продажу',
		'genitive'     => 'Машину на продажу',
		'dative'       => 'Машина на продажу',
		'instrumental' => 'Машиной на продажу',
		'plural'       => 'Машины на продажу',
		'plurals'      => 'Машины на продажу'

	];

	$args['project'] = [
		'cir'               => true,
		'single'            => 'Проект',
		'genitive'          => 'Проект',
		'dative'            => 'Проект',
		'instrumental'      => 'Проекта',
		'plural'            => 'Проекты',
		'plurals'           => 'Проекты',
		'show_in_rest'      => false,
		'taxonomies'        => [ 'product_cat', 'attach_makers' ],


	];

	return $args;
}


add_filter( 'wpp_fr_register_post_types', 'wpp_sad_register_post_types', 22 );


function br_add_product_cat( $array ) {
	$array[] = 'project';

	return $array;
}

add_filter( 'woocommerce_taxonomy_objects_product_cat', 'br_add_product_cat', 11 );


function add__product_cat_to_custom_post_type( $args ) {
	$args['show_admin_column'] = true;

	return $args;
}

add_filter( 'woocommerce_taxonomy_args_product_cat', 'add__product_cat_to_custom_post_type', 22 );


function wpp_br_custom_taxes($args) {
	$args['attach_makers'] = [
		'cir'               => true,
		'single'            => 'Производитель',
		'genitive'          => 'Производителя',
		'plural'            => 'Производители',
		'hierarchical'      => false,
		'post_types'   => [ 'project' ],
	];

	return $args;
}

add_filter( 'wpp_fr_register_taxonomies', 'wpp_br_custom_taxes' );