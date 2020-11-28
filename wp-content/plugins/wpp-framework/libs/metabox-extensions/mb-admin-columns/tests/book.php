<?php
add_action( 'init', 'prefix_register_book_post_type' );
function prefix_register_book_post_type() {
	$labels = array(
		'name'          => _x( 'Books', 'Post Type General Name', 'textdomain' ),
		'singular_name' => _x( 'Book', 'Post Type Singular Name', 'textdomain' ),
		'menu_name'     => __( 'Book', 'textdomain' ),
	);
	$args   = array(
		'labels'     => $labels,
		'supports'   => array( 'title', 'editor' ),
		'public'     => true,
		'taxonomies' => array( 'category' ),
		'menu_icon'  => 'dashicons-book-alt',
	);
	register_post_type( 'book', $args );
}

add_filter( 'rwmb_meta_boxes', 'prefix_book_meta_boxes' );
function prefix_book_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'title'      => __( 'Book Info', 'textdomain' ),
		'post_types' => 'book',
		'fields'     => array(
			array(
				'name'          => __( 'Cover', 'textdomain' ),
				'id'            => 'cover',
				'type'          => 'image_advanced',
				'admin_columns' => 'before title', // Show this column before 'Title' column.
			),
			array(
				'name'          => __( 'Author', 'textdomain' ),
				'id'            => 'book_author',
				'type'          => 'text',
				'admin_columns' => array(
					'position'   => 'after title', // Show this column after 'Title' column.
					'searchable' => true,
				),
			),
			array(
				'name'          => __( 'Category', 'textdomain' ),
				'id'            => 'category',
				'type'          => 'taxonomy',
				'taxonomy'      => 'category',
				'admin_columns' => array(
					'position' => 'replace categories', // Replace default 'Categories' column.
					'title'    => 'Genre',              // Custom title.
				),
			),
			array(
				'name'          => __( 'Publisher', 'textdomain' ),
				'id'            => 'publisher',
				'type'          => 'text',
				'admin_columns' => 'replace date', // Replace 'Date' column.
			),
			array(
				'name'          => __( 'Pages', 'textdomain' ),
				'id'            => 'pages',
				'type'          => 'number',
				'admin_columns' => true, // Just show this column.
			),
			array(
				'name'          => __( 'Price', 'textdomain' ),
				'id'            => 'price',
				'type'          => 'number',
				'admin_columns' => array(
					'before' => '$',    // Show custom HTML before and after column value.
					'after'  => ' USD',
					'sort'   => true,   // Sort this column.
				),
			),
		),
	);

	return $meta_boxes;
}
