<?php
add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$meta_boxes[] = array(
		'title' => 'Standard Fields',
		'type'  => 'user',

		'fields' => array(
			array(
				'name'          => __( 'Featured?', 'textdomain' ),
				'id'            => 'featured',
				'type'          => 'checkbox',
				'admin_columns' => true,
			),
			array(
				'name' => __( 'Featured Image', 'textdomain' ),
				'id'   => 'image_advanced',
				'type' => 'image_advanced',
			),
			array(
				'name'          => __( 'Color', 'textdomain' ),
				'id'            => 'color',
				'type'          => 'color',
				'admin_columns' => array(
					'position' => 'after featured',
					'sort'     => true,
				),
			),
			array(
				'name'          => __( 'Featured Content', 'textdomain' ),
				'id'            => 'featured_content',
				'type'          => 'wysiwyg',
				'admin_columns' => [ 'position' => 'after color', 'searchable' => true, 'sort' => true ],
			),
		),
	);

	return $meta_boxes;
} );
