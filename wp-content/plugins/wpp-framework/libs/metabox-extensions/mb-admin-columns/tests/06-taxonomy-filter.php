<?php
/**
 * This file tests the taxonomy filter for a custom post type.
 * Requires to setup a "computer" post type and "brand" taxonomy (used for filtering).
 *
 * @package MB Admin Columns
 */

add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix = 'your_prefix_';

	$meta_boxes[] = array(
		'title'      => 'Advanced Fields',
		'post_types' => 'computer',

		'fields' => array(
			array(
				'name'          => 'Color',
				'id'            => "{$prefix}color",
				'type'          => 'color',
				'admin_columns' => true,
			),
			array(
				'name'          => 'Brand',
				'id'            => "{$prefix}taxonomy",
				'type'          => 'taxonomy',
				'taxonomy'      => 'brand',
				'admin_columns' => array(
					'position'   => 'after title',
					'filterable' => true,
				),
			),
		),
	);

	return $meta_boxes;
} );
