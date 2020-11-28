<?php

class MBB_TextList extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );
		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'options' => array(
				'type' => 'custom',
				'content' => $options,
				'size'	=> 'wide'
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

new MBB_TextList;