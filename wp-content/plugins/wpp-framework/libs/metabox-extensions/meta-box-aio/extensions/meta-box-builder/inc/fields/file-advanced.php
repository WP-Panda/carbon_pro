<?php

class MBB_FileAdvanced extends MBB_Field
{
	public function __construct()
	{
		$status_field = Meta_Box_Attribute::get_attribute_content( 'status_field' );
		$this->basic = array(
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
	        'mime_type' => array(
	            'type'    => 'number',
	            'attrs' => array( 'size' => 99 )
	        ),
			'force_delete' => array(
				'type' => 'checkbox',
			),
			'max_status' => array(
				'type'    => 'custom',
				'content' => $status_field,
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' => 'checkbox',
			),
		);
		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_FileAdvanced;