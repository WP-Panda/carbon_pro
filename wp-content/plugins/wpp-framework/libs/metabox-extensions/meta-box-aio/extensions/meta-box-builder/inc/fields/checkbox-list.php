<?php

class MBB_Checkbox_List extends MBB_Field {
	public function __construct() {
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			null,
			'options' => array(
				'type' => 'textarea',
				'label'	=> 'Options <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Enter each choice on a new line.
					For more control, you may specify both a value and label like this:
					red: Red"></b></span>',
				// 'type' => 'custom',
				// 'content' => $options,
				// 'size'	=> 'wide'
			),
			'inline' => array(
				'type' => 'checkbox',
				'size' => 'wide'
			),
			'select_all_none' => array(
				'type' => 'checkbox',
				'size' => 'wide'
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

new MBB_Checkbox_List;
