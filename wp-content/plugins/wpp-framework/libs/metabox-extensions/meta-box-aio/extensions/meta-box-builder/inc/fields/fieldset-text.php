<?php

class MBB_FieldsetText extends MBB_Field
{
	public function __construct()
	{
		$options = Meta_Box_Attribute::get_attribute_content( 'key_value', 'options' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'rows' => 'number',
			'options' => array(
				'type' => 'custom',
				'content' => $options,
				'size'	=> 'wide'
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' 	=> 'checkbox',
				'size'	=> 'wide'
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

new MBB_FieldsetText;