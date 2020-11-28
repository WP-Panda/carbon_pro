<?php
/**
 * Custom HTML field class.
 */
class MBB_Custom_Html_Field extends MBB_Field {

	public $basic = array(
		'id',
		'name',
		'type' => 'custom_html',
		// HTML content
		'std'  => array(
			'type' 	=> 'textarea',
			'label' => 'Content (HTML allowed)',
		),
	);
}

new MBB_Custom_Html_Field;