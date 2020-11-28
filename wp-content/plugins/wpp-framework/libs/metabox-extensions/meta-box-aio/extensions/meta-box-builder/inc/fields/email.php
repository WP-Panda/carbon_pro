<?php

class MBB_Email extends MBB_Field {

	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std' => 'email',
		null,
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'size' => 'wide',
			'type' => 'checkbox',
		),
	);
}

new MBB_Email;
