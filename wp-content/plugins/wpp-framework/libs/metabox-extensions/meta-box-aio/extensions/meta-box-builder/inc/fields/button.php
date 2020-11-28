<?php
class MBB_Button extends MBB_Field {
	public function __construct() {
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'std'      => array(
				'type' => 'text',
				'label' => 'Button text',
			),
			'required' => array(
				'type' => 'checkbox',
			),
			'clone' => array(
				'type' => 'checkbox',
				'size' => 'wide'
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
new MBB_Button;