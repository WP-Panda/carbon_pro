<?php

class MBB_Number extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std' => 'number',
		// 'placeholder',
		null,
		'step' => array(
			'type' => 'text',
		),
		'min' => array(
			'type' => 'number',
		),
		'max' => array(
			'type' => 'number',
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
			'size'	=> 'wide'
		)
	);
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['placeholder'] );
    }
}

new MBB_Number;