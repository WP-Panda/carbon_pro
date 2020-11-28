<?php
add_filter( 'rwmb_meta_boxes', 'prefix_product_meta_boxes' );
function prefix_product_meta_boxes( $meta_boxes )
{
	$meta_boxes[] = array(
		'title'      => 'Additional Info',
		'post_types' => 'product',
		'fields'     => array(
			array(
				'name'          => __( 'Unit', 'textdomain' ),
				'id'            => 'unit',
				'type'          => 'text',
				'admin_columns' => 'replace sku',
			),
			array(
				'name'          => __( 'Delivery', 'textdomain' ),
				'id'            => 'delivery',
				'type'          => 'select',
				'options'       => array(
					'same_day' => 'Same day',
					'next_day' => 'Next day',
				),
				'admin_columns' => 'replace date',
			),
		),
	);
	return $meta_boxes;
}
