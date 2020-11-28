<?php
add_action( 'rwmb_meta_boxes', 'YOUR_PREFIX_register_taxonomy_meta_boxes' );
function YOUR_PREFIX_register_taxonomy_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      => 'Standard Fields',
		'taxonomies' => 'category', // List of taxonomies. Array or string

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
}
