<?php
include WP_PLUGIN_DIR . '/meta-box/demo/demo.php';
add_filter( 'rwmb_meta_boxes', function ( $meta_boxes )
{
	$prefix = 'your_prefix_';

	// 1st meta box
	//$meta_boxes[] = array(
	//	'title'  => __( 'Standard Fields', 'your-prefix' ),
	//	'fields' => array(
	//		array(
	//			'name'          => __( 'Text', 'your-prefix' ),
	//			'id'            => "{$prefix}text",
	//			'type'          => 'text',
	//			'clone'         => true,
	//			'admin_columns' => true,
	//		),
	//		array(
	//			'name'          => __( 'Checkbox', 'your-prefix' ),
	//			'id'            => "{$prefix}checkbox",
	//			'type'          => 'checkbox',
	//			'std'           => 1,
	//			'admin_columns' => true,
	//		),
	//		array(
	//			'name'          => __( 'Radio', 'your-prefix' ),
	//			'id'            => "{$prefix}radio",
	//			'type'          => 'radio',
	//			'options'       => array(
	//				'value1' => __( 'Label1', 'your-prefix' ),
	//				'value2' => __( 'Label2', 'your-prefix' ),
	//			),
	//			'admin_columns' => true,
	//		),
	//		array(
	//			'name'          => __( 'Select', 'your-prefix' ),
	//			'id'            => "{$prefix}select",
	//			'type'          => 'select',
	//			'options'       => array(
	//				'value1' => __( 'Label1', 'your-prefix' ),
	//				'value2' => __( 'Label2', 'your-prefix' ),
	//			),
	//			'multiple'      => false,
	//			'std'           => 'value2',
	//			'placeholder'   => __( 'Select an Item', 'your-prefix' ),
	//			'admin_columns' => true,
	//		),
	//		array(
	//			'name'          => __( 'Textarea', 'your-prefix' ),
	//			'desc'          => __( 'Textarea description', 'your-prefix' ),
	//			'id'            => "{$prefix}textarea",
	//			'type'          => 'textarea',
	//			'cols'          => 20,
	//			'rows'          => 3,
	//			'admin_columns' => true,
	//		),
	//	),
	//);

	// 2nd meta box
	$meta_boxes[] = array(
		'title' => __( 'Advanced Fields', 'your-prefix' ),

		'fields' => array(
			array(
				'name' => __( 'Slider', 'your-prefix' ),
				'id'   => "{$prefix}slider",
				'type' => 'slider',

				'prefix' => __( '$', 'your-prefix' ),
				'suffix' => __( ' USD', 'your-prefix' ),

				'js_options'    => array(
					'min'  => 10,
					'max'  => 255,
					'step' => 5,
				),
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Color picker', 'your-prefix' ),
				'id'            => "{$prefix}color",
				'type'          => 'color',
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Checkbox list', 'your-prefix' ),
				'id'            => "{$prefix}checkbox_list",
				'type'          => 'checkbox_list',
				'options'       => array(
					'value1' => __( 'Label1', 'your-prefix' ),
					'value2' => __( 'Label2', 'your-prefix' ),
				),
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Autocomplete', 'your-prefix' ),
				'id'            => "{$prefix}autocomplete",
				'type'          => 'autocomplete',
				'options'       => array(
					'value1' => __( 'Label1', 'your-prefix' ),
					'value2' => __( 'Label2', 'your-prefix' ),
				),
				'size'          => 30,
				'clone'         => false,
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'oEmbed', 'your-prefix' ),
				'id'            => "{$prefix}oembed",
				'desc'          => __( 'oEmbed description', 'your-prefix' ),
				'type'          => 'oembed',
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Select', 'your-prefix' ),
				'id'            => "{$prefix}select_advanced",
				'type'          => 'select_advanced',
				'options'       => array(
					'value1' => __( 'Label1', 'your-prefix' ),
					'value2' => __( 'Label2', 'your-prefix' ),
				),
				'multiple'      => false,
				'placeholder'   => __( 'Select an Item', 'your-prefix' ),
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Taxonomy', 'your-prefix' ),
				'id'            => "{$prefix}taxonomy",
				'type'          => 'taxonomy',
				'taxonomy'      => 'category',
				'field_type'    => 'checkbox_list',
				'query_args'    => array(),
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'Posts (Pages)', 'your-prefix' ),
				'id'            => "{$prefix}pages",
				'type'          => 'post',
				'post_type'     => 'page',
				'field_type'    => 'select_advanced',
				'placeholder'   => __( 'Select an Item', 'your-prefix' ),
				'query_args'    => array(
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
				),
				'admin_columns' => true,
			),
			array(
				'name'          => __( 'WYSIWYG / Rich Text Editor', 'your-prefix' ),
				'id'            => "{$prefix}wysiwyg",
				'type'          => 'wysiwyg',
				'raw'           => false,
				'std'           => __( 'WYSIWYG default value', 'your-prefix' ),
				'options'       => array(
					'textarea_rows' => 4,
					'teeny'         => true,
					'media_buttons' => false,
				),
				'admin_columns' => true,
			),
		),
	);
	//$meta_boxes[] = [
	//	'title' => 'File and Image fields',
	//	'fields' => [
	//		// FILE UPLOAD
	//		array(
	//			'name' => __( 'File Upload', 'your-prefix' ),
	//			'id'   => "{$prefix}file",
	//			'type' => 'file',
	//		),
	//		// FILE ADVANCED (WP 3.5+)
	//		array(
	//			'name'             => __( 'File Advanced Upload', 'your-prefix' ),
	//			'id'               => "{$prefix}file_advanced",
	//			'type'             => 'file_advanced',
	//			'max_file_uploads' => 4,
	//			'mime_type'        => 'application,audio,video', // Leave blank for all file types
	//		),
	//		// IMAGE UPLOAD
	//		array(
	//			'name' => __( 'Image Upload', 'your-prefix' ),
	//			'id'   => "{$prefix}image",
	//			'type' => 'image',
	//		),
	//		// THICKBOX IMAGE UPLOAD (WP 3.3+)
	//		array(
	//			'name' => __( 'Thickbox Image Upload', 'your-prefix' ),
	//			'id'   => "{$prefix}thickbox",
	//			'type' => 'thickbox_image',
	//		),
	//		// PLUPLOAD IMAGE UPLOAD (WP 3.3+)
	//		array(
	//			'name'             => __( 'Plupload Image Upload', 'your-prefix' ),
	//			'id'               => "{$prefix}plupload",
	//			'type'             => 'plupload_image',
	//			'max_file_uploads' => 4,
	//		),
	//		// IMAGE ADVANCED (WP 3.5+)
	//		array(
	//			'name'             => __( 'Image Advanced Upload', 'your-prefix' ),
	//			'id'               => "{$prefix}imgadv",
	//			'type'             => 'image_advanced',
	//			'max_file_uploads' => 4,
	//		),
	//		// BUTTON
	//		array(
	//			'id'   => "{$prefix}button",
	//			'type' => 'button',
	//			'name' => ' ', // Empty name will "align" the button to all field inputs
	//		),
	//	],
	//];

	return $meta_boxes;
} );
