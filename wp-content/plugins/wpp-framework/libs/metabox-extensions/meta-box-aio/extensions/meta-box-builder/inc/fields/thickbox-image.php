<?php

class MBB_ThickboxImage extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'std',
		'max_file_uploads' => 'number',
		'force_delete' => array(
			'type' 	=> 'checkbox',
			// 'label'	=> 'Force Delete?'
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone' => array(
			'type' 	=> 'checkbox',
			'size'	=> 'wide'
		)
	);
}

new MBB_ThickboxImage;