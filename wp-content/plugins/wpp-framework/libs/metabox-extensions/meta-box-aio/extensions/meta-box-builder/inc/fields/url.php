<?php

class MBB_Url extends MBB_Field
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
		null,
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		),
	);
}

new MBB_Url;