<?php

class MBB_File extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
		'max_file_uploads' => array(
			'type'	=> 'number',
			'label' => 'Maximum number of files',
			'attrs' => array( 'min' => 0, 'max' => 99 )
		),
		'force_delete' => array(
			'type' 	=> 'checkbox',
		),
		'required' => array(
			'type' => 'checkbox',
		),
		'clone'	=> array(
			'type' 	=> 'checkbox',
		),

	);
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_File;