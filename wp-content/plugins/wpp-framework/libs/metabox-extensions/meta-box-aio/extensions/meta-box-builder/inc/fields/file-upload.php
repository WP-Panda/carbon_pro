<?php

class MBB_File_Upload extends MBB_Field
{
	public $basic = array(
		'id',
		'name',
		// 'label_description',
		'desc' => array(
			'type' 	=> 'textarea',
		),
	);
	public function __construct()
	{
		$status_field = Meta_Box_Attribute::get_attribute_content( 'status_field' );
		$this->basic['max_file_uploads'] = array(
			'type'	=> 'number',
			'label' => 'Maximum number of files',
			'attrs' => array( 'min' => 0, 'max' => 99 )
		);

		$this->basic['max_status'] = array(
			'type'    => 'custom',
			'content' => $status_field,
		);
		$this->basic['force_delete'] = array(
			'type'    => 'checkbox',
		);
		$this->basic['required'] = array(
			'type'    => 'checkbox',
		);
		$this->basic['clone'] = array(
			'type'    => 'checkbox',
		);
		parent::__construct();
	}
    public function register_fields() {
        parent::register_fields();

		unset( $this->appearance['size'] );
		unset( $this->appearance['placeholder'] );
    }
}

new MBB_File_Upload;