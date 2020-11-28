<?php

class MBB_Password extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		// 'placeholder',
		// 'size' 	=> 'number',
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
		),
	);
}

new MBB_Password;