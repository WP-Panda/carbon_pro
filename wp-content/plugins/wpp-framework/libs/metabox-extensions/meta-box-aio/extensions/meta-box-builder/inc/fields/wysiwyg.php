<?php

class MBB_Wysiwyg extends MBB_Field {

	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		null,
		'std' => array(
			'type' => 'textarea',
			'size'	=> 'wide',
			'label' => 'Default',
		),
		'raw' => array(
			'type' => 'checkbox',
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
		),

	);

	public function __construct() {
		$label = 'WordPress editor options (<a href="https://codex.wordpress.org/Function_Reference/wp_editor">see documentation</a>)';
		$options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'options', $label );

		$this->advanced['options'] = array(
			'type' 		=> 'custom',
			'content' 	=> $options,
			'size'		=> 'wide',
			'label'     => 'Options',
		);

		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();
		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Wysiwyg;
