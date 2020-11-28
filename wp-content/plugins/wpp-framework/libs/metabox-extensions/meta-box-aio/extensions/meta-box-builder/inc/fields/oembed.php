<?php

class MBB_OEmbed extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' => 'checkbox',
		),
	);
}

new MBB_OEmbed;