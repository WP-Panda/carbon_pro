<?php

class MBB_Select extends MBB_Field {

	public function __construct() {
		$options = Meta_Box_Attribute::get_attribute_content( 'options' );

		$this->basic = array(
			'id',
			'name',
			// 'label_description',
			'desc' => array(
				'type' 	=> 'textarea',
			),
			'options' => array(
				'type' => 'textarea',
				'label'	=> 'Options <span class="tooltip"><span class="dashicons dashicons-editor-help"></span><b title="Enter each choice on a new line.
					For more control, you may specify both a value and label like this:
					red: Red"></b></span>',
				// 'type' => 'custom',
				// 'content' => $options,
				// 'size'	=> 'wide',
			),
			'select_all_none' => array(
				'type' => 'checkbox',
			),
			'multiple' => array(
				'type' => 'checkbox',
				'attrs' => array('ng-change', 'toggleMultiple()'),
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

		if ( isset( $this->appearance['size'] ) ) {
			unset( $this->appearance['size'] );
		}
    }

}

new MBB_Select;
